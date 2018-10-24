<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{


    public function rating() {
        return $this->hasMany('App\NotificationRating', 'notification_rating_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function notification_about() {
        return $this->belongsTo('App\NotificationAbout', 'notification_about_id');
    }

    public function department_info() {
        return $this->belongsTo('App\Department_info', 'department_info_id');
    }

    public function displayed_by() {
        return $this->belongsTo('App\User', 'displayed_by');
    }

    public function notification_type() {
        return $this->belongsTo('App\NotificationTypes', 'notification_type_id');
    }

    public function comments() {
        return $this->hasMany('App\CommentsNotifications', 'notification_id');
    }

    public function notifications_changes_displayed_flags() {
        return $this->hasOne('App\NotificationChangesDisplayedFlags', 'notification_id');
    }
}
