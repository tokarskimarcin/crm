<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestCategory extends Model
{
    public function questions() {
        return $this->hasMany('App\TestQuestion', 'category_id');
    }
}
