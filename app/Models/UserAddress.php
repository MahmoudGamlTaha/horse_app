<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class UserAddress extends Model{
  protected $table = "order_addresses";

  public function user(){
    return $this->belongsTo('App\User', 'user_id', 'id');
  }
  public function order(){
    return $this->belongsTo(ShopOrder::class, 'order_id', 'id');
  }
  public function city(){
    return $this->belongsTo(City::class, 'city_id', 'id');
  }
}