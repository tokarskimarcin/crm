<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcceptedPayment extends Model
{
    protected $table = 'accepted_payments';

    public function cadre() {
        return $this->belongsTo('App\User', 'cadre_id');
    }
}
