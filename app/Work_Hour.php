<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Work_Hour extends Model
{
    use Notifiable;
    protected $table = 'work_hours';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'status', 'click_start','click_stop','register_start','register_stop','accept_start','accept_stop','accept_sec',
        'success','id_user','id_manager','date'
    ];
}
