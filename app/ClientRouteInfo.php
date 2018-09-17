<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientRouteInfo extends Model
{
    protected $table = 'client_route_info';
    protected $guarded = array();
    public $timestamps = false;

    public function scopeOnlyActive($query) {
        $query->where('client_route_info.status', '=', 1);
    }

}
