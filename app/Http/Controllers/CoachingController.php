<?php

namespace App\Http\Controllers;

use App\Coaching;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoachingController extends Controller
{
    public function progress_tableGET(){

        $consultant = $this::getCoachConsultant();


        $inprogres = DB::table('coaching')
            ->select(DB::raw('coaching.*,
                        ROUND(sum(work_hours.id)/ sum(time_to_sec(`accept_stop`-`accept_start`)),2) as avg_consultant,
                        sum(time_to_sec(`accept_stop`-`accept_start`)) as couaching_time,
                        
                         (select sum(time_to_sec(`accept_stop`)-time_to_sec(`accept_start`)) from work_hours where work_hours.id_user = `coaching`.`consultant_id` 
                        and work_hours.date BETWEEN "2018-03-26 23:00:00" and "2018-03-30 23:00:00") as couching_rbh,
                        
                        consultant.first_name as consultant_first_name,
                        consultant.last_name as consultant_last_name,
                        manager.first_name as manager_first_name,                        
                        manager.last_name as manager_last_name'))
            ->join('users as consultant','consultant.id','coaching.consultant_id')
            ->join('work_hours','work_hours.id','coaching.consultant_id')
            ->join('users as manager','manager.id','coaching.manager_id')
            ->whereBetween('coaching_date',['2018-03-26 00:00:00','2018-03-27 23:00:00'])
            ->groupby('coaching.id')
            ->get();
        //return datatables($inprogres)->make(true);




        return view('coaching.progress_table')
                ->with('consultant',$consultant);
    }

    /**
     * @param Request $request
     * Zapisywanie nowego coaching'u
     */
    public function saveCoaching(Request $request){
        if($request->ajax()){
            $new_coaching = new Coaching();
            $new_coaching->consultant_id = $request->consultant_id;
            $new_coaching->manager_id = Auth::user()->id;
            $new_coaching->coaching_date = $request->coaching_date;
            $new_coaching->subject = $request->subject;
            $new_coaching->comment = $request->coaching_comment;
            $new_coaching->average_goal_min = $request->coaching_actual_avg;
            $new_coaching->average_goal_min = $request->coaching_goal_min;
            $new_coaching->average_goal_max = $request->coaching_goal_max;

            if($new_coaching->save()){
                return 1;
            }else{
                return 0;
            }

        }
    }


    public function datatableCoachingTable(Request $request){
        $inprogres = DB::table('coaching')
            ->select(DB::raw('coaching.*,
                        ROUND(sum(work_hours.id)/ sum(time_to_sec(`accept_stop`-`accept_start`)),2) as avg_consultant,
                        sum(time_to_sec(`accept_stop`-`accept_start`)) as couaching_time,                        
                         (select sum(time_to_sec(`accept_stop`)-time_to_sec(`accept_start`)) from work_hours where work_hours.id_user = `coaching`.`consultant_id` 
                        and work_hours.date BETWEEN CONCAT(coaching_date," 00:00:00") and NOW()) as couching_rbh,                        
                        consultant.first_name as consultant_first_name,
                        consultant.last_name as consultant_last_name,
                        manager.first_name as manager_first_name,                        
                        manager.last_name as manager_last_name'))
            ->join('users as consultant','consultant.id','coaching.consultant_id')
            ->join('work_hours','work_hours.id','coaching.consultant_id')
            ->join('users as manager','manager.id','coaching.manager_id')
            ->whereBetween('coaching_date',[$request->date_start .' 00:00:00',$request->date_stop.' 23:00:00'])
            ->where('coaching.status','=',$request->report_status)
            ->where('coaching.status','=',$request->report_status)
            ->groupby('coaching.id');
            return datatables($inprogres)->make(true);
    }

    /**
     * @return mixed
     * pobranie konsultantów dla zalogowanego trenera
     */
    public function getCoachConsultant(){
        $date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-7, date("Y")));

        return DB::table('work_hours')
            ->select(
                DB::raw('
                ROUND(sum(work_hours.id)/
                sum(time_to_sec(`accept_stop`-`accept_start`)),2) as avg_consultant,
                users.id,
                users.first_name,
                users.last_name'
                ))
            ->join('users','users.id','work_hours.id_user')
            ->whereBetween('date',[$date .' 00:00:00',date('Y-m-d').' 23:00:00'])
            ->where('users.coach_id','=',6052)
            ->where('users.status_work','=',1)
            ->groupby('users.id')
            ->get();
    }
}
