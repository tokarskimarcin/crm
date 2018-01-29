<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateSource extends Model
{
    protected $table = 'candidate_source';

    public function cadre() {
        return $this->belongsTo('App\User', 'cadre_id');
    }
}
