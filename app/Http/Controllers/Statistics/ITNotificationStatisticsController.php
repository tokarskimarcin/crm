<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 17.10.18
 * Time: 13:52
 */

namespace App\Http\Controllers\Statistics;


use App\Notifications;
use App\Utilities\Dates\MonthIntoCompanyWeeksDivision;
use App\Utilities\Dates\MonthPerWeekDivision;
use Illuminate\Support\Facades\DB;

class ITNotificationStatisticsController
{
    public function iTNotificationStatisticsGet(){
        return $this->iTNotificationStatisticsData(view('statistics.iTNotificationStatistics'), date('Y-m'));
    }

    public function iTNotificationStatisticsData($view, $month){
        $monthIntoCompanyWeeksDivision = MonthIntoCompanyWeeksDivision::get(date('m',strtotime($month)),date('Y',strtotime($month)));
        dd($monthIntoCompanyWeeksDivision);
        $iTRealizedNotificationStatistics = Notifications::select('displayed_by',
            DB::raw('cast(data_stop as date) as date_stop'),                //from datetime to date
            DB::raw('count(notifications.id) as notificationsCount'),       //notifications count realized
            DB::raw('case when count(nr.id) = 0 then 0 else round(sum(case when nr.id is not null then average_rating else 0 end)/count(nr.id),4) end as average_rating'),
            DB::raw('count(nr.id) as notificationsRatedCount')
        //DB::raw('count(case when notifications.status = 2 then 1 else null end) as notificationsInProgressCount')
        )
            ->leftJoin('notification_rating as nr','nr.notification_id','notifications.id')
            ->whereNotNull('displayed_by')
            ->groupBy('displayed_by')
            ->groupBy(DB::raw('cast(data_stop as date)'))
            ->where('status','>',0)
            ->whereNotNull('data_stop')
            ->get();
        $iTUnrealizedNotificationStatistics = Notifications::select('displayed_by',
            DB::raw('cast(data_start as date) as date_start'),
            DB::raw('cast(data_stop as date) as date_stop'))
            ->whereNotNull('displayed_by')
            ->where(function ($query){
                $query->where(DB::raw('cast(data_start as date)'),'<>',DB::raw('cast(data_stop as date)'))
                    ->orWhere(function ($query){
                        $query->whereNull('data_stop');
                    });
            })
            ->get();

        //dd($iTUnrealizedNotificationStatistics->groupBy('displayed_by')->toArray());
        //dd($iTRealizedNotificationStatistics->sortBy('date_stop')->groupBy('displayed_by')->toArray());
        return $view->with('iTRealizedNotificationStatistics',$iTRealizedNotificationStatistics->groupBy('displayed_by'))
            ->with('iTUnrealizedNotificationStatistics', $iTUnrealizedNotificationStatistics->groupBy('displayed_by'));
    }
}