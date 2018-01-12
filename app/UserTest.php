<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    public function questions() {
        return $this->hasMany('App\UserQuestion', 'test_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function cadre() {
        return $this->belongsTo('App\User', 'cadre_id');
    }
    
}
