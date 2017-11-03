<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedule';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_user', 'id_manager', 'year', 'week_num', 'monday_comment',
        'monday_hour', 'tuesday_comment', 'tuesday_hour', 'wednesday_comment',
        'wednesday_hour', 'thursday_comment', 'thursday_hour', 'friday_comment',
        'friday_hour', 'saturday_comment', 'saturday_hour', 'sunday_comment',
        'sunday_hour','updated_at', 'created_at', 'id_manager_edit'
    ];
}
