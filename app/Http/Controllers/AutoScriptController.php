<?php

namespace App\Http\Controllers;

use App\ClientRoute;
use Illuminate\Http\Request;

class AutoScriptController extends Controller
{
    //AutoChange Route Status from 1 to 2
    public function autoChangeRouteStatus(){
        $toDay = date('Y-m-d');
        $allClientRowToChangeStatus = ClientRoute::
        join('client_route_info','client_route_info.client_route_id','client_route.id')
        ->where('client_route.status','=','1')
        ->where('client_route_info.date','<=',$toDay)
        ->update(['client_route.status' => 2]);
    }
    //Auto invoice penalty
    public function checkPenatly(){
        $allSendInvoice = ClientRouteCampaigns::
            select(DB::raw('
                    id,
                    TIME_TO_SEC( TIMEDIFF ( now(), invoice_send_date) ) as waitSeconds
                '))
                ->where('invoice_status_id','=',3)
                ->get();
        foreach ($allSendInvoice as $item){
            if($item->waitSeconds > 172800){
                $waitSeconds = $item->waitSeconds - 172800;
                $penalty = intval($waitSeconds/86400) * 50;
                $item->penalty = $penalty;
                $item->save();
            }
        }
    }
}
