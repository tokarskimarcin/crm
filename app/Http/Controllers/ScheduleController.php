<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function setScheduleGet()
    {
        return view('schedule.setSchedule');
    }
    public function setSchedulePost(Request $request)
    {

        $number_of_week = $request->show_schedule;
        $request->session()->put('number_of_week', $number_of_week);
        $schedule_analitics = $this->setWeekDays($number_of_week);
        //dd($schedule_analitics);
        return view('schedule.setSchedule')
            ->with('number_of_week',$number_of_week)
            ->with('schedule_analitics',$schedule_analitics);
    }

    public function datatableShowUserSchedule(Request $request)
    {
        $number_week =  $request->session()->get('number_of_week');
        $year = $request->year;
        $request->session()->put('year', $year);
        $query = DB::table('users')
            ->leftjoin("schedule", function ($join) use ($number_week,$year) {
                $join->on("schedule.id_user", "=", "users.id")
                    ->where("schedule.week_num", "=", $number_week)
                    ->where("schedule.year", "=", $year);
            })
            ->select(DB::raw(
                'schedule.*,               
                users.id as id_user,
                users.first_name as user_first_name,
                users.last_name as user_last_name,
                users.phone as user_phone             
                '))
            ->where('users.department_info_id',Auth::user()->department_info_id);
        return datatables($query)->make(true);
    }

    public function saveSchedule(Request $request)
    {
        $start_hours = $request->start_hours;
        $stop_hours = $request->stop_hours;
        $reasons = $request->reasons;
        $id_user = $request->id_user;
        for($i=0;$i<7;$i++)
        {
            if($start_hours[$i] == 'null')
                $start_hours[$i] = null;
            if($stop_hours[$i] == 'null')
                $stop_hours[$i] = null;
            if($start_hours[$i] == null && $start_hours[$i] == null && $reasons[$i] == null)
                $reasons[$i] = 'Wolne';
            else if($start_hours[$i] != null && $start_hours[$i] != null)
                $reasons[$i] = null;
        }
        $number_week =  $request->session()->get('number_of_week');
        $year = $request->session()->get('year');
        $schedule_id = $request->schedule_id;
        $dayOfWeekArray= array('monday' ,'tuesday','wednesday','thursday','friday','saturday','sunday');
        if($schedule_id == 'null')
        {
            $schedule = new Schedule();
            $schedule->id_user = $id_user;
            $schedule->id_manager = Auth::user()->id;
        }else{
            $schedule = Schedule::find($schedule_id);
            $schedule->id_manager_edit = Auth::user()->id;
        }
            $schedule->year = $year;
            $schedule->week_num = $number_week;

            $schedule->monday_start = $start_hours[0];
            $schedule->monday_stop = $stop_hours[0];
            $schedule->monday_comment =  $reasons[0];

            $schedule->tuesday_start = $start_hours[1];
            $schedule->tuesday_stop = $stop_hours[1];
            $schedule->tuesday_comment =  $reasons[1];

            $schedule->wednesday_start = $start_hours[2];
            $schedule->wednesday_stop = $stop_hours[2];
            $schedule->wednesday_comment =  $reasons[2];

            $schedule->thursday_start = $start_hours[3];
            $schedule->thursday_stop = $stop_hours[3];
            $schedule->thursday_comment =  $reasons[3];

            $schedule->friday_start = $start_hours[4];
            $schedule->friday_stop = $stop_hours[4];
            $schedule->friday_comment =  $reasons[4];

            $schedule->saturday_start = $start_hours[5];
            $schedule->saturday_stop = $stop_hours[5];
            $schedule->saturday_comment =  $reasons[5];

            $schedule->sunday_start = $start_hours[6];
            $schedule->sunday_stop = $stop_hours[6];
            $schedule->sunday_comment =  $reasons[6];

            $schedule->save();

        return $schedule;
    }
    //*************Custom Function*************
    private function getStartAndEndDate($week, $year) {
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
    }
    private function setWeekDays($number_week)
    {
        $dayOfWeekArray= array('monday' ,'tuesday','wednesday','thursday','friday','saturday','sunday');
        $query = DB::table('schedule');
        $sql = '';
        for($j=0;$j<count($dayOfWeekArray);$j++) {
            for ($i = 8; $i <= 21; $i++) {
                if ($i < 10) {
                    if($i == 9)
                    {
                        $czas_plus = $i+1;
                    }else
                        $czas_plus = '0' .$i+1;
                    $czas = '0' . $i;
                } else $czas = $i;

                if ($j + 1 == count($dayOfWeekArray) && $i == 21) {
                    $sql .='sum(CASE WHEN TIME_TO_SEC(CAST("'.$czas .':00:00" as Time))
                      >= TIME_TO_SEC(schedule.'.$dayOfWeekArray[$j].'_start) And TIME_TO_SEC(CAST("' . $czas_plus . ':00:00" as Time)) 
                      <= TIME_TO_SEC(schedule.'.$dayOfWeekArray[$j].'_stop) THEN 1 ELSE 0 END) as  "'.$dayOfWeekArray[$j].$i.'"';

                } else {
                    $sql .='sum(CASE WHEN TIME_TO_SEC(CAST("'.$czas .':00:00" as Time))
                      >= TIME_TO_SEC(schedule.'.$dayOfWeekArray[$j].'_start) And TIME_TO_SEC(CAST("' . $czas_plus . ':00:00" as Time)) 
                      <= TIME_TO_SEC(schedule.'.$dayOfWeekArray[$j].'_stop) THEN 1 ELSE 0 END) as "'.$dayOfWeekArray[$j].$i.'",';
                }

            }
        }
        $query->select(DB::raw($sql))->where('week_num',$number_week);
        return $query->get();
    }

}
