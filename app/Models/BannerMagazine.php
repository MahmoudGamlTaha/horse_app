<?php
#app/Models/Banner.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerMagazine extends Model
{
    public $table = 'magazine_banner';
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

    public function magazine(){
        return $this->belongsTo(Magazine::class, 'magazine_id', 'id');
    }

}
