<?php

namespace App\Http\Controllers\Statistics;

use App\ClientRouteInfo;
use App\Department_info;
use App\PbxConfirmationReport;
use App\User;
use App\UserEmploymentStatus;
use App\Utilities\DataProcessing\ConfirmationStatistics;
use App\Utilities\Dates\MonthFourWeeksDivision;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DepartmentsConfirmationStatisticsController extends Controller
{
    //
    public function departmentsConfirmationGet(){

        $deps = Department_info::where('id_dep_type', 1)->with('departments')->with('department_type')->get();
        $userDepInfo = $deps->where('id', Auth::user()->department_info_id)->first();
        if($userDepInfo == null){
            return Redirect::to('/');
        }

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
        ini_set('max_execution_time', 300);
        $month = $request->selectedMonth;
        $trainersGrouping = $request->trainersGrouping;
        $departmentId = $request->departmentId;
        $trainerId = $request->trainerId;
        $period = $request->period;

        $monthFourWeeksDivision = null;
        if($period == 1) {
            $monthFourWeeksDivision = MonthFourWeeksDivision::get(date('Y', strtotime($month)), date('m', strtotime($month)));
        }else if($period == 3){
            $monthFourWeeksDivision = [(object)['firstDay'=> date('Y-m-', strtotime($month)).'01', 'lastDay' => date('Y-m-', strtotime($month)).date('t', strtotime($month))]];
        }else{
            return false;
        }
        $clientRouteInfo = ClientRouteInfo::select(
            DB::raw('concat(users.first_name," ",users.last_name) as confirmingUserName'),
            DB::raw('concat(trainer.first_name," ",trainer.last_name) as confirmingUserTrainerName'),
            'confirmingUser',
            'confirmDate',
            'frequency',
            'pairs',
            'actual_success',
            'users.department_info_id',
            'users.coach_id',
            'users.login_phone'
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

        $confirmationStatistics = ConfirmationStatistics::getConsultantsConfirmationStatisticsForMonth($clientRouteInfo, $monthFourWeeksDivision);

        $confirmationStatistics['data'] = $confirmationStatistics['data']->sortByDesc('provision')->groupBy('dateGroup');
        $dateGroupArrays = [];
        foreach ($confirmationStatistics['data'] as $dateGroup => $dateGroupCollection){
            if($trainersGrouping == 'true'){
                $confirmationStatistics['data'][$dateGroup] = $dateGroupCollection->groupBy('secondGroup');
            }
            $weekArr = explode(' ',$dateGroup);
            array_push($dateGroupArrays, (object)['dateGroup'=>$dateGroup,'firstDayTimestamp'=>\DateTime::createFromFormat('Y.m.d',$weekArr[0])->getTimestamp()]);
        }

        $counter = count($dateGroupArrays);
        while($counter > 1){
            for($i = 1; $i < $counter; $i++) {
                if ($dateGroupArrays[$i]->firstDayTimestamp < $dateGroupArrays[$i - 1]->firstDayTimestamp) {
                    $temp = $dateGroupArrays[$i - 1];
                    $dateGroupArrays[$i - 1] = $dateGroupArrays[$i];
                    $dateGroupArrays[$i] = $temp;
                }
            }
        $counter--;
        }

        $temp = [];
        foreach ($dateGroupArrays as $week){
            $temp[$week->dateGroup] = $confirmationStatistics['data'][$week->dateGroup];
        }
        $confirmationStatistics['data']= collect($temp);
        return $confirmationStatistics;
    }

    public function allDepartmentsConfirmationStatisticsAjax(Request $request){
        $month = $request->selectedMonth;
        $period = $request->period;

        $monthFourWeeksDivision = null;
        if($period == 1) {
            $monthFourWeeksDivision = MonthFourWeeksDivision::get(date('Y', strtotime($month)), date('m', strtotime($month)));
        }else if($period == 3){
            $monthFourWeeksDivision = [(object)['firstDay'=> date('Y-m-', strtotime($month)).'01', 'lastDay' => date('Y-m-', strtotime($month)).date('t', strtotime($month))]];
        }else{
            return false;
        }
        $clientRouteInfo = ClientRouteInfo::select(
            DB::raw('concat(users.first_name," ",users.last_name) as confirmingUserName'),
            DB::raw('concat(trainer.first_name," ",trainer.last_name) as confirmingUserTrainerName'),
            'confirmingUser',
            'confirmDate',
            'frequency',
            'pairs',
            'actual_success',
            'users.department_info_id',
            'users.coach_id',
            'users.login_phone'
        )
            ->join('users','confirmingUser', '=', 'users.id')
            ->join('department_info as di', 'users.department_info_id','=','di.id')
            ->join('users as trainer','users.coach_id','=','trainer.id')
            ->where('confirmDate', '>=', $monthFourWeeksDivision[0]->firstDay)
            ->where('confirmDate', '<=', $monthFourWeeksDivision[count($monthFourWeeksDivision)-1]->lastDay)
            ->where('di.id_dep_type',1)
            ->whereNotNull('confirmingUser')
            ->whereNotNull('users.coach_id');
        $clientRouteInfo = $clientRouteInfo->get();

        $confirmationStatistics = ConfirmationStatistics::getConsultantsConfirmationStatisticsForMonth($clientRouteInfo, $monthFourWeeksDivision, 'department_info_id');

        $confirmationStatistics['data'] = $confirmationStatistics['data']->sortByDesc('provision')->groupBy('dateGroup');

        return $confirmationStatistics;
    }

    /**
     * Getting every pbx confirmation report for specified consultant in specified week
     * @param array $userId
     * @param $pbxId
     * @param object $dateGroupSum - {'firstDay','lastDay'}
     * @return \Illuminate\Support\Collection
     */
    public static function getEveryPbxConfirmationReport($userId, $dateGroupSum){
        ini_set('max_execution_time', 300);
        $consultantEmploymentStatus = UserEmploymentStatus::whereIn('user_id', $userId)
            ->where(function ($query) use ($dateGroupSum){
                /*                         dateGroup->firstDay              dateGroup->lastDay                   dateGroup->firstDay                      dateGroup->lastDay*/
                //and ((pbx_id_add_date >= '2018-09-01' and pbx_id_add_date < '2018-09-30' ) or (pbx_id_remove_date >= '2018-09-01' and pbx_id_remove_date < '2018-09-30' ) or isnull(pbx_id_remove_date ))
                $query->where(function ($query) use ($dateGroupSum){
                    $query->where('pbx_id_add_date', '>=', $dateGroupSum->firstDay)
                        ->where('pbx_id_add_date', '<', $dateGroupSum->lastDay);
                })
                    ->orWhere(function ($query) use ($dateGroupSum){
                        $query->where('pbx_id_remove_date', '>', $dateGroupSum->firstDay)
                            ->where('pbx_id_remove_date', '<=', $dateGroupSum->lastDay);
                    })
                    ->orWhere(function ($query) use ($dateGroupSum){
                        $query->where('pbx_id_add_date', '<', $dateGroupSum->firstDay)
                            ->where('pbx_id_remove_date', '>', $dateGroupSum->lastDay);
                    })
                    ->orWhere(function ($query ) use ($dateGroupSum){
                        $query->whereNull('pbx_id_remove_date')
                            ->where('pbx_id_add_date', '<', $dateGroupSum->lastDay);
                    })
                    ->orWhere(function ($query) use ($dateGroupSum){
                        $query->where('pbx_id_remove_date','like','0000-00-00%')
                            ->where('pbx_id_add_date', '<', $dateGroupSum->lastDay);
                    });

            });
        $consultantEmploymentStatus = $consultantEmploymentStatus->get();

        $userIdsFromUserEmploymentStatus = $consultantEmploymentStatus->pluck('user_id')->unique()->toArray();
        $userIdsWithoutEmploymentStatus = array_diff($userId, $userIdsFromUserEmploymentStatus);
        if(count($userIdsWithoutEmploymentStatus) > 0){
            $usersWithoutEmploymentStatus = User::whereIn('id',$userIdsWithoutEmploymentStatus)->get();
            foreach ($usersWithoutEmploymentStatus as $userWithoutEmploymentStatus){
                $consultantEmploymentStatus->push((object)[
                    'pbx_id' => $userWithoutEmploymentStatus->login_phone,
                    'user_id' => $userWithoutEmploymentStatus->id,
                    'pbx_id_add_date' => $dateGroupSum->firstDay,
                    'pbx_id_remove_date' => $dateGroupSum->lastDay
                ]);
            }
        }

        $confirmationReport = PbxConfirmationReport::whereIn('id',function ($query) use($consultantEmploymentStatus, $dateGroupSum){
            $query->select(DB::raw('max(id)'))
                ->from('pbx_confirmation_report')
                ->groupBy('report_date','pbx_id');

            //building query in loop
            foreach ($consultantEmploymentStatus as $employmentStatus){
                $firstDayOfPeriod = is_null($employmentStatus->pbx_id_add_date) ? $dateGroupSum->firstDay : $employmentStatus->pbx_id_add_date;
                $lastDayOfPeriod = is_null($employmentStatus->pbx_id_remove_date) || $employmentStatus->pbx_id_remove_date == '0000-00-00' ? $dateGroupSum->lastDay : $employmentStatus->pbx_id_remove_date;

                if(\DateTime::createFromFormat('Y-m-d', $firstDayOfPeriod) < \DateTime::createFromFormat('Y-m-d', $dateGroupSum->firstDay)){
                    $firstDayOfPeriod = $dateGroupSum->firstDay;
                }
                if(\DateTime::createFromFormat('Y-m-d', $lastDayOfPeriod) > \DateTime::createFromFormat('Y-m-d', $dateGroupSum->lastDay)){
                    $lastDayOfPeriod = $dateGroupSum->lastDay;
                }
                $query->orWhere(function ($query) use ($employmentStatus, $firstDayOfPeriod, $lastDayOfPeriod){
                    $query->where('pbx_id', $employmentStatus->pbx_id)
                        ->whereBetween('report_date',[$firstDayOfPeriod,$lastDayOfPeriod]);
                });
            }
        })->get();

        $consultantEmploymentStatusOnlyUserIdAndPbxId = $consultantEmploymentStatus->map(function ($item) {
            return (object)['user_id' => $item->user_id, 'pbx_id' => $item->pbx_id];
        });
        //adding user id to every confirming report
        foreach ($consultantEmploymentStatusOnlyUserIdAndPbxId->unique() as $employmentStatus){
            $consultantConfirmationReports = $confirmationReport->where('pbx_id', $employmentStatus->pbx_id);
            foreach ($consultantConfirmationReports as $consultantConfirmationReport){
                $consultantConfirmationReport->user_id = $employmentStatus->user_id;
            }
        }
        return collect($confirmationReport);
    }
}
