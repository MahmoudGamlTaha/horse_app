<?php
#app/Models/Banner.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horse extends Model
{
    public $table = 'horses';
    protected $guarded = [];  

    /**
     * [getImage description]
     * @return [type] [description]
     */
    public function getImage()
    {
        $path_file = config('filesystems.disks.path_file', '');
        return $path_file . '/' . $this->image;

    }
//Scort
    public function scopeSort($query, $column = null)
    {
        $column = $column ?? 'sort';
        return $query->orderBy($column, 'asc')->orderBy('id', 'desc');
    }

    public function champions(){
        return $this->hasMany(ChampionDetails::class, 'id', 'parent_id');
    }

}
