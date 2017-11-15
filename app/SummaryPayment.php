<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SummaryPayment extends Model
{
    protected $table = 'summary_payment';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'department_info_id', 'month', 'payment', 'hours', 'dosuments', 'students', 'employee_count', 'created_at', 'updated_at'
    ];

    public function department_info() {
        return $this->belongsTo('App\Department_info', 'department_info_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user');
    }
}
