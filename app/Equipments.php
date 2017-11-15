<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipments extends Model
{
    protected $fillable = [
        'id', 'equipment_type_id', 'laptop_processor', 'laptop_ram', 'laptop_hard_drive',
        'screnn_signal_cable', 'phone_box', 'tablet_modem', 'sim_number_phone', 'sim_type',
        'sim_pin', 'sim_puk', 'sim_net', 'model', 'serial_code', 'description', 'power_cable',
        'signal_cable', 'status', 'id_user', 'id_manager', 'created_at', 'updated_at'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'id_user');
    }
}
