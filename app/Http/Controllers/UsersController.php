<?php

namespace App\Http\Controllers;

use App\Agencies;
use App\Department_info;
use App\Departments;
use App\User;
use App\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use Illuminate\Support\Facades\Hash;
use App\ActivityRecorder;

class UsersController extends Controller
{
    public function add_consultantGet()
    {
        $agencies = Agencies::all();
        $user = User::find(Auth::user()->id);
        return view('hr.addConsultantNew')
            ->with('agencies',$agencies)
            ->with('send_type',$user->department_info->type)
            ->with('type', 1);

    }
    public function add_CadreGet()
    {
        $agencies = Agencies::all();
        $user_types = UserTypes::all();
        $department_info = Department_info::all();
        return view('hr.addConsultantNew')->with('agencies',$agencies)
            ->with('department_info',$department_info)
            ->with('user_types',$user_types)
            ->with('type', 2);

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
        $send_type = Department_info::find(Auth::user()->department_info_id);
        $send_type = $send_type->type;
        $agencies = Agencies::all();
        $user = new User;
        $user->username = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email_off = $request->email;
        $user->password = bcrypt($request->password);
        $user->salary = $request->salary;
        $user->additional_salary = $request->additional_salary;
        $user->created_at = date("Y-m-d H:i:s");
        $user->updated_at = date("Y-m-d H:i:s");
        $user->password_date = date("Y-m-d");
        if($request->phone == null) {
            $request->phone = 0;
        }

        $user->guid = base64_encode($request->password);

        if(isset($request->department_info) && isset($request->user_type))
        {
            $redirect = 1;
            $user->user_type_id = $request->user_type;
            $user->department_info_id = $request->department_info;
        }else
        {
            if (Auth::user()->user_type_id == 13) {
                $user->user_type_id = 2;
            } else {
                $user->user_type_id = 1;
            }
            $user->department_info_id = Auth::user()->department_info_id;
            if(isset($request->dating_type))
            {
                $user->dating_type =  $request->dating_type;
            }
        }
        $user->start_work = $request->start_date;
        $user->status_work = 1;
        $user->phone = $request->phone;
        $user->private_phone = $request->private_phone;
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
        $user_data = array(
            'first_name' => $user->first_name,
            'last_name' => $user->last_name
        );

        new ActivityRecorder(1, 'Dodanie użytkownika: ' . $request->first_name . ' ' . $request->last_name . ', login: ' . $request->login_phone);

        Session::flash('message_ok', "Użytkownik dodany pomyślnie!");
        return Redirect::back();

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

        if ($user->status_work != 1) {
            return view('404');
        }
        
        return view('hr.addConsultantTEST')->with('agencies',$agencies)
          ->with('user',$user)
          ->with('type', 1);

    }

    public function edit_cadreGet($id) {
        $user = User::find($id);

        if ($user->status_work != 1) {
            return view('404');
        }
        $agencies = Agencies::all();

        $month = date('m');

        $months_names = ['Styczeń', 'Luty', 'Marzec', 'Kwiecien', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Padziernik', 'Listopad', 'Grudzień'];

        function month_reverse($mnt) {
            if ($mnt < 0) {
                $mnt += 12;
            }
            return $mnt;
        }
        $months = [$months_names[month_reverse($month - 1)], $months_names[month_reverse($month - 2)]];

        $penalty_bonuses = DB::select("SELECT event_date,SUM(CASE WHEN `type` = 2 AND `status` = 1 THEN `amount` ELSE 0 END) as premia , SUM(CASE WHEN `type` = 1 AND `status` = 1 THEN `amount` ELSE 0 END) as kara FROM
         `penalty_bonus` WHERE `id_user` = " . $id . " group by MONTH(`event_date`) LIMIT 2");

        return view('hr.addConsultantTEST')
            ->with('agencies', $agencies)
            ->with('user', $user)
            ->with('penalty_bonuses', $penalty_bonuses)
            ->with('month', $months)
            ->with('type', 2);
    }

    public function edit_cadrePOST($id, Request $request) {
        $manager_id = Auth::user()->id;

        $user = User::find($id);

        $user->username = $request->username;
        $user->email_off = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->updated_at = date("Y-m-d H:i:s");
        $user->phone = $request->phone;
        $user->email_off = $request->email;
        $user->private_phone = $request->private_phone;
        $user->description = $request->description;
        $user->student = $request->student;
        $user->salary_to_account = $request->salary_to_account;
        $user->agency_id = $request->agency_id;
        $user->login_phone = $request->login_phone;
        $user->rate = $request->rate;
        $user->salary = $request->salary;
        $user->documents = $request->documents;
        $user->id_manager = $manager_id;
        $user->additional_salary = $request->additional_salary;
        $user->status_work = $request->status_work;
        if($request->status_work == 0) {
            $user->end_work = $request->stop_date;
        }
        if($request->password != '')
        {
            $user->password = bcrypt($request->password);
            $user->guid = base64_encode($request->password);
        };
        $user->save();

        $data = [
            'Edycja użytkownika:' => ' ',
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'private_phone' => $request->private_phone,
            'description' => $request->description,
            'salary_to_account' => $request->salary_to_account,
            'agency_id' => $request->agency_id,
            'login_phone' => $request->login_phone,
            'rate' => $request->rate,
            'salary' => $request->salary,
            'documents' => $request->documents,
            'additional_salary' => $request->additional_salary,
            'status_work' => $request->status_work,
            'guid' => base64_encode($request->password)
        ];

        new ActivityRecorder(1, $data);

        Session::flash('message_edit', "Dane zostały zaktualizowane!");
        return Redirect::back();
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

    public function passwordChangeGet(){
        return view('home.passChange');
    }

    public function passwordChangePost(Request $request){
        $old_password = base64_decode(Auth::user()->guid);
        if ($request->old_pass == $old_password) {
            $user = User::find(Auth::user()->id);

            $user->password = bcrypt($request->new_pass);
            $user->guid = base64_encode($request->new_pass);
            $user->save();

            new ActivityRecorder(3, 'Zmiana hasła przez użytkownika, nowe hasło: ' . base64_encode($request->new_pass));
            Session::flash('message_ok', "Hasło zmienione pomyślnie!");
            return Redirect::back();
        } else {
            Session::flash('message_nok', "Podałeś nieprawidłowe stare hasło!");
            return Redirect::back();
        }
    }

}
