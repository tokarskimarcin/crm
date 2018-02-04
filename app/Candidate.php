<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\AttemptStatus;

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
        // $data = DB::select('
        //     SELECT 
        //     attempt_status.*
        //     FROM `recruitment_story`
        //     inner join recruitment_attempt on recruitment_attempt.id = recruitment_story.recruitment_attempt_id
        //     inner join attempt_status on attempt_status.id = recruitment_story.attempt_status_id
        //     where recruitment_attempt.status = 0
        //     and recruitment_story.id in (
        //         SELECT MAX(id) from recruitment_story where candidate_id = ' . $this->id . '
        //     )
        // ');

        // $status = (isset($data[0])) ? AttemptStatus::find($data[0]->id) : null;

        // return $status;
    }

    public function attempt_level_data() {
        return $this->belongsTo('App\AttemptStatus', 'attempt_status_id');
    }
}
