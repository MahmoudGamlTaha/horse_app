<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Events;
use App\Models\Video;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
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

            $content->header(trans('language.video.videos'));
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

            $content->header(trans('language.video.videos'));
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

            $content->header(trans('language.video.create'));
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
        return Admin::grid(Video::class, function (Grid $grid) {

            $grid->id(trans('language.admin.sort'))->sortable();
            $grid->name(trans('language.admin.name'));
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
    public function getVideo(Request $request){
          try{
              
                $selected = Video::query()->where('active', 1);
               
                 if($request->company_id != null && is_numeric($request->company_id)){
                    $selected = $selected->where('company_id', $request->company_id);
                 }
                 $selected  = $selected->paginate(20);
                return $this->sendResponse($selected, 200);
                            
          }catch(\Exception $ex){
             return $this->sendError("error-video", 400);
          }
    }
  
    protected function form()
    {
     //   Admin::script($this->jsProcess());
        return Admin::form(Video::class, function (Form $form) {
            
            $company = $this->getUserCompany()[0]->id;
            if(!$this->checkSuperUser()){
                 abort(404); 
            }
            $form->text('name', trans('language.admin.name'));
            $form->text('short_description', trans('language.events.short_desc'));
            $form->url('url', trans('language.events.video'));
            $form->model()->company_id =  $company ;
            $form->model()->path = Storage::disk(config('admin.upload.disk'))->url('');
            $form->switch('active', trans('language.admin.status'));
            if ($this->checkSuperUser()) {
                $companies = Company::pluck('name','id')->all();
                $form->select('company_id', trans('admin.company'))->options($companies);
            }
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
        });
    }

  
  public function  update(Request $request, $id){
    try{
       $video = Video::findOrFail($id);
       $company_id = $this->getUserCompany()[0]->id;
          if($video != null && $video->company_id != $company_id ){
            if(!$this->checkSuperUser()){
                abort(404);
            }

            if($video == null){
                abort(404);
            }
         }

        if(isset($request->url)){
           $video->url = $request->url;
        }
        if(isset($request->short_description)){
            $video->short_description = $request->short_description;
        }
       if(isset($request->name)){
        $video->name = $request->name; 
       }
       //return $request->all();
       if(isset($request->active)){
           $video->active = $request->active == "off"?0:1;
       }
       if ($this->checkSuperUser()) {
        $video->company_id = $request->company_id;
      }else{
        $video->company_id =  $this->getUserCompany()[0]->id;
      }
      
       $video->save();
    }catch(\Exception $e){
        return $this->sendError($e->getMessage(), 400);
    }

  }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(Video::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }

}
