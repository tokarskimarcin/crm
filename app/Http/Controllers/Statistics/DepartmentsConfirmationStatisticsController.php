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

        $monthFourWeeksDivision = MonthFourWeeksDivision::get(date('Y',strtotime($month)),date('m',strtotime($month)));
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
            ->where('confirmDate', '>=', $monthFourWeeksDivision[0]->firstDay)
            ->where('confirmDate', '<=', $monthFourWeeksDivision[count($monthFourWeeksDivision)-1]->lastDay)
            ->where('users.department_info_id', $departmentId)
            ->where('di.id_dep_type',1)
            ->whereNotNull('confirmingUser')
            ->whereNotNull('users.coach_id');
        if($trainerId>0){
            $clientRouteInfo->where('users.coach_id', $trainerId);
        }
        $clientRouteInfo = $clientRouteInfo->get();

        $confirmationStatistics = ConfirmationStatistics::getConsultantsConfirmationStatisticsCollectionForMonth($clientRouteInfo, $monthFourWeeksDivision);

        $confirmationStatistics = $confirmationStatistics->sortByDesc('provision')->groupBy('dateGroup');
        if($trainersGrouping == 'true'){
            foreach ($confirmationStatistics as $dateGroup => $dateGroupCollection){
                $confirmationStatistics[$dateGroup] = $dateGroupCollection->groupBy('trainer');
            }
        }

        return $confirmationStatistics;
    }


}
