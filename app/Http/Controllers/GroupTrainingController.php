<?php

namespace App\Http\Controllers;


use App\AttemptResult;
use App\Candidate;
use App\CandidateTraining;
use App\GroupTraining;
use App\RecruitmentStory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupTrainingController extends Controller
{
    public function add_group_training()
    {
        $cadre = User::whereIN('user_type_id',[4,5,12])
            ->where('department_info_id',Auth::user()->department_info_id)
            ->where('status_work','=',1)
            ->get();

        return view('recruitment.addGroupTraining')
            ->with('cadre',$cadre);
    }
    public function add_group_training_2()
    {
        $cadre = User::whereIN('user_type_id',[4,5,12])
            ->where('department_info_id',Auth::user()->department_info_id)
            ->where('status_work','=',1)
            ->get();


        return view('recruitment.addGroupTraining')
            ->with('cadre',$cadre);
    }
    public  function  datatableTrainingGroupList(Request $request)
    {
        $list_type = $request->list_type;
        $group_training = GroupTraining::select('group_training.*','users.first_name','users.last_name');
        if($list_type == 1 || $list_type == 2)
            $group_training = $group_training->join('users','users.id','group_training.leader_id');
        else
            $group_training = $group_training->join('users','users.id','group_training.edit_cadre_id');

        $group_training = $group_training->where('group_training.status','=',$list_type)
            ->where('group_training.department_info_id','=',Auth::user()->department_info_id)
            ->where('training_stage','=',$request->training_stage);
        return datatables($group_training)->make(true);
    }

    public function EndGroupTrainingForCandidate(Request $request)
    {
        if($request->ajax())
        {
            $candidate_id = $request->candidate_id_end;
            $training_group = $request->training_group_id;
            $status = $request->status;
            $candidate = Candidate::find($candidate_id);
            $comment = $request->comment;
            if($comment == null)
            {
                $comment = "Brak komentarza";
            }
            if($status == 1){
                if($request->training_stage == 1){
                    $candidate->attempt_status_id = 12;
                }else{
                    $candidate->attempt_status_id = 15;
                }
            }
            else{
                if($request->training_stage == 1) {
                    $candidate->attempt_status_id = 9;
                }else{
                    $candidate->attempt_status_id = 16;
                }
            }
            $candidate->save();

            $candidate_story_old = RecruitmentStory::where('candidate_id','=',$candidate_id)
                ->orderBy('id', 'desc')->first();
            $candidate_story_new = new RecruitmentStory();
            $candidate_story_new->cadre_id = Auth::user()->id;
            $candidate_story_new->cadre_edit_id = Auth::user()->id;
            $candidate_story_new->candidate_id = $candidate_id;
            $candidate_story_new->recruitment_attempt_id = $candidate_story_old->recruitment_attempt_id;
            if($status == 1){
                if($request->training_stage == 1) {
                    $candidate_story_new->attempt_status_id = 8;
                }else{
                    $candidate_story_new->attempt_status_id = 15;
                }

            }
            else{
                if($request->training_stage == 1) {
                    $candidate_story_new->attempt_status_id = 9;
                }else{
                    $candidate_story_new->attempt_status_id = 16;
                }
            }
            $candidate_story_new->comment = $comment;
            $candidate_story_new->save();



            $CandidateTraining = CandidateTraining::where('training_id','=',$training_group)
                ->where('candidate_id','=',$candidate_id)
                ->update(['completed_training' => $candidate_story_new->id]);

            // zapisanie na szkolenie etap 2
            if($candidate_story_new->attempt_status_id == 8){
                $candidate_story_new = new RecruitmentStory();
                $candidate_story_new->cadre_id = Auth::user()->id;
                $candidate_story_new->cadre_edit_id = Auth::user()->id;
                $candidate_story_new->candidate_id = $candidate_id;
                $candidate_story_new->recruitment_attempt_id = $candidate_story_old->recruitment_attempt_id;
                $candidate_story_new->attempt_status_id = 12;
                $candidate_story_new->comment = $comment;
                $candidate_story_new->save();
            }

            return 1;

        }
    }

    public function EndGroupTraining(Request $request)
    {
        if($request->ajax())
        {
            $training_group_id = $request->training_group_to_end;
            $training_group = GroupTraining::find($training_group_id);
            $training_group->status = 2;

            if($training_group->save()){

                $all_candidate = CandidateTraining::where('training_id','=',$training_group_id)->get();
                foreach ($all_candidate as $item)
                {
                    $candidate = Candidate::find($item->candidate_id);
                    if($request->training_stage == 1)
                        $candidate->attempt_status_id = 7;
                    else
                        $candidate->attempt_status_id = 14;


                    $candidate->save();

                    $candidate_story_old = RecruitmentStory::where('candidate_id','=',$item->candidate_id)
                        ->orderBy('id', 'desc')->first();

                    $candidate_story_new = new RecruitmentStory();
                    $candidate_story_new->cadre_id = Auth::user()->id;
                    $candidate_story_new->cadre_edit_id = Auth::user()->id;
                    $candidate_story_new->candidate_id = $item->candidate_id;
                    $candidate_story_new->recruitment_attempt_id = $candidate_story_old->recruitment_attempt_id;
                    if($request->training_stage == 1)
                        $candidate_story_new->attempt_status_id = 7;
                    else
                        $candidate_story_new->attempt_status_id = 14;
                    $candidate_story_new->comment = "Szkolenie zakończone";
                    $candidate_story_new->save();
                }
                return 1;
            }
        }
    }
    public function getCandidateForGroupTrainingInfo(Request $request)
    {
        if($request->ajax())
        {
            if($request->training_stage == 1)
            {
                $candidate = Candidate::where('attempt_status_id','=',5)
                    ->where('department_info_id','=',Auth::user()->department_info_id)->get();

            }else{
                $candidate = Candidate::where('attempt_status_id','=',12)
                    ->where('department_info_id','=',Auth::user()->department_info_id)->get();
            }
            return $candidate;
        }
    }

    public function deleteGroupTraining(Request $request)
    {
        if($request->ajax())
        {
            // zmiana statusu szkolenia na usuniete
            $training_id = $request->id_training_group_to_delete;
            $training_grou = GroupTraining::find($training_id);
            $training_grou->status = 0;
            $training_grou->edit_cadre_id = Auth::user()->id;
            if($training_grou->save()){

                 $all_candidate = CandidateTraining::where('training_id','=',$training_id)->get();
                 foreach ($all_candidate as $item)
                 {
                     $candidate = Candidate::find($item->candidate_id);
                     if($request->training_stage == 1)
                        $candidate->attempt_status_id = 5;
                     else
                         $candidate->attempt_status_id = 12;
                     $candidate->save();
                     $candidate_story = RecruitmentStory::where('candidate_id','=',$item->candidate_id)
                         ->orderBy('id', 'desc')->first();
                     if($request->training_stage == 1)
                        $candidate_story->attempt_status_id = 5;
                     else
                         $candidate_story->attempt_status_id = 12;
                     $candidate_story->save();
                 }
                 return 1;
            }
        }
    }
    public function getGroupTrainingInfo(Request $request)
    {
        if($request->ajax())
        {

            $group_training = GroupTraining::
            where('id','=',$request->id_training_group)->get();

            if($request->training_stage == 1)
                $candidate_avaible = Candidate::whereIn('attempt_status_id',[5])
                    ->where('department_info_id','=',Auth::user()->department_info_id)->get()
                    ->toArray();
            else
                $candidate_avaible = Candidate::whereIn('attempt_status_id',[12])
                    ->where('department_info_id','=',Auth::user()->department_info_id)->get()
                    ->toArray();

            $candidate_choice = DB::table('candidate')
                ->select(DB::raw('
                candidate.*,
                candidate_training.completed_training,
                recruitment_story.attempt_status_id as recruitment_story_id,    
                recruitment_story.comment  as recruitment_story_comment
            '))
                ->join('candidate_training', 'candidate_training.candidate_id', 'candidate.id')
                ->leftjoin('recruitment_story', 'recruitment_story.id', 'candidate_training.completed_training')
                ->join('group_training', 'group_training.id', 'candidate_training.training_id')
                ->where('group_training.id','=',$request->id_training_group)
                ->get()->toArray();
            if($request->cancel_candidate == 1 || $request->cancel_candidate == 2 )
            {
                $merge_array = $candidate_choice;
            }else
                $merge_array = array_merge($candidate_choice,$candidate_avaible);

            $object_array['group_training'] = $group_training ;
            $object_array['candidate'] = $merge_array ;
            return $object_array;
        }
    }
    public function saveGroupTraining (Request $request)
    {
        if($request->ajax()){
            $start_date_training = $request->start_date_training;
            $start_hour_training = $request->start_hour_training;
            $cadre_id = $request->cadre_id;
            $comment_about_training = $request->comment_about_training;
            $avaible_candidate = $request->avaible_candidate;
            $choice_candidate = $request->choice_candidate;
            $saving_type = $request->saving_type;
            $flag = true;

            // nowe szkolenie lub instniejące
            if($saving_type == 1 && $request->id_training_group == 0) // 1 - nowy wpisz, 0 - edycja
            {
                $training = new GroupTraining();

            }else if($request->id_training_group != 0){
                $training = GroupTraining::find($request->id_training_group);
            }
            // wypełnienie danych odnośnie szkolenia
            $training->cadre_id = Auth::user()->id;
            $training->leader_id = $cadre_id;
            $training->department_info_id = Auth::user()->department_info_id;
            $training->comment = $comment_about_training;
            $training->candidate_count = count($choice_candidate);
            $training->training_date = $start_date_training;
            $training->training_hour = $start_hour_training;
            $training->status = 1; // dotępne szkolenie 2 - zakończone 0 - anulowane

            if($request->actual_stage == '1'){
                $training->training_stage = 1;
            }else if($request->actual_stage == '2'){
                $training->training_stage = 2;
            }

            // Próba zapisu
            if($training->save())
            {
                $flag = true;
            }else{
                return 0;
            }
            // Gdy szkolenie się zapisało
            if($flag)
            {   // Pobernie id szkolenia
                $id = $training->id;
                // usunięcie kandydatów zapisanych na szkolenie, jeśli tacy istnieją
                CandidateTraining::where('training_id','=',$id)->delete();
                // dodanie nowych kandydatów do szkolenia
                for($i = 0 ;$i < count($choice_candidate) ; $i++){

                    $candidate = Candidate::find($choice_candidate[$i]);
                    if($request->actual_stage == '1')
                        $candidate->attempt_status_id = 6;
                    else if($request->actual_stage == '2')
                        $candidate->attempt_status_id = 13;
                    $candidate->save();
                    $candidate_story = RecruitmentStory::where('candidate_id','=',$choice_candidate[$i])
                        ->orderBy('id', 'desc')->first();
                    if($request->actual_stage == '1')
                        $candidate_story->attempt_status_id = 6;
                    else   if($request->actual_stage == '1')
                        $candidate_story->attempt_status_id = 13;
                    $candidate_story->save();
                    $new_relation = new CandidateTraining();
                    $new_relation->training_id = $id;
                    $new_relation->candidate_id = $choice_candidate[$i];
                    $new_relation->save();
                }
                for($i =  0 ;$i < count($avaible_candidate) ; $i++){// osoby które zostły zdjęce ze szkolenia( znowu dostepne
                    $candidate = Candidate::find($avaible_candidate[$i]);
                    if($request->actual_stage == 1)
                     $candidate->attempt_status_id = 5;
                    else  if($request->actual_stage == 2)
                        $candidate->attempt_status_id = 12;
                    $candidate->save();
                        $candidate_story = RecruitmentStory::where('candidate_id','=',$avaible_candidate[$i])
                            ->orderBy('id', 'desc')->first();

                    if($request->actual_stage == '1')
                        $candidate_story->attempt_status_id = 5;
                    else  if($request->actual_stage == '2')
                        $candidate_story->attempt_status_id = 12;
                    $candidate_story->save();
                }
                return 1;
            }else
                return 0;
        }
    }
}
