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
                        consultant.first_name as consultant_first_name,
                        consultant.last_name as consultant_last_name,
                        manager.first_name as manager_first_name,
                        manager.last_name as manager_last_name'))
                        ->join('users as consultant','consultant.id','coaching.consultant_id')
                        ->join('users as manager','manager.id','coaching.manager_id')
                        ->whereBetween('coaching_date',[$request->date_start .' 00:00:00',$request->date_stop.' 23:00:00'])
                        ->where('status','=',$request->report_status);
            return datatables($inprogres)->make(true);
    }

    /**
     * @return mixed
     * pobranie konsultantów dla zalogowanego trenera
     */
    public function getCoachConsultant(){
        return User::where('coach_id','=',Auth::user()->id)
            ->where('status_work','=',1)
            ->get();
    }
}
