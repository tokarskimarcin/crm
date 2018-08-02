<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Work_Hour extends Model
{
    use Notifiable;
    protected $table = 'work_hours';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'status', 'click_start','click_stop','register_start','register_stop','accept_start','accept_stop','accept_sec',
        'success','id_user','id_manager','date'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'id_user');
    }

    public static function usersWorkingLessThan($iNumberOfHours): Collection {
        $iNumberOfSeconds = $iNumberOfHours * 60 * 60;

        $cAllUsers = Work_Hour::select(DB::raw('id_user, SUM(TIME_TO_SEC(TIMEDIFF(accept_stop, accept_start))) as sec_sum'))
            ->join('users', 'work_hours.id_user', '=', 'users.id')
            ->where('users.status_work', '=', 1)
            ->groupBy('id_user')
            ->having(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(accept_stop, accept_start)))'), '<', $iNumberOfSeconds)
            ->get();

        return $cAllUsers;
    }

    public static function usersWhoStartedWorkThisMonth($sThisMonth, $sThisYear) {
        $cThisMonthUsers = Work_Hour::select(DB::raw('
        id_user,
        date,
        TIME_TO_SEC(TIMEDIFF(accept_stop, accept_start)) as sec_sum,
        MONTH(MIN(date)) as minMonth,
        YEAR(MIN(date)) as minYear,
        MIN(date) as minDate
        '))
            ->join('users', 'work_hours.id_user', '=', 'users.id')
            ->where('users.status_work', '=', 1)
            ->groupBy('id_user')
            ->get();
        $cThisMonthUsers = $cThisMonthUsers->where('minMonth', $sThisMonth)->where('minYear', $sThisYear)->where('sec_sum', '!=', null);

        return $cThisMonthUsers;
    }

}
