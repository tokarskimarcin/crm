<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentAgencyStory extends Model
{
    protected $table = 'payment_agency_story';

    public function cadre() {
        return $this->belongsTo('App\User', 'cadre_id');
    }
    public function consultant() {
        return $this->belongsTo('App\User', 'consultant_id');
    }
    public function agency() {
        return $this->belongsTo('App\Agencies', 'agency_id');
    }
}
