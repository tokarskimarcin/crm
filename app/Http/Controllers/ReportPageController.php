<?php

namespace App\Http\Controllers;

class ReportPageController extends Controller
{
    public function PageHourReportTelemarketing()
    {
        return view('reportpage.HourReportTelemarketing');
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
