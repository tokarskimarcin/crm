<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 09:42
 */

namespace App\Utilities\Salary\ProvisionLevels;


class ManagerProvisionLevels
{

    public static function get($level, $subsubtype, $subsubsubtype, $subsubsubsubtype){
        $provision = 0;
        if($level < 5) {
            switch($subsubsubtype) {
                case 'avg': {
                    if($subsubtype >= 100) { //target avg in %
                        if($subsubsubsubtype == 1)
                            $provision = 225;
                        else if($subsubsubsubtype == 2)
                            $provision = 300;
                        else if($subsubsubsubtype >= 3)
                            $provision = 450;
                    }
                    else {
                        $provision = 0;
                    }
                    break;
                }
                case 'ammount': {
                    if($subsubtype >= 100) { //target ammount in %
                        if($subsubsubsubtype == 1)
                            $provision = 225;
                        else if($subsubsubsubtype == 2)
                            $provision = 300;
                        else if($subsubsubsubtype >= 3)
                            $provision = 450;
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