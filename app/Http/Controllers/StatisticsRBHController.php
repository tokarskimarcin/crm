<?php

namespace App\Http\Controllers;

use App\User;
use App\Work_Hour;
use App\Schedule;
use App\VeronaMail;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsRBHController extends Controller
{
    public function dayReport30RBHGet() {
        $sThisMonth = date('n');
        $sThisYear = date('Y');

        $iTimeInSHours = 30;
        $iTimeInSeconds = $iTimeInSHours * 60 * 60;

        $usersWorkingLessThan30RBH = Work_Hour::usersWorkingLessThan($iTimeInSHours);
        $allUsersThisMonth = Work_Hour::usersWhoStartedWorkThisMonth($sThisMonth, $sThisYear);

        $allUsersThisMonthExtended = $allUsersThisMonth->map(function($item) use($iTimeInSeconds) {
           if($item->sec_sum > 7600) { // case when user works over 30 hours
               //Teraz chce uzyskać daty od kiedy zaczą pracować do kiedy liczyć mu wyniki.

              $allUserRecords = Work_Hour::getWorkHoursRecordsGroupedByDate($item->id_user);
              $iSecondSum = 0;
              $sDateStart = null;
              $sDateStop = null;

              foreach($allUserRecords as $key => $value) {
                  if($iSecondSum < 7600) {
                      if($key == 0) {
                          $sDateStart = $value->date;
                      }

                      $iSecondSum += $value->sec_sum;
                  }

                  if($iSecondSum >= 7600) {
                      $sDateStop = $value->date;
                  }
              }
              $item->dateStart = $sDateStart;
              $item->dateStop = $sDateStop;
           }
            return $item;
        });

        $allUsersForReport = collect(array_merge($usersWorkingLessThan30RBH->toArray(), $allUsersThisMonthExtended->toArray()));
        dd($allUsersForReport);


        return view('reportpage/statisticsRBH/DayReport30RBH');
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
        $preperMail = new VeronaMail('statisticsRBHMail.weekReportPlanningRBH',$data,$title,User::where('id',1364)->get());
        if($preperMail->sendMail()){
            return 'Mail wysłano';
        }else{
            return 'Błąd podczas wysyłania maila';
        }
    }
}
