<?php

namespace App\Http\Controllers;

use App\HourReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

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
            Mail::send('mail.hourReportTelemarketing', $data, function($message)
            {
                $message->from('jarzyna.verona@gmail.com');
                $message->to('jarzyna.verona@gmail.com', 'John Smith')->subject('Welcome!');
            });
        foreach ($data['reports'] as $report) {
            $report->is_send = 1;
            $report->save();
        }
         return view('mail.hourReportTelemarketing')
             ->with('reports', $data['reports'])
             ->with('hour', $data['hour'])
             ->with('date', $data['date']);
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

        $data = [
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'reports' => $reports,
            'work_hours' => $work_hours,
            'hours' => $hours,
            'sum_hours' => $sum_hours,
        ];
        return $data;
    }
//Mail do raportu Tygodniowego Telemarketing
    public function MailweekReportTelemarketing() {
        $data = $this::weekReportTelemarketing();
           Mail::send('mail.weekReportTelemarketing', $data, function($message)
           {
               $message->from('jarzyna.verona@gmail.com');
               $message->to('jarzyna.verona@gmail.com', 'John Smith')->subject('Welcome!');
           });
        return view('mail.weekReportTelemarketing')
            ->with('hours', $data['hours'])
            ->with('work_hours', $data['work_hours'])
            ->with('sum_hours', $data['sum_hours'])
            ->with('reports', $data['reports'])
            ->with('date_start', $data['date_start'])
            ->with('date_stop', $data['date_stop']);
    }
    // Wyswietlenie raportu tygodniowego na stronie 'telemarketing'
    public function pageWeekReportTelemarketing() {
        $data = $this::weekReportTelemarketing();
        return view('reportpage.WeekReportTelemarketing')
            ->with('hours', $data['hours'])
            ->with('work_hours', $data['work_hours'])
            ->with('sum_hours', $data['sum_hours'])
            ->with('reports', $data['reports'])
            ->with('date_start', $data['date_start'])
            ->with('date_stop', $data['date_stop']);
    }

    //zwracanie nazwy miesiąca którego dotyczy statystyka
    function monthReverseName($month) {
        $month_names = array( 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień' );
        $month -= 1;
        $month = ($month < 0) ? 11 : $month ;
        return $month_names[$month];
    }

    function monthReverse($month) {
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
            ->where('department_info.dep_aim','!=',0)
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

        $data = [
            'month_name' => $month_name,
            'reports' => $reports,
            'work_hours' => $work_hours,
            'hours' => $hours,
            'days_list' => $days_list,
        ];
        return $data;
    }
// Wysłanie maila z raportem miesiecznym
    public function MailmonthReportTelemarketing() {
    $month = date('m')-1;
    $year = date('Y');
    $data = $this::monthReportTelemarketing($month,$year);
             Mail::send('mail.monthReportTelemarketing', $data, function($message)
             {
                 $message->from('jarzyna.verona@gmail.com');
                 $message->to('jarzyna.verona@gmail.com', 'John Smith')->subject('Welcome!');
             });

        return view('mail.monthReportTelemarketing')
          ->with('hours', $data['hours'])
          ->with('work_hours', $data['work_hours'])
          ->with('month_name', $data['month_name'])
          ->with('days_list', $data['days_list'])
          ->with('reports', $data['reports']);
    }

public function pageMonthReportTelemarketing()
{
    $month = date('m');
    $year = date('Y');
    $data = $this::monthReportTelemarketing($month,$year);
    return view('reportpage.MonthReportTelemarketing')
        ->with('hours', $data['hours'])
        ->with('work_hours', $data['work_hours'])
        ->with('month_name', $data['month_name'])
        ->with('days_list', $data['days_list'])
        ->with('reports', $data['reports']);
}

    public function weekReportJanky() {

      $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
      $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));


        return view('mail.weekReportJanky')
            ->with('date_start', $date_start)
            ->with('date_stop', $date_stop);
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

        Mail::send('mail.dayReportMissedRepo', $data, function($message)
        {
            $message->from('jarzyna.verona@gmail.com');
            $message->to('jarzyna.verona@gmail.com', 'John Smith')->subject('Welcome!');
        });

        return view('mail.dayReportMissedRepo')
            ->with('reports', $reports)
            ->with('today', $today);
    }

    public function hourReportDkj() {
      $hour = date('H');
      $hour_ago = date('H', time() - 3600);
      $date_start = date('Y-m-d') . ' ' . $hour_ago . '%' ;
      $date_stop = date('Y-m-d') . ' ' . $hour . '%' ;

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
          ->whereBetween('add_date', [$date_start, $date_stop])
          ->groupBy('dkj.department_info_id')
          ->groupBy('department_info.type')
          ->get();

      $data = [
          'dkj' => $dkj,
          'date_stop' => date('H') . ':00:00'
      ];

      Mail::send('mail.hourReportDkj', $data, function($message)
      {
          $message->from('jarzyna.verona@gmail.com');
          $message->to('jarzyna.verona@gmail.com', 'John Smith')->subject('Welcome!');
      });

      return view('mail.hourReportDkj')
          ->with('date_stop', date('H') . ':00:00')
          ->with('dkj', $dkj);
    }

    public function dayReportDkj() {
      $today = date('Y-m-d') . "%";

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
          ->groupBy('dkj.department_info_id')
          ->groupBy('department_info.type')
          ->get();

        $data = [
            'dkj' => $dkj,
            'today' => date('Y-m-d')
        ];

        Mail::send('mail.dayReportDkj', $data, function($message)
        {
            $message->from('jarzyna.verona@gmail.com');
            $message->to('jarzyna.verona@gmail.com', 'John Smith')->subject('Welcome!');
        });

        return view('mail.dayReportDkj')
            ->with('dkj', $dkj)
            ->with('today', date('Y-m-d'));
    }

    public function weekReportDkj() {
      $date_start = '2017-11-29';
      $date_stop = '2017-12-07';

        $dkj = DB::table('dkj')
            ->select(DB::raw('
                users.first_name,
                users.last_name,
                users.dating_type,
                count(dkj.id) as user_sum,
                sum(CASE WHEN dkj.dkj_status = 1 THEN 1 ELSE 0 END) as user_janek,
                sum(CASE WHEN dkj.dkj_status = 0 THEN 1 ELSE 0 END) as user_not_janek,
                sec_to_time(sum(time_to_sec(register_stop) - time_to_sec(register_start))) as work_time
            '))
            ->join('users', 'users.id', '=', 'dkj.id_dkj')
            ->join('work_hours', 'work_hours.id_user', '=', 'users.id')
            ->whereBetween('dkj.add_date', [$date_start, $date_stop])
            ->whereBetween('work_hours.date', [$date_start, $date_stop])
            ->groupBy('dkj.id_dkj')
            ->get();



        return view('mail.weekReportDkj')
            ->with('dkj', $dkj);
    }
}
