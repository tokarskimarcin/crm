<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientRoute extends Model
{
    protected $table = 'client_route';
    protected $guarded = array();
    public $timestamps = false;
}
