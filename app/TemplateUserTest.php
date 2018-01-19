<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
