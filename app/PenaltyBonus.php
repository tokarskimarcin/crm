<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenaltyBonus extends Model
{
    protected $table = 'penalty_bonus';
    protected $fillable = [
        'id', 'type', 'id_user', 'amount', 'id_manager', 'event_date', 'updated_at', 'created_at', 'id_manager_edit'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'id_user');
    }

    public function manager() {
        return $this->belongsTo('App\User', 'id_manager');
    }

    public function manager_edit() {
        return $this->belongsTo('App\User', 'id_manager_edit');
    }
}
