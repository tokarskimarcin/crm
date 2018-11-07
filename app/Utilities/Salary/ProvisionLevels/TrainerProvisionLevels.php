<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 09:39
 */

namespace App\Utilities\Salary\ProvisionLevels;


class TrainerProvisionLevels
{
    public static function get($level, $subtype, $subsubtype = null, $subsubsubtype = null){
        $provision = 0;
        switch ($subtype) {
            case '1': { //case when red shows
                if($subsubtype < 5) { // janky < 5
                    if ($level < 6) {
                        $provision = 200;
                    } else {
                        $provision = 0;
                    }
                }
                return $provision;
                break;
            }
            case '2': { //case when green shows
                if($subsubtype < 5) { // janky < 5
                    if ($level >= 95) {
                        $provision = 350;
                    } else if ($level >= 90) {
                        $provision = 300;
                    } else if ($level >= 85) {
                        $provision = 250;
                    } else if ($level >= 80) {
                        $provision = 200;
                    } else {
                        $provision = 0;
                    }
                }
                return $provision;
                break;
            }
            case '3':{ // Telemarketing
                if($level < 5) {
                    switch($subsubsubtype) {
                        case 'avg': {
                            if($subsubtype >= 100) { //target avg in %
                                $provision = 150;
                            }
                            else {
                                $provision = 0;
                            }
                            break;
                        }
                        case 'ammount': {
                            if($subsubtype >= 100) { //target ammount in %
                                $provision = 150;
                            }
                            else {
                                $provision = 0;
                            }
                            break;
                        }
                        default:{
                            throw new \Exception('No such case - $subsubsubtype');
                        }
                    }
                }else {
                    $provision = 0;
                }
                return $provision;
                break;
            }
//                    case '4': {
//
//
//                        return $provision;
//                        break;
//                    }
        }
    }

}