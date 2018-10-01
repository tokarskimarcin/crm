<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = 'routes';
    protected $fillable = ['status'];
    public $timestamps = false;

    /**
     * @param $id - This method disable route templates
     */
    public static function disableRouteTemplate($id) {
        Route::where('id', '=', $id)->first()->update(['status' => 0]);
        RouteInfo::where('routes_id', '=', $id)->update(['status' => 0]);
    }
}
