<?php
/**
 * Created by PhpStorm.
 * User: Verona
 * Date: 12.09.2018
 * Time: 09:38
 */

namespace App\Utilities\Dates;


use DateTime;

class WeekNumber
{
    public static function get($date){
        $actualWeek = new DateTime($date);
        $actualWeek = $actualWeek->format("W");
        return $actualWeek;
    }
}