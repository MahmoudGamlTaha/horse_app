<?php
#app/Models/LayoutUrl.php
namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
    
    public $table      = 'champoines';

    public function meta_data(){
       return $this->hasMany(ChampionDescription::class, 'champion_id', 'id');
    }
    public function info(){
        return $this->hasMany(ChampionDescription::class, 'champion_id', 'id');
     }
    public function result(){
        return $this->hasMany(ChampionDetails::class, 'champion_id', 'id')->with("description");
    }
    public function classes_happen(){
        return $this->hasMany(Champion::class, "parent_id", "id")->with("result");
    }
    public function getChampions($lang_id, $type, $date){
        $str = '';
        if($date == null){
            $date = date("Y-m-d");
            $str = 'datediff(date(date) , date("'. $date . '")) > 0';
        } else {
            $str = 'year(date) = '.$date;
        }
        //\DB::connection()->enableQueryLog();
        return  $this->where("active", 1)
           ->whereIn('type', $type)
           ->whereRaw($str)
            ->with(["meta_data" => function($query) use($lang_id){
                $query->where("lang_id", $lang_id);
            }])
            ->with("result")
            ->with("classes_happen")
            ->paginate(20);
        //    dd(\DB::getQueryLog());
    }
    public function getChampionsYears(){
        return $this->where('active', 1)->selectRaw("distinct year(date) years")->pluck("years");
    }

    public function getChampionsWithCriteria($lang_id, $country, $title){
        $data =  $this->where("active", 1)
            ->with(["meta_data" => function($query) use($lang_id, $country, $title){
                 $query = $query->where("lang_id", $lang_id);
                   if($country != null){
                    $query = $query->where("country", $country);
                   }
                   $query->whereRaw("name LIKE '". $title."%'");

            }])
            ->with("result")
            ->paginate(20);
            foreach($data as $key => $item){
                if(count($item->meta_data) == 0){
                    $data->forget($key);
                }
            }
            return $data;
    }
    
}
