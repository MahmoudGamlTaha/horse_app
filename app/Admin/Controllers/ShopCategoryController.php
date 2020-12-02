<?php
#app/Http/Admin/Controllers/ShopCategoryController.php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Language;
use App\Models\ShopCategory;
use App\Models\ShopCategoryDescription;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use DB;

class ShopCategoryController extends Controller
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

            $content->header(trans('language.admin.shop_category'));
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

            $content->header(trans('language.admin.shop_category'));
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

            $content->header(trans('language.admin.shop_category'));
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
        return Admin::grid(ShopCategory::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->image(trans('language.category.image'))->image('', 50);
            $grid->name(trans('language.category.name'))->display(function () {
                return ShopCategory::find($this->id)->getName();
            });
            $grid->parent(trans('language.category.parent'))->display(function ($parent) {
                return (ShopCategory::find($parent)) ? ShopCategory::find($parent)->getName() : '';
            })->sortable();
            $grid->top(trans('language.category.top'))->switch()->sortable();
            $grid->status(trans('language.category.status'))->switch()->sortable();
            $grid->sort(trans('language.category.sort'))->editable();
            $grid->disableExport();
            $grid->model()->orderBy('id', 'desc');
            $grid->disableRowSelector();
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
    protected function form()
    {
        return Admin::form(ShopCategory::class, function (Form $form) {
//Language
            $arrParameters = request()->route()->parameters();
            $idCheck       = (int) end($arrParameters);
            $languages     = Language::getLanguages();
            $arrFields     = array();
            $company       = $this->getUserCompany()[0];
            
            foreach ($languages as $key => $language) {
                if ($idCheck) {
                    $langDescriptions = ShopCategoryDescription::where('shop_category_id', $idCheck)->where('lang_id', $language->id)->first();
                }
               
                //$form->html('<b>' . $language->name . '</b> <img style="height:25px" src="/' . config('filesystems.disks.path_file') . '/' . $language->icon . '">');
                $form->html('<b>' . $language->name . '</b>');
                $form->text($language->code . '__name', trans('language.category.name'))->rules('required', ['required' => trans('validation.required')])->default(!empty($langDescriptions->name) ? $langDescriptions->name : null);
                $form->text($language->code . '__keyword', trans('language.category.keyword'))->default(!empty($langDescriptions->keyword) ? $langDescriptions->keyword : null);
                $form->text($language->code . '__description', trans('language.category.description'))->rules('max:300', ['max' => trans('validation.max')])->default(!empty($langDescriptions->description) ? $langDescriptions->description : null);
                $arrFields[] = $language->code . '__name';
                $arrFields[] = $language->code . '__keyword';
                $arrFields[] = $language->code . '__description';
                $form->divide();
            }
            $form->ignore($arrFields);
            $form->model()->company_id = $company->id; 
            $arrCate = null;
            $companies = null;
            if(!$this->checkSuperUser()){
            $arrCate = (new ShopCategory)->getTreeCategoriesForCompany($form->model()->company_id);
            }else{
                $arrCate = (new ShopCategory)->getTreeCategories();
                $companies = Company::where('active', 1)->pluck('name','id');
            }
            if($arrCate == null){
                $arrCate = array();
            }
            $arrCate = ['0' => '== ROOT =='] + $arrCate;
            $form->select('parent', trans('language.category.parent'))->options($arrCate);
            if($this->checkSuperUser()){
            $form->select('company_id',trans('language.admin.category'))->options($companies);
           }
            $form->image('image', trans('language.category.image'))->uniqueName()->move('category')->removable();
            $form->number('sort', trans('language.category.sort'))->rules('numeric|min:0')->default(0);
            $form->switch('top', trans('language.category.top'))->help(trans('language.category.help_top'))->default(0);
            $form->switch('status', trans('language.category.status'));
            $arrData = array();

            $form->saving(function (Form $form) use ($languages, &$arrData) {
                //Lang
                foreach ($languages as $key => $language) {
                    $arrData[$language->code]['name']        = request($language->code . '__name');
                    $arrData[$language->code]['keyword']     = request($language->code . '__keyword');
                    $arrData[$language->code]['description'] = request($language->code . '__description');

                }
                //end lang
            });

            $form->saved(function (Form $form) use ($languages, &$arrData) {
                $idForm = $form->model()->id;

                //Language
                foreach ($languages as $key => $language) {
                    if (array_filter($arrData[$language->code], function ($v, $k) {
                        return $v != null;
                    }, ARRAY_FILTER_USE_BOTH)) {
                        $arrData[$language->code]['shop_category_id'] = $idForm;
                        $arrData[$language->code]['lang_id']          = $language->id;
                        ShopCategoryDescription::where('lang_id', $arrData[$language->code]['lang_id'])->where('shop_category_id', $arrData[$language->code]['shop_category_id'])->delete();
                        ShopCategoryDescription::insert($arrData[$language->code]);
                    }
                }
                //End language

                $file_path_admin = config('filesystems.disks.admin.root');
                $statusWatermark = \Helper::configs()['watermark'];
                $fileWatermark   = $file_path_admin . '/' . \Helper::configsGlobal()['watermark'];
                try {
                    //image primary
                    \Helper::processImageThumb($pathRoot = $file_path_admin, $pathFile = $form->model()->image, $widthThumb = 250, $heightThumb = null, $statusWatermark, $fileWatermark);

                } catch (\Exception $e) {
                    echo $e->getMessage();
                }

            });
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
        });
    }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(ShopCategory::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }

    public function getCategories(Request $request, $id){
        if(!is_numeric($id)){
            return $this->sendError("estable category Error", 400);
        }
    
         $categories = ShopCategory::query()->where('status', true)
       //  ->groupBy('parent')
                      ->where('company_id', $id)
                    
                      ->paginate(20);

       return $this->sendResponse($categories, 200);               
    }
    
    public function getTreeCategories(Request $request, $id){
        if(!is_numeric($id)){
            return $this->sendError("estable category Error", 400);
        }
    
         $categories = ShopCategory::query()->where('status', true)
                      ->where('company_id', $id)->paginate(20);

       return $this->sendResponse($categories, 200);               
    }
    public function update(Request $request, $id ){
        $arr = $request->all();
        
        $model = ShopCategory::findOrFail($id);
         
        if(isset($arr['status'])){
            $model->status = $arr['status']== 'off'? 0 : 1;  
        }
        if(isset($arr['home_page'])){
            $model->home_page = $arr['home_page']== 'off'? 0 :1;
        }
        if(isset($arr['top'])){
            $model->top = $arr['top']== 'off'? 0 :1;
        }
       
        if(isset($arr['sort'])){
            $model->sort = $arr['sort'];
        }
        $languages     = Language::getLanguages();
       DB::beginTransaction();
       foreach($languages as $key => $language ){ 
       if(isset($arr[$language->code.'__name'])) {
          $descModel = ShopCategoryDescription::where('lang_id', $language->id)->where('shop_category_id', $id)->delete();
       
          $descModel = new ShopCategoryDescription();
          $descModel->name = $arr[$language->code.'__name'];
          $descModel->description = $arr[$language->code.'__description'];
          $descModel->keyword = $arr[$language->code.'__keyword'];
          $descModel->lang_id = $language->id;
          $descModel->company_id = $model->company_id;
          $descModel->shop_category_id = $id;
        //  dd($descModel);
          $descModel->save(); 
          
        
        }
    }
        $model->save();
        DB::commit();
        
        return $this->sendResponse($arr['status'], 'sucess');      
    }
}
