<?php

namespace App\Http\Controllers;

use App\AcceptedPayment;
use App\Agencies;
use App\Department_info;
use App\Department_types;
use App\Departments;
use App\JankyPenatlyProc;
use App\PaymentAgencyStory;
use App\PenaltyBonus;
use App\SummaryPayment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\ActivityRecorder;

class FinancesController extends Controller
{
    public function viewPaymentGet()
    {
        return view('finances.viewPayment');
    }
    public function viewPaymentCadreGet()
    {
        return view('finances.viewPaymentCadre');
    }
    public function viewPaymentCadrePost(Request $request)
    {
        $date_to_post = $request->search_money_month;
        $date = $request->search_money_month.'%';
        $agencies = Agencies::all();
        $salary = DB::table(DB::raw("users"))
            ->whereNotIn('users.user_type_id',[1,2,14])
            ->where('users.salary','>',0)
            ->selectRaw('
            `users`.`id`,
            `users`.`agency_id`,
            `users`.`first_name`,
            `users`.`last_name`,
            `users`.`username`,
            `departments`.`name` as dep_name, 
            `department_type`.`name`  as dep_type,
            `users`.`salary`,
            `users`.`additional_salary`,
            `users`.`student`,
            `users`.`documents`,
            (SELECT SUM(`penalty_bonus`.`amount`) FROM `penalty_bonus` WHERE `penalty_bonus`.`id_user`=`users`.`id` AND `penalty_bonus`.`event_date` LIKE "'.$date.'" AND `penalty_bonus`.`type`=1 AND `penalty_bonus`.`status`=1) as `penalty`,
            (SELECT SUM(`penalty_bonus`.`amount`) FROM `penalty_bonus` WHERE `penalty_bonus`.`id_user`=`users`.`id` AND `penalty_bonus`.`event_date` LIKE  "'.$date.'" AND `penalty_bonus`.`type`=2 AND `penalty_bonus`.`status`=1) as `bonus`')
            ->where(function ($query) use ($date){
                $query->orwhere(DB::raw('SUBSTRING(promotion_date,1,7)'),'<', substr($date,0,strlen($date)-2))
                    ->orwhere('users.promotion_date','=',null);
            })
            ->join('department_info','department_info.id','users.main_department_id')
            ->join('work_hours', 'work_hours.id_user', 'users.id')
            ->join('departments','departments.id','department_info.id_dep')
            ->join('department_type','department_type.id','department_info.id_dep_type')

            ->where('work_hours.date', 'like', $date)
            ->groupBy('users.id')

            ->orderBy('users.last_name')->get();

        /**
         * Pobranie danych osób którzy nie pracowali całego miesiąca
         */
        $days_in_month = date('t', strtotime($request->search_money_month . "-01"));

        //Zdefiniownie ostatniego dnia miesiąca
        $last_day = $request->search_money_month . '-' . $days_in_month;
        //zdefiniowanie pierwszego dnia miesiaca
        $first_day = $request->search_money_month . '-01';

        /**
         * Puste tablice przechowujące dane osob ktorych pensja musi się zmienic
         */
        $working_days = [];
        $work_days_stop = [];
        $work_days_in_between = [];

        /**
         * Pobranie danych osob ktore rozpoczeły prace w tym miesiącu
         */
        $users_by_start = DB::table('users')
            ->select(DB::raw("
                id,
                start_work,
                salary
            "))
            ->where('start_work', 'like', $date)
            ->whereNotIn('user_type_id', [1,2,14])
            ->get();

        /**
         * Obliczenie średniej dziennej pensji oraz ilosci przepracowanych dni
         */
        foreach($users_by_start as $item) {

            $date_diff = strtotime($last_day) - strtotime($item->start_work);

            $user_salary_per_day = $item->salary / $days_in_month;

            $user_salary = $user_salary_per_day * (($date_diff / 3600 / 24) + 1);

            $working_days[$item->id] = round($user_salary, 0);
        }
    
        foreach($salary as $value) {
            foreach($working_days as $key => $item) {
                if ($value->id == $key) {
                    $value->salary = $item;
                }
            }
        }

        /**
        * Pobranie danych osob ktore zakończyły prace w tym miesiącu
        */
        $users_by_stop = DB::table('users')
            ->select(DB::raw("
                id,
                end_work,
                salary
            "))
            ->where('end_work', 'like', $date)
            ->whereNotIn('user_type_id', [1,2,14])
            ->get();

        /**
         * Obliczenie średniej dziennej pensji oraz ilosci przepracowanych dni
         */
        foreach($users_by_stop as $item) {

            $date_diff = strtotime($item->end_work) - strtotime($first_day);

            $user_salary_per_day = $item->salary / $days_in_month;

            $user_salary = $user_salary_per_day * (($date_diff / 3600 / 24) + 1);

            $work_days_stop[$item->id] = round($user_salary, 0);
        }
     

        foreach($salary as $value) {
            foreach($work_days_stop as $key => $item) {
                if ($value->id == $key) {
                    $value->salary = $item;
                }
            }
        }

        /**
         * Pobranie danych osób które rozpoczeły i zakończyły prace w danym miesiącu
         */
        $in_between = DB::table('users')
            ->select(DB::raw("
                id,
                start_work,
                end_work,
                salary
            "))
            ->where('end_work', 'like', $date)
            ->where('start_work', 'like', $date)
            ->whereNotIn('user_type_id', [1,2,14])
            ->get();

        /**
         * Obliczenie średniej dziennej pensji oraz ilosci przepracowanych dni
         */
        foreach($in_between as $item) {

            $date_diff = strtotime($item->end_work) - strtotime($item->start_work);

            $user_salary_per_day = $item->salary / $days_in_month;

            $user_salary = $user_salary_per_day * (($date_diff / 3600 / 24) + 1);            

            $work_days_in_between[$item->id] = round($user_salary, 0);
        }

        foreach($salary as $value) {
            foreach($work_days_in_between as $key => $item) {
                if ($value->id == $key) {
                    $value->salary = $item;
                }
            }
        }

        /**
         *  Pobranie informacji o departamentach
         */
        $departments = DB::table('department_info')
            ->select(DB::raw('department_info.id as id,departments.name as dep_name,department_type.name as dep_type'))
            ->join('departments','departments.id','department_info.id_dep')
            ->join('department_type','department_type.id','department_info.id_dep_type')
            ->get();

        return view('finances.viewPaymentCadre')
            ->with('month',$date_to_post)
            ->with('salary',$salary->groupby('agency_id'))
            ->with('agencies',$agencies)
            ->with('departments',$departments);
    }

    public function viewPaymentPOST(Request $request)
    {
        $date = $request->search_money_month;
        $salary = $this->getSalary($date.'%');
        $department_info = Department_info::find(Auth::user()->department_info_id);
        $janky_system = JankyPenatlyProc::where('system_id',$department_info->janky_system_id)->get();
        $agencies = Agencies::all();
        $department_type = Department_types::find($department_info->id_dep_type);
        $count_agreement = $department_type->count_agreement;


        $payment_saved = AcceptedPayment::
        where('department_info_id','=',Auth::user()->department_info_id)
            ->where('payment_month','like', $date.'%')
            ->get();

        if($count_agreement == 1)
        {
            return view('finances.viewPayment')
                ->with('month',$date)
                ->with('salary',$salary)
                ->with('department_info',$department_info)
                ->with('janky_system',$janky_system)
                ->with('agencies',$agencies)
                ->with('payment_saved',$payment_saved);
        }
       else
        {
            return view('finances.viewPaymentWithoutAgreement')
                ->with('month',$date)
                ->with('salary',$salary)
                ->with('department_info',$department_info)
                ->with('janky_system',$janky_system)
                ->with('agencies',$agencies)
                ->with('payment_saved',$payment_saved);
        }
    }

    public function viewPenaltyBonusPostEdit(Request $request) {
        $id_manager = Auth::user()->id;

        $user = User::find($request->user_id);

        if ($user == null || ($request->penalty_type != 1 && $request->penalty_type != 2)) {
            return view('errors.404');
        }

        $object = new PenaltyBonus();
        $object->id_user = $request->user_id;
        $object->type = $request->penalty_type;
        $object->amount = $request->cost;
        $object->event_date = date('Y-m-d');
        $object->id_manager = $id_manager;
        $object->comment = $request->reason;
        $object->event_date = $request->date_penalty;
        $object->save();

        $data = [
            'Dodanie kary/premii dla użytkownika' => '',
            'Id użytkownika' => $request->user_id,
            'typ' => $request->penalty_type,
            'amount' => $request->cost,
            'event_date' => date('Y-m-d'),
            'comment' => $request->reason,
        ];

        new ActivityRecorder(2, $data);

        $message_type = ($request->penalty_type == 1) ? 'Kara' : 'Premia' ;
        $message = $message_type . ' została dodana pomyślnie';

        $user = User::find($request->user_id);
        $agencies = Agencies::all();

        Session::flash('message', "Kara/premia dodana pomyślnie!");
        return Redirect::back();

    }

    public function createPenaltyBonusPOST(Request $request) {
        $checkUser = User::find($request->user_id);
        if($checkUser == null){
            return view('errors.404');
        }

        $object = new PenaltyBonus();
        $object->id_user = $request->user_id;
        $object->type = $request->type_penalty;
        $object->amount = $request->cost;
        $object->event_date = $request->date_penalty;
        $object->id_manager = Auth::user()->id;
        $object->comment = $request->reason;
        $object->save();

        Session::flash('message_ok', "Kara/premia dodana pomyślnie!");
        return Redirect::back();
    }

    public function viewPenaltyBonusGet()
    {

        // użytkownicy pracujący
        $users =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2])
            ->where('status_work', '=', 1)
            ->orderBy('last_name')
            ->get();
        $last_month = date("Y-m", strtotime("first day of previous month"));
        $current_month = date("Y-m");
        // zwolnieni miesiąc temu
        $users_fired_last_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $last_month.'%')
            ->orderBy('last_name')
            ->get();
        // zwolnieni w tym miesiącu
        $users_fired_current_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $current_month.'%')
            ->orderBy('last_name')
            ->get();
        $merge_array = $users->merge($users_fired_last_month);
        $merge_array = $merge_array->merge($users_fired_current_month);

        return view('finances.viewPenaltyBonus')
            ->with('users',$merge_array->sortBy('last_name'));
    }
    public function viewPenaltyBonusPOST(Request $request)
    {
        // użytkownicy pracujący
        $users =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2])
            ->where('status_work', '=', 1)
            ->orderBy('last_name')
            ->get();

        $last_month = date("Y-m", strtotime("first day of previous month"));
        $current_month = date("Y-m");
        // zwolnieni miesiąc temu
        $users_fired_last_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $last_month.'%')
            ->orderBy('last_name')
            ->get();
        // zwolnieni w tym miesiącu
        $users_fired_current_month =  User::where('department_info_id', Auth::user()->department_info_id)
            ->whereIn('user_type_id', [1, 2])
            ->where('status_work', '=', 0)
            ->where('end_work', 'like', $current_month.'%')
            ->orderBy('last_name')
            ->get();
        $merge_array = $users->merge($users_fired_last_month);
        $merge_array = $merge_array->merge($users_fired_current_month);

        $view = view('finances.viewPenaltyBonus')->with('users',$merge_array->sortBy('last_name'));

        $date_start = $request->date_penalty_show_start;
        $date_stop = $request->date_penalty_show_stop;
        $query = DB::table('penalty_bonus')
            ->join('users as users', 'penalty_bonus.id_user', '=', 'users.id')
            ->join('users as manager', 'penalty_bonus.id_manager', '=', 'manager.id')
            ->select(DB::raw(
                'users.first_name,
                users.last_name,
                manager.first_name as manager_first_name,
                manager.last_name as manager_last_name,
                penalty_bonus.*
               '))->where('users.department_info_id',Auth::user()->department_info_id)
                ->whereBetween('event_date', [$date_start, $date_stop])
                ->whereIn('type', [1,2])
                ->where('users.user_type_id',1)
                ->where('status',1);
        if($request->showuser != -1)
        {
            $query
              ->where('users.id' , $request->showuser)
              ->where('status', 1);
        }
        $view->with('users_show',$query->get())
        ->with('date_start',$date_start)
        ->with('date_stop',$date_stop)
        ->with('showuser',$request->showuser);

        return $view;
    }

    public function viewSummaryPaymentGet()
    {
       return view('finances.viewSummaryPayment');
    }
    public function viewSummaryPaymentPOST(Request $request)
    {
        $month = $request->search_summary_money_month;
        $summary_month = SummaryPayment::where('month',$month)->get();
        $departments = Department_info::where('type','!=','')->get();
        return view('finances.viewSummaryPayment')
            ->with('summary_month',$summary_month)
            ->with('month',$month)
            ->with('departments',$departments);
    }


    public function saveSummaryPayment(Request $request)
    {
        if($request->ajax())
        {
            $summary_payment = SummaryPayment::firstOrNew(array('month' =>$request->month,
                'department_info_id' =>Auth::user()->department_info_id));
            $summary_payment->department_info_id = Auth::user()->department_info_id;
            $summary_payment->month = $request->month;
            $summary_payment->payment = $request->payment_total;
            $summary_payment->month = $request->month;
            $summary_payment->hours = $request->rbh_total;
            $summary_payment->documents =$request->documents_total;
            $summary_payment->students = $request->students_total;
            $summary_payment->employee_count = $request->user_total;
            $summary_payment->id_user = Auth::user()->id;
            $summary_payment->save();
            return $summary_payment;
        }
    }
    public function editPenaltyBonus(Request $request)
    {
        if($request->ajax())
        {
            $object = PenaltyBonus::find($request->id);
            if ($object == null) {
                return 0;
            }
            if ($request->type != 1 && $request->type != 2) {
                return 0;
            }
            $object->type = $request->type;
            $object->amount = $request->amount;
            $object->comment = $request->comment;
            $object->id_manager_edit = Auth::user()->id;
            $object->save();
            return 1;
        }
    }


    //Custom Function
    private function getSalary($month)
    {
            //Czy wypłata jest już zatwierdzona
            $payment_saved = AcceptedPayment::
            where('department_info_id','=',Auth::user()->department_info_id)
            ->where('payment_month','like', $month)
            ->get();
            $string_to_sql = '';
            if(!$payment_saved->isEmpty()){
                $string_to_sql = "`payment_agency_story`.`agency_id`";
            }else{
                $string_to_sql = "`users`.`agency_id`";
            }


        $query = DB::table(DB::raw("users"))
            ->join('work_hours', 'work_hours.id_user', 'users.id')
            ->where('users.department_info_id',Auth::user()->department_info_id)
            ->where(function ($querry) use ($month){
                $querry->orwhere(DB::raw('SUBSTRING(promotion_date,1,7)'),'>=',substr($month,0,strlen($month)-2))
                    ->orwhere('users.user_type_id','=',1)
                    ->orwhere('users.user_type_id','=',2);
            })
            ->where('work_hours.date','like',$month)
            ->selectRaw('
            `users`.`id`,
            '.$string_to_sql.',              
            `users`.`first_name`,
            `users`.`last_name`,
            `users`.`username`,
            `users`.`rate`,
             SUM( time_to_sec(`work_hours`.`accept_stop`)-time_to_sec(`work_hours`.`accept_start`)) as `sum`,
            `users`.`student`,
            `users`.`documents`,
            (SELECT SUM(`penalty_bonus`.`amount`) FROM `penalty_bonus` WHERE `penalty_bonus`.`id_user`=`users`.`id` AND `penalty_bonus`.`event_date` LIKE "'.$month.'" AND `penalty_bonus`.`type`=1) as `kara`,
            (SELECT SUM(`penalty_bonus`.`amount`) FROM `penalty_bonus` WHERE `penalty_bonus`.`id_user`=`users`.`id` AND `penalty_bonus`.`event_date` LIKE  "'.$month.'" AND `penalty_bonus`.`type`=2) as `premia`,
            SUM(`work_hours`.`success`) as `success`,
            `salary_to_account`');
            if(!$payment_saved->isEmpty()){
                $query = $query
                    ->leftjoin('payment_agency_story',function ($querry) use ($month){
                        $querry->on('payment_agency_story.consultant_id','=','users.id')
                            ->where('payment_agency_story.accept_month','like',$month);
                    });
            }
            $query = $query
            ->groupBy('users.id')
            ->orderBy('users.last_name');

            $r = DB::table(DB::raw('('.$query->toSql().') as r'))
                ->mergeBindings($query)
                    ->leftjoin(
                    DB::raw('(SELECT `dkj`.`id_user`, COUNT(*) as ods FROM `dkj`
                    WHERE `deleted`=0 AND `add_date` LIKE  "'.$month.'"
                    GROUP by `dkj`.`id_user`
                    ) f '),'r.id','f.id_user'
                )
                ->leftjoin(
                    DB::raw('(SELECT `dkj`.`id_user`, COUNT(*) as janki FROM `dkj` where
                   `deleted`=0 AND `dkj_status`=1 AND `add_date` LIKE  "'.$month.'"
                    GROUP by `dkj`.`id_user`) h'),'r.id','h.id_user'
                )
                ->selectRaw('`id`,`agency_id`,`first_name`,`last_name`,`username`,`rate`,`sum`,`student`,`documents`,`kara`,`premia`,`success`,
            `f`.`ods`,
            `h`.`janki`,
            `salary_to_account`')->get();
            $final_salary = $r->groupBy('agency_id');


            return $final_salary;
    }

    public function deletePenaltyBonus(Request $request) {
        if($request->ajax())
        {
            $object = PenaltyBonus::find($request->id);

            if ($object != null) {
                $object->id_manager_edit = Auth::user()->id;
                $object->status = 0;
                $object->updated_at = date('Y-m-d H:i:s');
                $object->save();

                $data = [
                    'Usunięcie kary/premii' => '',
                    'data' => date('Y-m-d H:i:s'),
                    'Id kary/premii' => $object->id,
                ];
                new ActivityRecorder(2, $data);
                return 1;
            } else {
                return 0;
            }
        }
    }

    /**
     * @param Request $request
     * @return Zapisanie informacji o aktualnym stanie wypłat
     */
    public function paymentStory(Request $request){
        if($request->ajax()){


            //Zapisanie infromacji o zaakceptowaniu wypłat
            $is_exist = AcceptedPayment::
              where('department_info_id','=',Auth::user()->department_info_id)
            ->where('payment_month','like', $request->accetp_month.'%')
            ->get();
            if($is_exist->isEmpty()){
                $accept_payment = new AcceptedPayment();
                $accept_payment->cadre_id =  Auth::user()->id;
                $accept_payment->payment_month = $request->accetp_month.'-01';
                $accept_payment->department_info_id = Auth::user()->department_info_id;
                    $salary = $this::getSalary($request->accetp_month.'%');
                    $data = array();
                    foreach ($salary as $item ){
                        foreach ($item as $value){
                            array_push($data,array('consultant_id' => $value->id,
                                'agency_id' => $value->agency_id,
                                'cadre_id' => Auth::user()->id,
                                'department_info_id' => Auth::user()->department_info_id,
                                'accept_month' => $request->accetp_month.'-01',
                                'created_at' =>date('Y-m-d H:m:s:i'),
                                'updated_at' => date('Y-m-d H:m:s:i')));
                        }
                    }
                    PaymentAgencyStory::insert($data);
                    $accept_payment->save();
                    return $data;
            }
            return 0;
        }
    }


}
