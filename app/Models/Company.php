<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
   protected $table = 'companies';
   protected $fillable = ['id', 'name', 'activity_id', 'notes'];

   public function getContact(){
      return $this->hasMany(\App\Models\CompanyContact::class, 'company_id', 'id');
   }
   public function CompanyContact(){
    return $this->belongsTo(\App\Models\CompanyContact::class, 'company_id', 'id');
 }
 public function company_contact(){
    return $this->hasMany(\App\Models\CompanyContact::class, 'company_id', 'id');
 }
   public function products(){
      return $this->hasMany(ShopProduct::class,'company_id','id');
   }
   public function activity(){
      return $this->belongsTo(\App\Models\ShopActivity::class, 'activity_id', 'id');
   }
     
    public function local()
    {
        $lang = Language::pluck('id', 'code')->all();
        return ShopCategoryDescription::where('shop_category_id', $this->id)
            ->where('lang_id', $lang[app()->getLocale()])
            ->first();
    }

    public function configs(){
        return $this->hasMany(Config::class, 'company_id', 'id');
    }

    public function globalConfigs() {
        return $this->hasMany(ConfigGlobal::class, 'company_id', 'id');
    }


}