<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditStatus extends Model
{
    protected $table = 'audit_status';
    public $timestamps = false;
}
