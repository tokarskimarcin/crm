<?php

namespace App\Http\Controllers;

use App\DisableAccountInfo;
use App\HourReport;
use App\Pbx_report_extension;
use App\PBXDKJTeam;
use App\RecruitmentStory;
use DateTime;
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
        $last_reports = HourReport::where('report_date', '=', $date)
            ->where('hour', date('H')-1 . ':00:00')
            ->get();

        $data = [
            'hour' => $hour,
            'date' => $date,
            'reports' => $reports,
            'last_reports' => $last_reports
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
            ->with('date', $data['date'])
            ->with('last_reports', $data['last_reports']);
    }
// Przygotowanie danych do raportu tygodniowego telemarketing
    private function weekReportTelemarketing()
    {
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

        $reports = DB::table('hour_report')
            ->select(DB::raw(
                'SUM(call_time)/count(`call_time`) as sum_call_time,
                  SUM(success)/sum(`hour_time_use`) as avg_average,
                  SUM(success) as sum_success,
                  sum(`hour_time_use`) as hour_time_use,
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
                    ->where('call_time', '!=',0)
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
                    ->where('call_time', '!=',0)
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
        //Pobranie danych na temat ilości przepracowanych dni w danym miesiącu
        $check_working_days = DB::table('hour_report')
            ->select(DB::raw('
                DISTINCT(report_date),
                department_info_id,
                department_info.dep_aim as normal_day,
                department_info.dep_aim_week as week_day
            '))
            ->join('department_info', 'department_info.id', 'hour_report.department_info_id')
            ->whereBetween('report_date', [$year . '-' . $month . '-01', $year . '-' . $month . '-31'])
            ->where('average', '!=', 0)
            ->get();

        
        //Zdefiniowanie głównej tablicy do przekazania do widoku
        $result_days = array();
        //Zdefiniowanie tablicy tymczasowej
        $list = array();

        //Pogrupowanie danych ze względu na departamenty 
        $departments_keys = $check_working_days->groupBy('department_info_id');

        //ilteracja po poszczegolnych oddziałach 
        foreach($departments_keys as $key => $value) {
            
            //Iteracja 31 razy
            for($d = 1; $d <= 31; $d++)
            {
                //Zdefioniowanie czasu
                $time=mktime(12, 0, 0, $month, $d, $year);
                //przepierdolenie czasu do czytelnego formatu
                $time_format = date('Y-m-d', $time);

                //Flaga (ustala czy oddział pracował w danym dniu i dzien się wlicza)
                $add_date = false;

                //Sprawdzenie czy dzien był dla oddziału pracujący
                foreach ($value as $key2 => $value2) {
                    if($value2->report_date == $time_format) {
                        $add_date = true;
                        break;
                    }
                }

                //Jezeli dzien miesci się w zakresie dni z danego miesiaca i dzien był dla oddziału pracujący
                if (date('m', $time)==$month && $add_date == true) {
                    //dodanie dnia do listy
                    $list[$key][]=date('N', $time);
                }
            }
        }

        //Sumowanie dni dla poszczegolnych oddziałów (z podziałem na dni zwykłe/weekendowe)
        foreach($list as $key => $item) {
            $normal_day = 0;
            $weekend_day = 0;

            foreach($item as $value) {
                //sprawdzenie typu dnia
                if ($value == '6' || $value == '7') {
                    $weekend_day++;
                } else {
                    $normal_day++;
                }
            }

            $result_days[$key]['normal_day'] = $normal_day;
            $result_days[$key]['week_day'] = $weekend_day;
        }

        //dd($result_days);

        // for($d=1; $d<=31; $d++)
        // {
        //     $time=mktime(12, 0, 0, $month, $d, $year);
        //     if (date('m', $time)==$month)
        //         $list[]=date('N', $time);
        // }
        // $normal_day = 0;
        // $weekend_day = 0;

        // foreach($list as $item) {
        //     if ($item == '6') {
        //         $weekend_day++;
        //     } elseif ($item != '7' && $item != '6') {
        //         $normal_day++;
        //     }
        // }

        //$days_list = ['normal_day' => $normal_day, 'weekend_day' => $weekend_day];
        $month_name = $this::monthReverseName($month);
        $date = $year . "-" . $month . "-%";
        $month = date('Y') . '-' . $month . '%';

        $reports = DB::table('hour_report')
            ->select(DB::raw(
                    'SUM(call_time)/count(`call_time`) as sum_call_time,
                       SUM(success)/sum(`hour_time_use`) as avg_average,
                       sum(`hour_time_use`) as hour_time_use,
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
            'result_days' => $result_days
            //'days_list' => $days_list,
        ];
        
        return $data;
    }
// Wysłanie maila z raportem miesiecznym
    public function MailmonthReportTelemarketing() {
        $month = date('m') - 1;
        if ($month < 10) {
            $month = '0' . $month;
        }
        $year = date('Y');

        if ($month == 12) {
            $year -= 1;
        }
        $data = $this::monthReportTelemarketing($month,$year);

        $title = 'Raport miesięczny telemarketing';
        $this->sendMailByVerona('monthReportTelemarketing', $data, $title);
    }
    // wyswietlenie raportu miesiecznego
    public function pageMonthReportTelemarketing()
    {
        $month = date('m');
    // Tymczasowo dla testow
    //     $month = date('m') - 1;
    // if ($month < 10) {
    //     $month = '0' . $month;
    // } 
        $year = date('Y');
        $data = $this::monthReportTelemarketing($month,$year);
        return view('reportpage.MonthReportTelemarketing')
            ->with('work_hours', $data['work_hours'])
            ->with('month_name', $data['month_name'])
            ->with('result_days', $data['result_days'])
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
    private function hourReportDkj_PBX_READY() {

        $date = date('Y-m-d');
        $hour = date('H') . ':00:00'; //tutaj zmienic przy wydawaniu na produkcję na  date('H') - 1

        $reports = PBXDKJTeam::where('report_date', '=', $date)
            ->where('hour', $hour)
            ->get();
        $data = [
            'hour' => $hour,
            'date' => $date,
            'reports' => $reports
        ];
        return $data;
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
    public function MailhourReportDkj()
    {
        //$data = $this::hourReportDkj();
        $data = $this::hourReportDkj_PBX_READY(); //Gotowe na pbx
        $title = 'Raport godzinny DKJ ' . date('Y-m-d');
        $this->sendMailByVerona('hourReportDkj', $data, $title);
    }

    public function pageHourReportDKJ()
    {

            //wersja na pobx
//        $data = $this::hourReportDkj();
//        return view('reportpage.hourReportDkj')
//            ->with('date', $data['hour'])
//            ->with('hour', date('H') . ':00:00')
//            ->with('reports', $data['reports']);

        //$data = $this::hourReportDkj();
        $data = $this::hourReportDkj_PBX_READY();// Gotowe na pbx

            return view('reportpage.hourReportDkj')
                ->with('date', date('H') . ':00:00')
                ->with('reports', $data['reports']);
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

    //wyswietlanie danych raportu tygodniowego dla pracownikow DKJ
    public function pageWeekReportEmployeeDkj() {
        $data = $this->weekReportEmployeeDkjData();

        return view('reportpage.WeekReportEmployeeDkj')
            ->with('date_start', $data['date_start'])
            ->with('date_stop', $data['date_stop'])
            ->with('work_hours', $data['work_hours'])
            ->with('dkj', $data['dkj']);
    }

    //wysyłanie email (raport tygodniowy pracownikow dkj)
    public function MailweekReportEmployeeDkj() {
        $data = $this->weekReportEmployeeDkjData();

        $title = 'Raport tygodniowy pracowników DKJ '.$data['date_start'].' - '.$data['date_stop'];
        $this->sendMailByVerona('weekReportEmployeeDkj', $data, $title);
    }

    //przygotowanie danych do raportu tygodniowego pracownikow dkj
    private function weekReportEmployeeDkjData() {
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

    //przygotowanie danych do raportu tygodniowego dkj
    private function weekReportDkjData() {
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));


        $dkj = DB::table('pbx_dkj_team')
            ->select(DB::raw(
                'SUM(count_all_check) as sum_all_talks,
                SUM(count_good_check) as sum_correct_talks,
                SUM(count_bad_check) as sum_janky,
                departments.name as dep, 
                department_type.name as depname,
                count(departments.name)
                   '))
            ->join('department_info', 'department_info.id', '=', 'pbx_dkj_team.department_info_id')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->where('department_info.dep_aim','!=',0)
            ->whereIn('pbx_dkj_team.id', function($query) use($date_start, $date_stop){
                $query->select(DB::raw(
                    'MAX(pbx_dkj_team.id)'
                ))
                    ->from('pbx_dkj_team')
                    ->whereBetween('report_date', [$date_start, $date_stop])
                    ->groupBy('department_info_id','report_date');
            })
            ->where('department_info.id_dep_type', '=', 2)
            ->groupBy('pbx_dkj_team.department_info_id')
            ->get();

//            dd($dkj);
        $data = [
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'dkj' => $dkj
        ];
        return $data;
    }

    //wysyłanie maila z raportem pracownikow dkj (wczorajszy)
    public function MaildayReportEmployeeDkj()
    {
        $data = $this->dayReportEmployeeDkjData('yesterday');
        $title = 'Raport dzienny pracowników DKJ '.date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        $this->sendMailByVerona('dayReportEmployeeDkj', $data, $title);
    }
    // wyświetlenie strony z raportem pracownikow dkj
    public function pageDayReportEmployeeDkj()
    {
        $data = $this->dayReportEmployeeDkjData('today');
        return view('reportpage.DayReportEmployeeDkj')
            ->with('date', $data['date'])
            ->with('work_hours', $data['work_hours'])
            ->with('dkj', $data['dkj']);
    }

    public function dayReportEmployeeDkjData($type)
    {
            $date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));

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
            ->whereBetween('dkj.add_date', [$date.' 00:00:00', $date.' 23:00:00'])
            ->groupBy('dkj.id_dkj')
            ->get();

        $work_hours = DB::table('users')
            ->select(DB::raw('
                users.id,
                sec_to_time(sum(time_to_sec(register_stop) - time_to_sec(register_start))) as work_time
            '))
            ->join('work_hours', 'users.id', '=', 'work_hours.id_user')
            ->where('work_hours.date', $date)
            ->where('users.user_type_id', '=', 2)
            ->groupBy('users.id')
            ->get();

        $data = [
            'date' => $date,
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
            ->with('dkj', $data['dkj']);
    }

    //wysyłanie email (raport tygodniowy dkj)
    public function MailWeekReportDkj() {
      $data = $this->weekReportDkjData();

      $title = 'Raport tygodniowy DKJ ' . $data['date_start'] . ' - ' . $data['date_stop'];
      $this->sendMailByVerona('weekReportDkj', $data, $title);
    }

    //przygotowanie danych do raportu miesięcznego dkj
    private function MonthReportDkjData() {
        $month = $this->monthReverse(date('m'));
        $year = date('Y');
        if ($month < 10) {
            $month = '0' . $month;
        }
        if ($month == 12) {
            $year -= 1;
        }
        $selected_date = $year . '-' . $month . '%';

        $month_ini = new DateTime("first day of this month");
        $date_start = $month_ini->format('Y-m-d');
        $month_end = new DateTime("last day of this month");
        $date_stop = $month_end->format('Y-m-d');

//        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
//        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

//        $dkj = DB::table('users')
//            ->select(DB::raw('
//                users.id,
//                users.first_name,
//                users.last_name,
//                users.dating_type,
//                count(*) as user_sum,
//                sum(CASE WHEN dkj.dkj_status = 1 THEN 1 ELSE 0 END) as user_janek,
//                sum(CASE WHEN dkj.dkj_status = 0 THEN 1 ELSE 0 END) as user_not_janek
//            '))
//            ->join('dkj', 'users.id', '=', 'dkj.id_dkj')
//            ->where('dkj.add_date', 'like', $selected_date)
//            ->groupBy('dkj.id_dkj')
//            ->get();
//
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

        $dkj = DB::table('pbx_dkj_team')
            ->select(DB::raw(
                'SUM(count_all_check) as sum_all_talks,
                SUM(count_good_check) as sum_correct_talks,
                SUM(count_bad_check) as sum_janky,
                departments.name as dep, 
                department_type.name as depname,
                count(departments.name)
                   '))
            ->join('department_info', 'department_info.id', '=', 'pbx_dkj_team.department_info_id')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->where('department_info.dep_aim','!=',0)
            ->whereIn('pbx_dkj_team.id', function($query) use($date_start, $date_stop){
                $query->select(DB::raw(
                    'MAX(pbx_dkj_team.id)'
                ))
                    ->from('pbx_dkj_team')
                    ->whereBetween('report_date', [$date_start, $date_stop])
                    ->groupBy('department_info_id','report_date');
            })
            ->where('department_info.id_dep_type', '=', 2)
            ->groupBy('pbx_dkj_team.department_info_id')
            ->get();

        $data = [
            'month_name' => $this->monthReverseName($month),
            'work_hours' => $work_hours,
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'dkj' => $dkj
        ];
//        dd($data);
//        $data = [
//            'month_name' => $this->monthReverseName($month),
//            'dkj' => $dkj,
//            'work_hours' => $work_hours
//        ];
      
        return $data;
    }

    //wysyłanie raportu miesięcznego pracownicy dkj
    public function monthReportDkj() {
      $data = $this->MonthReportDkjData();
        $users = User::whereIn('id', [4796, 1364, 6009]);
      $title = 'Raport miesięczny DKJ';
      $this->sendMailByVerona('monthReportDkj', $data, $title, $users);
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
        $today = date('Y-m-d');

        $hour_stop = $today . ' ' . '23:00:00';
        $hour_start = $today . ' 07:00:00';

        $dkj = DB::table('dkj')
            ->select(DB::raw('
              users.department_info_id,
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
                users.dating_type,
                count(*) as dkj_sum
            '))
            ->join('users', 'users.id', '=', 'dkj.id_user')
            ->where('add_date', 'like', $today . '%')
            ->groupBy('users.department_info_id')
            ->groupBy('users.dating_type')
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
              //->where('department_info.id_dep_type', '=', 2)
              ->groupBy('hour_report.department_info_id')
              ->get();

          $day_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
          $day_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

          $date_start .= ' 00:00:00';
          $date_stop .= ' 23:00:00';

          $dkj = DB::table('dkj')
              ->select(DB::raw('
                  users.department_info_id,
                  count(*) as dkj_sum,
                  users.dating_type
              '))
              ->join('users', 'users.id', '=', 'dkj.id_user')
              ->whereBetween('add_date', [$date_start, $date_stop])
              ->groupBy('users.department_info_id')
              ->groupBy('users.dating_type')
              ->get();

          $data = [
              'day_start' => $day_start,
              'day_stop' => $day_stop,
              'hour_reports' => $hour_reports,
              'dkj' => $dkj
          ];
          return $data;
    }

    //Wysyłanie maila raport tygodniowy odsłuchane rozmowy
    public function weekReportChecked() {
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
        $data = $this->weekReportCheckedData();
        $title = 'Raport tygodniowy odsłuchanych rozmów '.$date_start.' - '.$date_stop;
        $this->sendMailByVerona('weekReportChecked', $data, $title);
    }

    //wyświetlanie widoku raport tygodniowy odsłuchane rozmowy
    public function pageWeekReportChecked() {
        $data = $this->weekReportCheckedData();
        return view('reportpage.WeekReportChecked')
            ->with('day_start', $data['day_start'])
            ->with('day_stop', $data['day_stop'])
            ->with('dkj', $data['dkj'])
            ->with('hour_reports', $data['hour_reports']);
    }

    //Raport godzinnny pracownikow DKJ
    private function hourReportDkjEmployeeData() {
      $date = date("Y-m-d");
      $hour = date('H'). ":00:00";

      $dkj = DB::table('users')
      ->select(DB::raw('
          users.id,
          users.first_name,
          users.last_name,
          users.dating_type,
          count(*) as user_sum,
          sum(CASE WHEN dkj.dkj_status = 1 THEN 1 ELSE 0 END) as user_janek,
          sum(CASE WHEN dkj.dkj_status = 0 THEN 1 ELSE 0 END) as user_not_janek,
          sum(CASE WHEN dkj.dkj_status = 1 AND dkj.manager_status = 1 THEN 1 ELSE 0 END) as user_manager_disagre,
          sum(CASE WHEN dkj.deleted = 1 THEN 1 ELSE 0 END) as dkj_deleted
      '))
      ->join('dkj', 'users.id', '=', 'dkj.id_dkj')
      ->whereBetween('dkj.add_date', [$date.' 00:00:00', $date.' 23:00:00'])
      ->groupBy('dkj.id_dkj')
      ->get();

      $data = [
          'date' => $date,
          'dkj' => $dkj,
          'hour' => $hour
      ];
      return $data;

    }
    public function MailHourReportDkjEmployee(){
        $data = $this->hourReportDkjEmployeeData();
        $title = 'Raport godzinny pracownicy DKJ ' . date('Y-m-d H') . ':00:00';
        $this->sendMailByVerona('hourReportDkjEmployee', $data, $title);
    }

    public function pageHourReportDkjEmployee() {
        $data = $this->hourReportDkjEmployeeData();

        return view('reportpage.HourReportDkjEmployee')
            ->with('dkj', $data['dkj'])
            ->with('hour', $data['hour'])
            ->with('date', $data['date']);
    }



    // Dane do raportu godzinnego Czas na rekord
    private function hourReportTimeOnRecord()
    {
        $date = date('Y-m-d');
        $hour = date('H') . ':00:00'; //tutaj zmienic przy wydawaniu na produkcję na  date('H') - 1

        $reports = DB::table('pbx_time_record')->where('report_date', '=', $date)
            ->where('hour', $hour)
            ->get();

        $data = [
            'hour' => $hour,
            'date' => $date,
            'reports' => $reports
        ];

        return $data;
    }

// Mail do raportu godzinnego Czas na rekord
    public function MailhourReportTimeOnRecord() {
        $data = $this::hourReportTimeOnRecord();
        $title = 'Raport godzinny potwierdzenia połączenia ' . date('Y-m-d');
        $this->sendMailByVerona('hourReportTimeOnRecord', $data, $title);
    }
// Wyswietlenie Czas na rekord na stronie
    public function pageHourReportTimeOnRecord()
    {
        $data = $this::hourReportTimeOnRecord();
        return view('reportpage.HourReportTimeOnRecord')
            ->with('reports', $data['reports'])
            ->with('hour', $data['hour'])
            ->with('date', $data['date']);
    }


    /**
     * Wyswietlanie spływu rekrutacji dzienny
     */
    public function pageDayReportRecruitmentFlow(){
        $date_start = date('Y-m-d');
        $date_stop = date('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportFlowData($date_start,$date_stop)
        ];
        return view('reportpage.recruitmentReport.DayReportRecruitmentFlow')
            ->with('data',$data['data']);
    }

    /**
     * Mail spływu rekrutacji dzienny
     */

    public function MaildayReportRecruitmentFlow() {
        $date_start = date('Y-m-d', time() - 24 * 3600);
        $date_stop = date('Y-m-d', time() - 24 * 3600);
        $data = [
            'data' => RecruitmentStory::getReportFlowData($date_start,$date_stop)
        ];
        $title = 'Raport Dzienny Spływu Rekrutacji ' . $date_start;
        $this->sendMailByVerona('recruitmentMail.dayReportRecruitmentFlow', $data, $title);
    }

    /**
     * Wyswietlanie spływu rekrutacji Tygodniowego
     */
    public function pageWeekReportRecruitmentFlow(){
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
        $data = [
            'data' => RecruitmentStory::getReportFlowData($date_start,$date_stop)
        ];
        return view('reportpage.recruitmentReport.WeekReportRecruitmentFlow')
            ->with('data',$data['data']);
    }

    /**
     * Mail spływu rekrutacji Tygodniowego
     */

    public function MailweekReportRecruitmentFlow() {
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
        $data = [
            'data' => RecruitmentStory::getReportFlowData($date_start,$date_stop)
        ];
        $title = 'Raport Tygodniowy Spływu Rekrutacji '.$date_start.' - '.$date_stop;
        $this->sendMailByVerona('recruitmentMail.weekReportRecruitmentFlow', $data, $title);
    }


    /**
     * Wyswietlanie spływu rekrutacji Miesięczny
     */
    public function pageMonthReportRecruitmentFlow(){
        $month_ini = new DateTime("first day of this month");
        $month_end = new DateTime("last day of this month");
        $date_start =  $month_ini->format('Y-m-d');
        $date_stop  = $month_end->format('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportFlowData($date_start,$date_stop)
        ];
        return view('reportpage.recruitmentReport.MonthReportRecruitmentFlow')
            ->with('data',$data['data']);
    }

    /**
     * Mail spływu rekrutacji Miesięczny
     */

    public function MailmonthReportRecruitmentFlow() {
        $month_ini = new DateTime("first day of last month");
        $month_end = new DateTime("last day of last month");
        $date_start =  $month_ini->format('Y-m-d');
        $date_stop  = $month_end->format('Y-m-d');

        $data = [
            'data' => RecruitmentStory::getReportFlowData($date_start,$date_stop)
        ];
        $title = 'Miesięczny Raport Spływu Rekrutacji '.$date_start.' - '.$date_stop;
        $this->sendMailByVerona('recruitmentMail.monthReportRecruitmentFlow', $data, $title);
    }


    /**
     * Wyświetlanie przeprowadzonych szkoleń Dzienny
     */
    public function pageDayReportTrainingGroup(){
        $date_start = date('Y-m-d');
        $date_stop = date('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportTrainingData($date_start,$date_stop)
        ];
        return view('reportpage.recruitmentReport.DayReportRecruitmentTrainingGroup')
            ->with('data',$data['data']);
    }

    /**
     * Mail przeprowadzonych szkoleń
     */
    public function MaildayReportTrainingGroup() {
        $date_start = date('Y-m-d', time() - 24 * 3600);
        $date_stop = date('Y-m-d', time() - 24 * 3600);
        $data = [
            'data' => RecruitmentStory::getReportTrainingData($date_start,$date_stop)
        ];
        $title = 'Raport Dzienny Szkoleń '. $date_start;
        $this->sendMailByVerona('recruitmentMail.dayReportRecruitmentTrainingGroup', $data, $title);
    }

    /**
     * Wyświetlanie przeprowadzonych szkoleń Tygodniowy
     */
    public function pageWeekReportTrainingGroup(){
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
        $data = [
            'data' => RecruitmentStory::getReportTrainingData($date_start,$date_stop)
        ];
        return view('reportpage.recruitmentReport.WeekReportRecruitmentTrainingGroup')
            ->with('data',$data['data']);
    }

    /**
     * Mail przeprowadzonych szkoleń Tygodniowy
     */
    public function MailweekReportTrainingGroup() {
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
        $data = [
            'data' => RecruitmentStory::getReportTrainingData($date_start,$date_stop)
        ];
        $title = 'Tygodniowy Raport Szkoleń '.$date_start.' - '.$date_stop;
        $this->sendMailByVerona('recruitmentMail.weekReportRecruitmentTrainingGroup', $data, $title);
    }

    /**
     * Wyświetlanie przeprowadzonych szkoleń Miesięczny
     */
    public function pageMonthReportTrainingGroup(){
        $month_ini = new DateTime("first day of this month");
        $month_end = new DateTime("last day of this month");
        $date_start =  $month_ini->format('Y-m-d');
        $date_stop  = $month_end->format('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportTrainingData($date_start,$date_stop)
        ];
        return view('reportpage.recruitmentReport.MonthReportRecruitmentTrainingGroup')
            ->with('data',$data['data']);
    }

    /**
     * Mail przeprowadzonych szkoleń Miesięczny
     */
    public function MailmonthReportTrainingGroup() {
        $month_ini = new DateTime("first day of last month");
        $month_end = new DateTime("last day of last month");
        $date_start =  $month_ini->format('Y-m-d');
        $date_stop  = $month_end->format('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportTrainingData($date_start,$date_stop)
        ];
        $title = 'Miesięczny Raport Szkoleń '.$date_start.' - '.$date_stop;
        $this->sendMailByVerona('recruitmentMail.monthReportRecruitmentTrainingGroup', $data, $title);
    }

    /**
     *  Wyświetlanie ilości przeprowadzonych rozmów Dzienny
     */

    public function pageDayReportInterviews(){
        $date_start = date('Y-m-d');
        $date_stop = date('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportInterviewsData($date_start,$date_stop,0)
        ];
        return view('reportpage.recruitmentReport.DayReportInterviews')
            ->with('data',$data['data']);
    }

    /**
     *  Maila przeprowadzonych rozmów Dzienny
     */
    public function MaildayReportInterviews(){
        $date_start = date('Y-m-d', time() - 24 * 3600);
        $date_stop = date('Y-m-d', time() - 24 * 3600);
        $data = [
            'data' => RecruitmentStory::getReportInterviewsData($date_start,$date_stop,0)
        ];
        $title = 'Dzienny Raport Rozmów Rekrutacyjnych '. $date_start;
        $this->sendMailByVerona('recruitmentMail.dayReportInterviews', $data, $title);
    }

    /**
     *  Wyświetlanie ilości przeprowadzonych rozmów Tygodniowy
     */

    public function pageWeekReportInterviews(){
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
        $data = [
            'data' => RecruitmentStory::getReportInterviewsData($date_start,$date_stop,0)
        ];
        return view('reportpage.recruitmentReport.WeekReportInterviews')
            ->with('data',$data['data']);
    }

    /**
     *  Maila przeprowadzonych rozmów Tygodniowy
     */
    public function MailweekReportInterviews(){
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
        $data = [
            'data' => RecruitmentStory::getReportInterviewsData($date_start,$date_stop,0)
        ];
        $title = 'Tygodniowy Raport Rozmów Rekrutacyjnych '.$date_start.' - '.$date_stop;
        $this->sendMailByVerona('recruitmentMail.weekReportInterviews', $data, $title);
    }


    /**
     *  Wyświetlanie ilości przeprowadzonych rozmów Miesięczny
     */

    public function pageMonthReportInterviews(){
        $month_ini = new DateTime("first day of this month");
        $month_end = new DateTime("last day of this month");
        $date_start =  $month_ini->format('Y-m-d');
        $date_stop  = $month_end->format('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportInterviewsData($date_start,$date_stop,0)
        ];
        return view('reportpage.recruitmentReport.MonthReportInterviews')
            ->with('data',$data['data']);
    }

    /**
     *  Maila przeprowadzonych rozmów Miesięczny
     */
    public function MailmonthReportInterviews(){
        $month_ini = new DateTime("first day of last month");
        $month_end = new DateTime("last day of last month");
        $date_start =  $month_ini->format('Y-m-d');
        $date_stop  = $month_end->format('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportInterviewsData($date_start,$date_stop,0)
        ];
        $title = 'Miesięczny Raport Rozmów Rekrutacyjnych '.$date_start.' - '.$date_stop;
        $this->sendMailByVerona('recruitmentMail.monthReportInterviews', $data, $title);
    }

    /**
     * Raport zatrudnienie
     */
    public function pageDayReportHireCandidate(){
        $date_start = date('Y-m-d');
        $date_stop = date('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportNewAccountData($date_start,$date_stop,0)
        ];
        return view('reportpage.recruitmentReport.DayReportHireCandidate')
            ->with('data',$data['data']);
    }

    /**
     *  Maila przeprowadzonych rozmów Dzienny
     */
    public function MaildayReportHireCandidate(){
        $date_start = date('Y-m-d', time() - 24 * 3600);
        $date_stop = date('Y-m-d', time() - 24 * 3600);
        $data = [
            'data' => RecruitmentStory::getReportInterviewsData($date_start,$date_stop,0)
        ];
        $title = 'Dzienny Raport Rozmów Rekrutacyjnych '. $date_start;
        $this->sendMailByVerona('recruitmentMail.dayReportHireCandidate', $data, $title);
    }


    /**
     * Raport zatrudnienie Tygodniowy
     */
    public function pageWeekReportHireCandidate(){
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
        $data = [
            'data' => RecruitmentStory::getReportNewAccountData($date_start,$date_stop,0)
        ];
        return view('reportpage.recruitmentReport.WeekReportHireCandidate')
            ->with('data',$data['data']);
    }

    /**
     *  Maila przeprowadzonych rozmów Tygodniowy
     */
    public function MailweekReportHireCandidate(){
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
        $data = [
            'data' => RecruitmentStory::getReportInterviewsData($date_start,$date_stop,0)
        ];
        $title = 'Tygodniowy Raport Rozmów Rekrutacyjnych '.$date_start.' - '.$date_stop;
        $this->sendMailByVerona('recruitmentMail.weekReportHireCandidate', $data, $title);
    }


    /**
     * Raport zatrudnienie Miesięczny
     */
    public function pageMonthReportHireCandidate(){
        $month_ini = new DateTime("first day of this month");
        $month_end = new DateTime("last day of this month");
        $date_start =  $month_ini->format('Y-m-d');
        $date_stop  = $month_end->format('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportNewAccountData($date_start,$date_stop,0)
        ];
        return view('reportpage.recruitmentReport.MonthReportHireCandidate')
            ->with('data',$data['data']);
    }

    /**
     *  Maila przeprowadzonych rozmów Miesięczny
     */
    public function MailmonthReportHireCandidate(){
        $month_ini = new DateTime("first day of last month");
        $month_end = new DateTime("last day of last month");
        $date_start =  $month_ini->format('Y-m-d');
        $date_stop  = $month_end->format('Y-m-d');
        $data = [
            'data' => RecruitmentStory::getReportInterviewsData($date_start,$date_stop,0)
        ];
        $title = 'Miesięczny Raport Rozmów Rekrutacyjnych '.$date_start.' - '.$date_stop;
        $this->sendMailByVerona('recruitmentMail.monthReportHireCandidate', $data, $title);
    }

    /**
     * Raport oddziały
     */
    public function pageReportDepartmentsGet() {
        $first_day = date('Y-m') . '-01';
        $days_in_month = date('t', strtotime(date('m')));
        $last_day = date('Y-m-') . date('t', strtotime(date('m')));
        $month = date('m');
        $year = date('Y');

        $departments = Department_info::where('id_dep_type', '=', 2)->get();

        $directorsIds = Department_info::select('director_id')->where('director_id', '!=', null)->distinct()->get();
        $directors = User::whereIn('id', $directorsIds)->get();

        $dep_id = $departments->first()->id;

        $data = $this->getDepartmentsData($first_day, $last_day, $month, $year, $dep_id, $days_in_month);

        return view('reportpage.ReportDepartments')
            ->with([
                'date_start'        => $data['date_start'],
                'date_stop'         => $data['date_stop'],
                'month'             => $data['month'],
                'year'              => $data['year'],
                'send_month'        => date('m'),
                'total_days'        => intval($days_in_month),
                'hour_reports'      => $data['hour_reports'],
                'dep_info'          => $data['dep_info'],
                'schedule_data'     => $data['schedule_data'],
                'month_selected'    => date('m'),
                'departments'       => $departments,
                'dep_id'            => $dep_id,
                'months'            => $data['months'],
                'wiev_type'         => 'department',
                'directors'         => $directors
            ]);
    }

    public function pageReportDepartmentsPost(Request $request) {
        $first_day = date('Y-') . $request->month_selected . '-01';
        $days_in_month = date('t', strtotime($request->month_selected));
        $last_day = date('Y-m-') . date('t', strtotime($request->month_selected));
        $month = $request->month_selected;
        $year = date('Y');
        $directorsIds = Department_info::select('director_id')->where('director_id', '!=', null)->distinct()->get();
        $directors = User::whereIn('id', $directorsIds)->get();

        if (intval($request->selected_dep) < 100) {
            $dep_id = $request->selected_dep;

            $departments = Department_info::where('id_dep_type', '=', 2)->get();

            $data = $this->getDepartmentsData($first_day, $last_day, $month, $year, $dep_id, $days_in_month);

            return view('reportpage.ReportDepartments')
                ->with([
                    'date_start'        => $data['date_start'],
                    'date_stop'         => $data['date_stop'],
                    'month'             => $data['month'],
                    'year'              => $data['year'],
                    'send_month'        => $month,
                    'total_days'        => intval($days_in_month),
                    'hour_reports'      => $data['hour_reports'],
                    'dep_info'          => $data['dep_info'],
                    'schedule_data'     => $data['schedule_data'],
                    'month_selected'    => $request->month_selected,
                    'departments'       => $departments,
                    'dep_id'            => $dep_id,
                    'months'            => $data['months'],
                    'wiev_type'         => 'department',
                    'directors'         => $directors
                ]);
        } else {
            if (Auth::user()->id != 4796) {
                return "Raport w przygotowaniu";
            }
            $dirId = substr($request->selected_dep, 2);
            $director_departments = Department_info::select('id')->where('director_id', '=', $dirId)->get();

            $departments = Department_info::where('id_dep_type', '=', 2)->get();

            $data = $this->getMultiDepartmentData($first_day, $last_day, $month, $year, $director_departments->pluck('id')->toArray(), $days_in_month);

            return view('reportpage.ReportDepartments')
                ->with([
                    'date_start'        => $data['date_start'],
                    'date_stop'         => $data['date_stop'],
                    'month'             => $data['month'],
                    'year'              => $data['year'],
                    'send_month'        => $month,
                    'total_days'        => intval($days_in_month),
                    'hour_reports'      => $data['hour_reports'],
                    'dep_info'          => $data['dep_info'],
                    'schedule_data'     => $data['schedule_data'],
                    'month_selected'    => $request->month_selected,
                    'departments'       => $departments,
                    'dep_id'            => $request->selected_dep,
                    'months'            => $data['months'],
                    'wiev_type'         => 'director',
                    'directors'         => $directors
                ]);
        }
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
        //dd($newYanky);
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
                    $tempReport->hour_time_use += floatval($item->hour_time_use);
                    $tempReport->total_time += ($item->call_time > 0) ? ((100 * $item->hour_time_use) / $item->call_time) : 0 ;
                }
                $tempReport->average = ($tempReport->hour_time_use > 0) ? round($tempReport->success / $tempReport->hour_time_use, 2) : 0 ;
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
        $dep_info = Department_info::whereIn('id',[2,10,11,8])->get(); //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! TUTAJ ZMIENIC na cos związanego z tym dla kogo generowany jest raport

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

    /**
     * Pobranie danych do raportu oddziałów
     */
    private function getDepartmentsData($date_start, $date_stop, $month, $year, $dep_id, $days_in_month) {
        /**
         * Pobranie ostatnich ID z dnia
         */
        $reportIds = DB::table('hour_report')
            ->select(DB::raw('
                MAX(id) as id
            '))
            ->whereBetween('hour_report.report_date', [$date_start, $date_stop])
            ->groupBy('report_date')
            ->where('department_info_id', '=', $dep_id)
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
            ->where('users.department_info_id', '=', $dep_id)
            ->whereIn('users.user_type_id', [1,2])
            ->groupBy('date')
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
            ->where('department_info_id', '=', $dep_id)
            ->get();

        $yanky = DB::table('pbx_dkj_team')
            ->select(DB::raw('
                *
            '))
            ->whereBetween('report_date', [$date_start, $date_stop])
            ->whereIn('id', $jankyIds->pluck('id')->toArray())
            ->get();

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
                ->where('users.department_info_id', '=', $dep_id)
                ->whereIn('users.user_type_id', [1,2])
                ->where('week_num', $week)
                ->get();
        }

        $schedule_data_raw = collect($schedule_data_raw);

        $schedule_data = $schedule_data_raw->map(function($item) {
            return $item->first();
        });

        /**
         * Przypisanie danych do jednego obiektu
         */
        $newHourReports = $hourReports->map(function($item) use ($yanky, $acceptHours) {
            //Pobranie danych z jankami
            $toAdd = $yanky->where('report_date', '=', $item->report_date)->first();

            $item->count_all_check = ($toAdd != null) ? $toAdd->count_all_check : 0;
            $item->count_bad_check = ($toAdd != null) ? $toAdd->count_bad_check : 0;

            //pobranie danych z przepracowanymi godzinami
            $toAddHours = $acceptHours->where('date', '=', $item->report_date)->first();

            $item->time_sum_real_RBH = ($toAddHours != null) ? $toAddHours->time_sum : 0;

            return $item;
        });

        /**
         * Pobranie danych departamentu
         */
        $dep_info = Department_info::find($dep_id);

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
            'dep_info' =>$dep_info,
            'schedule_data' => $schedule_data,
            'months' => $months
        ];

        return $data;
    }

    /**
     * funkcja wyświetlająca email miesięczny raport oddziały
     */
    public function pageMailMonthReportDepartments() {
        $data = [];

        $prev_month = date('m', strtotime('-1 month', time()));
        $year = (intval($prev_month) == 12) ? intval(date('Y')) - 1 : date('Y') ;

        $first_day = $year . '-' . $prev_month . '-01';
        $days_in_month = date('t', strtotime($year . '-' . $prev_month));
        $last_day = date('Y-') . $prev_month . '-' . date('t', strtotime($year . '-' . $prev_month));
        $month = $prev_month;

        $departments = Department_info::where('id_dep_type', '=', 2)->get();

        foreach ($departments as $dep) {
            $data[] = $this->getDepartmentsData($first_day, $last_day, $month, $year, $dep->id, $days_in_month);
        }

        return view('reportpage.SummaryMonthReportDepartment')
            ->with([
                'data' => $data,
                'send_month' => date('m'),
                'total_days' => intval($days_in_month),
                'departments'=> $departments
            ]);
    }

    /**
     * Wyśwetlanie raportu miesięczengo trenerzy
     */
    public function pageMonthReportCoachGet () {
        $coaches = User::whereIn('user_type_id', [4,12])
            ->where('status_work', '=', 1)
            ->orderBy('last_name')
            ->get();

        return view('reportpage.MonthReportCoach')
            ->with([
                'coaches' => $coaches
            ]);
    }

    /**
     * Wyświetlanie raportu miesięcznego trenerzy
     */
    public function pageMonthReportCoachPost(Request $request) {
        $date_start = date('Y-m-') . '01';
        $date_stop = date('Y-m-t');

        $leader = User::find($request->coach_id);

        $ids = $leader->trainerConsultants->pluck('login_phone')->toArray();

        $max_from_day = DB::table('pbx_report_extension')
            ->select(DB::raw('
                MAX(id) as id
            '))
            ->whereBetween('report_date', [$date_start, $date_stop])
            ->whereIn('pbx_id', $ids)
            ->groupBy('report_date')
            ->groupBy('pbx_id')
            ->get();

        $pbx_data = Pbx_report_extension::whereBetween('report_date', [$date_start, $date_stop])
            ->whereIn('pbx_id', $ids)
            ->whereIn('id', $max_from_day->pluck('id')->toArray())
            ->get();

        $total_data = $pbx_data->groupBy('pbx_id');

        $days_in_month = intval(date('t', strtotime($date_start)));

        $terefere = $total_data->map(function($item, $key) use ($days_in_month, $date_start) {
            $user_sum = [];

            $consultant = User::where('login_phone', '=', $item->first()->pbx_id)
                ->get();

            for ($y = 1; $y <= 4; $y++) {
                $user_sum[$y]['average'] = 0;
                $user_sum[$y]['janky_proc'] = 0;
                $user_sum[$y]['count_calls'] = 0;
                $user_sum[$y]['success'] = 0;
                $user_sum[$y]['proc_call_success'] = 0;
                $user_sum[$y]['pause_time'] = 0;
                $user_sum[$y]['received_calls'] = 0;
                $user_sum[$y]['login_time'] = 0;
                $user_sum[$y]['proc_received_calls'] = 0;

                $user_sum[$y]['first_name'] = $consultant->first()->first_name;
                $user_sum[$y]['last_name'] = $consultant->first()->last_name;
                $user_sum[$y]['week_num'] = $y;
                $user_sum[$y]['total_week_yanky'] = 0;
                $user_sum[$y]['first_week_day'] = null;
                $user_sum[$y]['last_week_day'] = null;
            }
            $week_num = 1;
            $week_yanky = 0;
            $add_week_sum = true;
            $start_day = true;
            $miss_first_week = false;

            for ($i = 1; $i <= $days_in_month; $i++) {
                $i_fixed = ($i < 10) ? '0' . $i : $i ;
                $actual_loop_day = date('Y-m-', strtotime($date_start)) . $i_fixed;
                $week_day = date('N', strtotime($actual_loop_day));

                if ($user_sum[$week_num]['first_week_day'] == null) {
                    $user_sum[$week_num]['first_week_day'] = $actual_loop_day;
                }

                if ($item->where('report_date', '=', $actual_loop_day)->count() > 0) {
                    $report = $item->where('report_date', '=', $actual_loop_day)->first();

                    $work_time_array = explode(":", $report->login_time);
                    $work_time = round((($work_time_array[0] * 3600) + ($work_time_array[1] * 60) + $work_time_array[2]) / 3600, 2);

                    $user_sum[$week_num]['success'] += $report->success;
                    $user_sum[$week_num]['login_time'] += $work_time;
                    $user_sum[$week_num]['pause_time'] += $report->time_pause;
                    $user_sum[$week_num]['received_calls'] += $report->received_calls;

                    $week_yanky += ($report->success * ($report->dkj_proc / 100));
                }

                if ($week_day == 7 && $start_day == false && $miss_first_week == false) {
                    $add_week_sum = true;
                }

                if ($start_day == true && $week_day == 1) {
                    $add_week_sum = true;
                    $start_day = false;
                } else if ($start_day == true && $week_day != 1) {
                    $add_week_sum = false;
                    $miss_first_week = true;
                    $start_day = false;
                }

                if ($week_num == 4 && $week_day == 7 && $i < $days_in_month) {
                    $add_week_sum = false;
                }

                if (($week_day == 7 || $i == $days_in_month) &&  $add_week_sum == true && $miss_first_week == false) {
                    $user_sum[$week_num]['last_week_day'] = $actual_loop_day;

                    $user_sum[$week_num]['total_week_yanky'] = $week_yanky;
                    $user_sum[$week_num]['janky_proc'] = ($user_sum[$week_num]['success'] > 0) ? round(($week_yanky / $user_sum[$week_num]['success']) * 100, 2) : 0 ;
                    $user_sum[$week_num]['average'] = ($user_sum[$week_num]['login_time']) ? round(($user_sum[$week_num]['success'] / $user_sum[$week_num]['login_time']), 2) : 0 ;
                    $user_sum[$week_num]['proc_received_calls'] = ($user_sum[$week_num]['received_calls'] > 0) ? round(($user_sum[$week_num]['success'] / $user_sum[$week_num]['received_calls']) * 100 , 2) : 0 ;
                    $week_num++;
                    $week_yanky = 0;
                    $add_week_sum = true;
                }

                if  ($miss_first_week == true && $week_day == 7) {
                    $miss_first_week = false;
                }
                if ($week_num == 4 && $week_day == 7 && $i < $days_in_month) {
                    $add_week_sum = true;
                }

            }
            return $user_sum;
        });
        $coaches = User::whereIn('user_type_id', [4,12])->where('status_work', '=', 1)->get();

        return view('reportpage.MonthReportCoach')
            ->with([
                'coaches' => $coaches,
                'date_start' => $date_start,
                'date_stop' => $date_stop,
                'coachData' => $terefere,
                'leader' => $leader
            ]);
    }

    /**
     *  funkcja wysyłająca email miesięczny raport oddziały
     */
    public function MailMonthReportDepartments() {
        $data = [];

        $prev_month = date('m', strtotime('-1 month', time()));
        $year = (intval($prev_month) == 12) ? intval(date('Y')) - 1 : date('Y') ;

        $first_day = $year . '-' . $prev_month . '-01';
        $days_in_month = date('t', strtotime($year . '-' . $prev_month));
        $last_day = date('Y-m-') . date('t', strtotime($year . '-' . $prev_month));
        $month = $prev_month;

        $departments = Department_info::where('id_dep_type', '=', 2)->get();

        foreach ($departments as $dep) {
            $data[] = $this->getDepartmentsData($first_day, $last_day, $month, $year, $dep->id, $days_in_month);
        }

        $data = [
            'data' => $data,
            'send_month' => $month,
            'total_days' => intval($days_in_month),
            'departments'=> $departments
        ];


        $title = 'Miesięczny Raport Oddziały';
        $this->sendMailByVerona('summaryReportDepartment', $data, $title);
    }

    /*
     *  Strona z informacją o dezaktywowanych kontach
     */

    public function pageWeekReportUnuserdAccount(){

        $date_start = date('Y-m-d');
        $date_stop = date('Y-m-d');
        $data = $this::UnuserdAccount(1);
        return view('reportpage.accountReport.WeekReportUnuserdAccount')
            ->with('department_info',$data['departments'])
            ->with('users_warning',$data['users_warning'])
            ->with('users_disable',$data['users_disable']);
    }
    public function MailWeekReportUnuserdAccount(){

        $date_start = date('Y-m-d');
        $date_stop = date('Y-m-d');
        $data = $this::UnuserdAccount(1);
        $title = 'Tygodniowy Raport Nieaktywnych Kont Konsultantów '.$date_start.' - '.$date_stop;

        $this->sendMailByVerona('accountMail.weekReportUnuserdAccount', $data, $title);

    }

    public function UnuserdAccount($type){
        $today = date("Y-m-d");
        $date_warning = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_disable = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-14,date("Y")));


        $users_warning = User::
        wherebetween('last_login',[$date_disable,$date_warning])
                    ->whereIn('users.user_type_id',[1,2])
                    ->where('status_work','=',1)
                    ->get();

        $users_disable = DisableAccountInfo::
                    wherebetween('disable_date',[$date_warning,$today])
                        ->get();
        $departmnets = Department_info::all();
        $data = [
            'users_warning'     => $users_warning,
            'users_disable'     => $users_disable,
            'departments'       => $departmnets
        ];
        return $data;

    }

    /**
     * Metoda wysyłająca dziennie raport do kierwników oddziałów
     */
    public function MailDayDepartmentsReport() {
        $departments = Department_info::where('id_dep_type', '=', 2)->get();

        foreach($departments as $department) {
            $menager = User::whereIn('id', [$department->menager_id, 4796])->get();

            $date_start = date('Y-m-') . '01';
            $date_stop = date('Y-m-t');
            $month = date('m');
            $year = date('Y');
            $day_in_month = date('t', strtotime($month));

            $data = $this->getDepartmentsData($date_start, $date_stop, $month, $year, $department->id, $day_in_month);
            $data['total_days'] = $day_in_month;

            $this->sendMailByVerona('reportDepartments', $data, 'Raport oddziały', $menager);
        }
    }

    /**
     * Raport dzienny trenerzy
     */
    public function pageDayReportCoachGet() {
        $coaches = User::whereIn('user_type_id', [4, 12])
            ->orderBy('last_name')
            ->where('status_work', '=', 1)
            ->get();
        $year = date('Y');
        $month = date('m');
        $days_in_month = date('t', strtotime($month));

        return view('reportpage.dayReportCoaches')
            ->with([
                'coaches'   => $coaches,
                'year'      => $year,
                'month'     => $month,
                'days'      => $days_in_month,
                'coach_id'  => 0,
                'date_selected' => date('Y-m-d'),
                'hour_selected' => '09:00:00'
            ]);
    }

    /**
     * Raport dzienny trenerzy (po wyborze)
     */
    public function pageDayReportCoachPost(Request $request) {
        $coaches = User::whereIn('user_type_id', [4, 12])
            ->orderBy('last_name')
            ->where('status_work', '=', 1)
            ->get();
        $year = date('Y');
        $month = date('m');
        $days_in_month = date('t', strtotime($month));

        /*
         * !!!!!!!!!!!!!!!!!!!!! ZOSTAWIC DO WYSYłania maili
         */
//        $ids = DB::table('pbx_report_extension')
//            ->select(DB::raw('
//                MAX(pbx_report_extension.id) as id
//            '))
//            ->join('users', 'users.login_phone', 'pbx_report_extension.pbx_id')
//            ->where('users.coach_id', '=', $request->coach_id)
//            ->groupBy('users.id')
//            ->where('report_date', '=', $request->day_select)
//            ->get();

        $data = DB::table('pbx_report_extension')
            ->select(DB::raw('
                pbx_report_extension.*,
                users.last_name as user_last_name,
                users.first_name as user_first_name
            '))
            ->join('users', 'users.login_phone', 'pbx_report_extension.pbx_id')
            ->where('users.coach_id', '=', $request->coach_id)
            //->whereIn('pbx_report_extension.id', $ids->pluck('id')->toArray())
            ->where('report_date', '=', $request->day_select)
            ->where('report_hour', '=', $request->hour_select)
            ->get();

        $coach = User::find($request->coach_id);

        return view('reportpage.dayReportCoaches')
            ->with([
                'coaches'   => $coaches,
                'coach'     => $coach,
                'year'      => $year,
                'month'     => $month,
                'days'      => $days_in_month,
                'data'      => $data,
                'coach_id'  => $request->coach_id,
                'date_selected' => $request->day_select,
                'hour_selected' => $request->hour_select
            ]);
    }

    /**
     * Strona wyświetająca zborczy raport trenerrow (po ddziałach)
     */
    public function pageSummaryDayReportCoachesGet() {
        $department_info = Department_info::where('id_dep_type', '=', 2)->get();

        $month = date('m');
        $year = date('Y');
        $days_in_month = date('t', strtotime($month));

        return view('reportpage.DayReportSummaryCoaches')
            ->with([
                'department_info' => $department_info,
                'dep_id' => 2,
                'days' => $days_in_month,
                'month' => $month,
                'year' => $year,
                'date_selected' => date('Y-m-d')
            ]);
    }

    /**
     * Raport dzienny zbiorczy trenerzy (po oddziałach)
     */
    public function pageSummaryDayReportCoachesPost(Request $request) {
        $department_info = Department_info::where('id_dep_type', '=', 2)->get();

        $month = date('m');
        $year = date('Y');
        $days_in_month = date('t', strtotime($month));

        $department = Department_info::find($request->dep_id);

        $data = $this->getDayCoachStatistics($request->dep_id, $request->day_select);

        return view('reportpage.DayReportSummaryCoaches')
            ->with([
                'department_info'   => $department_info,
                'department'        => $department,
                'dep_id'            => $request->dep_id,
                'days'              => $days_in_month,
                'month'             => $month,
                'year'              => $year,
                'date_selected'     => $request->day_select,
                'coaches'           => $data['coaches'],
                'data'              => $data['data'],
                'report_date'       => $data['report_date']
            ]);
    }

    private function getDayCoachStatistics($dep_id, $report_date) {
        $department_info = Department_info::find($dep_id);
        $coaches = User::whereIn('user_type_id', [4,12])
            ->where('status_work', '=', 1)
            ->where('department_info_id', '=', $department_info->id)
            ->get();


        $data = [];

        foreach ($coaches as $coach) {
            $ids = DB::table('pbx_report_extension')
                ->select(DB::raw('
                    MAX(pbx_report_extension.id) as id
                '))
                ->join('users', 'users.login_phone', 'pbx_report_extension.pbx_id')
                ->where('users.coach_id', '=', $coach->id)
                ->groupBy('users.id')
                ->where('report_date', '=', $report_date)
                ->get();

            $coach_data = DB::table('pbx_report_extension')
                ->select(DB::raw('
                    pbx_report_extension.*,
                    users.last_name as user_last_name,
                    users.first_name as user_first_name
                '))
                ->join('users', 'users.login_phone', 'pbx_report_extension.pbx_id')
                ->where('users.coach_id', '=', $coach->id)
                ->whereIn('pbx_report_extension.id', $ids->pluck('id')->toArray())
                ->where('report_date', '=', $report_date)
                ->get();

            $data[] = $coach_data->merge($coach);
        }
        $total_data = [
            'coaches' => $coaches,
            'report_date' => $report_date,
            'data' => $data
        ];
        return $total_data;
    }

    /**
     * Wysyłanie maila dziennego (trenerzy) dla kierownikow
     */
    public function MailDayReportCoaches() {
        $departments = Department_info::where('id_dep_type', '=', 2)
            ->get();

        foreach ($departments as $department){
            $data_raw = $this->getDayCoachStatistics($department->id, date('Y-m-d'));

            $data = [
                'department'   => $department,
                'coaches'   => $data_raw['coaches'],
                'data'      => $data_raw['data'],
                'report_date' => $data_raw['report_date']
            ];

            $menager = User::whereIn('id', [$department->menager_id, 4796, 1364])->get();

            $this->sendMailByVerona('hourReportCoach', $data, 'Raport trenerzy', $menager);
        }
    }

    /******** Główna funkcja do wysyłania emaili*************/
    /*
    * $mail_type - jaki mail ma być wysłany - typ to nazwa ścieżki z web.php
    * $data - $dane przekazane z metody
    *
    */

    private function sendMailByVerona($mail_type, $data, $mail_title, $default_users = null) {
        if ($default_users !== null) {
            $email = [];
            $mail_type_pom = $mail_type;
            $mail_without_folder = explode(".",$mail_type);
            // podfoldery
            $mail_type = $mail_without_folder[count($mail_without_folder)-1];
            $mail_type2 = ucfirst($mail_type);
            $mail_type2 = 'page' . $mail_type2;
            $accepted_users = $default_users;
        } else {
            $email = [];
            $mail_type_pom = $mail_type;
            $mail_without_folder = explode(".",$mail_type);
            // podfoldery
            $mail_type = $mail_without_folder[count($mail_without_folder)-1];
            $mail_type2 = ucfirst($mail_type);
            $mail_type2 = 'page' . $mail_type2;
            $accepted_users = DB::table('users')
                ->select(DB::raw('
            users.first_name,
            users.last_name,
            users.username,
            users.email_off
            '))
                ->join('privilage_relation', 'privilage_relation.user_type_id', '=', 'users.user_type_id')
                ->join('links', 'privilage_relation.link_id', '=', 'links.id')
                ->where('links.link', '=', $mail_type2)
                ->where('users.status_work', '=', 1)
                ->where('users.id', '!=', 4592) // tutaj szczesna
                ->get();

            $szczesny = new User();
            $szczesny->username = 'bartosz.szczesny@veronaconsulting.pl';
            $szczesny->first_name = 'Bartosz';
            $szczesny->last_name = 'Szczęsny';
            $accepted_users->push($szczesny);
        }

//    $accepted_users = [
//        'cytawa.verona@gmail.com',
//        'jarzyna.verona@gmail.com'
//    ];

//        $mail_type = $mail_type_pom;
//     Mail::send('mail.' . $mail_type, $data, function($message) use ($accepted_users, $mail_title)
//     {
//        $message->from('noreply.verona@gmail.com', 'Verona Consulting');
//        foreach ($accepted_users as $key => $user) {
//          if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
//              $message->to($user)->subject($mail_title);
//          }
//        }
//     });

        $mail_type = $mail_type_pom;
      /* UWAGA !!! ODKOMENTOWANIE TEGO POWINNO ZACZĄC WYSYŁAĆ MAILE*/
       Mail::send('mail.' . $mail_type, $data, function($message) use ($accepted_users, $mail_title)
       {
           $message->from('noreply.verona@gmail.com', 'Verona Consulting');
           foreach($accepted_users as $user) {
            if (filter_var($user->username, FILTER_VALIDATE_EMAIL)) {
                $message->to($user->username, $user->first_name . ' ' . $user->last_name)->subject($mail_title);
             }
             if (filter_var($user->email_off, FILTER_VALIDATE_EMAIL)) {
                $message->to($user->email_off, $user->first_name . ' ' . $user->last_name)->subject($mail_title);
             }
           }
       });
    }
}
