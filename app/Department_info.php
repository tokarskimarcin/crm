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
        'dep_aim','dep_aim_week','commission_janky','type',
    ];
}
