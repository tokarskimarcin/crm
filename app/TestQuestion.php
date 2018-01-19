<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    public function category() {
        return $this->belongsTo('App\TestCategory', 'category_id');
    }

    public function userQuestion() {
        return $this->belongsToMany('App\UserQuestion', 'test_users_questions', 'test_question_id', 'user_question_id');
    }
}
