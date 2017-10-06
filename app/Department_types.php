<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department_types extends Model
{
    protected $table = 'department_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name',
    ];
}
