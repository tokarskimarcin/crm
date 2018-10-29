<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationRatingCriterion extends Model
{
    //
    protected $table = 'notification_rating_criterion';
    public $timestamps = false;

    public function rating_component() {
        return $this->hasMany('App\NotificationRatingComponents', 'notification_rating_criterion_id');
    }

    public function rating_system() {
        return $this->belongsTo('App\NotificationRatingSystem', 'notification_rating_system_id');
    }
}
