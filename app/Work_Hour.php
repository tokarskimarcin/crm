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

        $cAllUsers = Work_Hour::select(DB::raw('
        id_user,
        SUM(TIME_TO_SEC(TIMEDIFF(accept_stop, accept_start))) as sec_sum,
        departments.name as dep_city,
        department_type.name as dep_type,
        department_info.id as dep_id
        '))
            ->join('users', 'work_hours.id_user', '=', 'users.id')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
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
        SUM(TIME_TO_SEC(TIMEDIFF(accept_stop, accept_start))) as sec_sum,
        MONTH(MIN(date)) as minMonth,
        YEAR(MIN(date)) as minYear,
        departments.name as dep_city,
        department_type.name as dep_type,
        department_info.id as dep_id
        '))
            ->join('users', 'work_hours.id_user', '=', 'users.id')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->where('users.status_work', '=', 1)
            ->groupBy('id_user')
            ->get();
        $cThisMonthUsers = $cThisMonthUsers->where('minMonth', $sThisMonth)->where('minYear', $sThisYear)->where('sec_sum', '!=', null);

        return $cThisMonthUsers;
    }

    public static function getWorkHoursRecordsGroupedByDate($id) {
        $allUserRecords = Work_Hour::select(DB::raw('
                id_user,
                date,
                SUM(TIME_TO_SEC(TIMEDIFF(accept_stop, accept_start))) as sec_sum,
                departments.name as dep_city,
                department_type.name as dep_type,
                department_info.id as dep_id
                '))
            ->join('users', 'work_hours.id_user', '=', 'users.id')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->where('id_user', '=', $id)
            ->groupBy('date')
            ->get();

        return $allUserRecords;
    }

}
