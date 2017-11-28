<?php

namespace App\Http\Controllers;

use App\HourReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function hourReportGet()
    {
        $reports = HourReport::where('report_date','like','2017-11-28')
            ->where('department_info_id',Auth::user()->department_info_id)->get();
        return view('statistics.hourReport')
            ->with('reports',$reports);
    }
    public function hourReportPost(Request $request)
    {

    }
}
