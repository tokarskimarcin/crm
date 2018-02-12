<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PBXDKJTeam extends Model
{
    protected $table = 'pbx_dkj_team';
    public $timestamps = false;

    public function department_info() {
        return $this->belongsTo('App\Department_info','department_info_id');
    }
}
