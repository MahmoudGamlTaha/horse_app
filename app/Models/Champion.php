<?php
#app/Models/LayoutUrl.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
    
    public $table      = 'champoines';

    public function description(){
        return $this->hasMany(ChampionDescription::class, 'champion_id', 'id');
    }
    public function result(){
        return $this->hasMany(ChampionDetails::class, 'champion_id', 'id')->with("description");
    }

    public function getChampions($lang_id, $type){
        return $this->where("active", 1)
           ->whereIn('type', $type)
            ->with(["description" => function($query) use($lang_id){
                $query->where("lang_id", $lang_id);
            }])
            ->with("result")
            ->paginate(20);
    }
}
