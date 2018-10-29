<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 17.10.18
 * Time: 08:50
 */

namespace App\Http\Controllers\Statistics;


use App\Department_info;
use App\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RemovedNotificationStatisticsController
{

    public function removedNotificationGet(){
        $departments = Department_info::with('departments')->with('department_type')->get();
        return view('statistics.removedNotificationsStatistics')
            ->with('departments',$departments);
    }

    public function removedNotificationsCountStatisticsAjax(Request $request){
        $departmentsSelected = $request->departmentsSelect;
        $notificationsTypeSelected = $request->notificationsTypeSelect;
        $removedNotificationsStatistics = Notifications::
        select(DB::raw('concat(u.last_name," ", u.first_name) as person'),
            DB::raw('concat(dep.name," ", dep_type.name) as department'),
            DB::raw('count(notifications.id) as removedNotificationsCount'),
            'user_id')
            ->join('users as u','u.id','notifications.user_id')
            ->join('department_info as dep_info','u.main_department_id','dep_info.id')
            ->join('department_type as dep_type','dep_info.id_dep_type','dep_type.id')
            ->join('departments as dep','dep_info.id_dep','dep.id')
            ->whereBetween('remove_date',[$request->dateStart, $request->dateStop])
            ->where('status', 0 )
            ->groupBy('user_id');
        if($departmentsSelected != 0){
            $removedNotificationsStatistics->where('u.main_department_id', $departmentsSelected);
        }
        if($notificationsTypeSelected == 1){            //removed by user who create notification
            $removedNotificationsStatistics->whereRaw('user_id = removed_by_user_id');
        }else if($notificationsTypeSelected ==2){       //removed by user who didn't create notification
            $removedNotificationsStatistics->whereRaw('user_id <> removed_by_user_id');
        }

        return $removedNotificationsStatistics->get();
    }

    public function removedNotificationsAjax(Request $request){

        $notificationsTypeSelected = $request->notificationsTypeSelect;
        $removedNotificationsStatistics = Notifications::select('remove_date',
            DB::raw('concat(remover.last_name," ", remover.first_name) as removedBy'),
            DB::raw('concat(notifier.last_name," ", notifier.first_name) as notifiedBy'),
            'title')
            ->join('users as notifier', 'user_id','notifier.id')
            ->join('users as remover', 'removed_by_user_id','remover.id')
            ->whereBetween('remove_date',[$request->dateStart, $request->dateStop])
            ->where('status',0)
            ->where('user_id', $request->selectedUser);

        if($notificationsTypeSelected == 1){            //removed by user who create notification
            $removedNotificationsStatistics->whereRaw('user_id = removed_by_user_id');
        }else if($notificationsTypeSelected ==2){       //removed by user who didn't create notification
            $removedNotificationsStatistics->whereRaw('user_id <> removed_by_user_id');
        }
        return $removedNotificationsStatistics->get();
    }
}