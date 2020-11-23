<?php
#app/Models/LayoutUrl.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChampionDetails extends Model
{
    
    public $table      = 'champion_details';

    public function description(){
        return $this->hasMany(ChampionDetailsDescription::class, 'champion_detail_id', 'id');
    }
  
}
