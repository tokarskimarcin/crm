<?php

namespace App\Http\Controllers;


use App\Candidate;
use App\CandidateTraining;
use App\GroupTraining;
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
    public  function  datatableTrainingGroupList(Request $request)
    {
        $list_type = $request->list_type;
        $group_training = GroupTraining::where('status','=',$list_type)
        ->where('department_info_id','=',Auth::user()->department_info_id);
        return datatables($group_training)->make(true);
    }
    public function getCandidateForGroupTrainingInfo(Request $request)
    {

        if($request->ajax())
        {
            $candidate = Candidate::where('attempt_status_id','=',5)
                ->where('department_info_id','=',Auth::user()->department_info_id)->get();
            return $candidate;
        }
    }
    public function getGroupTrainingInfo(Request $request)
    {
        if($request->ajax())
        {

            $group_training = GroupTraining::where('id','=',$request->id_training_group)->get();
            $candidate = Candidate::whereIn('attempt_status_id',[5,6])
                ->where('department_info_id','=',Auth::user()->department_info_id)->get();
            $object_array['group_training'] = $group_training ;
            $object_array['candidate'] = $candidate ;
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
                    $new_relation = new CandidateTraining();
                    $candidate = Candidate::find($choice_candidate[$i]);
                    $candidate->attempt_status_id = 6;
                    $new_relation->training_id = $id;
                    $new_relation->candidate_id = $choice_candidate[$i];
                    $candidate->save();
                    $new_relation->save();
                }
            }
        }
    }
}



