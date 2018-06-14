<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoachHistory extends Model
{
    protected $table='coach_history';

    protected $fillable=['user_id','coach_change_id'];

    public function user_id(){
        $this->hasMany('/App/User','user_id');
    }

    public function coach_change_id(){
        $this->hasMany('/App/CoachChange', 'coach_change_id');
    }
}
