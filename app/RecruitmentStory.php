<?php

namespace App;

use App\Utilities\Dates\MonthFourWeeksDivision;
use function foo\func;
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

        $candidate_source = CandidateSource::where('deleted', '=', 0)->get();

        $result = DB::table('department_info')
            ->select(DB::raw('               
                departments.name, 
                department_type.name as dep_type, 
                department_info.id as dep_info_id,
                SUM(CASE WHEN `candidate`.`created_at` between "' . $data_start . '" and "' . $data_stop . '" THEN 1 ELSE 0 END) as count_flow
            '))
            ->join('departments','departments.id','department_info.id_dep')
            ->join('department_type','department_type.id','department_info.id_dep_type')
            ->leftjoin('candidate', 'candidate.department_info_id', 'department_info.id')
//            ->join('candidate_source', 'candidate.candidate_source_id', 'candidate_source.id')
            ->leftjoin('users', 'users.id', 'department_info.hr_id')
            ->groupBy('department_info.id')
            ->orderBy('count_flow', 'desc')
            ->where('commission_janky', '!=', 0)
            ->get();

        $result2 = $result->map(function ($item) use ($data_start, $data_stop, $candidate_source) {
           forEach($candidate_source as $candidate) {
               $result = DB::table('department_info')
                   ->select(DB::raw('  
                candidate_source.name as source_name,
                SUM(CASE WHEN `candidate`.`created_at` between "' . $data_start . '" and "' . $data_stop . '" THEN 1 ELSE 0 END) as count_source
            '))
                   ->join('departments','departments.id','department_info.id_dep')
                   ->join('department_type','department_type.id','department_info.id_dep_type')
                   ->leftjoin('candidate', 'candidate.department_info_id', 'department_info.id')
                    ->join('candidate_source', 'candidate.candidate_source_id', 'candidate_source.id')
                   ->leftjoin('users', 'users.id', 'department_info.hr_id')
                   ->groupBy('department_info.id')
                   ->orderBy('count_source', 'desc')
                   ->where('commission_janky', '!=', 0)
                   ->where('candidate_source_id', '=', $candidate->id)
                   ->where('department_info.id', '=', $item->dep_info_id)
                   ->first();
               if(!is_object($result)){
                   $result = new \stdClass();
                   $result->source_name = $candidate->name;
                   $result->count_source = 0;
               }
               $candidate_id = $candidate->id;
               $item->$candidate_id = $result;
           }
            return $item;
        });
        return $result2;
    }

    public static function getReportTrainingDataAndHire($data_start,$data_stop){
        //4712
        $records = DB::table('group_training')
            ->select(DB::raw('
                group_training.id as groupTrainingID,
                candidate.id as candidateID,
                group_training.training_date,
                recruitment_story.recruitment_attempt_id,
                0 as last_recruitment_story_id,
                0 as attempt_status_id,
                0 as userID,
                0 as departmentInfoId
            '))
            ->join('candidate_training','candidate_training.training_id','group_training.id')
            ->join('recruitment_story','recruitment_story.id','candidate_training.completed_training')
            ->join('recruitment_attempt','recruitment_attempt.id','recruitment_story.recruitment_attempt_id')
            ->join('candidate','candidate.id','recruitment_story.candidate_id')
            ->whereBetween('group_training.training_date', [$data_start, $data_stop])
            ->where('recruitment_attempt.training_date','=',null)
            ->where('recruitment_attempt.status',1)
            ->where('recruitment_story.attempt_status_id',8)
            ->where('group_training.training_stage',1)
            ->get();

        $records = $records->map(function ($item){
            $lookingData = RecruitmentStory::where('recruitment_attempt_id',$item->recruitment_attempt_id)->orderby('id','desc')->first();
            $item->last_recruitment_story_id = $lookingData->id;
            $item->attempt_status_id = $lookingData->attempt_status_id;
            if($item->attempt_status_id == 10){
                $findCandidateInUser = User::where('candidate_id',$item->candidateID)->first();
                if(!empty($findCandidateInUser)){
                    $item->userID = $findCandidateInUser->id;
                    $item->departmentInfoId = $findCandidateInUser->department_info_id;
                }
            }
            return  $item;
        })->where('attempt_status_id',10)->where('userID','!=',0);

        return $records;
    }

    public static function getCandidatesTrainedStageOne($date_start, $date_stop, $dividedMonth){
        $groupByWeeksString = 'CASE ';

        for($i = 0; $i < count($dividedMonth); $i++){
            $groupByWeeksString .= 'WHEN g.training_date BETWEEN "'
                .$dividedMonth[$i]->firstDay
                .'" AND "'
                .$dividedMonth[$i]->lastDay
                .'" THEN '
                .($i+1).' ';
        }
        $groupByWeeksString .= 'END';

        $candidates = RecruitmentStory::select('recruitment_story.candidate_id',
            'recruitment_story.recruitment_attempt_id',
            'g.department_info_id',
            'g.training_date',
            DB::raw($groupByWeeksString.' as week'))
            ->leftJoin('candidate as c','recruitment_story.candidate_id','c.id')
            ->leftJoin('recruitment_attempt as ra','recruitment_story.recruitment_attempt_id','ra.id')
            ->leftJoin('candidate_training as ct','ct.completed_training','recruitment_story.id')
            ->leftJoin('group_training as g','ct.training_id','g.id')
            ->where('recruitment_story.attempt_status_id',8)
            ->where('ra.status',1)
            //->whereNull('ra.training_date')
            ->where('g.training_stage',1)
            ->whereBetween('g.training_date', [$date_start, $date_stop]);
        //dd($candidates->get()->groupBy('week'));

        return collect($candidates->get()->toArray())->unique();
    }

    public static function getReportTrainingDataAndHireShorter($candidates){
        $weeksDataCandidates = $candidates;
        $usersHiredWithTrainingDateBetweenStartAndStopDate = RecruitmentStory::select('u.candidate_id', DB::raw('max(u.id) as user_id'))
            ->join('users as u','u.candidate_id','recruitment_story.candidate_id')
            ->where('recruitment_story.attempt_status_id',10)
            ->whereNotNull('u.id')
            ->where('u.id','<>',0)
            ->whereIn('recruitment_story.candidate_id', $weeksDataCandidates->pluck('candidate_id')->toArray())
            ->whereIn('recruitment_story.recruitment_attempt_id', $weeksDataCandidates->pluck('recruitment_attempt_id')->toArray())
            ->groupBy('u.candidate_id')
            ->get();

        $combinedData = $usersHiredWithTrainingDateBetweenStartAndStopDate->map(function ($item) use($weeksDataCandidates){
            $candidateData = $weeksDataCandidates->where('candidate_id',$item->candidate_id)->first();
            $item = $item->toArray();
            $item["recruitment_attempt_id"] = $candidateData["recruitment_attempt_id"];
            $item["department_info_id"] = $candidateData["department_info_id"];
            $item["training_date"] = $candidateData["training_date"];
            $item["week"] = $candidateData["week"];
            return (object)$item;
        });
        return $combinedData;
    }

    public static function getReportTrainingDataAndHireShort($data_start,$data_stop){
        $myRecords = DB::table('group_training')
            ->select('u.id','u.candidate_id','u.department_info_id')
            ->join('candidate_training','candidate_training.training_id','group_training.id')
            ->join('recruitment_story','recruitment_story.id','candidate_training.completed_training')
            ->join('recruitment_attempt','recruitment_attempt.id','recruitment_story.recruitment_attempt_id')
            ->join('candidate','candidate.id','recruitment_story.candidate_id')
            ->join('users as u','u.candidate_id','candidate.id')
            ->whereBetween('group_training.training_date', [$data_start, $data_stop])
            ->where('recruitment_attempt.training_date','=',null)
            ->where('recruitment_attempt.status',1)
            ->where('recruitment_story.attempt_status_id',8)
            ->where('group_training.training_stage',1)
            ->get();


//        $users = User::select('id', 'department_info_id', 'candidate_id')->get();

        $records = DB::table('group_training')
            ->select(DB::raw('
                group_training.id as groupTrainingID,
                candidate.id as candidateID,
                group_training.training_date,
                recruitment_story.recruitment_attempt_id,
                0 as last_recruitment_story_id,
                0 as attempt_status_id,
                0 as userID,
                0 as departmentInfoId
            '))
            ->join('candidate_training','candidate_training.training_id','group_training.id')
            ->join('recruitment_story','recruitment_story.id','candidate_training.completed_training')
            ->join('recruitment_attempt','recruitment_attempt.id','recruitment_story.recruitment_attempt_id')
            ->join('candidate','candidate.id','recruitment_story.candidate_id')
            ->whereBetween('group_training.training_date', [$data_start, $data_stop])
            ->where('recruitment_attempt.training_date','=',null)
            ->where('recruitment_attempt.status',1)
            ->where('recruitment_story.attempt_status_id',8)
            ->where('group_training.training_stage',1)
            ->get();

        $ids = $records->pluck('recruitment_attempt_id')->toArray();

        $halfOfArr = (int)floor(count($ids) / 2);
        $firstIdsArr = [];
        $secondIdsArr = [];
        $allIds = count($ids);

        for($i = 0; $i < $halfOfArr; $i++) {
            array_push($firstIdsArr, $ids[$i]);
        }

        for($i = $halfOfArr; $i < $allIds; $i++) {
            array_push($secondIdsArr, $ids[$i]);
        }

        $maxIds1 = RecruitmentStory::select(DB::raw('MAX(id) as id'))->whereIn('recruitment_attempt_id', $firstIdsArr)->groupBy('recruitment_attempt_id')->pluck('id')->toArray();
        $maxIds2 = RecruitmentStory::select(DB::raw('MAX(id) as id'))->whereIn('recruitment_attempt_id', $secondIdsArr)->groupBy('recruitment_attempt_id')->pluck('id')->toArray();

        $recruitment1 = RecruitmentStory::select('id','attempt_status_id', 'recruitment_attempt_id')->whereIn('id', $maxIds1)->get();
        $recruitment2 = RecruitmentStory::select('id','attempt_status_id', 'recruitment_attempt_id')->whereIn('id', $maxIds2)->get();

        $records = $records->map(function ($item) use($recruitment1, $recruitment2) {
            $lookingData1 = $recruitment1->where('recruitment_attempt_id',$item->recruitment_attempt_id)->first();
            $lookingData2 = null;
            if(!isset($lookingData1)) {
                $lookingData2 = $recruitment2->where('recruitment_attempt_id',$item->recruitment_attempt_id)->first();
            }

            if(isset($lookingData1)) {
                $item->last_recruitment_story_id = $lookingData1->id;
                $item->attempt_status_id = $lookingData1->attempt_status_id;
            }
            else {
                $item->last_recruitment_story_id = $lookingData2->id;
                $item->attempt_status_id = $lookingData2->attempt_status_id;
            }

            if($item->attempt_status_id == 10){
                $findCandidateInUser = User::where('candidate_id',$item->candidateID)->first();
                if(isset($findCandidateInUser)){
                    $item->userID = $findCandidateInUser->id;
                    $item->departmentInfoId = $findCandidateInUser->department_info_id;
                }
            }
            return  $item;
        })->where('attempt_status_id',10)->where('userID','!=',0);

        //dd($myRecords->groupBy('department_info_id')->toArray(),$records->groupBy('departmentInfoId'));
        return $records;
    }


    public static function getReportTrainingData($data_start,$data_stop){
        $records = DB::table('group_training')
            ->select(DB::raw('
                sum(candidate_choise_count) as sum_choise,
                sum(candidate_absent_count) as sum_absent,
                group_training.training_stage,
                department_info.id as dep_id,
                departments.name as dep_name,
                department_type.name as dep_name_type
            '))
            ->join('department_info', 'group_training.department_info_id', 'department_info.id')
            ->join('departments', 'departments.id', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', 'department_info.id_dep_type')
            ->whereBetween('training_date', [$data_start, $data_stop])
            ->groupBy('department_info.id','training_stage')
            ->get();

        $deps = Department_info::where('commission_avg','!=',0)->get();
        $data=[];
        $departmentUserArray = [];
        foreach ($deps as $dep) {
            if(!in_array($dep->id,$departmentUserArray))
            {
                $dep_data = new \stdClass();
                $dep_data->dep_id = $dep->id;
                $dep_data->countHireUserFromFirstTrainingGroup = 0;
                $dep_data->dep_name = $dep->departments->name;
                $dep_data->dep_name_type = $dep->department_type->name;
                $dep_data->sum_choise_stageOne = 0;
                $dep_data->sum_absent_stageOne = 0;
                $dep_data->sum_choise_stageTwo = 0;
                $dep_data->sum_absent_stageTwo = 0;
                $dep_data->procScore = 0;
                foreach($records as $item) {
                    if ($item->dep_id == $dep->id) {
                        if($item->training_stage == 1){
                            $dep_data->sum_choise_stageOne = $item->sum_choise;
                            $dep_data->sum_absent_stageOne = $item->sum_absent;
                        }else{
                            $dep_data->sum_choise_stageTwo = $item->sum_choise;
                            $dep_data->sum_absent_stageTwo = $item->sum_absent;
                        }
                    }
                }
                $data[] = $dep_data;
                array_push($departmentUserArray,$dep->id);
            }
        }

        return collect($data)->sortByDesc('sum_choise');
    }

    public static function getReportTrainingDataShorter($dividedMonth){

        $groupByWeeksString = 'CASE ';

        for($i = 0; $i < count($dividedMonth); $i++){
            $groupByWeeksString .= 'WHEN training_date BETWEEN "'
                .$dividedMonth[$i]->firstDay
                .'" AND "'
                .$dividedMonth[$i]->lastDay
                .'" THEN '
                .($i+1).' ';
        }
        $groupByWeeksString .= 'END';
        $records = GroupTraining::select(
            'department_info.id as dep_id',
            DB::raw($groupByWeeksString.' as week'),
            DB::raw('sum(candidate_choise_count) as sum_choise')
        )
            ->join('department_info', 'group_training.department_info_id', 'department_info.id')
            ->where('training_stage',1)
            ->whereBetween('training_date', [$dividedMonth[0]->firstDay, $dividedMonth[count($dividedMonth)-1]->lastDay])
            ->groupBy('department_info.id','week');

        return $records->get();
    }

    public static function getReportTrainingDataShort($data_start,$data_stop, $deps){
        $records = DB::table('group_training')
            ->select(DB::raw('
                sum(candidate_choise_count) as sum_choise,
                group_training.training_stage,
                department_info.id as dep_id
            '))
            ->join('department_info', 'group_training.department_info_id', 'department_info.id')
            ->whereBetween('training_date', [$data_start, $data_stop])
            ->groupBy('department_info.id','training_stage')
            ->get();


        $data=[];
        $departmentUserArray = [];
        foreach ($deps as $dep) {
            if(!in_array($dep->id,$departmentUserArray))
            {
                $dep_data = new \stdClass();
                $dep_data->dep_id = $dep->id;
                $dep_data->countHireUserFromFirstTrainingGroup = 0;
                $dep_data->sum_choise_stageOne = 0;
                $dep_data->procScore = 0;
                foreach($records as $item) {
                    if ($item->dep_id == $dep->id) {
                        if($item->training_stage == 1){
                            $dep_data->sum_choise_stageOne = $item->sum_choise;
                        }
                    }
                }
                $data[] = $dep_data;
                array_push($departmentUserArray,$dep->id);
            }
        }
        return collect($data)->sortByDesc('sum_choise');
    }

    /**
     * Pobranie danych na temat ilości rozmów rekrutacyjnych
     */
    public static function getReportInterviewsData($date_start, $date_stop, $select_type) {
        if ($select_type == 0) {
            $dataCount = DB::table('recruitment_story')
                ->select(DB::raw('
                    count(*) as count_record,
                    department_info.id as dep_id,
                    departments.name as dep_name,
                    department_type.name as dep_name_type,
                    recruitment_story.attempt_result_id
                '))
                ->join('candidate', 'candidate.id', 'recruitment_story.candidate_id')
                ->join('department_info', 'candidate.department_info_id', 'department_info.id')
                ->join('departments', 'departments.id', 'department_info.id_dep')
                ->join('department_type', 'department_type.id', 'department_info.id_dep_type')
                ->whereBetween('recruitment_story.created_at', [$date_start . ' 01:00:00', $date_stop . ' 23:00:00'])
                ->where('recruitment_story.attempt_status_id','=',17)
                ->groupBy('candidate.department_info_id','recruitment_story.attempt_result_id')
                ->get();

            //zlicza ile jest poszczególnych wyników rekrutacji

            $deps = Department_info::all();

            $data = [];
            foreach ($deps as $dep) {

                $status_other_offert = $dataCount
                    ->where('attempt_result_id', '=', 2)
                    ->where('dep_id', '=', $dep->id)->pluck('count_record')->first();
                $status_not_interested = $dataCount
                    ->where('attempt_result_id', '=', 3)
                    ->where('dep_id', '=', $dep->id)->pluck('count_record')->first();
                $status_other = $dataCount
                    ->where('attempt_result_id', '=', 5)
                    ->where('dep_id', '=', $dep->id)->pluck('count_record')->first();
                $status_finished_positive = $dataCount
                    ->where('attempt_result_id', '=', 6)
                    ->where('dep_id', '=', $dep->id)->pluck('count_record')->first();

                $dataCount_all = $dataCount->where('dep_id', '=', $dep->id)->sum('count_record');
                $dep_data = new \stdClass();
                $dep_data->dep_name = $dep->departments->name;
                $dep_data->dep_name_type = $dep->department_type->name;
                $dep_data->counted = 0;
                $dep_data->other_offer = 0;
                $dep_data->not_interested = 0;
                $dep_data->other = 0;
                $dep_data->finished_positive = 0;


                $dep_data->counted = $dataCount_all != null ? $dataCount_all : 0;
                $dep_data->other_offer = $status_other_offert != null ? $status_other_offert : 0;
                $dep_data->not_interested = $status_not_interested != null ? $status_not_interested : 0;
                $dep_data->other = $status_other != null ? $status_other : 0;
                $dep_data->finished_positive = $status_finished_positive != null ? $status_finished_positive : 0;

                array_push($data, $dep_data);
                    }
            }
         else if ($select_type == 1) {
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

        return collect($data)->sortByDesc('counted');
    }

    /**
     *  Przygotowanie danych do raportu nowych kont
     */
    public static function getReportNewAccountData($date_start, $date_stop){
//        $all_hr = User::where('user_type_id','=','5')
//                ->select('users.id','users.first_name','users.last_name','departments.name','department_type.name as dep_type')
//                ->join('department_info','department_info.id','users.department_info_id')
//                ->join('departments','departments.id','department_info.id_dep')
//                ->join('department_type', 'department_type.id', 'department_info.id_dep_type')
//                ->where('status_work','=',1)
//                ->get();

        //collection of all working hr and informations about them
        $all_hr_from_departments = Department_info::
            select('users.id','users.first_name','users.last_name','departments.name','department_type.name as dep_type','department_info.id as departmentInfoId')
            ->join('users','users.id','department_info.hr_id')
            ->join('departments','departments.id','department_info.id_dep')
            ->join('department_type', 'department_type.id', 'department_info.id_dep_type')
            ->where('status_work','=',1)
            ->get();

        $all_hr_from_departments->map(function ($item) use ($date_start, $date_stop) {

        //All candidates assigned to given HR employee
        $all_candidate = Candidate::where('cadre_id','=',$item->id)
            ->get();

            //Ammount of new users from given date range which filed created by indices given HR employee
            $all_hire_candidate_new = User::where('created_by','=',$item->id)
                    ->whereBetween('start_work',[$date_start,$date_stop])
                    ->where('department_info_id','=',$item->departmentInfoId)
                    ->count();

            $all_hire_candidate_reactive = User::whereIn('id',$all_candidate->pluck('id_user')->toArray())
            ->whereBetween('start_work',[$date_start,$date_stop])
            ->where('department_info_id','=',$item->departmentInfoId)
            ->count();
            $item->add_user = $all_hire_candidate_new;
            $item->add_candidate = $all_hire_candidate_reactive;
           return $item;
        });

//        dd($all_hr);
//        $date = DB::table('users')->
//        select(DB::raw('sum(case when `users`.`start_work` between "'.$date_start.'" and "'.$date_stop.'" then 1 else 0 end) as add_user,
//         sum(Case when `users`.`candidate_id` is not null and `users`.`start_work` between "'.$date_start.'" and "'.$date_stop.'"
//          and `candidate`.`created_at` < `users`.`created_at`
//         then 1 else 0 end ) as add_candidate
//         ,`user`.`first_name`,`user`.`last_name`,`departments`.`name`,`department_type`.`name` as dep_type'))
//            ->join('users as user','user.id','users.id_manager')
//            ->leftjoin('candidate','candidate.id','users.candidate_id')
//            ->join('department_info','department_info.id','users.department_info_id')
//            ->join('departments','departments.id','department_info.id_dep')
//            ->join('department_type', 'department_type.id', 'department_info.id_dep_type')
//            ->where('user.user_type_id','=','5')
//            ->groupby('users.id_manager')
//            ->having('add_user','!=',0)
//            ->orderBy('add_user','desc')
//            ->get();
        return $all_hr_from_departments;
    }
}
