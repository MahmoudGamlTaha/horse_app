<?php
#app/Models/LayoutUrl.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PayPal\Api\Details;

class ChampionDescription extends Model
{
    
    public $table      = 'champoines_desc';

    public function getCountries($lang_id)
    {
        $this->selectRaw("distict country")
          ->where("lang_id", $lang_id)
          ->pluck("country");
    }
}
