<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pbx_report_extension extends Model
{
    protected $table = 'pbx_report_extension';
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User','pbx_id', 'login_phone');
    }


    //Collect must have id_user field in collect
    public static function getPbxUserStatistics($CcollectOfUsers){
        $max_from_day = DB::table('pbx_report_extension')
            ->select(DB::raw('
                MAX(id) as id
            '))
            ->whereIn('user_id', $CcollectOfUsers->pluck('id_user'))
            ->groupBy('report_date')
            ->groupBy('pbx_id','user_id')
            ->get();
        $pbx_data = Pbx_report_extension::whereIn('id', $max_from_day->pluck('id')->toArray())->get();
        $CcollectOfUsers = $CcollectOfUsers->map(function ($item) use ($pbx_data){
            $userInfo = $pbx_data->where('user_id',$item['id_user']);
            if(!$userInfo->isEmpty()){
                if(isset($item['dateStart'])){
                    $userInfo = $userInfo->where('report_date','>=',$item['dateStart'])
                    ->where('report_date','<=',$item['dateStop']);
                    $item['avg']  = $item['secondStop'] != 0 ? round($item['success']/($item['secondStop']/3600),2) : 0;
                }else{
                    if(!isset($item['success']))
                        dd($item);
                    $item['avg']  = $item['sec_sum'] != 0 ? round($item['success']/($item['sec_sum']/3600),2) : 0;
                }
                $item['jankyProc']          = $userInfo->sum('all_checked_talks') != 0 ? round($userInfo->sum('all_bad_talks')/($userInfo->sum('all_checked_talks')) * 100,2) : 0;
                $item['bad_talks']          = $userInfo->sum('all_bad_talks');
                $item['all_checked_talks']  = $userInfo->sum('all_checked_talks');
                $item['pause_timeSec']      = $userInfo->sum('time_pause');
                $item['pause_time']         = Schedule::secondToHour($userInfo->sum('time_pause'));
                $item['received_calls']     = $userInfo->sum('received_calls');
                $item['received_callsProc'] = $userInfo->sum('received_calls') != 0 ? round($item['success']/($userInfo->sum('received_calls')) * 100,2) : 0;
            }else{
                $item['bad_talks']          = 0;
                $item['all_checked_talks']  = 0;
                $item['success']            = 0;
                $item['avg']                = 0;
                $item['jankyProc']          = 0;
                $item['pause_time']         = 0;
                $item['received_calls']     = 0;
                $item['received_callsProc'] = 0;
            }
            return $item;
        });
        return $CcollectOfUsers;
    }

}


