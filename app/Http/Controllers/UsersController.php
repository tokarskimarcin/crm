<?php

namespace App\Http\Controllers;

use App\Agencies;
use App\Departments;
use App\User;
use App\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function add_consultantGet()
    {
        $agencies = Agencies::all();
        return view('hr.addConsultant')->with('agencies',$agencies);

    }
    public function add_CadreGet()
    {
        $agencies = Agencies::all();
        $department_info = $query = DB::table('department_info')
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->select(DB::raw('
                department_info.id,               
                department_type.name as department_type_name,
                departments.name as department_name
                '))->get();
        $user_types = UserTypes::all();
        return view('hr.addCadre')->with('agencies',$agencies)
            ->with('department_info',$department_info)
            ->with('user_types',$user_types);

    }
    public function uniqueUsername(Request $request)
    {
       if($request->ajax())
       {
          $user = User::where('username',$request->username)->get();
       }
       if($user->isEmpty())
            echo 0;
       else
           echo 1;
    }

    public function add_userPOST(Request $request)
    {
        $redirect = 0;
        $agencies = Agencies::all();
        $user = new User;
        $user->username = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->password = bcrypt($request->password);
        $user->created_at = date("Y-m-d H:i:s");
        $user->updated_at = date("Y-m-d H:i:s");
        $user->password_date = date("Y-m-d");

        if(isset($request->department_info) && isset($request->user_type))
        {
            $redirect = 1;
            $user->user_type_id = $request->user_type;
            $user->department_info_id = $request->department_info;
        }else
        {
            $user->user_type_id = 1;
            $user->department_info_id = Auth::user()->department_info_id;
        }
        $user->start_work = $request->start_date;
        $user->status_work = 1;
        $user->phone = $request->phone;
        $user->description = $request->description;
        $user->student = $request->student;
        $user->salary_to_account = $request->salary_to_account;
        $user->agency_id = $request->agency_id;
        $user->login_phone = $request->login_phone;
        if($request->rate == 'Nie dotyczy')
            $request->rate = 0;
        $user->rate = $request->rate;
        $user->id_manager = Auth::id();
        $user->documents = $request->documents;
        $user->save();
        if( $redirect = 0)
            return view('hr.addConsultant')->with('saved','saved')->with('agencies',$agencies);
        else
            return view('hr.addCadre')->with('saved','saved')->with('agencies',$agencies);
    }
    public function employee_managementGet()
    {
        return view('hr.employeeManagement');
    }
    public function cadre_managementGet()
    {
        return view('hr.cadreManagement');
    }
    public function edit_consultantGet($id)
    {
        $agencies = Agencies::all();
        $user = User::find($id);
        return view('hr.editConsultant')->with('agencies',$agencies)
            ->with('user',$user);
    }

    public function edit_consultantPOST($id,Request $request)
    {
        $agencies = Agencies::all();
        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        if($request->password != '')
        {
            $user->password = bcrypt($request->password);
        }
        $user->updated_at = date("Y-m-d H:i:s");
        $user->password_date = date("Y-m-d");
        $user->user_type_id = 1;
        $user->department_info_id = Auth::user()->department_info_id;
        $user->start_work = $request->start_date;
        $user->status_work = $request->status_work;
        $user->phone = $request->phone;
        $user->description = $request->description;
        $user->student = $request->student;
        $user->salary_to_account = $request->salary_to_account;
        $user->agency_id = $request->agency_id;
        $user->login_phone = $request->login_phone;
        $user->end_work = $request->end_work;
        if($request->rate == 'Nie dotyczy')
            $request->rate = 0;
        $user->rate = $request->rate;
        $user->id_manager = Auth::id();
        $user->documents = $request->documents;
        $user->save();
        return redirect('employee_management')->with('saved',$user->frist_name.' '.$user->last_name);
    }

    public function datatableEmployeeManagement(Request $request)
    {
        if($request->ajax()) {
            $query = User::select('id', 'first_name','last_name',
                'username', 'start_work',
                'end_work', 'phone',
                'documents', 'student',
                'status_work','last_login')
                ->where('user_type_id', 1)
                ->where('department_info_id', Auth::user()->department_info_id);
        }
        return datatables($query)->make(true);
    }
    public function datatableCadreManagement(Request $request)
    {
        if($request->ajax()) {
            $query = DB::table('users')
                ->join('department_info', 'department_info.id', '=', 'users.department_info_id')
                ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
                ->join('departments', 'department_info.id_dep', '=', 'departments.id')
                ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                ->select(DB::raw('
                users.*,
                department_type.name as department_type_name,
                departments.name as department_name,
                user_types.name as user_type_name
                '))
                ->where('users.user_type_id','!=',1)
                ->where('users.status_work','=',1);
            return datatables($query)->make(true);
        }
    }

}
