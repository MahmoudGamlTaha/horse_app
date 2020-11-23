<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AddressOrder extends Model{
  protected $table = "order_to_address";

    public function order(){
    return $this->belongsTo(ShopOrder::class, 'order_id', 'id');
  }
  public function userBillingAddress(){
    return $this->belongsTo(UserAddress::class, 'bill_address_id', 'id');
  }
  public function userShippingAddress(){
    return $this->belongsTo(UserAddress::class, 'ship_address_id', 'id');
  }
}