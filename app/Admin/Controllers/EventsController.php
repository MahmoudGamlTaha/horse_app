<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventsController extends Controller
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

            $content->header(trans('language.events.events'));
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

            $content->header(trans('language.events.events'));
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

            $content->header(trans('language.events.create'));
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
        return Admin::grid(Events::class, function (Grid $grid) {

            $grid->id(trans('language.admin.sort'))->sortable();
            $grid->name(trans('language.events.event'));
            $grid->image(trans('language.events.image'))->image('', 50);
            $grid->short_description(trans('language.events.short_desc'));
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
    public function getEvents(Request $request){
          try{
              
                $selected = Events::query()->where('active', 1)
                ->get();
                return $this->sendResponse($selected, 200);
                            
          }catch(\Exception $ex){
             return $this->sendError("error", 400);
          }
    }
  
    protected function form()
    {
     //   Admin::script($this->jsProcess());
        return Admin::form(Events::class, function (Form $form) {
            
            $company = $this->getUserCompany()[0]->id;
            if(!$this->checkSuperUser()){
                 abort(404); 
            }
            $form->text('name', trans('language.events.event'));
            $form->text('short_description', trans('language.events.short_desc'));
            $form->image('image', trans('language.events.image'));
            $form->url('video', trans('language.events.video'));
            $form->date('event_date', trans('language.events.date'));
            $form->ckeditor('description', trans('Language.events.description'));
            $form->model()->company_id =  $company ;
            $form->model()->path = Storage::disk(config('admin.upload.disk'))->url('');
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
       $event = Events::findOrFail($id);
       $company_id = $this->getUserCompany()[0]->id;
       if(isset($request->magazine_id)){
          $product = Events::findOrFail($request->magazine_id);
          if($product != null && $product->company_id != $company_id ){
            if(!$this->checkSuperUser()){
                abort(404);
            }

            if($product == null){
                abort(404);
            }
         }
        
        $event->product_id = $request->product_id;

       }

       if(isset($request->title)){
        $event->title = $request->title; 
       }

       if(isset($request->status)){
           $event->status = $request->status == "Off"?0:1;
       }

       if(isset($request->event_date)){
         $event->event_date = $request->event_date;
       }
      
       $event->save();
    }catch(\Exception $e){
        return $this->sendError($e->getMessage(), 400);
    }

  }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(Events::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }

}
