<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JudgeResult extends Model
{
  public function user_it() {
      return $this->belongsTo('App\User', 'it_id');
  }
}
