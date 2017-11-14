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
}
