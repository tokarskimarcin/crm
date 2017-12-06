<?php

namespace App\Http\Controllers;

use App\HourReport;

class ReportPageController extends Controller
{
    public function PageHourReportTelemarketing()
    {
        $date = date('Y-m-d');
        $hour = date('H') . ':00:00'; //tutaj zmienic przy wydawaniu na produkcję na  date('H') - 1

        $reports = HourReport::where('report_date', '=', $date)
            ->where('hour', $hour)
            ->get();

        return view('reportpage.HourReportTelemarketing')
            ->with('date',$date)
            ->with('hour',$hour)
            ->with('reports',$reports);
    }

    public function PageWeekReportTelemarketing()
    {
        return view('reportpage.WeekReportTelemarketing');
    }

    public function PageMonthReportTelemarketing()
    {
        return view('reportpage.MonthReportTelemarketing');
    }

    public function PageWeekReportJanky()
    {
        return view('reportpage.WeekReportJanky');
    }

    public function PageDayReportMissedRepo()
    {
        return view('reportpage.DayReportMissedRepo');
    }
}
