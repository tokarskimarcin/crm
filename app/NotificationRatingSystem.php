<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationRatingSystem extends Model
{
    //
    protected $table = 'notification_rating_system';
    public $timestamps = false;


    public function rating_criterion() {
        return $this->hasMany('App\NotificationRatingCriterion', 'notification_rating_criterion_id');
    }

}
