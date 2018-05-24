<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'city';
    public $timestamps = false;

    public function voivodes_info() {
        return $this->belongsTo('App\Voivodes','voivodeship_id');
    }
}
