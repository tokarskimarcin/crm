<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationRatingComponents extends Model
{
    //
    protected $table = 'notification_rating_component';
    public $timestamps = false;


    public function rating_criterion() {
        return $this->belongsTo('App\NotificationRatingCriterion', 'notification_rating_criterion_id');
    }

    public function rating() {
        return $this->belongsTo('App\NotificationRating', 'notification_rating_id');
    }
}
