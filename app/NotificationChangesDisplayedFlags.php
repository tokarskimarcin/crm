<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationChangesDisplayedFlags extends Model
{

    protected $table = 'notifications_changes_displayed_flags';
    //

    public function notification(){
        return $this->belongsTo('App\Notifications','notification_id');
    }
}
