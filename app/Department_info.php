<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department_info extends Model
{
    protected $table = 'department_info';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_dep','id_dep_type','size','commission_avg','commission_hour','commission_start_money','commission_step',
        'dep_aim','dep_aim_week','commission_janky','type','janky_system_id','pbx_id'
    ];

    public function users() {
       return $this->hasMany('App\User');
    }

    public function departments() {
        return $this->belongsTo('App\Departments', 'id_dep');
    }

    public function department_type() {
        return $this->belongsTo('App\Department_types', 'id_dep_type');
    }

    public function summaryPayment() {
        return $this->hasMany('App\SummaryPayment', 'department_info_id');
    }

    public function equipments() {
        return $this->hasMany('App\Equipments', 'department_info_id');
    }

    public function notifications() {
        return $this->hasMany('App\Notifications', 'department_info_id');
    }

    public function multiple_departments(){
        return $this->hasMany('App\MultipleDepartments', 'department_info_id');
    }

}
