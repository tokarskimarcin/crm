<?php

namespace App\Http\Controllers\Statistics;

use App\ClientRouteInfo;
use App\Department_info;
use App\User;
use App\Utilities\DataProcessing\ConfirmationStatistics;
use App\Utilities\Dates\MonthFourWeeksDivision;
use App\Utilities\Dates\MonthIntoCompanyWeeksDivision;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentsConfirmationStatisticsController extends Controller
{
    //
    public function departmentsConfirmationGet(){
        $deps = Department_info::where('id_dep_type', 1)->with('departments')->with('department_type')->get();
        $trainers = User::select(
            'id',
            'first_name',
            'last_name',
            'department_info_id')
            ->whereIn('department_info_id',$deps->pluck('id')->toArray())
            ->where('status_work',1)
            ->where('user_type_id',4)->get();
        return view('statistics.departmentsConfirmationStatistics')
            ->with('deps', $deps)
            ->with('trainers', $trainers);
    }


    public function departmentsConfirmationStatisticsAjax(Request $request){

        $month = $request->selectedMonth;
        $trainersGrouping = $request->trainersGrouping;
        $departmentId = $request->departmentId;
        $trainerId = $request->trainerId;
        MonthFourWeeksDivision::get(date('Y',strtotime($month)),date('m',strtotime($month)));

        $monthIntoCompanyWeeksDivision = MonthIntoCompanyWeeksDivision::get(date('m',strtotime($month)),date('Y',strtotime($month)));
        $clientRouteInfo = ClientRouteInfo::select(
            'confirmingUser',
            'confirmDate',
            'frequency',
            'pairs',
            'actual_success',
            'users.first_name',
            'users.last_name',
            'trainer.first_name as t_first_name',
            'trainer.last_name as t_last_name',
            'users.department_info_id',
            'users.coach_id'
            )
            ->join('users','confirmingUser', '=', 'users.id')
            ->join('department_info as di', 'users.department_info_id','=','di.id')
            ->join('users as trainer','users.coach_id','=','trainer.id')
            ->where('confirmDate', '>=', $monthIntoCompanyWeeksDivision[0]->firstDay)
            ->where('confirmDate', '<=', $monthIntoCompanyWeeksDivision[count($monthIntoCompanyWeeksDivision)-1]->lastDay)
            ->where('users.department_info_id', $departmentId)
            ->where('di.id_dep_type',1)
            ->whereNotNull('confirmingUser')
            ->whereNotNull('users.coach_id');
        if($trainerId>0){
            $clientRouteInfo->where('users.coach_id', $trainerId);
        }
        $clientRouteInfo = $clientRouteInfo->get();

        ConfirmationStatistics::getConsultantsConfirmationStatisticsCollectionForMonth($clientRouteInfo, $monthIntoCompanyWeeksDivision);
        $collect = collect();
        $collect->push(['name' => 'Marcin Tokarski1',   'shows' => 20,'provision' => 300, 'successful' => 12,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski4',   'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski3',   'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski2',   'shows' => 20,'provision' => 300, 'successful' => 12,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski5',   'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski8',   'shows' => 20,'provision' => 250, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski6',   'shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski19',  'shows' => 20,'provision' => 300, 'successful' => 12,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski7',   'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski20',  'shows' => 20,'provision' => 300, 'successful' => 12,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski11',  'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski16',  'shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski13',  'shows' => 20,'provision' => 100, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski15',  'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski9',   'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski10',  'shows' => 20,'provision' => -100, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski17',  'shows' => 20,'provision' => -200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski14',  'shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski15',  'shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-11', 'dateGroup' => '2018-09-10 - 2018-09-16', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski17',  'shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-12', 'dateGroup' => '2018-09-10 - 2018-09-16', 'trainer' => 'Małgorzata Wawrzyn']);

        //wymagane operacje:
        $collect = $collect->sortByDesc('provision')->groupBy('dateGroup');
        if($trainersGrouping == 'true'){
            foreach ($collect as $dateGroup => $dateGroupCollection){
                $collect[$dateGroup] = $dateGroupCollection->groupBy('trainer');
            }
        }

        return $collect;
    }


}
