<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TemplateUserTest extends Model
{
    public function cadre() {
        return $this->belongsTo('App\User', 'cadre_id');
    }

    public function tests() {
        return $this->hasMany('App\UserTest', 'template_id');
    }

    public function templateQuestions() {
        return $this->belongsToMany('App\TestQuestion', 'template_questions', 'template_id', 'question_id');
    }

    public function questionsData() {
        $query = DB::table('template_user_tests')
            ->select(DB::raw('
                template_user_tests.*,
                template_questions.*,
                test_questions.content as content,
                test_categories.name as category_name
            '))
            ->join('template_questions', 'template_questions.template_id', 'template_user_tests.id')
            ->join('test_questions', 'template_questions.question_id', 'test_questions.id')
            ->join('test_categories', 'test_categories.id', 'test_questions.category_id')
            ->where('template_user_tests.id', '=', $this->id)
            ->get();

        return $query;
    }
}
