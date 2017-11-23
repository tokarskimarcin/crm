<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentsNotifications extends Model
{
    public function notification() {
        return $this->belongsTo('App\Notifications', 'notification_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
