<?php

namespace App\Http\Controllers;

use App\Coaching;
use App\CoachingDirector;
use App\Department_info;
use App\User;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CoachingController extends Controller
{
    /*
     * Status -1 - nierozliczony
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

    /**
     * @param Request $request
     * Zapisywanie nowego coaching'u Dla Kierownika oddziału
     * status -> 0 Nowy coaching
     * status -> 1 Edycja Coaching'u
     */
    public function saveCoachingDirector(Request $request){
        if($request->ajax()){
            if($request->status == 0)
                $new_coaching =  new CoachingDirector();
            else{
                $new_coaching = CoachingDirector::find($request->status);
            }
            $new_coaching->user_id              = $request->manager_id;
            $new_coaching->manager_id           = Auth::user()->id;
            $new_coaching->coaching_date        = $request->coaching_date;
            $new_coaching->subject              = $request->subject;
            $new_coaching->comment              = $request->coaching_comment;

            // Dane startowe coachingu
            $new_coaching->average_start        = $request->manager_actual_avg      == '' ? 0 : $request->manager_actual_avg;
            $new_coaching->janky_start          = $request->manager_actual_janky    == '' ? 0 : $request->manager_actual_janky;
            $new_coaching->rbh_start            = $request->manager_actual_rbh      == '' ? 0 : $request->manager_actual_rbh ;
            // Dane docelowe
            $new_coaching->average_goal         = $request->coaching_manager_avg_goal   == '' ? 0 : $request->coaching_manager_avg_goal;
            $new_coaching->janky_goal           = $request->coaching_manager_avg_janky  == '' ? 0 : $request->coaching_manager_avg_janky;
            $new_coaching->rbh_goal             = $request->coaching_manager_avg_rbh    == '' ? 0 : $request->coaching_manager_avg_rbh;
            //Typ coachingu
            $new_coaching->coaching_type        = $request->coaching_type;

            if($new_coaching->save()){
                return 0;
            }else{
                return -1;
            }
        }
    }

    /*
     * Datatables dla dyrektorów
     */
    public function datatableCoachingTableDirector(Request $request){
        $inprogres = $this::setInfoCoachingTableDirector($request);
        return Datatables::of($inprogres)->make(true);
    }
    //Pobieranie danych o kierownikach dla dyrektora (oddziałowy)
    public function setInfoCoachingTableDirector($request){
        $date_start = $request->date_start;
        $date_stop = $request->date_stop;
        // informacje o coachingu
        $coaching_director_inprogres = DB::table('coaching_director')
            ->select(DB::raw('coaching_director.*,
                            user.first_name as user_first_name,
                            user.last_name as user_last_name,
                            user.department_info_id as user_department_info,
                            manager.first_name as manager_first_name,
                            manager.last_name as manager_last_name,
                            round((select sum(time_to_sec(`accept_stop`)-time_to_sec(`accept_start`)) from work_hours 
                            join users on users.id = work_hours.id_user
                            where users.department_info_id = user.department_info_id
                            and users.user_type_id in (1,2)
                            and work_hours.date >= CONCAT(coaching_date," 00:00:00") )/3600,2) as actual_rbh,
                            department_info.commission_avg,
                            department_info.dep_aim
                            '))
            ->join('users as user','user.id','coaching_director.user_id')
            ->join('users as manager','manager.id','coaching_director.manager_id')
            ->join('department_info', 'department_info.id', '=', 'user.department_info_id');
            if($request->report_status == 0){
                $coaching_director_inprogres = $coaching_director_inprogres->where('status','=',$request->report_status);
            }else
                $coaching_director_inprogres = $coaching_director_inprogres ->whereIn('status',[1,2]);
           $coaching_director_inprogres = $coaching_director_inprogres->whereBetween('coaching_date',[$date_start .' 00:00:00',$date_stop.' 23:00:00'])
            ->groupBy('user.id','coaching_director.id')
            ->get();
        //informacje z raportów godzinnych
        $hour_report_inprogres = DB::table('hour_report')
            ->select(DB::raw('
                  SUM(call_time)/count(`call_time`) as sum_call_time,
                  SUM(hour_report.success) as sum_success,
                  sum(`hour_time_use`) as hour_time_use,
                  SUM(wear_base)/count(`call_time`) as avg_wear_base, 
                  report_date,                
                  hour_report.department_info_id'))
            ->whereIn('hour_report.id', function($query) use ($date_start){
                $query->select(DB::raw(
                    'MAX(hour_report.id)'
                ))
                    ->from('hour_report')
                    ->where('report_date','>=',$date_start)
                    ->where('call_time', '!=',0)
                    ->groupBy('department_info_id','report_date');
            })
            ->groupby('department_info_id','report_date')
            ->get();
        // inforamcje o jankach
        $janky_reports = DB::table('pbx_dkj_team')
            ->select(DB::raw(
                'sum(pbx_dkj_team.count_bad_check) as sum_bad,
                  sum(pbx_dkj_team.count_all_check) as sum_check,
                  department_info.id as janky_department_info,                  
                  report_date'))
            ->join('department_info', 'department_info.id', '=', 'pbx_dkj_team.department_info_id')
            ->whereIn('pbx_dkj_team.id', function($query) use($date_start){
                $query->select(DB::raw(
                    'MAX(pbx_dkj_team.id)'
                ))
                    ->from('pbx_dkj_team')
                    ->where('report_date','>=',$date_start)
                    ->groupBy('department_info_id','report_date');
            })
            ->groupBy('pbx_dkj_team.department_info_id','report_date')
            ->get();
        //mapowanie wyniku
        $coaching_director_inprogres = $coaching_director_inprogres->map(function ($iteam) use($hour_report_inprogres,$janky_reports){
            //Zerowanie rhb
            if($iteam->actual_rbh == null)
                $iteam->actual_rbh = 0;
            //Data coachingu
            $coaching_date = $iteam->coaching_date;
            //Aktualna średnia
            $sum_success = $hour_report_inprogres
                ->where('department_info_id','=',$iteam->user_department_info)
                ->where('report_date','>=',$coaching_date)
                ->sum('sum_success');
            $iteam->actual_avg = ($sum_success != null && $iteam->actual_rbh != 0) ? round($sum_success/$iteam->actual_rbh,2) : 0;

            $actual_janky = $janky_reports
                ->where('janky_department_info','=',$iteam->user_department_info)
                ->where('report_date','>=',$coaching_date);

            $sum_janky_check = $actual_janky->sum('sum_check');
            $sum_janky_bad = $actual_janky->sum('sum_bad');
            //Aktualna ilość janków
            $iteam->actual_janky = ($sum_janky_bad != 0 && $sum_janky_check != 0 && $sum_janky_check != null) ? round(($sum_janky_bad*100)/$sum_janky_check,2) : 0;
            //Próg RBH
            $iteam->rbh_min = $iteam->dep_aim / $iteam->commission_avg ;
            $iteam->rbh_min = $iteam->rbh_min * 3;
            return $iteam;
        });
        if(is_numeric($request->type) && $request->type!= 0){
            $coaching_director_inprogres = $coaching_director_inprogres->where('coaching_type','=',$request->type);
        }
        return $coaching_director_inprogres;
    }

    /*
     * Datatable dla trenerów
     */
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

    // Informacje o oddziale kierownika
    public function getDepartmentInfo($date_start,$date_stop,$director_id,$all_manager_list){
        // od 02.04 do 08.04 tydzień
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-21,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-15,date("Y")));

        //tu był zmiana z godzin na liczbę
        $work_hours = DB::table('work_hours')
            ->select(DB::raw(
                'sum(time_to_sec(accept_stop) - time_to_sec(accept_start))/3600 as realRBH,
                work_hours.date,
                department_info.*
            '))
            ->join('users', 'users.id', '=', 'work_hours.id_user')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->whereIn('department_info.id',$director_id)
            ->where('users.user_type_id', '=', 1)
            ->groupBy('department_info.id','work_hours.date')
            ->orderby('date','desc')
            ->get();


        $ready_data= collect(); // Gotowe dane

        foreach ($director_id as $item){
            $range = $work_hours->where('id','=',$item);
            $rbh = 0;
            $succes  = 0;
            $janky = 0;
            $date_start = '';
            $date_stop = '';
            $i = 0;
            if(is_object($range->first())){
                $dep_aim = $range->first()->dep_aim;
                $commission_avg = $range->first()->commission_avg;
                // Pobranie informacji o rbh oraz zakres datowy
               while($rbh < ($dep_aim/$commission_avg)*3){
                   if($i==0){
                       $date_stop = $range->first()->date;
                       $i++;
                   }
                   $date_start = $range->first()->date;
                   $rbh +=  $range->first()->realRBH;
                   $range = $range->slice(1);
               }
                //sumowanie janków
                $janky_reports = DB::table('pbx_dkj_team')
                    ->select(DB::raw(
                        'round(SUM(pbx_dkj_team.count_bad_check)*100/SUM(pbx_dkj_team.count_all_check),2) as actual_janky,
                        SUM(success) as sum_success, 
                     department_info.id'))
                    ->join('department_info', 'department_info.id', '=', 'pbx_dkj_team.department_info_id')
                    ->whereIn('pbx_dkj_team.id', function($query) use($date_start, $date_stop){
                        $query->select(DB::raw(
                            'MAX(pbx_dkj_team.id)'
                        ))
                            ->from('pbx_dkj_team')
                            ->whereBetween('report_date', [$date_start, $date_stop])
                            ->groupBy('department_info_id','report_date');
                    })
                    ->where('department_info.id',$item)
                    ->groupBy('pbx_dkj_team.department_info_id')
                    ->get();
                $janky = $janky_reports->first()->actual_janky;
                $succes = $janky_reports->first()->sum_success;
                $manager = DB::table('department_info')
                    ->select(DB::raw('users.id as manager_id,
                                users.first_name,
                                users.last_name,
                                department_info.id as department_info_id'))
                    ->join('users','users.id','department_info.menager_id')
                    ->where('department_info.id','=',$item)
                    ->first();
                $data = new \stdClass();
                $data->department_info_id = $item;
                $data->success = $succes;
                $data->date_start = $date_start;
                $data->date_stop = $date_stop;
                $data->avg_average = round($succes / $rbh,2);
                $data->realRBH = $rbh;
                $data->sum_janky_count = $janky;
                $data->menager_id = $manager->manager_id;
                $data->manager_name = $manager->first_name.' '.$manager->last_name;
                $ready_data->push($data);
            }
        }

//        /**
//         * Przypisanie danych do jednego obiektu
//         * Dodanie RBH Do Kolekcji oraz imion kierowników
//         */
//        $collect_report = $reports->map(function($item) use ($work_hours,$all_manager_list,$janky_reports,$date_stop) {
//            $item->report_date = $date_stop;
//            $toAdd_rbh = $work_hours->where('id', '=', $item->id)->first();
//            //Pobranie danych z jankami
//            $sum_janky_count = $janky_reports->where('id', '=', $item->id)->first();
//            $item->sum_janky_count = ($sum_janky_count != null) ? $sum_janky_count->actual_janky : 0;
//            //Wpisanie liczby rbh
//            $item->realRBH = ($toAdd_rbh != null) ? $toAdd_rbh->realRBH : 0;
//            //dodanie danych kierownika
//            $toAdd_manager = $all_manager_list->where('id','=',$item->menager_id)->first();
//            $item->manager_name = ($toAdd_manager != null) ? $toAdd_manager->first_name.' '.$toAdd_manager->last_name : 0;
//            //Wyliczenie odpowiedniej średniej
//            $item->avg_average =  round( $item->sum_success/$item->realRBH,2);
//            return $item;
//        });
        $data = [
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'collect_report' => $ready_data,
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
     * Akceptacja Coaching'u Dyrektor
     * @param Request $request
     * @return int
     */
    public function acceptCoachingDirector(Request $request){
        if($request->ajax()){
            $coaching               = CoachingDirector::find($request->coaching_id);
            $coaching->comment      = $request->coaching__comment;

            if($request->coaching_type == 'Średnia'){
                if(floatval($coaching->average_goal) > floatval($request->end_score)){
                    $coaching->status  = 2; // Coaching niezaliczony
                 }else{
                    $coaching->status  = 1; // Coaching zaliczony
                }
                $coaching->average_end = $request->end_score;// Ostateczny wybik
            }else if($request->coaching_type == 'Jakość'){
                if(floatval($coaching->janky_goal) < floatval($request->end_score)){
                    $coaching->status  = 2;// Coaching niezaliczony
                }else{
                    $coaching->status  = 1; // Coaching zaliczony
                }
                $coaching->janky_end = $request->end_score;// Ostateczny wybik
            }else{ // RGH
                if(floatval($coaching->rbh_goal) > floatval($request->end_score)){
                    $coaching->status  = 2;// Coaching niezaliczony
                }else{
                    $coaching->status  = 1; // Coaching zaliczony
                }
                $coaching->rbh_end = $request->end_score; // Ostateczny wybik
            }
            //Data zaakceptowania coachingu
            $coaching->coaching_date_accept = date('Y-m-d');
            //Ilość rgb przed zaakceptowaniem coachingu
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
    /**
     * Usunięcie Coaching'u dla kierownika
     * @param Request $request
     * @return int
     */
    public function deleteCoachingTableDirector(Request $request){
        if($request->ajax()){
            $coaching           = CoachingDirector::find($request->coaching_id);
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

    public function getCoachingDirector(Request $request){
        if($request->ajax()){
            $coaching = CoachingDirector::find($request->coaching_id);
            return $coaching;
        }else
            return 0;
    }






}
