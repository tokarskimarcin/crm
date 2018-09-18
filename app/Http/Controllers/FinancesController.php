<?php

namespace App\Http\Controllers;

use App\AcceptedPayment;
use App\Agencies;
use App\ClientRouteInfo;
use App\Department_info;
use App\Department_types;
use App\Departments;
use App\DoublingQueryLogs;
use App\JankyPenatlyProc;
use App\PaymentAgencyStory;
use App\PenaltyBonus;
use App\RecruitmentStory;
use App\ReportCampaign;
use App\Schedule;
use App\SuccessorHistory;
use App\SummaryPayment;
use App\User;
use App\UserEmploymentStatus;
use App\UserTypes;
use App\Utilities\Dates\MonthFourWeeksDivision;
use App\Utilities\DataProcessing\ConfirmationStatistics;
use App\Utilities\Dates\MonthIntoCompanyWeeksDivision;
use App\Utilities\Dates\MonthPerWeekDivision;
use App\Utilities\Salary\ProvisionLevels;
use App\Work_Hour;
use DateTime;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\ActivityRecorder;

class FinancesController extends Controller
{
    public function viewPaymentGet()
    {
        $logged_user = Auth::user();
        return view('finances.viewPayment')->with('user', $logged_user);
    }
    public function viewPaymentCadreGet()
    {
        return view('finances.viewPaymentCadre');
    }

    private function monthPerRealWeekDivision($month, $year) {
        $dataStart = date('Y-m-' . '01', strtotime($year . '-' . $month)); //first day of given month
        $loopDate = $dataStart;
        $days_in_month = date('t', strtotime($year . '-' . $month));
        $firstWeekNumber = date('W', strtotime($year . '-' . $month . '-01')); //first week number of given month
        $unoWeekNumber = date('W', strtotime($year . '-' . $month . '-01')); //first week number of given month
        $monthNumber = date('m', strtotime($year . '-' . $month . '-01'));

        $loopDateMonthNumber = date('m', strtotime($loopDate));

        $weeks = [$firstWeekNumber]; //array of weeks number in this month

        for ($i = 1; $i <= $days_in_month; $i++) {
            $loop_day = ($i < 10) ? '0' . $i : $i;
            $loop_date = $year . '-' . $month . '-' . $loop_day;
            $loopDateWeekNumber = date('W', strtotime($loop_date));

            if($loopDateWeekNumber != $unoWeekNumber) {
                array_push($weeks, $loopDateWeekNumber);
                $unoWeekNumber = $loopDateWeekNumber;
            }
        }

        $weeksDivided = array();

        for($j = 0; $j < count($weeks); $j++) {
            $flag = 1;

            if($j == 0) {
            $from = date("Y-m-d", strtotime("{$year}-W{$weeks[$j]}-1")); //Returns the date of monday in week
            $to = date("Y-m-d", strtotime("{$year}-W{$weeks[$j]}-7"));   //Returns the date of sunday in week

                for($k = 1; $k <= 8; $k++) {
                    if($k == 1) {
                        $currentMonthNumber = date('m', strtotime($from));
                        if($monthNumber == $currentMonthNumber && $flag != 0) {
                            $firstDayNr = date("N", strtotime("{$year}-W{$weeks[$j]}-1"));
                            $lastDayNr = date("N", strtotime("{$year}-W{$weeks[$j]}-7"));
                            array_push($weeksDivided, ['weekNumber' => $weeks[$j], 'firstDay' => $from, 'lastDay' => $to, 'firstDayNr' => $firstDayNr, 'lastDayNr' => $lastDayNr]);
                            $flag = 0;
                        }
                    }
                    else {
                        $days = $k - 1;
                        $currentMonthNumber = date('m', strtotime($from . '+ ' . $days . ' days'));
                        if($monthNumber == $currentMonthNumber  && $flag != 0) {
                            $firstDayNr = date("N", strtotime($from . '+ ' . $days . ' days'));
                            $lastDayNr = date("N", strtotime("{$year}-W{$weeks[$j]}-7"));
                            array_push($weeksDivided, ['weekNumber' => $weeks[$j], 'firstDay' => date('Y-m-d', strtotime($from . '+ ' . $days . ' days')), 'lastDay' => $to, 'firstDayNr' => $firstDayNr, 'lastDayNr' => $lastDayNr]);
                            $flag = 0;
                        }
                    }
                }
            }
            else if($j == count($weeks) - 1) {
                $from = date("Y-m-d", strtotime("{$year}-W{$weeks[$j]}-1")); //Returns the date of monday in week
                $to = date("Y-m-d", strtotime("{$year}-W{$weeks[$j]}-7"));   //Returns the date of sunday in week
                if(date('m', strtotime($to)) != $monthNumber) { //last week day is not in given month
                    for($k = 1; $k <= 7; $k++) {
                        $days = $k;
                        $currentMonthNumber = date('m', strtotime($from . '+ ' . $days . ' days'));
                        if($monthNumber != $currentMonthNumber  && $flag != 0) {
                            $corrDays = $days - 1;
                            $firstDayNr = date("N", strtotime("{$year}-W{$weeks[$j]}-1"));
                            $lastDayNr = date("N", strtotime($from . '+ ' . $corrDays . ' days'));
                            array_push($weeksDivided, ['weekNumber' => $weeks[$j], 'firstDay' => $from, 'lastDay' => date('Y-m-d', strtotime($from . '+ ' . $corrDays . ' days')), 'firstDayNr' => $firstDayNr, 'lastDayNr' => $lastDayNr]);
                            $flag = 0;
                        }
                    }
                }
                else { //last week day contain in given month
                    $from = date("Y-m-d", strtotime("{$year}-W{$weeks[$j]}-1")); //Returns the date of monday in week
                    $to = date("Y-m-d", strtotime("{$year}-W{$weeks[$j]}-7"));   //Returns the date of sunday in week
                    $firstDayNr = date("N", strtotime("{$year}-W{$weeks[$j]}-1"));
                    $lastDayNr = date("N", strtotime("{$year}-W{$weeks[$j]}-7"));
                    array_push($weeksDivided, ['weekNumber' => $weeks[$j], 'firstDay' => $from, 'lastDay' => $to, 'firstDayNr' => $firstDayNr, 'lastDayNr' => $lastDayNr]);
                }
            }
            else {
                $from = date("Y-m-d", strtotime("{$year}-W{$weeks[$j]}-1")); //Returns the date of monday in week
                $to = date("Y-m-d", strtotime("{$year}-W{$weeks[$j]}-7"));   //Returns the date of sunday in week
                $firstDayNr = date("N", strtotime("{$year}-W{$weeks[$j]}-1"));
                $lastDayNr = date("N", strtotime("{$year}-W{$weeks[$j]}-7"));
                array_push($weeksDivided, ['weekNumber' => $weeks[$j], 'firstDay' => $from, 'lastDay' => $to, 'firstDayNr' => $firstDayNr, 'lastDayNr' => $lastDayNr]);
            }
        }
//        dd($weeksDivided);
        return $weeksDivided;
    }

    /**
     * @param $sum == 0 indices that we want raw data, $sum == 1 indices that we want agreggate data
     * @return data about camapigns
     */
    public function getCampaignData($date_start, $date_stop, $sum) {
        if($sum == 0) { //raw data
            $campaign_data = DB::table('report_campaign')->select(DB::raw('
            SUM(all_campaigns) as all_campaigns,
            SUM(active_campaigns) as active_campaigns,
            SUBSTRING_INDEX(name,"_",1) as split_name
            '))
                ->whereBetween('date', [$date_start, $date_stop])
                ->where('all_campaigns', '>', 0)
                ->groupBy('split_name')
                ->get();
        }
        else { //agreggate data
            $campaign_data = ReportCampaign::select(DB::raw('
            SUM(all_campaigns) AS sum_campaign,
            SUM(active_campaigns) AS sum_active,
            SUM(received_campaigns) AS sum_received,
            SUM(unreceived_campaigns) AS sum_unreceived
            '))
                ->whereBetween('date', [$date_start, $date_stop])
                ->get();
        }
        return $campaign_data->sortByDesc('all_campaigns');
    }

    /**
     * Pobranie danych dla zbiorczego raportu (sortowanie po dyrektorach)
     */
    private function getMultiDepartmentData($date_start, $date_stop, $month, $year, $deps, $days_in_month) {
        /**
         * Pobranie ostatnich ID z dnia
         */
        $reportIds = DB::table('hour_report')
            ->select(DB::raw('
                MAX(id) as id
            '))
            ->whereBetween('hour_report.report_date', [$date_start, $date_stop])
            ->groupBy('report_date')
            ->groupBy('department_info_id')
            ->whereIn('department_info_id', $deps)
            ->get();
        /**
         * Pobranie danych do raportu
         */
        $hourReports = DB::table('hour_report')
            ->select(DB::raw('
                hour_report.*
            '))
            ->whereBetween('hour_report.report_date', [$date_start, $date_stop])
            ->whereIn('hour_report.id', $reportIds->pluck('id')->toArray())
            ->get();
        /**
         * Pobranie danych z przepracowanych godzin
         */
        $acceptHours = DB::table('work_hours')
            ->select(DB::raw('
                SUM(TIME_TO_SEC(accept_stop) - TIME_TO_SEC(accept_start)) as time_sum,
                date
            '))
            ->join('users', 'users.id', 'work_hours.id_user')
            ->whereBetween('date', [$date_start, $date_stop])
            ->whereIn('users.department_info_id', $deps)
            ->whereIn('users.user_type_id', [1,2])
            ->groupBy('date')
            ->get();
        /**
         * Pobranie danych z przepracowanych godzin
         */
        $acceptHours_2 = DB::table('work_hours')
            ->select(DB::raw('
                SUM(TIME_TO_SEC(accept_stop) - TIME_TO_SEC(accept_start)) as time_sum,
                date,users.department_info_id
                
            '))
            ->join('users', 'users.id', 'work_hours.id_user')
            ->whereBetween('date', [$date_start, $date_stop])
            ->whereIn('users.department_info_id', $deps)
            ->whereIn('users.user_type_id', [1,2])
            ->groupBy('date','users.department_info_id')
            ->get();
        /**
         * Pobranie danych dotyczących janków
         */
        $jankyIds = DB::table('pbx_dkj_team')
            ->select(DB::raw('
                MAX(id) as id
            '))
            ->whereBetween('report_date', [$date_start, $date_stop])
            ->groupBy('report_date')
            ->groupBy('department_info_id')
            ->whereIn('department_info_id', $deps)
            ->get();
        $yanky = DB::table('pbx_dkj_team')
            ->select(DB::raw('
                *
            '))
            ->whereBetween('report_date', [$date_start, $date_stop])
            ->whereIn('id', $jankyIds->pluck('id')->toArray())
            ->get();
        $newYanky = [];
        for ($i = 1; $i <= $days_in_month; $i++) {
            $day = ($i < 10) ? '0' . $i : $i ;
            $loop_date = $year . '-' . $month . '-' . $day;
            if ($yanky->where('report_date', '=', $loop_date)->count() > 0) {
                $tempYanek = new \stdClass();
                $tempYanek->report_date = $loop_date;
                $tempYanek->consultant_without_check = 0;
                $tempYanek->online_consultant = 0;
                $tempYanek->success = 0;
                $tempYanek->count_all_check = 0;
                $tempYanek->count_good_check = 0;
                $tempYanek->count_bad_check = 0;
                $tempYanek->all_jaky_disagreement = 0;
                $tempYanek->good_jaky_disagreement = 0;
                foreach($yanky->where('report_date', '=', $loop_date) as $item) {
                    $tempYanek->consultant_without_check += $item->consultant_without_check;
                    $tempYanek->online_consultant += $item->online_consultant;
                    $tempYanek->success += $item->success;
                    $tempYanek->count_all_check += $item->count_all_check;
                    $tempYanek->count_good_check += $item->count_good_check;
                    $tempYanek->count_bad_check += $item->count_bad_check;
                    $tempYanek->all_jaky_disagreement += $item->all_jaky_disagreement;
                    $tempYanek->good_jaky_disagreement += $item->good_jaky_disagreement;
                }
                $newYanky[] = $tempYanek;
            }
        }
        $newYanky = collect($newYanky);
        /**
         * Pobranie danych z grafiku
         */
        //Pobranie tygodni których dotyczy dany miesiąc
        $schedule_weeks = [];
        for ($i = 1; $i <= intval($days_in_month); $i = $i + 7) {
            $cur_day = ($i < 10) ? '0' . $i : $i;
            $schedule_weeks[] = intval(date('W',strtotime($year . '-'. $month . '-' . $cur_day)));
        }
        $schedule_data_raw = [];
        foreach($schedule_weeks as $week) {
            $schedule_data_raw[] = DB::table('schedule')
                ->select(DB::raw('
                    SUM(TIME_TO_SEC(monday_stop) - TIME_TO_SEC(monday_start)) / 3600 as day1,
                    SUM(TIME_TO_SEC(tuesday_stop) - TIME_TO_SEC(tuesday_start)) / 3600 as day2,
                    SUM(TIME_TO_SEC(wednesday_stop) - TIME_TO_SEC(wednesday_start)) / 3600 as day3,
                    SUM(TIME_TO_SEC(thursday_stop) - TIME_TO_SEC(thursday_start)) / 3600 as day4,
                    SUM(TIME_TO_SEC(friday_stop) - TIME_TO_SEC(friday_start)) / 3600 as day5,
                    SUM(TIME_TO_SEC(saturday_stop) - TIME_TO_SEC(saturday_start)) / 3600 as day6,
                    SUM(TIME_TO_SEC(sunday_stop) - TIME_TO_SEC(sunday_start)) / 3600 as day7,
                    week_num
                '))
                ->join('users', 'users.id', 'schedule.id_user')
                ->whereIn('users.department_info_id', $deps)
                ->whereIn('users.user_type_id', [1,2])
                ->where('week_num', $week)
                ->get();
        }
        $schedule_data_raw = collect($schedule_data_raw);
        $schedule_data = $schedule_data_raw->map(function($item) {
            return $item->first();
        });
        $reps = [];
        for ($i = 1; $i <= $days_in_month; $i++) {
            $day = ($i < 10) ? '0' . $i : $i ;
            $loop_date = $year . '-' . $month . '-' . $day;
            if ($hourReports->where('report_date', '=', $loop_date)->count() > 0) {
                $reports = $hourReports->where('report_date', '=', $loop_date);
                $tempReport = new \stdClass();
                $tempReport->report_date = $loop_date;
                $tempReport->average = 0;
                $tempReport->success = 0;
                $tempReport->janky_count = 0;
                $tempReport->wear_base = 0;
                $tempReport->call_time = 0;
                $tempReport->hour_time_use = 0;
                $tempReport->total_time = 0;
                foreach ($reports as $item) {
                    $tempReport->success += $item->success;
                    $rbh_departments = $acceptHours_2->where('date', '=', $item->report_date);
                    $total_hour_time_use = 0;
                    foreach ($rbh_departments as $rbh_department)
                    {
                        $sigle_hour_report = $reports->where('department_info_id','=',$rbh_department->department_info_id);
                        if(!$sigle_hour_report->isEmpty()){
                            $total_hour_time_use += round(($sigle_hour_report->first()->call_time * ($rbh_department->time_sum/3600)) / 100, 2);
                        }
                    }
                    $tempReport->hour_time_use += $total_hour_time_use;//floatval($item->hour_time_use);
                    $tempReport->total_time += floatval($item->hour_time_use);//($item->call_time > 0) ? ((100 * $item->hour_time_use) / $item->call_time) : 0 ;
                }
                $tempReport->average = ($tempReport->hour_time_use > 0) ? round($tempReport->success / $tempReport->hour_time_use, 2) : 0 ;
                $tempReport->hour_time_use = $total_hour_time_use;
                $reps[] = $tempReport;
            }
        }
        $hourReports = collect($reps);
        /**
         * Przypisanie danych do jednego obiektu
         */
        $newHourReports = $hourReports->map(function($item) use ($newYanky, $acceptHours) {
            //Pobranie danych z jankami
            $toAdd = $newYanky->where('report_date', '=', $item->report_date)->first();
            $item->count_all_check = ($toAdd != null) ? $toAdd->count_all_check : 0;
            $item->count_bad_check = ($toAdd != null) ? $toAdd->count_bad_check : 0;
            //pobranie danych z przepracowanymi godzinami
            $toAddHours = $acceptHours->where('date', '=', $item->report_date)->first();
            $item->time_sum_real_RBH = ($toAddHours != null) ? $toAddHours->time_sum : 0;
            return $item;
        });
        /**
         *Tutaj raport w widoku bierze pierwszy wpis z daną datą, trzeba  posumować dane ze wszystkich oddziałów pogrupowane po datach
         */
        /**
         * Pobranie danych departamentu
         */
        $dep_info = Department_info::whereIn('id', $deps)->get();
        /**
         * Tabela z miesiącami
         */
        $months = [
            '01' => 'Styczeń',
            '02' => 'Luty',
            '03' => 'Marzec',
            '04' => 'Kwiecień',
            '05' => 'Maj',
            '06' => 'Czerwiec',
            '07' => 'Lipiec',
            '08' => 'Sierpień',
            '09' => 'Wrzesień',
            '10' => 'Październik',
            '11' => 'Listopad',
            '12' => 'Grudzień'
        ];
        $data = [
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'month' => $month,
            'year' => $year,
            'hour_reports' => $newHourReports,
            'dep_info' => $dep_info,
            'schedule_data' => $schedule_data,
            'months' => $months
        ];
        return $data;
    }

    private function getFreeDays($dividedMonth) {

//        dd($dividedMonth);
        $fieldArr = [
            '1' => 'mondayPaid',
            '2' => 'tuesdayPaid',
            '3' => 'wednesdayPaid',
            '4' => 'thursdayPaid',
            '5' => 'fridayPaid',
            '6' => 'saturdayPaid',
            '7' => 'sundayPaid'
                ];
        $safeWeeksArr = []; //safe weeks arr
        $unsafeWeeksArr = [];
        for($i = 0; $i < count($dividedMonth); $i++) {
            if($i != 0 && $i != count($dividedMonth) - 1) {
                array_push($safeWeeksArr, $dividedMonth[$i]['weekNumber']);
            }
            else {
                array_push($unsafeWeeksArr, $dividedMonth[$i]['weekNumber']);
            }
        }

        $freeDaysSafe = Schedule::select(DB::raw('
            id_user,
            SUM(CASE WHEN mondayPaid = 0 THEN 1 ELSE 0 END) + SUM(CASE WHEN tuesdayPaid = 0 THEN 1 ELSE 0 END) + SUM(CASE WHEN wednesdayPaid = 0 THEN 1 ELSE 0 END) + SUM(CASE WHEN thursdayPaid = 0 THEN 1 ELSE 0 END) + SUM(CASE WHEN fridayPaid = 0 THEN 1 ELSE 0 END) + SUM(CASE WHEN saturdayPaid = 0 THEN 1 ELSE 0 END) + SUM(CASE WHEN sundayPaid = 0 THEN 1 ELSE 0 END)
            as notPaidDays
        '))
            ->groupBy('id_user')
            ->whereIn('week_num', $safeWeeksArr)
            ->get();

        $unsafeWeekString1 = '';
        $unsafeWeekString2 = '';
//        dd($unsafeWeeksArr);
        for($i = $dividedMonth[0]['firstDayNr']; $i < 8; $i++) {
            if($i != 7) {
                $unsafeWeekString1 .= 'SUM(CASE WHEN ' . $fieldArr[$i] . ' = 0 THEN 1 ELSE 0 END) + ';
            }
            else {
                $unsafeWeekString1 .= 'SUM(CASE WHEN ' . $fieldArr[$i] . ' = 0 THEN 1 ELSE 0 END)';
            }
        }

        $max = $dividedMonth[count($dividedMonth) - 1]['lastDayNr'];
        for($i = 1; $i <= $max; $i++) {
            if($i != $max) {
                $unsafeWeekString2 .= 'SUM(CASE WHEN ' . $fieldArr[$i] . ' = 0 THEN 1 ELSE 0 END) + ';
            }
            else {
                $unsafeWeekString2 .= 'SUM(CASE WHEN ' . $fieldArr[$i] . ' = 0 THEN 1 ELSE 0 END)';
            }
        }

        $freeDaysUnsafe1 = Schedule::select(DB::raw('
            id_user,
            ' . $unsafeWeekString1 . ' as notPaidDays
        '))
            ->groupBy('id_user')
            ->where('week_num', '=', $unsafeWeeksArr[0])
            ->get();

        $freeDaysUnsafe2 = Schedule::select(DB::raw('
            id_user,
            ' . $unsafeWeekString2 . ' as notPaidDays
        '))
            ->groupBy('id_user')
            ->where('week_num', '=', $unsafeWeeksArr[1])
            ->get();


        $finalArr = [];
        $idArr = array_merge($freeDaysSafe->pluck('id_user')->toArray(), $freeDaysUnsafe1->pluck('id_user')->toArray());
        $fullIdArr = array_merge($idArr, $freeDaysUnsafe2->pluck('id_user')->toArray());
        $fullIdUniqueArray = array_unique($fullIdArr); //array of all user ids

        foreach($fullIdUniqueArray as $userId) {

            $element1 = $freeDaysSafe->where('id_user', '=', $userId)->count() != 0 ? $freeDaysSafe->where('id_user', '=', $userId)->first()->notPaidDays : 0;
            $element2 = $freeDaysUnsafe1->where('id_user', '=', $userId)->count() != 0 ? $freeDaysUnsafe1->where('id_user', '=', $userId)->first()->notPaidDays : 0;
            $element3 = $freeDaysUnsafe2->where('id_user', '=', $userId)->count() != 0 ? $freeDaysUnsafe2->where('id_user', '=', $userId)->first()->notPaidDays : 0;
            array_push($finalArr, ['id_user' => $userId, 'freeDays' => $element1 + $element2 + $element3]);
        }

        return $finalArr;

    }

    private function provisionSystemForTrainers(&$user, $dividedMonth) {
        $user->provision = 0;
        if($user->department_type_id == 1){             //trener potwierdzeń
            $clientRouteInfo = ClientRouteInfo::select(
                DB::raw('concat(users.first_name," ",users.last_name) as confirmingUserName'),
                DB::raw('concat(trainer.first_name," ",trainer.last_name) as confirmingUserTrainerName'),
                'confirmingUser',
                'confirmDate',
                'frequency',
                'pairs',
                'actual_success',
                'users.department_info_id',
                'users.coach_id'
            )
                ->join('users','confirmingUser', '=', 'users.id')
                ->join('department_info as di', 'users.department_info_id','=','di.id')
                ->join('users as trainer','users.coach_id','=','trainer.id')
                ->where('confirmDate', '>=', $dividedMonth[0]->firstDay)
                ->where('confirmDate', '<=', $dividedMonth[count($dividedMonth)-1]->lastDay)
                ->where('users.department_info_id', $user->department_info_id)
                ->where('di.id_dep_type',1)
                ->whereNotNull('confirmingUser')
                ->whereNotNull('users.coach_id')
                ->where('users.coach_id', $user->id)->get(); //client route info poszczególnych konsultantów wybranego trenera w miesiacu
            $confirmationStatistics = ConfirmationStatistics::getConsultantsConfirmationStatisticsForMonth($clientRouteInfo, $dividedMonth, 'coach_id');
            foreach ($confirmationStatistics['sums'] as $confirmationStatisticsWeek){
                $user->provision = $user->provision + ProvisionLevels::get('trainer', $confirmationStatisticsWeek->successfulPct,2);
                $user->provision = $user->provision + ProvisionLevels::get('trainer', $confirmationStatisticsWeek->unsuccessfulBadlyPct,1);
            }
            dd($user);
        }else if($user->department_type_id == 2){       //trener telemarketing

        }
    }

    private function provisionSystemForInstructors(&$user, $dividedMonth){
        $user->provision = 0;
        if($user->department_type_id == 1){             //szkoleniowiec potwierdzeń
            $clientRouteInfo = ClientRouteInfo::select(
                DB::raw('concat(users.first_name," ",users.last_name) as confirmingUserName'),
                DB::raw('concat(trainer.first_name," ",trainer.last_name) as confirmingUserTrainerName'),
                'confirmingUser',
                'confirmDate',
                'frequency',
                'pairs',
                'actual_success',
                'users.department_info_id',
                'users.coach_id'
            )
                ->join('users','confirmingUser', '=', 'users.id')
                ->join('department_info as di', 'users.department_info_id','=','di.id')
                ->join('users as trainer','users.coach_id','=','trainer.id')
                ->where('confirmDate', '>=', $dividedMonth[0]->firstDay)
                ->where('confirmDate', '<=', $dividedMonth[count($dividedMonth)-1]->lastDay)
                ->where('users.department_info_id', $user->department_info_id)
                ->where('di.id_dep_type',1)
                ->whereNotNull('confirmingUser')
                ->whereNotNull('users.coach_id')->get(); //client route info poszczególnych konsultantów w calym oddziale w miesiacu
            $confirmationStatistics = ConfirmationStatistics::getConsultantsConfirmationStatisticsForMonth($clientRouteInfo, $dividedMonth);
            foreach ($confirmationStatistics['sums'] as $confirmationStatisticsWeek){
                $user->provision = $user->provision + ProvisionLevels::get('instructor', $confirmationStatisticsWeek->successfulPct,2);
                $user->provision = $user->provision + ProvisionLevels::get('instructor', $confirmationStatisticsWeek->unsuccessfulBadlyPct,1);
            }
            dd($user);
        }else if($user->department_type_id == 2){       //szkoleniowiec telemarketing

        }
    }

    private function getDepartmentStatistics($weekDateArr, $month, $year, $departments) {
        $firstDayOfMonth = new DateTime(date('Y-m-d', strtotime($year . '-' . $month . '-01')));
        $lastDayOfMonth = new DateTime(date('Y-m-d', strtotime($year .'-'. $month . '-' . date('t', strtotime($year . '-' . $month . '-01')))));

        $days_in_month = date('t', strtotime(date('Y').'-'. $month));

        $today = date('Y-m-d'); //today
        $todayDateTime = new DateTime($today);
        $data = $this->getMultiDepartmentData($firstDayOfMonth->format('Y-m-d'), $lastDayOfMonth->format('Y-m-d'), $month, $year,$departments, $days_in_month);
        $rbhTargetArr = [];
        foreach($weekDateArr as $weekInfo) {
            $total_week_proc_janky = 0;
            $total_week_goal = 0;
            $total_week_success = 0;
            $real_week_RBH = 0;
            $week_target_RBH = 0;
            $total_week_checked = 0;
            $total_week_bad = 0;
            $hour_reports = $data['hour_reports'];
            $dep_info = $data['dep_info'];
            $firstDayOfMonthDateTime = new DateTime($weekInfo->firstDay);
            $lastDayOfWeekDateTime = new DateTime($weekInfo->lastDay);
            $dateDiff = $lastDayOfWeekDateTime->diff($firstDayOfMonthDateTime)->days;
            for($i = 0; $i <= intval($dateDiff); $i++) {
                $depAim = 0;
                $date = date('Y-m-d', strtotime($weekInfo->firstDay . ' + ' . $i .' days'));
                $report = $hour_reports->where('report_date', '=', $date)->where('success', '>', 0)->first();
                $add_default_zero = ($report != null) ? false : true ;
                if ($add_default_zero == false) {
                    $day_number = date('N', strtotime($report->report_date));
                    $real_RBH = round(($report->time_sum_real_RBH / 3600) ,2);
                    if(date('w', strtotime($date)) == 6) { //saturday
                        $depAim = $dep_info[0]['dep_aim_week'];
                    }
                    else { //other than saturday
                        $depAim = $dep_info[0]['dep_aim'];
                    }
                    $goal = ($day_number < 6) ? $dep_info[0]['dep_aim'] : $dep_info[0]['dep_aim_week'];
                    $total_week_goal += $goal;
                    $total_week_success += $report->success;

                    $commisionAvg = $dep_info[0]['commission_avg'];
                    $targetRBH =$commisionAvg != 0 ? round($depAim / $commisionAvg, 2) : 0; //cel rbh
                    $week_target_RBH += $targetRBH;
                    $real_week_RBH += $real_RBH;
                    $total_week_checked += $report->count_all_check;
                    $total_week_bad += $report->count_bad_check;
                }
            }
            $total_week_proc_janky = ($total_week_checked != null && $total_week_checked > 0) ? round(($total_week_bad / $total_week_checked) * 100, 2) : 0 ;
            $total_week_goal_proc = ($total_week_goal != null && $total_week_goal > 0) ? round(($total_week_success / $total_week_goal) * 100, 2) : 0 ;
            $tatal_week_avg = ($total_week_success <=0 || $real_week_RBH <=0)  ? 0: round($total_week_success/$real_week_RBH,2);

            $obj = new \stdClass();
            $obj->total_week_success = $total_week_success;
            $obj->total_week_avg = $tatal_week_avg;
            $obj->week_target_rbh = round($week_target_RBH);
            $obj->real_week_rbh = $real_week_RBH;
            $obj->janky_proc = $total_week_proc_janky;
            $obj->target_rbh_percentage = round($week_target_RBH) != 0 ? 100 * $real_week_RBH / round($week_target_RBH) : 0;
            $obj->total_week_goal_proc = $total_week_goal_proc;
            array_push($rbhTargetArr, $obj);
        }

        return $rbhTargetArr;
    }

    private function provisionSystemForHR(&$user, $month, $year) {

        $weekDateArr = MonthFourWeeksDivision::get($year,$month); // array of objects with week info
//        dd($weekDateArr);

        if($user->dep_type_id == 1) { //hr from confirming
            //*****Generating info how much account was added per week
            $infoArr = [];
            $totalProv = 0;
            foreach($weekDateArr as $weekInfo) {
                $data = RecruitmentStory::getReportNewAccountData($weekInfo->firstDay,$weekInfo->lastDay); //info about new accounts in teambox

                foreach($data as $item) {
                    if($item->id == $user->id) {
                        $provision = ProvisionLevels::get('HR',$item->add_user, 1);
                        $obj = new \stdClass();
                        $obj->provision = $provision;
                        array_push($infoArr, $obj);
                        $totalProv += $provision;
                    }
                }

            }
            $user->provisions = $infoArr;
            $user->totalProvision = $totalProv;
            //*****End of generating info how much account was added per week
        }
        else if($user->dep_type_id == 2) { //hr from telemarketing
            $provisions = [];
            $totalProvision = 0;
            $rbhTargetArr = $this->getDepartmentStatistics($weekDateArr, $month, $year, [$user->department_info_id]);
            foreach ($rbhTargetArr as $target) {
                $provTarget = ProvisionLevels::get('HR', $target->janky_proc, $target->total_week_goal_proc, 2, 'ammount');
                $prov = ProvisionLevels::get('HR', $target->janky_proc, $target->target_rbh_percentage,2, 1);
                $sumProv = $prov + $provTarget;
                array_push($provisions, $sumProv);
                $totalProvision += $sumProv;
            }
            $user->totalProvision = $totalProvision;
        }

        //*****Generating info with audits score
//        $departmentAudits = Audit::where('date_audit', 'like', $year . '-' . $month . '%')
//            ->where('user_type', '=', 3)
//            ->where('department_info_id', '=', $user->department_info_id)
//            ->first();
//
//        if(!is_null($departmentAudits)) {
//            $user->auditScore = $departmentAudits->score;
//        }
//        else {
//            $user->auditScore = -1; // if no data, -1
//        }
//
//        $personalAudits = Audit::where('date_audit', 'like', $year . '-' . $month . '%')
//            ->where('user_type', '=', 2)
//            ->where('trainer_id', '=', $user->id)
//            ->first();
//
//        if(!is_null($personalAudits)) {
//            $user->auditScorePersonal = $personalAudits->score;
//        }
//        else {
//            $user->auditScorePersonal = -1; // if no data, -1
//        }
        //*****End of generating info with audits score
    }

    private function provisionSystemForCoordinators(&$user, $month, $year) {
        $weekDateArr = MonthFourWeeksDivision::get($year, $month); //month divided on 4 weeks
        $firstDayOfMonth = new DateTime(date('Y-m-d', strtotime($year . '-' . $month . '-01')));
        $lastDayOfMonth = new DateTime(date('Y-m-d', strtotime($year .'-'. $month . '-' . date('t', strtotime($year . '-' . $month . '-01')))));

        $days_in_month = date('t', strtotime(date('Y').'-'. $month));

        $departments = Department_info::where('id_dep_type', '=', 2)->get();

        $infoArr = [];
        $today = date('Y-m-d'); //today
        $todayDateTime = new DateTime($today);
        $provisions = [];
        $totalProvision = 0;
        $goals = [];
        $data = $this->getMultiDepartmentData($firstDayOfMonth->format('Y-m-d'), $lastDayOfMonth->format('Y-m-d'), $month, $year, $departments->pluck('id')->toArray(), $days_in_month);
        foreach($weekDateArr as $weekInfo) {
            $firstDayOfWork = null;
            if($user->promotion_date != null) { //user was promoted for coordinator
                $firstDayOfWork = new DateTime($user->promotion_date);
            }
            else { //user started working as coordinator, and wasn't in company priously.
                $firstDayOfWork = new DateTime($user->start_work);
            }
            $daysInPosition = $todayDateTime->diff($firstDayOfWork)->days; //how much days coordinator work on his position
            $campaignData = $this->getCampaignData($weekInfo->firstDay, $weekInfo->lastDay, 1);
            $databasePercentageUsage = $campaignData[0]->sum_campaign != 0 ? 100 * $campaignData[0]->sum_active / $campaignData[0]->sum_campaign : 0; //percent of database usage

            $hour_reports = $data['hour_reports'];
            $dep_info = $data['dep_info'];
            $total_success = 0;
            $total_week_goal = 0;
            $total_week_success = 0;
            $firstDayOfMonthDateTime = new DateTime($weekInfo->firstDay);
            $lastDayOfWeekDateTime = new DateTime($weekInfo->lastDay);
            $dateDiff = $lastDayOfWeekDateTime->diff($firstDayOfMonthDateTime)->days;

            for($i = 0; $i <= intval($dateDiff); $i++) {
                $date = date('Y-m-d', strtotime($weekInfo->firstDay . ' + ' . $i .' days'));
                $report = $hour_reports->where('report_date', '=', $date)->where('success', '>', 0)->first();
                $add_default_zero = ($report != null) ? false : true ;
                if ($add_default_zero == false) {
                    $goal = 0;
                    $total_week_success += $report->success;
                    $day_number = date('N', strtotime($report->report_date));
                    foreach ($dep_info as $dep) {
                        $goal += ($day_number < 6) ? $dep->dep_aim : $dep->dep_aim_week;
                    }
                    $total_week_goal += $goal;
                }
            }
            $total_week_goal_proc = ($total_week_goal != null && $total_week_goal > 0) ? round(($total_week_success / $total_week_goal) * 100, 2) : 0 ;
            $provision = 0;
            if($user->user_type_id == 22) { //coordinators menager
                $provision = ProvisionLevels::get('coordinator leader', $databasePercentageUsage, $total_week_goal_proc);
            }
            else if($user->user_type_id == 8) {
                $provision = ProvisionLevels::get('koordynator', $databasePercentageUsage, $total_week_goal_proc, $daysInPosition);
            }

            array_push($goals, $total_week_goal_proc);
            array_push($provisions, $provision);
            $totalProvision+= $provision;
        }
        $user->totalProvision = $totalProvision;
        $user->provisions = $provisions;
    }

    public function viewPaymentCadrePost(Request $request)
    {

        $date_to_post = $request->search_money_month;
        $date = $request->search_money_month.'%';
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);

        $dividedMonth = $this->monthPerRealWeekDivision($month, $year);
        $agencies = Agencies::all();
        $salary = DB::table(DB::raw("users"))
            ->whereNotIn('users.user_type_id',[1,2,9])
            ->where('users.salary','>',0)
            ->selectRaw('
            `users`.`id`,
            `users`.`user_type_id`,
            `users`.`agency_id`,
            `users`.`max_transaction`,
            `users`.`first_name`,
            `users`.`last_name`,
            `users`.`salary_to_account`,
            `users`.`username`,
            `department_type`.`id` as department_type_id,
            `departments`.`name` as dep_name, 
            `department_type`.`name`  as dep_type,
            `department_type`.`id` as dep_type_id,
            `department_info`.`id` as department_info_id,
            `users`.`salary`,
            `users`.`additional_salary`,
            `users`.`student`,
            `users`.`documents`,
            `users`.`promotion_date`,
            `users`.`start_work`,
             ROUND(salary / DAY(LAST_DAY("' . $request->search_money_month.'-01' .'")),2) as average_salary,
            (SELECT SUM(`penalty_bonus`.`amount`) FROM `penalty_bonus` WHERE `penalty_bonus`.`id_user`=`users`.`id` AND `penalty_bonus`.`event_date` LIKE "'.$date.'" AND `penalty_bonus`.`type`=1 AND `penalty_bonus`.`status`=1) as `penalty`,
            (SELECT SUM(`penalty_bonus`.`amount`) FROM `penalty_bonus` WHERE `penalty_bonus`.`id_user`=`users`.`id` AND `penalty_bonus`.`event_date` LIKE  "'.$date.'" AND `penalty_bonus`.`type`=2 AND `penalty_bonus`.`status`=1) as `bonus`')
            ->where(function ($query) use ($date){
                $query->orwhere(DB::raw('SUBSTRING(promotion_date,1,7)'),'<', substr($date,0,strlen($date)-1))
                    ->orwhere('users.promotion_date','=',null);
            })
            ->join('department_info','department_info.id','users.main_department_id')
            ->join('work_hours', 'work_hours.id_user', 'users.id')
            ->join('departments','departments.id','department_info.id_dep')
            ->join('department_type','department_type.id','department_info.id_dep_type')
            ->where('work_hours.date', 'like', $date)
            ->groupBy('users.id')
            ->orderBy('users.last_name')->get();

        foreach($salary as $user) {
            if($user->user_type_id == 4) {
//                $this->provisionSystemForTrainers($user,  MonthFourWeeksDivision::get($year, $month));
            }
            else if($user->user_type_id == 5) {
                $this->provisionSystemForHR($user, $month, $year);
            }
            else if($user->user_type_id == 19) {
//                $this->provisionSystemForInstructors($user,  MonthFourWeeksDivision::get($year, $month));
            }
            else if($user->user_type_id == 8 || $user->user_type_id == 22) { //koordynator + menager of coordinators
                $this->provisionSystemForCoordinators($user, $month, $year);
            }
        }
//        dd($salary->where('id', '=', 6));

        $freeDaysData = $this->getFreeDays($dividedMonth); //[id_user, freeDays]
//        dd($freeDaysData);

        /**
         * Pobranie danych osób którzy nie pracowali całego miesiąca
         */
        $days_in_month = date('t', strtotime($request->search_money_month . "-01"));

        //Zdefiniownie ostatniego dnia miesiąca
        $last_day = $request->search_money_month . '-' . $days_in_month;
        //zdefiniowanie pierwszego dnia miesiaca
        $first_day = $request->search_money_month . '-01';

        /**
         * Puste tablice przechowujące dane osob ktorych pensja musi się zmienic
         */
        $working_days = [];
        $work_days_stop = [];
        $work_days_in_between = [];

        /**
         * Pobranie danych osob ktore rozpoczeły prace w tym miesiącu
         */
        $users_by_start = DB::table('users')
            ->select(DB::raw("
                id,
                start_work,
                salary
            "))
            ->where('start_work', 'like', $date)
            ->whereNotIn('user_type_id', [1,2,9])
            ->get();

        /**
         * Obliczenie średniej dziennej pensji oraz ilosci przepracowanych dni
         */
        foreach($users_by_start as $item) {

            $date_diff = strtotime($last_day) - strtotime($item->start_work);

            $user_salary_per_day = $item->salary / $days_in_month;

            $user_salary = $user_salary_per_day * (($date_diff / 3600 / 24) + 1);

            $working_days[$item->id] = round($user_salary, 0);
        }
    
        foreach($salary as $value) {
            foreach($working_days as $key => $item) {
                if ($value->id == $key) {
                    $value->salary = $item;
                }
            }
        }

        /**
        * Pobranie danych osob ktore zakończyły prace w tym miesiącu
        */
        $users_by_stop = DB::table('users')
            ->select(DB::raw("
                id,
                end_work,
                salary
            "))
            ->where('end_work', 'like', $date)
            ->whereNotIn('user_type_id', [1,2])
            ->get();

        /**
         * Obliczenie średniej dziennej pensji oraz ilosci przepracowanych dni
         */
        foreach($users_by_stop as $item) {

            $date_diff = strtotime($item->end_work) - strtotime($first_day);

            $user_salary_per_day = $item->salary / $days_in_month;

            $user_salary = $user_salary_per_day * (($date_diff / 3600 / 24) + 1);

            $work_days_stop[$item->id] = round($user_salary, 0);
        }
     

        foreach($salary as $value) {
            foreach($work_days_stop as $key => $item) {
                if ($value->id == $key) {
                    $value->salary = $item;
                }
            }
        }

        /**
         * Pobranie danych osób które rozpoczeły i zakończyły prace w danym miesiącu
         */
        $in_between = DB::table('users')
            ->select(DB::raw("
                id,
                start_work,
                end_work,
                salary
            "))
            ->where('end_work', 'like', $date)
            ->where('start_work', 'like', $date)
            ->whereNotIn('user_type_id', [1,2,9])
            ->get();

        /**
         * Obliczenie średniej dziennej pensji oraz ilosci przepracowanych dni
         */
        foreach($in_between as $item) {

            $date_diff = strtotime($item->end_work) - strtotime($item->start_work);

            $user_salary_per_day = $item->salary / $days_in_month;

            $user_salary = $user_salary_per_day * (($date_diff / 3600 / 24) + 1);            

            $work_days_in_between[$item->id] = round($user_salary, 0);
        }

        foreach($salary as $value) {
            foreach($work_days_in_between as $key => $item) {
                if ($value->id == $key) {
                    $value->salary = $item;
                }
            }
        }

        foreach($salary as $onePerson) {
            $flag = false;
            foreach($freeDaysData as $freeDayData) {
                if($onePerson->id == $freeDayData['id_user']) {
                    $flag = true;
                    $onePerson->salary -= $onePerson->average_salary * $freeDayData['freeDays'];
                    $onePerson->freeDays = $freeDayData['freeDays'];
                }
            }
            if(!$flag) {
                $onePerson->freeDays = 0;
            }

        }

        /**
         *  Pobranie informacji o departamentach
         */
        $departments = DB::table('department_info')
            ->select(DB::raw('department_info.id as id,departments.name as dep_name,department_type.name as dep_type'))
            ->join('departments','departments.id','department_info.id_dep')
            ->join('department_type','department_type.id','department_info.id_dep_type')
            ->get();

        return view('finances.viewPaymentCadre')
            ->with('month',$date_to_post)
            ->with('salary',$salary->groupby('agency_id'))
            ->with('agencies',$agencies)
            ->with('departments',$departments);
    }

    public function viewPaymentPOST(Request $request)
    {
        $date = $request->search_money_month;
        $salary = $this->getSalary($date.'%');
        $department_info = Department_info::find(Auth::user()->department_info_id);
        $janky_system = JankyPenatlyProc::where('system_id',$department_info->janky_system_id)->get();
        $agencies = Agencies::all();
        $department_type = Department_types::find($department_info->id_dep_type);
        $count_agreement = $department_type->count_agreement;

        $payment_saved = AcceptedPayment::
        where('department_info_id','=',Auth::user()->department_info_id)
            ->where('payment_month','like', $date.'%')
            ->get();

        if($count_agreement == 1)
        {
            return view('finances.viewPayment')
                ->with('month',$date)
                ->with('salary',$salary)
                ->with('department_info',$department_info)
                ->with('janky_system',$janky_system)
                ->with('agencies',$agencies)
                ->with('payment_saved',$payment_saved);
        }
       else
        {
            return view('finances.viewPaymentWithoutAgreement')
                ->with('month',$date)
                ->with('salary',$salary)
                ->with('department_info',$department_info)
                ->with('janky_system',$janky_system)
                ->with('agencies',$agencies)
                ->with('payment_saved',$payment_saved);
        }
    }

    public function viewPenaltyBonusPostEdit(Request $request) {
        $id_manager = Auth::user()->id;

        $user = User::find($request->user_id);

        if ($user == null || ($request->penalty_type != 1 && $request->penalty_type != 2)) {
            return view('errors.404');
        }

        $object = new PenaltyBonus();
        $object->id_user = $request->user_id;
        $object->type = $request->penalty_type;
        $object->amount = $request->cost;
        $object->event_date = date('Y-m-d');
        $object->id_manager = $id_manager;
        $object->comment = $request->reason;
        $object->event_date = $request->date_penalty;
        $object->save();

        $LogData = array_merge(['T ' => 'Dodanie kary/premii '],$object->toArray());
        new ActivityRecorder($LogData, 10, 1);

        $message_type = ($request->penalty_type == 1) ? 'Kara' : 'Premia' ;
        $message = $message_type . ' została dodana pomyślnie';

        $user = User::find($request->user_id);
        $agencies = Agencies::all();

        Session::flash('message', "Kara/premia dodana pomyślnie!");
        return Redirect::back();

    }

    public function createPenaltyBonusPOST(Request $request) {
        $checkUser = User::find($request->user_id);
        if($checkUser == null){
            return view('errors.404');
        }

        $object = new PenaltyBonus();
        $object->id_user = $request->user_id;
        $object->type = $request->type_penalty;
        $object->amount = $request->cost;
        $object->event_date = $request->date_penalty;
        $object->id_manager = Auth::user()->id;
        $object->comment = $request->reason;
        $object->save();
        $LogData = array_merge(['T ' => 'Dodanie kary/premii '],$object->toArray());
        new ActivityRecorder($LogData, 10, 1);
        Session::flash('message_ok', "Kara/premia dodana pomyślnie!");
        return Redirect::back();
    }

    public function viewPenaltyBonusGet()
    {

        // użytkownicy pracujący
        $users =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2, 9])
            ->where('status_work', '=', 1)
            ->orderBy('last_name')
            ->get();
        $last_month = date("Y-m", strtotime("first day of previous month"));
        $current_month = date("Y-m");
        // zwolnieni miesiąc temu
        $users_fired_last_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2, 9])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $last_month.'%')
            ->orderBy('last_name')
            ->get();
        // zwolnieni w tym miesiącu
        $users_fired_current_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2, 9])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $current_month.'%')
            ->orderBy('last_name')
            ->get();
        $merge_array = $users->merge($users_fired_last_month);
        $merge_array = $merge_array->merge($users_fired_current_month);

        return view('finances.viewPenaltyBonus')
            ->with('users',$merge_array->sortBy('last_name'));
    }
    public function viewPenaltyBonusPOST(Request $request)
    {
        // użytkownicy pracujący
        $users =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2, 9])
            ->where('status_work', '=', 1)
            ->orderBy('last_name')
            ->get();

        $last_month = date("Y-m", strtotime("first day of previous month"));
        $current_month = date("Y-m");
        // zwolnieni miesiąc temu
        $users_fired_last_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2, 9])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $last_month.'%')
            ->orderBy('last_name')
            ->get();
        // zwolnieni w tym miesiącu
        $users_fired_current_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2, 9])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $current_month.'%')
            ->orderBy('last_name')
            ->get();
        $merge_array = $users->merge($users_fired_last_month);
        $merge_array = $merge_array->merge($users_fired_current_month);

        $view = view('finances.viewPenaltyBonus')->with('users',$merge_array->sortBy('last_name'));

        $date_start = $request->date_penalty_show_start;
        $date_stop = $request->date_penalty_show_stop;
        $query = DB::table('penalty_bonus')
            ->join('users as users', 'penalty_bonus.id_user', '=', 'users.id')
            ->join('users as manager', 'penalty_bonus.id_manager', '=', 'manager.id')
            ->select(DB::raw(
                'users.first_name,
                users.last_name,
                manager.first_name as manager_first_name,
                manager.last_name as manager_last_name,
                penalty_bonus.*
               '))->where('users.department_info_id',Auth::user()->department_info_id)
                ->whereBetween('event_date', [$date_start, $date_stop])
                ->whereIn('type', [1,2])
                ->whereIn('users.user_type_id',[1,2,9])
                ->where('penalty_bonus.status',1);
        if($request->showuser != -1)
        {
            $query
              ->where('users.id' , $request->showuser)
              ->where('status', 1);
        }
        $view->with('users_show',$query->get())
        ->with('date_start',$date_start)
        ->with('date_stop',$date_stop)
        ->with('showuser',$request->showuser);

        return $view;
    }

    public function viewSummaryPaymentGet()
    {
       return view('finances.viewSummaryPayment');
    }
    public function viewSummaryPaymentPOST(Request $request)
    {
        $month = $request->search_summary_money_month;
        $summary_month = SummaryPayment::where('month',$month)->get();
        $departments = Department_info::where('type','!=','')->get();
        return view('finances.viewSummaryPayment')
            ->with('summary_month',$summary_month)
            ->with('month',$month)
            ->with('departments',$departments);
    }


    public function saveSummaryPayment(Request $request)
    {
        if($request->ajax())
        {
            $summary_payment = SummaryPayment::firstOrNew(array('month' =>$request->month,
                'department_info_id' =>Auth::user()->department_info_id));
            $summary_payment->department_info_id = Auth::user()->department_info_id;
            $summary_payment->month = $request->month;
            $summary_payment->payment = $request->payment_total;
            $summary_payment->month = $request->month;
            $summary_payment->hours = $request->rbh_total;
            $summary_payment->documents =$request->documents_total;
            $summary_payment->students = $request->students_total;
            $summary_payment->employee_count = $request->user_total;
            $summary_payment->id_user = Auth::user()->id;
            $summary_payment->save();
            $LogData = array_merge(['T ' => 'Sumowanie wypłat oddziałów '],$summary_payment->toArray());
            new ActivityRecorder($LogData, 24, 1);
            return $summary_payment;
        }
    }
    public function editPenaltyBonus(Request $request)
    {
        if($request->ajax())
        {
            $object = PenaltyBonus::find($request->id);
            if ($object == null) {
                return 0;
            }
            if ($request->type != 1 && $request->type != 2) {
                return 0;
            }
            $object->type = $request->type;
            $object->amount = $request->amount;
            $object->comment = $request->comment;
            $object->id_manager_edit = Auth::user()->id;
            $object->save();
            $LogData = array_merge(['T ' => 'Edycja kary/premii '],$object->toArray());
            new ActivityRecorder($LogData, 10, 2);
            return 1;
        }
    }


    //Custom Function
    private function getSalary($month)
    {
            $realMonth = substr($month,5,2);
            $realYear = substr($month, 0,4);
            $clientRouteInfoRecords = ClientRouteInfo::where('confirmDate', 'like', $month)->OnlyActive()->get();
            //Czy wypłata jest już zatwierdzona
            $payment_saved = AcceptedPayment::
            where('department_info_id','=',Auth::user()->department_info_id)
            ->where('payment_month','like', $month)
            ->get();
            $string_to_sql = '';
            if(!$payment_saved->isEmpty()){
                $string_to_sql = "`payment_agency_story`.`agency_id`";
            }else{
                $string_to_sql = "`users`.`agency_id`";
            }
        $query = DB::table(DB::raw("users"))
            ->join('work_hours', 'work_hours.id_user', 'users.id')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->where('users.department_info_id',Auth::user()->department_info_id)
            ->where(function ($querry) use ($month){
                $querry->orwhere(DB::raw('SUBSTRING(promotion_date,1,7)'),'>=',substr($month,0,strlen($month)-1))
                    ->orwhere('users.user_type_id','=',1)
                    ->orwhere('users.user_type_id','=',2);
            })
            ->where('work_hours.date','like',$month)
            ->selectRaw('
            `users`.`id`,
            '.$string_to_sql.',              
            `users`.`first_name`,
            `users`.`last_name`,
            `users`.`max_transaction`,
            `users`.`username`,
            `users`.`rate`,
            `users`.`login_phone`,
             SUM( time_to_sec(`work_hours`.`accept_stop`)-time_to_sec(`work_hours`.`accept_start`)) as `sum`,
            `users`.`student`,
            `users`.`documents`,
            (SELECT SUM(`penalty_bonus`.`amount`) FROM `penalty_bonus` WHERE `penalty_bonus`.`id_user`=`users`.`id` AND `penalty_bonus`.`event_date` LIKE "'.$month.'" AND `penalty_bonus`.`type`=1 AND `penalty_bonus`.`status`=1) as `kara`,
            (SELECT SUM(`penalty_bonus`.`amount`) FROM `penalty_bonus` WHERE `penalty_bonus`.`id_user`=`users`.`id` AND `penalty_bonus`.`event_date` LIKE  "'.$month.'" AND `penalty_bonus`.`type`=2 AND `penalty_bonus`.`status`=1) as `premia`,
            SUM(`work_hours`.`success`) as `success`,
            `salary_to_account`,
            `users`.`successorUserId`,
            0 as successorSalary,
            id_dep_type
            ');
            if(!$payment_saved->isEmpty()){
                $query = $query
                    ->leftjoin('payment_agency_story',function ($querry) use ($month){
                        $querry->on('payment_agency_story.consultant_id','=','users.id')
                            ->where('payment_agency_story.accept_month','like',$month);
                    });
            }
            $query = $query
            ->groupBy('users.id')
            ->orderBy('users.last_name');

            $r = DB::table(DB::raw('('.$query->toSql().') as r'))
                ->mergeBindings($query)
                    ->leftjoin(
                    DB::raw('(SELECT `dkj`.`id_user`, COUNT(*) as ods FROM `dkj`
                    WHERE `deleted`=0 AND `add_date` LIKE  "'.$month.'"
                    GROUP by `dkj`.`id_user`
                    ) f '),'r.id','f.id_user'
                )
                ->leftjoin(
                    DB::raw('(SELECT `dkj`.`id_user`, COUNT(*) as janki FROM `dkj` where
                   `deleted`=0 AND `dkj_status`=1 AND `add_date` LIKE  "'.$month.'"
                    GROUP by `dkj`.`id_user`) h'),'r.id','h.id_user'
                )
                ->selectRaw('`id`,`agency_id`,`first_name`,`last_name`,`max_transaction`,`username`,`rate`,`login_phone`,`sum`,`student`,`documents`,`kara`,`premia`,`success`,
            `f`.`ods`,
            `h`.`janki`,
            `salary_to_account`,
            successorUserId,
            successorSalary,
            id_dep_type')->get();


        $weekDayArr = MonthIntoCompanyWeeksDivision::get($realMonth,$realYear);

            $result = $r->map(function($item) use($month, $clientRouteInfoRecords, $weekDayArr) {
                $user_empl_status = UserEmploymentStatus::
                    where( function ($querry) use ($item) {
                    $querry = $querry->orwhere('pbx_id', '=', $item->login_phone)
                        ->orWhere('user_id', '=', $item->id);
                    })
                    ->where('pbx_id_add_date', 'like', $month)
                    ->where('pbx_id', '!=', 0)
                    ->where('pbx_id', '!=', null)
                    ->get();

                $campaignScoresArr = [];

                //creating provision field for each confirming consultant with data about provisions.
                if($item->id_dep_type == 1) { //konsultant potwierdzeń
                    $globalProvisionSum = 0;

                    foreach($weekDayArr as $week) { //looping after each week
                        $weekScoreArr = [];
                        $showRecordsOfGivenUser = $clientRouteInfoRecords
                            ->where('confirmingUser', '=', $item->id)
                            ->where('confirmDate', '>=', $week->firstDay)
                            ->where('confirmDate', '<', $week->lastDay); //all campaigns that he/she confirm in given week
                        $provisionSum = 0;
                        $badCampaigns = 0;
                        foreach($showRecordsOfGivenUser as $show) {
                            $provision = ProvisionLevels::get('consultant', $show->frequency, '1');
                            array_push($weekScoreArr, $provision);
                            if($provision < 0) {
                                $badCampaigns++;
                            }
                            $provisionSum += $provision;
                            $globalProvisionSum += $provision;
                        }
                        $badCampaignsProvision = ProvisionLevels::get('consultant', $badCampaigns, '2');
                        $obj = new \stdClass();
                        $obj->weekNumber = $week->weekNumber;
                        $obj->provisions = $weekScoreArr;
                        $obj->provisionSum =  $provisionSum;
                        $obj->badCampaignsProvision = $badCampaignsProvision; // 50zl for each week without bad campaigns
                        $globalProvisionSum += $badCampaignsProvision;
                        array_push($campaignScoresArr, $obj);
                    }
                    $item->provision = $campaignScoresArr;
                    $item->totalProvision = $globalProvisionSum < 0 ? 0 : $globalProvisionSum;
                }
                $user_empl_status = $user_empl_status->where('user_id','=',$item->id);
                if(count($user_empl_status) == 0 || count($user_empl_status) == 1) {

                    $reports = DB::table('pbx_report_extension')
                        ->select(DB::raw(
                            'SUM(`all_checked_talks`) as sum_all_checked_talks,
                            SUM(`all_bad_talks`) as sum_all_bad_talks,
                            SUM(success) as sum_success
                            '))
                        ->where('pbx_report_extension.pbx_id','=', $item->login_phone)
                        ->whereIn('pbx_report_extension.id', function($query) use($month){
                            $query->select(DB::raw(
                                'MAX(pbx_report_extension.id)'
                            ))
                                ->from('pbx_report_extension')
                                ->where('report_date', 'like', $month)
                                ->groupBy('report_date', 'pbx_id');
                        })
                        ->first();
                    $item->ods = $reports->sum_all_checked_talks;
                    $item->janki = $reports->sum_all_bad_talks;
                    $item->pbx_success = $reports->sum_success;
                }
                else if (count($user_empl_status) > 1) {
                    $sum_janki = $sum_success = $sum_ods = 0;
                    foreach($user_empl_status as $user_status) {

                        $reports = DB::table('pbx_report_extension')
                            ->select(DB::raw(
                                'SUM(`all_checked_talks`) as sum_all_checked_talks,
                            SUM(`all_bad_talks`) as sum_all_bad_talks,
                            SUM(success) as sum_success
                            '))
                            ->where('pbx_report_extension.pbx_id','=', $user_status->pbx_id)
                            ->whereIn('pbx_report_extension.id', function($query) use($user_status,$month){
                                $query->select(DB::raw(
                                    'MAX(pbx_report_extension.id)'
                                ))
                                    ->from('pbx_report_extension');
                                if($user_status->pbx_id_remove_date == null || $user_status->pbx_id_remove_date == '0000-00-00'){
                                    $query->whereBetween('report_date',[$user_status->pbx_id_add_date,substr($month,0,7).'-31',]);
                                }else
                                    $query->whereBetween('report_date',[substr($month,0,7).'-01',$user_status->pbx_id_remove_date]);
                                 $query->groupBy('report_date', 'pbx_id');
                            })
                            ->first();
                        $sum_ods += $reports->sum_all_checked_talks;
                        $sum_janki += $reports->sum_all_bad_talks;
                        $sum_success += $reports->sum_success;
                    }
                    $item->ods = $sum_ods;
                    $item->janki = $sum_janki;
                    $item->pbx_success = $sum_success;
                }
               return $item;
            });

            $this::mapSuccessorSalary(Auth::user()->department_info_id,$r,$month);
            $final_salary = $r->groupBy('agency_id');
            return $final_salary;
    }

    public function mapSuccessorSalary($departmentInfoID,&$usersSalary,$month){
        $map = SuccessorHistory::select('successor_history.*','users.rate','users.successorUserId')->join('users','users.id','successor_history.user_id')
            ->where('users.department_info_id',$departmentInfoID)
            ->where(function ($querry) use ($month){
                $querry->orwhere('successor_history.date_stop','<=',substr($month,0,7).'-31')
                    ->orwhere('successor_history.date_stop',null);
            })
            ->get()->groupBy('user_id');
        $map->map(function ($item) use ($month,&$usersSalary){
            $salaryAdd = 0;
            $user_id = $item->first()->successorUserId;
            $rate = $item->first()->rate;
            foreach ($item as $row){
                $date_stop = $row->date_stop == null ? substr($month,0,7).'-31' : $row->date_stop;
                $salaryAdd +=  $this::getInfoAboutWorkHour($row->user_id,$row->date_start,$date_stop)->first()->sumHour;
            }
            $salaryAdd = round(($salaryAdd/3600)*$rate,2);
            $usersSalary->where('id',$user_id)->map(function ($itemSalary) use ($salaryAdd,$user_id){
                if($itemSalary->id == $user_id){
                    $itemSalary->successorSalary = $salaryAdd;
                }
                return $itemSalary;
            });
            return $item;
        });
    }


    public function getInfoAboutWorkHour($user_id,$date_start,$date_stop){
        return $userInfo = Work_Hour::select(DB::raw(
            'SUM( time_to_sec(`work_hours`.`accept_stop`)-time_to_sec(`work_hours`.`accept_start`)) as `sumHour`,
            sum(success) as sumSucces
            '))
            ->where('id_user',$user_id)
            ->where('date','>=',$date_start)
            ->where('date','<=',$date_stop)
            ->get();
    }
    public function deletePenaltyBonus(Request $request) {
        if($request->ajax())
        {
            $object = PenaltyBonus::find($request->id);

            if ($object != null) {
                $object->id_manager_edit = Auth::user()->id;
                $object->status = 0;
                $object->updated_at = date('Y-m-d H:i:s');
                $object->save();
                $LogData = array_merge(['T ' => 'Usunięcie kary/premii'],$object->toArray());
                new ActivityRecorder($LogData, 25, 3);
                return 1;
            } else {
                return 0;
            }
        }
    }

    /**
     * @param Request $request
     * @return Zapisanie informacji o aktualnym stanie wypłat
     */
    public function paymentStory(Request $request){
        if($request->ajax()){


            //Zapisanie infromacji o zaakceptowaniu wypłat
            $is_exist = AcceptedPayment::
              where('department_info_id','=',Auth::user()->department_info_id)
            ->where('payment_month','like', $request->accetp_month.'%')
            ->get();
            if($is_exist->isEmpty()){
                $accept_payment = new AcceptedPayment();
                $accept_payment->cadre_id =  Auth::user()->id;
                $accept_payment->payment_month = $request->accetp_month.'-01';
                $accept_payment->department_info_id = Auth::user()->department_info_id;
                    $salary = $this::getSalary($request->accetp_month.'%');
                    $data = array();
                    foreach ($salary as $item ){
                        foreach ($item as $value){
                            array_push($data,array('consultant_id' => $value->id,
                                'agency_id' => $value->agency_id,
                                'cadre_id' => Auth::user()->id,
                                'department_info_id' => Auth::user()->department_info_id,
                                'accept_month' => $request->accetp_month.'-01',
                                'created_at' =>date('Y-m-d H:m:s:i'),
                                'updated_at' => date('Y-m-d H:m:s:i')));
                        }
                    }

                    if(session()->has('isPaymentAgencyStoryQueryRunning')){
                        if(session('isPaymentAgencyStoryQueryRunning')){
                            $DOUBLING_QUERY_LOG = new DoublingQueryLogs();
                            $DOUBLING_QUERY_LOG->table_name = 'PaymentAgencyStory';
                            $DOUBLING_QUERY_LOG->save();
                        }else{
                            session(['isPaymentAgencyStoryQueryRunning' => true]);
                            PaymentAgencyStory::insert($data);
                            session()->forget('isPaymentAgencyStoryQueryRunning');
                        }
                    }else{
                        session(['isPaymentAgencyStoryQueryRunning' => true]);
                        PaymentAgencyStory::insert($data);
                        session()->forget('isPaymentAgencyStoryQueryRunning');
                    }

                    if(session()->has('isAcceptedPaymentQueryRunning')){
                        if(session('isAcceptedPaymentQueryRunning')){
                            $DOUBLING_QUERY_LOG = new DoublingQueryLogs();
                            $DOUBLING_QUERY_LOG->table_name = 'AcceptedPayment';
                            $DOUBLING_QUERY_LOG->save();
                        }else{
                            session(['isAcceptedPaymentQueryRunning' => true]);
                            $accept_payment->save();
                            session()->forget('isAcceptedPaymentQueryRunning');
                        }
                    }else{
                        session(['isAcceptedPaymentQueryRunning' => true]);
                        $accept_payment->save();
                        session()->forget('isAcceptedPaymentQueryRunning');
                    }


                    $LogData = array_merge(['T ' => ' Zapisanie wypłat '],$accept_payment->toArray());
                    new ActivityRecorder($LogData, 24, 1);
                    return $data;
            }
            return 0;
        }
    }


    public function viewEmployeeOfTheWeekCadreGet(){
        $userTypes = UserTypes::whereNotIn('id',[1,2,9,3])->get();
        return view('finances.employeeOfTheWeek.viewEmployeeOfTheWeekCadre')
            ->with('userTypes', $userTypes->where('id',4));
    }

    public function employeeOfTheWeekSubViewAjax( Request $request){
        if($request->ajax()){
            if($request->view == 4){
                $selectedMonth = $request->selectedMonth;
                $year = date('Y',strtotime($selectedMonth));
                $month = date('m',strtotime($selectedMonth));
                $dividedMonth = MonthFourWeeksDivision::get($year, $month);
                return view('finances.employeeOfTheWeek.trainerOfTheWeekConfirmation')->with('a','bu');
            }
        }else{
            return view('errors.404');
        }
    }
}
