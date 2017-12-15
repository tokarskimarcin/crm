<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MultipleDepartments extends Model
{
    public function department_info() {
        return $this->belongsTo('App\Department_info', 'department_info_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'id_user');
    }
}
