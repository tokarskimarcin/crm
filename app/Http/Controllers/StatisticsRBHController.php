<?php

namespace App\Http\Controllers;

use App\Department_info;
use App\Pbx_report_extension;
use App\NewUsersRbhReport;
use App\User;
use App\Utilities\Dates\MonthFourWeeksDivision;
use App\Work_Hour;
use App\Schedule;
use App\VeronaMail;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsRBHController extends Controller
{
    /**
     * Generate Day report 30 RBH GET
     * @param Request $request
     * @return mixed
     */
    public function dayReport30RBHGet() {
        $sThisMonth = date('n');
        $sThisMonthToView  = $sThisMonth <10 ? '0'.$sThisMonth : $sThisMonth;
        $sThisYear = date('Y');
        $SreportDate = date('Y-m-d');
        $iTimeInSHours = 30;
        $iTimeInSeconds = $iTimeInSHours * 60 * 60;

        $CusersWorkingLessThan30RBH = Work_Hour::usersWorkingRBHSelector($iTimeInSHours, '<');
        $CallUsersThisMonth = Work_Hour::usersWhoStartedWorkThisMonth($sThisMonth, $sThisYear);

        $CallUsersThisMonthExtended = Work_Hour::mergeCollection($CallUsersThisMonth,$iTimeInSeconds);

        $CallUsersForReport = collect(array_merge($CusersWorkingLessThan30RBH->toArray(), $CallUsersThisMonthExtended->where('sec_sum','>=',$iTimeInSeconds)->toArray()))->unique('id_user');

        $CallUsersForReport = Pbx_report_extension::getPbxUserStatistics($CallUsersForReport);
        $aCllUsersForReport = $CallUsersForReport->groupBy('dep_id')->sortBy('dep_id');
        $sMonths = Work_Hour::getMonthsNames();
        return view('reportpage.statisticsRBH.DayReport30RBH')
            ->with('allUsersForReport',$aCllUsersForReport)
            ->with('SreportDate',$SreportDate)
            ->with('sMonths',$sMonths)
            ->with('sDayToHeader',date('Y-m-d'))
            ->with('Smonth_selected',$sThisMonthToView);
    }

    /**
     * Generate Day report 30 RBH POST
     * @param Request $request
     * @return mixed
     */
    public function dayReport30RBHPost(Request $request) {
        $sThisMonth = date('n',strtotime(date('Y').'-'.$request->month_selected));
        $sThisMonthToView  = $sThisMonth <10 ? '0'.$sThisMonth : $sThisMonth;
        $sThisYear = date('Y');
        $sActualMonth = date('Y').'-'.$request->month_selected;
        $iTimeInSHours = 30;
        $iTimeInSeconds = $iTimeInSHours * 60 * 60;
        $CusersWorkingLessThan30RBH = Work_Hour::usersWorkingRBHSelector($iTimeInSHours,'<', $sActualMonth);
        $CallUsersThisMonth = Work_Hour::usersWhoStartedWorkThisMonth($sThisMonth, $sThisYear,$sActualMonth);
        $CallUsersThisMonthExtended = Work_Hour::mergeCollection($CallUsersThisMonth,$iTimeInSeconds);
        $CallUsersForReport = collect(array_merge($CusersWorkingLessThan30RBH->toArray(), $CallUsersThisMonthExtended->where('sec_sum','>=',$iTimeInSeconds)->toArray()))->unique('id_user');
        $CallUsersForReport = Pbx_report_extension::getPbxUserStatistics($CallUsersForReport);
        $aCllUsersForReport = $CallUsersForReport->groupBy('dep_id')->sortBy('dep_id');
        $sMonths = Work_Hour::getMonthsNames();
        return view('reportpage.statisticsRBH.DayReport30RBH')
            ->with('allUsersForReport',$aCllUsersForReport)
            ->with('SreportDate',$sActualMonth)
            ->with('sMonths',$sMonths)
            ->with('sDayToHeader',$sActualMonth)
            ->with('Smonth_selected',$sThisMonthToView);
    }

    /**
     * Send mail with statistics
     * @return string
     */
    public function  DayReport30RBHMail(){
        $sThisMonth = date('n');
        $sThisMonthToView  = $sThisMonth <10 ? '0'.$sThisMonth : $sThisMonth;
        $sThisYear = date('Y');
        $SreportDate = date('Y-m-d');
        $iTimeInSHours = 30;
        $iTimeInSeconds = $iTimeInSHours * 60 * 60;

        $CusersWorkingLessThan30RBH = Work_Hour::usersWorkingRBHSelector($iTimeInSHours,'<');
        $CallUsersThisMonth = Work_Hour::usersWhoStartedWorkThisMonth($sThisMonth, $sThisYear);

        $CallUsersThisMonthExtended = Work_Hour::mergeCollection($CallUsersThisMonth,$iTimeInSeconds);

        $CallUsersForReport = collect(array_merge($CusersWorkingLessThan30RBH->toArray(), $CallUsersThisMonthExtended->where('sec_sum','>=',$iTimeInSeconds)->toArray()))->unique('id_user');
        $CallUsersForReport = Pbx_report_extension::getPbxUserStatistics($CallUsersForReport);
        $aCllUsersForReport = $CallUsersForReport->groupBy('dep_id')->sortBy('dep_id');
        $sMonths = Work_Hour::getMonthsNames();

        $title = 'Dzienny raport nowych osób '. date('Y-m-d');
        $data = [
            'allUsersForReport' => $aCllUsersForReport, 'SreportDate' => $SreportDate, 'sMonths' => $sMonths,
            'sDayToHeader' => date('Y-m-d'), 'Smonth_selected' => $sThisMonthToView,
        ];

        $preperMail = new VeronaMail('statisticsRBHMail.dayReport30RBH',$data,$title);
        if($preperMail->sendMail()){
            return 'Mail wysłano';
        }else{
            return 'Błąd podczas wysyłania maila';
        }

    }

    /**
     * GET Report Planing RBH
     * @return mixed
     */
    public function weekReportPlanningRBHGet(){
        $SactualYear = date('Y');
        $SactualWeekNumber = date('W');
        $objectOfSumColumns = Schedule::prepereObjectSumColumn();
        $CsheduleInfo = Schedule::getUsersRBHSchedule($SactualWeekNumber,$SactualYear);
        $CsheduleInfo = Schedule::groupUsersRBHbyDepartments($CsheduleInfo,$objectOfSumColumns);
        $objectOfSumColumns = Schedule::changeSecondsToHourArray($objectOfSumColumns);
        $CsheduleInfo = Schedule::addMissingDepartmentToCollect($CsheduleInfo)->sortBy('department_info_id');
        $SfirstDate   =  date("Y-m-d", strtotime('monday this week'));
        $SlastDate    = date("Y-m-d", strtotime('sunday this week'));
        return View('reportpage.statisticsRBH.WeekReportPlanningRBH')
            ->with('CsheduleInfo',$CsheduleInfo)
            ->with('SfirstDate',$SfirstDate)
            ->with('SlastDate',$SlastDate)
            ->with('columnSum',$objectOfSumColumns);
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
        $objectOfSumColumns = Schedule::prepereObjectSumColumn();
        $CsheduleInfo = Schedule::getUsersRBHSchedule($SactualWeekNumber,$SactualYear);
        $CsheduleInfo = Schedule::groupUsersRBHbyDepartments($CsheduleInfo,$objectOfSumColumns);
        $CsheduleInfo = Schedule::addMissingDepartmentToCollect($CsheduleInfo)->sortBy('department_info_id');
        $objectOfSumColumns = Schedule::changeSecondsToHourArray($objectOfSumColumns);
        $SfirstDate   =  $week_start->modify('monday this week')->format('Y-m-d');
        $SlastDate    = $week_start->modify('sunday this week')->format('Y-m-d');
        return View('reportpage.statisticsRBH.WeekReportPlanningRBH')
            ->with('CsheduleInfo',$CsheduleInfo)
            ->with('SfirstDate',$SfirstDate)
            ->with('SlastDate',$SlastDate)
            ->with('SactualWeekNumber',$SactualWeekNumber)
            ->with('columnSum',$objectOfSumColumns);
    }

    /**
     * Send mail with statistics
     * @return string
     */
    public function WeekReportPlanningRBHMail(){
        $SactualYear = date('Y');
        $SactualWeekNumber = date('W');
        $objectOfSumColumns = Schedule::prepereObjectSumColumn();
        $CsheduleInfo = Schedule::getUsersRBHSchedule($SactualWeekNumber,$SactualYear);
        $CsheduleInfo = Schedule::groupUsersRBHbyDepartments($CsheduleInfo,$objectOfSumColumns);
        $objectOfSumColumns = Schedule::changeSecondsToHourArray($objectOfSumColumns);
        $CsheduleInfo = Schedule::addMissingDepartmentToCollect($CsheduleInfo)->sortBy('department_info_id');
        $SfirstDate   =  date("Y-m-d", strtotime('monday this week'));
        $SlastDate    = date("Y-m-d", strtotime('sunday this week'));
        $title = 'Tygodniowy Raport (Planowanie) ' . $SfirstDate.' '.$SlastDate;
        $data = [
                'CsheduleInfo' => $CsheduleInfo,
                'SfirstDate' => $SfirstDate,
                'SactualWeekNumber' => $SactualWeekNumber,
                'columnSum' => $objectOfSumColumns,
                'SlastDate' => $SlastDate,
        ];
        $preperMail = new VeronaMail('statisticsRBHMail.weekReportPlanningRBH',$data,$title);
        if($preperMail->sendMail()){
            return 'Mail wysłano';
        }else{
            return 'Błąd podczas wysyłania maila';
        }
    }

    /**
     * This is get method for week30RbhReport.
     */
    public function pageWeek30RbhReport() {
        $today = date('Y-m-d');
        $companyWeeks = MonthFourWeeksDivision::get(date('Y'), date('m'));
        $weekIndex = null;

        foreach($companyWeeks as $weekNumber => $value) { //counting index number of company week.
            $todayDateTime = new DateTime($today);
            $firstDayDateTime = new DateTime($value->firstDay);
            $lastDayDateTime = new DateTime($value->lastDay);

            if($todayDateTime >= $firstDayDateTime && $todayDateTime <= $lastDayDateTime) {
                $weekIndex = $weekNumber;
            }
        }

        $date_start = $companyWeeks[$weekIndex]->firstDay;
        $date_stop = $companyWeeks[$weekIndex]->lastDay;

        $data = $this->get30RBHData($date_start, $date_stop);

        $regionalManagersInstructors = Department_info::select('instructor_regional_id', 'users.first_name', 'users.last_name')
            ->join('users', 'department_info.instructor_regional_id', '=', 'users.id')
            ->where('instructor_regional_id', '!=', null)
            ->where('id_dep_type', '=', 2)
            ->distinct()
            ->get();

        return view('reportpage.Week30RbhReport')->with([
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'data' => $data,
            'regionalManagersInstructors' => $regionalManagersInstructors
        ]);
    }

    public function pageWeek30RbhReportPost(Request $request) {
        $date_start = $request->date_start;
        $date_stop = $request->date_stop;

        $data = $this->get30RBHData($date_start, $date_stop);

        $regionalManagersInstructors = Department_info::select('instructor_regional_id', 'users.first_name', 'users.last_name')
            ->join('users', 'department_info.instructor_regional_id', '=', 'users.id')
            ->where('instructor_regional_id', '!=', null)
            ->where('id_dep_type', '=', 2)
            ->distinct()
            ->get();

        return view('reportpage.Week30RbhReport')->with([
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'data' => $data,
            'regionalManagersInstructors' => $regionalManagersInstructors
        ]);
    }

    public function pageMonth30RbhReport() {
        $today = date('Y-m-d');
        $companyWeeks = MonthFourWeeksDivision::get(date('Y'), date('m'));
        $weekIndex = null;

        $date_start = $companyWeeks[0]->firstDay;
        $date_stop = $companyWeeks[count($companyWeeks) - 1]->lastDay;

        $data = $this->get30RBHData($date_start, $date_stop);

        $regionalManagersInstructors = Department_info::select('instructor_regional_id', 'users.first_name', 'users.last_name')
            ->join('users', 'department_info.instructor_regional_id', '=', 'users.id')
            ->where('instructor_regional_id', '!=', null)
            ->where('id_dep_type', '=', 2)
            ->distinct()
            ->get();

        return view('reportpage.Month30RbhReport')->with([
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'data' => $data,
            'regionalManagersInstructors' => $regionalManagersInstructors
        ]);
    }

    public function pageMonth30RbhReportPost(Request $request) {
        $date_start = $request->date_start;
        $date_stop = $request->date_stop;

        $data = $this->get30RBHData($date_start, $date_stop);

        $regionalManagersInstructors = Department_info::select('instructor_regional_id', 'users.first_name', 'users.last_name')
            ->join('users', 'department_info.instructor_regional_id', '=', 'users.id')
            ->where('instructor_regional_id', '!=', null)
            ->where('id_dep_type', '=', 2)
            ->distinct()
            ->get();

        return view('reportpage.Month30RbhReport')->with([
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'data' => $data,
            'regionalManagersInstructors' => $regionalManagersInstructors
        ]);
    }

    /**
     * @param $date_start
     * @param $date_stop
     * @return Collection with keys related to department info id and values matches info about new consultants
     */
    private function get30RBHData($date_start, $date_stop) {

        $maxIds = DB::table('rbh_30_report')
            ->select(DB::raw('
                    MAX(id) as id
                '))
            ->groupBy('user_id')
            ->where([
                ['created_at', '>=', $date_start],
                ['created_at', '<=', $date_stop]
            ])
            ->pluck('id')->toArray();


        //All most recent records from given range
        $data = NewUsersRbhReport::select(
            DB::raw('CONCAT(departments.name, " ", department_type.name) as department_info_id'),
            'first_name',
            'last_name',
            'success',
            'sec_sum',
            'janki',
            'average',
            'received_calls',
            'all_checked_talks',
            'instructor_regional_id'
        )
            ->join('users', 'rbh_30_report.user_id', '=', 'users.id')
            ->join('department_info', 'rbh_30_report.department_info_id', '=', 'department_info.id')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->whereIn('rbh_30_report.id', $maxIds)
            ->orderBy('department_info_id')
            ->orderBy('average', 'DESC')
            ->get();

        $dataGroupedByDepartment = $data->groupBy('department_info_id');

        return $dataGroupedByDepartment;
    }


}
