<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Candidate extends Model
{
    protected $table = 'candidate';

    public function cadre() {
        return $this->belongsTo('App\User', 'cadre_id');
    }

    public function edit_cadre() {
        return $this->belongsTo('App\User', 'cadre_edit_id');
    }

    public function dapartment_info() {
        return $this->belongsTo('App\Department_info', 'department_info_id');
    }

    public function recruitment_story() {
        return $this->hasMany('App\RecruitmentStory', 'candidate_id');
    }

    public function recruitment_attempt() {
        return $this->hasMany('App\RecruitmentAttempt', 'candidate_id');
    }

    public function attempt_level() {
        // $data = DB::table('recruitment_story')
        //     ->select(DB::raw('

        //     '))
        //     ->join('attempt_status', 'attempt_status.id', 'recruitment_story.attempt_level_id')
        //     ->join('recruitment_attempt', 'recruitment_attempt.id', '')
        //     ->where('recruitment_attempt.status', '=', 0)
        //     ->get();
    }
}
