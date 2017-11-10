<?php

namespace App\Http\Controllers;

use App\Agencies;
use App\Department_info;
use App\JankyPenatlyProc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return view('finances.viewPayment')
            ->with('month',$date)
            ->with('salary',$salary)
            ->with('department_info',$department_info)
            ->with('janky_system',$janky_system)
            ->with('agencies',$agencies);
    }



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
}
