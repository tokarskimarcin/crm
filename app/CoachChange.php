<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoachChange extends Model
{
    protected $table='coach_change';

    protected $fillable=['coach_id','prev_coach_id','editor_id','status'];

    public function coach_id(){
        return $this->hasMany('App/User','coach_id');
    }
    public function prev_coach_id(){
        return $this->hasMany('App/User','prev_coach_id');
    }
    public function editor_id(){
        return $this->hasMany('App/User','editor_id');
    }
}
