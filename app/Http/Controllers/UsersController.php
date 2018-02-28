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
use Illuminate\Support\Facades\URL;
use App\PenaltyBonus;
use App\MedicalPackage;

class UsersController extends Controller
{
    /**
     * Wyświetlanie widou dla telemarketera
     */
    public function add_consultantGet()
    {
        $agencies = Agencies::all();
        $user = User::find(Auth::user()->id);
        
        return view('hr.addNewUser')
            ->with('agencies',$agencies)
            ->with('send_type',$user->department_info->type)
            ->with('type', 1);

    }
    public function add_CadreGet()
    {
        $agencies = Agencies::all();
        $user_types = UserTypes::all();
        $department_info = Department_info::all();
        return view('hr.addNewUser')
            ->with('agencies',$agencies)
            ->with('department_info',$department_info)
            ->with('user_types',$user_types)
            ->with('type', 2);

    }
    public function uniqueUsername(Request $request)
    {
        if($request->ajax())
        {
            $user = User::where('username', '=',$request->username)->count();
   
            return ($user > 0) ? 1 : 0 ;
        }
    }

    public function uniqueEmail(Request $request)
    {
       if($request->ajax())
       {
          $user = User::where('email_off',$request->email)->get();
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
        if ($request->email == null) {
            $user->email_off = null;
        } else {
            $user->email_off = $request->email;
        }
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
            $user->main_department_id = $request->department_info;
        }else
        {
            if (Auth::user()->user_type_id == 13) {
                $user->user_type_id = 2;
            } else {
                $user->user_type_id = 1;
            }
            $user->department_info_id = Auth::user()->department_info_id;
            $user->main_department_id = Auth::user()->department_info_id;
        }
        $user->dating_type = ($request->dating_type != null)? $request->dating_type : 0 ;
        $user->start_work = $request->start_date;
        $user->status_work = 1;
        $user->phone = $request->phone;
        $user->private_phone = $request->private_phone;
        $user->description = $request->description;
        $user->student = $request->student;
        $user->salary_to_account = $request->salary_to_account;
        $user->agency_id = $request->agency_id;
        $user->login_phone = ($request->login_phone != null) ? $request->login_phone : 0 ;
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

        /**
         * Dodanie pakietu medycznego
         */
        if ($request->medical_package_active > 0) {
            $this->addMedicalPackage($request, $user->id);
        }

        Session::flash('message_ok', "Użytkownik dodany pomyślnie!");
        return Redirect::back();

    }

    public function cadre_management_fireGet()
    {
        return view('hr.cadreManagementFire');
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
        $department_info = Department_info::all();
        $userTypes = UserTypes::all();

        if ($user == null || ($user->user_type_id != 1 && $user->user_type_id != 2)) {
            return view('errors.404');
        }

        if ($user->department_info_id != Auth::user()->department_info_id) {
            return view('404');
        }

        return view('hr.editUser')->with('agencies',$agencies)
          ->with('user',$user)
          ->with('department_info', $department_info)
          ->with('userTypes', $userTypes)
          ->with('type', 1);

    }

    public function edit_cadreGet($id) {

        if(Auth::user()->user_type->all_departments == 1) {
            $user = User::find($id);

            $agencies = Agencies::all();
            $department_info = Department_info::all();
            $month = date('m');
            $months_names = ['Styczeń', 'Luty', 'Marzec', 'Kwiecien', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Padziernik', 'Listopad', 'Grudzień'];

            function month_reverse($mnt)
            {
                if ($mnt < 0) {
                    $mnt += 12;
                }
                return $mnt;
            }

            $months = [$months_names[month_reverse($month - 1)], $months_names[month_reverse($month - 2)]];
            $actual_month = date('Y-m') . '%';
            $previous_month = date('Y-m', strtotime(date('Y-m') . " -1 month")) . "%";

            $penalty_bonuses[0] = DB::table('penalty_bonus')
                ->select(DB::raw('
                SUM(CASE WHEN `type` = 2 AND `status` = 1 THEN `amount` ELSE 0 END) as premia ,
                SUM(CASE WHEN `type` = 1 AND `status` = 1 THEN `amount` ELSE 0 END) as kara
            '))
                ->where('id_user', '=', $id)
                ->where('status', '=', 1)
                ->where('event_date', 'like', $actual_month)
                ->get();

            $penalty_bonuses[1] = DB::table('penalty_bonus')
                ->select(DB::raw('
                   SUM(CASE WHEN `type` = 2 AND `status` = 1 THEN `amount` ELSE 0 END) as premia ,
                   SUM(CASE WHEN `type` = 1 AND `status` = 1 THEN `amount` ELSE 0 END) as kara
               '))
                ->where('id_user', '=', $id)
                ->where('status', '=', 1)
                ->where('event_date', 'like', $previous_month)
                ->get();

            $userTypes = UserTypes::all();

            return view('hr.editUser')
                ->with('agencies', $agencies)
                ->with('userTypes', $userTypes)
                ->with('user', $user)
                ->with('penalty_bonuses', $penalty_bonuses)
                ->with('month', $months)
                ->with('department_info', $department_info)
                ->with('type', 2);
        }else{
            return Redirect::back();
        }
    }

    public function edit_cadrePOST($id, Request $request) {
        $manager_id = Auth::user()->id;
        $url_array = explode('/',URL::previous());
        $urlValidation = end($url_array);
        if ($urlValidation != $id) {
            return view('errors.404');
        }

        $user = User::find($id);

        if ($user == null) {
            return view('errors.404');
        }

        /**
         * Opcja dodania pakietu medycznego (Jezeli zmienna $request->medical_package_is_new == 1 && $request->medical_package_active == 1)
         * Lub zmiany gdy zmienna $request->medical_package_is_edited == 1
         */
        if ($request->medical_package_is_new == 1 && $request->medical_package_active == 1) {
            $this->addMedicalPackage($request, $user->id);
        } else if ($request->medical_package_is_edited == 1 && $request->medical_package_active == 1) {
            $this->changeMedicalPackage($request, $user);
        }


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
        $user->login_phone = ($request->login_phone != null) ? $request->login_phone : 0 ;
        $user->rate = $request->rate;
        $user->salary = $request->salary;
        $user->documents = $request->documents;
        $user->id_manager = $manager_id;
        $user->additional_salary = $request->additional_salary;
        $user->status_work = $request->status_work;
        $user->dating_type = $request->dating_type;
        $user->start_work = $request->start_date;
        if ($request->department_info_id != null) {
            $check_department = Department_info::find($request->department_info_id);
            if ($check_department == null) {
                return view('errors.404');
            }
            $user->department_info_id = $request->department_info_id;
            $user->main_department_id = $request->department_info_id;
        }
        if ($request->user_type != null && $request->user_type != 0) {
            $user->user_type_id = $request->user_type;
        }
        $user->end_work = $request->stop_date;
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
            'start_work' => $request->start_date,
            'stop_work' => $request->stop_date,
            'guid' => base64_encode($request->password)
        ];

        new ActivityRecorder(1, $data);

        /**
         * Ewentualna zmiana pakietów medycznych
         */


        Session::flash('message_edit', "Dane zostały zaktualizowane!");
        return Redirect::back();
    }

    public function datatableEmployeeManagement(Request $request)
    {
        if($request->ajax()) {
            $query = User::select('id', 'first_name','last_name',
                'username', 'start_work',
                'end_work', 'private_phone',
                'documents', 'student',
                'status_work','last_login')
                ->whereIn('user_type_id', [1,2])
                ->where('department_info_id', Auth::user()->department_info_id);
        }
        return datatables($query)
            ->filterColumn('student', function($query, $keyword) {
                $sql = "student = ?";
                if(strtolower($keyword) == 'tak')
                    $query->whereRaw($sql, ["1"]);
                else if(strtolower($keyword) == 'nie')
                    $query->whereRaw($sql, ["0"]);
            })->filterColumn('status_work', function($query, $keyword) {
                $sql = "status_work = ?";
                if(mb_strtolower($keyword) == 'pracujący')
                    $query->whereRaw($sql, ["1"]);
                else if(mb_strtolower($keyword) == "niepracujący")
                    $query->whereRaw($sql, ["0"]);
            })->filterColumn('documents', function($query, $keyword) {
                $sql = "documents = ?";
                if(mb_strtolower($keyword) == 'posiada')
                    $query->whereRaw($sql, ["1"]);
                else if(mb_strtolower($keyword) == "brak")
                    $query->whereRaw($sql, ["0"]);
            })->
            make(true);
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
                ->where('users.user_type_id','!=',2)
                ->where('users.status_work','=',1);
            return datatables($query)->make(true);
        }
    }

    public function datatableCadreManagementFire(Request $request)
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
                ->where('users.user_type_id','!=',2)
                ->where('users.status_work','=',0)
            ->orderBY('end_work','desc');
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
            $user->password_date = date('Y-m-d');
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

    public function cadreHRGet() {
        return view('hr.cadreHR');
    }

    public function datatableCadreHR(Request $request) {
        $data = DB::table('users')
            ->select(DB::raw('
                users.*,
                departments.name as dep_name,
                department_type.name as dep_name_type
            '))
            ->join('department_info', 'department_info.id', '=', 'users.department_info_id')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->where('users.user_type_id', '=', 5)
            ->get();

        return datatables($data)->make(true);
    }

    /**
     * Sprawdzenie czy numer kolejki pbx jest unikalny
     */
    public function uniquePBX(Request $request) {
        if ($request->ajax()) {
            $user = User::where('login_phone', '=', $request->login_phone)
                ->where('status_work','=','1')->count();

            return ($user > 0) ? 1 : 0 ;
        }
    }

    /**
     * Sprawdzenie czy email jest unikalny edycja
     */
    public function uniquerEmailEdit(Request $request) {
        if ($request->ajax()) {
            $email = $request->email;
            $id = $request->user_id;

            $check = User::where('email_off', '=', $email)->get();

            if ($check->count() == 0) {
                return 0;
            } else if ($check->count() > 0 && $check[0]->id != $id) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    /**
     * Funkcja dodająca pakiety medyczne dla nowych pracowników
     */
    public function addMedicalPackage(Request $request, $id) {

        /**
         * Dodanie pliku
         */
        $file_extension = $request->file('user_scan')->getClientOriginalExtension();
        $file_name = $request->first_name . '_' . $request->last_name;
        $store_name = $file_name . '_' . time() . '.' . $file_extension;
        $request->file('user_scan')->storeAs('medicalscan', $store_name);
        $request->store_name = $store_name;

        /**
         * Pobranie ilości osób w pakiecie medycznym
         */
        $sum = $request->totalMemberSum;

        for ($i = 0; $i < $sum; $i++) {
            $medicalPackage = new MedicalPackage();

            $medicalPackage->user_id            = $id;
            $medicalPackage->pesel              = $request->pesel[$i];
            $medicalPackage->user_first_name    = $request->user_first_name[$i];
            $medicalPackage->user_last_name     = $request->user_last_name[$i];
            $medicalPackage->birth_date         = $request->birth_date[$i];
            $medicalPackage->postal_code        = $request->postal_code[$i];
            $medicalPackage->city               = $request->city[$i];
            $medicalPackage->street             = $request->street[$i];
            $medicalPackage->house_number       = $request->house_number[$i];
            $medicalPackage->flat_number        = $request->flat_number[$i];
            $medicalPackage->phone_number       = $request->phone_number[$i];
            $medicalPackage->family_member      = ($i > 0) ? 1 : null ;
            $medicalPackage->deleted            = 0;
            $medicalPackage->package_name       = $request->package_name;
            $medicalPackage->package_variable   = $request->package_variable;
            $medicalPackage->cadre_id           = Auth::user()->id;
            $medicalPackage->package_scope      = ($i > 0) ? 'R-OM' : 'P-OM' ;
            $medicalPackage->scan_path          = $store_name;
            $medicalPackage->month_start        = $request->medical_start;
            $medicalPackage->created_at         = date('Y-m-d H:i:s');
            $medicalPackage->updated_at         = null;

            $medicalPackage->save();
        }
    }

    /**
     * Edycja pakietu medycznego
     */
    private function changeMedicalPackage(Request $request, User $user) { //dd($request);
        $old_medical_ids = $user->medicalPackages->where('deleted', '=', 0);
        $old_medical_ids = $old_medical_ids->pluck('id')->toArray();

        $medical_ids_from_form = $request->medical_id;
        /**
         *  $to_delete - tablica z ID osób którym usuwamy opiekę medyczną
         */
        if (is_array($medical_ids_from_form)) {
            $to_delete = array_diff($old_medical_ids, $medical_ids_from_form);

            foreach ($to_delete as $key => $value) {
                $medicalPackage = MedicalPackage::find($value);

                $medicalPackage->deleted = 1;
                $medicalPackage->updated_by = Auth::user()->id;
                $medicalPackage->updated_at = date('Y-m-d H:i:s');
                $medicalPackage->month_stop = date('Y-m-d H:i:s');

                $medicalPackage->save();
            }
        }

        /**
         * Sprawdzenie czy podmieniony został plik z umową dla użytkownika
         */
        if ($request->file('user_scan') != null) {
            // Pobranie rozszerzenia pliku
            $file_extension = $request->file('user_scan')->getClientOriginalExtension();
            // Konkatenacja imienia i nazwiska pracownika
            $file_name = $request->first_name . '_' . $request->last_name;
            // Utworzenie nazwy pliku - imie, nazwisko, aktualny czas + rozszerzenie
            $store_name = $file_name . '_' . time() . '.' . $file_extension;
            // Zapis pliku do lokalizacji /storage/app/medicalscan
            $request->file('user_scan')->storeAs('medicalscan', $store_name);
            $scan_path = $store_name;
        } else {
            $scan_path = $user->medicalPackages->where('deleted', '=', 0)->first()->scan_path;
        }

        /**
         * Trzeba przekręcić wszystkie wpisy z formularza, jezeli medical_id == 0 dodajemy nowy, jezeli jest już id to podmieniamy
         */
        $count_form = count($request->medical_id);

        for ($i = 0; $i < $count_form; $i++) {

            if ($request->medical_id[$i] == 0) {
                /**
                 * Jezeli dodajemy nowego posiadacza opieki
                 */
                $medicalPackage = new MedicalPackage();

                $medicalPackage->user_id            = $user->id;
                $medicalPackage->pesel              = $request->pesel[$i];
                $medicalPackage->user_first_name    = $request->user_first_name[$i];
                $medicalPackage->user_last_name     = $request->user_last_name[$i];
                $medicalPackage->birth_date         = $request->birth_date[$i];
                $medicalPackage->postal_code        = $request->postal_code[$i];
                $medicalPackage->city               = $request->city[$i];
                $medicalPackage->street             = $request->street[$i];
                $medicalPackage->house_number       = $request->house_number[$i];
                $medicalPackage->flat_number        = $request->flat_number[$i];
                $medicalPackage->phone_number       = $request->phone_number[$i];
                $medicalPackage->family_member      = ($i > 0) ? 1 : null ;
                $medicalPackage->deleted            = 0;
                $medicalPackage->package_name       = $request->package_name;
                $medicalPackage->package_variable   = $request->package_variable;
                $medicalPackage->cadre_id           = Auth::user()->id;
                $medicalPackage->package_scope      = ($i > 0) ? 'R-OM' : 'P-OM' ;
                $medicalPackage->scan_path          = $scan_path;
                $medicalPackage->month_start        = $request->medical_start;

                $medicalPackage->save();
            } else {
                /**
                 * Edycja istniejacych danych
                 */
                $medicalPackage = MedicalPackage::find(intval($request->medical_id[$i]));

                $medicalPackage->pesel              = $request->pesel[$i];
                $medicalPackage->user_first_name    = $request->user_first_name[$i];
                $medicalPackage->user_last_name     = $request->user_last_name[$i];
                $medicalPackage->birth_date         = $request->birth_date[$i];
                $medicalPackage->postal_code        = $request->postal_code[$i];
                $medicalPackage->city               = $request->city[$i];
                $medicalPackage->street             = $request->street[$i];
                $medicalPackage->house_number       = $request->house_number[$i];
                $medicalPackage->flat_number        = $request->flat_number[$i];
                $medicalPackage->phone_number       = $request->phone_number[$i];
                $medicalPackage->family_member      = ($i > 0) ? 1 : null ;
                $medicalPackage->deleted            = 0;
                $medicalPackage->package_name       = $request->package_name;
                $medicalPackage->package_variable   = $request->package_variable;
                $medicalPackage->scan_path          = $scan_path;
                $medicalPackage->month_start        = $request->medical_start;
                $medicalPackage->updated_by         = Auth::user()->id;
                $medicalPackage->updated_at         = date('Y-m-d H:i:s');

                $medicalPackage->save();
            }
        }
    }

    /**
     * Funkcja usuwająca całkowicie pakiet medyczny
     */
    public function deleteMedicalPackage(Request $request) {
        if ($request->ajax()) {

            /**
             * Pobranie wszystkich aktywnych wpisów dla danego użytkownika
             */
            $packages = MedicalPackage::where('user_id', '=', $request->user_id)
                ->where('deleted', '=', 0)
                ->get();

            foreach ($packages as $package) {

                $package->deleted = 1;
                $package->updated_by = Auth::user()->id;
                $package->month_stop = $request->medical_stop;
                $package->updated_at = date('Y-m-d H:i:s');

                $package->save();
            }
            return 1;
        }
    }

    /**
     * Dane na temat pakietów medycznych (domyślnie)
     */
    public function medicalPackagesAllGet() {
        return $this->getMedicalPackagesData(date('Y'), date('m'));
    }

    /**
     * Dane na temat pakietów medycznych (wybór)
     */
    public function medicalPackagesAllPost(Request $request) {
        return $this->getMedicalPackagesData($request->medical_year, $request->medical_month);
    }

    /**
     * Metoda pobierająca dane na temat
     */
    private function getMedicalPackagesData($year, $selectedMonth) {
        $month = $year . '-' . $selectedMonth . '%';
        $monthLimit = $year . '-' . $selectedMonth . '-01';
        $prevMonth = $this->getPreviousMonth($year, $selectedMonth);
        /**
         * Pobranie pakietow ktore sa nie edytowane i starsze niz miesiac
         */
        $packagesOldNotEdited = MedicalPackage::where('deleted', '=', 0)
            ->where('updated_at', 'not like', $month)
            ->orWhere('updated_at', '=', null)
            ->where('month_start', 'not like', $month)
            //->orderBy('user_last_name')
            ->get();

        /**
         * Edytowane w tym miesiącu (Edycja dotyczy zmian danych pakietów które nie są nowe)
         */
        $packagesOldEdited = MedicalPackage::where('deleted', '=', 0)
            ->where('month_start', 'not like', $month)
            ->where('updated_at', 'like', $month)
            //->orderBy('user_last_name')
            ->get();

        /**
         * Nowe w tym miesiącu OK
         */
        $packagesNewMonth = MedicalPackage::where('month_start', 'like', $month)
            //->orderBy('user_last_name')
            ->get();

        /**
         * Usunięte w tym miesiącu OK
         */
        $packagedDeletedThisMonth = MedicalPackage::where('deleted', '=', 1)
            ->where('month_stop', 'like', $prevMonth)
            //->orderBy('user_last_name')
            ->get();

        $packagesOldNotEdited = $packagesOldNotEdited->map(function($item) {
            $item['flag'] = 0;
            return $item;
        });

        $packagesOldEdited = $packagesOldEdited->map(function($item) {
            $item['flag'] = 1;
            return $item;
        });

        $packagesNewMonth = $packagesNewMonth->map(function($item) {
            $item['flag'] = 2;
            return $item;
        });

        $packagedDeletedThisMonth = $packagedDeletedThisMonth->map(function($item) {
            $item['flag'] = 3;
            return $item;
        });

        $merged = $packagesOldNotEdited;
        $merged = $merged->merge($packagesOldEdited);
        $merged = $merged->merge($packagesNewMonth);
        $merged = $merged->merge($packagedDeletedThisMonth);

        /**
         * Tablica z miesiącami
         */
        $months = collect([
            ['id' => '01', 'name' => 'Styczeń'],
            ['id' => '02', 'name' => 'Luty'],
            ['id' => '03', 'name' => 'Marzec'],
            ['id' => '04', 'name' => 'Kwiecień'],
            ['id' => '05', 'name' => 'Maj'],
            ['id' => '06', 'name' => 'Czerwiec'],
            ['id' => '07', 'name' => 'Lipiec'],
            ['id' => '08', 'name' => 'Sierpień'],
            ['id' => '09', 'name' => 'Wrzesień'],
            ['id' => '10', 'name' => 'Październik'],
            ['id' => '11', 'name' => 'Listopad'],
            ['id' => '12', 'name' => 'Grudzień']
        ]);

        return view('hr.allMedicalPackages')
            ->with('months', $months)
            ->with('selected_month', $selectedMonth)
            ->with('packages', $merged);
    }

    /**
     * Pobranie danych poprzedniego miesiąca
     */
    private function getPreviousMonth($year, $month) {
        $month = intval($month);
        $month -= 1;
        if ($month <= 0) {
            $month += 12;
            $year -= 1;
        }

        $month = ($month < 10) ? '0' . $month : $month;

        return $year . '-' . $month . '%';
    }

    /**
     * Zmiana daty rozmowy kwalifikacyjnej
     */
    public function editInterviewDate(Request $request) {
        if ($request->ajax()) {
            $candidate_id = $request->candidate_id;
            $newDate = $request->result;

            $candidate = Candidate::find($candidate_id);

            $data = $candidate->recruitment_attempt->where('status', '=', 0)->first();

            return $data;
        }
    }
}
