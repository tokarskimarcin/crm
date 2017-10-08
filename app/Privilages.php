<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Privilages extends Model
{
    use Notifiable;
    protected $table = 'privilages';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'link', 'priv','group_link_id','name'
    ];
}
