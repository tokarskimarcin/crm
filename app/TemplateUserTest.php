<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateUserTest extends Model
{
    public function cadre() {
        return $this->belongsTo('App\User', 'cadre_id');
    }
}
