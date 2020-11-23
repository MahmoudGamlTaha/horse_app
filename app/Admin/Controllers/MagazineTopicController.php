<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArticleLike;
use App\Models\Language;
use App\Models\Magazine;
use App\Models\MagazineTopic;
use App\Models\MagazineTopicDescription; 
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class MagazineTopicController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('language.magazine.topic'));
            $content->description(' ');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('language.magazine.topic_edit'));
            $content->description(' ');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('language.magazine.topic_edit'));
            $content->description(' ');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(MagazineTopic::class, function (Grid $grid) {

            $grid->id(trans('language.admin.sort'))->sortable();
            $grid->title(trans('language.magazine.topic'));
            $grid->image(trans('language.magazine.image'))->image('',50);
            $grid->notes(trans('admin.notes'));
            $grid->active(trans('language.admin.status'))->switch();
            $grid->created_at(trans('language.admin.created_at'));
            $grid->model()->orderBy('id', 'desc');
            $grid->disableExport();
            $grid->disableFilter();
            $grid->actions(function ($actions) {
                $actions->disableView();
            });
            $grid->tools(function ($tools) {
                $tools->disableRefreshButton();
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function MagazineTopicold(Request $request, $id){
          try{
              if(!is_numeric($id)){
                return $this->sendError("error magazine", 400);      
              }
                $selected = MagazineTopic::query()->where('active', 1)
                            ->where('magazine_id', $id)
                ->paginate(20);
                return $this->sendResponse($selected, 200);
                            
          }catch(\Exception $ex){
             return $this->sendError("error", 400);
          }
    }
    public function MagazineTopic(Request $request, $id){
        try{
            if(!is_numeric($id)){
              return $this->sendError("error magazine", 400);      
            }
            $lang_id = $request->lang_id ?? 1;
            $magazineTopic = new MagazineTopic();
              $selected = $magazineTopic->getMagazineTopic($id, $lang_id);
              return $this->sendResponse($selected, 200);
                          
        }catch(\Exception $ex){
           return $this->sendError("error", 400);
        }
  }
  
    protected function form()
    {
     //   Admin::script($this->jsProcess());
        return Admin::form(MagazineTopic::class, function (Form $form) {
            
            $company = $this->getUserCompany()[0]->id;
            if(!$this->checkSuperUser()){
                 abort(404); 
            }
            $magazine = Magazine::where('active', 1)->pluck('title', 'id');
            
            $form->image('image', trans('language.magazine.image'));//->image('',50);
            $form->select('magazine_id', trans('language.magazine.title'))->options($magazine);
            $form->date('date', trans('language.magazine.article_date'))->rules("required");
            $form->model()->company_id =  $company ;
            $form->model()->path = Storage::disk(config('admin.upload.disk'))->url('');
            $form->switch('active', trans('language.admin.status'));
            $arrParameters = request()->route()->parameters();
             $idCheck       = (int) end($arrParameters);
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $arrayData = array();
            $ignoredFields  = array();
            $languages = Language::getLanguages();
            foreach($languages as $key=>$language){
                $form->html('<span>'.$language->name.'</span>');
                $mag_desc = MagazineTopicDescription::where("lang_id", $language->id)->where("magazine_topic_id", $idCheck)->first();
                
                $form->text($language->code.'__title', trans('language.magazine.topic_title'))->default(!empty($mag_desc->title)? $mag_desc->title : null);
                $form->textarea($language->code.'__content', trans('Language.contact_form.content'))->default(!empty($mag_desc->content)? $mag_desc->content : null);
                $ignoredFields[] =    $language->code.'__content';
                $ignoredFields[] =    $language->code.'__title';
                $form->divide();
            }
            
            $form->saving(function(Form $form) use (&$arrayData, $languages){
                foreach ($languages as $key => $language) {
                     $arrayData[$language->code]["title"] = request($language->code.'__title');
                     $arrayData[$language->code]["content"] = request($language->code.'__content');
                     $arrayData[$language->code]["lang_id"] = $language->id;
                     if($language->id == 1){
                        $form->model()->title = $arrayData[$language->code]["title"] ;
                     }
                }
            });
            $form->ignore($ignoredFields);
            $form->saved(function(Form $form) use(&$arrayData, $languages){
              
                foreach ($languages as $key => $language) {
                    $arrayData[$language->code]["magazine_topic_id"] = $form->model()->id;
                    $arrayData[$language->code]["magazine_id"] = $form->model()->magazine_id;
                    $arrayData[$language->code]["company_id"] =  $this->getUserCompany()[0]->id;
                MagazineTopicDescription::where('lang_id', $arrayData[$language->code]["lang_id"])->where('magazine_id', $arrayData[$language->code]['magazine_id'])->delete();
               }
               MagazineTopicDescription::insert($arrayData);
            });
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
        });
    }

  
  public function  update(Request $request, $id){
    try{
       $magazine = MagazineTopic::findOrFail($id);
       $company_id = $this->getUserCompany()[0]->id;
       if(isset($request->magazine_id)){
          $magazineTopic = MagazineTopic::findOrFail($id);
          if($magazineTopic != null && $magazineTopic->company_id != $company_id ){
            if(!$this->checkSuperUser()){
                abort(404);
            }
            if($magazineTopic == null){
                abort(404);
            }
         } 
    //    $magazine->magazine_id = $request->magazine_id;

       }

       if(isset($request->title)){
        $magazine->title = $request->title; 
       }
       if(isset($request->status)){
           $magazine->status = $request->status == "Off"?0:1;
       }
       if(isset($request->date)){
           $magazine->date = $request->date;
       }
       $languages = Language::getLanguages();
       $arr = $request->all();
       $mag_desc = new MagazineTopicDescription();
       foreach ($languages as $key => $language) {
        if(!isset($arr[$language->code . '__title']))
        continue;
        $mag_desc = MagazineTopicDescription::where('magazine_topic_id', $id) 
                         ->where('lang_id', $language->id)
                         ->first();
        if($mag_desc == null){
            $mag_desc = new MagazineTopicDescription();
        }
        $mag_desc->title =  $arr[$language->code.'__title'];
        $mag_desc->content = $arr[$language->code.'__content'];
        $mag_desc->lang_id = $language->id;
        $mag_desc->magazine_topic_id = $id;
        $mag_desc->magazine_id = $arr['magazine_id'];
        $mag_desc->company_id =  $company_id ;
        $mag_desc->save(); 
        if($language->id == 1){
            $magazine->title = $mag_desc->title;
            $magazine->content = $mag_desc->content;
        }          
   }
       $magazine->save();
    }catch(\Exception $e){
        //dd($e->getMessage());
        return $this->sendError($e->getMessage(), 400);
    }

  }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(MagazineTopic::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }
    public function getTotalArticleLike(Request $request, $articleId){ // need upgrade
         if(!is_numeric($articleId)){
           return $this->sendError("data error", 400);
         }

        $data = ArticleLike::where('article_id', $articleId)
                          ->selectRaw('sum(likes) likes')
                          ->groupBy('article_id')
                          ->first(); 
       return $this->sendResponse($data, 200);
     }

     public function LikeArticle(Request $request, $articleId){
        if(!is_numeric($articleId) || !is_numeric($request->header('user-id'))){
            return $this->sendError("data error", 400);
          }
          try{
              MagazineTopic::findOrFail($articleId);
              $user_id = $request->header('user-id');
              $like = ArticleLike::query()->where('article_id', $articleId)
                                  ->where('user_id', $user_id)->first();
          if($like == null){
               $like = new ArticleLike();
               $like->article_id = $articleId;
               $like->likes = 1;
               $like->user_id = $user_id;
               $like->save();

            }
            return $this->sendResponse($like, 200);
          }catch(\Exception $e){
              return $this->sendError($e->getMEssage());
          }
     }

}
