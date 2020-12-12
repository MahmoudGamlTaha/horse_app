<?php
#app/Models/Banner.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genealogy extends Model
{
    public $table = 'genealogy';
    protected $guarded = [];  

  
    public function scopeSort($query, $column = null)
    {
        $column = $column ?? 'sort';
        return $query->orderBy($column, 'asc')->orderBy('id', 'desc');
    }
    public function ancesstor(){
        return $this->hasMany(Banner::class, 'id', 'parent_id');
    }

}
