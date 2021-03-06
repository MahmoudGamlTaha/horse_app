<?php
#app/Models/LayoutUrl.php
namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Facades\Admin;
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
               ->leftJoin('article_like', function($join){
                   $join->on('article_like.article_id','=','magazine_topic.id');
                   $join->on('article_like.user_id','=', DB::raw(Admin::user() == null? 0:Admin::user()->id));
               })
               ->where('magazine_topic_desc.lang_id', $lang)
               ->where('magazine_topic.magazine_id', $magazine_id)
               ->select(['magazine_topic.id',
                'magazine_topic.magazine_id',
                'magazine_topic.date', 
                'magazine_topic.image',
                'magazine_topic.path', 'magazine_topic_desc.content', 'magazine_topic_desc.title', 'magazine_topic.company_id', DB::raw('IFNULL(article_like.likes,0) AS `like`')])
                ->paginate(20);
               
    }
    public function searchMagazineTopic($param, $lang = null){
        $lang = $lang?? 1;
        return $this->where('active', 1)
               ->leftJoin('magazine_topic_desc', 'magazine_topic_desc.magazine_topic_id', 'magazine_topic.id')
               ->leftJoin('article_like', function($join){
                   $join->on('article_like.article_id','=','magazine_topic.id');
                   $join->on('article_like.user_id','=', DB::raw(Admin::user() == null? 0:Admin::user()->id));
               })
               ->where('magazine_topic_desc.lang_id', $lang)
               ->whereRaw('magazine_topic_desc.title like "'. $param.'%" OR magazine_topic_desc.content like "'. $param.'%"')
               //->orWhere('magazine_topic_desc.content', 'like', $param.'%')
               ->select(['magazine_topic.id',
                'magazine_topic.magazine_id',
                'magazine_topic.date', 
                'magazine_topic.image',
                'magazine_topic.path', 'magazine_topic_desc.content', 'magazine_topic_desc.title', 'magazine_topic.company_id', DB::raw('IFNULL(article_like.likes,0) AS `like`')])
                ->paginate(20);
               
    }
}
