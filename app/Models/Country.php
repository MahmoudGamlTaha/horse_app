<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Country extends Model{
    protected $table = "countries";
    public function city(){
        return $this->hasMany(City::class, "country_id", "id");
    }
}