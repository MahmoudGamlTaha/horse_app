<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Session;
class Cors 
{

public function handle($request, Closure $next)
{
    return $next($request)
    ->header('Access-Control-Allow-Origin', '*')
    ->header( 'Access-Control-Allow-Methods','GET, POST, PUT, DELETE, PATCH, OPTIONS, HEAD')
    ->header('Access-Control-Allow-Headers', 'Content-Type,X-Requested-With, append,delete,entries,foreach,get,has,keys,set,values,Authorization');

}

}