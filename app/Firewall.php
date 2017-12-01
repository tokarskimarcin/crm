<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Firewall extends Model
{
    protected $table = 'firewall';

    protected $fillable = [
        'id', 'ip_address','whitelisted','created_at','updated_at'
    ];
}
