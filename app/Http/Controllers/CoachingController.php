<?php

namespace App\Http\Controllers;

use App\Coaching;
use App\Department_info;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoachingController extends Controller
{



    public function progress_table_managerGET(){
        $departments = Department_info::whereIn('id_dep_type', [1,2])->get();
        $directorsIds = Department_info::select('director_id')->where('director_id', '!=', null)->distinct()->get();
        $directors = User::whereIn('id', $directorsIds)->get();
        $dep_id = Auth::user()->department_info_id;
        $coach = User::where('status_work','=','1')
                        ->where('department_info_id','=',$dep_id)
                        ->whereIn('user_type_id',[4,12])
                        ->get();
        return view('coaching.progress_manager_table')->with([
            'departments'   => $departments,
            'directorsIds'  => $directorsIds,
            'wiev_type'     => 'department',
            'directors'     => $directors,
            'dep_id'        => $dep_id,
            'coach'        => $coach,
        ]);
    }

    public function getcoach_list(Request $request){
        if($request->ajax()){
            if($request->department_info_id < 100){
                $coach = User::where('status_work','=','1')
                    ->where('department_info_id','=',$request->department_info_id)
                    ->whereIn('user_type_id',[4,12])
                    ->get();
            }else{
                $dirId = substr($request->department_info_id, 2);
                $director_departments = Department_info::select('id')->where('director_id', '=', $dirId)->get();
                $coach = User::where('status_work','=','1')
                    ->whereIn('department_info_id',$director_departments->pluck('id')->toArray())
                    ->whereIn('user_type_id',[4,12])
                    ->get();
            }
            return json_decode($coach);
        }else return 0;
    }
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
            ->join('users as manager','manager.id','coaching.manager_id');
        if($request->type == 'manager'){
            if($request->department_info < 100){
                $inprogres = $inprogres->whereBetween('coaching_date',[$request->date_start .' 00:00:00',$request->date_stop.' 23:00:00'])
                    ->where('coaching.status','=',$request->report_status)
                    // wybrany oddział do filtracji
                    ->where('manager.department_info_id','=',$request->department_info);
            }else{ // opcja z dyrektorem
                $dirId = substr($request->department_info, 2);
                $director_departments = Department_info::select('id')->where('director_id', '=', $dirId)->get();
                $inprogres = $inprogres->whereBetween('coaching_date',[$request->date_start .' 00:00:00',$request->date_stop.' 23:00:00'])
                    ->where('coaching.status','=',$request->report_status)
                    // wybrany oddział do filtracji
                    ->whereIn('manager.department_info_id',$director_departments->pluck('id')->toArray());
            }
            // wybrany trener do filtracji
            if($request->coach_id != 'Wszyscy'){
                $inprogres = $inprogres->where('manager.id','=',$request->coach_id);
            }
            $inprogres = $inprogres->groupby('coaching.id');
        }else{
            $inprogres->whereBetween('coaching_date',[$request->date_start .' 00:00:00',$request->date_stop.' 23:00:00'])
                ->where('coaching.status','=',$request->report_status)
                ->where('coaching.manager_id','=',Auth::user()->id)
                ->groupby('coaching.id');
        }


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
            ->where('users.coach_id','=',Auth::user()->id)
            ->where('users.status_work','=',1)
            ->groupby('users.id')
            ->get();


        $ready_data=[];
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

            if(floatval($coaching->average_goal) > floatval($request->avrage_end)) // Coaching niezaliczony
                $coaching->status  = 2;
            else
                $coaching->status  = 1;    // Coaching zaliczony
            $coaching->coaching_date_accept = date('Y-m-d');
            $coaching->avrage_end = $request->avrage_end;
            $coaching->rbh_end = $request->rbh_end;
            $coaching->save();
            return $coaching->average_goal;
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
