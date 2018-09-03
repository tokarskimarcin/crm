<?php
/**
 * Created by PhpStorm.
 * User: shuwax
 * Date: 03.09.2018
 * Time: 11:39
 */

namespace App\Http\Controllers\AdminPanel;


use App\ActivityRecorder;
use App\Department_info;
use App\Department_types;
use App\Departments;
use App\MultipleDepartments;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class DepartmentsController
{
    /** Show form new department
     * @return mixed
     */
    public function addDepartmentGet() {
        $department_types               = Department_types::all();
        $departments                    = Departments::all();
        $menagers                       = User::whereIn('user_type_id', ['7','15'])
            ->where('status_work', '=', '1')->get();
        $hr                             = User::where('user_type_id', '=', '5')
            ->where('status_work', '=', '1')->get();
        $hrDirectors                    = User::where('user_type_id', '=', '14')
            ->where('status_work', '=', '1')->get();
        $regionalManagers               = User::where('user_type_id', '=', '17')
            ->where('status_work', '=', '1')->get();
        $regionalManagersInstructors    = User::where('user_type_id', '=', 21)
            ->where('status_work', '=', 1)->get();
        return view('admin.addDepartment')
            ->with('departments', $departments)
            ->with('department_types', $department_types)
            ->with('menagers', $menagers)
            ->with('hrEmployee', $hr)
            ->with('hrDirectors', $hrDirectors)
            ->with('regionalManagers', $regionalManagers)
            ->with('regionalManagersInstructors', $regionalManagersInstructors);
    }

    /**
     * Save new Department
     * @param Request $request
     * @return mixed
     */
    public function addDepartmentPost(Request $request) {
        $department_info = Department_info::addModifyDepartment($request);
        if(!empty($department_info)){
            new ActivityRecorder("Dodano oddział o numerze ID: " . $department_info->id,51,1);
            Session::flash('message_ok', "Oddział został dodany!");
            return Redirect::back();
        }else{
            Session::flash('message_error', "Problem podczas wykonywania SQL");
            return Redirect::back();
        }
    }

    /**
     * Show list of department to edit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editDepartmentGet() {
        $department_info = Department_info::all();
        $user = User::all();
        return view('admin.editDepartment')
            ->with('department_info', $department_info)
            ->with('user', $user);
    }

    /** Show or edit department
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editDepartmentPost(Request $request) {
        //$request->type okkreśla czy oddział jest wybierany czy edytowany
        //1 - wybranie oddziału
        //2 - edycja oddziału
        $menagers                       = User::whereIn('user_type_id', ['7','15','17'])->where('status_work', '=', '1')->get();
        $hrDirectors                    = User::where('user_type_id', '=', '14')->where('status_work', '=', '1')->get();
        $hrEmployee                     = User::where('user_type_id', '=', '5')->where('status_work', '=', '1')->get();
        $regionalManagers               = User::where('user_type_id', '=', '17')->where('status_work', '=', '1')->get();
        $department_info                = Department_info::all();
        $department_types               = Department_types::all();
        $selected_department            = Department_info::find($request->selected_department_info_id);
        $regionalManagersInstructors    = User::where('user_type_id', '=', 21)->where('status_work', '=', 1)->get();

        if ($selected_department == null || ($request->post_type != 1 && $request->post_type != 2)) {
            return view('errors.404');
        }
        if ($request->post_type == 1 ) {

            return view('admin.editDepartment')
                ->with('selected_department', $selected_department)
                ->with('department_types', $department_types)
                ->with('department_info', $department_info)
                ->with('hrEmployee', $hrEmployee)
                ->with('menagers', $menagers)
                ->with('hrDirectors', $hrDirectors)
                ->with('regionalManagers', $regionalManagers)
                ->with('regionalManagersInstructors', $regionalManagersInstructors);
        }

        if ($request->post_type == 2) {
            //Edycja opisu oddziału znajsuje sie w Departments
            $departments = Departments::find($selected_department->departments->id);
            $departments->desc = $request->desc;
            $departments->save();
            //Change user type for all consultant
            if($request->type != 'Wybierz' && $request->type != 'Badania/Wysyłka')
            {
                if($selected_department->type != $request->type){
                    if( $request->type == 'Badania'){
                        DB::table('users')
                            ->where('department_info_id',$request->selected_department_info_id)
                            ->where('user_type_id',1)
                            ->update(['dating_type' => 0]);
                    }else{
                        DB::table('users')
                            ->where('department_info_id',$request->selected_department_info_id)
                            ->where('user_type_id',1)
                            ->update(['dating_type' => 1]);
                    }
                }
            }
            $department_info = Department_info::addModifyDepartment($request);
            //Save change
            if(!empty($department_info)){
                $data = [
                    'Edycja danych oddziału' => '',
                    'Id oddziału' => $request->selected_department_info_id
                ];
                new ActivityRecorder($data,66,2);
                Session::flash('message_ok', "Zmiany zapisano pomyślnie!");
                return Redirect::back();
            }else{
                Session::flash('message_error', "Problem podczas wykonywania SQL");
                return Redirect::back();
            }
        }
    }


    /** Show page to select user and departments
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function multipleDepartmentGet() {
        $users = User::onlyCadre()->activeUser()->orderBy('last_name')->get();
        return view('admin.multipleDepartments')
            ->with('users', $users);
    }

    /** Save or show user and avaible department
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function multipleDepartmentPost(Request $request) {
        $data = [];
        if($request->request_type == 'select_user'){
            $users = User::onlyCadre()->activeUser()->orderBy('last_name')->get();

            $user = User::find($request->user_department);
            if ($user == null) {
                return view('errors.404');
            }
            $user_id_post = $user->id;
            $user_dep = MultipleDepartments::
                select('department_info_id')
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

            MultipleDepartments::
                where('user_id', '=', $request->user_department_post)
                ->delete();

            $data['Edycja użytkownika'] = $userCheck->last_name.' '.$userCheck->first_name;
            $data['ID użytkownika'] = $userCheck->id;
            $data['Przydzielone ID oddziały'] = '[';
            for($i = 1; $i <= $last_id; $i++) {
                $actual_dep = 'dep' . $i;
                if($request->$actual_dep == $i){
                    $data['Przydzielone ID oddziały'] .= $request->$actual_dep.',';
                    MultipleDepartments::insert(
                        ['user_id' => $request->user_department_post, 'department_info_id' => $request->$actual_dep]
                    );
                }
            }
            $users = User::onlyCadre()->activeUser()->orderBy('last_name')->get();
            $data['Przydzielone ID oddziały'] = rtrim($data['Przydzielone ID oddziały'], ',');
            $data['Przydzielone ID oddziały'] .= ']';
            new ActivityRecorder($data, 70, 2);
            return view('admin.multipleDepartments')
                ->with('success', 'Zmiany zapisano pomyślnie!')
                ->with('users', $users);
        }
    }
}