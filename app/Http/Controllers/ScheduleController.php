<?php

namespace App\Http\Controllers;

use App\ActivityRecorder;
use App\Department_info;
use App\MultipleDepartments;
use App\Schedule;
use App\ScheduleRelation;
use App\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Work_Hour;

class ScheduleController extends Controller
{

    public function setScheduleGet()
    {
        $userTypes = UserTypes::all();
        $departmentType = Department_info::where('id', '=', Auth::user()->department_info_id)->first()->id_dep_type;
        return view('schedule.setSchedule')
            ->with('userTypes', $userTypes)
            ->with('department_type', $departmentType);
    }
    public function setSchedulePost(Request $request)
    {
        $departmentType = Department_info::where('id', '=', Auth::user()->department_info_id)->first()->id_dep_type;
        $userTypes = UserTypes::all();
        $number_of_week = $request->show_schedule;
        $request->session()->put('number_of_week', $number_of_week);
        $request->session()->put('year', $request->schedule_year);
        $schedule_analitics = $this->setWeekDays($number_of_week,$request, [1,2]);
        return view('schedule.setSchedule')
            ->with('number_of_week',$number_of_week)
            ->with('schedule_analitics',$schedule_analitics)
            ->with('userTypes', $userTypes)
            ->with('department_type', $departmentType);
    }
    public function viewScheduleGet()
    {
        $departments = Department_info::whereIn('id_dep_type',[1,2, 6])->get();
        $departmentInfo = Department_info::getDepartmentsWithNames([1,2, 6]);
        $multipleDepartments = MultipleDepartments::select('department_info_id')->where('user_id', '=', Auth::user()->id)->pluck('department_info_id')->toArray();
        $excludedUserTypes = [1,2];
        $extendedUserTypes = UserTypes::select('id')->whereNotIn('id', $excludedUserTypes)->pluck('id')->toArray();

        $authUserType = Auth::user()->user_type_id;

        //check whether user has permission to see extended department list in view
        $directors = null;
        $directorsHR = null;
        $regionalManagers = null;
        $regionalManagersInstructors = null;
        if(in_array($authUserType, $extendedUserTypes)) {
            $directorsIds = Department_info::select('director_id')->where('director_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
            $directorsHRIds = Department_info::select('director_hr_id')->where('director_hr_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
            $regionalManagersIds = Department_info::select('regionalManager_id')->where('regionalManager_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
            $regionalManagersInstructorsIds = Department_info::select('instructor_regional_id')->where('instructor_regional_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
            $directors = User::whereIn('id', $directorsIds)->get();
            $directorsHR = User::whereIn('id', $directorsHRIds)->get();
            $regionalManagers = User::whereIn('id', $regionalManagersIds)->get();
            $regionalManagersInstructors = User::whereIn('id',$regionalManagersInstructorsIds)->get();
        }

        return view('schedule.viewSchedule')
            ->with('department_info', $departmentInfo)
            ->with('departments', $departments)
            ->with('multiple_departments', $multipleDepartments)
            ->with('directors', $directors)
            ->with('directorsHR', $directorsHR)
            ->with('regionalManagers', $regionalManagers)
            ->with('regionalManagersInstructors', $regionalManagersInstructors);

    }
    public function viewSchedulePost(Request $request)
    {
        $allDepartments = Department_info::whereIn('id_dep_type',[1,2, 6])->get();
        $departmentInfo = Department_info::getDepartmentsWithNames([1,2, 6]);
        $multipleDepartments = MultipleDepartments::select('department_info_id')->where('user_id', '=', Auth::user()->id)->pluck('department_info_id')->toArray();
        $excludedUserTypes = [1,2];
        $extendedUserTypes = UserTypes::select('id')->whereNotIn('id', $excludedUserTypes)->pluck('id')->toArray();

        $authUserType = Auth::user()->user_type_id;

        $directors = null;
        $directorsHR = null;
        $regionalManagers = null;
        $regionalManagersInstructors = null;
        //check whether user has permission to see extended department list in view
        if(in_array($authUserType, $extendedUserTypes)) {
            $directorsIds = Department_info::select('director_id')->where('director_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
            $directorsHRIds = Department_info::select('director_hr_id')->where('director_hr_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
            $regionalManagersIds = Department_info::select('regionalManager_id')->where('regionalManager_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
            $regionalManagersInstructorsIds = Department_info::select('instructor_regional_id')->where('instructor_regional_id', '!=', null)->where('id_dep_type', '=', 2)->distinct()->get();
            $directors = User::whereIn('id', $directorsIds)->get();
            $directorsHR = User::whereIn('id', $directorsHRIds)->get();
            $regionalManagers = User::whereIn('id', $regionalManagersIds)->get();
            $regionalManagersInstructors = User::whereIn('id',$regionalManagersInstructorsIds)->get();
        }


        if ($request->show_schedule == "Wybierz") {
            return view('schedule.viewSchedule');
        }

        $selected_department = explode('_',$request->department); //['name', 'value']
        $number_of_week = $request->show_schedule;
        $year = $request->year;
        $year = explode('.',$year);
        $year = $year[0];
        $query = DB::table('users')
            ->leftjoin("schedule", function ($join) use ($number_of_week,$year) {
                $join->on("schedule.id_user", "=", "users.id")
                    ->where("schedule.week_num", "=", $number_of_week)
                    ->orderBy('users.last_name')
                    ->where("schedule.year", "=", $year);
            })
            ->select(DB::raw(
                'schedule.*,
                time_to_sec(`monday_stop`)-time_to_sec(`monday_start`) as sec_monday,
                time_to_sec(`tuesday_stop`)-time_to_sec(`tuesday_start`) as sec_tuesday,
                time_to_sec(`wednesday_stop`)-time_to_sec(`wednesday_start`) as sec_wednesday,
                time_to_sec(`thursday_stop`)-time_to_sec(`thursday_start`) as sec_thursday,
                time_to_sec(`friday_stop`)-time_to_sec(`friday_start`) as sec_friday,
                time_to_sec(`saturday_stop`)-time_to_sec(`saturday_start`) as sec_saturday,
                time_to_sec(`sunday_stop`)-time_to_sec(`sunday_start`) as sec_sunday,
                users.id as id_user,
                users.first_name as user_first_name,
                users.last_name as user_last_name
                '));

        $resultArr = []; //collection on departments and their corresponding data

        switch($selected_department[0]) {
            case 'department': {
                $query = $query
                    ->where('users.department_info_id',$selected_department[1])
                    ->where('users.status_work', '=', 1)
                    ->wherein('users.user_type_id',[1,2])
                    ->orderBy('users.last_name')
                    ->get();

                array_push($resultArr, ['data' => $query, 'name' => $departmentInfo->where('id', '=', $selected_department[1])->first()->department_name . ' ' . $departmentInfo->where('id', '=', $selected_department[1])->first()->department_type]);
                break;
            }
            case 'regionalDirector': {
                $departments = $departmentInfo->where('director_id', '=', $selected_department[1])->pluck('id')->toArray();

                foreach($departments as $department) {
                    $query = $this->view_shedule_query($number_of_week, $year, $department);
                    array_push($resultArr, ['data' => $query, 'name' => $departmentInfo->where('id', '=', $department)->first()->department_name . ' ' . $departmentInfo->where('id', '=', $department)->first()->department_type]);
                }
                break;
            }
            case 'regionalMenager': {
                $departments = $departmentInfo->where('regionalManager_id', '=', $selected_department[1])->pluck('id')->toArray();

                foreach($departments as $department) {
                    $query = $this->view_shedule_query($number_of_week, $year, $department);
                    array_push($resultArr, ['data' => $query, 'name' => $departmentInfo->where('id', '=', $department)->first()->department_name . ' ' . $departmentInfo->where('id', '=', $department)->first()->department_type]);
                }
                break;
            }
            case 'regionalDirectorInstructor': {
                $departments = $departmentInfo->where('instructor_regional_id', '=', $selected_department[1])->pluck('id')->toArray();

                foreach($departments as $department) {
                    $query = $this->view_shedule_query($number_of_week, $year, $department);
                    array_push($resultArr, ['data' => $query, 'name' => $departmentInfo->where('id', '=', $department)->first()->department_name . ' ' . $departmentInfo->where('id', '=', $department)->first()->department_type]);
                }

                break;
            }
            case 'regionalDirectorHr': {
                $departments = $departmentInfo->where('director_hr_id', '=', $selected_department[1])->pluck('id')->toArray();

                foreach($departments as $department) {
                    $query = $this->view_shedule_query($number_of_week, $year, $department);
                    array_push($resultArr, ['data' => $query, 'name' => $departmentInfo->where('id', '=', $department)->first()->department_name . ' ' . $departmentInfo->where('id', '=', $department)->first()->department_type]);
                }

                break;
            }
            default: {
                $query = $query
                    ->where('users.department_info_id', Auth::user()->department_info_id)
                    ->where('users.status_work', '=', 1)
                    ->wherein('users.user_type_id',[1,2])
                    ->orderBy('users.last_name')
                    ->get();

                array_push($resultArr, ['data' => $query, 'name' => $departmentInfo->where('id', '=', Auth::user()->department_info_id)->first()->department_name . ' ' . $departmentInfo->where('id', '=', Auth::user()->department_info_id)->first()->department_type]);
                break;
            }
        }

        return view('schedule.viewSchedule')
            ->with('departments', $allDepartments)
            ->with('number_of_week',$number_of_week)
            ->with('schedule_users',$resultArr)
            ->with('department_info', $departmentInfo)
            ->with('multiple_departments', $multipleDepartments)
            ->with('directors', $directors)
            ->with('directorsHR', $directorsHR)
            ->with('regionalManagers', $regionalManagers)
            ->with('regionalManagersInstructors', $regionalManagersInstructors);
    }

    private function view_shedule_query($number_of_week, $year, $department) {
        return $query = DB::table('users')
            ->leftjoin("schedule", function ($join) use ($number_of_week,$year) {
                $join->on("schedule.id_user", "=", "users.id")
                    ->where("schedule.week_num", "=", $number_of_week)
                    ->orderBy('users.last_name')
                    ->where("schedule.year", "=", $year);
            })
            ->select(DB::raw(
                'schedule.*,
                            time_to_sec(`monday_stop`)-time_to_sec(`monday_start`) as sec_monday,
                            time_to_sec(`tuesday_stop`)-time_to_sec(`tuesday_start`) as sec_tuesday,
                            time_to_sec(`wednesday_stop`)-time_to_sec(`wednesday_start`) as sec_wednesday,
                            time_to_sec(`thursday_stop`)-time_to_sec(`thursday_start`) as sec_thursday,
                            time_to_sec(`friday_stop`)-time_to_sec(`friday_start`) as sec_friday,
                            time_to_sec(`saturday_stop`)-time_to_sec(`saturday_start`) as sec_saturday,
                            time_to_sec(`sunday_stop`)-time_to_sec(`sunday_start`) as sec_sunday,
                            users.id as id_user,
                            users.first_name as user_first_name,
                            users.last_name as user_last_name
                            '))
            ->where('users.department_info_id','=',$department)
            ->where('users.status_work', '=', 1)
            ->wherein('users.user_type_id',[1,2])
            ->orderBy('users.last_name')
            ->get();
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
                users.private_phone as user_phone,
                users.user_type_id as user_type_id
                '))
            ->wherein('users.user_type_id',[1,2])
            ->where('users.status_work', '=', 1)
            ->where('users.department_info_id',Auth::user()->department_info_id);

        $departmentInfo = Department_info::where('id',Auth::user()->department_info_id)->first();
        if(Auth::user()->user_type_id == 4 && $departmentInfo->id_dep_type == 1) {
            $query->where('users.coach_id', Auth::user()->id);
        }
        return datatables($query)->make(true);
    }


    public function setScheduleCadreGet()
    {
        $userTypes = UserTypes::all();
        return view('schedule.setScheduleCadre')->with('userTypes', $userTypes);
    }
    public function setScheduleCadrePost(Request $request)
    {
        $number_of_week = $request->show_schedule;
        $userTypes = UserTypes::all();
        $request->session()->put('number_of_week', $number_of_week);
        $request->session()->put('year', $request->schedule_year);
        $schedule_analitics = $this->setWeekDays($number_of_week,$request,$userTypes->whereNotIn('id',[1,2])->pluck('id')->toArray());
        return view('schedule.setScheduleCadre')
            ->with('number_of_week',$number_of_week)
            ->with('userTypes', $userTypes)
            ->with('schedule_analitics',$schedule_analitics);
    }
    public function viewScheduleCadreGet()
    {

        return view('schedule.viewScheduleCadre');
    }
    public function viewScheduleCadrePost(Request $request)
    {
        if ($request->show_schedule == "Wybierz") {
            return view('schedule.viewScheduleCadre');
        }
        $setter = Auth::user()->user_type_id;
        $getters = ScheduleRelation::where('setter_type_id', '=', $setter)->pluck('getter_type_id')->toArray();
        $number_of_week = $request->show_schedule;
        $userDepartmentInfo = Auth::user()->department_info_id;
        $year = $request->year;
        $year = explode('.',$year);
        $year = $year[0];
        $query = DB::table('users')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->leftjoin("schedule", function ($join) use ($number_of_week,$year) {
                $join->on("schedule.id_user", "=", "users.id")
                    ->where("schedule.week_num", "=", $number_of_week)
                    ->orderBy('users.last_name')
                    ->where("schedule.year", "=", $year);
            })
            ->select(DB::raw(
                'schedule.*,
                time_to_sec(`monday_stop`)-time_to_sec(`monday_start`) as sec_monday,
                time_to_sec(`tuesday_stop`)-time_to_sec(`tuesday_start`) as sec_tuesday,
                time_to_sec(`wednesday_stop`)-time_to_sec(`wednesday_start`) as sec_wednesday,
                time_to_sec(`thursday_stop`)-time_to_sec(`thursday_start`) as sec_thursday,
                time_to_sec(`friday_stop`)-time_to_sec(`friday_start`) as sec_friday,
                time_to_sec(`saturday_stop`)-time_to_sec(`saturday_start`) as sec_saturday,
                time_to_sec(`sunday_stop`)-time_to_sec(`sunday_start`) as sec_sunday,
                users.id as id_user,
                users.first_name as user_first_name,
                users.last_name as user_last_name,
                users.department_info_id as department_info_id,
                users.user_type_id as user_type_id,
                department_info.id_dep_type as id_dep_type
                '))
            ->where('users.status_work', '=', 1)
            ->orderBy('users.last_name')
            ->whereIn('users.user_type_id', $getters);

        if($setter == 4 || $setter == 12 || $setter == 22) {
            $query = $query->where('users.department_info_id', $userDepartmentInfo);
        }

        //In this part we handle user types who has to set engravement for people within their department and also for regional roles.
        $properCollection = collect();
        if($setter == 17) {
            $departmentInfo = Department_info::where('regionalManager_id', '=', Auth::user()->id)->pluck('id')->toArray();
            $query = $query->whereIn('department_info_id', $departmentInfo);
        }
        if($setter == 7) {
            $departmentInfo = Department_info::where('menager_id', '=', Auth::user()->id)->pluck('id')->toArray();
            $query = $query->whereIn('department_info_id', $departmentInfo);
        }
        if($setter == 15) {
//            $departmentInfo = Auth::user()->department_info->id_dep_type;
//            $query= $query->get();
//            foreach($query as $item) {
//                if($item->id_dep_type == $departmentInfo) {
//                    $properCollection->push($item);
//                }
//            }
//            $query = $properCollection;
            $departmentInfo = Department_info::where('director_id', '=', Auth::user()->id)->pluck('id')->toArray();
            $query = $query->whereIn('department_info_id', $departmentInfo);
        }
        if($setter == 5) {
            $departmentInfo = Department_info::where('hr_id', '=', Auth::user()->id)->orWhere('hr_id_second', '=', Auth::user()->id)->pluck('id')->toArray();
            $query = $query->whereIn('department_info_id', $departmentInfo);
        }
        if($setter == 14) {
            $departmentInfo = Department_info::where('director_hr_id', '=', Auth::user()->id)->pluck('id')->toArray();
            $query = $query->whereIn('department_info_id', $departmentInfo);
        }

        $query = $query->get();
        return view('schedule.viewScheduleCadre')
            ->with('number_of_week',$number_of_week)
            ->with('schedule_user',$query);
    }

    public function datatableShowUserCadreSchedule(Request $request)
    {
        $setter = $request->userType;
        $number_week =  $request->session()->get('number_of_week');
        $year = $request->year;
        $request->session()->put('year', $year);

        $getters = ScheduleRelation::where('setter_type_id', '=', $setter)->pluck('getter_type_id')->toArray();
        $userDepartmentInfo = Auth::user()->department_info_id;

        $query = DB::table('users')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->leftjoin("schedule", function ($join) use ($number_week,$year, $getters) {
                $join->on("schedule.id_user", "=", "users.id")
                    ->where("schedule.week_num", "=", $number_week)
                    ->where("schedule.year", "=", $year);
            })
            ->select(DB::raw(
                'schedule.*,
                users.id as id_user,
                users.first_name as user_first_name,
                users.last_name as user_last_name,
                users.private_phone as user_phone,
                users.department_info_id as department_info_id,
                users.user_type_id as user_type_id,
                department_info.id_dep_type as id_dep_type
                '))
            ->where('users.status_work', '=', 1)
            ->whereIn('users.user_type_id', $getters);

        if($setter == 4 || $setter == 12) {
            $query = $query->where('users.department_info_id', $userDepartmentInfo);
        }

        //In this part we handle user types who has to set engravement for people within their department and also for regional roles.
        $properCollection = collect();
        if($setter == 17) {
            $departmentInfo = Department_info::where('regionalManager_id', '=', Auth::user()->id)->pluck('id')->toArray();
            $query = $query->whereIn('department_info_id', $departmentInfo)->get();
        }
        if($setter == 7) {
            $departmentInfo = Department_info::where('menager_id', '=', Auth::user()->id)->pluck('id')->toArray();
            $query = $query->whereIn('department_info_id', $departmentInfo)->get();
        }
        if($setter == 15) {
//            $departmentInfo = Auth::user()->department_info->id_dep_type;
//            $query= $query->get();
//            foreach($query as $item) {
//                if($item->id_dep_type == $departmentInfo) {
//                    $properCollection->push($item);
//                }
//            }
//            $query = $properCollection;
            $departmentInfo = Department_info::where('director_id', '=', Auth::user()->id)->pluck('id')->toArray();
            $query = $query->whereIn('department_info_id', $departmentInfo)->get();
        }
        if($setter == 5) {
            $departmentInfo = Department_info::where('hr_id', '=', Auth::user()->id)->orWhere('hr_id_second', '=', Auth::user()->id)->pluck('id')->toArray();
            $query = $query->whereIn('department_info_id', $departmentInfo)->get();
        }
        if($setter == 14) {
            $departmentInfo = Department_info::where('director_hr_id', '=', Auth::user()->id)->pluck('id')->toArray();
            $query = $query->whereIn('department_info_id', $departmentInfo)->get();
        }

        return datatables($query)->make(true);
    }

    public function saveSchedule(Request $request)
    {

        $start_hours = $request->start_hours;
        $stop_hours = $request->stop_hours;
        $reasons = $request->reasons;

        if(isset($request->isPaid)) {
            $paid = $request->isPaid;
        }
        else {
            $paid = array();
            for($i = 0 ; $i < count($reasons); $i++) {
                if($reasons[$i]) {
                    array_push($paid, 'false');
                }
                else {
                    array_push($paid, 'true');
                }
            }
        }

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
            $result = Schedule::where('id_user', '=', $id_user)
                ->where('year','=',$year)
                ->where('week_num','=',$number_week)->get();
            //dd($result->first());
            if($result->first() !== null){
                $schedule = $result->first();
                $schedule->id_manager = Auth::user()->id;
            }else {
                $schedule = new Schedule();
                $schedule->id_user = $id_user;
                $schedule->id_manager = Auth::user()->id;
            }
        }else{
            $schedule = Schedule::find($schedule_id);
            $schedule->id_manager_edit = Auth::user()->id;
        }
            $schedule->year = $year;
            $schedule->week_num = $number_week;

            $schedule->monday_start = $start_hours[0];
            $schedule->monday_stop = $stop_hours[0];
            $schedule->monday_comment =  $reasons[0];
            $schedule->mondayPaid =  $paid[0] == 'false' ? 0 : 1;

            $schedule->tuesday_start = $start_hours[1];
            $schedule->tuesday_stop = $stop_hours[1];
            $schedule->tuesday_comment =  $reasons[1];
            $schedule->tuesdayPaid =  $paid[1] == 'false' ? 0 : 1;

            $schedule->wednesday_start = $start_hours[2];
            $schedule->wednesday_stop = $stop_hours[2];
            $schedule->wednesday_comment =  $reasons[2];
            $schedule->wednesdayPaid =  $paid[2] == 'false' ? 0 : 1;

            $schedule->thursday_start = $start_hours[3];
            $schedule->thursday_stop = $stop_hours[3];
            $schedule->thursday_comment =  $reasons[3];
            $schedule->thursdayPaid =  $paid[3] == 'false' ? 0 : 1;

            $schedule->friday_start = $start_hours[4];
            $schedule->friday_stop = $stop_hours[4];
            $schedule->friday_comment =  $reasons[4];
            $schedule->fridayPaid =  $paid[4] == 'false' ? 0 : 1;

            $schedule->saturday_start = $start_hours[5];
            $schedule->saturday_stop = $stop_hours[5];
            $schedule->saturday_comment =  $reasons[5];
            $schedule->saturdayPaid =  $paid[5] == 'false' ? 0 : 1;

            $schedule->sunday_start = $start_hours[6];
            $schedule->sunday_stop = $stop_hours[6];
            $schedule->sunday_comment =  $reasons[6];
            $schedule->sundayPaid = $paid[6] == 'false' ? 0 : 1;

            if(isset($request->leader)) {
                $schedule->leader = $request->leader == 'false' ? 0 : 1;
            }
            else {
                $schedule->leader = 0;
            }

            $schedule->save();

            $log = array("T" => "Edycja grafiku");
            $log = array_merge($log, $schedule->toArray());

            new ActivityRecorder($log, 22,2);

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
    private function setWeekDays($number_week,$request, $userTypesArrays)
    {
        $dayOfWeekArray= array('monday' ,'tuesday','wednesday','thursday','friday','saturday','sunday');
        $query = DB::table('schedule');
        $sql = '';
        for($j=0;$j<count($dayOfWeekArray);$j++) {
            for ($i = 8; $i < 21; $i++) {
                if ($i < 10) {
                    if($i == 9)
                    {
                        $czas_plus = $i+1;
                        $czas = '0' . $i;
                    }else {
                        $czas_plus = '0' . ($i + 1);
                        $czas = '0' . $i;
                    }
                }
                else{
                    $czas = $i;
                    $czas_plus = $i+1;
                }

                if ($j + 1 == count($dayOfWeekArray) && $i == 20) {
                    $sql .='sum(CASE WHEN Hour(CAST("'.$czas .':00:00" as Time))
                      >= Hour(schedule.'.$dayOfWeekArray[$j].'_start) and Hour(CAST("' . $czas_plus . ':00:00" as Time))
                      <= Hour(schedule.'.$dayOfWeekArray[$j].'_stop) THEN 1 ELSE 0 END) as  "'.$dayOfWeekArray[$j].$i.'"';

                } else {
                    $sql .='sum(CASE WHEN Hour(CAST("'.$czas .':00:00" as Time))
                      >= Hour(schedule.'.$dayOfWeekArray[$j].'_start) and Hour(CAST("' . $czas_plus . ':00:00" as Time))
                      <= Hour(schedule.'.$dayOfWeekArray[$j].'_stop) THEN 1 ELSE 0 END) as "'.$dayOfWeekArray[$j].$i.'",';
                }
            }
        }
        $query->select(DB::raw($sql))
            ->join('users','users.id','schedule.id_user')
            ->where('week_num',$number_week)
            ->whereIn('user_type_id', $userTypesArrays)
            ->where('year',$request->session()->get('year'))
            ->where('users.department_info_id',Auth::user()->department_info_id);

        return $query->get();
    }

    public function timesheetGet() {

        return view('schedule.timesheet');
    }

    public function timesheetPost(Request $request) {
        $date_start = $request->timesheet_date_start;
        $date_stop = $request->timesheet_date_stop;

        $hours = DB::table('work_hours')
            ->select(DB::raw('
                first_name,
                last_name,
                rate,
                SUM(success) as user_success,
                SUM(time_to_sec(accept_stop) - time_to_sec(accept_start)) / 3600 as user_sum
            '))
            ->join('users', 'users.id', '=', 'work_hours.id_user')
            ->whereBetween('work_hours.date', [$date_start, $date_stop])
            ->whereIn('users.user_type_id', [1,2])
            ->whereIn('work_hours.status', [4,5])
            ->where('users.department_info_id', '=', Auth::user()->department_info_id)
            ->groupBy('users.id')
            ->orderBy('users.last_name')
            ->get();

        return view('schedule.timesheet')
            ->with('date_start', $date_start)
            ->with('date_stop', $date_stop)
            ->with('hours', $hours);
    }

    public function timesheetCadreGet() {
        return view('schedule.timesheetCadre');
    }

    public function timesheetCadrePost(Request $request) {
        $date_start = $request->timesheet_date_start;
        $date_stop = $request->timesheet_date_stop;

        $hours = DB::table('work_hours')
            ->select(DB::raw('
                first_name,
                last_name,
                SUM(time_to_sec(accept_stop) - time_to_sec(accept_start)) / 3600 as user_sum
            '))
            ->join('users', 'users.id', '=', 'work_hours.id_user')
            ->whereNotIn('users.user_type_id', [1,2])
            ->whereBetween('work_hours.date', [$date_start, $date_stop])
            ->whereIn('work_hours.status', [4,5])
            ->groupBy('users.id')
            ->orderBy('users.last_name')
            ->get();

            return view('schedule.timesheetCadre')
                ->with('date_start', $date_start)
                ->with('date_stop', $date_stop)
                ->with('hours', $hours);
    }

}
