<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Schedule extends Model
{
    protected $table = 'schedule';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public static $polishDate = ['Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota','Niedziela'];
    protected $fillable = [
        'id', 'id_user', 'id_manager', 'year', 'week_num', 'monday_comment',
        'monday_hour', 'tuesday_comment', 'tuesday_hour', 'wednesday_comment',
        'wednesday_hour', 'thursday_comment', 'thursday_hour', 'friday_comment',
        'friday_hour', 'saturday_comment', 'saturday_hour', 'sunday_comment',
        'sunday_hour','updated_at', 'created_at', 'id_manager_edit'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'id_user');
    }

    /**
     * Return all users planning rbh
     * @param $SnumberOfWeek
     * @param $Syear
     * @return Collection
     */
    public static function getUsersRBHSchedule($SnumberOfWeek,$Syear) : Collection {
        try{
            $CusersScheduleInfo = Schedule::select(DB::raw('
                users.department_info_id,
                CONCAT(departments.name," ",department_type.name) as departmentConcatName,
                time_to_sec(`monday_stop`)-time_to_sec(`monday_start`) as sec_monday,
                time_to_sec(`tuesday_stop`)-time_to_sec(`tuesday_start`) as sec_tuesday,
                time_to_sec(`wednesday_stop`)-time_to_sec(`wednesday_start`) as sec_wednesday,
                time_to_sec(`thursday_stop`)-time_to_sec(`thursday_start`) as sec_thursday,
                time_to_sec(`friday_stop`)-time_to_sec(`friday_start`) as sec_friday,
                time_to_sec(`saturday_stop`)-time_to_sec(`saturday_start`) as sec_saturday,
                time_to_sec(`sunday_stop`)-time_to_sec(`sunday_start`) as sec_sunday,
                 0 as sec_sum'))
                ->join('users','users.id','schedule.id_user')
                ->leftjoin('department_info','department_info.id','users.department_info_id')
                ->join('departments','departments.id','department_info.id_dep')
                ->join('department_type','department_type.id','department_info.id_dep_type')
                ->where("schedule.week_num", "=", $SnumberOfWeek)
                ->where("schedule.year", "=", $Syear)
                ->where('users.status_work', '=', 1)
                ->wherein('users.user_type_id',[1,2])
                ->get();
        }catch(\Exception $Eex){
            return new Collection('Błąd wykonywania SQL');
        }
        return $CusersScheduleInfo;
    }

    /**
     * Group schedule collection to department and sum
     * @param $CsheduleInfo
     * @return Collection
     */
    public static function groupUsersRBHbyDepartments($CsheduleInfo) : Collection{
        $CsheduleInfo = $CsheduleInfo->groupBy('department_info_id')->map(function ($row){
            $CfirstCollect = $row->first();
            $CfirstCollect->sec_sum         =  gmdate('H:i:s',($CfirstCollect->sec_monday +
                $CfirstCollect->sec_tuesday     +
                $CfirstCollect->sec_wednesday   +
                $CfirstCollect->sec_thursday    +
                $CfirstCollect->sec_friday      +
                $CfirstCollect->sec_saturday));
            $CfirstCollect->sec_monday      =   gmdate('H:i:s', $row->sum('sec_monday'));
            $CfirstCollect->sec_tuesday     =   gmdate('H:i:s', $row->sum('sec_tuesday'));
            $CfirstCollect->sec_wednesday   =   gmdate('H:i:s', $row->sum('sec_wednesday'));
            $CfirstCollect->sec_thursday    =   gmdate('H:i:s', $row->sum('sec_thursday'));
            $CfirstCollect->sec_friday      =   gmdate('H:i:s', $row->sum('sec_friday'));
            $CfirstCollect->sec_saturday    =   gmdate('H:i:s', $row->sum('sec_saturday'));
            $CfirstCollect->sec_sunday      =   gmdate('H:i:s', $row->sum('sec_sunday'));



            return $row->first();
        });
        return $CsheduleInfo;
    }

    /** Add missing department to collect
     * @param $CsheduleInfo
     * @return Collection
     */
    public static function addMissingDepartmentToCollect($CsheduleInfo) : Collection{
        $CallDepartmentInfo = Department_info::where('janky_system_id','!=',0)->get();
        $CallDepartmentInfo->map(function ($item) use ($CsheduleInfo){
            $CexisteDepartmentInCollect = $CsheduleInfo->where('department_info_id',$item->id);
            if($CexisteDepartmentInCollect->isEmpty())
                $CsheduleInfo = self::addDepartmentToSheduleCollect($item,$CsheduleInfo);
        });
        return $CsheduleInfo;
    }

    /** Add department to collection
     * @param $Cdepartment
     * @param $Ccollect
     * @return Collection
     */
    public static function addDepartmentToSheduleCollect($Cdepartment,$Ccollect) : Collection{
        $CnewItemToCollect =  new Schedule();
        $CnewItemToCollect->offsetSet('department_info_id',$Cdepartment->id);
        $CnewItemToCollect->offsetSet('sec_monday',0);
        $CnewItemToCollect->offsetSet('sec_tuesday',0);
        $CnewItemToCollect->offsetSet('sec_wednesday',0);
        $CnewItemToCollect->offsetSet('sec_thursday',0);
        $CnewItemToCollect->offsetSet('sec_friday',0);
        $CnewItemToCollect->offsetSet('sec_friday',0);
        $CnewItemToCollect->offsetSet('sec_saturday',0);
        $CnewItemToCollect->offsetSet('sec_sunday',0);
        $CnewItemToCollect->offsetSet('sec_sum',0);
        $CnewItemToCollect->offsetSet('departmentConcatName',$Cdepartment->departments->name.' '.$Cdepartment->department_type->name);
        $Ccollect->push($CnewItemToCollect);
        return $Ccollect;
    }
}
