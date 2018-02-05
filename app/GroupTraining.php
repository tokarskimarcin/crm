<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupTraining extends Model
{
    protected $table = 'group_training';

    public function cadre() {
        return $this->belongsTo('App\User', 'cadre_id');
    }

    public function edit_cadre() {
        return $this->belongsTo('App\User', 'edit_cadre_id');
    }
    public function leader() {
        return $this->belongsTo('App\User', 'leader_id');
    }
    public function dapartment_info() {
        return $this->belongsTo('App\Department_info', 'department_info_id');
    }

    public function candidates() {
        return $this->belongsToMany('App\Candidate', 'candidate_training', 'training_id', 'candidate_id');
    }
}
