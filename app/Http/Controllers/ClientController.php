<?php

namespace App\Http\Controllers;

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
           return datatables($clients)->make(true);
        }
    }

    /**
     * Save new/edit city
     * @param Request $request
     */
    public function saveNewCity(Request $request){
        if($request->ajax()){
            if($request->cityID == 0) // new city
                $newCity = new Cities();
            else    // Edit city
                $newCity = Cities::find($request->cityID);
            $newCity->voivodeship_id = $request->voiovedshipID;
            $newCity->name = $request->cityName;
            $newCity->max_hour = $request->eventCount;
            $newCity->grace_period = $request->gracePeriod;
            $newCity->status = 0;
            $newCity->save();
            return 200;
        }
    }

    /**
     * turn off city change status to 1 disable or 0 avaible
     * @param Request $request
     */
    public function changeStatusCity(Request $request){
        if($request->ajax()){
            $newCity = Cities::find($request->cityId);
            if($newCity->status == 0)
                $newCity->status = 1;
            else
                $newCity->status = 0;
            $newCity->save();
        }
    }
    /**
     * find city by id
     */
    public function findCity(Request $request){
        if($request->ajax()){
            $city = Cities::find($request->cityId);
            return $city;
        }
    }


}
