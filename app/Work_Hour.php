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

    /**
     * Return users who work <comparator> than number of hours
     * @param $iNumberOfHours
     * @param $comparator
     * @param null $SactualMonth
     * @return Collection
     */
    public static function usersWorkingRBHSelector($iNumberOfHours, $comparator, $SactualMonth = null): Collection {
        $availableComparators = ['<','>','<=','>=','=','<>'];
        if(!in_array($comparator, $availableComparators)){
            throw new \Exception('Wrong param (comparator) in usersWorkingRBHSelector function');
        }
        if($SactualMonth == null) $SactualMonth = date('Y-m');
        $iNumberOfSeconds = $iNumberOfHours * 60 * 60;

        $cAllUsers = Work_Hour::select(DB::raw('
        id_user,
        Concat(users.first_name," ",users.last_name) as userNameInfo,
        IFNULL(SUM(TIME_TO_SEC(TIMEDIFF(accept_stop, accept_start))), 0) as sec_sum,
        sum(success) as success,
        departments.name as dep_city,
        department_type.name as dep_type,
        department_info.id as dep_id
        '))
            ->join('users', 'work_hours.id_user', '=', 'users.id')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->where(function ($querry) use ($SactualMonth, $comparator){
                if($comparator == '<' || $comparator == '<=' ){
                    $querry->where(function ($query) use ($SactualMonth, $comparator){
                        if($comparator == '<' || $comparator == '<=' ){
                                $query->where(function ($query) use ($SactualMonth) {
                                $query->where('users.status_work', '=', 1)->where('users.start_work','like',$SactualMonth.'%');
                                })
                                ->orwhere('users.end_work','like',$SactualMonth.'%');
                            }
                        })
                        ->orwhere('users.end_work','like',$SactualMonth.'%');
                }
            })
            ->whereIn('users.user_type_id', [1,2])
            ->groupBy('id_user')
            ->having(DB::raw('IFNULL(SUM(TIME_TO_SEC(TIMEDIFF(accept_stop, accept_start))), 0)'), $comparator, $iNumberOfSeconds)
            ->get();

        return $cAllUsers;
    }
    
    /**
     * Pobranie tablicy z miesiącami
     */
    public  static function getMonthsNames() {
        $months = [
            '01' => 'Styczeń',
            '02' => 'Luty',
            '03' => 'Marzec',
            '04' => 'Kwiecień',
            '05' => 'Maj',
            '06' => 'Czerwiec',
            '07' => 'Lipiec',
            '08' => 'Sierpień',
            '09' => 'Wrzesień',
            '10' => 'Październik',
            '11' => 'Listopad',
            '12' => 'Grudzień'
        ];
        return $months;
    }

    /**
     * Return Users who start work on report month
     * @param $sThisMonth
     * @param $sThisYear
     * @param null $SactualMonth
     * @return Collection
     */
    public static function usersWhoStartedWorkThisMonth($sThisMonth, $sThisYear,$SactualMonth = null) : Collection {
        if($SactualMonth == null) $SactualMonth = date('Y-m');
        $cThisMonthUsers = Work_Hour::select(DB::raw('
        id_user,
        Concat(users.first_name," ",users.last_name) as userNameInfo,
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
            ->where(function ($querry) use ($SactualMonth){
                $querry->orwhere('users.status_work',1)
                    ->orwhere('users.end_work','like',$SactualMonth.'%');
            })
            ->whereIn('users.user_type_id', [1,2])
            ->groupBy('id_user')
            ->get();
        $cThisMonthUsers = $cThisMonthUsers->where('minMonth', $sThisMonth)->where('minYear', $sThisYear)->where('sec_sum', '!=', null);

        return $cThisMonthUsers;
    }

    /**
     * Get info work haours, about user by id
     * @param $id
     * @return Collection
     */
    public static function getWorkHoursRecordsGroupedByDate($id) : Collection {
        $allUserRecords = Work_Hour::select(DB::raw('
                id_user,
                date,
                SUM(TIME_TO_SEC(TIMEDIFF(accept_stop, accept_start))) as sec_sum,
                success,
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

    /**
     * @param $allUsersThisMonth
     * @param $iTimeInSeconds
     * @return Collection
     */
    public static function mergeCollection($allUsersThisMonth,$iTimeInSeconds) : Collection{
       return $allUsersThisMonth->map(function($item) use($iTimeInSeconds, $allUsersThisMonth) {
            if($item->sec_sum >= $iTimeInSeconds) { // case when user works over 30 hours
                //Teraz chce uzyskać daty od kiedy zaczą pracować do kiedy liczyć mu wyniki.

                $allUserRecords = Work_Hour::getWorkHoursRecordsGroupedByDate($item->id_user);
                $iSecondSum = 0;
                $only30RBHSuccess = 0;
                $sDateStart = null;
                $sDateStop = null;

                foreach($allUserRecords as $key => $value) {
                    if($iSecondSum < $iTimeInSeconds) {
                        if($key == 0) {
                            $sDateStart = $value->date;
                        }
                        $iSecondSum += $value->sec_sum;
                        $only30RBHSuccess += $value->success;
                    }
                    if($iSecondSum >= $iTimeInSeconds) {
                        $sDateStop = $value->date;
                        $item->secondStop = $iSecondSum;
                        $item->success = $only30RBHSuccess;
                        break;
                    }
                }
                $item->dateStart = $sDateStart;
                $item->dateStop = $sDateStop;

            }
            return $item;
        });
    }

}
