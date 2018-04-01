<?php

namespace App\Http\Controllers;

use App\Coaching;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoachingController extends Controller
{
    /**
     * @return $this
     * Wyświetlenie strony 'progress_table'
     */
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
            if($request->status == 0)
                $new_coaching =  new Coaching();
            else{
                $new_coaching = Coaching::find($request->status);
                $new_coaching->coaching_date_accept = date('Y-m-d');
            }


            $new_coaching->consultant_id        = $request->consultant_id;
            $new_coaching->manager_id           = Auth::user()->id;
            $new_coaching->coaching_date        = $request->coaching_date;
            $new_coaching->subject              = $request->subject;
            $new_coaching->comment              = $request->coaching_comment;
            $new_coaching->coaching_actual_avg  = $request->coaching_actual_avg;
            $new_coaching->average_goal         = $request->coaching_goal;
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
                        round(sum(case when work_hours.date >= coaching.coaching_date then work_hours.success else 0 end) / (sum(case when work_hours.date >= coaching.coaching_date then time_to_sec(`work_hours`.`accept_stop`) - time_to_sec(`work_hours`.`accept_start`) else 0 end)/3600),2) avg_consultant,
                        sum(case when work_hours.date >= coaching.coaching_date then time_to_sec(`accept_stop`) - time_to_sec(`accept_start`) else 0 end ) as couaching_time,
                        (select sum(time_to_sec(`accept_stop`)-time_to_sec(`accept_start`)) from work_hours where work_hours.id_user = `coaching`.`consultant_id`
                        and work_hours.date >= CONCAT(coaching_date," 00:00:00") ) as couching_rbh,
                        consultant.first_name as consultant_first_name,
                        consultant.last_name as consultant_last_name,
                        manager.first_name as manager_first_name,
                        manager.last_name as manager_last_name'))
            ->join('users as consultant','consultant.id','coaching.consultant_id')
            ->join('work_hours','work_hours.id_user','coaching.consultant_id')
            ->join('users as manager','manager.id','coaching.manager_id')
            ->whereBetween('coaching_date',[$request->date_start .' 00:00:00',$request->date_stop.' 23:00:00'])
            ->where('coaching.status','=',$request->report_status)
            ->groupby('coaching.id');
            return datatables($inprogres)->make(true);
    }

    /**
     * @return mixed
     * pobranie konsultantów dla zalogowanego trenera
     */
    public function getCoachConsultant(){

        $all_users = DB::table('work_hours')
            ->select(
                DB::raw('               
                users.id as user_id'
                ))
            ->join('users','users.id','work_hours.id_user')
            ->where('users.coach_id','=',6052)
            ->where('users.status_work','=',1)
            ->groupby('users.id')
            ->get();

        $data=[];
        foreach ($all_users as $user_form_all){
            $user = User::find($user_form_all->user_id);
            $item = $user->work_hours->sortbyDESC('date');
            $succes  = 0;
            $rbh = 0;
            while($rbh < 64800 && is_object($item->first())){
                $work_hours = $item->first();
                // sumowanie zgód
                $succes += $work_hours->success;
                // zamian godzin na sekundy
                $time_stop = $work_hours->accept_stop;
                $timeInSeconds_stop = strtotime($time_stop) - strtotime('TODAY');
                $time_start = $work_hours->accept_start;
                $timeInSeconds_start = strtotime($time_start) - strtotime('TODAY');
                $time_diff = $timeInSeconds_stop - $timeInSeconds_start;
                //sumowanie rbh
                $rbh += $time_diff;
                //zmniejszenie kolekcji
                $item = $item->slice(1);
            }
            $data = new \stdClass();
            $data->id = $user->id;
            $data->first_name = $user->first_name;
            $data->last_name = $user->last_name;
            if($rbh == 0){
                $data->avg_consultant = 0;
            }else
                $data->avg_consultant = round($succes/($rbh/3600),2);
            $ready_data[] = $data;
        }
        return collect($ready_data);
    }


    /**
     * Akceptacja Coaching'u
     * @param Request $request
     * @return int
     */
    public function acceptCoaching(Request $request){
        if($request->ajax()){
            $coaching               = Coaching::find($request->coaching_id);
            $coaching->comment      = $request->coaching__comment;
            $coaching->status       = 1;
            $coaching->save();
            return 1;
        }else
            return 0;
    }

    /**
     * Usunięcie Coaching'u
     * @param Request $request
     * @return int
     */
    public function deleteCoaching(Request $request){
        if($request->ajax()){
            $coaching           = Coaching::find($request->coaching_id);
            $coaching->status   = 3;
            $coaching->save();
            return 1;
        }else
            return 0;
    }

    public function getCoaching(Request $request){
        if($request->ajax()){
            $coaching = Coaching::find($request->coaching_id);
            return $coaching;
        }else
            return 0;
    }
}
