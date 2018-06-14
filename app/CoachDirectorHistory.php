<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoachDirectorHistory extends Model
{
    protected $table='coach_director_history';

    protected $fillable=['coaching_id','coach_director_change_id'];

    public function coaching_id(){
        $this->hasMany('/App/CoachingDirector','coaching_id');
    }

    public function coach_director_change_id(){
        $this->hasMany('/App/CoachDirectorChange', 'coach_director_change_id');
    }
}
