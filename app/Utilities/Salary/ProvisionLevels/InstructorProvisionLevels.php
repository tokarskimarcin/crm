<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 09:48
 */

namespace App\Utilities\Salary\ProvisionLevels;


class InstructorProvisionLevels
{
    public static function get($level, $subtype, $subsubtype, $subsubsubtype){
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
                if($level < 5) { //janki less than 5%
                    if($subsubsubtype == 'avg') {
                        if($subsubtype > 2.25) {
                            $provision = 150;
                        }
                        else {
                            $provision = 0;
                        }
                    }
                    else if($subsubsubtype == 'employment') {
                        if($subsubtype > 80) {
                            $provision = 150;
                        }
                        else {
                            $provision = 0;
                        }
                    }
                }
                return $provision;
                break;
            }
        }
    }
}