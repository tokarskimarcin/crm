<?php

namespace App\Http\Controllers;

use App\HourReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Department_info;
use App\User;

class StatisticsController extends Controller
{
    // wyswietlenie raportu godzinnego do uzupełnienia
    public function hourReportGet()
    {
        $today = date('Y-m-d');
        $reports = HourReport::where('report_date','like',$today)
            ->where('department_info_id',Auth::user()->department_info_id)->get();
        return view('statistics.hourReport')
            ->with('reports',$reports);
    }
    // wyswietlenie raportu godzinnego do uzupełnienia i zapisu
    public function hourReportPost(Request $request)
    {
        $today = date('Y-m-d');
        $hour = $request->hour;
        $average = $request->average;
        $success = $request->success;
        $employee_count = $request->employee_count;
        $janky_count = $request->janky_count;
        $wear_base = $request->wear_base;
        $call_Time = $request->call_Time;
        $find_report = HourReport::where('hour',$hour)
            ->where('report_date',$today)
            ->where('department_info_id',Auth::user()->department_info_id)
            ->first();
        if($find_report == null)
        {
            $newRaport = new HourReport();
            $newRaport->user_id = Auth::user()->id;
            $newRaport->department_info_id = Auth::user()->department_info_id;
            $newRaport->report_date = $today;
            $newRaport->hour = $hour;
            $newRaport->average = $average;
            $newRaport->success = $success;
            $newRaport->employee_count = $employee_count;
            $newRaport->janky_count = $janky_count;
            $newRaport->wear_base = $wear_base;
            $newRaport->call_Time = $call_Time;
            $newRaport->save();
            $message = "Raport został dodany.";
            $status = 1;
        }else
        {
            $message = "Raport nie został dodany, ponieważ jest już wysłany.";
            $status = 0;
        }
        return redirect()->back()
            ->with('message',$message)
            ->with('status',$status);
    }
    // edycja wpisywanego raportu godzinnego
    public function hourReportEditPost(Request $request)
    {
        $today = date('Y-m-d');
        $record_id = $request->record_id;
        $average = $request->average;
        $success = $request->success;
        $employee_count = $request->employee_count;
        $janky_count = $request->janky_count;
        $wear_base = $request->wear_base;
        $call_Time = $request->call_Time;
        $newRaport = HourReport::find($record_id);
        if ($newRaport != null) {
            if($newRaport->is_send == 0)
            {
                $newRaport->user_id = Auth::user()->id;
                $newRaport->department_info_id = Auth::user()->department_info_id;
                $newRaport->average = $average;
                $newRaport->success = $success;
                $newRaport->employee_count = $employee_count;
                $newRaport->janky_count = $janky_count;
                $newRaport->wear_base = $wear_base;
                $newRaport->call_Time = $call_Time;
                $newRaport->save();
                $message = "Raport został zmieniony";
                $status = 1;
            }else
            {
                $message = "Raport nie został zmienione, ponieważ jest już wysłany.";
                $status = 0;
            }
            return redirect()->back()
                ->with('message',$message)
                ->with('status',$status);
        } else {
          return view('errors.404');
        }
    }

    // Dane do raportu godzinnego Telemarketing
    private function hourReportTelemarketing()
    {
        $date = date('Y-m-d');
        $hour = date('H') . ':00:00'; //tutaj zmienic przy wydawaniu na produkcję na  date('H') - 1

        $reports = HourReport::where('report_date', '=', $date)
            ->where('hour', $hour)
            ->get();

        $data = [
            'hour' => $hour,
            'date' => $date,
            'reports' => $reports
        ];
        return $data;
    }
// Mail do raportu godzinnego telemarketing
    public function MailhourReportTelemarketing() {
        $data = $this::hourReportTelemarketing();

        $title = 'Raport godzinny telemarketing ' . date('Y-m-d');
        $this->sendMailByVerona('hourReportTelemarketing', $data, $title);
        foreach ($data['reports'] as $report) {
            $report->is_send = 1;
            $report->save();
        }
    }
// Wyswietlenie raportu godzinnego na stronie
    public function pageHourReportTelemarketing()
    {
        $data = $this::hourReportTelemarketing();

        return view('reportpage.HourReportTelemarketing')
            ->with('reports', $data['reports'])
            ->with('hour', $data['hour'])
            ->with('date', $data['date']);
    }
// Przygotowanie danych do raportu tygodniowego telemarketing
    private function weekReportTelemarketing()
    {
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

        $reports = DB::table('hour_report')
            ->select(DB::raw(
                'SUM(call_time)/count(`call_time`) as sum_call_time,
                  SUM(average)/count(`call_time`) as avg_average,
                  SUM(success) as sum_success,
                  SUM(wear_base)/count(`call_time`) as avg_wear_base,
                  SUM(janky_count)/count(`call_time`)  as sum_janky_count,
                  department_type.name as dep_name,
                  departments.name as dep_type_name,
                  department_info.*
                   '))
            ->join('department_info', 'department_info.id', '=', 'hour_report.department_info_id')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->where('department_info.dep_aim','!=',0)
            ->whereIn('hour_report.id', function($query) use($date_start, $date_stop){
                $query->select(DB::raw(
                    'MAX(hour_report.id)'
                ))
                    ->from('hour_report')
                    ->whereBetween('report_date', [$date_start, $date_stop])
                    ->groupBy('department_info_id','report_date');
            })
            ->where('department_info.id_dep_type', '=', 2)
            ->groupBy('hour_report.department_info_id')
            ->get();

            //tu był zmiana z godzin na liczbę
        $work_hours = DB::table('work_hours')
            ->select(DB::raw(
                'sum(time_to_sec(register_stop) - time_to_sec(register_start))/3600 as realRBH,
                department_info.id
            '))
            ->join('users', 'users.id', '=', 'work_hours.id_user')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->whereBetween('date', [$date_start, $date_stop])
            ->where('department_info.id_dep_type', '=', 2)
            ->where('users.user_type_id', '=', 1)
            ->groupBy('department_info.id')
            ->get();

        $data = [
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'reports' => $reports,
            'work_hours' => $work_hours,
        ];
        return $data;
    }
//Mail do raportu Tygodniowego Telemarketing
    public function MailweekReportTelemarketing() {
        $data = $this::weekReportTelemarketing();

        $title = 'Raport tygodniowy telemarketing';
        $this->sendMailByVerona('weekReportTelemarketing', $data, $title);
    }
    // Wyswietlenie raportu tygodniowego na stronie 'telemarketing'
    public function pageWeekReportTelemarketing() {
        $data = $this::weekReportTelemarketing();
//
        return view('reportpage.WeekReportTelemarketing')
            ->with('work_hours', $data['work_hours'])
            ->with('reports', $data['reports'])
            ->with('date_start', $data['date_start'])
            ->with('date_stop', $data['date_stop']);
    }

    //dane do raportu dziennego telemarketing
    private function dayReportTelemarketing($type)
    {
        if ($type == 'today') {
            $date = date('Y-m-d');
        } else if ($type == 'yesterday') {
            $date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
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
            ->where('department_info.dep_aim','!=',0)
            ->whereIn('hour_report.id', function($query) use($date){
                $query->select(DB::raw(
                    'MAX(hour_report.id)'
                ))
                    ->from('hour_report')
                    ->where('report_date', '=',$date)
                    ->groupBy('department_info_id');
            })
            ->where('department_info.id_dep_type', '=', 2)
            ->groupBy('hour_report.department_info_id')
            ->get();

        $work_hours = DB::table('work_hours')
            ->select(DB::raw(
                'sum(time_to_sec(register_stop) - time_to_sec(register_start))/3600 as realRBH,
                 department_info.id
            '))
            ->join('users', 'users.id', '=', 'work_hours.id_user')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->where('date', 'like', $date . '%')
            ->where('users.user_type_id', '=', 1)
            ->groupBy('department_info.id')
            ->get();

        $data = [
            'date' => $date,
            'reports' => $reports,
            'work_hours' => $work_hours,
        ];
        return $data;
    }
//Mail do raportu dziennego Telemarketing
    public function MailDayReportTelemarketing() {
        $data = $this::dayReportTelemarketing('yesterday');

        $title = 'Raport dzienny telemarketing '.date("d.m.Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        $this->sendMailByVerona('dayReportTelemarketing', $data, $title);
    }
    // Wyswietlenie raportu dziennego na stronie 'telemarketing'
    public function pageDayReportTelemarketing() {
        $data = $this::dayReportTelemarketing('today');

        return view('reportpage.dayReportTelemarketing')
            ->with('date', $data['date'])
            ->with('work_hours', $data['work_hours'])
            ->with('reports', $data['reports']);
    }

    //zwracanie nazwy miesiąca którego dotyczy statystyka
    private function monthReverseName($month) {
        $month_names = array( 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień' );
        $month -= 1;
        $month = ($month < 0) ? 11 : $month ;
        return $month_names[$month];
    }

    private function monthReverse($month) {
        $month -= 1;
        return ($month < 1) ? 12 : $month ;
    }

    // przygotowanie danych do miesiecznego raportu telemarketingu
    private function monthReportTelemarketing($month,$year){
        $list=array();
        for($d=1; $d<=31; $d++)
        {
            $time=mktime(12, 0, 0, $month, $d, $year);
            if (date('m', $time)==$month)
                $list[]=date('N', $time);
        }
        $normal_day = 0;
        $weekend_day = 0;

        foreach($list as $item) {
            if ($item == '6') {
                $weekend_day++;
            }else
                $normal_day++;
        }

        $days_list = ['normal_day' => $normal_day, 'weekend_day' => $weekend_day];
        $month_name = $this::monthReverseName($month);
        $date = $year . "-" . $month . "-%";
        $month = date('Y') . '-' . $month . '%';

        $reports = DB::table('hour_report')
            ->select(DB::raw(
                    'SUM(call_time)/count(`call_time`) as sum_call_time,
                      SUM(average)/count(`call_time`) as avg_average,
                      SUM(success) as sum_success,
                      SUM(wear_base)/count(`call_time`) as avg_wear_base,
                      SUM(janky_count)/count(`call_time`)  as sum_janky_count,
                    department_type.name as dep_name,
                    departments.name as dep_type_name,
                    department_info.*
                     '))
            ->join('department_info', 'department_info.id', '=', 'hour_report.department_info_id')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->where('department_info.dep_aim','!=',0)
            ->where('department_info.id_dep_type', '=', 2)
            ->whereIn('hour_report.id', function($query) use($month){
                $query->select(DB::raw(
                    'MAX(hour_report.id)'
                ))
                    ->from('hour_report')
                    ->where('report_date', 'like', $month)
                    ->groupBy('department_info_id','report_date');
            })
            ->groupBy('hour_report.department_info_id')
            ->get();

        //pobieranie sumy godzin pracy dla poszczególnych oddziałów
        $work_hours = DB::table('work_hours')
            ->select(DB::raw(
                'sum(time_to_sec(register_stop) - time_to_sec(register_start))/3600 as realRBH,
                  department_info.id
                  '))
            ->join('users', 'users.id', '=', 'work_hours.id_user')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->where('work_hours.date', 'like', $date)
            ->where('users.user_type_id', 1)
            ->groupBy('department_info.id')
            ->get();

        $data = [
            'month_name' => $month_name,
            'reports' => $reports,
            'work_hours' => $work_hours,
            //'hours' => $hours,
            'days_list' => $days_list,
        ];
        return $data;
    }
// Wysłanie maila z raportem miesiecznym
    public function MailmonthReportTelemarketing() {
    $month = date('m') -1;
    $year = date('Y');
    $data = $this::monthReportTelemarketing($month,$year);

    $title = 'Raport miesięczny telemarketing';
    $this->sendMailByVerona('monthReportTelemarketing', $data, $title);
    }
    // wyswietlenie raportu miesiecznego
    public function pageMonthReportTelemarketing()
    {
        $month = date('m');
        $year = date('Y');
        $data = $this::monthReportTelemarketing($month,$year);
        return view('reportpage.MonthReportTelemarketing')
            ->with('work_hours', $data['work_hours'])
            ->with('month_name', $data['month_name'])
            ->with('days_list', $data['days_list'])
            ->with('reports', $data['reports']);
    }

    /*********** tygodniowy raport podwazonych janków *****************/
    private function weekReportJankyData() {
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

        $dkj = DB::table('dkj')
            ->select(DB::raw('
                users.department_info_id,
                departments.name as dep_name,
                department_type.name as dep_name_type,
                SUM(CASE WHEN dkj_status = 1 THEN 1 ELSE 0 END) as janky_sum,
                SUM(CASE WHEN dkj_status = 1 AND manager_status = 0 AND deleted = 0 THEN 1 ELSE 0 END) as confirmed_janky,
                SUM(CASE WHEN dkj_status = 1 AND manager_status = 1 AND deleted = 0 THEN 1 ELSE 0 END) as unconfirmed_janky,
                SUM(CASE WHEN dkj_status = 1 AND manager_status is null AND deleted = 0 THEN 1 ELSE 0 END) as unchecked_janky,
                SUM(CASE WHEN dkj_status = 0 AND manager_status = 1 AND deleted = 0 THEN 1 ELSE 0 END) as anulled_janky,
                SUM(CASE WHEN deleted = 1 THEN 1 ELSE 0 END) as deleted_janky
            '))
            ->join('users', 'users.id', '=', 'dkj.id_user')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->whereBetween('add_date', [$date_start, $date_stop])
            ->groupBy('users.department_info_id')
            ->get();

            $data = [
                'dkj' => $dkj,
                'date_start' => $date_start,
                'date_stop' => $date_stop
            ];
            return $data;
    }
    public function MailweekReportJanky() {
        $data = $this->weekReportJankyData();

        $title = 'Raport tygodniowy janki';
        $this->sendMailByVerona('weekReportJanky', $data, $title);
    }

    public function pageWeekReportJanky(){
        $data = $this->weekReportJankyData();

        return view('reportpage.WeekReportJanky')
            ->with('dkj', $data['dkj'])
            ->with('date_start', $data['date_start'])
            ->with('date_stop', $data['date_stop']);
    }

    public function dayReportMissedRepo() {
        $today = date('Y-m-d');

        $reports = DB::table('hour_report')
            ->select(DB::raw(
              'department_info.id,
              departments.name as dep_name,
              department_type.name as dep_name_type,
              sum(CASE WHEN hour_report.is_send = 1 THEN 1 ELSE 0 END) as send,
              sum(CASE WHEN hour_report.is_send = 0 THEN 1 ELSE 0 END) as missed
              '))
            ->join('department_info', 'department_info.id', '=', 'hour_report.department_info_id')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->where('report_date', '=', $today)
            ->groupBy('department_info_id')
            ->get();

        $data = [
            'reports' => $reports,
            'today' => $today
        ];

        $title = 'Raport dzienny pominięte raporty';
        $this->sendMailByVerona('dayReportMissedRepo', $data, $title);
    }
    // Przygotowanie danych do raportu godzinnego DKJ
    private function hourReportDkj() {
        $today = date('Y-m-d');

        $hour_stop = $today . ' ' . '23:00:00'; //tutaj zmienic przy wydawaniu na produkcję na  date('H') - 1
        $hour_start = $today . ' 07:00:00';

        $dkj = DB::table('dkj')
            ->select(DB::raw('
              dkj.department_info_id,
              departments.name as dep_name,
              department_type.name as dep_name_type,
              department_info.type,
              count(dkj.id) as liczba_odsluchanych,
              sum(CASE WHEN users.dating_type = 0 THEN 1 ELSE 0 END) as badania,
              sum(CASE WHEN users.dating_type = 1 THEN 1 ELSE 0 END) as wysylka,
              SUM(CASE WHEN dkj.dkj_status = 1 AND users.dating_type = 0 THEN 1 ELSE 0 END) as bad_badania,
              SUM(CASE WHEN dkj.dkj_status = 1 AND users.dating_type = 1 THEN 1 ELSE 0 END) as bad_wysylka
          '))
            ->join('users', 'users.id', '=', 'dkj.id_user')
            ->join('department_info', 'department_info.id', '=', 'dkj.department_info_id')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->whereBetween('add_date', [$hour_start, $hour_stop])
            ->groupBy('users.department_info_id')
            ->groupBy('users.dating_type')
            ->get();
        $data = [
            'dkj' => $dkj,
            'date_stop' => date('H') . ':00:00'
        ];
        return $data;

    }

    // Mail do godzinnego raportu DKJ
    public function MailhourReportDkj() {
        $data = $this::hourReportDkj();

        $title = 'Raport godzinny DKJ '.date('Y-m-d');
        $this->sendMailByVerona('hourReportDkj', $data, $title);
    }
    public function pageHourReportDKJ()
    {
        $data = $this::hourReportDkj();

        return view('reportpage.hourReportDkj')
            ->with('date_stop', date('H') . ':00:00')
            ->with('dkj', $data['dkj']);
    }

    private function dayReportDkjData($type) {
      if ($type == 'today') {
          $today = date('Y-m-d') . "%";
          $data_help = date('Y-m-d');
      } else if ($type == 'yesterday') {
          $today = date('Y-m-d', time() - 24 * 3600) . "%";
          $data_help = date('Y-m-d', time() - 24 * 3600);
      }


      $dkj = DB::table('dkj')
          ->select(DB::raw('
              dkj.department_info_id,
              departments.name as dep_name,
              department_type.name as dep_name_type,
              department_info.type,
              count(dkj.id) as liczba_odsluchanych,
              sum(CASE WHEN users.dating_type = 1 THEN 1 ELSE 0 END) as wysylka,
              sum(CASE WHEN users.dating_type = 0 THEN 1 ELSE 0 END) as badania,
              SUM(CASE WHEN dkj.dkj_status = 1 AND users.dating_type = 1 THEN 1 ELSE 0 END) as bad_wysylka,
              SUM(CASE WHEN dkj.dkj_status = 1 AND users.dating_type = 0 THEN 1 ELSE 0 END) as bad_badania
          '))
          ->join('users', 'users.id', '=', 'dkj.id_user')
          ->join('department_info', 'department_info.id', '=', 'dkj.department_info_id')
          ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
          ->join('departments', 'departments.id', '=', 'department_info.id_dep')
          ->where('add_date', 'like', $today)
          ->groupBy('users.department_info_id')
          ->groupBy('department_info.type')
          ->get();

        $data = [
            'dkj' => $dkj,
            'today' => $data_help
        ];
        return $data;
    }

    public function dayReportDkj() {
        $data = $this->dayReportDkjData('yesterday');
        $title = 'Raport dzienny DKJ '.date("d.m.Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        $this->sendMailByVerona('dayReportDkj', $data, $title);
    }

    public function pageDayReportDKJ() {
        $data = $this->dayReportDkjData('today');

        return view('reportpage.DayReportDkj')
            ->with('today', $data['today'])
            ->with('dkj', $data['dkj']);
    }

    //przygotowanie danych do raportu tygodniowego dkj
    private function weekReportDkjData() {
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

        $dkj = DB::table('users')
            ->select(DB::raw('
                users.id,
                users.first_name,
                users.last_name,
                users.dating_type,
                count(*) as user_sum,
                sum(CASE WHEN dkj.dkj_status = 1 THEN 1 ELSE 0 END) as user_janek,
                sum(CASE WHEN dkj.dkj_status = 0 THEN 1 ELSE 0 END) as user_not_janek
            '))
            ->join('dkj', 'users.id', '=', 'dkj.id_dkj')
            ->whereBetween('dkj.add_date', [$date_start.' 00:00:00', $date_stop.' 23:00:00'])
            ->groupBy('dkj.id_dkj')
            ->get();

        $work_hours = DB::table('users')
            ->select(DB::raw('
                users.id,
                sec_to_time(sum(time_to_sec(register_stop) - time_to_sec(register_start))) as work_time
            '))
            ->join('work_hours', 'users.id', '=', 'work_hours.id_user')
            ->whereBetween('work_hours.date', [$date_start, $date_stop])
            ->where('users.user_type_id', '=', 2)
            ->groupBy('users.id')
            ->get();

        $data = [
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'dkj' => $dkj,
            'work_hours' => $work_hours
        ];
        return $data;
    }

    //wyswietlanie danych raportu tygodniowego dla DKJ
    public function pageWeekReportDKJ() {
      $data = $this->weekReportDkjData();

        return view('reportpage.WeekReportDkj')
            ->with('date_start', $data['date_start'])
            ->with('date_stop', $data['date_stop'])
            ->with('work_hours', $data['work_hours'])
            ->with('dkj', $data['dkj']);
    }

    //wysyłanie email (raport tygodniowy dkj)
    public function MailWeekReportDkj() {
      $data = $this->weekReportDkjData();

      $title = 'Raport tygodniowy DKJ';
      $this->sendMailByVerona('weekReportDkj', $data, $title);
    }

    //przygotowanie danych do raportu miesięcznego dkj
    private function MonthReportDkjData() {
        $month = $this->monthReverse(date('m'));
        $year = date('Y');
        if ($month == 12) {
            $year -= 1;
        }
        $selected_date = $year . '-' . $month . '%';

        $dkj = DB::table('users')
            ->select(DB::raw('
                users.id,
                users.first_name,
                users.last_name,
                users.dating_type,
                count(*) as user_sum,
                sum(CASE WHEN dkj.dkj_status = 1 THEN 1 ELSE 0 END) as user_janek,
                sum(CASE WHEN dkj.dkj_status = 0 THEN 1 ELSE 0 END) as user_not_janek
            '))
            ->join('dkj', 'users.id', '=', 'dkj.id_dkj')
            ->where('dkj.add_date', 'like', $selected_date)
            ->groupBy('dkj.id_dkj')
            ->get();

        $work_hours = DB::table('users')
            ->select(DB::raw('
                users.id,
                sec_to_time(sum(time_to_sec(register_stop) - time_to_sec(register_start))) as work_time
            '))
            ->join('work_hours', 'users.id', '=', 'work_hours.id_user')
            ->where('work_hours.date', 'like', $selected_date)
            ->groupBy('users.id')
            ->where('users.user_type_id', '=', 2)
            ->get();


        $data = [
            'month_name' => $this->monthReverseName($month),
            'dkj' => $dkj,
            'work_hours' => $work_hours
        ];
        return $data;
    }

    //wysyłanie raportu miesięcznego pracownicy dkj
    public function monthReportDkj() {
      $data = $this->MonthReportDkjData();

      $title = 'Raport miesięczny DKJ';
      $this->sendMailByVerona('monthReportDkj', $data, $title);
    }

    //wyswietlanie raoprtu miesiecznego pracownicy dkj
    public function pageMonthReportDKJ(){
        $data = $this->MonthReportDkjData();

        return view('reportpage.MonthReportDkj')
            ->with('month_name', $data['month_name'])
            ->with('dkj', $data['dkj'])
            ->with('work_hours', $data['work_hours']);
    }

    /****************** RAPORTY ODSŁUCH ***********************/

    /**************** Dane z hour report*******************************/

    private function getHourReportData($type, $date = null, $hour = null, $hour_start = null) {

        $reports = DB::table('hour_report')
              ->select(DB::raw('
                  hour_report.department_info_id,
                  hour_report.success,
                  departments.name as dep_name,
                  department_type.name as dep_name_type
              '))
              ->join('department_info', 'department_info.id', '=', 'hour_report.department_info_id')
              ->join('departments', 'departments.id', '=', 'department_info.id_dep')
              ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type');

        if ($type == 'hourReport') {
            $reports->where('hour_report.report_date', '=', $date)
                ->where('hour_report.hour', '=', $hour);
        } else if ($type == 'dayReport') {
            $reports->whereIn('hour_report.id', function($query) use($date){
                  $query->select(DB::raw('
                    MAX(hour_report.id)
                  '))
                  ->from('hour_report')
                  ->where('hour_report.report_date', '=', $date)
                  ->groupBy('hour_report.department_info_id');
              });
        }

        $reports = $reports->get();
        return $reports;
    }

    private function hourReportCheckedData() {
        $date = date('Y-m-d');
        $hour = date('H') . ':00:00';
        $hour_stop = '23:00:00';
        $hour_start = '07:00:00';

        $reports = $this->getHourReportData('hourReport', $date, $hour);

        $dkj = DB::table('dkj')
            ->select(DB::raw('
                users.department_info_id,
                count(*) as dkj_sum
            '))
            ->join('users', 'users.id', '=', 'dkj.id_user')
            ->whereBetween('add_date', [$date . ' ' . $hour_start, $date . ' ' . $hour_stop])
            ->groupBy('users.department_info_id')
            ->get();

        $data = [
            'date'    => $date,
            'hour'    => $hour,
            'hour_start'    => $hour_start,
            'reports' => $reports,
            'dkj'     => $dkj
        ];
        return $data;
    }

    //wysyłanie emaili - raport godzinny odsłuchanych rozmów
    public function hourReportChecked() {
        $data = $this->hourReportCheckedData();

        $title = 'Raport godzinny odsłuchanych rozmów '.date('Y-m-d');
        $this->sendMailByVerona('hourReportChecked', $data, $title);
    }

    //wyswietlanie widoku odsłuchu godzinnego
    public function pageHourReportChecked() {
        $data = $this->hourReportCheckedData();

        return view('reportpage.HourReportChecked')
          ->with('date', $data['date'])
          ->with('hour', $data['hour'])
          ->with('dkj', $data['dkj'])
          ->with('reports', $data['reports']);
    }

    //dane do raportu dziennego odsłuchancyh rozmow
    private function dayReportCheckedData($type) {
        if ($type == 'today') {
            $today = date('Y-m-d');
            $data_help = date('Y-m-d');
        } else if ($type == 'yesterday') {
            $today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
            $data_help = $today;
        }

        $hour_reports = $this->getHourReportData('dayReport', $today);

        $dkj = DB::table('dkj')
            ->select(DB::raw('
                users.department_info_id,
                count(*) as dkj_sum
            '))
            ->join('users', 'users.id', '=', 'dkj.id_user')
            ->where('add_date', 'like', $today . '%')
            ->groupBy('users.department_info_id')
            ->get();

        $data = [
            'today' => $today,
            'hour_reports' => $hour_reports,
            'dkj' => $dkj
        ];
        return $data;
    }

    //wysyłanie emaili (raport dzienny odłsuchanych rozmów)
    public function dayReportChecked() {
      $data = $this->dayReportCheckedData('yesterday');

      $title = 'Raport dzienny odsłuchanych rozmów '.date("d.m.Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
      $this->sendMailByVerona('dayReportChecked', $data, $title);
    }

    //wyświetlanie raportu odsłuchanych rozmów (raport dzienny)
    public function pageDayReportChecked() {
        $data = $this->dayReportCheckedData('today');

        return view('reportpage.DayReportChecked')
            ->with('hour_reports', $data['hour_reports'])
            ->with('dkj', $data['dkj'])
            ->with('today', $data['today']);
    }

    //przygotowanie danych dla raportu tygodniowego odsłuchane rozmowy
    private function weekReportCheckedData() {
          $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
          $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

          $hour_reports = DB::table('hour_report')
              ->select(DB::raw('
                department_info_id,
                sum(success) as success,
                departments.name as dep_name,
                department_type.name as dep_name_type
              '))
              ->join('department_info', 'department_info.id', '=', 'hour_report.department_info_id')
              ->join('departments', 'departments.id', '=', 'department_info.id_dep')
              ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
              ->whereIn('hour_report.id', function($query) use($date_start, $date_stop){
                  $query->select(DB::raw('
                    MAX(hour_report.id)
                  '))
                  ->from('hour_report')
                  ->whereBetween('hour_report.report_date', [$date_start, $date_stop])
                  ->groupBy('hour_report.department_info_id')
                  ->groupBy('hour_report.report_date');
              })
              ->where('department_info.id_dep_type', '=', 2)
              ->groupBy('hour_report.department_info_id')
              ->get();

          $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
          $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

          $date_start .= ' 00:00:00';
          $date_stop .= ' 23:00:00';

          $dkj = DB::table('dkj')
              ->select(DB::raw('
                  users.department_info_id,
                  count(*) as department_sum
              '))
              ->join('users', 'users.id', '=', 'dkj.id_user')
              ->whereBetween('add_date', [$date_start, $date_stop])
              ->groupBy('users.department_info_id')
              ->get();

          $data = [
              'date_start' => $date_start,
              'date_stop' => $date_stop,
              'hour_reports' => $hour_reports,
              'dkj' => $dkj
          ];
          return $data;
    }

    //Wysyłanie maila raport tygodniowy odsłuchane rozmowy
    public function weekReportChecked() {
        $data = $this->weekReportCheckedData();

        $title = 'Raport tygodniowy odsłuchanych rozmów';
        $this->sendMailByVerona('weekReportChecked', $data, $title);
    }

    //wyświetlanie widoku raport tygodniowy odsłuchane rozmowy
    public function pageWeekReportChecked() {
        $data = $this->weekReportCheckedData();

        return view('reportpage.WeekReportChecked')
            ->with('date_start', $data['date_start'])
            ->with('date_stop', $data['date_stop'])
            ->with('dkj', $data['dkj'])
            ->with('hour_reports', $data['hour_reports']);
    }

    /******** Główna funkcja do wysyłania emaili*************/
    /*
    * $mail_type - jaki mail ma być wysłany - typ to nazwa ścieżki z web.php
    * $data - $dane przekazane z metody
    *
    */

    private function sendMailByVerona($mail_type, $data, $mail_title) {
//        $email = [];
//
//        $mail_type2 = ucfirst($mail_type);
//        $mail_type2 = 'page' . $mail_type2;
//
//        $accepted_users = DB::table('users')
//            ->select(DB::raw('
//            users.first_name,
//            users.last_name,
//            users.username,
//            users.email_off
//            '))
//            ->join('privilage_relation', 'privilage_relation.user_type_id', '=', 'users.user_type_id')
//            ->join('links', 'privilage_relation.link_id', '=', 'links.id')
//            ->where('links.link', '=', $mail_type2)
//            ->where('users.status_work', '=', 1)
//            ->where('users.id', '!=', 4592) // tutaj szczesna
//            ->get();
//
//            $szczesny = new User();
//            $szczesny->username = 'bartosz.szczesny@veronaconsulting.pl';
//            $szczesny->first_name = 'Bartosz';
//            $szczesny->last_name = 'Szczęsny';
//            $accepted_users->push($szczesny);

// dd($accepted_users);
    $accepted_users = [
        'cytawa.verona@gmail.com',
        'jarzyna.verona@gmail.com'
    ];


     Mail::send('mail.' . $mail_type, $data, function($message) use ($accepted_users, $mail_title)
     {
        $message->from('noreply.verona@gmail.com', 'Verona Consulting');
        foreach ($accepted_users as $key => $user) {
          if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
              $message->to($user)->subject($mail_title);
          }
        }
     });


      /* UWAGA !!! ODKOMENTOWANIE TEGO POWINNO ZACZĄC WYSYŁAĆ MAILE*/
//       Mail::send('mail.' . $mail_type, $data, function($message) use ($accepted_users, $mail_title)
//       {
//           $message->from('noreply.verona@gmail.com', 'Verona Consulting');
//           foreach($accepted_users as $user) {
//            if (filter_var($user->username, FILTER_VALIDATE_EMAIL)) {
//                $message->to($user->username, $user->first_name . ' ' . $user->last_name)->subject($mail_title);
//             } else if (filter_var($user->email_off, FILTER_VALIDATE_EMAIL)) {
//                $message->to($user->email_off, $user->first_name . ' ' . $user->last_name)->subject($mail_title);
//             }
//           }
//       });

    }
}
