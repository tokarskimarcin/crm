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
        if($request->ajax()) {
            $start_date = $request->start_date;
            $stop_date = $request->stop_date;
            $department_id_info = $request->department_id_info;
            $query = DB::table('dkj')
                ->join('users', 'dkj.id_user', '=', 'users.id')
                ->select(DB::raw(
                    'dkj.id as id,
                    dkj.add_date,
                    dkj.phone,
                    dkj.campaign,
                    dkj.comment,
                    dkj.dkj_status,
                    dkj.comment_manager,
                    dkj.manager_status
                   '))
                ->where('dkj.department_info_id', '=', $department_id_info)
                ->whereBetween('add_date',[$start_date,$stop_date]);
            return datatables($query)->make(true);
        }
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
}
