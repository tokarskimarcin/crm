<?php

namespace App\Http\Controllers;

use App\Department_info;
use App\Department_types;
use App\Schedule;
use App\User;
use App\UserTypes;
use App\Utilities\GlobalVariables\UsersGlobalVariables;
use App\Work_Hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\ActivityRecorder;

class WorkHoursController extends Controller
{

    public function __construct()
    {
        $this->actuall_date = date("Y-m-d");
        $this->actuall_hour = date("H:i:s");
    }


    public static function getUserTypesPermissionToEditSuccess(){
        return [3,23, 10];
    }
    //******************acceptHour****************** START
    public function acceptHour()
    {
        $myDepartment_info = Department_info::find(Auth::user()->department_info_id);
        $count_agreement = Department_types::find($myDepartment_info->id_dep_type);
        if($count_agreement->count_agreement == 1 && in_array(Auth::user()->user_type_id,WorkHoursController::getUserTypesPermissionToEditSuccess())) // czy zliczane są zagody
        {
            return view('workhours.acceptHourSucces');
        }
        else
        {
            return view('workhours.acceptHour');
        }
    }

    public function acceptHourCadre()
    {
            $departments = $this->getDepartment();
            return view('workhourscadre.acceptHourCadre')->with('departments', $departments);
    }
    public function checkListCadre()
    {
        $userTypes = UserTypes::all();
        $departments = $this->getDepartment();
        return view('workhourscadre.checkListCadre')->with('departments', $departments)->with('userTypes', $userTypes);
    }

    public function test() {
        $workingLessThanNewUsersRBH = Work_Hour::usersWorkingRBHSelector(UsersGlobalVariables::$newUsersRbh,'<=');
//        dd($workingLessThan30RBH->pluck('last_name')->toArray());
        dd($workingLessThanNewUsersRBH->pluck('id_user')->toarray());
    }

    public function usersLive()
    {
        $date = date("W", strtotime( date('Y-m-d'))); // numer tygodnia
        $dayOfWeekArray= array('monday' ,'tuesday','wednesday','thursday','friday','saturday','sunday');
        $day_number = date('N', strtotime(date('Y-m-d')))-1; // numer dnia tygodnia 0-poniedzialek
        $shedule = Schedule::where('week_num',$date)
            ->where('year',date('Y'))
            ->where($dayOfWeekArray[$day_number].'_start','!=',null)
            ->orderby($dayOfWeekArray[$day_number].'_start')
            ->get();

//        dd($shedule);

        $workingLessThanNewUsersRBH = Work_Hour::usersWorkingRBHSelector(UsersGlobalVariables::$newUsersRbh,'<');

        //we are adding field newUser with value 1 if user is new, otherwise 0.
        $sheduleWithNewUsers = $shedule->map(function($item) use($workingLessThanNewUsersRBH) {
            $item->newUser = 0;

            foreach($workingLessThanNewUsersRBH as $newUser) {
                if($item->id_user == $newUser->id_user) { //user from schedule is in set of users that work less than 30 RBH
                    $item->newUser = 1;
                }
            }

            return $item;
        });

        // $shedule = DB::table('schedule')
        //     ->select(DB::raw('
        //         schedule.*
        //     '))
        //     ->join('users', 'users.id', '=', 'schedule.id_user')
        //     ->where('year',date('Y'))
        //     ->where($dayOfWeekArray[$day_number].'_start','!=',null)
        //     ->where('week_num',$date)
        //     ->where('users.department_info_id', '=', Auth::user()->department_info_id)
        //     ->get();

        return view('workhours.usersLive')
            ->with('shedule',$sheduleWithNewUsers)
            ->with('day_number',$day_number)
            ->with('newUsersRbh', UsersGlobalVariables::$newUsersRbh);
    }

    public function datatableAcceptHour(Request $request) // akceptacja godzin dla konsultantów
    {
        if($request->ajax()) {
            $start_date = $request->start_date;
            $stop_date = $request->stop_date;
            $isChecked = $request->withCheck;
            $yesterday = date('Y-m-d', strtotime("-1 days"));
            $today = date('Y-m-d');
            $query = DB::table('work_hours')
                ->join('users', 'work_hours.id_user', '=', 'users.id')
                ->select(DB::raw(
                    'work_hours.id as id,
                    users.first_name,
                    users.last_name,
                    work_hours.click_start,
                    work_hours.click_stop,
                    work_hours.success,
                    work_hours.register_start,
                    work_hours.register_stop,
                    work_hours.date,
                    SEC_TO_TIME(TIME_TO_SEC(register_stop) - TIME_TO_SEC(register_start) ) as time'));
                    //Checking whether checkbox is checked then displaying proper status
                    if($isChecked == 1) {
                      $query = $query
                      ->where('work_hours.status', '=', 1);
                    }
                    else {
                      $query = $query
                      ->whereIn('work_hours.status', [2,3]);
                    }
                    $query = $query
                ->where('users.department_info_id', '=', Auth::user()->department_info_id)
                ->whereIn('users.user_type_id', [1,2,9])
                ->where('work_hours.id_manager', '=', null);
                //"If" for proper date interval displaying
                if($isChecked == 1 && $stop_date == $today) {
                  $query = $query
                  ->whereBetween('date',[$start_date,$yesterday]);
                }
                else {
                  $query = $query
                  ->whereBetween('date',[$start_date,$stop_date]);
                }
            return datatables($query)->make(true);
        }
    }

    public function datatableAcceptHourCadre(Request $request) // akceptacja godzin dla kadry
    {
        if($request->ajax()) {
            $start_date = $request->start_date;
            $stop_date = $request->stop_date;
            $dep_info = $request->dep_info;
            $isChecked = $request->withCheck;
            $yesterday = date('Y-m-d', strtotime("-1 days"));
            $today = date('Y-m-d');
            $query = DB::table('work_hours')
                ->join('users', 'work_hours.id_user', '=', 'users.id')
                ->select(DB::raw(
                    'work_hours.id as id,
                    users.first_name,
                    users.last_name,
                    work_hours.click_start,
                    work_hours.click_stop,
                    work_hours.register_start,
                    work_hours.register_stop,
                    work_hours.date,
                    SEC_TO_TIME(TIME_TO_SEC(register_stop) - TIME_TO_SEC(register_start) ) as time'));
                    if($isChecked == 1) {
                      $query = $query
                      ->where('work_hours.status', '=', 1);
                    }
                    else {
                      $query = $query
                      ->whereIn('work_hours.status', [2,3]);
                    }
            if($dep_info != '*')
            {
                $query->where('users.department_info_id', '=', $dep_info);
            }
            $query->whereNotIn('users.user_type_id',[1,2,9])
                ->where('work_hours.id_manager', '=', null);
                //"If" for proper date interval displaying
                if($isChecked == 1 && $stop_date == $today) {
                  $query = $query
                  ->whereBetween('date',[$start_date,$yesterday]);
                }
                else {
                  $query = $query
                  ->whereBetween('date',[$start_date,$stop_date]);
                }
            return datatables($query)->make(true);
        }
    }

    public function datatableCheckList(Request $request) // akceptacja godzin dla kadry
    {
        if($request->ajax()) {
            $start_date = $request->start_date;
            $start_date_month = date('W', strtotime($start_date));
            $dep_info = $request->dep_info;
            $engraved = $request->engraved; //true || false
            $leaders = $request->leaders; //true || false
            $query = DB::table('users')
                ->leftjoin("work_hours", function ($join) use ($start_date) {
                    $join->on("users.id", "=", "work_hours.id_user")
                    ->where("work_hours.date", "=", $start_date);
                })
                ->leftjoin('schedule', function($join) use ($start_date_month) {
                    $join->on('users.id', '=', 'schedule.id_user')
                    ->where('schedule.week_num', '=', $start_date_month);
                })
                ->select(DB::raw(
                    "STR_TO_DATE('$start_date', '%Y-%m-%d') as start_date,
                    DAYOFWEEK('$start_date') as dayOFWeek,                   
                    work_hours.id as id,
                    users.first_name,
                    users.last_name,
                    work_hours.click_start,
                    HOUR(click_start) onTime,
                    work_hours.click_stop,
                    work_hours.register_start,
                    work_hours.register_stop,
                    work_hours.date,
                    schedule.id_user as hasShedule,
                    schedule.monday_start,
                    schedule.monday_stop,
                    schedule.tuesday_start,
                    schedule.tuesday_stop,
                    schedule.wednesday_start,
                    schedule.wednesday_stop,
                    schedule.thursday_start,
                    schedule.thursday_stop,
                    schedule.friday_start,
                    schedule.friday_stop,
                    schedule.saturday_start,
                    schedule.saturday_stop,
                    schedule.sunday_start,
                    schedule.sunday_stop,
                    schedule.leader,
                    null as scheduleToDayStart,
                    null as scheduleToDayStop,
                    null as freeDay,
                    user_type_id,
                    SEC_TO_TIME(TIME_TO_SEC(register_stop) - TIME_TO_SEC(register_start) ) as time"));
            if($dep_info != '*')
            {
                $query = $query->where('users.department_info_id', '=', $dep_info);
            }
            if($engraved == 'true') { //show only engravered users. If user is in schedule table for given week number then id_user is not null.
                $query = $query->where('schedule.id_user', '!=', null);
            }
            if($leaders == 'true') { //show only leaders of shift.
                $query = $query->where('schedule.leader', '=', 1);
            }
            $query = $query->wherenotin('users.user_type_id',[1,2,9])
            ->where('users.id','!=',11)
            ->where('users.status_work',1)
            ->get();
            $query = $query->map(function ($item){
               if($item->hasShedule != null){
                       switch ($item->dayOFWeek) {
                           case 1:
                               $item->scheduleToDayStart = $item->sunday_start;
                               $item->scheduleToDayStop = $item->sunday_stop;
                               break;
                           case 2:
                               $item->scheduleToDayStart = $item->monday_start;
                               $item->scheduleToDayStop = $item->monday_stop;
                                   break;
                           case 3:
                               $item->scheduleToDayStart = $item->tuesday_start;
                               $item->scheduleToDayStop = $item->tuesday_stop;
                               break;
                           case 4:
                               $item->scheduleToDayStart = $item->wednesday_start;
                               $item->scheduleToDayStop = $item->wednesday_stop;
                               break;
                           case 5:
                               $item->scheduleToDayStart = $item->thursday_start;
                               $item->scheduleToDayStop = $item->thursday_stop;
                               break;
                           case 6:
                               $item->scheduleToDayStart = $item->friday_start;
                               $item->scheduleToDayStop = $item->friday_stop;
                               break;
                           case 7:
                               $item->scheduleToDayStart = $item->saturday_start;
                               $item->scheduleToDayStop = $item->saturday_stop;
                               break;
                           default: break;
                       }
                       if($item->click_start == null){
                           $item->onTime = 1; //Brak klikniecia start pomimo zagrafikowania
                       }
                       else if($item->click_start > $item->scheduleToDayStart ){
                           $item->onTime = 3; // spóźniony do pracy
                       }else if($item->click_start <= $item->scheduleToDayStart){
                           $item->onTime = 2; // na czas
                       }
                       if($item->scheduleToDayStart == null){
                           $item->onTime = 0;
                           $item->freeDay = 1; //Wolny dzień lub brak grafiku na dany dzień
                       }
               }else{
                   $item->onTime = 0; // Brak Grafiku
               }
               return $item;
            });

            return datatables($query)->make(true);
        }
    }

    public function saveAcceptHour(Request $request)
    {
        if($request->ajax())
        {
            $id = $request->id;
            $register_start = $request->register_start;
            $register_stop = $request->register_stop;
            $type_edit = $request->type_edit;
//            $succes = $request->succes;
            $id_manager = Auth::id();
            $checkWorkHours = Work_Hour::find($id);
            if ($checkWorkHours == null) {
                die;
            }
            if($type_edit == 0)
            {
                $work_data = Work_Hour::where('id',$id)->select('register_start'
                ,'register_stop')->first();
                $register_start = $work_data->register_start;
                $register_stop = $work_data->register_stop;
                if($register_start == null || $register_stop == null)
                {
                    echo -1;
                }else{
                Work_Hour::where('id', $id)
                    ->update(['id_manager' => $id_manager,
                        'accept_start' => $register_start,
                        'accept_stop' => $register_stop,
                        'status' => 4,
                        'updated_at' => date('Y-m-d H:i:s')]);}

            }else
            {
                Work_Hour::where('id', $id)
                    ->update(['id_manager' => $id_manager,
                        'accept_start' => $register_start,
                        'accept_stop' => $register_stop,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'status' => 4]);
            }
        }
        $data = [
            'T' => 'Akceptacja godzin pracy',
            'Id czasu pracy' => $request->id,
            'register_start' => $request->register_start,
            'register_stop' => $request->register_stop,
            'type_edit' => $request->type_edit
        ];

        new ActivityRecorder($data, 17,1);
    }
    //******************acceptHour****************** Stop

    //******************RegisterHour****************** Start
    public function registerHour(Request $request)
    {
        if($request->ajax())
        {
            $time_register_start = $request->register_start;
            $time_register_stop = $request->register_stop;
            $check = Work_Hour::where('id_user', Auth::id())
                ->where('date', date('Y-m-d'))
                ->get();
            if ($check[0]->status >= 4) {
                return 1;
            }

            Work_Hour::where('id_user', Auth::id())
                ->where('date', date('Y-m-d'))
                ->update(['register_start' => $time_register_start,'register_stop' => $time_register_stop, 'status' => 3, 'updated_at' => date('Y-m-d H:i:s')]);
        }
    }
    //******************RegisterHour****************** Stop

    public function addHour()
    {
        return view('workhours.addHour');
    }


    //******************ViewHour****************** Start
    public function viewHourGet()
    {
        $department_id = Auth::user()->department_info_id;

        $users = User::where('status_work',1)->wherein('user_type_id',[1,2,9])
            ->where('department_info_id',$department_id)->orderBy('last_name')->get();

        $last_month = date("Y-m", strtotime("first day of previous month"));
        $current_month = date("Y-m");
        // zwolnieni miesiąc temu
        $users_fired_last_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $last_month.'%')
            ->orderBy('last_name')
            ->get();
        // zwolnieni w tym miesiącu
        $users_fired_current_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $current_month.'%')
            ->orderBy('last_name')
            ->get();
        $merge_array = $users->merge($users_fired_last_month);
        $merge_array = $merge_array->merge($users_fired_current_month);

        $userTypesPermissionToEditSuccess = WorkHoursController::getUserTypesPermissionToEditSuccess();
        return view('workhours.viewHour')
            ->with('users',$merge_array->sortBy('last_name'))
            ->with('userTypesPermissionToEditSuccess', $userTypesPermissionToEditSuccess);
    }
    public function viewHourGetCadre()
    {
        $userTypesPermissionToEditSuccess = WorkHoursController::getUserTypesPermissionToEditSuccess();
        $users = User::wherenotin('user_type_id', [1,2,9])
            ->where('status_work',1)
            ->orderBy('last_name')
            ->get();

        $last_month = date("Y-m", strtotime("first day of previous month"));
        $current_month = date("Y-m");
        // zwolnieni miesiąc temu
        $users_fired_last_month =  User::wherenotin('user_type_id', [1,2,9])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $last_month.'%')
            ->orderBy('last_name')
            ->get();
        // zwolnieni w tym miesiącu
        $users_fired_current_month =  User::wherenotin('user_type_id', [1,2,9])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $current_month.'%')
            ->orderBy('last_name')
            ->get();

        $merge_array = $users->merge($users_fired_last_month);
        $merge_array = $merge_array->merge($users_fired_current_month);

        return view('workhourscadre.viewHourCadre')
            ->with('users',$merge_array->sortBy('last_name'))
            ->with('userTypesPermissionToEditSuccess', $userTypesPermissionToEditSuccess);
    }
    public function viewHourPostCadre(Request $request)
    {
        if($request->userid == "-1") {
              $users = User::wherenotin('user_type_id', [1,2,9])
                  ->where('status_work',1)
                  ->orderBy('last_name')
                  ->get();

            $last_month = date("Y-m", strtotime("first day of previous month"));
            $current_month = date("Y-m");
            // zwolnieni miesiąc temu
            $users_fired_last_month =  User::wherenotin('user_type_id', [1,2,9])
                ->where('status_work', '=', 0)
                ->where('end_work', 'like', $last_month.'%')
                ->orderBy('last_name')
                ->get();
            // zwolnieni w tym miesiącu
            $users_fired_current_month =  User::wherenotin('user_type_id', [1,2,9])
                ->where('status_work', '=', 0)
                ->where('end_work', 'like', $current_month.'%')
                ->orderBy('last_name')
                ->get();

            $merge_array = $users->merge($users_fired_last_month);
            $merge_array = $merge_array->merge($users_fired_current_month);

            return view('workhourscadre.viewHourCadre')
                  ->with('users',$merge_array->sortBy('last_name'));
        }
        $checkUser = User::find($request->userid);
        if ($checkUser == null) {
            return view('errors.404');
        }
        $user_type_info = UserTypes::find(Auth::user()->user_type_id);
        $what_show = $user_type_info->all_departments;
        $users = $this->getCadre($what_show);
        $month = $request->month;
        $userid = $request->userid;
        $myDepartment_info = Department_info::find(Auth::user()->department_info_id);
        $count_agreement= 0;
        Session::put('count_agreement', $count_agreement);
        $user_info = $this->user_info($userid,$month);

        $users = User::wherenotin('user_type_id', [1,2,9])
            ->where('status_work',1)
            ->orderBy('last_name')
            ->get();

        $last_month = date("Y-m", strtotime("first day of previous month"));
        $current_month = date("Y-m");
        // zwolnieni miesiąc temu
        $users_fired_last_month =  User::wherenotin('user_type_id', [1,2,9])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $last_month.'%')
            ->orderBy('last_name')
            ->get();
        // zwolnieni w tym miesiącu
        $users_fired_current_month =  User::wherenotin('user_type_id', [1,2,9])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $current_month.'%')
            ->orderBy('last_name')
            ->get();
        $merge_array = $users->merge($users_fired_last_month);
        $merge_array = $merge_array->merge($users_fired_current_month);

        $userTypesPermissionToEditSuccess = WorkHoursController::getUserTypesPermissionToEditSuccess();
        return view('workhourscadre.viewHourCadre')
            ->with('users',$merge_array->sortBy('last_name'))
            ->with('response_userid',$userid)
            ->with('response_month',$month)
            ->with('agreement',$count_agreement)
            ->with('response_user_info',$user_info)
            ->with('action_status',$what_show)
            ->with('userTypesPermissionToEditSuccess', $userTypesPermissionToEditSuccess);
    }
    public function viewHourPost(Request $request)
    {
        if ($request->userid == "-1") {
          $department_id = Auth::user()->department_info_id;
            $users = User::where('status_work',1)->wherein('user_type_id',[1,2,9])
                ->where('department_info_id',$department_id)->orderBy('last_name')->get();

            $last_month = date("Y-m", strtotime("first day of previous month"));
            $current_month = date("Y-m");
            // zwolnieni miesiąc temu
            $users_fired_last_month =  User::where('department_info_id', Auth::user()->department_info_id)
                ->whereIn('user_type_id', [1, 2])
                ->where('status_work', '=', 0)
                ->where('end_work', 'like', $last_month.'%')
                ->orderBy('last_name')
                ->get();
            // zwolnieni w tym miesiącu
            $users_fired_current_month =  User::where('department_info_id', Auth::user()->department_info_id)
                ->whereIn('user_type_id', [1, 2])
                ->where('status_work', '=', 0)
                ->where('end_work', 'like', $current_month.'%')
                ->orderBy('last_name')
                ->get();
            $merge_array = $users->merge($users_fired_last_month);
            $merge_array = $merge_array->merge($users_fired_current_month);

          return view('workhours.viewHour')
              ->with('users',$merge_array->sortBy('last_name'));
        }
        $checkUser = User::find($request->userid);
        if ($checkUser == null) {
            return view('errors.404');
        }
        $month = $request->month;
        $userid = $request->userid;
        $myDepartment_info = Department_info::find(Auth::user()->department_info_id);
        $count_agreement = Department_types::find($myDepartment_info->id_dep_type);
        $count_agreement= $count_agreement->count_agreement;
        Session::put('count_agreement', $count_agreement);
        $user_info = $this->user_info($userid,$month);
        $department_id = Auth::user()->department_info_id;

        $userTypesPermissionToEditSuccess = WorkHoursController::getUserTypesPermissionToEditSuccess();

        $users = User::where('status_work',1)->wherein('user_type_id',[1,2,9])
            ->where('department_info_id',$department_id)->orderBy('last_name')->get();

        $last_month = date("Y-m", strtotime("first day of previous month"));
        $current_month = date("Y-m");
        // zwolnieni miesiąc temu
        $users_fired_last_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $last_month.'%')
            ->orderBy('last_name')
            ->get();
        // zwolnieni w tym miesiącu
        $users_fired_current_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $current_month.'%')
            ->orderBy('last_name')
            ->get();
        $merge_array = $users->merge($users_fired_last_month);
        $merge_array = $merge_array->merge($users_fired_current_month);


        $user = User::find($userid);
        if ($user == null) {
            return view('errors.404');
        }

        $add_hour_success = false;
        if ($request->session()->has('add_hour_success')) {
            $add_hour_success = true;
        }
        $request->session()->forget('add_hour_success');
        return view('workhours.viewHour')
            ->with('users',$merge_array->sortBy('last_name'))
            ->with('response_userid',$userid)
            ->with('response_month',$month)
            ->with('agreement',$count_agreement)
            ->with('userTypesPermissionToEditSuccess', $userTypesPermissionToEditSuccess)
            ->with('response_user_info',$user_info)
            ->with('user',$user)
            ->with('add_hour_success', $add_hour_success);
    }

    public function deleteAcceptHour(Request $request)
    {
        if($request->ajax())
        {
            $id = $request->id;
            $checkWorkHour = Work_Hour::find($id);
            if ($checkWorkHour == null) {
                return 0;
            }
            Work_Hour::where('id', $id)
                ->update(['id_manager' => Auth::id(),
                    'success' => 0,
                    'accept_start' => null,
                    'accept_stop' => null,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status' => 6]);

            $log = array(
                'T' => 'Usunięcie godzin pracy',
                'id' => $id,
                'id_manager' => Auth::id(),
                'accept_start' => null,
                'accept_stop' => null,
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => 6
            );
            new ActivityRecorder($log,18,3);
            return 1;
        }
    }
    public function editAcceptHour(Request $request)
    {
        if($request->ajax())
        {
            $id = $request->id;
            $accept_start = $request->accept_start;
            $accept_stop = $request->accept_stop;
            $succes = $request->success;
            $id_manager = Auth::id();
            $checkWorkHour = Work_Hour::find($id);
            if ($checkWorkHour == null) {
                return 0;
            }

            $work_hour = Work_Hour::where('id', $id)->first();
            if(in_array(Auth::user()->user_type_id, WorkHoursController::getUserTypesPermissionToEditSuccess())){
                $work_hour->success = $succes;
            }
            $work_hour->status = 5;
            $work_hour->accept_start = $accept_start;
            $work_hour->accept_stop = $accept_stop;
            $work_hour->id_manager = $id_manager;
            $work_hour->updated_at = date('Y-m-d H:i:s');
            $work_hour->save();

            $data = [
                'T' => 'Edycja godzin pracy',
                'Id godzin pracy:' => $id,
                'accept_start' => $request->accept_start,
                'accept_stop' => $request->accept_stop,
                'success' => $request->success
            ];
            new ActivityRecorder($data,18,2);
            return 1;
        }
    }
    public function addAcceptHour(Request $request)
    {
        if($request->ajax())
        {
            $id_user_date = $request->id_user_date;
            $date = explode('/',$id_user_date);
            $accept_start = $request->accept_start;
            $accept_stop = $request->accept_stop;
            $succes = $request->success;
            $id_manager = Auth::id();
            $try_find_user = Work_Hour::where('id_user','=',$date[0])
                ->where('date','=',$date[1])->first();
            if(is_object($try_find_user)){
                $work_hour = $try_find_user;
            }else
                $work_hour = new Work_Hour;
            $work_hour->status = 4;
            $work_hour->accept_sec = 100;
            if(in_array(Auth::user()->user_type_id, WorkHoursController::getUserTypesPermissionToEditSuccess())){
                $work_hour->success = $succes;
            }
            $work_hour->date = $date[1];
            $work_hour->accept_start = $accept_start;
            $work_hour->accept_stop = $accept_stop;
            $work_hour->id_user = $date[0];
            $work_hour->id_manager = $id_manager;
            $work_hour->created_at = date('Y-m-d H:i:s');
            $work_hour->save();

            $log = array("T" => "Dodanie godzin");
            $log = array_merge($log, $work_hour->toArray());
            new ActivityRecorder($log,18,1);
        }
    }
    //******************ViewHour****************** Stop



    //******************Custom Functions******************
    function getUsers()
    {
        $users = User::where('users.department_info_id', '=', Auth::user()->department_info_id)
            ->where('users.user_type_id', '=', 1)
            ->where('users.status_work', '=', 1)
            ->get();
        return $users;
    }
    function getCadre($status)
    {
        if($status==0) {
            $users = User::where('users.department_info_id', '=', Auth::user()->department_info_id)
                ->where('users.user_type_id', '!=', 1)
                ->where('users.status_work', '=', 1)
                ->get();
        }else{
            $users = User::where('users.user_type_id', '!=', 1)
                ->where('users.status_work', '=', 1)
                ->get();
        }
        return $users;
    }
    function user_info($userid,$month)
    {
        return DB::table('work_hours')
            ->join('users', 'work_hours.id_user', '=', 'users.id')
            ->leftjoin('users as manager', 'work_hours.id_manager', '=', 'manager.id')
            ->select(DB::raw(
                'work_hours.id as id,
                    work_hours.status,
                    work_hours.id_manager,
                    users.rate,
                    manager.first_name,
                    manager.last_name,
                    work_hours.id_user,
                    work_hours.accept_start,
                    work_hours.accept_stop,
                    work_hours.register_start,
                    work_hours.register_stop,
                    work_hours.success,
                    work_hours.date,
                    SUBSTRING(SEC_TO_TIME(TIME_TO_SEC(accept_stop) - TIME_TO_SEC(accept_start) ),1,5) as time,
                    TIME_TO_SEC(accept_stop) - TIME_TO_SEC(accept_start) as second'))
            ->where('work_hours.id_user', '=', $userid)
            ->where('work_hours.status', '>', 2)
            ->where('date','like',$month.'%')->get();
    }
    private function getDepartment()
    {
        $departments = DB::table('department_info')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->select(DB::raw(
                'department_info.id,
                 department_info.id_dep,
                 department_info.id_dep_type,
                    departments.name as department_name,
                    department_info.type,
                    department_type.name as department_type_name
                   '))->get();
        return $departments;
    }


    private function timeDiff($start, $stop) {
        $t1 = strtotime(substr($start, 11, 20));
        $t2 = strtotime(substr($stop, 11, 20));

        return $t2 - $t1;
    }

}
