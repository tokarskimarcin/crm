<?php

namespace App\Http\Controllers;

use App\Agencies;
use App\Department_info;
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
    public function dkjRaportPost(Request $request)
    {
        $departments = $this->getDepartment();
        return view('dkj.dkjRaport')
        ->with('departments',$departments)
        ->with('select_department_id_info',$request->department_id_info)
        ->with('select_start_date',$request->start_date)
        ->with('select_stop_date',$request->stop_date)
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
                    user.first_name as user_first_name,
                    user.id as user_id,
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
                   '))->whereBetween('add_date',[$start_date,$stop_date]);

            // <0 Badania >0 Wysyłka
            if($type =='Badania/Wysyłka')
            {
                if($save_deaprtemnt_inf<0)
                {
                    $query->where('dkj.department_info_id', '=', $department_id_info)
                        ->where('user.dating_type', '=', 0);
                }else
                {
                    $query->where('dkj.department_info_id', '=', $department_id_info)
                        ->where('user.dating_type', '=', 1);
                }
            }
            else
            {
                $query->where('dkj.department_info_id', '=', $department_id_info);

            }
            return datatables($query)->make(true);
        }
    }
    public function dkjRaportSave(Request $request)
    {
        $intpu = $request->all();
        $action = $request->input('action');
        return $request->input('data');
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
                            ->where('users.dating_type', '=', 0);
                    } else {
                        $query->where('department_info.id', '=', $department_id_info)
                            ->where('users.dating_type', '=', 1);
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
