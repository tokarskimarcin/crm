<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeOfTheWeek extends Model
{
    //
    protected $table = 'employee_of_the_week';

    public function user_type(){
        return $this->belongsTo('App\UserTypes','user_type_id');
    }
    public function department_info(){
        return $this->belongsTo('App\Department_info','department_info_id');
    }

    public function employee_of_the_week_ranking(){
        return $this->hasMany('App\EmployeeOfTheWeekRanking','employee_of_the_week_id');
    }
}
