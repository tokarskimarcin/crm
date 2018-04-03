<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditHeaders extends Model
{
    protected $table = 'audit_header';
    public $timestamps = false;
}
