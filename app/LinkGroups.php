<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkGroups extends Model
{
    protected $table = 'link_groups';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name',
    ];
}
