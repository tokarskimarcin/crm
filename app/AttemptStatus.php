<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttemptStatus extends Model
{
    protected $table = 'attempt_status';

    public function attemptResult()
    {
        return $this->belongsToMany('App\AttemptResult');
    }

    public function defaultAttemptResult(){
        return $this->belongsTo('App\AttemptResult','default_attempt_result_id');
    }
}
