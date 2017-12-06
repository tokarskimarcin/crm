<?php

namespace App\Http\Controllers;

use App\HourReport;
use Illuminate\Support\Facades\DB;

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

    private function monthReverseName($month) {
        $month_names = array( 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień' );
        $month -= 2;
        $month = ($month < 0) ? 11 : $month ;
        return $month_names[$month];
    }
    private function monthReverse($month) {
        $month -= 1;
        return ($month < 1) ? 12 : $month ;
    }

    public function PageWeekReportTelemarketing()
    {

        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

        $reports = DB::table('hour_report')
            ->select(DB::raw(
                'SUM(call_time) as sum_call_time,
                  AVG(average) as avg_average,
                  SUM(success) as sum_success,
                  AVG(wear_base) as avg_wear_base,
                  SUM(janky_count) as sum_janky_count,
                  department_type.name as dep_name,
                  departments.name as dep_type_name,
                  department_info.*
                   '))
            ->join('department_info', 'department_info.id', '=', 'hour_report.department_info_id')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->whereIn('hour_report.id', function($query) use($date_start, $date_stop){
                $query->select(DB::raw(
                    'MAX(hour_report.id)'
                ))
                    ->from('hour_report')
                    ->whereBetween('report_date', [$date_start, $date_stop])
                    ->groupBy('department_info_id');
            })
            ->groupBy('hour_report.department_info_id')
            ->get();

        $work_hours = DB::table('work_hours')
            ->select(DB::raw(
                'sec_to_time(sum(time_to_sec(register_stop) - time_to_sec(register_start))) as realRBH,
            department_info.id
            '))
            ->join('users', 'users.id', '=', 'work_hours.id_user')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->whereIn('work_hours.id', function($query) use($date_start, $date_stop){
                $query->select(DB::raw('
                  work_hours.id
              '))
                    ->whereBetween('date', [$date_start, $date_stop]);
            })
            ->groupBy('department_info.id')
            ->get();

        $sum_hours = DB::table('work_hours')
            ->select(DB::raw(
                'sec_to_time(sum(time_to_sec(register_stop) - time_to_sec(register_start))) as realRBH,
            department_info.id
            '))
            ->join('users', 'users.id', '=', 'work_hours.id_user')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->whereIn('work_hours.id', function($query) use($date_start, $date_stop){
                $query->select(DB::raw('
                  work_hours.id
              '))
                    ->whereBetween('date', [$date_start, $date_stop]);
            })
            ->get();

        $time_sum_array = 0;
        foreach($work_hours as $work_hour) {
            if ($work_hour->realRBH != null) {
                $time = explode(':', $work_hour->realRBH);
                $time_sum_array += ($time[0]*3600) + ($time[1]*60) + $time[2];
            }
        }
        $hours = round($time_sum_array / 3600, 2);

        return view('reportpage.WeekReportTelemarketing')
                 ->with('hours', $hours)
                 ->with('work_hours', $work_hours)
                 ->with('sum_hours', $sum_hours)
                 ->with('reports', $reports)
                 ->with('date_start', $date_start)
                 ->with('date_stop', $date_stop);
    }

    public function PageMonthReportTelemarketing()
    {
        //ilosc dni pracujących w mieisącu z podziałem na dni normalne/weekendowe
        $list=array();
        $month = 11;
        $year = 2017;

        for($d=1; $d<=31; $d++)
        {
            $time=mktime(12, 0, 0, $month, $d, $year);
            if (date('m', $time)==$month)
                $list[]=date('N-D', $time);
        }
        $normal_day = 0;
        $weekend_day = 0;

        foreach($list as $item) {
            if ($item >= 1 || $item <= 5) {
                $normal_day++;
            } else if ($item == 6) {
                $weekend_day++;
            }
        }

        $days_list = ['normal_day' => $normal_day, 'weekend_day' => $weekend_day];

        $month = date('m') - 1;
        $year = date('Y');
        $month_name = $this::monthReverseName($month);


        if ($month == 1) {
            $date = ($year - 1) . "-" . $this::monthReverse($month) . '-%';
        } else {
            $date = $year . "-" . $month . "-%";
        }

        $reports = DB::table('hour_report')
            ->select(DB::raw(
                'SUM(call_time) as sum_call_time,
                    AVG(average) as avg_average,
                    SUM(success) as sum_success,
                    AVG(wear_base) as avg_wear_base,
                    SUM(janky_count) as sum_janky_count,
                    department_type.name as dep_name,
                    departments.name as dep_type_name,
                    department_info.*
                     '))
            ->join('department_info', 'department_info.id', '=', 'hour_report.department_info_id')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->whereIn('hour_report.id', function($query) use($date){
                $query->select(DB::raw(
                    'MAX(hour_report.id)'
                ))
                    ->from('hour_report')
                    ->where('report_date', 'like', $date)
                    ->groupBy('department_info_id');
            })
            ->groupBy('hour_report.department_info_id')
            ->get();

        //pobieranie sumy godzin pracy dla poszczególnych oddziałów
        $work_hours = DB::table('work_hours')
            ->select(DB::raw(
                'sec_to_time(sum(time_to_sec(register_stop) - time_to_sec(register_start))) as realRBH,
                  department_info.id
                  '))
            ->join('users', 'users.id', '=', 'work_hours.id_user')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->whereIn('work_hours.id', function($query) use($date){
                $query->select(DB::raw('
                        work_hours.id
                    '))
                    ->where('date', 'like', $date);
            })
            ->groupBy('department_info.id')
            ->get();

        $time_sum_array = 0;
        foreach($work_hours as $work_hour) {
            if ($work_hour->realRBH != null) {
                $time = explode(':', $work_hour->realRBH);
                $time_sum_array += ($time[0]*3600) + ($time[1]*60) + $time[2];
            }
        }
        $hours = round($time_sum_array / 3600, 2);

        return view('reportpage.MonthReportTelemarketing')
            ->with('hours', $hours)
            ->with('work_hours', $work_hours)
            ->with('month_name', $month_name)
            ->with('days_list', $days_list)
            ->with('reports', $reports);;
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
