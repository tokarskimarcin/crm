<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RouteInfo extends Model
{
    protected $table = 'routes_info';
    public $timestamps = false;

    public function voivode() {
        return $this->belongsTo('App\Voivodes','voivodeship_id');
    }
    public function city() {
        return $this->belongsTo('App\Cities','city_id');
    }
}
