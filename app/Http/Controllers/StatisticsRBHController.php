<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\VeronaMail;
use DateTime;
use Illuminate\Http\Request;

class StatisticsRBHController extends Controller
{
    /**
     * GET Report Planing RBH
     * @return mixed
     */
    public function weekReportPlanningRBHGet(){
        $SactualYear = date('Y');
        $SactualWeekNumber = date('W');
        $CsheduleInfo = Schedule::getUsersRBHSchedule($SactualWeekNumber,$SactualYear);
        $CsheduleInfo = Schedule::groupUsersRBHbyDepartments($CsheduleInfo);
        $CsheduleInfo = Schedule::addMissingDepartmentToCollect($CsheduleInfo)->sortBy('department_info_id');
        $SfirstDate   =  date("Y-m-d", strtotime('monday this week'));
        $SlastDate    = date("Y-m-d", strtotime('sunday this week'));
        return View('reportpage.statisticsRBH.WeekReportPlanningRBH')
            ->with('CsheduleInfo',$CsheduleInfo)
            ->with('SfirstDate',$SfirstDate)
            ->with('SlastDate',$SlastDate);
    }

    /**
     * POST Report Planing RBH
     * @param Request $request
     * @return mixed
     */
    public function weekReportPlanningRBHPost(Request $request){
        $SactualYear = date('Y');
        $SactualWeekNumber = $request->date;
        $week_start = new DateTime();
        $week_start->setISODate($SactualYear,$SactualWeekNumber);
        $CsheduleInfo = Schedule::getUsersRBHSchedule($SactualWeekNumber,$SactualYear);
        $CsheduleInfo = Schedule::groupUsersRBHbyDepartments($CsheduleInfo);
        $CsheduleInfo = Schedule::addMissingDepartmentToCollect($CsheduleInfo)->sortBy('department_info_id');
        $SfirstDate   =  $week_start->modify('monday this week')->format('Y-m-d');
        $SlastDate    = $week_start->modify('sunday this week')->format('Y-m-d');
        return View('reportpage.statisticsRBH.WeekReportPlanningRBH')
            ->with('CsheduleInfo',$CsheduleInfo)
            ->with('SfirstDate',$SfirstDate)
            ->with('SlastDate',$SlastDate)
            ->with('SactualWeekNumber',$SactualWeekNumber);
    }

    /**
     * Send mail with statistics
     * @return string
     */
    public function WeekReportPlanningRBHMail(){
        $SactualYear = date('Y');
        $SactualWeekNumber = date('W');
        $CsheduleInfo = Schedule::getUsersRBHSchedule($SactualWeekNumber,$SactualYear);
        $CsheduleInfo = Schedule::groupUsersRBHbyDepartments($CsheduleInfo);
        $CsheduleInfo = Schedule::addMissingDepartmentToCollect($CsheduleInfo)->sortBy('department_info_id');
        $SfirstDate   =  date("Y-m-d", strtotime('monday this week'));
        $SlastDate    = date("Y-m-d", strtotime('sunday this week'));
        $title = 'Tygodniowy Raport (Planowanie) ' . $SfirstDate.' '.$SlastDate;
        $data = [
          'CsheduleInfo' => $CsheduleInfo, 'SfirstDate' => $SfirstDate, 'SlastDate' => $SlastDate,
        ];
        $preperMail = new VeronaMail('statisticsRBHMail.weekReportPlanningRBH',$data,$title);
        if($preperMail->sendMail()){
            return 'Mail wysłano';
        }else{
            return 'Błąd podczas wysyłania maila';
        }
    }
}
