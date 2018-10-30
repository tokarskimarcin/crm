<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 09:52
 */

namespace App\Utilities\Salary\ProvisionLevels;


class CoordinatorLeaderProvisionLevels
{
    public static function get($level, $subtype){
        $provision = 0;
        if($level < 10) { //database use
            if($subtype >= 100) { //target > 100%
                $provision = 1000;
            }
            else {
                $provision = 0;
            }
        }
        else {
            $provision = 0;
        }
        return $provision;
    }
}