<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivilageUserRelation extends Model
{
    protected $table = 'privilage_user_relation';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id','link_id',
    ];
}
