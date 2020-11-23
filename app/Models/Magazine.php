<?php
#app/Models/LayoutUrl.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Magazine extends Model
{
    
    public $table      = 'magazine';
    public function topic(){
        return $this->hasMany(MagazineTopic::class,'magazine_id');
    }
    
    public function getMagazines($lang = null){
        $lang = $lang?? 1;
        return $this->where("active", 1)
               ->leftJoin('magazine_desc', 'magazine_desc.magazine_id', 'magazine.id')
               ->where('magazine_desc.lang_id', $lang)
               ->select(['magazine.id','magazine_desc.title', 'magazine.path','magazine.logo', 'magazine.id', 'magazine.active', 'magazine.updated_at', 'magazine.created_at'])
               ->get();
    }
}
