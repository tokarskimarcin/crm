<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function checkedBy() {
        return $this->belongsTo('App\User', 'checked_by');
    }
    
    public function questionsData() {
        $query = DB::table('user_tests')
            ->select(DB::raw('
                user_questions.*,
                test_questions.content as content,
                test_categories.name as category_name
            '))
            ->join('user_questions', 'user_questions.test_id', 'user_tests.id')
            ->join('test_questions', 'test_questions.id', 'user_questions.question_id')
            ->join('test_categories', 'test_categories.id', 'test_questions.category_id')
            ->where('user_tests.id', '=', $this->id)
            ->get();

        return $query;
    }
}
