<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Magazine;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Form\Field\Image;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Models\MagazineDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;

class MagazineController extends Controller
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

            $content->header(trans('language.magazine.magazines'));
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

            $content->header(trans('language.magazine.manage'));
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

            $content->header(trans('language.magazine.manage'));
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
        return Admin::grid(Magazine::class, function (Grid $grid) {

            $grid->id(trans('language.admin.sort'))->sortable();
            $grid->title(trans('language.magazine.title'));
            $grid->logo(trans('language.magazine.logo'))->image('', 50);
            $grid->notes(trans('admin.notes'));
            $grid->active(trans('language.admin.status'))->switch();
            $grid->created_at(trans('language.admin.created_at'));
            $grid->model()->orderBy('id', 'desc');
            $grid->disableExport();
            $grid->disableFilter();
            $grid->disableCreation();
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
    public function getMagazineOld(Request $request){
          try{
            
                $selected = Magazine::query()->where('active', 1)

                ->get();
                return $this->sendResponse($selected, 200);
                            
          }catch(\Exception $ex){
             return $this->sendError("error", 400);
          }
    }
    public function getMagazine(Request $request){
        try{
              $lang = $request->lang_id ?? 1;
              $magazine = new Magazine();
              $selected = $magazine->getMagazines($lang);
              return $this->sendResponse($selected, 200);
                          
        }catch(\Exception $ex){
           return $this->sendError("error", 400);
        }
  }
  
    protected function form($id = null)
    {
     //   Admin::script($this->jsProcess());
        return Admin::form(Magazine::class, function (Form $form) {
            
            $company = $this->getUserCompany()[0]->id;
            if(!$this->checkSuperUser()){
                 abort(404); 
            }
            $arrParameters = request()->route()->parameters();
            $id      = (int) end($arrParameters);
                      
            $languages = Language::getLanguages();
            $arrayData = array();
            $ignoredFields = array();
            foreach($languages as $key=>$language){
                $form->html('<span>'.$language->name.'</span>');
                $mag_desc = MagazineDescription::where("lang_id", $language->id)->where("magazine_id", $id)->first();
                
                $form->text($language->code.'__title', trans('language.magazine.title'))->default(!empty($mag_desc->title)? $mag_desc->title : null);
                $form->text($language->code.'__notes', trans('Language.notes'))->default(!empty($mag_desc->notes)? $mag_desc->notes : null);
                $ignoredFields[] =    $language->code.'__notes';
                $ignoredFields[] =    $language->code.'__title';
                $form->divide();

            }
           
            $form->ignore($ignoredFields);
            $form->image('logo', trans('language.magazine.logo'));
            $form->model()->company_id =  $company ;
            $form->model()->path = Storage::disk(config('admin.upload.disk'))->url('');
            $form->switch('active', trans('language.admin.status'));
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->saving(function(Form $form) use ($arrayData, $languages){
                foreach ($languages as $key => $language) {
                     $arrayData[$language->code] = request($language->code.'__title');
                     $arrayData[$language->code] = request($language->code.'__notes');
                     $arrayData[$language->code] = $language->id;
                            
                }
            });
            $form->saved(function(Form $form) use($arrayData, $languages){
                $arrayData[$language->code] = $form->model()->id;
                foreach ($languages as $key => $language) {
                MagazineDescription::where('lang_id', $arrData[$language->code]['lang_id'])->where('magazine_id', $arrData[$language->code]['magazine_id'])->delete();
                MagazineDescription::insert($arrayData);
               }
            });
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
        });
    }

  
  public function  update(Request $request, $id){
    try{
        DB::beginTransaction();
         $magazine = Magazine::findOrFail($id);
         $company_id = $this->getUserCompany()[0]->id;
        
          if($magazine != null && $magazine->company_id != $company_id ){
            if(!$this->checkSuperUser()){
                abort(404);
            }
            if($magazine == null){
                abort(404);
            }
         }
        $arr = $request->all();
       if(isset($request->logo)){
        $uploadedImage = new Image($request->logo) ;
        $uploadedImage->uniqueName();
        $uploadedImage->move('productImage_'.$company_id);
        $magazine->logo = $uploadedImage->prepare($request->logo);
       }
       if(isset($request->notes)){
           $magazine->notes = $request->notes;
       }
       if(isset($request->title)){
        $magazine->title = $request->title; 
       }
    
       if(isset($request->active)){
           $magazine->active = $request->active == "off"?0:1;
       }
       $languages = Language::getLanguages();
       foreach ($languages as $key => $language) {
        if(!isset($arr[$language->code . '__title']))
        continue;
        $mag_desc = MagazineDescription::where('magazine_id', $id)->where('lang_id', $language->code)->first();
        if($mag_desc == null){
            $mag_desc = new MagazineDescription();
        }
        $mag_desc->title =  $arr[$language->code.'__title'];
        $mag_desc->notes = $arr[$language->code.'__notes'];
        $mag_desc->lang_id = $language->id;
        $mag_desc->magazine_id = $id;
        $mag_desc->save(); 
        if($language->id == 1){
            $magazine->title = $mag_desc->title;
            $magazine->notes = $mag_desc->notes;
        }          
   }
   
       $magazine->save();
       DB::commit();
    }catch(\Exception $e){  
        return $this->sendError($e->getMessage(), 400);
    }

  }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(Magazine::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }

}
