<?php

namespace App\Http\Controllers;

use App\Agencies;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function add_consultantGet()
    {
        $agencies = Agencies::all();
        return view('hr.addConsultant')->with('agencies',$agencies);

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

    public function add_consultantPOST(Request $request)
    {
        $agencies = Agencies::all();
        $user = new User;
        $user->username = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->password = bcrypt($request->password);
        $user->created_at = date("Y-m-d H:i:s");
        $user->updated_at = date("Y-m-d H:i:s");
        $user->password_date = date("Y-m-d");
        $user->user_type_id = 1;
        $user->department_info_id = Auth::user()->department_info_id;
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
        return view('hr.addConsultant')->with('saved','saved')->with('agencies',$agencies);
    }
    public function employee_managementGet()
    {
        return view('hr.employeeManagement');
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

}
