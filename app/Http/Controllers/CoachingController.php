<?php

namespace App\Http\Controllers;

use App\ActivityRecorder;
use App\CoachDirectorChange;
use App\CoachDirectorHistory;
use App\Coaching;
use App\CoachingDirector;
use App\Department_info;
use App\Department_types;
use App\User;
use Exception;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
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

    public function progress_table_managerGET()
    {
        $departments = Department_info::whereIn('id_dep_type', [1, 2])->get();
        $directorsIds = Department_info::select('director_id')->where('director_id', '!=', null)->distinct()->get();
        $directors = User::whereIn('id', $directorsIds)->get();
        $dep_id = Auth::user()->department_info_id;
        $coach = User::where('status_work', '=', '1')
            ->where('department_info_id', '=', $dep_id)
            ->whereIn('user_type_id', [4, 12, 20])
            ->get();
        return view('coaching.progress_manager_table')->with([
            'departments' => $departments,
            'directorsIds' => $directorsIds,
            'wiev_type' => 'department',
            'directors' => $directors,
            'dep_id' => $dep_id,
            'coach' => $coach,
        ]);
    }

    public function progress_table_managerAllGET()
    {
        $departments = Department_info::whereIn('id_dep_type', [1, 2])->get();


        $directorsIds = Department_info::select('director_id')->where('director_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
        $directorsHRIds = Department_info::select('director_hr_id')->where('director_hr_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
        $regionalManagersIds = Department_info::select('regionalManager_id')->where('regionalManager_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();

        $directors = User::whereIn('id', $directorsIds)->get();
        $directorsHR = User::whereIn('id', $directorsHRIds)->get();
        $regionalManagers = User::whereIn('id', $regionalManagersIds)->get();

        $dep_info_id = Auth::user()->department_info_id;


        $coach = User::where('department_info_id', '=', $dep_info_id)
            ->whereIn('user_type_id', [4, 12, 20])
            ->get();

//        $coach = User::where('status_work', '=', '1')
//            ->where('department_info_id', '=', $dep_id)
//            ->whereIn('user_type_id', [4, 12, 20])
//            ->get();

        return view('coaching.progress_manager_table_for_all')->with([
            'departments' => $departments,
            'directorsIds' => $directorsIds,
            'wiev_type' => 'department',
            'directors' => $directors,
            'directorsHR' => $directorsHR,
            'regionalManagers' => $regionalManagers,
            'dep_info_id' => $dep_info_id,
            'coach' => $coach
        ]);
    }

    public function progress_adminGET()
    {
        $departments = Department_info::whereIn('id_dep_type', [1, 2])->get();

        $directorsIds = Department_info::select('director_id')->where('director_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
        $directorsHRIds = Department_info::select('director_hr_id')->where('director_hr_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
        $regionalManagersIds = Department_info::select('regionalManager_id')->where('regionalManager_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();

        $directors = User::whereIn('id', $directorsIds)->get();
        $directorsHR = User::whereIn('id', $directorsHRIds)->get();
        $regionalManagers = User::whereIn('id', $regionalManagersIds)->get();


        $dep_id = Auth::user()->department_info_id;
        $coach = User::where('status_work', '=', '1')
            ->where('department_info_id', '=', $dep_id)
            ->whereIn('user_type_id', [4, 12, 20])
            ->get();
        return view('coaching.progress_table_admin')->with([
            'directorsHR' => $directorsHR,
            'regionalManagers' => $regionalManagers,
            'departments' => $departments,
            'directorsIds' => $directorsIds,
            'wiev_type' => 'department',
            'directors' => $directors,
            'dep_id' => $dep_id,
            'coach' => $coach,
        ]);
    }


    public function getcoach_list(Request $request)
    {
        if ($request->ajax()) {
            if ($request->coaching_level == 1) {
                if ($request->department_info_id < 100) {
                    $coach = User::where('status_work', '=', '1')
                        ->where('department_info_id', '=', $request->department_info_id)
                        ->whereIn('user_type_id', [4, 12, 20])
                        ->get();
                } else {
                    $dirId = substr($request->department_info_id, 2);
                    $typeUser = User::find($dirId);
                    if($typeUser->user_type_id == 14)
                        $userTypeId = [5];
                    else
                        $userTypeId = [4,12];
                    $director_departments =
                        Department_info::where(function($querry) use ($dirId) {
                            $querry->orwhere('director_id', '=', $dirId)
                                ->orwhere('regionalManager_id', '=', $dirId)
                                ->orwhere('director_hr_id', '=', $dirId);
                        })->get();
                    $coach = User::where('status_work', '=', '1')
                        ->whereIn('department_info_id', $director_departments->pluck('id')->toArray())
                        ->whereIn('user_type_id',$userTypeId )
                        ->get();
                }
            } else if ($request->coaching_level == 2) {
                $managers_id = Department_info::all()->pluck('menager_id')->toArray();
                $coach = User::where('status_work', '=', '1')
                    ->whereIn('id', $managers_id)
                    ->get();

            } else {
                $directors = Department_info::all()->pluck('director_id')->toArray();
                $directorsHR = Department_info::all()->pluck('director_hr_id')->toArray();
                foreach($directorsHR as $item){
                    array_push($directors,$item);
                }
                $coach = User::where('status_work', '=', '1')
                    ->whereIn('id', $directors)
                    ->get();
            }
            return json_decode($coach);
        } else return 0;
    }

    /**
     * @return $this
     * Wyświetlenie strony 'progress_table' dla Trenerów
     */
    public function progress_table_for_coachGET()
    {
        $coachingConsultantList = $this::getCoachConsultant(array(Auth::user()->id));
        $loggedUser = Auth::user()->department_info->id_dep_type;
        $data = [
            'collect_report' => $coachingConsultantList,
            'user_department_type' => $loggedUser
        ];
        $departmentInfo = Department_info::where('id','=',Auth::user()->department_info_id)->first();

        return view('coaching.progress_table_for_coach')
            ->with('coachingManagerList', $data)
            ->with('userDepartmentInfo',$departmentInfo);
    }

    /**
     * @return $this
     * Wyświetlenie strony 'progress_table' dla Dyrektorów
     */
    public function progress_table_for_directorGET()
    {
        $userId = Auth::user()->id;
        $isHr = false;
        $isDirectorHr = Department_info::where('director_hr_id','=',$userId)->get();
        if(!$isDirectorHr->isEmpty()){
            $isHr = true;
            $coachingManagerList = $this::getCoachingManagerListHR($userId,$isHr);
            $loggedUser = Auth::user()->department_info->id_dep_type;
        }else{
            $coachingManagerList = $this::getCoachingManagerList($userId,$isHr);
            $loggedUser = Auth::user()->department_info->id_dep_type;
        }
        return view('coaching.progress_table_for_director')
            ->with('coachingManagerList', $coachingManagerList)
            ->with('user_department_type', $loggedUser)
            ->with('isHr',$isHr);
    }

    /**
     * @return $this
     * Wyświetlenie strony 'progress_table' dla Kierowników
     */
    public function progress_table_for_managerGET()
    {
        //pobranie trenerów i średnie ich grup dla danego kierownika
        $coachingManagerList = $this::getCoachingCoachList(array(Auth::user()->id));
        $loggedUser = Auth::user()->department_info->id_dep_type;
        return view('coaching.progress_table_for_manager')
            ->with('coachingManagerList', $coachingManagerList)
            ->with('user_department_type', $loggedUser);

    }

    /**
     * @return $this
     * Wyświetlenie strony 'progress_table' dla trenerów
     */
    public function progress_tableGET()
    {
        $consultant = $this::getCoachConsultant(array(Auth::user()->id));
        return view('coaching.progress_table')
            ->with('consultant', $consultant);
    }

    /**
     * @param Request $request
     * Zapisywanie nowego coaching'u
     */
    public function saveCoaching(Request $request)
    {
        if ($request->ajax()) {
            if ($request->status == 0)
                $new_coaching = new Coaching();
            else {
                $new_coaching = Coaching::find($request->status);

            }
            $new_coaching->consultant_id = $request->consultant_id;
            $new_coaching->manager_id = Auth::user()->id;
            $new_coaching->coaching_date = $request->coaching_date;
            $new_coaching->subject = $request->subject;
            if ($request->status == 0)
                $new_coaching->comment = 'Brak';
            else
                $new_coaching->comment = $request->coaching_comment;
            $new_coaching->coaching_actual_avg = $request->coaching_actual_avg;
            $new_coaching->average_goal = $request->coaching_goal;
            if ($new_coaching->save()) {
                return 1;
            } else {
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
    public function saveCoachingDirector(Request $request)
    {
        if ($request->ajax()) {
            $activitiStatus = 1;
            if ($request->status == 0) {
                $new_coaching = new CoachingDirector();
                $new_coaching->coaching_level = $request->coaching_level;
                $headInfo = ["T" => 'Nowy coaching'];
            } else {
                $activitiStatus = 2;
                $new_coaching = CoachingDirector::find($request->status);
                $headInfo = ["T" => 'Edycja coachingu'];
            }
            $new_coaching->user_id = $request->manager_id;
            $new_coaching->manager_id = Auth::user()->id;
            $new_coaching->coaching_date = $request->coaching_date;
            $new_coaching->subject = $request->subject;
            $new_coaching->comment = $request->coaching_comment;
            // Dane startowe coachingu
            $new_coaching->average_start = $request->manager_actual_avg == '' ? 0 : $request->manager_actual_avg;
            $new_coaching->janky_start = $request->manager_actual_janky == '' ? 0 : $request->manager_actual_janky;
            $new_coaching->rbh_start = $request->manager_actual_rbh == '' ? 0 : $request->manager_actual_rbh;
            // Dane docelowe
            $new_coaching->average_goal = $request->coaching_manager_avg_goal == '' ? 0 : $request->coaching_manager_avg_goal;
            $new_coaching->janky_goal = $request->coaching_manager_avg_janky == '' ? 0 : $request->coaching_manager_avg_janky;
            $new_coaching->rbh_goal = $request->coaching_manager_avg_rbh == '' ? 0 : $request->coaching_manager_avg_rbh;
            //Typ coachingu
            $new_coaching->coaching_type = $request->coaching_type;

            if ($new_coaching->save()) {
                $this::saveLogInfo($new_coaching,$activitiStatus);
                return 0;
            } else {
                return -1;
            }
        }
    }

    /*
     * Datatables dla dyrektorów i kierowników gdzie level 3 dyrektor 2 kierownik
     */
    public function datatableCoachingTableDirector(Request $request)
    {
        $inprogres = $this::setInfoCoachingTableDirector($request);
        return Datatables::of($inprogres)->make(true);
    }

    //Pobieranie danych o kierownikach dla dyrektora (oddziałowy)
    public function setInfoCoachingTableDirector($request)
    {
        $date_start = $request->date_start;
        $date_stop = $request->date_stop;

//        $date_start = '2018-04-01';
//        $date_stop = '2018-04-30';
//        $request->coaching_level = 1;
//        $request->report_status = 0;

        //informacje o coachingu dla kierownika
        if ($request->coaching_level == 1) {

            $coaching_consultant_inprogres = DB::table('coaching_director')
                ->select(DB::raw('coaching_director.*,
                            user.id as user_id,
                            user.status_work as statusWork,
                            user.login_phone as login_phone,
                            user.first_name as user_first_name,
                            user.last_name as user_last_name,
                            manager.first_name as manager_first_name,
                            manager.last_name as manager_last_name,
                            round((select sum(time_to_sec(`accept_stop`)-time_to_sec(`accept_start`)) 
                                from work_hours where work_hours.id_user = `coaching_director`.`user_id`
                            and work_hours.date >= CONCAT(coaching_date," 00:00:00") )/3600) as actual_rbh,
                            (select sum(success) 
                                from work_hours where work_hours.id_user = `coaching_director`.`user_id`
                            and work_hours.date >= CONCAT(coaching_date," 00:00:00") ) as couching_success
                        '))
                ->join('users as user', 'user.id', 'coaching_director.user_id')
                ->join('users as manager', 'manager.id', 'coaching_director.manager_id')
                ->whereBetween('coaching_date', [$date_start . ' 00:00:00', $date_stop . ' 23:00:00'])
                ->where('coaching_level', '=', $request->coaching_level);
            if ($request->report_status == 0) {
                $coaching_consultant_inprogres = $coaching_consultant_inprogres->where('coaching_director.status', '=', $request->report_status);
            } else
                $coaching_consultant_inprogres = $coaching_consultant_inprogres->whereIn('coaching_director.status', [1, 2]);

            if ($request->type_table == 'manager') { // tabela postępów menager
                if ($request->department_info < 100) { // wybrany oddział
                    $coaching_consultant_inprogres = $coaching_consultant_inprogres->where('manager.department_info_id', '=', $request->department_info);
                } else { // Wybrany dyrektor
                    $dirId = substr($request->department_info, 2);
                    $userType = User::find($dirId);
                    if($userType->user_type_id == 14)
                        $userTypeId = [5];
                    else
                        $userTypeId = [4,12];
                    $coaching_consultant_inprogres = $coaching_consultant_inprogres->whereIn('manager.user_type_id',$userTypeId);
                    $director_departments = Department_info::where(function($querry) use ($dirId) {
                        $querry->orwhere('director_id', '=', $dirId)
                            ->orwhere('regionalManager_id', '=', $dirId)
                            ->orwhere('director_hr_id', '=', $dirId);
                    })->get()->pluck('id')->toArray();
                    $coaching_consultant_inprogres = $coaching_consultant_inprogres->whereIn('manager.department_info_id', $director_departments);
                }
                if ($request->coach_id != 'Wszyscy') {
                    $coaching_consultant_inprogres = $coaching_consultant_inprogres->where('manager.id', '=', $request->coach_id);
                }
            } else {
                if (Auth::user()->id != 1364) {
                    $coaching_consultant_inprogres = $coaching_consultant_inprogres->where('manager_id', '=', Auth::user()->id);
                }
            }

            $coaching_consultant_inprogres = $coaching_consultant_inprogres->groupBy('user.id', 'coaching_director.id')
                ->get();
            $ready_data = collect();
            $coaching_consultant_inprogres->map(function ($item) {

                $date_start_janky = $item->coaching_date;
                // sumowanie janków
                $user_pbx_number = $item->login_phone;
                $janky_reports = DB::table('pbx_report_extension')
                    ->select(DB::raw(
                        '
                                 SUM(pbx_report_extension.all_bad_talks) as janky_all_bad,
                                 SUM(pbx_report_extension.all_checked_talks) as janky_all_check,
                                 SUM(success) as sum_success, 
                                 pbx_id'))
                    ->where('pbx_report_extension.pbx_id', '=', $user_pbx_number)
                    ->whereIn('pbx_report_extension.id', function ($query) use ($date_start_janky, $user_pbx_number) {
                        $query->select(DB::raw(
                            'MAX(pbx_report_extension.id)'
                        ))
                            ->from('pbx_report_extension')
                            ->where('pbx_report_extension.pbx_id', '=', $user_pbx_number)
                            ->where('report_date', '>=', $date_start_janky)
                            ->groupBy('report_date');
                    })
                    ->groupBy('pbx_report_extension.pbx_id')
                    ->get();
                if ($item->actual_rbh == null) {
                    $item->actual_rbh = 0;
                }
                if ($item->actual_rbh != 0) {
                    $item->actual_avg = round($item->couching_success / $item->actual_rbh, 2);
                } else {
                    $item->actual_avg = 0;
                }
                // gdy znaleziono jaki
                if (is_object($janky_reports->first())) {
                    $all_check = $janky_reports->first()->janky_all_check;
                    $all_bad = $janky_reports->first()->janky_all_bad;
                    if ($all_check != 0)
                        $item->actual_janky = round(($all_bad * 100) / $all_check, 2);
                    else
                        $item->actual_janky = 0;
                } else {
                    $all_check = 0;
                    $all_bad = 0;
                    $item->actual_janky = 0;
                }

                return $item;
            });
            // czy jest w toku czy rozliczony
            if (is_numeric($request->type) && $request->type != 0) {
                $coaching_consultant_inprogres = $coaching_consultant_inprogres->where('coaching_type', '=', $request->type);
            }
            //dd($coaching_consultant_inprogres);
            return $coaching_consultant_inprogres;
        } else if ($request->coaching_level == 2) {
            //informacje o coachingu dla kierownika
            $coaching_manager_inprogres = DB::table('coaching_director')
                ->select(DB::raw('coaching_director.*,
                            user.id as user_id,
                            user.first_name as user_first_name,
                            user.last_name as user_last_name,
                            manager.first_name as manager_first_name,
                            manager.last_name as manager_last_name'))
                ->join('users as user', 'user.id', 'coaching_director.user_id')
                ->join('users as manager', 'manager.id', 'coaching_director.manager_id');

            if ($request->type_table == 'manager') {
                if ($request->coach_id != 'Wszyscy') {
                    $coaching_manager_inprogres = $coaching_manager_inprogres->where('manager.id', '=', $request->coach_id);
                }
            } else {
                if (Auth::user()->id != 1364 && Auth::user()->id != 2 && Auth::user()->id != 29) {
                    $coaching_manager_inprogres = $coaching_manager_inprogres->where('manager.id', '=', Auth::user()->id);
                } else {
                    $coaching_manager_inprogres = $coaching_manager_inprogres->where('manager.department_info_id', '=', Auth::user()->department_info_id);
                }
            }
            $coaching_manager_inprogres = $coaching_manager_inprogres->where('coaching_level', '=', $request->coaching_level);
            if ($request->report_status == 0) {
                $coaching_manager_inprogres = $coaching_manager_inprogres->where('status', '=', $request->report_status);
            } else
                $coaching_manager_inprogres = $coaching_manager_inprogres->whereIn('status', [1, 2]);
            $coaching_manager_inprogres = $coaching_manager_inprogres->whereBetween('coaching_date', [$date_start . ' 00:00:00', $date_stop . ' 23:00:00'])
                ->groupBy('user.id', 'coaching_director.id')
                ->get();
            $ready_data = collect();
            foreach ($coaching_manager_inprogres as $item) {
                $single_data = collect();
                //grupa trenera
                $all_users = DB::table('work_hours')
                    ->select(
                        DB::raw('               
                                    users.id as user_id'
                        ))
                    ->join('users', 'users.id', 'work_hours.id_user')
                    ->whereIn('users.coach_id', array($item->user_id))
                    ->where('users.status_work', '=', 1)
                    ->groupby('users.id')
                    ->get();
                $succes = 0;
                $rbh = 0;
                $all_check = 0;
                $all_bad = 0;
                foreach ($all_users as $user_form_all) {
                    $user = User::find($user_form_all->user_id);
                    $user_pbx_number = $user->login_phone;
                    //sumowanie po użytkowniku RBH i sukcesy
                    $work_hours_user = DB::table('work_hours')
                        ->select(
                            DB::raw('     
                                   sum(success) as succes_sum,       
                                   sum(round((time_to_sec(`accept_stop`)-time_to_sec(`accept_start`))/3600)) as rbh
                                    '))
                        ->where('id_user', '=', $user->id)
                        ->where('date', '>=', $item->coaching_date)
                        ->get();
                    if (is_object($work_hours_user->first())) {
                        $succes += $work_hours_user->first()->succes_sum;
                        $rbh += $work_hours_user->first()->rbh;
                    }
                    // sumowanie janków
                    $janky_reports = DB::table('pbx_report_extension')
                        ->select(DB::raw(
                            '
                                 SUM(pbx_report_extension.all_bad_talks) as janky_all_bad,
                                 SUM(pbx_report_extension.all_checked_talks) as janky_all_check,
                                 SUM(success) as sum_success, 
                                 pbx_id'))
                        ->where('pbx_report_extension.pbx_id', '=', $user_pbx_number)
                        ->whereIn('pbx_report_extension.id', function ($query) use ($item, $user_pbx_number) {
                            $query->select(DB::raw(
                                'MAX(pbx_report_extension.id)'
                            ))
                                ->from('pbx_report_extension')
                                ->where('pbx_report_extension.pbx_id', '=', $user_pbx_number)
                                ->where('report_date', '>=', $item->coaching_date)
                                ->groupBy('report_date');
                        })
                        ->groupBy('pbx_report_extension.pbx_id')
                        ->get();
                    // gdy znaleziono jaki
                    if (is_object($janky_reports->first())) {
                        $all_check += $janky_reports->first()->janky_all_check;
                        $all_bad += $janky_reports->first()->janky_all_bad;
                    } else {
                        $all_check += 0;
                        $all_bad += 0;
                    }
                }
                //tworzenie obiektu z danymu
                $single_data->coaching_id = $item->id;
                $single_data->rbh = $rbh;
                $single_data->check_all = $all_check;
                $single_data->all_bad = $all_bad;
                $single_data->succes = $succes;
                $ready_data->push($single_data);
            }
            // dodanie nowych wpisów z danymi użytkownika do coachingu głównego
            $coaching_manager_inprogres->map(function ($item) use ($ready_data) {
                $hold_date = $ready_data->where('coaching_id', '=', $item->id);
                if ($hold_date->first() != null) {
                    $hold_date = $hold_date->first();
                    $item->actual_avg = $hold_date->rbh != null && $hold_date->rbh != 0 ? round($hold_date->succes / $hold_date->rbh, 2) : 0;
                    $item->actual_janky = $hold_date->check_all != null && $hold_date->check_all != 0 ? round(($hold_date->all_bad * 100) / $hold_date->check_all, 2) : 0;
                    $item->actual_rbh = $hold_date->rbh != null && $hold_date->rbh != 0 ? $hold_date->rbh : 0;
                } else {
                    $item->actual_avg = 0;
                    $item->actual_janky = 0;
                    $item->actual_rbh = 0;
                }
                return $item;
            });
            // czy jest w toku czy rozliczony
            if (is_numeric($request->type) && $request->type != 0) {
                $coaching_manager_inprogres = $coaching_manager_inprogres->where('coaching_type', '=', $request->type);
            }
            return $coaching_manager_inprogres;
        } else if ($request->coaching_level == 3) {
            // informacje o coachingu dla dyrektora
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
                            and work_hours.date < CURDATE()
                            and work_hours.date >= CONCAT(coaching_date," 00:00:00") )/3600,2) as actual_rbh,
                            department_info.commission_avg,
                            department_info.dep_aim
                            '))
                ->join('users as user', 'user.id', 'coaching_director.user_id')
                ->join('users as manager', 'manager.id', 'coaching_director.manager_id')
                ->join('department_info', 'department_info.id', '=', 'user.department_info_id');
            if ($request->type_table == 'manager') {
                if ($request->coach_id != 'Wszyscy') {
                    $coaching_director_inprogres = $coaching_director_inprogres->where('manager.id', '=', $request->coach_id);
                }
            } else {
                if (Auth::user()->id != 1364) {
                    $coaching_director_inprogres = $coaching_director_inprogres->where('manager.id', '=', Auth::user()->id);
                } else {
                    $coaching_director_inprogres = $coaching_director_inprogres->where('manager.department_info_id', '=', Auth::user()->department_info_id);
                }
            }
            $coaching_director_inprogres = $coaching_director_inprogres->where('coaching_level', '=', $request->coaching_level)
                ->where('coaching_level', '=', $request->coaching_level);
            if ($request->report_status == 0) {
                $coaching_director_inprogres = $coaching_director_inprogres->where('status', '=', $request->report_status);
            } else
                $coaching_director_inprogres = $coaching_director_inprogres->whereIn('status', [1, 2]);
            $coaching_director_inprogres = $coaching_director_inprogres->whereBetween('coaching_date', [$date_start . ' 00:00:00', $date_stop . ' 23:00:00'])
                ->groupBy('user.id', 'coaching_director.id')
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
                ->whereIn('hour_report.id', function ($query) use ($date_start) {
                    $query->select(DB::raw(
                        'MAX(hour_report.id)'
                    ))
                        ->from('hour_report')
                        ->where('report_date', '>=', $date_start)
                        ->where('call_time', '!=', 0)
                        ->groupBy('department_info_id', 'report_date');
                })
                ->groupby('department_info_id', 'report_date')
                ->get();
            // inforamcje o jankach
            $janky_reports = DB::table('pbx_dkj_team')
                ->select(DB::raw(
                    'sum(pbx_dkj_team.count_bad_check) as sum_bad,
                  sum(pbx_dkj_team.count_all_check) as sum_check,
                  department_info.id as janky_department_info,                  
                  report_date'))
                ->join('department_info', 'department_info.id', '=', 'pbx_dkj_team.department_info_id')
                ->whereIn('pbx_dkj_team.id', function ($query) use ($date_start) {
                    $query->select(DB::raw(
                        'MAX(pbx_dkj_team.id)'
                    ))
                        ->from('pbx_dkj_team')
                        ->where('report_date', '>=', $date_start)
                        ->groupBy('department_info_id', 'report_date');
                })
                ->groupBy('pbx_dkj_team.department_info_id', 'report_date')
                ->get();
            //mapowanie wyniku
            $coaching_director_inprogres = $coaching_director_inprogres->map(function ($iteam) use ($hour_report_inprogres, $janky_reports) {
                //Zerowanie rhb
                if ($iteam->actual_rbh == null)
                    $iteam->actual_rbh = 0;
                //Data coachingu
                $coaching_date = $iteam->coaching_date;
                //Aktualna średnia
                $sum_success = $hour_report_inprogres
                    ->where('department_info_id', '=', $iteam->user_department_info)
                    ->where('report_date', '>=', $coaching_date)
                    ->where('report_date', '<', date('Y-m-d'))
                    ->sum('sum_success');
                $iteam->actual_avg = ($sum_success != null && $iteam->actual_rbh != 0) ? round($sum_success / $iteam->actual_rbh, 2) : 0;

                $actual_janky = $janky_reports
                    ->where('janky_department_info', '=', $iteam->user_department_info)
                    ->where('report_date', '>=', $coaching_date)
                    ->where('report_date', '<', date('Y-m-d'));

                $sum_janky_check = $actual_janky->sum('sum_check');
                $sum_janky_bad = $actual_janky->sum('sum_bad');
                //Aktualna ilość janków
                $iteam->actual_janky = ($sum_janky_bad != 0 && $sum_janky_check != 0 && $sum_janky_check != null) ? round(($sum_janky_bad * 100) / $sum_janky_check, 2) : 0;
                //Próg RBH
                $iteam->rbh_min = $iteam->dep_aim / $iteam->commission_avg;
                $iteam->rbh_min = $iteam->rbh_min * 3;
                return $iteam;
            });
            if (is_numeric($request->type) && $request->type != 0) {
                $coaching_director_inprogres = $coaching_director_inprogres->where('coaching_type', '=', $request->type);
            }
            return $coaching_director_inprogres;
        }

    }

    //Cofnięcie wybranego rozliczenia
    public function revertSettlementPost(Request $request)
    {
        $coaching_director_id = $request->coaching_director_id;
        if ($coaching_director_id != null || $coaching_director_id != 0) {
            $coachingDirector = null;
            try {
                $coachingDirector = CoachingDirector::where('id', '=', $coaching_director_id)->first();
            } catch (Exception $e) {
                report($e);
                return ["type" => "error", "msg" => "Pojawił się problem z bazą danych, skontaktuj się z administratorem", "title" => "Błąd"];
            }
            if ($coachingDirector == null) {
                return ["type" => "warning", "msg" => "To rozliczenie już nie istnieje", "title" => "Błąd"];
            }
            $coachingDirector->status = 0;
            $coachingDirector->save();
            return ["type" => "success", "msg" => "Rozliczenie zostało cofnięte", "title" => "Sukces"];
        } else
            return ["type" => "warning", "msg" => "To rozliczenie już nie istniejes", "title" => "Błąd"];
    }

    /*
     * Datatable dla trenerów o konsultantach + dodać janki
     */
    public function datatableCoachingTable(Request $request)
    {
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
            ->join('users as consultant', 'consultant.id', 'coaching.consultant_id')
            ->join('work_hours', 'work_hours.id_user', 'coaching.consultant_id')
            ->join('users as manager', 'manager.id', 'coaching.manager_id');
        if ($request->type == 'manager') {
            if ($request->department_info < 100) {
                $inprogres = $inprogres->whereBetween('coaching_date', [$request->date_start . ' 00:00:00', $request->date_stop . ' 23:00:00']);
                if ($request->report_status == 1) {
                    $inprogres = $inprogres->whereIn('coaching.status', [1, 2]);
                } else {
                    $inprogres = $inprogres->where('coaching.status', '=', $request->report_status);
                }
                // wybrany oddział do filtracji
                $inprogres = $inprogres->where('manager.department_info_id', '=', $request->department_info);
            } else { // opcja z dyrektorem
                $dirId = substr($request->department_info, 2);
                $director_departments = Department_info::select('id')->where('director_id', '=', $dirId)->get();
                $inprogres = $inprogres->whereBetween('coaching_date', [$request->date_start . ' 00:00:00', $request->date_stop . ' 23:00:00']);
                if ($request->report_status == 1) {
                    $inprogres = $inprogres->whereIn('coaching.status', [1, 2]);
                } else {
                    $inprogres = $inprogres->where('coaching.status', '=', $request->report_status);
                }
                // wybrany oddział do filtracji
                $inprogres = $inprogres->whereIn('manager.department_info_id', $director_departments->pluck('id')->toArray());
            }
            // wybrany trener do filtracji
            if ($request->coach_id != 'Wszyscy') {
                $inprogres = $inprogres->where('manager.id', '=', $request->coach_id);
            }
            $inprogres = $inprogres->groupby('coaching.id');
        } else {
            $inprogres->whereBetween('coaching_date', [$request->date_start . ' 00:00:00', $request->date_stop . ' 23:00:00']);
            if ($request->report_status == 1) {
                $inprogres = $inprogres->whereIn('coaching.status', [1, 2]);
            } else {
                $inprogres = $inprogres->where('coaching.status', '=', $request->report_status);
            }
            if (Auth::user()->id != 1364) {
                $inprogres = $inprogres
                    ->where('coaching.manager_id', '=', Auth::user()->id);
            }
            $inprogres = $inprogres->groupby('coaching.id');
        }
        return datatables($inprogres)->make(true);
    }



    /**
     * @return mixed
     * pobranie Hr dla danego dyrektorów HR
     */
    public function getCoachingManagerListHR($director_id,$isHr)
    {
        // Pobranie oddziałów przypisanych do dyrektora
        $director_departments = Department_info::
        where('director_hr_id', '=', $director_id)
            ->get();
        //List Kierowników
        $all_manager_list = User::
        whereIn('department_info_id', $director_departments->pluck('id')->toarray())
            ->where('status_work', '=', 1)
            ->whereIn('user_type_id', [5])
            ->where('id', '!=', $director_id)
            ->get();
        // Pobranie statystyk dla hr
        $department_statistics = $this::getDepartmentInfo(1, 1, $director_departments->pluck('id')->toArray(), $all_manager_list,$isHr);
        return $department_statistics;
    }
    /**
     * @return mixed
     * pobranie kierowników dla danego dyrektorów
     */
    public function getCoachingManagerList($director_id,$isHr)
    {

        if (Auth::user()->id == 1364 || Auth::user()->id == 11) {
            $director_id = 29;
        }
        // Pobranie oddziałów przypisanych do dyrektora
        $director_departments = Department_info::
        where('director_id', '=', $director_id)
            ->get();

        //List Kierowników
        $all_manager_list = User::
        whereIn('department_info_id', $director_departments->pluck('id')->toarray())
            ->where('status_work', '=', 1)
            ->whereIn('user_type_id', [7, 13])
            ->where('id', '!=', $director_id)
            ->get();
        // Pobranie statystyk dla kierownika
        $department_statistics = $this::getDepartmentInfo(1, 1, $director_departments->pluck('id')->toArray(), $all_manager_list,$isHr);
        return $department_statistics;
    }

    // Informacje o oddziale kierownika
    public function getDepartmentInfo($date_start, $date_stop, $director_id, $all_manager_list,$isHr)
    {
        // od 02.04 do 08.04 tydzień
        $date_start = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 21, date("Y")));
        $date_stop = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y")));

        //tu był zmiana z godzin na liczbę
        $work_hours = DB::table('work_hours')
            ->select(DB::raw(
                'sum(time_to_sec(accept_stop) - time_to_sec(accept_start))/3600 as realRBH,
                work_hours.date,
                department_info.*
            '))
            ->join('users', 'users.id', '=', 'work_hours.id_user')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->whereIn('department_info.id', $director_id)
            ->where('users.user_type_id', '=', 1)
            ->groupBy('department_info.id', 'work_hours.date')
            ->orderby('date', 'desc')
            ->get();


        $ready_data = collect(); // Gotowe dane

        foreach ($director_id as $item) {
            $range = $work_hours->where('id', '=', $item);
            $rbh = 0;
            $succes = 0;
            $janky = 0;
            $date_start = '';
            $date_stop = '';
            $i = 0;
            $date_array = [];
            if (is_object($range->first())) {
                $dep_aim = $range->first()->dep_aim;
                $commission_avg = $range->first()->commission_avg;
                // Pobranie informacji o rbh oraz zakres datowy
                while ($rbh < ($dep_aim / $commission_avg) * 3) {
                    if ($i == 0 && $range->first()->realRBH != null && $range->first()->realRBH > 10) {
                        $date_stop = $range->first()->date;
                        $i++;
                    }
                    $date_start = $range->first()->date;
                    $rbh += $range->first()->realRBH;
                    $range = $range->slice(1);
                }
                //sumowanie janków
                $janky_reports = DB::table('pbx_dkj_team')
                    ->select(DB::raw(
                        'round(SUM(pbx_dkj_team.count_bad_check)*100/SUM(pbx_dkj_team.count_all_check),2) as actual_janky,
                        SUM(success) as sum_success, 
                     department_info.id'))
                    ->join('department_info', 'department_info.id', '=', 'pbx_dkj_team.department_info_id')
                    ->whereIn('pbx_dkj_team.id', function ($query) use ($date_start, $date_stop) {
                        $query->select(DB::raw(
                            'MAX(pbx_dkj_team.id)'
                        ))
                            ->from('pbx_dkj_team')
                            ->whereBetween('report_date', [$date_start, $date_stop])
                            ->groupBy('department_info_id', 'report_date');
                    })
                    ->where('department_info.id', $item)
                    ->groupBy('pbx_dkj_team.department_info_id')
                    ->get();
                if(!$janky_reports->isEmpty()){
                    $janky = $janky_reports->first()->actual_janky;
                    $succes = $janky_reports->first()->sum_success;
                }
                else{
                    $succes = 0;
                    $janky = 0;
                }


                if($isHr){
                    $columnName = 'hr_id';
                    $manager = DB::table('department_info')
                        ->select(DB::raw('users.id as manager_id,
                                users.first_name,
                                users.last_name,
                                secondHR.first_name as secondHRFirstName,
                                secondHR.last_name as secondHRLastName,
                                secondHR.id as secondHRID,
                                department_info.id as department_info_id'))
                        ->leftjoin('users', 'users.id', $columnName);
                }else{
                    $columnName = 'menager_id';
                    $manager = DB::table('department_info')
                        ->select(DB::raw('users.id as manager_id,
                                users.first_name,
                                users.last_name,
                                null as secondHRFirstName,
                                null as secondHRLastName,
                                null as secondHRID,
                                department_info.id as department_info_id'))
                        ->leftjoin('users', 'users.id', $columnName);
                }


                if($isHr)
                    $manager = $manager->leftjoin('users as secondHR', 'secondHR.id', "department_info.hr_id_second");
                $manager = $manager
                    ->where('department_info.id', '=', $item)
                    ->first();
                if(is_object($manager)){
                    if($manager->manager_id != null)
                    {
                        $data = new \stdClass();
                        $data->department_info_id = $item;
                        $data->date_start = $date_start;
                        $data->date_stop = $date_stop;
                        $data->menager_id = $manager->manager_id;
                        $data->manager_name = $manager->first_name . ' ' . $manager->last_name;
                        if($isHr){
                            $data->success          = 0;
                            $data->avg_average      = 0;
                            $data->realRBH          = 0;
                            $data->sum_janky_count  = 0;
                        }else{
                            $data->success          = $succes;
                            $data->avg_average      = round($succes / $rbh, 2);
                            $data->realRBH          = $rbh;
                            $data->sum_janky_count  = $janky;
                        }
                        $ready_data->push($data);
                    }
                    if($manager->secondHRID != null){
                        $data = new \stdClass();
                        $data->department_info_id = $item;
                        $data->date_start = $date_start;
                        $data->date_stop = $date_stop;
                        $data->menager_id = $manager->secondHRID;
                        $data->manager_name = $manager->secondHRFirstName . ' ' . $manager->secondHRLastName;
                        if($isHr){
                            $data->success          = 0;
                            $data->avg_average      = 0;
                            $data->realRBH          = 0;
                            $data->sum_janky_count  = 0;
                        }else{
                            $data->success          = $succes;
                            $data->avg_average      = round($succes / $rbh, 2);
                            $data->realRBH          = $rbh;
                            $data->sum_janky_count  = $janky;
                        }
                        $ready_data->push($data);
                    }
                }

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
    public function getCoachingCoachList()
    {
        // Pobranie oddziałów przypisanych do kierownika
        $manager_id = Auth::user()->id;

        $manager_departments = Department_info::
            where(function($querry) use ($manager_id) {
                $querry->orwhere('director_id', '=', $manager_id)
                    ->orwhere('menager_id', '=', $manager_id)
                    ->orwhere('regionalManager_id', '=', $manager_id)
                    ->orwhere('director_hr_id', '=', $manager_id)
                    ->orwhere('instructor_regional_id', '=', $manager_id);
            })->get();
        // gdy koching chce zrobić osoba która nie jest kierownikiem lub dyrektorem
        if ($manager_departments->isempty()) {
            $manager_departments = Department_info::
            where('id', '=', Auth::user()->department_info_id)
                ->get();
        }
        //List Treneró i Hrowców
        $all_coach_list = User::
        whereIn('main_department_id', $manager_departments->pluck('id')->toarray())
            ->where('status_work', '=', 1)
            ->whereIn('user_type_id', [4, 12, 5, 19, 20])
            ->get();

//        dd($all_coach_list);
        $group_status = collect();
        foreach ($all_coach_list as $item) {
            $hold_info_about_user = $this::getCoachConsultant(array($item->id));
            //$group_status->push($this::getCoachConsultant(array($item->id)));
            $manager_info = new \stdClass();
            $janky_all_check = $hold_info_about_user->sum('janky_all_check');
            $janky_all_bad = $hold_info_about_user->sum('janky_all_bad');
            $rbh = round($hold_info_about_user->sum('rbh'), 2);
            $success = $hold_info_about_user->sum('success');
            $manager_actual_avg = ($rbh != 0 && $rbh != null) ? round($success / $rbh, 2) : 0;
            $manager_actual_janky = ($janky_all_check != 0 && $janky_all_check != null) ? round($janky_all_bad * 100 / $janky_all_check, 2) : 0;
            $manager_info->manager_id = $item->id;
            $manager_info->manager_name = $item->first_name . ' ' . $item->last_name;
            $manager_info->manager_actual_avg = $manager_actual_avg;
            $manager_info->manager_actual_rbh = $rbh != null ? $rbh : 0;
            $manager_info->manager_actual_janky = $manager_actual_janky;
            $manager_info->manager_actual_succes = $success;
            $manager_info->manager_actual_rbh = $rbh;
            $manager_info->manager_actual_check = $janky_all_check;
            $manager_info->manager_actual_bad = $janky_all_bad;
            $manager_info->user_type = $item->user_type_id;
            $group_status->push($manager_info);
        }
        return $group_status;
    }

    /**
     * @return mixed
     * pobranie konsultantów dla zalogowanego trenera
     */
    public function getCoachConsultant($coach_id)
    {
        if ($coach_id[0] == 1364 or $coach_id[0] == 6964)
            $coach_id[0] = 4153;
        $all_users = DB::table('work_hours')
            ->select(
                DB::raw('               
                users.id as user_id'
                ))
            ->join('users', 'users.id', 'work_hours.id_user')
            ->whereIn('users.coach_id', $coach_id)
            ->where('users.status_work', '=', 1)
            ->groupby('users.id')
            ->get();
        $ready_data = [];
        $date_start = '';
        $date_stop = '';
        $i = 0;
        foreach ($all_users as $user_form_all) {
            $user = User::find($user_form_all->user_id);
            $user_pbx_number = $user->login_phone;
            $item = $user->work_hours->sortbyDESC('date');
            $succes = 0;
            $rbh = 0;
            $date_start = '';
            $date_stop = '';
            $i = 0;
            while ($rbh < 18*60*60 && is_object($item->first())) { // po przepracowaniu coanjmniej 18 rbh
                $work_hours = $item->first();
                if ($i == 0) {
                    $date_stop = $work_hours->date;
                    $i++;
                }
                $date_start = $work_hours->date;
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

            //sumowanie janków
            $janky_reports = DB::table('pbx_report_extension')
                ->select(DB::raw(
                    '
                     SUM(pbx_report_extension.all_bad_talks) as janky_all_bad,
                     SUM(pbx_report_extension.all_checked_talks) as janky_all_check,
                     SUM(success) as sum_success, 
                     pbx_id'))
                ->where('pbx_report_extension.pbx_id', '=', $user_pbx_number)
                ->whereIn('pbx_report_extension.id', function ($query) use ($date_start, $date_stop, $user_pbx_number) {
                    $query->select(DB::raw(
                        'MAX(pbx_report_extension.id)'
                    ))
                        ->from('pbx_report_extension')
                        ->where('pbx_report_extension.pbx_id', '=', $user_pbx_number)
                        ->whereBetween('report_date', [$date_start, $date_stop])
                        ->groupBy('report_date');
                })
                ->groupBy('pbx_report_extension.pbx_id')
                ->get();

            $data = new \stdClass();
            $data->id = $user->id;
            $data->first_name = $user->first_name;
            $data->last_name = $user->last_name;
            $data->start_date = $date_start;
            $data->stop_date = $date_stop;
            $data->success = $succes;
            $data->pbx = $user_pbx_number;
            if (is_object($janky_reports->first())) {
                $data->janky_all_check = $janky_reports->first()->janky_all_check;
                $data->janky_all_bad = $janky_reports->first()->janky_all_bad;
                if ($data->janky_all_check != 0)
                    $data->sum_janky_count = round(($data->janky_all_bad * 100) / $data->janky_all_check, 2);
                else
                    $data->sum_janky_count = 0;
            } else {
                $data->janky_all_check = 0;
                $data->janky_all_bad = 0;
                $data->sum_janky_count = 0;
            }
            $data->rbh = $rbh;
            if ($rbh == 0) {
                $data->avg_consultant = 0;
                $data->rbh = 0;

            } else {
                $data->rbh = $rbh / 3600;
                $data->avg_consultant = round($succes / ($rbh / 3600), 2);
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
    public function acceptCoaching(Request $request)
    {
        if ($request->ajax()) {
            $coaching = Coaching::find($request->coaching_id);
            $coaching->comment = $request->coaching__comment;

            if (floatval($coaching->average_goal) > floatval($request->avrage_end)) // Coaching niezaliczony
                $coaching->status = 2;
            else
                $coaching->status = 1;    // Coaching zaliczony
            $coaching->coaching_date_accept = date('Y-m-d');
            $coaching->avrage_end = $request->avrage_end;
            $coaching->rbh_end = $request->rbh_end;
            $coaching->save();
            return $coaching->average_goal;
        } else
            return 0;
    }

    /**
     * Akceptacja Coaching'u Dyrektor
     * @param Request $request
     * @return int
     */
    public function acceptCoachingDirector(Request $request)
    {
        if ($request->ajax()) {
            $coaching = CoachingDirector::find($request->coaching_id);
            $coaching->comment = $request->coaching__comment;

            if ($request->coaching_type == 'Średnia') {
                if (floatval($coaching->average_goal) > floatval($request->end_score)) {
                    $coaching->status = 2; // Coaching niezaliczony
                } else {
                    $coaching->status = 1; // Coaching zaliczony
                }
                $coaching->average_end = $request->end_score;// Ostateczny wybik
            } else if ($request->coaching_type == 'Jakość') {
                if (floatval($coaching->janky_goal) < floatval($request->end_score)) {
                    $coaching->status = 2;// Coaching niezaliczony
                } else {
                    $coaching->status = 1; // Coaching zaliczony
                }
                $coaching->janky_end = $request->end_score;// Ostateczny wybik
            } else { // RGH
                if (floatval($coaching->rbh_goal) > floatval($request->end_score)) {
                    $coaching->status = 2;// Coaching niezaliczony
                } else {
                    $coaching->status = 1; // Coaching zaliczony
                }
                $coaching->rbh_end = $request->end_score; // Ostateczny wybik
            }
            //Data zaakceptowania coachingu
            $coaching->coaching_date_accept = date('Y-m-d');
            //Ilość rgb przed zaakceptowaniem coachingu
            $coaching->rbh_end = $request->rbh_end;
            $coaching->save();
            $this::saveLogInfo($coaching,2);
            return $coaching->average_goal;
        } else
            return 0;
    }


    /**
     * Usunięcie Coaching'u
     * @param Request $request
     * @return int
     */
    public function deleteCoaching(Request $request)
    {
        if ($request->ajax()) {
            $coaching = Coaching::find($request->coaching_id);
            $coaching->status = 3;
            $coaching->save();
            return 1;
        } else
            return 0;
    }

    /**
     * Usunięcie Coaching'u dla kierownika
     * @param Request $request
     * @return int
     */
    public function deleteCoachingTableDirector(Request $request)
    {
        if ($request->ajax()) {
            $coaching = CoachingDirector::find($request->coaching_id);
            $coaching->status = 3;
            $coaching->save();
            $this::saveLogInfo($coaching,3,$request->coaching_id);
            return 1;
        } else
            return 0;
    }


    public function getCoaching(Request $request)
    {
        if ($request->ajax()) {
            $coaching = Coaching::find($request->coaching_id);
            return $coaching;
        } else
            return 0;
    }

    public function getCoachingDirector(Request $request)
    {
        if ($request->ajax()) {
            $coaching = CoachingDirector::find($request->coaching_id);
            return $coaching;
        } else
            return 0;
    }

    public function getManagerId(Request $request)
    {
        if ($request->ajax()) {
            $coaching = CoachingDirector::find($request->coaching_id);
            $user = User::where('id', '=', $coaching->user_id);
            return json_decode($user->pluck('user_type_id'));
        }

    }

    /**
     * This method return view coachingAscription with necessary data
     */
    public function coachAscriptionGet()
    {
        $user_type_id = 3;

        $departmentOfLoggedUser = Auth::user()->department_info_id;

        $coachingOwners = DB::table('coaching_director')
            ->select(DB::raw('
                coaching_director.manager_id as manager_id,
                users.first_name as first_name,
                users.last_name as last_name,
                users.id as id
            '))
            ->join('users', 'users.id', 'coaching_director.manager_id')
            ->where([
                ['status', '=', 0],
                ['coaching_level', '=', 1],
                ['users.department_info_id', '=', $departmentOfLoggedUser]
            ])
            ->distinct()
            ->get();

        $allTrenersFromUserDepartment = User::where([
            ['status_work', '=', 1],
            ['user_type_id', '=', 4],
            ['department_info_id', '=', $departmentOfLoggedUser]
        ])
            ->get();

        return view('coaching.coachingAscription')
            ->with('coachingOwners', $coachingOwners)
            ->with('allTrainers', $allTrenersFromUserDepartment);
    }

    /**
     * This method save to database info about changing coach
     */
    public function coachAscriptionPost(Request $request)
    {
        $error = false;
        $previousCoachDirector_id = $request->coaches;
        $newCoachDirector_id = $request->newCoach;
        if ($previousCoachDirector_id && $newCoachDirector_id) {
            $allCoachingsOfPreviousCoach = CoachingDirector::where([
                ['manager_id', '=', $previousCoachDirector_id],
                ['coaching_level', '=', 1]
            ])
                ->whereIn('status', [0])
                ->get();
        } else {
            $request->session()->flash('message_warning', 'Wybierz obu trenerów');
            return Redirect::back();
        }

        $error = !$this->coachDirectorAscription($previousCoachDirector_id, $newCoachDirector_id, $allCoachingsOfPreviousCoach);

        if ($request->has('action')) {
            if ($request->action == "coachAscription") {
                new ActivityRecorder('T : Przepisanie coachingu, Poprzedni Coach: ' . $previousCoachDirector_id . ' Nowy Coach: ' . $newCoachDirector_id, 192, 2);
                return ['type' => 'success', 'msg' => 'Użytkownik został przypisany', 'title' => 'Przypisano!'];
            }

            if ($error)
                return ['type' => 'warning', 'msg' => 'Coś poszło nie tak, spróbuj później', 'title' => "Nie udało się!"];
            return ['type' => 'success', 'msg' => 'Użytkownik został przypisany', 'title' => 'Przypisano!'];
        } else
            if ($error)
                $request->session()->flash('message_warning', 'Coś poszło nie tak, spróbuj później');
            else {
                new ActivityRecorder('Poprzedni Coach: ' . $previousCoachDirector_id . ' Nowy Coach: ' . $newCoachDirector_id, 192, 2);
                $request->session()->flash('message_ok', 'Coachingi zostały przypisane do nowego trenera');
            }

        return Redirect::back();
    }

    public function coachDirectorAscription($previousCoachDirector_id, $newCoachDirector_id, $allCoachingsToChange)
    {
        try {
            CoachDirectorChange::create([
                'coach_director_id' => $newCoachDirector_id,
                'prev_coach_director_id' => $previousCoachDirector_id,
                'editor_id' => Auth::user()->id,
                'status' => 0
            ]);

            $coachDirectorChangeId = CoachDirectorChange::max('id');

            //dd($allCoachingsToChange);
            foreach ($allCoachingsToChange as $oldCoach) {
                CoachDirectorHistory::create([
                    'coaching_id' => $oldCoach->id,
                    'coach_director_change_id' => $coachDirectorChangeId
                ]);
                $oldCoach->manager_id = $newCoachDirector_id;
                $oldCoach->save();
            }
        } catch (Exception $e) {
            report($e);
            return false;
        }
        return true;
    }

    public function coachAscriptionRevertPost(Request $request)
    {
        $coachDirectorChangeId = $request->coach_director_change_id;

        $error = false;
        try {
            $coachDirectorChange = CoachDirectorChange::where('id', $coachDirectorChangeId)->get()->first();

            $allCoachesIdWithSelectedCoachingChange = CoachDirectorHistory::where('coach_director_change_id', $coachDirectorChangeId)
                ->pluck('coaching_id')->toArray();

            $allCoachingsBeforeAscription = CoachingDirector::whereIn('id', $allCoachesIdWithSelectedCoachingChange)->get();


        } catch (Exception $e) {
            report($e);
            $error = true;
        }

        $error = !$this->coachDirectorAscription($coachDirectorChange->coach_director_id,
            $coachDirectorChange->prev_coach_director_id,
            $allCoachingsBeforeAscription);
        if (!$error) {
            $coachDirectorChange->status = 1;
            $coachDirectorChange->save();
        }

        if ($request->has('action')) {
            //ponizszy if nie ma znaczenia, to jest tylko flaga oznaczenia wykonania ajax
            if ($request->action == "coachAscriptionRevert") {
                new ActivityRecorder('T : Cofniecie przypisania coacingu , Poprzedni Coach: ' . $coachDirectorChange->coach_director_id . ' Nowy Coach: ' . $coachDirectorChange->prev_coach_director_id, 192, 2);
                return ['type' => 'success', 'msg' => 'Pomyślne cofnięcie zmiany', 'title' => "Udało się!"];
            }
            if ($error)
                return ['type' => 'warning', 'msg' => 'Coś poszło nie tak, spróbuj później', 'title' => "Nie udało się!"];
            new ActivityRecorder('Cofniecie przypisania, Poprzedni Coach: ' . $coachDirectorChange->coach_director_id . ' Nowy Coach: ' . $coachDirectorChange->prev_coach_director_id, 192, 2);
            return ['type' => 'success', 'msg' => 'Pomyślne cofnięcie zmiany', 'title' => "Udało się!"];
        } else {
            return Redirect::back();
        }
    }

    public function datatableCoachAscription(Request $request)
    {
        $coachDirectorChanges = null;
        if ($request->ajax())
            if (Auth::user()->user_type_id == 3)
                $coachDirectorChanges = DB::table('coach_director_change as c')
                    ->select('c.id',
                        'u1.first_name as c_first_name', 'u1.last_name as c_last_name',
                        'u2.first_name as pc_first_name', 'u2.last_name as pc_last_name',
                        'c.created_at')
                    ->leftJoin('users as u1', 'c.coach_director_id', '=', 'u1.id')
                    ->leftJoin('users as u2', 'c.prev_coach_director_id', '=', 'u2.id')
                    ->where('c.status', '=', 0)
                    ->orderBy('c.id', 'desc')
                    ->get();
        return datatables($coachDirectorChanges)->make(true);
    }

    public function endCoachingDirector(Request $request){
        if($request->ajax()){
            $coaching = CoachingDirector::find($request->coachingID);
            if($coaching->coaching_type == 1){
                if (floatval($coaching->average_goal) > floatval($request->actualScore)) {
                    $coaching->status = 2; // Coaching niezaliczony
                } else {
                    $coaching->status = 1; // Coaching zaliczony
                }
                $coaching->average_end = $request->actualScore;
            }else if ($coaching->coaching_type == 2){
                if (floatval($coaching->janky_goal) < floatval($request->actualScore)) {
                    $coaching->status = 2;// Coaching niezaliczony
                } else {
                    $coaching->status = 1; // Coaching zaliczony
                }
                $coaching->janky_end = $request->actualScore;
            }else{
                if (floatval($coaching->rbh_goal) > floatval($request->actualScore)) {
                    $coaching->status = 2;// Coaching niezaliczony
                } else {
                    $coaching->status = 1; // Coaching zaliczony
                }
                $coaching->rbh_end = $request->actualScore;
            }
            $coaching->save();
            new ActivityRecorder('T : Zakończenie coachingu:'.$request->coachingID,175,1);
            return 200;
        }
    }
    public function saveLogInfo($collect,$actionType,$coaching_id = null){
        if($collect->coaching_level == 1)
            $link_id = 175;
        else if($collect->coaching_level == 2)
            $link_id = 165;
        else
            $link_id = 166;

        if($actionType == 3){
            new ActivityRecorder('T : Usunuęcie coachingu id:'.$coaching_id,$link_id,$actionType);
        }else{
            if($actionType == 1)
                $header = ["T" => 'Dodanie nowego coachingu '];
            else
                $header = ["T" => 'Edycja coachingu '];
            new ActivityRecorder(array_merge($header,$collect->toArray()),$link_id,$actionType);
        }

    }

}
