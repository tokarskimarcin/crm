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
use Illuminate\Support\Facades\Auth;
use App\ActivityRecorder;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\User;
use App\Notifications;
use Illuminate\Support\Facades\URL;
use App\Firewall;
use App\FirewallPrivileges;
use App\UserTest;

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
        $checkLink = Links::find($id);
        if ($checkLink == null) {
            return view('errors.404');
        }
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
        $url_array = explode('/',URL::previous());
        $urlValidation = end($url_array);
        if ($urlValidation != $id) {
            return view('errors.404');
        }
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
            ->whereNotIn('user_type_id',$request->link_privilages)
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
                   '))->orderBy('department_info.id', 'asc')->get();
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
            if ($department_info_id == null) {
                return 0;
            } else {
                $department_info_id->blocked = $request->type;
                $department_info_id->save();
                return 1;
            }
        }
    }

    public function addDepartmentGet() {
        $department_types = Department_types::all();
        $departments = Departments::all();

        return view('admin.addDepartment')
            ->with('departments', $departments)
            ->with('department_types', $department_types);
    }

    public function addDepartmentPost(Request $request) {
        $department_info = new Department_info();

        //tutaj sprawdzenie czy oddział jest dodawany do istniejącego miasta czy stworzono  nowy
        if ($request->department != '-1') {
            $id_dep = $request->department;
        } else {
            $department = new Departments();

            $department->name = $request->city;
            $department->desc = $request->desc;
            $department->save();

            $departments = DB::table('departments')
                ->orderBy('id', 'desc')
                ->limit(1)
                ->get();

            $id_dep = $departments[0]->id;
        }

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
        $department_info->type = ($request->type != 'Wybierz') ? $request->type : '' ;
        $department_info->janky_system_id = ($request->janky_system_id) ? $request->janky_system_id : 0 ;
        $department_info->pbx_id = ($request->pbx_id) ? $request->pbx_id : 0 ;
        $department_info->blocked = 0;

        $department_info->save();

        new ActivityRecorder(3, "Dodano oddział o numerze ID: " . $id_dep);

        Session::flash('message_ok', "Oddział został dodany!");
        return Redirect::back();

    }

    //edycja oddziałów
    public function editDepartmentGet() {
        $department_info = Department_info::all();

        return view('admin.editDepartment')
            ->with('department_info', $department_info);
    }

    //edycja oddziałów
    public function editDepartmentPost(Request $request) {
        //$request->type okkreśla czy oddział jest wybierany czy edytowany
        //1 - wybranie oddziału
        //2 - edycja oddziału
        $department_info = Department_info::all();
        $department_types = Department_types::all();
        $selected_department = Department_info::find($request->selected_department_info_id);

        if ($selected_department == null || ($request->post_type != 1 && $request->post_type != 2)) {
            return view('errors.404');
        }
        if ($request->post_type == 1 ) {

          return view('admin.editDepartment')
              ->with('selected_department', $selected_department)
              ->with('department_types', $department_types)
              ->with('department_info', $department_info);
        }

        if ($request->post_type == 2) {
            //Edycja opisu oddziału znajsuje sie w Departments
            $departments = Departments::find($selected_department->departments->id);
            $departments->desc = $request->desc;
            $departments->save();

            $selected_department->size = ($request->size != null) ? $request->size : 0 ;
            $selected_department->commission_avg = ($request->commission_avg) ? $request->commission_avg : 0 ;
            $selected_department->commission_hour = ($request->commission_hour) ? $request->commission_hour : 0 ;
            $selected_department->commission_step = ($request->commission_step) ? $request->commission_step : 0 ;
            $selected_department->commission_start_money = ($request->commission_start_money) ? $request->commission_start_money : 0 ;
            $selected_department->commission_janky = ($request->commission_janky) ? $request->commission_janky : 0 ;
            $selected_department->dep_aim = ($request->dep_aim) ? $request->dep_aim : 0 ;
            $selected_department->dep_aim_week = ($request->dep_aim_week) ? $request->dep_aim_week : 0 ;
            $selected_department->type = ($request->type != 'Wybierz') ? $request->type : '' ;
            $selected_department->janky_system_id = ($request->janky_system_id) ? $request->janky_system_id : 0 ;
            $selected_department->pbx_id = ($request->pbx_id != null) ? $request->pbx_id : 0 ;

            $selected_department->save();
        }

        $data = [
            'Edycja danych oddziału' => '',
            'Id oddziału' => $request->selected_department_info_id
        ];

        new ActivityRecorder(3, $data);

        Session::flash('message_ok', "Zmiany zapisano pomyślnie!");
        return Redirect::back();
    }

    public function multipleDepartmentGet() {
        $users = User::where('status_work', '=', 1)
            ->orderBy('last_name')
            ->get();

        return view('admin.multipleDepartments')
            ->with('users', $users);
    }

    public function multipleDepartmentPost(Request $request) {
        if($request->request_type == 'select_user'){
          $users = User::where('status_work', '=', 1)
              ->orderBy('last_name')
              ->get();

          $user = User::find($request->user_department);
          if ($user == null) {
              return view('errors.404');
          }
          $user_id_post = $user->id;

          $user_dep = DB::table('multiple_departments')
              ->select(DB::raw('
                  department_info_id
              '))
              ->where('user_id', '=', $user->id)
              ->get();

          $department_info = Department_info::all();

          return view('admin.multipleDepartments')
              ->with('department_info', $department_info)
              ->with('user_id_post', $user_id_post)
              ->with('user_dep', $user_dep)
              ->with('users', $users);

        } else if ($request->request_type == 'save_changes') {
          $userCheck = User::find($request->user_department_post);
          if ($userCheck == null) {
              return view('errors.404');
          }
          $department_info = Department_info::orderBy('id', 'desc')->limit(1)->get();
          $last_id = $department_info[0]->id;

          DB::table('multiple_departments')
              ->where('user_id', '=', $request->user_department_post)
              ->delete();

          for($i = 1; $i <= $last_id; $i++) {
              $actual_dep = 'dep' . $i;
              if($request->$actual_dep == $i){
                DB::table('multiple_departments')->insert(
                  ['user_id' => $request->user_department_post, 'department_info_id' => $request->$actual_dep]
                );
              }
          }
          $users = User::where('status_work', '=', 1)
              ->orderBy('last_name')
              ->get();

          return view('admin.multipleDepartments')
              ->with('success', 'Zmiany zapisano pomyślnie!')
              ->with('users', $users);
        }
    }

    public function createLinkGet(){
        $link_groups = LinkGroups::all();

        return view('admin.create_link')
            ->with('link_groups', $link_groups);
    }

    public function createLinkPost(Request $request){
        $link = new Links();
        $linkGroupCheck = LinkGroups::find($request->group_link_id);
        if ($linkGroupCheck == null) {
            return view('errors.404');
        }

        $link->name = $request->name;
        $link->link = $request->link;
        $link->group_link_id = $request->group_link_id;

        $link->save();

        Session::flash('message_ok', "Link został dodany!");
        return Redirect::back();
    }

    public function firewallGet() {
        $firewall = Firewall::all();

        return view('admin.firewall')
            ->with('firewall', $firewall);
    }

    public function firewallPost(Request $request) {
        $firewall = new Firewall();

        if ($request->ip_status != 1 && $request->ip_status != 2) {
            return view('errors.404');
        }

        $firewall->ip_address = $request->new_ip;
        $firewall->whitelisted = $request->ip_status;
        $firewall->save();

        Session::flash('message_ok', "Adres IP został dodany!");
        return Redirect::back();
    }

    public function firewallPrivilegesGet() {
        $firewall_privileges = FirewallPrivileges::all();
        $users = User::whereNotIn('user_type_id', [1,2])
            ->where('status_work', '=', 1)
            ->orderBy('last_name')
            ->get();

        return view('admin.firewallPrivileges')
            ->with('firewall_privileges', $firewall_privileges)
            ->with('users', $users);
    }

    public function firewallPrivilegesPost(Request $request) {
        $obj = new FirewallPrivileges();

        $obj->user_id = $request->user_selected;
        $obj->save();

        Session::flash('message_ok', "Użytkownik został dodany!");
        return Redirect::back();
    }

    public function firewallDeleteUser(Request $request) {
        if ($request->ajax()) {
            $user = User::find($request->user_id);

            if ($user == null) {
                return 0;
            } else {
                FirewallPrivileges::where('user_id', '=', $request->user_id)->delete();
                return 1;
            }
        }
    }

    public function check_all_tests() {                                                  return view('testorm');
        return view('admin.all_tests');
    }

    public function datatableAllTests(Request $request) {
        $data = DB::table('user_tests')
            ->select(DB::raw('
                user_tests.*,
                first_name,
                last_name
            '))
            ->join('users', 'users.id', 'user_tests.cadre_id')
            ->get();

        return datatables($data)->make(true);
    }

    public function show_test_for_admin($id) {
        $test = UserTest::find($id);

        if ($test == null) {
            return view('errors.404');
        }
        
        return view('tests.testResult')
            ->with('test', $test);
    }

}
