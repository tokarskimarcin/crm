<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditInfo extends Model
{
    protected $table = 'audit_info';
    public $timestamps = false;
}
