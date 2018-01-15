<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserQuestion extends Model
{
    public function test() {
        return $this->belongsTo('App\UserTest', 'test_id');
    }

    public function testQuestion() {
        return $this->belongsToMany('App\TestQuestion', 'test_users_questions', 'user_question_id', 'test_question_id');
    }
}
