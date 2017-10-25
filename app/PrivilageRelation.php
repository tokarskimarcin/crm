<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivilageRelation extends Model
{
    protected $table = 'privilage_relation';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_type_id','link_id',
    ];
}
