<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisableAccountInfo extends Model
{
    protected $table = 'disable_account_info';
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
