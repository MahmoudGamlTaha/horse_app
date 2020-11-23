<?php
#app/Models/LayoutUrl.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagazineTopic extends Model
{
    
    public $table      = 'magazine_topic';

    public function magazine(){
        return $this->belongsTo(Magazine::class, 'magazine_id', 'id');
    }
    public function category(){
       return $this->belongsTo(TopicCategory::class, 'category_id', 'id');
    }
    public function getMagazineTopic($magazine_id, $lang = null){
        $lang = $lang?? 1;
        return $this->where('active', 1)
               ->leftJoin('magazine_topic_desc', 'magazine_topic_desc.magazine_topic_id', 'magazine_topic.id')
               ->where('magazine_topic_desc.lang_id', $lang)
               ->where('magazine_topic.magazine_id', $magazine_id)
               ->select(['magazine_topic.id',
                'magazine_topic.magazine_id',
                'magazine_topic.date', 
                'magazine_topic.image',
                'magazine_topic.path', 'magazine_topic_desc.content', 'magazine_topic_desc.title', 'magazine_topic.company_id'])
                ->paginate(20);
               
    }
}
