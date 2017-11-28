<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function hourReportGet()
    {

        return view('statistics.hourReport');
    }
    public function hourReportPost(Request $request)
    {

    }
}
