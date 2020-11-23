<?php
#app/Http/Admin/Controllers/BannerController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerMagazine;
use App\Models\Company;
use App\Models\Magazine;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Form\Field\Image;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;

class BannerMagazineController extends Controller
{
    use HasResourceActions;
    public $arrType = ['1' => 'Slider', '2' => 'Show-Image', '3'=> 'Advertisement'];
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(trans('language.admin.banner_manager'))
            ->description(' ')
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('language.admin.banner_manager'));
            $content->description(' ');
            $bannerModel = BannerMagazine::findOrFail($id);
            if($bannerModel!= null && $bannerModel->company_id != $this->getUserCompany()[0]->id)
            {
                if (!$this->checkSuperUser()) {
                      abort(404);
                }
            }
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(trans('language.admin.banner_manager'))
            ->description(' ')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BannerMagazine);
        $grid->id('ID')->sortable();
        $grid->image(trans('language.admin.image'))->image('', 50);
        if(!$this->checkSuperUser()){
            $grid->company_id = $this->getUserCompany()[0]->id;
        }
        $grid->magazine()->title(trans('language.magazine.title'));
        $grid->status(trans('language.admin.status'))->switch();
      //  $grid->sort(trans('language.admin.sort'))->sortable();
        $grid->disableRowSelector();
        $grid->disableFilter();
        $grid->tools(function ($tools) {
            $tools->disableRefreshButton();
        });
        $grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        $grid->model()->orderBy('id', 'desc');
        return $grid;
    }
    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form($id = null)
    {  
        try{
        $form = new Form(new BannerMagazine);
        $form->image('image', trans('language.admin.image'))->uniqueName()->move('banner')->removable();
        $form->radio('type', trans('language.admin.banner_type'))->options($this->arrType)->default('0');
       if (!$this->checkSuperUser()) {
           abort(404);
  //          $companies = Company::pluck('name','id')->all();
    //        $form->select('company_id', trans('admin.company'))->options($companies)
      //      ->rules('required');
       }
        $magazines = Magazine::where('active', 1)->pluck('title', 'id'); 
        $form->select('magazine_id', trans('language.magazine.title'))->options($magazines)
            ->rules('required');
        $form->switch('status', trans('language.admin.status'))->rules('required');
     //   $form->number('sort', trans('language.admin.sort'))->rules('numeric|min:0')->default(0);
         $form->disableViewCheck();
        $form->disableEditingCheck();
        
        $form->saving(function (Form $form)  {
           $form->model()->path = Storage::disk(config('admin.upload.disk'))->url('');
           $form->model()->company_id = $this->getUserCompany()[0]->id;
           
        });
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        return $form;
    } catch(\Exception $ex){

    }
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description(' ')
            ->body($this->form($id));
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(BannerMagazine::findOrFail($id));
        return $show;
    }

    public function update($id, Request $request)
    {
        $data = $request->all();
        $banner = BannerMagazine::findOrFail($id);
        
        if(isset($data['type']))
           $banner->type = $data['type'];
          
        if(isset($data['magazine_id']))
           $banner->magazine_id = $data['magazine_id'];
          
           if(isset($request->status))
           $banner->status =$request->status == 'off'? 0 : 1;
           
        if(isset($request->image)){
        $banner->path =  Storage::disk(config('admin.upload.disk'))->url('');
        $uploadedImage = new Image($request->image) ;
        $uploadedImage->uniqueName();
        $uploadedImage->move('bannerMagazine');
        $banner->image = $uploadedImage->prepare($request->image);
        }
        
      /*if (!$this->checkSuperUser()) {
          $banner->company_id =  $this->getUserCompany()[0]->id;
      }else{
          $banner->company_id = $data['company_id'];
      }*/
        $banner->save();
        return $this->detail($banner->id);
    }

    function admin_toastr($message = '', $type = 'success', $options = [])
    {
        $toastr = new MessageBag(get_defined_vars());

        session()->flash('toastr', $toastr);
    }
    
    function getMagazineImages(Request $request, $id){
      if(!is_numeric($id) || !is_numeric($request->type)){
          return $this->sendError([], 400);
      }
       $type = $request->type;
    
       $banners = BannerMagazine::where('status', true)
                    ->where('type', $type)
                    ->where('magazine_id', $id)
                    ->get();
       return $this->sendResponse($banners,200);
    }
    

}
