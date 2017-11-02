<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JankyPenatlyProc extends Model
{
    protected $table = 'janky_penatly_proc';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'system_id','min_proc','max_proc','cost',
    ];
}
