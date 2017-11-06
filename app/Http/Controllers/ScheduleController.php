<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function setScheduleGet()
    {
        return view('schedule.setSchedule');
    }
    public function setSchedulePost(Request $request)
    {
        $number_of_week = $request->show_schedule;
        $request->session()->put('number_of_week', $number_of_week);
        return view('schedule.setSchedule')
            ->with('number_of_week',$number_of_week);
    }

    public function datatableShowUserSchedule(Request $request)
    {
        $number_week =  $request->session()->get('number_of_week');
        $year = $request->year;
        $request->session()->put('year', $year);
        $query = DB::table('users')
            ->leftjoin("schedule", function ($join) use ($number_week,$year) {
                $join->on("schedule.id_user", "=", "users.id")
                    ->where("schedule.week_num", "=", $number_week)
                    ->where("schedule.year", "=", $year);
            })
            ->select(DB::raw(
                'schedule.*,               
                users.id as id_user,
                users.first_name as user_first_name,
                users.last_name as user_last_name,
                users.phone as user_phone             
                '))
            ->where('users.department_info_id',Auth::user()->department_info_id);
        return datatables($query)->make(true);
    }

    public function saveSchedule(Request $request)
    {
        $start_hours = $request->start_hours;
        $stop_hours = $request->stop_hours;
        $reasons = $request->reasons;
        $id_user = $request->id_user;
        $number_week =  $request->session()->get('number_of_week');
        $year = $request->session()->get('year');
        $schedule_id = $request->schedule_id;

        if($schedule_id == 'null')
        {

        }else{

        }
    }











    //*************Custom Function*************
    private function getStartAndEndDate($week, $year) {
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
    }

}
