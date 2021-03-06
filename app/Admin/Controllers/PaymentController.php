<?php
 namespace App\Admin\Controllers;
 use App\Http\Controllers\Controller;
 use App\Models\Company;
 use App\Models\Transaction;
 use App\Models\CompanyContact;
use App\Models\ConfigGlobalDescription;
use App\Models\Language;
use App\Models\ShopProduct;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form\Field\Image;
use Encore\Admin\Form\Field\UploadField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ShopOrder;
use App\Models\ShopOrderDetail;
use App\Models\ShopOrderHistory;
use App\Models\ShopOrderStatus;
use App\Models\ShopOrderTotal;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PaymentController extends Controller
{
  use HasResourceActions;
  public $arrType = ['CARD' => 'CARD', 'BANK' => 'BANK'];
  public function index(){
    
    return Admin::content(function (Content $content) {
      $content->header(trans('language.admin.companies_manager'));
      $content->description(' ');  
      $content->body($this->grid());

  });
   }
   public function create()
   {
       return Admin::content(function (Content $content) {

           $content->header(trans('admin.company'));
           $content->description(trans('admin.edit'));

           $content->body($this->form());
       });
   }

  public function proceedPayment(Request $request){
      try{
     $card = trim($request->cardNumber);   
     $holder = trim($request->holderName); 
     $expiryMonth = $request->expireMonth;
     $expireyear = $request->expireyear;
     $CVV = $request->CVV;
     $order_ids = $request->order_id;
     $orders = explode(",", $order_ids);
     
    if(!is_numeric($card) || !is_numeric($expiryMonth)  || !is_numeric($expireyear) || !is_numeric($CVV)){
        return $this->sendError("error", 400);
    }
    if($order_ids == null || trim($order_ids) == ""){
        return $this->sendError("no order", 400);
    }
    foreach($orders as $index => $order){
       $order = ShopOrder::findOrFail($order);
    }
     return $this->sendResponse("sucess",200);
}catch(\Exception $ex){
    return $this->sendError($ex->getMessage(), 400);
}
  }

   protected function grid()
    {
        return Admin::grid(Transaction::class, function (Grid $grid) {
            //if($this->checkSuperUser())
          //  $grid->model()->company_id = 1;
            $grid->id('ID')->sortable();
            
            $grid->type(trans('language.type'));
            $grid->notes(trans('language.notes'));
            $states = [
                '1' => ['value' => 1, 'text' => 'YES', 'color' => 'primary'],
                '0' => ['value' => 0, 'text' => 'NO', 'color' => 'default'],
            ];
            $grid->cat_type(trans('language.typedes'));
            $grid->active(trans('language.admin.status'))->switch($states);
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
         if(!$this->checkSuperUser())
            {
            //  $grid->model()
              //   ->where('id',$this->getUserCompany()[0]->id);
            }
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('name', trans('admin.company'));
              

            });
        });
    }
    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(Transaction::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }
   
   public function edit($id = null){
    return Admin::content(function (Content $content) use ($id) {
      $content->header(trans('admin.menu_titles.activities'));
      $content->description(trans('admin.edit'));
      
      $content->body($this->form($id)->edit($id));
  });
   } // sprint 3
   //from allah 
   public function update(Request $request, $id ){
     $arr = $request->all();
     if(isset($arr['active']) && !isset( $arr['type'])){
        $model = Transaction::findOrFail($id);
        $model->active = $arr['active']== 'off'? 0 : 1;
        $model->save();
         return $this->sendResponse("sucess", 200);
     }
     $model = Transaction::findOrFail($id);
     $model->type = $arr['type'];
     $model->notes = $arr['notes'];
     $model->parent_id = $arr['parent_id'];
     if(isset($arr['icon'])){
	 $model->path = Storage::disk(config('admin.upload.disk'))->url(''); 
     $uploadedImage = new Image($arr['icon']) ;
     $uploadedImage->uniqueName();
     $uploadedImage->move('activity_Icon');
     $model->icon =$uploadedImage->prepare($arr['icon']);
     }
     $model->save();
    return Admin::form(Transaction::class, function (Form $form) use ($id) {
    })->update($id,[]);
    }     
   protected function form($id = null)
    {
        return Admin::form(Transaction::class, function (Form $form) use ($id) {
            $languages = Language::getLanguages();
            $form->tab(trans('admin.menu_titles.activities'), function ($form) use ($languages, $id) {
//Language      
                $name = '';
                $notes = '';
                $selected = '0';
                $arrData = array();
                 if($id != null){
                      $entity = Transaction::find($id);
                      $name = $entity->type;
                      $notes = $entity->notes;
                     // $selected = strval($entity->activity_id);
                      //$companyContacts = $entity->getContact()->get();
                     // $config = $entity->configs();
                     
                 }
               //  dd(8)
                $form->text('type', trans('admin.type'))->default(!empty($name) ? $name : null);
                $form->text('notes', trans('language.notes'))->default(!empty($notes) ? $notes : null);
                $form->radio('cat_type', trans('language.product.product_type'))->options($this->arrType)->default('0');
                $form->image('icon', trans('language.admin.image'))->uniqueName()->move('activityImages');
                $activities = Transaction::where('id','!=', $id)->pluck('type','id');
                $form->select('parent_id', trans('language.admin.parent_category'))->options($activities);
                 $form->saving(function (Form $form) use ($languages, &$arrData) {
				 $form->model()->path =Storage::disk(config('admin.upload.disk'))->url(''); 
                    //Lang
                    //if($this->isValidIBAN($form->model()->iban))
                    if(sizeof($arrData) > 0){
                    foreach ($languages as $key => $language) {
                        $arrData[$language->code]['name']        = request($language->code . '__name');
                        $arrData[$language->code]['keyword']     = request($language->code . '__keyword');
                        $arrData[$language->code]['description'] = request($language->code . '__description');
                    }
    
                    }
                  
                });
                $form->saved(function (Form $form) use ($languages, &$arrData) {
                $id = $form->model()->id;
                //Lang
                foreach ($languages as $key => $language) {
                    if (sizeof($arrData) > 0 && array_filter($arrData[$language->code], function ($v, $k) {
                        return $v != null;
                    }, ARRAY_FILTER_USE_BOTH)) {
                       //config save
                    }
                }
                //end lang
        });
            $form->disableViewCheck();
        //    $form->disableEditingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
         
            });//sprint 2
               
            
    });
}
//sprint 3
     
    }