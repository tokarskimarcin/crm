<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientRouteCampaigns extends Model
{
    protected $table = 'client_route_campaigns';
    //

    public function scopeActiveCampaigns($query, $idsArr) {
            return $query->join('client_route_info', 'client_route_campaigns.client_route_info_id', '=', 'client_route_info.id')
            ->join('client_route', 'client_route_info.client_route_id', '=', 'client_route.id')
            ->where('client_route_info.status', '=', 1)
            ->whereIn('client_route.id', $idsArr);
    }
}
