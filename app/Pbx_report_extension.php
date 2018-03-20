<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pbx_report_extension extends Model
{
    protected $table = 'pbx_report_extension';
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User','pbx_id', 'login_phone');
    }
}


