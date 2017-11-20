<?php

namespace App\Http\Controllers;

use App\Agencies;
use App\Department_info;
use App\Dkj;
use App\JankyPenatlyProc;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DkjController extends Controller
{
    public function dkjRaportGet()
    {
        $departments =  $this->getDepartment();
        return view('dkj.dkjRaport')->with('departments',$departments);
    }
    public function jankyStatistics()
    {
        $actual_month = date("Y-m");
        $user_dkj_info = DB::table('dkj')
            ->select(DB::raw(
                'Date(add_date) as add_date,
                SUM(CASE WHEN dkj_status = 0 or deleted = 1 THEN 1 ELSE 0 END) as good ,
                SUM(CASE WHEN dkj_status = 1 AND deleted = 0 THEN 1 ELSE 0 END) as bad'))
            ->where('id_user', Auth::user()->id)
            ->where('add_date','like',$actual_month.'%')
            ->groupBy(DB::raw('Date(add_date)'))
            ->get();
        $department_info = Department_info::find(Auth::user()->department_info_id);
        $janky_system = JankyPenatlyProc::where('system_id',$department_info->janky_system_id)->get();
        return view('dkj.jankyStatistics')->with('user_info', $user_dkj_info)
            ->with('janky_system', $janky_system);
    }
    public function dkjVerificationGet()
    {
        return view('dkj.dkjVerification');
    }

    public function jankyVerificationGet()
    {
        $departments =  $this->getDepartment();

        return view('dkj.jankyVerification')
            ->with('departments',$departments);
    }

    public function jankyVerificationPOST(Request $request)
    {
        $departments = $this->getDepartment();
        $department_id_info = $request->department_id_info;
        $dating_type = 0;
        if($department_id_info<0)
        {
            $department_id_info = $department_id_info*(-1);
            $dating_type = 1;
        }
        $users = User::where('department_info_id',$department_id_info)
                ->where('user_type_id',1)
                ->where('dating_type',$dating_type)
                ->get();
        return view('dkj.jankyVerification')
            ->with('departments',$departments)
            ->with('select_department_id_info',$request->department_id_info)
            ->with('select_start_date',$request->start_date)
            ->with('select_stop_date',$request->stop_date)
            ->with('users',$users)
            ->with('show_raport',1);
    }

    public function departmentStatisticsGet()
    {
        $departments = Department_info::all();
        $users = User::where('department_info_id',Auth::user()->department_info_id);
        return view('dkj.departmentStatistics')
            ->with('departments',$departments)
            ->with('users',$users);
    }

    public function departmentStatisticsPOST(Request $request)
    {
        $user_dkj_info = Dkj::whereHas('user', function ($query) {
                $query->where('department_info_id', 'like', Auth::user()->department_info_id);
            })->
            selectRaw(
                'Date(add_date) as add_date,
                SUM(CASE WHEN dkj_status = 0 or deleted = 1 THEN 1 ELSE 0 END) as good ,
                SUM(CASE WHEN dkj_status = 1 AND deleted = 0 THEN 1 ELSE 0 END) as bad')
            ->where('add_date','like',$request->month.'%')
            ->groupBy(DB::raw('Date(add_date)'))
            ->get();
        return view('dkj.departmentStatistics')
            ->with('user_info', $user_dkj_info)
            ->with('month',$request->month);
    }



    public function datatableDkjVerification(Request $request)
    {
        $query = DB::table('dkj')
            ->join('users as user', 'dkj.id_user', '=', 'user.id')
            ->leftjoin('users as manager', 'dkj.id_manager', '=', 'manager.id')
            ->join('users as dkj_user', 'dkj.id_dkj', '=', 'dkj_user.id')
            ->select(DB::raw(
                'dkj.id as id,
                DATE_ADD(dkj.add_date, INTERVAL 2 DAY) as expiration_date,
                user.id as id_user,
                user.first_name as user_first_name,
                user.last_name as user_last_name,
                dkj.add_date,
                dkj.phone,
                dkj.campaign,
                dkj.comment,
                dkj.comment_manager,
                dkj.manager_status
                '))->where('dkj.dkj_status',1)
                 ->where('dkj.deleted',0)
                 ->where('dkj.manager_status',null)
                 ->where('user.department_info_id',Auth::user()->department_info_id);
        return datatables($query)->make(true);
    }

    public function datatableShowDkjVerification(Request $request)
    {
        $query = DB::table('dkj')
            ->join('users as user', 'dkj.id_user', '=', 'user.id')
            ->leftjoin('users as manager', 'dkj.id_manager', '=', 'manager.id')
            ->join('users as dkj_user', 'dkj.id_dkj', '=', 'dkj_user.id')
            ->select(DB::raw(
                'dkj.id as id,
                DATE_ADD(dkj.add_date, INTERVAL 2 DAY) as expiration_date,
                user.id as id_user,
                user.first_name as user_first_name,
                user.last_name as user_last_name,
                dkj.add_date,
                dkj.phone,
                dkj.campaign,
                dkj.comment,
                dkj.comment_manager,
                dkj.manager_status,
                dkj.dkj_status
                '))->where('dkj.dkj_status',1)
            ->where('dkj.deleted',0)
            ->where('dkj.manager_status','!=',null)
            ->where('user.department_info_id',Auth::user()->department_info_id);
        return datatables($query)->make(true);
    }

    public function saveDkjVerification(Request $request)
    {
             $dkj_id = $request->id;
             $manager_comment = $request->manager_coment;
             $manager_status = $request->manager_status;
             $dkj_record = Dkj::find($dkj_id);
             $dkj_record->comment_manager = $manager_comment;
             $dkj_record->manager_status = $manager_status;
             $dkj_record->date_manager = date('Y-m-d H:i:s');
             $dkj_record->id_manager = Auth::user()->id;
             $dkj_record->save();
    }
    public function dkjRaportPost(Request $request)
    {
        $departments = $this->getDepartment();
        $department_id_info = $request->department_id_info;
        $dating_type = 0;
        if($department_id_info<0)
        {
            $department_id_info = $department_id_info*(-1);
            $dating_type = 1;
        }
        $users = User::where('department_info_id',$department_id_info)
            ->where('user_type_id',1)
            ->where('dating_type',$dating_type)
            ->get();

        return view('dkj.dkjRaport')
        ->with('departments',$departments)
        ->with('select_department_id_info',$request->department_id_info)
        ->with('select_start_date',$request->start_date)
        ->with('select_stop_date',$request->stop_date)
        ->with('users',$users)
        ->with('show_raport',1);
    }

    public function datatableDkjRaport(Request $request)
    {
        // zmian -1 na 1 gdy oddział jest wysyłka/badania, pojebane ale skuteczne
        if($request->ajax()) {
            $start_date = $request->start_date;
            $stop_date = $request->stop_date;
            $department_id_info = $request->department_id_info;
            $save_deaprtemnt_inf = $department_id_info;
            if($department_id_info<0)
            {
                $department_id_info=$department_id_info*(-1);
            }
            $type = Department_info::find($department_id_info);
            $type = $type->type;
            $query = DB::table('dkj')
                ->join('users as user', 'dkj.id_user', '=', 'user.id')
                ->leftjoin('users as manager', 'dkj.id_manager', '=', 'manager.id')
                ->join('users as dkj_user', 'dkj.id_dkj', '=', 'dkj_user.id')
                ->select(DB::raw(
                    'dkj.id as id,                   
                    user.id as id_user,
                    user.first_name as user_first_name,
                    user.last_name as user_last_name,
                    manager.first_name as manager_first_name,
                    manager.last_name as manager_last_name,
                    dkj_user.first_name as dkj_user_first_name,
                    dkj_user.last_name as dkj_user_last_name,
                    dkj.add_date,
                    dkj.phone,
                    dkj.campaign,
                    dkj.comment,
                    dkj.dkj_status,
                    dkj.comment_manager,
                    dkj.manager_status
                   '))
                ->where('deleted',0)
                ->where('add_date','>=',$start_date.' 00:00:00')
                ->where('add_date','<=',$stop_date.' 23:00:00')
                ->where('user.department_info_id', '=', $department_id_info);

            //  -1 Wysyłka 0 Badania
            if($type =='Badania/Wysyłka')
            {
                if($save_deaprtemnt_inf<0)
                {
                    $query->where('user.dating_type', '=', 1);
                }else
                {
                    $query->where('user.dating_type', '=', 0);
                }
            }
            if($request->type_verification == 1)
            {
                $query->where('dkj.manager_status','!=',null)
                    ->where('dkj_status',1);
            }

            return datatables($query)->make(true);
        }
    }
    public function dkjRaportSave(Request $request)
    {

        if ($request->action == 'create') {
            $dkj = new DKJ();
            $dkj->id_dkj = Auth::user()->id;
        }
        if ($request->action == 'edit') {
            $dkj =Dkj::find($request->id);
            $dkj->edit_dkj = Auth::user()->id;
            $dkj->edit_date = date('Y-m-d H:i:s');
        }
        if($request->action == 'create' || $request->action == 'edit')
        {
            $dkj->id_user = $request->id_user;
            $dkj->phone = $request->phone;
            $dkj->dkj_status = $request->dkj_status;
            $dkj->comment = $request->comment;
            $dkj->campaign = $request->campaign;
            $dkj->save();
        }
        if ($request->action == 'remove') {
                $dkj = Dkj::find($request->id);
                $dkj->deleted = 1;
                $dkj->save();
        }
        return 'ok';
    }
    private function getDepartment()
    {
        $departments = DB::table('department_info')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->select(DB::raw(
                'department_info.id,
                    departments.name as department_name,
                    department_info.type,
                    department_type.name as department_type_name
                   '))->get();
        return $departments;
    }

    public function getUser(Request $request)
    {
        if($request->ajax())
        {
            $department_id_info = $request->department_info;
            $save_deaprtemnt_inf = $department_id_info;

            if($department_id_info!=0) {
                if ($department_id_info < 0) {
                    $department_id_info = $department_id_info * (-1);
                }
                $type = Department_info::find($department_id_info);
                $type = $type->type;
                $query = DB::table('users')
                    ->join('department_info', 'department_info.id', '=', 'users.department_info_id')
                    ->select(DB::raw(
                        'users.first_name,
                        users.last_name,
                        users.id
                   '));
                if ($type == 'Badania/Wysyłka') {
                    if ($save_deaprtemnt_inf < 0) {
                        $query->where('department_info.id', '=', $department_id_info)
                            ->where('users.dating_type', '=', 1);
                    } else {
                        $query->where('department_info.id', '=', $department_id_info)
                            ->where('users.dating_type', '=', 0);
                    }
                } else {
                    $query->where('department_info.id', '=', $department_id_info);
                }
                return $query->where('users.user_type_id', '=', 1)->get();
            }else
            return 0;
        }
    }

}
