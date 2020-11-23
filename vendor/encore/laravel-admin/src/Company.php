<?php
namespace Encore\Admin;

use Illuminate\Database\Eloquent\Model;

class company extends Model
{
   protected $table = 'companies';
   protected $fillable = ['id', 'name', 'activity_id', 'notes'];
}