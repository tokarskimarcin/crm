<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 19.10.18
 * Time: 13:10
 */

namespace App\Http\Controllers\Statistics;


use App\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ITNotificationsRatingsStatisticsController
{
    public function iTNotificationsRatingsStatisticsGet() {
        return view('statistics.iTNotificationsRatingsStatistics');
    }

    public function iTCadreNotificationsRatingsStatisticsAjax(Request $request){
        $startDate = $request->startDate;
        $stopDate = $request->stopDate;
        $iTCadreNotificationsRatingsStatistics = Notifications::select(
            'u.id',
            'u.status_work',
            DB::raw('concat(u.last_name," ",u.first_name) as displayedBy'),
            DB::raw('round(sum(average_rating)/count(nr.id),4) as averageRating'),
            DB::raw('round(sum(TIME_TO_SEC(TIMEDIFF(data_stop, data_start)))/count(notifications.id)) as averageRealizationTime'),
            DB::raw('round(sum(TIME_TO_SEC(TIMEDIFF(data_start, notifications.created_at)))/count(notifications.id)) as averageReactionTime')
            )
            ->join('users as u','u.id','displayed_by')
            ->leftJoin('notification_rating as nr','nr.notification_id','notifications.id')
            ->whereNotNull('displayed_by')
            ->whereBetween('data_stop',[$startDate,$stopDate])
            ->groupBy('displayed_by')
            ->orderByDesc('averageRating');

        $iTCadreNotificationsRatingsStatistics = $iTCadreNotificationsRatingsStatistics->get();
        //dd($iTCadreNotificationsRatingsStatistics->get()->sortByDesc('averageRating')->toArray());
        return $iTCadreNotificationsRatingsStatistics;
    }
}