<?php

namespace App\Http\Controllers;

use App\Department_info;
use App\Department_types;
use App\User;
use App\UserTypes;
use App\Work_Hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WorkHoursController extends Controller
{

    public function __construct()
    {
        $this->actuall_date = date("Y-m-d");
        $this->actuall_hour = date("H:i:s");
    }


    //******************acceptHour****************** START
    public function acceptHour()
    {
        $myDepartment_info = Department_info::find(Auth::user()->department_info_id);
        $count_agreement = Department_types::find($myDepartment_info->id_dep_type);
        if($count_agreement->count_agreement == 1) // czy zliczane są zagody
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
        $departments = $this->getDepartment();
        return view('workhourscadre.checkListCadre')->with('departments', $departments);;
    }

    public function datatableAcceptHour(Request $request) // akceptacja godzin dla konsultantów
    {
        if($request->ajax()) {
            $start_date = $request->start_date;
            $stop_date = $request->stop_date;
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
                    SEC_TO_TIME(TIME_TO_SEC(register_stop) - TIME_TO_SEC(register_start) ) as time'))
                ->where('work_hours.status', '=', 2)
                ->where('users.department_info_id', '=', Auth::user()->department_info_id)
                ->where('users.user_type_id', '=', 1)
                ->where('work_hours.id_manager', '=', null)
                ->whereBetween('date',[$start_date,$stop_date]);
            return datatables($query)->make(true);
        }
    }

    public function datatableAcceptHourCadre(Request $request) // akceptacja godzin dla kadry
    {
        if($request->ajax()) {
            $start_date = $request->start_date;
            $stop_date = $request->stop_date;
            $dep_info = $request->dep_info;
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
                    SEC_TO_TIME(TIME_TO_SEC(register_stop) - TIME_TO_SEC(register_start) ) as time'))
                ->where('work_hours.status', '=', 2);
            if($dep_info != '*')
            {
                $query->where('users.department_info_id', '=', $dep_info);
            }
            $query->where('users.user_type_id','!=',1)
                ->where('work_hours.id_manager', '=', null)
                ->whereBetween('date',[$start_date,$stop_date]);
            return datatables($query)->make(true);
        }
    }

    public function datatableCheckList(Request $request) // akceptacja godzin dla kadry
    {
        if($request->ajax()) {
            $start_date = $request->start_date;
            $dep_info = $request->dep_info;
            $query = DB::table('users')
                ->leftjoin("work_hours", function ($join) use ($start_date) {
                    $join->on("users.id", "=", "work_hours.id_user")
                    ->where("work_hours.date", "=", $start_date);
                })->select(DB::raw(
                    "STR_TO_DATE('$start_date', '%Y-%m-%d') as start_date,
                     work_hours.id as id,
                    users.first_name,
                    users.last_name,
                    work_hours.click_start,
                    work_hours.click_stop,
                    work_hours.register_start,
                    work_hours.register_stop,
                    work_hours.date,
                    SEC_TO_TIME(TIME_TO_SEC(register_stop) - TIME_TO_SEC(register_start) ) as time"));
            if($dep_info != '*')
            {
                $query->where('users.department_info_id', '=', $dep_info);
            }
            $query->where('users.user_type_id','!=',1);
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
            $succes = $request->succes;
            $id_manager = Auth::id();
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
                        'success' => $succes]);}

            }else
            {
                Work_Hour::where('id', $id)
                    ->update(['id_manager' => $id_manager,
                        'success' => $succes,
                        'accept_start' => $register_start,
                        'accept_stop' => $register_stop,
                        'status' => 3]);
            }
        }
    }
    //******************acceptHour****************** Stop

    //******************RegisterHour****************** Start
    public function registerHour(Request $request)
    {
        if($request->ajax())
        {
            $time_register_start = $request->register_start;
            $time_register_stop = $request->register_stop;
            Work_Hour::where('id_user', Auth::id())
                ->where('date',$this->actuall_date)
                ->update(['register_start' => $time_register_start,'register_stop' => $time_register_stop]);

            $request->session()->flash('message', 'New customer added successfully.');
            $request->session()->flash('message-type', 'success');
            return response()->json(['status'=>'Hooray']);
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
        $users = $this->getUsers();
        return view('workhours.viewHour')
            ->with('users',$users);
    }
    public function viewHourGetCadre()
    {
        $user_type_info = UserTypes::find(Auth::user()->user_type_id);
        $what_show = $user_type_info->all_departments;
        $users = $this->getCadre($what_show);
        return view('workhourscadre.viewHourCadre')
            ->with('users',$users);
    }
    public function viewHourPostCadre(Request $request)
    {
        $user_type_info = UserTypes::find(Auth::user()->user_type_id);
        $what_show = $user_type_info->all_departments;
        $users = $this->getCadre($what_show);
        $month = $request->month;
        $userid = $request->userid;
        $myDepartment_info = Department_info::find(Auth::user()->department_info_id);
        $count_agreement = Department_types::find($myDepartment_info->id_dep_type);
        $count_agreement= $count_agreement->count_agreement;
        Session::put('count_agreement', $count_agreement);
        $user_info = $this->user_info($userid,$month);
        return view('workhourscadre.viewHourCadre')
            ->with('users',$users)
            ->with('response_userid',$userid)
            ->with('response_month',$month)
            ->with('agreement',$count_agreement)
            ->with('response_user_info',$user_info)
            ->with('action_status',$what_show);
    }
    public function viewHourPost(Request $request)
    {
        $users = $this->getUsers();
        $month = $request->month;
        $userid = $request->userid;
        $myDepartment_info = Department_info::find(Auth::user()->department_info_id);
        $count_agreement = Department_types::find($myDepartment_info->id_dep_type);
        $count_agreement= $count_agreement->count_agreement;
        Session::put('count_agreement', $count_agreement);
        $user_info = $this->user_info($userid,$month);

        return view('workhours.viewHour')
            ->with('users',$users)
            ->with('response_userid',$userid)
            ->with('response_month',$month)
            ->with('agreement',$count_agreement)
            ->with('response_user_info',$user_info);
    }

    public function deleteAcceptHour(Request $request)
    {
        if($request->ajax())
        {
            $id = $request->id;
                Work_Hour::where('id', $id)
                    ->update(['id_manager' => Auth::id(),
                        'success' => 0,
                        'accept_start' => null,
                        'accept_stop' => null,
                        'status' => 4]);
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
                Work_Hour::where('id', $id)
                    ->update(['id_manager' => $id_manager,
                        'success' => $succes,
                        'accept_start' => $accept_start,
                        'accept_stop' => $accept_stop,
                        'status' => 3]);
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
            $work_hour = new Work_Hour;
            $work_hour->status = 3;
            $work_hour->accept_sec = 0;
            $work_hour->success = $succes;
            $work_hour->date = $date[1];
            $work_hour->accept_start = $accept_start;
            $work_hour->accept_stop = $accept_stop;
            $work_hour->id_user = $date[0];
            $work_hour->id_manager = $id_manager;
            $work_hour->save();
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

}
