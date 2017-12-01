<?php

namespace App\Http\Controllers;

use App\Department_info;
use App\Department_types;
use App\Departments;
use App\LinkGroups;
use App\Links;
use App\PrivilageRelation;
use App\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ActivityRecorder;
use Illuminate\Support\Facades\Redirect;
use Session;

class AdminController extends Controller
{
    public function admin_privilage()
    {
        $link_groups = LinkGroups::all();
        $Department_types = Department_types::all();
        $Department_info = $this->getDepartment();
        $Departments = Departments::all();
        $links =  DB::table('links')
            ->leftjoin('link_groups', 'link_groups.id', '=', 'links.group_link_id')
            ->select(DB::raw('
              links.id,
              links.link,
              links.name,
              link_groups.name as link_groups_name'
            ))
            ->get();

        return view('admin.admin_privilage')
            ->with('groups',$link_groups)
            ->with('links',$links)
            ->with('department_types',$Department_types)
            ->with('department_info',$Department_info)
            ->with('departments',$Departments);
    }
    public function admin_privilage_show($id)
    {
        $link_groups = LinkGroups::all();
        $users_type = UserTypes::all();
        $link =  DB::table('links')
            ->leftjoin('link_groups', 'link_groups.id', '=', 'links.group_link_id')
            ->leftjoin('privilage_relation', 'links.id', '=', 'privilage_relation.link_id')
            ->leftjoin('privilage_user_relation', 'links.id', '=', 'privilage_user_relation.link_id')
            ->select(DB::raw('
              links.id as id,
              links.link,
              links.group_link_id,
              links.name,
              link_groups.name as link_groups_name,
              privilage_relation.user_type_id as relation_user_type_id'
            ))
            ->where('links.id',$id)
            ->get();
        $link_info = $link->first();

        return view('admin.admin_privilage_show')
            ->with('groups',$link_groups)
            ->with('link',$link)
            ->with('users_type',$users_type)
            ->with('link_info',$link_info);
    }
    public function admin_privilage_edit($id,Request $request)
    {
            $data = [];
            $link = Links::findOrFail($id);
            $link->link = $request->link_adress;
            $link->name = $request->link_name;
            $link->group_link_id = $request->link_group;
            $link->save();
            $user_tab = $request->link_privilages;
            if($request->link_privilages == null )
            {
                PrivilageRelation::where('link_id', $id)
                ->delete();
            }else{
                PrivilageRelation::where('link_id', $id)
                ->wherenotin('link_id',$request->link_privilages)
                ->delete();
                foreach ($user_tab as $item) {
                    PrivilageRelation::updateOrCreate(array('user_type_id'=>$item,'link_id'=>$id));
                    $data['item' . $item] = 'id' . $id;
                }

            }
            $data['Zmiana uprawnień grup i użytkowników'] = '';
            $data['Link name'] = $request->link_name;
            $data['Link adress'] = $request->link_adress;
            $data['Link group'] = $request->link_goup;

            new ActivityRecorder(3, $data);

            Session::flash('message_ok', "Zmiany zapisano!");
            return Redirect::back();
    }


    private function getDepartment()
    {
        $departments = DB::table('department_info')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->select(DB::raw(
                'department_info.*,
                    departments.name as department_name,
                    department_info.type,
                    department_type.name as department_type_name
                   '))->orderBy('department_name', 'asc')->get();
        return $departments;
    }

    public function lockerGet() {
        $department_info = Department_info::all();

        return view('admin.locker')
            ->with('department_info', $department_info);
    }

    public function lockerPost(Request $request) {
        if($request->ajax()) {
            $department_info_id = Department_info::find($request->department_info_id);
            $department_info_id->blocked = $request->type;
            $department_info_id->save();
        }
    }

    public function addDepartmentGet() {
        $department_types = Department_types::all();

        return view('admin.addDepartment')
            ->with('department_types', $department_types);
    }

    public function addDepartmentPost(Request $request) {
        $department = new Departments();

        $department->name = $request->city;
        $department->desc = $request->desc;
        $department->save();

        $departments = DB::table('departments')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->get();

        $id_dep = $departments[0]->id;

        $department_info = new Department_info();

        $department_info->id_dep = $id_dep;
        $department_info->id_dep_type = $request->id_dep_type;
        $department_info->size = ($request->size != null) ? $request->size : 0 ;
        $department_info->commission_avg = ($request->commission_avg) ? $request->commission_avg : 0 ;
        $department_info->commission_hour = ($request->commission_hour) ? $request->commission_hour : 0 ;
        $department_info->commission_step = ($request->commission_step) ? $request->commission_step : 0 ;
        $department_info->commission_start_money = ($request->commission_start_money) ? $request->commission_start_money : 0 ;
        $department_info->commission_janky = ($request->commission_janky) ? $request->commission_janky : 0 ;
        $department_info->dep_aim = ($request->dep_aim) ? $request->dep_aim : 0 ;
        $department_info->dep_aim_week = ($request->dep_aim_week) ? $request->dep_aim_week : 0 ;
        $department_info->type = ($request->type != 'Wybierz') ? $request->type : 0 ;
        $department_info->janky_system_id = ($request->janky_system_id) ? $request->janky_system_id : 0 ;
        $department_info->blocked = 0;

        $department_info->save();

        Session::flash('message_ok', "Oddział został dodany!");
        return Redirect::back();

    }

}
