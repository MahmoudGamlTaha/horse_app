<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductPriceList;
use App\Models\ShopProduct;
use App\Models\UofmGroups;
use App\Models\Uofms;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Nexmo\Account\Price;

class PriceListController extends Controller
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

            $content->header(trans('language.admin.special_price_manager'));
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

            $content->header(trans('language.admin.special_price_manager'));
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

            $content->header(trans('language.admin.special_price_manager'));
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
        return Admin::grid(ProductPriceList::class, function (Grid $grid) {

            $grid->id(trans('language.admin.sort'))->sortable();
            $grid->product(trans('language.admin.product'))->display(function ($product) {
                return $product['name'] . "<br>(SKU: " . $product['sku'] . ")";
            });
            $grid->price(trans('language.admin.special_price'))->display(function ($price) {
                return number_format($price);
            });
          
            $grid->unit(trans('language.unit'))->display(function ($unit) {
                return $unit;
            });

            $grid->comment(trans('language.admin.comment'));
            $grid->status(trans('language.admin.status'))->switch();
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
    public function getUnitList(Request $request){
          try{
              if(is_numeric($request->id)){
                $selected = Uofms::query()->where('group_id', $request->id)
                ->pluck('name','id')->toArray();
                return $this->sendResponse($selected, 200);
              }
              return $this->sendError("error id", 400);
          }catch(\Exception $ex){
             return $this->sendError("error", 400);
          }
    }
    public function getInfoPrice(Request $request)
    {
        $id  = $request->input('id');
        $uofm = $request->input('uofm');
        if(!is_numeric($id)){
            return $this->sendError("error id", 400);
        }
        $priceList = null;
       
        if($uofm){
        $priceList = ProductPriceList::query()
                                   ->where('product_id', $id)
                                   ->where('uof_id', $uofm)
                                   ->first();
        } 
       $product = ShopProduct::find($id);
        if ($priceList) {
            $product->price = $priceList->price;
        } 
    
        $arrayReturn                     = $product->toArray();
        $arrayReturn['renderAttDetails'] = $product->renderAttDetails();
        return json_encode($arrayReturn);
   // return $this->sendError('product not found', 400);
    }
    protected function form()
    {
        Admin::script($this->jsProcess());
        return Admin::form(ProductPriceList::class, function (Form $form) {
            
            $company = $this->getUserCompany()[0]->id;
            $products = null;
            $uofmgs = null;
            $units = null;
            if(!$this->checkSuperUser()){
              $products = ShopProduct::getArrayProductNameForCompany($company);
              $uofmgs = UofmGroups::where('company_id', $company);
            }
            else{
                $products = ShopProduct::query()->pluck('sku','id');
                $uofmgs = UofmGroups::query()->pluck('name', 'id'); 
            }
            $form->select('product_id', trans('language.admin.product'))->options($products)->rules(function ($form) {
                return 'required|unique:shop_special_price,product_id,' . $form->model()->id . ',id';
            });
            $form->select('uof_group', trans('language.admin.unit'))->options($uofmgs)->rules(function ($form) {
                return 'required';
            });
            $form->select('uof_id', trans('language.admin.SecondaryUnit'))->options($units)->rules(function ($form) {
                return 'required';
            });

            //$form->html('', trans('language.product.price'));
            $form->model()->company_id =  $company ;
            $form->currency('price',  trans('language.product.price'))->symbol('')->options(['digits' => 0])->default(0);
            $form->switch('status', trans('language.admin.status'));
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
        });
    }

    public function jsProcess()
    {
        $urlgetInfoProduct = route('getInfoPrice');
        $urlgetUofmList = route('uofm-details');
        return <<<JS
        $(document).ready(function(){
            var id = $('select[name*="product_id"]').val();
            $.ajax({
                url : '$urlgetInfoProduct',
                type : "get",
                datatype : "json",
                dateType:"application/json; charset=utf-8",
                data : {
                     id : id
                },
                success: function(result){
                    console.log(result)
                    var returnedData = JSON.parse(result);
                    $('input[name*="price"]').val(returnedData.price);
                }
            });
            ///
            var uofmg = $('select[name*="uof_group"]').val();
            $.ajax({
                url : '$urlgetUofmList',
                type : "get",
                datatype : "json",
                dateType:"application/json; charset=utf-8",
                data : {
                     id : id
                },
                success: function(result){
                    $('select[name*="uof_id"]').empty(); 
                        for (const [key, value] of Object.entries(result.data)) {
                            $('select[name*="uof_id"]').append(new Option(value, key));
                        }
                       
                },
                error:function(error){
                    console.log(error);
                }
            });
        });
       //  $('')
       $('[name*="product_id"]').change(function(){
           console.log("fired");
            var id = $(this).val();
                $.ajax({
                    url : '$urlgetInfoProduct',
                    type : "get",
                    datatype : "json",
                    dateType:"application/json; charset=utf-8",
                    data : {
                         id : id
                    },
                    success: function(result){
                        var returnedData = JSON.parse(result);
                        $('input[name*="price"]').val(returnedData.price);
                    }
                }) ;
        });   
        $('[name*="uof_group"]').change(function(){
            var id = $(this).val();
                $.ajax({
                    url : '$urlgetUofmList',
                    type : "get",
                    datatype : "json",
                    dateType:"application/json; charset=utf-8",
                    data : {
                         id : id
                    },
                    success: function(result){
                        $('select[name*="uof_id"]').empty(); 
                        for (const [key, value] of Object.entries(result.data)) {
                            $('select[name*="uof_id"]').append(new Option(value, key));
                        }
                       
                    }
                });
        });

        $('[name*="uof_id"').change(function(){
        var id = $(this).val();
        var prodId = $('[name*="product_id"]').children("option:selected").val();

                $.ajax({
                    url : '$urlgetInfoProduct',
                    type : "get",
                    datatype : "json",
                    dateType:"application/json; charset=utf-8",
                    data : {
                         uofm:id,
                         id : prodId
                    },
                    success: function(result){
                        var returnedData = JSON.parse(result);
                        $('input[name*="price"]').val(returnedData.price);
                    }
                });
       
        });

    $('#off').change(function(){
        var offData = $('#off').val();
        if(offData ===''){
            $('#off').val(0);
        }
  //  var newPrice = parseInt($('#price-old').val().replace(',','')) * (100 - parseInt($('#off').val())) /100;
    //$('#price').val(newPrice);
    });


  function formatNumber (num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
  }

JS;
    }
  public function  update(Request $request, $id){
    try{
       $price = ProductPriceList::findOrFail($id);
       $company_id = $this->getUserCompany()[0]->id;
       if(isset($request->product_id)){
          $product = ShopProduct::findOrFail($request->product_id);
          if($product != null && $product->company_id != $company_id ){
            if(!$this->checkSuperUser()){
                abort(404);
            }
            if($product == null){
                abort(404);
            }
         }
        
        $price->product_id = $request->product_id;

       }

       if(isset($request->uof_id)){
           $price->uof_id = $request->uof_id; 
       }
       if(isset($request->status)){
           $price->status = $request->status == "Off"?0:1;
       }
       if(isset($request->price)){
           $price->price = $request->price;
       }
       if(isset($request->uof_group)){
           $price->uof_group = $request->uof_group;
       }
       $price->save();
    }catch(\Exception $e){
        return $this->sendError($e->getMessage(), 400);
    }

  }
    public function getInfoProduct(Request $request)
    {
        $id = $request->input('id');
        return ShopProduct::find($id)->toJson();

    }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(ProductPriceList::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }

}
