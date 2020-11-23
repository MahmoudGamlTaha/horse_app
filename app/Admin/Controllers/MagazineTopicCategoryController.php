<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Magazine;
use App\Models\TopicCategory;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MagazineTopicCategoryController extends Controller
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

            $content->header(trans('language.magazine.topics'));
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
        return Admin::grid(TopicCategory::class, function (Grid $grid) {

            $grid->id(trans('language.admin.sort'))->sortable();
            $grid->name(trans('language.magazine.topic'));
        
            $grid->active(trans('language.admin.status'))->switch();
            $grid->notes(trans('admin.notes'));
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
    public function getTopicMagazine(Request $request){
          try{
              
                $selected = TopicCategory::query()->where('active', 1)
                ->get();
                return $this->sendResponse($selected, 200);
                            
          }catch(\Exception $ex){
             return $this->sendError("error", 400);
          }
    }
  
    protected function form()
    {
     //   Admin::script($this->jsProcess());
        return Admin::form(TopicCategory::class, function (Form $form) {
            
            $company = $this->getUserCompany()[0]->id;
            if(!$this->checkSuperUser()){
                 abort(404); 
            }
            $form->text('name', trans('language.magazine.topic'));
            $form->text('notes', trans('Language.notes'));
            $form->model()->company_id =  $company ;
            //$form->model()->path = Storage::disk(config('admin.upload.disk'))->url('');
            $form->switch('active', trans('language.admin.status'));
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
        });
    }

  
  public function  update(Request $request, $id){
    try{
       $magazine = TopicCategory::findOrFail($id);
       $company_id = $this->getUserCompany()[0]->id;
       if(isset($request->magazine_id)){
          $product = TopicCategory::findOrFail($request->magazine_id);
          if($product != null && $product->company_id != $company_id ){
            if(!$this->checkSuperUser()){
                abort(404);
            }
            if($product == null){
                abort(404);
            }
         }
        
        $magazine->product_id = $request->product_id;

       }

       if(isset($request->title)){
        $magazine->title = $request->title; 
       }
       if(isset($request->status)){
           $magazine->status = $request->status == "Off"?0:1;
       }
      
       $magazine->save();
    }catch(\Exception $e){
        return $this->sendError($e->getMessage(), 400);
    }

  }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(TopicCategory::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }

}
