<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecruitmentAttempt extends Model
{
    protected $table = 'recruitment_attempt';

    public function cadre() {
        return $this->belongsTo('App\User', 'cadre_id');
    }

    public function edit_cadre() {
        return $this->belongsTo('App\User', 'cadre_edit_id');
    }
    public function candidate() {
        return $this->belongsTo('App\Candidate', 'candidate_id');
    }
}
