<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 09:51
 */

namespace App\Utilities\Salary\ProvisionLevels;


class CoordinatorProvisionLevels
{
    public static function get($level, $subtype, $subsubtype){
        $provision = 0;
        if($level < 10) { //database use
            if($subtype >= 100) { //target > 100%
                if($subsubtype >= 90) { //working more than 90 days as coordinator
                    $provision = 500;
                }
                else { //working less or equal than 90 days as coordinator
                    $provision = 250;
                }
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