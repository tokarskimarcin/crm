<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\User;
use App\Department_info;
use App\Department_types;
use App\Departments;
use App\UserTypes;
use App\Agencies;
use App\Schedule;
use App\PenaltyBonus;
use App\Work_hour;
use App\Dkj;
use App\Links;
use App\LinkGroups;
use App\PrivilageRelation;
use App\SummaryPayment;
use App\EquipmentTypes;
use App\Equipments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\MultipleDepartments;
use App\ActivityRecorder;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Mail;
use App\Mail\RaportMail;
use Mail;
use Request;
use App\HourReport;

class TestORM extends Controller
{
    public function test() {


        $today = date('Y-m-d');

        $hour = '09:00:00';

        $dkj = DB::table('dkj') // tutaj badania
              ->select(DB::raw(
                  'SUM(CASE WHEN (users.dating_type = 0) THEN 1 ELSE 0 END) as sum ,
                  SUM(CASE WHEN `dkj_status` = 0 AND (users.dating_type = 0) THEN 1 ELSE 0 END) as good ,
                  SUM(CASE WHEN `dkj_status` = 1 AND (users.dating_type = 0) THEN 1 ELSE 0 END) as bad,
                  dkj.department_info_id,
                  department_info.*,
                  departments.name as dep_name,
                  department_type.name as dep_type,
                  users.*
                     '))
              ->whereBetween('dkj.add_date', [$today . ' 08:00:00', $today . ' 13:00:00'])
              ->join('department_info', 'department_info.id', '=', 'dkj.department_info_id')
              ->join('departments', 'department_info.id_dep', '=', 'departments.id')
              ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
              ->join('users', 'dkj.id_user', '=', 'users.id')
              ->groupBy('dkj.department_info_id')
              ->get();

          $dkj_select = DB::table('dkj') // tutaj wysyłka
                ->select(DB::raw(
                    'SUM(CASE WHEN (users.dating_type = 1) THEN 1 ELSE 0 END) as sum ,
                    SUM(CASE WHEN `dkj_status` = 0 AND (users.dating_type = 1) THEN 1 ELSE 0 END) as good ,
                    SUM(CASE WHEN `dkj_status` = 1 AND (users.dating_type = 1) THEN 1 ELSE 0 END) as bad,
                    dkj.department_info_id,
                    department_info.*,
                    departments.name as dep_name,
                    department_type.name as dep_type,
                    users.*
                       '))
                ->whereBetween('dkj.add_date', [$today . ' 08:00:00', $today . ' 13:00:00'])
                ->join('department_info', 'department_info.id', '=', 'dkj.department_info_id')
                ->join('departments', 'department_info.id_dep', '=', 'departments.id')
                ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
                ->join('users', 'dkj.id_user', '=', 'users.id')
                ->groupBy('dkj.department_info_id')
                ->get();

                        //
                        // $data = [
                        //     'dkj' => $dkj,
                        //     'dkj_select' => $dkj_select,
                        //     'hour' => $hour
                        // ];
                        //
                        //
                        // Mail::send('mail.raport', $data, function($message)
                        // {
                        //     //MAIL_DRIVER=mail w env
                        //     // 'sendmail' => '/usr/sbin/sendmail -bs', na
                        //    // -> mail.php  'sendmail' => "C:\xampp\sendmail\sendmail.exe\ -t",
                        //     $message->from('jarzyna.verona@gmail.com');
                        //     $message->to('jarzyna.verona@gmail.com', 'John Smith')->subject('Welcome!');
                        // });

        return view('mail.raport')
          ->with('dkj', $dkj)
          ->with('dkj_select', $dkj_select)
          ->with('hour', $hour);

    }

}
