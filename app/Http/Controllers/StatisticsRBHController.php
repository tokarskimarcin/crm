<?php

namespace App\Http\Controllers;

use App\User;
use App\Work_Hour;
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
}
