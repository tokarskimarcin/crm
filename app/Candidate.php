<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
