<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipmentTypes extends Model
{
    protected $table = 'equipment_types';

    protected $fillable = [
        'id', 'name'
    ];

    public function equipment() {
        return $this->hasMany('App\Equpments', 'equipment_type_id');
    }

}
