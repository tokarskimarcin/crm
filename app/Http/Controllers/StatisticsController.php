<?php

namespace App\Http\Controllers;

use App\HourReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class StatisticsController extends Controller
{
    public function hourReportGet()
    {
        $today = date('Y-m-d');
        $reports = HourReport::where('report_date','like',$today)
            ->where('department_info_id',Auth::user()->department_info_id)->get();
        return view('statistics.hourReport')
            ->with('reports',$reports);
    }
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

    public function hourReportTelemarketing() {
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


            Mail::send('mail.hourReportTelemarketing', $data, function($message)
            {
                //MAIL_DRIVER=mail w env
                // 'sendmail' => '/usr/sbin/sendmail -bs', na
               // -> mail.php  'sendmail' => "C:\xampp\sendmail\sendmail.exe\ -t",
                $message->from('jarzyna.verona@gmail.com');
                $message->to('jarzyna.verona@gmail.com', 'John Smith')->subject('Welcome!');
            });
        foreach ($reports as $report) {
            $report->is_send = 1;
            $report->save();
        }
        // return view('mail.hourReportTelemarketing')
        //     ->with('reports', $reports)
        //     ->with('hour', $hour)
        //     ->with('date', $date);
    }

    public function weekReportTelemarketing() {
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

          $data = [
              'date_start' => $date_start,
              'date_stop' => $date_stop,
              'reports' => $reports,
              'work_hours' => $work_hours,
              'sum_hours' => $sum_hours,
          ];

          Mail::send('mail.weekReportTelemarketing', $data, function($message)
          {
              //MAIL_DRIVER=mail w env
              // 'sendmail' => '/usr/sbin/sendmail -bs', na
             // -> mail.php  'sendmail' => "C:\xampp\sendmail\sendmail.exe\ -t",
              $message->from('jarzyna.verona@gmail.com');
              $message->to('jarzyna.verona@gmail.com', 'John Smith')->subject('Welcome!');
          });

        return view('mail.weekReportTelemarketing')
            ->with('work_hours', $work_hours)
            ->with('sum_hours', $sum_hours)
            ->with('reports', $reports)
            ->with('date_start', $date_start)
            ->with('date_stop', $date_stop);
    }
}
