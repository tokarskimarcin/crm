<?php

namespace App\Http\Controllers;

use App\ActivityRecorder;
use App\Clients;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Panel to managment all client (VIEW)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clientPanel(){
       return view('crmRoute.clientPanel');
    }
    /**
     *  Return all city with info
     */
    public function getClient(Request $request){
        if($request->ajax()){
           $clients = Clients::all();
            $clientExtended = $clients->map(function($item) {
                if($item->priority == 1) {
                    $item->priorityName = "Niski";
                }
                else if($item->priority == 2) {
                    $item->priorityName = "Średni";
                }
                else if($item->priority == 3) {
                    $item->priorityName = "Wysoki";
                }
                return $item;
            });
           return datatables($clientExtended)->make(true);
        }
    }

    /**
     * Save new/edit client
     * @param Request $request
     */
    public function saveClient(Request $request){
        if($request->ajax()) {
            if ($request->clientID == 0) {
                // new city
                $client = new Clients();
                new ActivityRecorder(null, 194, 1);
            }
            else { // Edit city
                new ActivityRecorder(null, 194, 2);
                $client = Clients::find($request->clientID);
            }

            $client->name = $request->clientName;
            $client->priority = $request->clientPriority;
            $client->type = $request->clientType;

            if ($request->clientID == 0) {
                $client->status = 0;
            }

            $client->save();

            return 200;
        }
    }

    /**
     * turn off client change status to 1 disable or 0 avaible
     * @param Request $request
     */
    public function changeStatusClient(Request $request){
        if($request->ajax()){
            $client = Clients::find($request->clientId);
            if($client->status == 0)
                $client->status = 1;
            else
                $client->status = 0;
            $client->save();
            new ActivityRecorder(null, 194, 4);
        }
    }
    /**
     * find client by id
     */
    public function findClient(Request $request){
        if($request->ajax()){
            $client = Clients::find($request->clientId);
            return $client;
        }
    }


}
