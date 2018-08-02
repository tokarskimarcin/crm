<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;

class StatisticsRBHController extends Controller
{
    // Show Raport PlanningRBH
    public function weekReportPlanningRBHGet(){
        $SactualYear = date('Y');
        $SactualWeekNumber = date('W');
        $CsheduleInfo = Schedule::getUsersRBHSchedule($SactualWeekNumber,$SactualYear);
        $CsheduleInfo = Schedule::groupUsersRBHbyDepartments($CsheduleInfo);
        $CsheduleInfo = Schedule::addMissingDepartmentToCollect($CsheduleInfo)->sortBy('department_info_id');
        return View('reportpage.statisticsRBH.WeekReportPlanningRBH');
    }
}
