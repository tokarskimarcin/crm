<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttemptResult extends Model
{
    protected $table = 'attempt_result';
    public function attemptStatus()
    {
        return $this->belongsToMany('App\AttemptStatus');
    }
}
