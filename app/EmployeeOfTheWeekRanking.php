<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeOfTheWeekRanking extends Model
{
    //
    protected $table = 'employee_of_the_week_ranking';

    public function employee_of_the_week(){
        return $this->belongsTo('App\EmployeeOfTheWeek','employee_of_the_week_id');
    }

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
