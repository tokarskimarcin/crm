<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CampaignsController extends Controller
{

    public function addNewCampaignsGet(){
        $numberOfLastYearsWeek = date('W',mktime(0, 0, 0, 12, 27, 2018));

        return view('crmRoute.AddNewCampaigns')
                ->with('lastWeek', $numberOfLastYearsWeek);
    }

    public function addNewCampaignsPost(){

    }
}
