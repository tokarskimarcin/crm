<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserQuestion extends Model
{
    public function test() {
        return $this->belongsTo('App\UserTest', 'test_id');
    }

    public function testQuestion() {
        return $this->hasOne('App\TestQuestion', 'id');
    }
}
