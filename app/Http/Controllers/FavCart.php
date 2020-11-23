<?php
#app/Http/Controller/ShopCart.php
namespace App\Http\Controllers;

use App\Models\ShopAttributeGroup;
use App\Models\ShopCurrency;
use App\Models\ShopOrder;
use App\Models\ShopOrderDetail;
use App\Models\ShopOrderHistory;
use App\Models\ShopOrderTotal;
use App\Models\ShopProduct;
use App\User;
use Cart;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Facades\Admin;

class FavCart extends GeneralController
{
    public function __construct()
    {
        parent::__construct();

    }   

/**
 * [addToCart description]
 * @param Request $request [description]
 */
public function addFavMobileCart(Request $request)
{
    if($request->header('userId')== null || !is_numeric($request->header('userId')))
    {
      return $this->sendError("data Missing", 400);
    }
    
    $user_id = $request->header('userId');
    
    $instance = request('instance') ?? 'fav';
    
    $id             = request('product_id');
    $attribute      = request('attribute') ?? null;
    $opt_sku        = request('opt_sku') ?? null;
    $qty            = 1;
    $options        = [];
    $options['att'] = $attribute;
    $options['opt'] = $opt_sku;

    $product        = ShopProduct::findOrFail($id);
    
    $cart     = \Cart::instance($product->company_id, $user_id, $instance);
    $itemAdded  = null;
    switch ($instance) {

        case 'fav':
            //Wishlist or Compare...
            ${'arrID' . $instance} = array_keys($cart->content()->groupBy('id')->toArray());
            if (!in_array($id, ${'arrID' . $instance})) {
                try {
                    $itemAdded =  $cart->addItemAuth(
                        $user_id,
                        $id,
                        $product->company_id,
                        $product->name,
                        $qty,
                        $product->getPrice($id),
                        $options 
               );
                } catch (\Exception $e) {
                    return response()->json(
                        [
                            'error' => 1,
                            'msg'   => $e->getMessage(),
                            'item' =>$itemAdded??[]
                        ]
                    );
                }

            } else {
                return response()->json(
                    [
                        'error' => 1,
                        'msg'   => trans('language.cart.exist', ['instance' => $instance]),
                        'item' =>$itemAdded??[]
                    ]
                );
            }
            break;
            default:
            return response()->json(
                [
                    'error' => 1,
                    'msg'   => trans('language.cart.not_exist', ['instance' => $instance]),
                ]
            );

    }
  //  dd($cart->getInstance());   
    $carts = \Helper::getListCart($instance, $product->company_id, $user_id);
    return response()->json(
        [
            'error'      => 0,
            'count_Fav' => $carts['count'],
            'instance'   => $instance,
            'item' =>$itemAdded??[],
            'msg'        => trans('language.cart.success', ['instance' => ($instance == 'default') ? 'cart' : $instance]),
        ], 
        200
    );

}
    
    public function removeCartFav(Request $request)
    {
        if(!is_numeric($request->header('user-id')))
        {
          return $this->sendError("data Missing", 400);
        }
        
       if(is_null($request->get('rowId'))){
            return $this->sendError("data Missing id", 400);
        }
        $user_id = $request->header('user-id');
        $id      = $request->get('id');
        $rowId   = $request->get('rowId');
        $instance = 'fav';
    
        $carts = \Cart::instance(0, $user_id, $instance);
       // $carts
        $product = ShopProduct::find($id);
        $new_qty = 0;
     
           $affected = $carts->update($rowId, ($new_qty) ? $new_qty : 0, $user_id, $product->id);
          if(!$affected){
            return response()->json(['success' => false, 'message'=> 'no rowid or product cart for this company'],400);
          }
            return response()->json(
                ['success' => true,
                 'message' => 'updated successfully'
            ], 200);
        

    }

/**
 * [clearCart description]
 * @return [type] [description]
 */
    public function clearCart()
    {
        Cart::destroy();
        return redirect()->route('cart');
    }


    public function getMobileFav(Request $request, $user_id)
    {
      try{
      $instance = request('instance') ?? 'fav';
      $company = $request['company_id']??'0';
      if(!is_numeric($user_id)){
          return $this->sendError("error user",400);
      }                               
      $carts = \Helper::getListCart($instance, $company, $user_id);
      return $this->sendResponse($carts, 200);
      }catch(\Exception $e){
         return $this->sendError("please login".$e->getMessage(), 401);
      }
    
       
    }


}
