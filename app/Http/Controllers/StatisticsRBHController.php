<?php

namespace App\Http\Controllers;

use App\Pbx_report_extension;
use App\User;
use App\Work_Hour;
use App\Schedule;
use App\VeronaMail;
use DateTime;
use Illuminate\Http\Request;

class StatisticsRBHController extends Controller
{
    public function dayReport30RBHGet() {
        $sThisMonth = date('n');
        $sThisMonthToView  = $sThisMonth <10 ? '0'.$sThisMonth : $sThisMonth;
        $sThisYear = date('Y');
        $SreportDate = date('Y-m-d');
        $iTimeInSHours = 30;
        $iTimeInSeconds = $iTimeInSHours * 60 * 60;

        $CusersWorkingLessThan30RBH = Work_Hour::usersWorkingLessThan($iTimeInSHours);
        $CallUsersThisMonth = Work_Hour::usersWhoStartedWorkThisMonth($sThisMonth, $sThisYear);

        $CallUsersThisMonthExtended = Work_Hour::mergeCollection($CallUsersThisMonth,$iTimeInSeconds);

        $CallUsersForReport = collect(array_merge($CusersWorkingLessThan30RBH->toArray(), $CallUsersThisMonthExtended->toArray()));

        $CallUsersForReport = Pbx_report_extension::getPbxUserStatistics($CallUsersForReport);
        $aCllUsersForReport = $CallUsersForReport->groupBy('dep_id')->sortBy('dep_id');
        $sMonths = Work_Hour::getMonthsNames();
        return view('reportpage.statisticsRBH.DayReport30RBH')
            ->with('allUsersForReport',$aCllUsersForReport)
            ->with('SreportDate',$SreportDate)
            ->with('sMonths',$sMonths)
            ->with('Smonth_selected',$sThisMonthToView);
    }
    public function dayReport30RBHPost(Request $request) {
        dd($request->month_selected);
        $sThisMonth = date('n',strtotime($request->month_selected));
        $sThisMonthToView  = $sThisMonth <10 ? '0'.$sThisMonth : $sThisMonth;
        $sThisYear = date('Y',strtotime($request->reportDate));
        $sActualMonth = date('Y-m',strtotime($request->reportDate));
        $iTimeInSHours = 30;
        $iTimeInSeconds = $iTimeInSHours * 60 * 60;

        $CusersWorkingLessThan30RBH = Work_Hour::usersWorkingLessThan($iTimeInSHours,$sActualMonth);
        $CallUsersThisMonth = Work_Hour::usersWhoStartedWorkThisMonth($sThisMonth, $sThisYear,$sActualMonth);

        $CallUsersThisMonthExtended = Work_Hour::mergeCollection($CallUsersThisMonth,$iTimeInSeconds);

        $CallUsersForReport = collect(array_merge($CusersWorkingLessThan30RBH->toArray(), $CallUsersThisMonthExtended->toArray()));

        $CallUsersForReport = Pbx_report_extension::getPbxUserStatistics($CallUsersForReport);
        $aCllUsersForReport = $CallUsersForReport->groupBy('dep_id')->sortBy('dep_id');
        $sMonths = Work_Hour::getMonthsNames();
        return view('reportpage.statisticsRBH.DayReport30RBH')
            ->with('allUsersForReport',$aCllUsersForReport)
            ->with('SreportDate',$sActualMonth)
            ->with('sMonths',$sMonths)
            ->with('Smonth_selected',$sThisMonthToView);
    }
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
