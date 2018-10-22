<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 27.09.18
 * Time: 15:29
 */

namespace App\Utilities\Dates;


class TimeFromSeconds
{

    public static function get($seconds){
        $hours = floor($seconds/3600);
        $mins = floor($seconds/60 % 60);
        $secs = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    }
}