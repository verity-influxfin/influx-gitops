<?php

namespace App\Http\Controllers;

use App\Models\RouteMeta;
// use Illuminate\Http\Request;

class RouteMetaController extends Controller
{
   public function getMeta($route_path)
   {
       return RouteMeta::where('route_path', $route_path)->first();
   }
}
