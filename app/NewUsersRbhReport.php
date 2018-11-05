<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewUsersRbhReport extends Model
{
    protected $table = 'rbh_30_report';
    public $timestamps = false;

    /**
     * @param $query
     * @param @array $dates
     * @return mixed
     */
    public function scopeRBHUsersBetweenDates($query, $dates){
        return $query->select('user_id')->whereBetween('created_at', $dates)
            ->groupBy('user_id');
    }
}
