<?php
#app/Models/LayoutUrl.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicCategory extends Model
{
    
    public $table      = 'magazine_topic_categories';
    public function topic(){
        return $this->hasMany(MagazineTopic::class,'magazine_id');
    }    
}
