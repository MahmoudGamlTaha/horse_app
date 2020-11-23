<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\ExcelExpoter;
use App\Extensions\Total\Models\Discount;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CouponOrder;
use App\Models\ShopAttributeGroup;
use App\Models\ShopCurrency;
use App\Models\ShopOrder;
use App\Models\ShopOrderDetail;
use App\Models\ShopOrderHistory;
use App\Models\ShopOrderStatus;
use App\Models\ShopOrderTotal;
use App\Models\ShopPaymentStatus;
use App\Models\ShopProduct;
use App\Models\ShopShippingStatus;
use App\User;
use DB;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Extensions\Total\Models\Discount as DiscountModel;
use App\Models\AddressOrder;
use App\Models\UserAddress;

class ShopOrderController extends Controller
{
    use HasResourceActions;
    public $statusPayment, $statusOrder, $statusShipping, $statusOrder2, $statusShipping2, $currency;

    public function __construct()
    {
        $this->statusOrder     = ShopOrderStatus::pluck('name', 'id')->all();
        $this->currency        = ShopCurrency::pluck('name', 'code')->all();
        $this->statusPayment   = ShopPaymentStatus::pluck('name', 'id')->all();
        $this->statusShipping  = ShopShippingStatus::pluck('name', 'id')->all();
        $this->statusOrder2    = ShopOrderStatus::mapValue();
        $this->statusShipping2 = ShopShippingStatus::mapValue();
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $keyword = \Request::input('keyword');
        $keyword = empty($keyword) ? "" : $keyword;
        return Admin::content(function (Content $content) use ($keyword) {

            $content->header(trans('language.admin.order_manager'));
            $content->description(' ');
            if ($keyword != "") {
                $content->description(trans('language.order.search_keyword') . ': "' . $keyword . '"');
            }

            $content->body($this->grid($keyword));
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

            $content->header(trans('language.admin.order_manager'));
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

            $content->header(trans('language.admin.order_manager'));
            $content->description(' ');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($keyword)
    {
        return Admin::grid(ShopOrder::class, function (Grid $grid) use ($keyword) {

            $grid->id('ID')->sortable();
            $grid->email('Email')->display(function ($email) {
                return empty($email) ? 'N/A' : '<div style="max-width:150px; overflow:auto;word-wrap: break-word;">' . $email . '</div>';
            });
            $grid->subtotal(trans('language.order.sub_total'))->display(function ($price) {
                return empty($price) ? 0 : '<div style="max-width:100px; overflow:auto;word-wrap: break-word;">' . \Helper::currencyOnlyRender($price, $this->currency) . '</div>';
            });
            $grid->shipping(trans('language.order.shipping_price'))->display(function ($price) {
                return empty($price) ? 0 : '<div style="max-width:100px; overflow:auto;word-wrap: break-word;">' . \Helper::currencyOnlyRender($price, $this->currency) . '</div>';
            });
            $grid->discount(trans('language.order.discount'))->display(function ($price) {
                return empty($price) ? 0 : '<div style="max-width:100px; overflow:auto;word-wrap: break-word;">' . \Helper::currencyOnlyRender($price, $this->currency) . '</div>';
            });
            $grid->total(trans('language.order.total'))->display(function ($price) {
                return empty($price) ? 0 : '<div style="max-width:100px; overflow:auto;word-wrap: break-word;">' . \Helper::currencyOnlyRender($price, $this->currency) . '</div>';
            });
            $grid->received(trans('language.order.received'))->display(function ($price) {
                return empty($price) ? 0 : '<div style="max-width:100px; overflow:auto;word-wrap: break-word;">' . \Helper::currencyOnlyRender($price, $this->currency) . '</div>';
            });
            $grid->payment_method(trans('language.order.payment_method'))->sortable();

            $grid->currency(trans('language.order.currency'));
            $grid->exchange_rate(trans('language.order.exchange_rate'));
            $statusOrder = $this->statusOrder;
            $grid->status(trans('language.admin.status'))->display(function ($status) use ($statusOrder) {
                $style = "";
                if ($status == 0) {
                    $style = '';
                } elseif ($status == 1) {
                    $style = 'class="label label-primary"';
                } elseif ($status == 2) {
                    $style = 'class="label label-warning"';
                } elseif ($status == 3) {
                    $style = 'class="label label-danger"';
                } elseif ($status == 4) {
                    $style = 'class="label label-success"';
                }
                return "<span $style>" . $statusOrder[$status] . "</span>";
            });
            $grid->actions(function ($actions) {
                $actions->disableEdit();
                $actions->prepend('<a title="Show Customer detail" href="shop_order_edit/' . $actions->getkey() . '"><i class="fa fa-edit"></i></a>');
                $actions->disableView();
            });

            $grid->created_at(trans('language.admin.created_at'));
            $grid->model()->orderBy('id', 'desc');
            if (!$this->checkSuperUser()) {
                $grid->model()
                    ->where('company_id', $this->getUserCompany()[0]->id);
            }
            if ($keyword != "") {
                $grid->model()
                    ->where('company_id', $this->getUserCompany()[0]->id)
                    //   ->orwhere('toname', 'like', '%' . $keyword . '%')
                    //->orWhere('phone', 'like', '%' . $keyword . '%')
                    ->Where('email', 'like', '%' . $keyword . '%')
                    ->Where('id', (int) $keyword);
            }
            $grid->exporter(new ExcelExpoter('dataOrder', 'Order list'));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        Admin::script($this->jsProcess());
        return Admin::form(ShopOrder::class, function (Form $form) {
            $arrCustomer = array();
            $company = $this->getUserCompany()[0]->id;

            if (!$this->checkSuperUser()) {
                $this->statusOrder = ShopOrderStatus::where('company_id', $company)->pluck('name', 'id');
                $form->model()->company_id = $company;
                $customers   = User::where('company_id', $company);
            } else {
                $this->statusOrder = ShopOrderStatus::all()->pluck('name', 'id');
                $customers   = Administrator::all();
            }


            foreach ($customers as $key => $value) {
                $arrCustomer[$value['id']] = $value['name'] . "<" . $value['email'] . ">";
            }
            $form->select('user_id', trans('language.order.select_customer'))->options($arrCustomer)->rules('required');
            if ($this->checkSuperUser()) {
                $companies = Company::pluck('name', 'id');
                $form->select('company_id', trans('language.company'))->options($companies);
            }
            $form->text('toname', trans('language.order.shipping_name'));
            $form->text('address1', trans('language.order.shipping_address1'));
            $form->text('address2', trans('language.order.shipping_address2'));
            $form->mobile('phone', trans('language.order.shipping_phone'));
            $form->select('currency', trans('language.order.currency'))->options($this->currency)->rules('required');
            $form->number('exchange_rate', trans('language.order.exchange_rate'))->default(0);
            $form->textarea('comment', trans('language.order.order_note'));
            $form->select('status', trans('language.admin.status'))->options($this->statusOrder);
            $form->hidden('email');

            $form->divide();
            $form->saving(function (Form $form) use ($customers) {
                $checkCurrency = ShopCurrency::where('code', $form->currency)->first();
                $checkUser     = User::find($form->user_id);
                $form->email   = $checkUser->email;
            });

            $form->saved(function (Form $form) {
                $id         = $form->model()->id;
                $company_id = $form->model()->company_id;
                $this->initOrderTotal($id, $company_id);
            });
            $form->disableViewCheck();
            $form->disableEditingCheck();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
        });
    }

    public function jsProcess()
    {
        $urlgetInfoUser    = route('getInfoUser');
        $urlgetInfoProduct = route('getInfoProduct');
        $currencies        = json_encode(ShopCurrency::pluck('exchange_rate', 'code')->all());
        return <<<JS
        $('[name="user_id"]').change(function(){
            id = $(this).val();
                $.ajax({
                    url : '$urlgetInfoUser',
                    type : "get",
                    dateType:"application/json; charset=utf-8",
                    data : {
                         id : id
                    },
                    success: function(result){
                        var returnedData = JSON.parse(result);
                        $('[name="toname"]').val(returnedData.name);
                        $('[name="address1"]').val(returnedData.address1);
                        $('[name="address2"]').val(returnedData.address2);
                        $('[name="phone"]').val(returnedData.phone);
                    }
                });
        });
        $('[name="currency"]').change(function(){
            var currency = $(this).val();
            var jsonCurrency = $currencies;
            $('[name="exchange_rate"]').val(jsonCurrency[currency]);
        });


JS;
    }

    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('');
            $content->description('');
            $content->body(Admin::show(ShopOrder::findOrFail($id), function (Show $show) {
                $show->id('ID');
            }));
        });
    }

    /**
     * [getInfoUser description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getInfoUser(Request $request)
    {
        $id = $request->input('id');
        return User::find($id)->toJson();
    }
    /**
     * [getInfoProduct description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getInfoProduct(Request $request)
    {
        $id  = $request->input('id');
        $sku = $request->input('sku');
        if ($id) {
            $product = ShopProduct::find($id);
        } else {
            $product = ShopProduct::where('sku', $sku)->first();
        }
    
        $arrayReturn                     = $product->toArray();
        $arrayReturn['renderAttDetails'] = $product->renderAttDetails();
        return json_encode($arrayReturn);
   // return $this->sendError('product not found', 400);
    }
    /**
     * [getInfoItem description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getInfoItem(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            $item = ShopOrderDetail::find($id);
        }
        $arrayReturn = $item->toArray();
        return json_encode($arrayReturn);
    }

    /**
     * [detailOrder description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detailOrder($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('language.admin.order_manager'));
            $content->description(' ');
            $content->body(
                $this->detailOrderForm($id)
            );
        });
    }
    public function detailOrderMobile(Request $request, $id)
    {  if(!is_numeric($request->header("user-id") )){
           return $this->sendError("missing user", 400);
        }
        $user_id = $request->header("user-id");
        $orderData = ShopOrder::where('id', $id)
         ->where('user_id',  $user_id)
         //->with('shippingCost')
         ->with('discountOrder')
            ->with('userAddress')
            ->with('orderStatus')
            ->get();
        $details = ShopOrderDetail::where(['order_id'=> $id])
                  ->with(array('product' => function($query){
                    $query->select(['image','path', 'id']);
                  }))
                  ->select(['name','qty','price','product_id'])
                  ->get();   
                  $totalQty  = 0;
            
        $order = array();
        $order['order'] = $orderData;
        $order['item_line'] = $details;
        return $this->sendResponse($order, 200);
    }
    public function detailOrderForm($id = null)
    {
        $company = $this->getUserCompany()[0]->id;
        $order =  ShopOrder::find($id);

        if (!$this->checkSuperUser()) {
            if ($order === null || $order->company_id != $company) {
                return '<b>Order Not Found</b>';
            }

            $products = ShopProduct::getArrayProductNameForCompany($company);
        } else {
            if ($order === null) {
                return '<b>Order Not Found</b>';
            }
            $products         = ShopProduct::getArrayProductName();
        }
        $paymentMethodTmp = \Helper::getExtensionsGroup('payment', $onlyActive = false);
        foreach ($paymentMethodTmp as $key => $value) {
            $paymentMethod[$key] = trans($value->detail);
        }
        $shippingMethodTmp = \Helper::getExtensionsGroup('shipping', $onlyActive = false);
        foreach ($shippingMethodTmp as $key => $value) {
            $shippingMethod[$key] = trans($value->detail);
        }
        return view('admin.OrderEdit')->with(
            [
                "order"           => $order,
                "products"        => $products,
                "statusOrder"     => $this->statusOrder,
                "statusPayment"   => $this->statusPayment,
                "statusShipping"  => $this->statusShipping,
                "statusOrder2"    => $this->statusOrder2,
                "statusShipping2" => $this->statusShipping2,
                'dataTotal'       => ShopOrderTotal::getTotal($id),
                'attributesGroup' => ShopAttributeGroup::pluck('name', 'id')->all(),
                'paymentMethod'   => $paymentMethod,
                'shippingMethod'  => $shippingMethod,
            ]
        )->render();
    }
    /**
     * [postOrderUpdate description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postOrderUpdate(Request $request)
    {
        $id    = $request->input('pk');
        $field = $request->input('name');
        $value = $request->input('value');
        if ($field == 'shipping' || $field == 'discount' || $field == 'received') {
            $order_total_origin = ShopOrderTotal::find($id);
            $order_id           = $order_total_origin->order_id;
            $oldValue           = $order_total_origin->value;
            $order              = ShopOrder::find($order_id);
            $fieldTotal         = [
                'id'    => $id,
                'code'  => $field,
                'value' => $value,
                'text'  => \Helper::currencyOnlyRender($value, $order->currency),
            ];
            ShopOrderTotal::updateField($fieldTotal);
        } else {
            $arrFields = [
                $field => $value,
            ];
            $order_id = $id;
            $order    = ShopOrder::find($order_id);
            $oldValue = $order->{$field};
            ShopOrder::updateInfo($order_id, $arrFields);
        }

        //Add history
        $dataHistory = [
            'order_id' => $order_id,
            'content'  => 'Change <b>' . $field . '</b> from <span style="color:blue">\'' . $oldValue . '\'</span> to <span style="color:red">\'' . $value . '\'</span>',
            'admin_id' => Admin::user()->id,
            'add_date' => date('Y-m-d H:i:s'),
        ];
        ShopOrderHistory::insert($dataHistory);

        //updateField
        // $updateSubTotal = ShopOrderTotal::updateSubTotal($id, $fields = array($field => $value));

        if ($order_id) {
            $orderUpdated = ShopOrder::find($order_id);
            if ($orderUpdated->balance == 0 && $orderUpdated->total != 0) {
                $style = 'style="color:#0e9e33;font-weight:bold;"';
            } else
            if ($orderUpdated->balance < 0) {
                $style = 'style="color:#ff2f00;font-weight:bold;"';
            } else {
                $style = 'style="font-weight:bold;"';
            }
            $style_blance = '<tr ' . $style . ' class="data-balance"><td>' . trans('language.order.balance') . ':</td><td align="right">' . \Helper::currencyFormat($orderUpdated->balance) . '</td></tr>';
            return json_encode([
                'stt' => 1, 'msg' => [
                    'total'          => \Helper::currencyFormat($orderUpdated->total),
                    'subtotal'       => \Helper::currencyFormat($orderUpdated->subtotal),
                    'shipping'       => \Helper::currencyFormat($orderUpdated->shipping),
                    'discount'       => \Helper::currencyFormat($orderUpdated->discount),
                    'received'       => \Helper::currencyFormat($orderUpdated->received),
                    'balance'        => $style_blance,
                    'payment_status' => ($orderUpdated->payment_status == 2) ? '<span style="color:#0e9e33;font-weight:bold;">' . $this->statusPayment[$orderUpdated->payment_status] . '</span>' : (($orderUpdated->payment_status == 3) ? '<span style="color:#ff2f00;font-weight:bold;">' . $this->statusPayment[$orderUpdated->payment_status] . '</span>' : $this->statusPayment[$orderUpdated->payment_status]),
                ],
            ]);
        } else {
            return json_encode(['stt' => 0, 'msg' => 'Error ']);
        }
    }

    /**
     * [orderUpdateMobile description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function orderUpdateMobile(Request $request)
    {
        if (
            $request->header('user_id') == null
            || !isset($request->company_id)
            || (!isset($request->address1) || trim($request->address1) == '')
            || (!isset($request->phone))
            || (!isset($request->order_id))
        ) {
            return $this->sendError('There are Missing Data', 500);
        }
        if (!is_numeric($request['phone'])) {
            return $this->sendError('Phone Number Not Valid', 500);
        }
       /* $currency = ShopCurrency::where('company_id', $request->company_id)->where('order_default', 1)->first();
        if (!isset($currency)) {
            $currency =  new ShopCurrency();
            $currency->name = "Dinar";
            $currency->code = "KD";
            $currency->symbol = "KD";
            $currency->exchange_rate = 1;
            $currency->order_default = 1;
            $currency->status = 1;
            $currency->company_id = $request->company_id;
            $currency->save();
        }*/
        
        $shopOrder = ShopOrder::findOrFail($request->order_id);
        $shopOrder->address1 = $request['address1']??$shopOrder->address1;
        $shopOrder->address2 = $request['address2']?? $shopOrder->address2;
        $shopOrder->email  = $request['email'] ?? Admin::user()->email;
        $shopOrder->phone = $request['phone'];
        $shopOrder->company_id = $request['company_id'];
        $shopOrder->toname  =  Admin::user()->name;
        $shopOrder->save();
        $this->initOrderTotal($shopOrder->id,  $shopOrder->company_id);
        return $this->sendResponse($shopOrder, 200);
    }
    /**
     * [postAddItem description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postAddItem(Request $request)
    {
        $data     = $request->all();
        $order_id = $data['add_order'] ?? 0;
        $pQty     = $data['add_qty'] ?? 0;
        $pAttr    = $data['add_attr'] ?? null;
        $pId      = $data['add_id'] ?? 0;
        $pPrice   = $data['add_price'] ?? 0;
        $company_id =  $this->getUserCompany()[0]->id;
        $arrData  = array();
        if ($pId != 0) {
            $product    = ShopProduct::find($pId);
            $attDetails = $product->attDetails->pluck('name', 'id')->all();
            if (is_array($pAttr)) {
                foreach ($pAttr as $key => $value) {
                    $pAttr[$key] = $attDetails[$value];
                }
                $pAttr = json_encode($pAttr);
            }
            
            $arrData = array(
                'order_id'    => $order_id,
                'product_id'  => $pId,
                'name'        => $product->name,
                'qty'         => (int) $pQty,
                'price'       => (int) $pPrice,
                'total_price' => $pPrice * (int) $pQty,
                'sku'         => $product->sku,
                'attribute'   => $pAttr,
                'company_id'  => $company_id,
            );
            $rs = (new ShopOrderDetail)->insert($arrData);

            //Add history
            $dataHistory = [
                'order_id' => $order_id,
                'content'  => 'Add product ' . $product->name,
                'admin_id' => Admin::user()->id,
                'add_date' => date('Y-m-d H:i:s'),
            ];
            ShopOrderHistory::insert($dataHistory);

            //Update total price
            $subtotal = ShopOrderDetail::select(DB::raw('sum(total_price) as subtotal'))
                ->where('order_id', $order_id)
                ->first()->subtotal;
            $updateSubTotal = ShopOrderTotal::updateSubTotal($order_id, empty($subtotal) ? 0 : $subtotal);
            //end update total price
            if ($rs && $updateSubTotal === 1) {
                return json_encode(['error' => 0, 'msg' => '']);
            } else {
                return json_encode(['error' => 1, 'msg' => 'Error: ' . $updateSubTotal]);
            }
        }
    }

    /**
     * // deprecated
     * [postCreateOrder description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    
    public function postCreateOrder(Request $request)
    {
        if (
            $request->header('user_id') == null
            || !isset($request->company_id)
            || (!isset($request->address1) || trim($request->address1) == '')
            || (!isset($request->phone))
        ) {
            return $this->sendError('There are Missing Data', 500);
        }
        if (!is_numeric($request['phone'])) {
            return $this->sendError('Phone Number Not Valid', 500);
        }
        $currency = ShopCurrency::where('company_id', $request->company_id)->where('order_default', 1)->first();
        if (!isset($currency)) {
            $currency =  new ShopCurrency();
            $currency->name = "Dinar";
            $currency->code = "KD";
            $currency->symbol = "KD";
            $currency->exchange_rate = 1;
            $currency->order_default = 1;
            $currency->status = 1;
            $currency->company_id = $request->company_id;
            $currency->save();
        }

        $shopOrder = new ShopOrder();
        $shopOrder->user_id = $request->header('user_id');
        $shopOrder->address1 = $request['address1'];
        $shopOrder->address2 = $request['address2'];
        $shopOrder->email  = $request['email'] ?? Admin::user()->email;
        $shopOrder->phone = $request['phone'];
        $shopOrder->company_id = $request['company_id'];
        $shopOrder->currency = $currency->name;
        $shopOrder->toname  =  Admin::user()->name;
        $shopOrder->exchange_rate = $currency->exchange_rate;
        $shopOrder->save();
        $this->initOrderTotal($shopOrder->id,  $shopOrder->company_id);
        return $this->sendResponse($shopOrder, 200);
    }
    private function updateShopOrderTotal($id, $company_id, $code ,$value){
       $data = ShopOrderTotal::where(["code" => $code,"company_id" => $company_id, "order_id" => $id])->first();
       if($data != null){
         $data->value = $data->value + $value;   
         $data->save();
         return 1;
       }
    }
    private function initOrderTotal($id, $company_id)
    {
        $checkTotal = ShopOrderTotal::where('order_id', $id)->first();
        $shipping            = 'ShippingStandard';
        $installment         = 'ShippingBasic';
       
        $classShippingMethod = '\App\Extensions\Shipping\Controllers\\' . $shipping;
        $classInstallItem    =  '\App\Extensions\Shipping\Controllers\\' . $installment;
        $shippingMethod      = (new $classShippingMethod)->getData($company_id);
        $Install             = (new $classInstallItem)->getData($company_id);
      
        if (!$checkTotal) {
            ShopOrderTotal::insert([
                ['code' => 'subtotal', 'company_id' => $company_id, 'value' => 0, 'title' => 'Subtotal', 'sort' => 1, 'order_id' => $id],
                ['code' => 'shipping', 'company_id' => $company_id, 'value' => $shippingMethod["value"], 'title' => 'Shipping', 'sort' => 10, 'order_id' => $id],
                ['code' => 'discount', 'company_id' => $company_id, 'value' => 0, 'title' => 'Discount', 'sort' => 20, 'order_id' => $id],
                ['code' => 'installation', 'company_id' => $company_id, 'value' => $Install["value"], 'title' => 'install-item', 'sort' => 20, 'order_id' => $id],
                ['code' => 'total', 'value' => 0, 'company_id' => $company_id, 'title' => 'Total', 'sort' => 100, 'order_id' => $id],
                ['code' => 'received', 'value' => 0, 'company_id' => $company_id, 'title' => 'Received', 'sort' => 200, 'order_id' => $id],
            ]);
        }
    }
    /**
     * [addItemMobile description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addOrderItemMobile(Request $request)
    {
        $data     = $request->all();
        if (!isset($request->order_id)) {
            return $this->sendError('There are Missing Data', 500);
        }

        $order_id = $data['order_id'] ?? 0;
        $order    = ShopOrder::findOrFail($order_id);
        $pQty     = $data['qty'] ?? 0;
        $pAttr    = $data['attr'] ?? null;
        $pId      = $data['product_id'] ?? 0;
        $pPrice   = $data['price'] ?? 0;
        $arrData  = array();
        if ($pId != 0) {
            $product    = ShopProduct::findOrFail($pId);

            if ($order->company_id != $product->company_id) {
                return $this->sendError('In comppatable Order Select Your Cart From Same Factory', 500);
            }
            $attDetails = $product->attDetails->pluck('name', 'id')->all();
            if (is_array($pAttr)) {
                foreach ($pAttr as $key => $value) {
                    $pAttr[$key] = $attDetails[$value];
                }
                $pAttr = json_encode($pAttr);
            }
            $arrData = array(
                'order_id'    => $order_id,
                'product_id'  => $pId,
                'name'        => $product->name,
                'qty'         => (int) $pQty,
                'price'       => (int) $pPrice,
                'total_price' => $pPrice * (int) $pQty,
                'sku'         => $product->sku,
                'attribute'   => $pAttr,
                'company_id'  => $order->company_id
            );
            $rs = (new ShopOrderDetail)->insertGetId($arrData);
            
            //Add history
            $dataHistory = [
                'order_id' => $order_id,
                'company_id' => $order->company_id,
                'content'  => 'Add product ' . $product->name,
                'admin_id' => Admin::user()->id,
                'add_date' => date('Y-m-d H:i:s'),
            ];
            ShopOrderHistory::insert($dataHistory);

            //Update total price
            $subtotal = ShopOrderDetail::select(DB::raw('sum(total_price) as subtotal'))
                ->where('order_id', $order_id)
                ->first()->subtotal;
            $updateSubTotal = ShopOrderTotal::updateSubTotal($order_id, empty($subtotal) ? 0 : $subtotal);
            //end update total price
            if ($rs && $updateSubTotal === 1) {
                return json_encode(['success' => 200, 'data' => ["line_id"=> $rs]]);
            } else {
                return json_encode(['error' => 500, 'msg' => 'Error: ' . $updateSubTotal]);
            }
        }
    }

    public function editOrderItem(Request $request)
    {

        $data   = $request->all();
        if (
           ( !isset($request->product_id)
            || !isset($request->order_id)
            || $request->header('user_id') == null) && !isset($request->line_id)
        ) {
            return $this->sendError('There are Missing Data', 500);
        }
        $pQty   = $data['qty'] ?? 0;
        $pAttr  = $data['attr'] ?? null;
        $pId    = $data['product_id'] ?? 0;
        $pPrice = $data['price'] ?? 0;
        $pOrder = $data['order_id'] ?? 0;
        $line_id = $data['line_id'];
        // $pName  = $data['name'] ?? '';
        $data   = array(
            'qty'         => $pQty,
            'price'       => $pPrice,
            //   'name'        => $pName,
            'total_price' => $pQty * $pPrice,
            'attribute'   => $pAttr
        );
        //print_r($data);die;
        try {
            $rs = (new ShopOrderDetail)->updateDetail($pId, $pOrder,$line_id, $data);
            //   print_r((new ShopOrderDetail)->where('order_id',$pOrder)->get());die;
            //Add history
            $dataHistory = [
                'order_id' => $pOrder,
                'content'  => trans('language.product.edit_product') . ' #' . $pId . ': Data ' . json_encode($data),
                'admin_id' => Admin::user()->id,
                'add_date' => date('Y-m-d H:i:s'),
            ];
            ShopOrderHistory::insert($dataHistory);

            //Update total price
            $subtotal = ShopOrderDetail::select(DB::raw('sum(total_price) as subtotal'))
                ->where('order_id', $pOrder)
                ->first()->subtotal;
            ShopOrderTotal::updateSubTotal($pOrder, $subtotal);
            //end update total price
            $arrayReturn = ['success' => 200, 'msg' => 'item was updated'];
        } catch (\Exception $e) {
            $arrayReturn = ['error' => 1, 'msg' => $e->getMessage()];
        }
        return json_encode($arrayReturn);
    }
    public function postEditItem(Request $request)
    {
        $data   = $request->all();
        $pQty   = $data['pQty'] ?? 0;
        $pAttr  = $data['pAttr'] ?? null;
        $pId    = $data['pId'] ?? 0;
        $pPrice = $data['pPrice'] ?? 0;
        $pOrder = $data['pOrder'] ?? 0;
        $pName  = $data['pName'] ?? '';
        $data   = array(
            'qty'         => $pQty,
            'price'       => $pPrice,
            'name'        => $pName,
            'total_price' => $pQty * $pPrice,
            'attribute'   => $pAttr,
        );
        try {
            $rs = (new ShopOrderDetail)->updateDetailFront($pId, $pOrder, $data);
            //Add history
            $dataHistory = [
                'order_id' => $pOrder,
                'content'  => trans('language.product.edit_product') . ' #' . $pId . ': Data ' . json_encode($data),
                'admin_id' => Admin::user()->id,
                'add_date' => date('Y-m-d H:i:s'),
            ];
            ShopOrderHistory::insert($dataHistory);

            //Update total price
            $subtotal = ShopOrderDetail::select(DB::raw('sum(total_price) as subtotal'))
                ->where('order_id', $pOrder)
                ->first()->subtotal;
            ShopOrderTotal::updateSubTotal($pOrder, $subtotal);
            //end update total price
            $arrayReturn = ['error' => 0, 'msg' => ''];
        } catch (\Exception $e) {
            $arrayReturn = ['error' => 1, 'msg' => $e->getMessage()];
        }
        return json_encode($arrayReturn);
    }

    /**
     * [postDeleteItem description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postDeleteItem(Request $request)
    {
        $data       = $request->all();
        $pId        = $data['pId'] ?? 0;
        $itemDetail = (new ShopOrderDetail)->where('id', $pId)->first();
        $order_id   = $itemDetail->order_id;
        $product_id = $itemDetail->product_id;
        $qty        = $itemDetail->qty;
        $rs         = $itemDetail->delete(); //Remove item from shop order detail
        //Update total price
        $subtotal = ShopOrderDetail::select(DB::raw('sum(total_price) as subtotal'))
            ->where('order_id', $order_id)
            ->first()->subtotal;
        $updateSubTotal = ShopOrderTotal::updateSubTotal($order_id, empty($subtotal) ? 0 : $subtotal);
        $item           = ShopProduct::find($product_id);
        $item->stock    = $item->stock + $qty; // Restore stock
        $item->sold     = $item->sold - $qty; // Subtract sold
        $item->save();

        //Add history
        $dataHistory = [
            'order_id' => $order_id,
            'content'  => 'Remove item pID#' . $pId,
            'admin_id' => Admin::user()->id,
            'add_date' => date('Y-m-d H:i:s'),
        ];
        ShopOrderHistory::insert($dataHistory);

        //end update total price
        if ($rs && $updateSubTotal === 1) {
            return json_encode(['error' => 0, 'msg' => '']);
        } else {
            return json_encode(['error' => 1, 'msg' => 'Error: ' . $updateSubTotal]);
        }
    }
    /**
     * [deleteOrderItemMobile description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
   public function deleteOrderItemMobile(Request $request)
    {
        try{
        $data       = $request->all();
        if ($data['order-id']==null  || $data['line-id'] == null ) {
            return $this->sendError('There are Missing Data', 500);
        }
        $order_id  = $data['order-id'];
        $pId        = $data['pId'] ?? 0;
        $line_id    = $data['line-id'];
        $itemDetail = null;
        if(isset($request->order_id) && isset($request->product_id)){
        $itemDetail = (new ShopOrderDetail)->where('id', $line_id)->where('product_id', $pId)->where('order_id', $order_id)->first();
        }else if($data['order-id']!= null) {
            $itemDetail = (new ShopOrderDetail)->where('order_id',$order_id)->where('id', $line_id)->first();
        }else{
            return $this->sendError('There are Missing Data order', 500);
        }
        $product_id = $itemDetail->product_id;
        $qty        = $itemDetail->qty;
        $rs         = $itemDetail->delete(); //Remove item from shop order detail
        //Update total price
        $subtotal = ShopOrderDetail::select(DB::raw('sum(total_price) as subtotal'))
            ->where('order_id', $order_id)
            ->first()->subtotal;
        $updateSubTotal = ShopOrderTotal::updateSubTotal($order_id, empty($subtotal) ? 0 : $subtotal);
        $item           = ShopProduct::find($product_id);
        $item->stock    = $item->stock + $qty; // Restore stock
        $item->sold     = $item->sold - $qty; // Subtract sold
        $item->save();

        //Add history
        $dataHistory = [
            'order_id' => $order_id,
            'content'  => 'Remove item pID#' . $pId,
            'admin_id' => Admin::user()->id,
            'add_date' => date('Y-m-d H:i:s'),
        ];
        ShopOrderHistory::insert($dataHistory);

        //end update total price
        if ($rs && $updateSubTotal === 1) {
            return json_encode(['error' => 0, 'msg' => '']);
        } else {
            return json_encode(['error' => 1, 'msg' => 'Error: ' . $updateSubTotal]);
        }
    }catch(\Exception $e){
        return $this->sendError("internal error", 400);
    }
    }

    public function getUserOrder(Request $request)
    {
        try{
            
           $validatedData = $request->validate([
                'status' => 'required|integer',
            ]);
			if(!$validatedData){
			   return $this->sendError("data invalid", 400);	
			}
        $status = $request->status;
			$orders = null;
			if($status >= 0){
        $orders = ShopOrder::where('user_id', $request->header('user_id'))
			->orderBy('shop_order.id', 'desc')
            ->where('shop_order.status', $status)
            ->with('shippingCost')
            ->with('discountOrder')
            ->with('installation')
            ->with('orderStatus')
            ->with('userAddress')
            ->paginate(20);
        }
        else if ($status == -1) {
            $orders = ShopOrder::where('user_id', $request->header('user_id'))
            ->orderBy('shop_order.id', 'desc')
            ->with('shippingCost')
            ->with('discountOrder')
            ->with('installation')
            ->with('orderStatus')
            ->with('userAddress')
            ->paginate(20);
		}
        return $this->sendResponse($orders, 200);
        }catch(\Exception $e){
            return $this->sendError($e->getMessage(), 400);
        }
    }

    public function SetOrderCoupon($order_id, $company_id, $coupon_code){
      $check = DiscountModel::where('company_id', $company_id)
                           ->where('code',$coupon_code)
                           ->where('status', 1)
                           ->whereDate('IFNULL(expires_at, NOW())', '>=', 'NOW()')->get();
         if($check){
             $this->updateOrderTotal( $order_id, $company_id, $check);
         }
         else{
             return 0;
         }
    }
    
    private function createOrder($id, $company_id, $address, $user_id, $currency, $s_address, $coupon, $type){
        if($id != null){
            $shopOrder = ShopOrder::findOrFail($id);
            if($shopOrder->company_id == $company_id){
                return  $shopOrder;
            }
        }
        
        $shopOrder = new ShopOrder();
        $shopOrder->user_id = $user_id;
        $shopOrder->address1 = $address;
        $shopOrder->type = $type;
    //    $shopOrder->email  = $address['email']??Admin::user()->email;
      //  $shopOrder->phone = $address['phone'];
        $shopOrder->company_id = $company_id;
        $shopOrder->currency = $currency->name;
        //$shopOrder->toname  =  $address['f_name'].' '.$address['l_name'];
        $shopOrder->exchange_rate = $currency->exchange_rate;
        $shopOrder->created_at =  date('Y-m-d H:i:s');
        $shopOrder->status = 0;
        $shopOrder->company_id = $company_id;
         if($s_address != null){
             $address2 = json_decode($s_address, 200);
             $shopOrder->country = $address2['country']; 
             $shopOrder->toname = $address2['name'];
             $shopOrder->phone = $address2['phone'];
             $shopOrder->company_name = $address2['stall_name']??null;
             $shopOrder->email = $address2['email']??null;
         }
        $shopOrder->save();
       
        
        $this->initOrderTotal($shopOrder->id, $company_id);
        $dataHistory = [
            'order_id' => $shopOrder->id,
            'content'  => 'New order',
            'user_id'  => Admin::user()->id ?? 0,
            'add_date' => date('Y-m-d H:i:s'),
            'company_id' => $company_id
        ];
        
        if($coupon != null){
        $this->updateOrderTotal($shopOrder, $company_id, $coupon);
        }
        ShopOrderHistory::insert($dataHistory);

        return $shopOrder;
    }

    private function updateOrderTotal($orderObj, $company_id, $coupon){
        $order = $orderObj;
        if($order->company_id != $company_id){
            return $this->sendError('Order not found',500);
        }
        if($order->company_id != $coupon->$company_id){
            return $this->sendError('coupon not found',500);
        }
        $couponOrder = new CouponOrder();
        $couponOrder->order_id = $order->id;
        $couponOrder->company_id = $company_id;
        $couponOrder->customer_id =  $order->user_id??Admin::user()->id;
        $couponOrder->coupon_name = $coupon->code;
        $couponOrder->coupon_id = $coupon->id;
        if($coupon->type == 0){
          $order->discount = $order->discount??0 + $coupon->reward;
          $coupon->number_uses = $coupon->number_uses + 1;
        }else if($coupon->type == 2){
            $value =($order->subtotal * $coupon->reward) / 100; 
            $order->discount = $order->discount??0 +  $value;
            $coupon->number_uses = $coupon->number_uses + 1;
          
        } 
        $couponOrder->discount_value = $order->discount;
        $order->total = $order->total - $order->discount;
        
        $coupon->save();
        $order->save();
        $couponOrder->save();
        $this->updateShopOrderTotal($order->id, $company_id, 'discount', $coupon->reward);
        return $this->sendResponse($order, 200);
    }
    public function applyCoupon(Request $request, $code, $factory, $order_id){
        if($request->header('user-id')== null || !is_numeric($request->header('user-id')))
        {
          return $this->sendError("data Missing", 400);
        }
        try{
        $user_id = $request->header('user-id');
        $discount = DiscountModel::where(["code" => $code, "status" => 1, "company_id" => $factory])
                                   ->whereRaw("IFNULL(expires_at, now() + INTERVAL 1 DAY) > now()")
                                   ->first();
        $data = array();
        $data["id"] = $discount->id;
        $data["reward"] = $discount->reward;
        $data["type"]= $discount->type;                           
       if($discount != null){
           $order= ShopOrder::findOrFail($order_id);
          $this->updateOrderTotal($order, $factory, $discount);
          return $this->sendResponse($data, 200);
       }
        }catch(\Exception $ex){
             return $this->sendError($ex->getMessage(), 400);
        }                           
    }
    //create order with products
    public function storeOrderMobile(Request $request)
    {
        if($request->header('user-id')== null || !is_numeric($request->header('user-id')))
        {
          return $this->sendError("data Missing", 400);
        }
        $user_id = $request->header('user-id');
        $type = 'NORMAL'; 
       
        if($request->type != NULL){
           if($request->type == 'RESERVE' || $request->type=='NORMAL'){
               $type = $request->type;
             
           } 
        }
       /* if(Admin::user()->id != $user_id)
        { //try convert to string
            return $this->sendError("Incompatable Data", 400);
        }*/
        //Not allow for guest
        $data = request()->all();
        
        if (!$data) {
            return $this->sendError([], 400);
        } else {
        
            if(!isset($data['address'])){
                return $this->sendError([], 401);
            }
            $address = $data['address'];
          
            
            $coupon = null;
            if(isset($data['coupon_id'])){
               $discount = $data['coupon_id'];
               $coupon = DiscountModel::findOrFail($discount);
            }
            $items     = $data['items'];
         //   $payment   = $data['payment'];
            //$shipping  = $data['shipping'];
        }
        try {
            //Insert to Order
            $currency = ShopCurrency::where('company_id', 1)->where('order_default', 1)->first();
            if (!isset($currency)) {
                $currency =  new ShopCurrency();
                $currency->name = "DINAR";
                $currency->code = "KWD";
                $currency->symbol = "KWD";
                $currency->exchange_rate = 1;
                $currency->order_default = 1;
                $currency->status = 1;
                $currency->company_id = $request->company_id;
                $currency->save();
            }
            DB::beginTransaction();
            $productCount = 0;
            $order = null;
            //$items urldecode($data['items']);
            $address2 = null;
            if(isset($data['address2'])){
                $address2 = $data['address2'];
            }
           
            $items = json_decode($items, 200);
            $set = array();
            foreach ($items as $value) {
                if(!is_numeric($value['id']) || !is_numeric($value['qty'])){
                    return $this->sendError("data error",400);
                }
                $product = ShopProduct::findOrFail($value['id']);
              //    return $this->sendResponse($address,200);
                if($productCount == 0){
                $order = clone $this->createOrder(null, $product->company_id, $address, $user_id, $currency, $address2, $coupon, $type);
                
                } else {
                  $order = clone $this->createOrder($order->id, $product->company_id, $address, $user_id, $currency, $address2, $coupon, $type);
                  
                }
                if(!in_array($order->id, $set)){
                    array_push($set, $order->id);
                }
             //   return $product;
                $arrDetail['order_id']    = $order->id;
                $arrDetail['company_id']  = $order->company_id;
                $arrDetail['product_id']  = $value['id'];
                $arrDetail['name']        = $product->name;
                $arrDetail['price']       = \Helper::currencyValue($product->price);
                $arrDetail['qty']         = $value["qty"];
                //$arrDetail['attribute']   = ($value->options->att) ? json_encode($value->options->att) : null;
                $arrDetail['sku']         = $product->sku;
                $arrDetail['total_price'] = \Helper::currencyValue($product->price) * $value["qty"];
                $arrDetail['created_at']  = date('Y-m-d H:i:s');
                ShopOrderDetail::insert($arrDetail);
                $subtotal = ShopOrderDetail::select(DB::raw('sum(total_price) as subtotal'))
                ->where('order_id', $order->id)
                ->first()->subtotal;
			
            $updateSubTotal = ShopOrderTotal::updateSubTotal($order->id, empty($subtotal) ? 0 : $subtotal);
                //If product out of stock
                //!$this->configs['product_buy_out_of_stock'] &&
                if ( !$product->pay_out_of_stock && $product->stock < $value['qty']) {
                    return $this->sendError('no avaliable quatity', 400);
                } //
                $product->stock -= $value['qty'];
                $product->sold += $value['qty'];
                $product->save();
               $productCount++;
            }
            
            DB::commit();
            $namedSet = array();
            foreach($set as $value){
                array_push($namedSet, array("order_id" =>$value));
            }
            return $this->SendResponse($namedSet, 200);

        } catch (\Exception $e) {
            DB::connection('mysql')->rollBack();
            echo 'Caught exception: ', $e, "\n";

        }

    }

}
