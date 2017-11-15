<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Links extends Model
{
    use Notifiable;
    protected $table = 'links';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'link','group_link_id','name'
    ];

    public function link_groups() {
        return $this->belongsTo('App\LinkGroups', 'group_link_id');
    }

    public function users() {
        return $this->belongsToMany('App\User', 'privilage_relation', 'link_id', 'user_type_id');
    }
}
