<?php
/**
 * Created by PhpStorm.
 * User: Verona
 * Date: 13.09.2018
 * Time: 11:53
 */

namespace App\Utilities\Salary;


class ProvisionLevels
{

    /**
     * //This method return premium money amount in case of frequency amount
     * @param $level - float number sometimes it is percent sometimes it is value, depends of type or subtype
     * @param $type - name of role
     * @param null $subtype - different variations of provisions for some type, defined as integer.
     * @return int - value of provision
     */
    public static function get($level, $type, $subtype = 1, $subsubtype = null) {
        $provision = 0;
        switch($type) {
            case 'consultant': {
                switch($subtype) {
                    case '1': { //case of number of people who show up after invitation.
                        if($level < 12) {
                            $provision = -180;
                        }
                        else if($level >= 12 && $level < 16) {
                            $provision = -60;
                        }
                        else if($level >= 16 && $level < 20) {
                            $provision = 0;
                        }
                        else if($level >= 20 && $level < 25) {
                            $provision = 20;
                        }
                        else if($level >= 25 && $level < 30) {
                            $provision = 25;
                        }
                        else if($level >= 30 && $level < 35) {
                            $provision = 30;
                        }
                        else if($level >= 35 && $level < 40) {
                            $provision = 35;
                        }
                        else if($level >= 40) {
                            $provision = 40;
                        }
                        return $provision;
                        break;
                    }
                    case '2': { //case when at least one of shows is bad.
                        if($level == 0) {
                            $provision = 50;
                        }
                        else {
                            $provision = 0;
                        }
                        return $provision;
                        break;
                    }
                }


                break;
            }
            case 'trainer': {
                switch ($subtype) {
                    case '1': { //case when red shows
                        if($level < 6) {
                            $provision = 200;
                        }
                        else {
                            $provision = 0;
                        }
                        return $provision;
                        break;
                    }
                    case '2': { //case when green shows
                        if($level < 80) {
                            $provision = 0;
                        }
                        else if($level >= 80 && $level < 85) {
                            $provision = 200;
                        }
                        else if($level >= 85 && $level < 90) {
                            $provision = 250;
                        }
                        else if($level >= 90 && $level < 95) {
                            $provision = 300;
                        }
                        else if($level >= 95) {
                            $provision = 350;
                        }
                        return $provision;
                        break;
                    }
                }
                break;
            }
            case 'HR': {
                if($level < 6) {
                    $provision = 0;
                }
                else if($level >= 6 && $level < 10) {
                    $provision = 150;
                }
                else if($level >= 10 && $level < 16) {
                    $provision = 300;
                }
                else if($level >= 16) {
                    $provision = 450;
                }
                return $provision;
                break;
            }
            case 'instructor': {
                switch ($subtype) {
                    case '1': { //case when red shows
                        if($level < 6) {
                            $provision = 200;
                        }
                        else {
                            $provision = 0;
                        }
                        return $provision;
                        break;
                    }
                    case '2': { //case when green shows
                        if($level < 80) {
                            $provision = 0;
                        }
                        else if($level >= 80 && $level < 85) {
                            $provision = 200;
                        }
                        else if($level >= 85 && $level < 90) {
                            $provision = 250;
                        }
                        else if($level >= 90 && $level < 95) {
                            $provision = 300;
                        }
                        else if($level >= 95) {
                            $provision = 350;
                        }
                        return $provision;
                        break;
                    }
                }
                break;
            }
            case 'koordynator': {
                if($subtype > 100) { //cel > 100%
                    if($subsubtype > 90) { //number of days in work
                        if($level > 10) { //database use
                            $provision = 500;
                        }
                        else {
                            $provision = 0;
                        }
                    }
                    else {
                        if($level > 10) {
                            $provision = 250;
                        }
                        else {
                            $provision = 0;
                        }
                    }
                }
                else {
                    $provision = 0;
                }

                return $provision;
                break;
            }
            case 'Lider koordynatorow': {
                if($subtype > 100) { // cel > 100%
                    if($level > 10) { //database use
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
                break;
            }
        }
    }
}