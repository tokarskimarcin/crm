<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenaltyBonus extends Model
{
    protected $table = 'penalty_bonus';
    protected $fillable = [
        'id', 'type', 'id_user', 'amount', 'id_manager', 'event_date', 'updated_at', 'created_at', 'id_manager_edit'
    ];
}
