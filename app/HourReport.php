<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HourReport extends Model
{
    protected $table = 'hour_report';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'department_info_id', 'user_id', 'hour',
        'report_date', 'is_send', 'average', 'success', 'employee_count',
        'janky_count', 'wear_base', 'call_time', 'created_at', 'updated_at'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function department_info() {
        return $this->belongsTo('App\Department_info','department_info_id');
    }
}
