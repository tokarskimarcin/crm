<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationRating extends Model
{
    //
    protected $table = 'notification_rating';
    public $timestamps = false;

    public function rating_component() {
        return $this->hasMany('App\NotificationRatingComponents', 'notification_rating_id');
    }

    public function notification() {
        return $this->belongsTo('App\Notifications', 'notification_id');
    }
}
