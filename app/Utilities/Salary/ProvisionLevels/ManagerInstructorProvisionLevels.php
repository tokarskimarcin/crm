<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 09:43
 */

namespace App\Utilities\Salary\ProvisionLevels;


class ManagerInstructorProvisionLevels
{
    public static function get($level, $subsubtype, $subsubsubtype){
        $provision = 0;
        if($level < 5) {
            switch($subsubsubtype) {
                case 'avg': {
                    if($subsubtype >= 100) { //target avg in %
                        $provision = 300;
                    }
                    else {
                        $provision = 0;
                    }
                    break;
                }
                case 'ammount': {
                    if($subsubtype >= 100) { //target ammount in %
                        $provision = 300;
                    }
                    else {
                        $provision = 0;
                    }
                    break;
                }
            }
        }else {
            $provision = 0;
        }
        return $provision;
    }
}