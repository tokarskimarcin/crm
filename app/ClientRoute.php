<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientRoute extends Model
{
    protected $table = 'client_route';
    protected $guarded = array();
    public $timestamps = false;

    //This function generates query builder where are selected only clientRoutes with status 1
    public function scopeOnlyActiveClientRoutes($query) {
        $query->where('client_route.status', '=', 1);
    }

    /**
     * @param $id - client route id
     * This method delete client route by changing it's status to 0
     */
    public static function safeDelete($id) {
        ClientRoute::find($id)->update(['status' => 0]);
        ClientRouteInfo::where('client_route_id', '=', $id)->where('status', '=', 1)->update(['status' => 0]);
    }
}
