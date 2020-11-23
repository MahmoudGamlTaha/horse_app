<?php
namespace App\Models;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;


class ProductPriceList extends Model {
  protected $table = "product_price_list";
  protected $appends = [
    'uofmg',
    'unit'
   ];
//sprint 3
  function compnayPriceList(){
      return $this->belongsTo(company::class, "company_id", "id");
  }
  public function product()
  {
      return $this->belongsTo(ShopProduct::class, 'product_id', 'id');
  }
  public function unit(){
    return $this->belongsTo(UofM::class, 'uof_id', 'id');
  }
 public function group(){
  return $this->belongsTo(UofmGroups::class, 'uof_group', 'id');
 }
  public function getUofmgAttribute(){
    $group = $this->group()->get()->first();
    if($group != null)
      return  $this->group()->get()->first()->name;
   return null;  
  }

  public function getUnitAttribute(){
    
    $unit = $this->unit()->get()->first();
    if($unit != null){
      if(app()->getLocale() == "ar")
      return $unit->name;
      else 
      return $unit->code;
    }  
    return '';
  } 
}
?>