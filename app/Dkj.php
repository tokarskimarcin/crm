<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dkj extends Model
{
    protected $table = 'dkj';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'add_date', 'id_user', 'id_dkj', 'id_manager', 'date_manager', 'comment_manager',
        'phone', 'campaign', 'comment', 'dkj_status', 'manager_status', 'deleted', 'edit_dkj',
        'edit_date', 'department_info_id',
    ];

    public function user() {
        return $this->belongsTo('App\User', 'id_user');
    }
}
