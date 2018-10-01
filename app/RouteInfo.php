<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RouteInfo extends Model
{
    protected $table = 'routes_info';
    protected $fillable = ['status'];
    public $timestamps = false;

    public function voivode() {
        return $this->belongsTo('App\Voivodes','voivodeship_id');
    }
    public function city() {
        return $this->belongsTo('App\Cities','city_id');
    }

    public static function disableRouteTemplates($cityId) {
        //all route info records with city_id
        $allTemplateInfo = RouteInfo::where('city_id', '=', $cityId)
            ->where('status', '=', 1)
            ->get();

        //disable all routes with given city
        foreach($allTemplateInfo as $templateInfo) {
            Route::disableRouteTemplate($templateInfo->routes_id);
        }
    }
}
