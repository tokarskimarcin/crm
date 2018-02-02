<?php

namespace App\Http\Controllers;


use App\Candidate;
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
        ->where('dapartment_info_id','=',Auth::user()->department_info_id);
        return datatables($group_training)->make(true);
    }
    public function getCandidateForGroupTrainingInfo(Request $request)
    {

        if($request->ajax())
        {
            $candidate = Candidate::where('attempt_status_id','=',5)->get();
            return $candidate;
        }
    }
    public function getGroupTrainingInfo(Request $request)
    {
        if($request->ajax())
        {
            $group_training = GroupTraining::where('id','=',$request->id_training_group)->get();
            $candidate =  $candidate = Candidate::whereIn('attempt_status_id',[5,6])->get();
            $object_array['group_training'] = $group_training ;
            $object_array['candidate'] = $candidate ;
            return $object_array;
        }
    }
    public function saveGroupTraining (Request $request)
    {
        if($request->ajax()){

        }
    }
}



