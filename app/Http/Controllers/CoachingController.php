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
    /*
     * Status 0 - w toku
     * Status 1 - Coaching zaliczony
     * Status 2 - Coaching niezaliczony
     * Status 3 - Usunięty
     */


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
     * Wyświetlenie strony 'progress_table' dla Dyrektorów
     */
    public function progress_table_for_directorGET(){
        $coachingManagerList = $this::getCoachingManagerList(array(Auth::user()->id));
        return view('coaching.progress_table_for_director')
            ->with('coachingManagerList',$coachingManagerList);
    }

    /**
     * @return $this
     * Wyświetlenie strony 'progress_table' dla Kierowników
     */
    public function progress_table_for_managerGET(){
        //pobranie trenerów i średnie ich grup dla danego kierownika
        $consultant = $this::getCoachingCoachList();

        return view('coaching.progress_table_for_manager')
            ->with('consultant',$consultant);
    }

    /**
     * @return $this
     * Wyświetlenie strony 'progress_table' dla trenerów
     */
    public function progress_tableGET(){
        $consultant = $this::getCoachConsultant(array(Auth::user()->id));

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
                $inprogres = $inprogres->whereBetween('coaching_date',[$request->date_start .' 00:00:00',$request->date_stop.' 23:00:00']);
                    if($request->report_status == 1){
                        $inprogres = $inprogres->whereIn('coaching.status',[1,2]);
                    }else
                    {
                        $inprogres = $inprogres->where('coaching.status','=',$request->report_status);
                    }
                    // wybrany oddział do filtracji
                    $inprogres = $inprogres->where('manager.department_info_id','=',$request->department_info);
            }else{ // opcja z dyrektorem
                $dirId = substr($request->department_info, 2);
                $director_departments = Department_info::select('id')->where('director_id', '=', $dirId)->get();
                $inprogres = $inprogres->whereBetween('coaching_date',[$request->date_start .' 00:00:00',$request->date_stop.' 23:00:00']);
                if($request->report_status == 1){
                    $inprogres = $inprogres->whereIn('coaching.status',[1,2]);
                }else
                {
                    $inprogres = $inprogres->where('coaching.status','=',$request->report_status);
                }
                    // wybrany oddział do filtracji
                    $inprogres = $inprogres->whereIn('manager.department_info_id',$director_departments->pluck('id')->toArray());
            }
            // wybrany trener do filtracji
            if($request->coach_id != 'Wszyscy'){
                $inprogres = $inprogres->where('manager.id','=',$request->coach_id);
            }
            $inprogres = $inprogres->groupby('coaching.id');
        }else{
            $inprogres->whereBetween('coaching_date',[$request->date_start .' 00:00:00',$request->date_stop.' 23:00:00']);
                if($request->report_status == 1){
                    $inprogres = $inprogres->whereIn('coaching.status',[1,2]);
                }else
                {
                    $inprogres = $inprogres->where('coaching.status','=',$request->report_status);
                }
            $inprogres = $inprogres
                ->where('coaching.manager_id','=',Auth::user()->id)
                ->groupby('coaching.id');
        }


            return datatables($inprogres)->make(true);
    }

    /**
     * @return mixed
     * pobranie kierowników dla danego dyrektorów
     */
    public function getCoachingManagerList(){
        // Id dyrektora
        $director_id = 2; //Auth::user()->id
        // Pobranie oddziałów przypisanych do dyrektora
        $director_departments = Department_info::
            where('director_id','=',$director_id)
                ->get();

        //List Kierowników
        $all_manager_list = User::
        whereIn('department_info_id',$director_departments->pluck('id')->toarray())
            ->where('status_work','=',1)
            ->whereIn('user_type_id',[7,13])
            ->where('id','!=',$director_id)
            ->get();
        // Pobranie statystyk dla kierownika
        $department_statistics = $this::getDepartmentInfo(1,1,$director_departments->pluck('id')->toArray(),$all_manager_list);

        return $department_statistics;
    }

    public function getDepartmentInfo($date_start,$date_stop,$director_id,$all_manager_list){
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-3,date("Y")));
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
            ->whereIn('department_info.id',$director_id)
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
            ->whereIn('department_info.id',$director_id)
            ->where('users.user_type_id', '=', 1)
            ->groupBy('department_info.id')
            ->get();

        /**
         * Przypisanie danych do jednego obiektu
         * Dodanie RBH Do Kolekcji oraz imion kierowników
         */
        $collect_report = $reports->map(function($item) use ($work_hours,$all_manager_list) {
            //Pobranie danych z jankami
            $toAdd_rbh = $work_hours->where('id', '=', $item->id)->first();
            $item->realRBH = ($toAdd_rbh != null) ? $toAdd_rbh->realRBH : 0;
            $toAdd_manager = $all_manager_list->where('id','=',$item->menager_id)->first();
            $item->manager_name = ($toAdd_manager != null) ? $toAdd_manager->first_name.' '.$toAdd_manager->last_name : 0;
            return $item;
        });

        $data = [
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'collect_report' => $collect_report,
        ];
        return $data;
    }
    /**
     * @return mixed
     * pobranie trenerów dla danego kierownika
     */
    public function getCoachingCoachList(){
        // Pobranie oddziałów przypisanych do kierownika
        $manager_departments = Department_info::
                                where('menager_id','=',Auth::user()->id)
                                ->get();
        //List Treneró
        $all_coach_list = User::
                        whereIn('department_info_id',$manager_departments->pluck('id')->toarray())
                        ->where('status_work','=',1)
                        ->whereIn('user_type_id',[4,12])
                        ->get();
        $group_status = collect();
        dd($all_coach_list);
        foreach ($all_coach_list as $item){
            $group_status->push($this::getCoachConsultant(array($item->id)));
        }
        dd($group_status);
    }

    /**
     * @return mixed
     * pobranie konsultantów dla zalogowanego trenera
     */
    public function getCoachConsultant($coach_id){
        $all_users = DB::table('work_hours')
            ->select(
                DB::raw('               
                users.id as user_id'
                ))
            ->join('users','users.id','work_hours.id_user')
            ->whereIn('users.coach_id',$coach_id)
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
            $data->success = $succes;
            if($rbh == 0){
                $data->avg_consultant = 0;
                $data->rbh = 0;

            }else{
                $data->rbh = $rbh/3600;
                $data->avg_consultant = round($succes/($rbh/3600),2);
            }
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
