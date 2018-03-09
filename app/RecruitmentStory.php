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
        $result = DB::table('candidate')
            ->select(DB::Raw("users.first_name,users.last_name,count(candidate.id) as count_flow,
                `departments`.`name`"))
            ->join('users','users.id','candidate.cadre_id')
            ->join('department_info','department_info.id','users.department_info_id')
            ->join('departments','departments.id','department_info.id_dep')
            ->wherebetween('candidate.created_at',[$data_start.' 00:00:00',$data_stop.' 23:00:00'])
            ->groupBy('candidate.cadre_id')
            ->get();
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
            $data = DB::table('recruitment_attempt')
                ->select(DB::raw('
                    departments.name as dep_name,
                    department_type.name as dep_name_type,
                    count(recruitment_attempt.id) as counted
                '))
                ->join('users', 'users.id', 'recruitment_attempt.cadre_id')
                ->join('department_info', 'users.department_info_id', 'department_info.id')
                ->join('departments', 'departments.id', 'department_info.id_dep')
                ->join('department_type', 'department_type.id', 'department_info.id_dep_type')
                ->whereBetween('interview_date', [$date_start . ' 01:00:00', $date_stop . ' 23:00:00'])
                ->groupBy('users.department_info_id')
                ->get();
        } else if ($select_type == 1) {
            $data = DB::table('recruitment_attempt')
                ->select(DB::raw('
                    first_name,
                    last_name,
                    count(recruitment_attempt.id) as counted
                '))
                ->join('users', 'users.id', 'recruitment_attempt.cadre_id')
                ->whereBetween('interview_date', [$date_start . ' 01:00:00', $date_stop . ' 23:00:00'])
                ->groupBy('users.id')
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
         ,`user`.`first_name`,`user`.`last_name`,`departments`.`name`'))
            ->join('users as user','user.id','users.id_manager')
            ->leftjoin('candidate','candidate.id','users.candidate_id')
            ->join('department_info','department_info.id','users.department_info_id')
            ->join('departments','departments.id','department_info.id_dep')
            ->groupby('users.id_manager')
            ->having('add_user','!=',0)
            ->get();
        return $date;
    }
}
