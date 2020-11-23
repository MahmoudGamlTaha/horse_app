<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Uofms extends Model
{// sprint 3
  protected $table ="uofms";
  protected $appends = [
   'uofmg'
  ];
  public function getGroup(){
   return $this->belongsTo(UofmGroups::class, "group_id", "id");
  }
  
  public function getUofmgAttribute(){
    $group = $this->getGroup()->get()->first();
    if($group  == null){
      return null;
    }
    return $this->getGroup()->get()->first()->name;
  }
}