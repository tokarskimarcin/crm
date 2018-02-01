<?php

namespace App\Http\Controllers;


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
        $group_training = GroupTraining::where('status','=',$list_type);
        return datatables($group_training)->make(true);
    }
    public function getGrpupTrainingInfo(Request $request)
    {
        if($request->ajax())
        {
            $group_training = GroupTraining::where('id','=',$request->id_training_group)->get();
            $candidate = DB::table('candidate')
                ->select(DB::raw('
                recruitment_story.attempt_status_id,
                recruitment_story.id as max_id,
                candidate.*         
            '))
                ->join('candidate_training', 'candidate_training.candidate_id', 'candidate.id')
                ->join('group_training', 'group_training.id', 'candidate_training.training_id')
                ->join('recruitment_story','recruitment_story.candidate_id','candidate.id')
                ->where('group_training.id','=',2)
                ->whereIn('recruitment_story.id', function($query){
                    $query->select(DB::raw(
                        'MAX(recruitment_story.id)'
                    ))
                        ->from('recruitment_story')
                        ->groupby('recruitment_story.candidate_id');
                })
                ->get();
            $object_array['group_training'] = $group_training ;
            $object_array['candidate'] = $candidate ;
            return $object_array;
        }
    }
}



