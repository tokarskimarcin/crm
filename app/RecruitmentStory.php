<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecruitmentStory extends Model
{
    protected $table = 'recruitment_story';

    public function cadre() {
        return $this->belongsTo('App\User', 'cadre_id');
    }

    public function edit_cadre() {
        return $this->belongsTo('App\User', 'cadre_edit_id');
    }
    public function candidate() {
        return $this->belongsTo('App\Candidate', 'candidate_id');
    }

    public function recruitmentAttempt() {
        return $this->belongsTo('App\RecruitmentAttempt', 'recruitment_attempt_id');
    }

    public function attemptStatus() {
        return $this->belongsTo('App\AttemptStatus', 'attempt_status_id');
    }

    public function attemptResult() {
        return $this->belongsTo('App\AttemptResult', 'attempt_result_id');
    }

    public function attemptLevel() {
        return $this->belongsTo('App\AttemptStatus', 'attempt_status_id');
    }

    public function lastAttemptResult() {
        return $this->belongsTo('App\AttemptResult', 'last_attempt_result_id');
    }

    public function lastAttemptLevel() {
        return $this->belongsTo('App\AttemptStatus', 'last_attempt_status_id');
    }


    public static function getReportFlowData($data_start,$data_stop){
        $data_start = $data_start . ' 00:00:00';
        $data_stop = $data_stop . ' 23:00:00';

        $result = DB::table('department_info')
            ->select(DB::raw('
                users.last_name,
                users.first_name,
                departments.name, 
                department_type.name as dep_type, 
                SUM(CASE WHEN `candidate`.`created_at` between "' . $data_start . '" and "' . $data_stop . '" THEN 1 ELSE 0 END) as count_flow
            '))
            ->join('departments','departments.id','department_info.id_dep')
            ->join('department_type','department_type.id','department_info.id_dep_type')
            ->leftjoin('candidate', 'candidate.department_info_id', 'department_info.id')
            ->leftjoin('users', 'users.id', 'department_info.hr_id')
            ->groupBy('department_info.id')
            ->orderBy('count_flow', 'desc')
            ->where('commission_janky', '!=', 0)
            ->get();
//        $result = DB::table('candidate')
//            ->select(DB::Raw("department_info.id as depid, users.id as uid, users.first_name,users.last_name,count(candidate.id) as count_flow,
//                `departments`.`name`,`department_type`.`name` as dep_type"))
//            ->join('users','users.id','candidate.cadre_id')
//            ->join('department_info','department_info.id','users.department_info_id')
//            ->join('departments','departments.id','department_info.id_dep')
//            ->join('department_type','department_type.id','department_info.id_dep_type')
//            ->wherebetween('candidate.created_at',[$data_start.' 00:00:00',$data_stop.' 23:00:00'])
//            ->where('users.user_type_id','=','5')
//            ->groupBy('candidate.cadre_id')
//            ->orderBy('count_flow','desc')
//            ->get();
        //dd($result);
        return $result;
    }

    public static function getReportTrainingData($data_start,$data_stop){
        return DB::table('group_training')
            ->select(DB::raw('
                sum(candidate_choise_count) as sum_choise,
                sum(candidate_absent_count) as sum_absent,
                departments.name as dep_name,
                department_type.name as dep_name_type
            '))
            ->join('department_info', 'group_training.department_info_id', 'department_info.id')
            ->join('departments', 'departments.id', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', 'department_info.id_dep_type')
            ->whereBetween('training_date', [$data_start, $data_stop])
            ->groupBy('department_info.id')
            ->get();
    }

    /**
     * Pobranie danych na temat ilości rozmów rekrutacyjnych
     */
    public static function getReportInterviewsData($date_start, $date_stop, $select_type) {
        if ($select_type == 0) {
            $dataCount = DB::table('recruitment_story')
                ->select(DB::raw('
                    departments.id as dep_id,
                    departments.name as dep_name,
                    department_type.name as dep_name_type,
                    count(recruitment_story.id) as counted
                '))
                ->join('candidate', 'candidate.id', 'recruitment_story.candidate_id')
                ->join('department_info', 'candidate.department_info_id', 'department_info.id')
                ->join('departments', 'departments.id', 'department_info.id_dep')
                ->join('department_type', 'department_type.id', 'department_info.id_dep_type')
                ->whereBetween('recruitment_story.created_at', [$date_start . ' 01:00:00', $date_stop . ' 23:00:00'])
                ->where('recruitment_story.attempt_status_id','=',17)
                ->groupBy('candidate.department_info_id')
                ->orderBy('counted','desc')
                ->get();

            $deps = Department_info::all();

            $data = [];
            foreach ($deps as $dep) {
                $dep_data = new \stdClass();
                $dep_data->dep_name = $dep->departments->name;
                $dep_data->dep_name_type = $dep->department_type->name;
                $dep_data->counted = 0;
                foreach($dataCount as $item) {
                    if ($item->dep_id == $dep->id) {
                        $dep_data->counted = $item->counted;
                    }
                }
                $data[] = $dep_data;
            }
        } else if ($select_type == 1) {
            $data = DB::table('recruitment_story')
                ->select(DB::raw('
                    first_name,
                    last_name,
                    count(recruitment_story.id) as counted
                '))
                ->join('users', 'users.id', 'recruitment_story.cadre_id')
                ->whereBetween('recruitment_story.created_at', [$date_start . ' 01:00:00', $date_stop . ' 23:00:00'])
                ->where('recruitment_story.attempt_status_id','=',17)
                ->groupBy('users.id')
                ->orderBy('counted','desc')
                ->get();
        }
        return $data;
    }

    /**
     *  Przygotowanie danych do raportu nowych kont
     */
    public static function getReportNewAccountData($date_start, $date_stop){

        $date = DB::table('users')->
        select(DB::raw('sum(case when `users`.`start_work` between "'.$date_start.'" and "'.$date_stop.'" then 1 else 0 end) as add_user,
         sum(Case when `users`.`candidate_id` is not null and `users`.`start_work` between "'.$date_start.'" and "'.$date_stop.'" 
          and `candidate`.`created_at` < `users`.`created_at`
         then 1 else 0 end ) as add_candidate
         ,`user`.`first_name`,`user`.`last_name`,`departments`.`name`,`department_type`.`name` as dep_type'))
            ->join('users as user','user.id','users.id_manager')
            ->leftjoin('candidate','candidate.id','users.candidate_id')
            ->join('department_info','department_info.id','users.department_info_id')
            ->join('departments','departments.id','department_info.id_dep')
            ->join('department_type', 'department_type.id', 'department_info.id_dep_type')
            ->where('user.user_type_id','=','5')
            ->groupby('users.id_manager')
            ->having('add_user','!=',0)
            ->orderBy('add_user','desc')
            ->get();
        return $date;
    }
}
