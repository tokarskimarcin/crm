<?php

namespace App\Http\Controllers;

use App\Agencies;
use App\Department_info;
use App\Department_types;
use App\JankyPenatlyProc;
use App\PenaltyBonus;
use App\SummaryPayment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;

class FinancesController extends Controller
{
    public function viewPaymentGet()
    {
        return view('finances.viewPayment');
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

        if($count_agreement == 1)
        {
            return view('finances.viewPayment')
                ->with('month',$date)
                ->with('salary',$salary)
                ->with('department_info',$department_info)
                ->with('janky_system',$janky_system)
                ->with('agencies',$agencies);
        }
       else
        {
            return view('finances.viewPaymentWithoutAgreement')
                ->with('month',$date)
                ->with('salary',$salary)
                ->with('department_info',$department_info)
                ->with('janky_system',$janky_system)
                ->with('agencies',$agencies);
        }


    }

    public function viewPenaltyBonusGetEdit($id) {
      $user = User::find($id);
      $agencies = Agencies::all();
      return view('hr.addConsultantTEST')
          ->with('user', $user)
          ->with('agencies', $agencies);
    }

    public function viewPenaltyBonusPostEdit(Request $request) {
        $id_manager = Auth::user()->id;

        $object = new PenaltyBonus();
        $object->id_user = $request->user_id;
        $object->type = $request->penalty_type;
        $object->amount = $request->cost;
        $object->event_date = date('Y-m-d');
        $object->id_manager = $id_manager;
        $object->comment = $request->reason;
        $object->save();

        $message_type = ($request->penalty_type == 1) ? 'Kara' : 'Premia' ;
        $message = $message_type . ' została dodana pomyślnie';

        $user = User::find($request->user_id);
        $agencies = Agencies::all();

        Session::flash('message', "Kara/premia dodana pomyślnie!");
        return Redirect::back();

    }

    public function createPenaltyBonusPOST(Request $request) {
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
        $users =  User::where('department_info_id', Auth::user()->department_info_id)->whereIn('user_type_id', [1, 2])->get();
        return view('finances.viewPenaltyBonus')
            ->with('users',$users);
    }
    public function viewPenaltyBonusPOST(Request $request)
    {
        $users =  User::where('department_info_id', Auth::user()->department_info_id)->whereIn('user_type_id', [1, 2])->get();
        $view = view('finances.viewPenaltyBonus')->with('users',$users);

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
        return view('finances.viewSummaryPayment')
            ->with('summary_month',$summary_month)
            ->with('month',$month);
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
            $object->type = $request->type;
            $object->amount = $request->amount;
            $object->comment = $request->comment;
            $object->id_manager_edit = Auth::user()->id;
            $object->save();
            return 0;
        }
    }


    //Custom Function
    private function getSalary($month)
    {
        $query = DB::table(DB::raw("users"))
            ->join('work_hours', 'work_hours.id_user', 'users.id')
            ->where('users.department_info_id',Auth::user()->department_info_id)
            ->where('users.user_type_id',1)
            ->where('work_hours.date','like',$month)
            ->selectRaw('
            `users`.`id`,
            `users`.`agency_id`,
            `users`.`first_name`,
            `users`.`last_name`,
            `users`.`rate`,
             SUM( time_to_sec(`work_hours`.`accept_stop`)-time_to_sec(`work_hours`.`accept_start`)) as `sum`,
            `users`.`student`,
            `users`.`documents`,
            (SELECT SUM(`penalty_bonus`.`amount`) FROM `penalty_bonus` WHERE `penalty_bonus`.`id_user`=`users`.`id` AND `penalty_bonus`.`event_date` LIKE "'.$month.'" AND `penalty_bonus`.`type`=1) as `kara`,
            (SELECT SUM(`penalty_bonus`.`amount`) FROM `penalty_bonus` WHERE `penalty_bonus`.`id_user`=`users`.`id` AND `penalty_bonus`.`event_date` LIKE  "'.$month.'" AND `penalty_bonus`.`type`=2) as `premia`,
            SUM(`work_hours`.`success`) as `success`,
            `salary_to_account`')
            ->groupBy('users.id')
            ->orderBy('users.last_name','users.first_name');

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
                ->selectRaw('`agency_id`,`first_name`,`last_name`,`rate`,`sum`,`student`,`documents`,`kara`,`premia`,`success`,
            `f`.`ods`,
            `h`.`janki`,
            `salary_to_account` ')->get();
            $final_salary = $r->groupBy('agency_id');
            return $final_salary;
    }

    public function deletePenaltyBonus(Request $request) {
        if($request->ajax())
        {
            $object = PenaltyBonus::find($request->id);
            $object->id_manager_edit = Auth::user()->id;
            $object->status = 0;
            $object->updated_at = date('Y-m-d H:i:s');
            $object->save();

        }


    }

}
