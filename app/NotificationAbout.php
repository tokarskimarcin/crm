<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationAbout extends Model
{

    protected $table = 'notification_about';
    //
    public $timestamps = false;

    public function notification() {
        return $this->hasMany('App\Notifications', 'notification_about_id');
    }
}
