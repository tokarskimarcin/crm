<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirewallPrivileges extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }
}
