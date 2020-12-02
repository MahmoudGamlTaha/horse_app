<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Language;
use App\Models\ShopAttributeDetail;
use App\Models\ShopAttributeGroup;
use App\Models\ShopBrand;
use App\Models\ShopCategory;
use App\Models\ShopProduct;
use App\Models\ShopProductDescription;
use App\Models\ShopVendor;
use App\Models\UofmGroups;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Models\ProductPriceList;
use App\Models\ShopProductImage;
use App\Models\ShopProductLike;
use App\Modules\Api\Product;
use Illuminate\Support\Facades\Storage;
use Encore\Admin\Form\Field\Image;
use DB;

class ShopProductController extends Controller
{
    use HasResourceActions;
    public $arrType = ['0' => 'Default', '1' => 'New', '2' => 'Featured'];

    /**zz
     * Index interface.
     * sprint 3
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('language.admin.product_manager'));
            $content->description(' ');  
            $content->body($this->grid());

        });
    }//allha 
    public function update(Request $request, $id ){
        $arr = $request->all();
        try{
         //  dd( $arr);
            DB::beginTransaction();
            $shopProductModel = ShopProduct::findOrFail($id);
            if(array_key_exists('image', $arr) && $arr['image'] == '_file_del_'){
               /* dd($arr);
                $path = $shopProductModel->path ."". $shopProductModel->image;
                $shopProductModel->image = null;
                $shopProductModel->path = null;
                $shopProductModel->save();
                $path = parse_url($path)['path']; 
                if(app()->isLocal()){
                    $path = public_path($path);
                    str_replace("/", "\\",$path);
                }else{
                    $path = base_path($path);
                    str_replace("/", "\\",$path);
                }
                unlink($path);*/
                return $this->sendResponse("sucess", 200);
            }
			
            $shopProductModel->sku = $arr['sku'];
            $shopProductModel->price = $arr['price'];
            $shopProductModel->cost = $arr['cost'];
            $shopProductModel->category_id = $arr['category_id'];
        //   $shopProductModel->vendor_id = $arr['vendor_id'];
            $shopProductModel->status = $arr['status'] == 'on' ? 1 : 0;
            $shopProductModel->stock  = $arr['stock'];
            $shopProductModel->path = Storage::disk(config('admin.upload.disk'))->url('');
            $shopProductModel->type = $arr['type'];
            $shopProductModel->reserve = $arr['reserve'] == 'on' ? 1 : 0;; 
      
          $company = null;
            if($this->checkSuperUser()){
                $company = $arr['company_id'];
            }
            else {
                $company = $this->getUserCompany()[0]->id;
                if($company != $shopProductModel->company_id){
                   abort(404); 
                }
            }

            if(array_key_exists('image', $arr)){
                $uploadedImage = new Image($arr['image']) ;
                $uploadedImage->uniqueName();
                $uploadedImage->move('productImage_'.$company);
//                dd($uploadedImage->prepare($arr['image']));
                $shopProductModel->image = $uploadedImage->prepare($arr['image']);
            }
           
            $shopProductModel->company_id = $company;
         //   $shopProductModel->brand_id = $arr['brand_id'];
            $shopProductModel->date_available = $arr['date_available'];

            $shopProductModel->save();
            $groups = UofmGroups::all()->pluck('code','name')->toArray();
            foreach($groups as $key => $code){
                if(!isset($arr[$code]) || (isset($arr[$code]) && $arr[$code] == 0))
                    continue;
            ProductPriceList::where('product_id', $shopProductModel->id)->where('uof_id', $arr[$code])->delete();
            $productPriceList = new ProductPriceList();
            $productPriceList->product_id =  $shopProductModel->id;
            $productPriceList->uof_id = $arr[$code];
            $productPriceList->price =  $shopProductModel->price;
            $productPriceList->company_id = $company;
            $productPriceList->save();
            }
            if(array_key_exists('images', $arr)){
                $ImageData = array();
                foreach($arr['images'] as $metaFile) {
                   
                    if(!isset($metaFile['image'])){
                        if(isset($metaFile['id']) && isset($metaFile['_remove_'])){
                            if($metaFile['_remove_'] == 1){
                                
                                $shopImage = ShopProductImage::find($metaFile['id']);
                                $fullPath = $shopImage->path .''.$shopImage->image;
                                unlink($fullPath);
                                $shopImage->delete();
                                ShopProductImage::destroy($shopImage->id);   
                            }
                        }
                        continue;
                    }
                    $image = $metaFile['image'];
                    $uploadedImage = new Image($image) ;
                    $uploadedImage->uniqueName();
                    $uploadedImage->move('productImage_'.$company);
                    $relativePath = $uploadedImage->prepare($image);
                    $data = array('path'=>$shopProductModel->path,
                     'image'=> $relativePath,'company_id'=>$company,'status'=> 1, 'product_id'=> $shopProductModel->id);
                     array_push($ImageData, $data);
                }
                ShopProductImage::insert($ImageData);
            }
        
            $languages = Language::getLanguages();
            $arrData = array();
            
            foreach ($languages as $key => $language) {
                $arrData[$language->code]['lang_id']    = $language->id;
                $arrData[$language->code]['product_id']    = $id;
                if(!isset($arr[$language->code . '__name']))
                continue;
                $arrData[$language->code]['company_id']  = $company; 
                $arrData[$language->code]['name']        = $arr[$language->code . '__name'];
                $arrData[$language->code]['keyword']     = $arr[$language->code . '__keyword'];
                $arrData[$language->code]['description'] = $arr[$language->code . '__description'];
            // if(ShopProductDescription::where('lang_id', $arrData[$language->code]['lang_id'])->exists()){
                    ShopProductDescription::where('lang_id', $arrData[$language->code]['lang_id'])->where('product_id', $arrData[$language->code]['product_id'])->delete();
                //}
                ShopProductDescription::insert($arrData[$language->code]);
        }
            DB::commit();
              return $this->edit($id);
    }catch(\Exception $e){
        return $e->getMessage();
    }
        
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
            $content->header(trans('language.admin.product_manager'));
            $content->description(' ');

            $content->body($this->form($id)->edit($id));
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

            $content->header(trans('language.admin.product_manager'));
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
        return Admin::grid(ShopProduct::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            if($this->checkSuperUser()){
            $grid->company()->name(trans('admin.company')); //sprint 1
            }
            $grid->image(trans('language.admin.image'))->image('', 50);
            $grid->sku(trans('language.product.sku'))->sortable();
            $grid->name(trans('language.product.product_name'))->sortable();
            $grid->category()->name(trans('language.categories'));
            $grid->cost(trans('language.product.price_cost'))->display(function ($price) {
                return number_format($price);
            });
            $grid->price(trans('language.product.price'))->display(function ($price) {
                return number_format($price);
            });
            $arrType = $this->arrType;
            $grid->type(trans('language.product.product_type'))->display(function ($type) use ($arrType) {
                $style = ($type == 1) ? 'class="label label-success"' : (($type == 2) ? '  class="label label-danger"' : 'class="label label-default"');
                return '<span ' . $style . '>' . $arrType[$type] . '</span>';
            });
            $grid->status(trans('language.admin.status'))->switch();
            $grid->created_at(trans('language.admin.created_at'));
            $grid->model()->orderBy('id', 'desc');
            //$grid->disableExport();
            $grid->actions(function ($actions) {
                $actions->disableView();
            });

            $grid->tools(function ($tools) {
                $tools->append('<div class="pull-right">
            <div class="btn-group pull-right" style="margin-right: 10px">
             <a href="' . route('productImport') . '" class="btn btn-sm btn-success" title="New">
                <i class="fa fa-save"></i><span class="hidden-xs">&nbsp;&nbsp;&nbsp;' . trans('language.product.import_multi') . '</span>
            </a>
          </div>
        </div>');
            });
        //    print_r(session('locale_id'));
        //die;
            $grid->model()->leftJoin('shop_product_description', 'shop_product_description.product_id', '=', 'shop_product.id')
                ->where('lang_id', session('locale_id'));
            //$grid->expandFilter();
            if(!$this->checkSuperUser())
            {
              $grid->model()
                 ->where('shop_product.company_id',$this->getUserCompany()[0]->id);
            }
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('name', trans('language.product.name'));
                $filter->like('sku', trans('language.product.sku'));
                $filter->like('company.name', trans('admin.company'));

            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null)
    {
        return Admin::form(ShopProduct::class, function (Form $form) use ($id) {
            $languages = Language::getLanguages();
            $form->tab(trans('language.product.product_info'), function ($form) use ($languages) {
//Language
                $arrParameters = request()->route()->parameters();
                $idCheck       = (int) end($arrParameters);

                $arrFields = array();
             //   print_r($idCheck);
                 $once = false;
                 $form->text('sku', trans('language.product.sku'));
                foreach ($languages as $key => $language) {
                    if ($idCheck /* product_id*/) {
                        $langDescriptions = ShopProductDescription::where('product_id', $idCheck)->where('lang_id', $language->id)->first();
                    }
                    if ($languages->count() > 1) { // no need if one language used
                     //   if($language->direction =='RTL')
                       //     $form->html('<b>' . $language->name . '</b>&nbsp; <img style="height:25px;float:right;" src="/' . config('filesystems.disks.path_file') . '/' . $language->icon . '">');
                        //else
                        //$form->html('<b>' . $language->name . '</b>&nbsp; <img style="height:25px;" src="/' . config('filesystems.disks.path_file') . '/' . $language->icon . '">');
                    }
                    if(!$once) // print it one time independent from language just layout
                    {
                        $form->currency('price', trans('language.product.price'))->symbol('EGP')->options(['digits' => 0]);
                        $form->currency('cost', trans('language.product.price_cost'))->symbol('EGP')->options(['digits' => 0]);
                        $once = true;
                    }

                    $form->html("<span>".$language->code."</span>");
                    if(!$this->checkSuperUser())
                    {
                      $form->model()->company_id = $this->getUserCompany()[0]->id;
                    }
                   
                    $form->text($language->code . '__name', trans('language.product.product_name'))->rules('required', ['required' => trans('validation.required')])->default(!empty($langDescriptions->name) ? $langDescriptions->name : null);
                   
                    $form->text($language->code . '__keyword', trans('language.admin.keyword'))->default(!empty($langDescriptions->keyword) ? $langDescriptions->keyword : null);
                   
                    $form->textarea($language->code . '__description', trans('language.admin.description'))->rules('max:300', ['max' => trans('validation.max')])->default(!empty($langDescriptions->description) ? $langDescriptions->description : null);
                   // $form->ckeditor($language->code . '__content', trans('language.admin.content'))->default(!empty($langDescriptions->content) ? $langDescriptions->content : null)->rules('required');
                    $arrFields[] = $language->code . '__name';
                    $arrFields[] = $language->code . '__keyword';
                    $arrFields[] = $language->code . '__description';
                    $arrFields[] = $language->code . '__content';
                    $form->ignore($arrFields);
                    $form->divide();
                }
          
                if(!$this->checkSuperUser())
                {
               //   $arrVendor = ShopVendor::where('company_id', $this->getUserCompany()[0]->id)->pluck('name', 'id')->all();
                  $arrCate = (new ShopCategory)->getTreeCategoriesForCompany($this->getUserCompany()[0]->id);
                }
                else{
            //        $arrVendor = ShopVendor::pluck('name','id')->all();
                    $companies = Company::pluck('name','id');
                    $arrCate = (new ShopCategory)->getTreeCategories();
                }
          //      $arrVendor = ['0' => '-- ' . trans('language.vendor') . ' --'] + $arrVendor;
                
                if($arrCate!=null || sizeof($arrCate) > 0)
                {
                    
                  $form->select('category_id', trans('language.admin.shop_category'))->options($arrCate)
                    ->rules('required');
                }
                if(isset($companies))
                {
                    $form->divide();
                    $form->select('company_id', trans('admin.company'))->options($companies)
                   ->rules('required');
                }
                $form->image('image', trans('language.admin.image'))->uniqueName()->move('product');
                $form->number('stock', trans('language.product.stock'))->rules('numeric|min:0')->default('0'); //sprint 1
            
            //    $form->select('brand_id', trans('language.brands'))->options($arrBrand)->default('0')
              //      ->rules('required');
             //  $form->select('vendor_id', trans('language.vendor'))->options($arrVendor)->default('0')
               //     ->rules('required');
                $form->switch('status', trans('language.admin.status'));
                $form->switch('reserve', trans('language.admin.shapwa'));
                $form->number('sort', trans('language.admin.sort'))->rules('numeric|min:0')->default(0);
                $form->divide();
                $form->radio('type', trans('language.product.product_type'))->options($this->arrType)->default('0');
                $form->datetime('date_available', trans('language.date_available'))->help(trans('language.default_available'));

            })->tab(trans('language.admin.sub_image'), function ($form) {
                $form->hasMany('images', ' ', function (Form\NestedForm $form) {
                    $form->image('image', trans('language.admin.sub_image'))->uniqueName()->move('product_images');
                });

            })->tab(trans('language.product.attribute'), function ($form) use ($id) {
                $groups = ShopAttributeGroup::pluck('name', 'id')->all();
                $html   = '';
                foreach ($groups as $key => $group) {
                    ${'group_' . $key} = ShopAttributeDetail::where('product_id', $id)->where('attribute_id', $key)->get();
                    $html .= '
                        <table class="table box  table-bordered table-responsive">
                            <thead>
                              <tr>
                                <th colspan="4">' . $group . '</th>
                              </tr>
                            </thead>
                            <tbody>
                                      <tr>
                                        <td><span> ' . trans('language.attribute.detail_name') . ' ' . $group . '</span></td>
                                        <td></td>
                                      </tr>';
                    if (count(${'group_' . $key}) == 0) {
                        $html .= '<tr id="no-item-' . $key . '">
                                <td colspan="4" align="center" style="color:#cc2a2a">' . trans('language.attribute.no_item') . '</td>
                              </tr>';
                    } else {

                        foreach (${'group_' . $key} as $key2 => $value2) {
                            $html .= '
                                      <tr>
                                        <td>
                                        <span><div class="input-group"><input  type="text" name="group[' . $key . '][name][]" value="' . $value2['name'] . '" class="form-control" placeholder="' . trans('language.attribute.detail_name') . '"></div></span>
                                        </td>
                                        <td>
                                         <button onclick="removeItemForm(this);" class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal"  data-placement="top" rel="tooltip" data-original-title="" title="Remove item"><span class="glyphicon glyphicon-remove"></span>' . trans('admin.remove') . '</button>
                                        </td>
                                      </tr>';
                        }
                    }

                    $html .= '
                               <tr id="addnew-' . $key . '">
                                <td colspan="8">  <button type="button" class="btn btn-sm btn-success"  onclick="morItem(' . $key . ');" rel="tooltip" data-original-title="" title="Add new item"><i class="fa fa-plus"></i> ' . trans('language.attribute.add_more') . '</button>
                        </td>
                              </tr>
                        <tr>
                        </tr>
                            </tbody>
                          </table> ';
                }
                $detail_name = trans('language.attribute.detail_name');
                $remove      = trans('admin.remove');
                $script      = <<<SCRIPT
               <script>
                function morItem(id){
                        $("#no-item-"+id).remove();
                    $("tr#addnew-"+id).before("<tr><td><span><span class=\"input-group\"><input  type=\"text\" name=\"group["+id+"][name][]\" value=\"\" class=\"form-control\" placeholder=\"$detail_name\"></span></span></td><td><button onclick=\"removeItemForm(this);\" class=\"btn btn-danger btn-xs\" data-title=\"Delete\" data-toggle=\"modal\"  data-placement=\"top\" rel=\"tooltip\" data-original-title=\"\" title=\"Remove item\"><span class=\"glyphicon glyphicon-remove\"></span> $remove</button></td></tr>");
                    }

                    function removeItemForm(elmnt){
                      elmnt.closest("tr").remove();
                    }

                </script>
SCRIPT;
                $form->html($html . $script);

            });
            if(!$this->checkSuperUser())
               {
                $form->model()->company_id = $this->getUserCompany()[0]->id;
               }
            $arrData = array();
            $form->saving(function (Form $form) use ($languages, &$arrData) {
                $form->model()->path = Storage::disk(config('admin.upload.disk'))->url('');
                //Lang
                foreach ($languages as $key => $language) {
                    $arrData[$language->code]['name']        = request($language->code . '__name');
                    $arrData[$language->code]['keyword']     = request($language->code . '__keyword');
                    $arrData[$language->code]['description'] = request($language->code . '__description');
                    $arrData[$language->code]['content']     = request($language->code . '__content');
                }
              //  print_r($arrData);die;
                //end lang
            });
        //    print_r($arrData);die;
            //saved
            $form->saved(function (Form $form) use ($languages, &$arrData) {
                $id = $form->model()->id;
                //Lang
                foreach ($languages as $key => $language) {
                    if (array_filter($arrData[$language->code], function ($v, $k) {
                        return $v != null;
                    }, ARRAY_FILTER_USE_BOTH)) {
                        $arrData[$language->code]['product_id'] = $id;
                        $arrData[$language->code]['lang_id']    = $language->id;
                        $arrData[$language->code]['company_id'] = $form->model()->company_id; // sprint 1
                        ShopProductDescription::where('lang_id', $arrData[$language->code]['lang_id'])->where('product_id', $arrData[$language->code]['product_id'])->delete();
                        ShopProductDescription::insert($arrData[$language->code]);
                    }
                }
                //end lang
                $product         = ShopProduct::find($id);
                $file_path_admin = config('filesystems.disks.admin.root');
                $statusWatermark = \Helper::configs()['watermark'];
                $fileWatermark   = $file_path_admin . '/' . \Helper::configsGlobal()['watermark'];
                try {
                    //image primary
                    \Helper::processImageThumb($pathRoot = $file_path_admin, $pathFile = $product->image, $widthThumb = 250, $heightThumb = null, $statusWatermark, $fileWatermark);

                    if (($product->images)) {
                        foreach ($product->images as $key => $image) {
                            //images slide
                            \Helper::processImageThumb($pathRoot = $file_path_admin, $pathFile = $image->image, $widthThumb = 250, $heightThumb = null, $statusWatermark, $fileWatermark);
                        }
                    }

                    if (($product->options)) {
                        foreach ($product->options as $key => $image) {
                            //images options
                            \Helper::processImageThumb($pathRoot = $file_path_admin, $pathFile = $image->opt_image, $widthThumb = 250, $heightThumb = null, $statusWatermark, $fileWatermark);
                        }
                    }

                } catch (\Exception $e) {
                    echo $e->getMessage();
                }

                ShopAttributeDetail::where('product_id', $id)->delete();
                $groups = $form->group;
                if ($groups > 0) {
                    foreach ($groups as $attID => $group) {
                        foreach ($group['name'] as $key => $value) {
                            if ($value != '') {
                                ShopAttributeDetail::insert(['name' => $value, 'attribute_id' => $attID, 'product_id' => $id]);
                            }

                        }
                    }
                }
            });
            $form->disableViewCheck();
        //    $form->disableEditingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
        });

    }//sprint 3

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(ShopProduct::findOrFail($id), function (Show $show) {
                $show->id('ID');
                $show->name('name');
                $show->sku('sku');
              //  $show->company()->name('name');
            }));
        });
    }
    public function getTotalProductReview($companyId, $productId){ // need upgrade

       $data = ShopProductLike::where('product_id', $productId)
                         ->where('company_id', $companyId)
                         ->selectRaw('SUM(CASE WHEN review IS NOT NULL THEN 1  ELSE 0 END) reviews, (sum(rate) / 5) rates')
                         ->groupBy('product_id', 'company_id')
                         ->get(); 
      return $this->sendResponse($data, 200);
    }
    public function getAllProductReview($companyId, $productId){
        $data1 = ShopProductLike::where('product_id', $productId)
                         ->where('company_id', $companyId)
                         ->selectRaw('SUM(CASE WHEN review IS NOT NULL THEN 1  ELSE 0 END) reviews, (sum(rate) / SUM(CASE WHEN rate > 0 THEN 1  ELSE 0 END)) rates')
                         ->groupBy('product_id', 'company_id')
                         ->get(); 
       $data2 = ShopProductLike::where('product_id', $productId)
                        ->join('admin_users', 'admin_users.id', '=', 'shop_product_like.users_id')
                         ->where('shop_product_like.company_id', $companyId)
                         ->selectRaw('CONCAT(admin_users.name, " ", admin_users.lname) name ,shop_product_like.review,  shop_product_like.rate,shop_product_like.created_at, admin_users.path, admin_users.avatar')
                         ->get();
      $data = array();
      $data["totals"] = $data1;
      $data["details"] = $data2;
      
        return response()->json($data, 200);                 
    }
    public function getProductDetailsOld($companyId, $productId){
        if(!is_numeric($productId) || !is_numeric($companyId)){
          return $this->sendError("data invalid ", 400);
        }
        $ProductDetails = ShopProduct::where('company_id',$companyId)->where('id', $productId)
                                       ->with('images')
                                       ->with('priceList')
                          //             ->with('reviews')
                            //           ->withCount(['reviews'=> function(\Illuminate\Database\Eloquent\Builder $query){
                              //              $query->whereNotNull('review');

                                //       }])
                                      // ->withCount(['likes' => function(\Illuminate\Database\Eloquent\Builder $query) use($productId){
                                        //     $query->where('likes', '>', 0 )->where('product_id', $productId);
                                       //}])
                                       ->get();
        
        return $this->sendResponse($ProductDetails, 200);
    }
    public function getProductDetails(Request $request, $companyId, $productId){
        if(!is_numeric($productId) || !is_numeric($companyId)){
          return $this->sendError("data invalid ", 400);
        }
        
        $ProductDetails = new ShopProduct();
        $lang = $request->lang_id??1; 
        $ProductDetails->lang_api = $lang;
        
        //$ProductDetails->setLangApi($lang); 
        $details =  $ProductDetails->getProductDetails($productId,$companyId, $lang);
       
        return $this->sendResponse($details, 200);
    }
    

    public function likeOrReviewProduct(Request $request) {
        try{
        if($request->header('user-id') ==null 
           || !isset($request->product_id)
           || !isset($request->company_id)){
            return $this->sendResponse("fail data missing", 500);
        }
        
        $productLikeModel = ShopProductLike::where('users_id',  $request->header('user_id') )
                                           ->where('company_id', $request->company_id)
                                           ->where('product_id', $request->product_id)->first();
       // print_r($check);die;
       
        if(!isset($productLikeModel)){
            $productLikeModel = new ShopProductLike();
        }
        $productLikeModel->product_id = $request->product_id;
        $productLikeModel->company_id = $request->company_id;
        $productLikeModel->users_id = $request->header('user_id');
       
       if(isset($request->review)){
        $productLikeModel->review = $request->review;
      //  print_r($productLikeModel);die;
       }
        if(isset($request->rate)){
            if(!is_numeric($request->rate) || $request->rate > 5){
                return $this->sendError("rate value not valid should be less or equal 5", 400);
            }
            $productLikeModel->rate = $request->rate;
        }

      $productLikeModel->save();
      return $this->sendResponse('success',200);
    }catch(\Exception $e){
        return $this->sendError($e->getMessage(), 400);
    }
    }

    public function searchProduct(Request $request, $search){
        try{
           $productModel = new ShopProduct();
			  if($search == 'all'){
               $search = '';
           }
           $lang = $request->lang_id?? 1;
           $products = $productModel->getSearch($search, 20, 'price', $lang);
         return $this->sendResponse($products, 200);
        }catch(\Exception $ex){
             return $this->sendError($ex->getMessage(), 400);
        }
    }

    public function getMostProduct(Request $request){
        if(!is_numeric($request->company)){
          $request->company = 0;
        }
          $company = $request->company;
          return $this->getMost($company);
                     
        }

        private function getMost($company){
            if($company){
            $mostSales = DB::table('shop_order_detail')
            ->select(DB::Raw('COUNT(order_id) numOrder, product_id'))
                ->where('company_id', $company)
                ->groupBy('product_id')
                ->orderBy('numOrder', 'desc')
                ->limit(4)
                ->pluck('product_id', 'numOrder');
                $mostSalesProdIds = array();
      
                foreach($mostSales as $key=>$value) {
                  array_push($mostSalesProdIds, $value);
               }
                $products_most = ShopProduct::whereIn('id', $mostSalesProdIds)
                                 ->where('company_id', $company)
                                 ->get();
                $newProducts = ShopProduct::where('type', 1)->limit(4)
                                   ->where('company_id', $company)
                                  ->get();
                $featuredProduct = ShopProduct::where('type', 2)->limit(4)
                                     ->where('company_id', $company)
                                     ->get();
                $firstPageProduct = array();
                $firstPageProduct["most_sales"] =  $products_most;
                $firstPageProduct["product_new"] = $newProducts;
                $firstPageProduct["featured"] = $featuredProduct;
                return $this->sendResponse($firstPageProduct, 200);
            }
            
            $mostSales = DB::table('shop_order_detail')
            ->select(DB::Raw('COUNT(order_id) numOrder, product_id'))
                ->groupBy('product_id')
                ->orderBy('numOrder', 'desc')
                ->limit(4)
                ->pluck('product_id', 'numOrder');
                $mostSalesProdIds = array();
      
                foreach($mostSales as $key=>$value) {
                  array_push($mostSalesProdIds, $value);
               }
                $products_most = ShopProduct::whereIn('id', $mostSalesProdIds)
                                 ->get();
                $newProducts = ShopProduct::where('type', 1)->limit(4)
                                  ->get();
                $featuredProduct = ShopProduct::where('type', 2)->limit(4)
                                     ->get();
                $firstPageProduct = array();
                $firstPageProduct["most_sales"] =  $products_most;
                $firstPageProduct["product_new"] = $newProducts;
                $firstPageProduct["featured"] = $featuredProduct;
                return $this->sendResponse($firstPageProduct, 200);
        }

}
