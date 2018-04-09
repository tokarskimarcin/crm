<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditFiles extends Model
{
    protected $table = 'audit_files';
    public $timestamps = false;
}
