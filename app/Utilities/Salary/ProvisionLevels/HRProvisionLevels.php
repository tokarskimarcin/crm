<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 09:45
 */

namespace App\Utilities\Salary\ProvisionLevels;


class HRProvisionLevels
{

    public static function get($level, $subtype, $subsubtype, $subsubsubtype){
        $provision = 0;
        switch ($subsubtype) {
            case 1: { //confirmation
                if($level >= 15) { // ammout of new peoples
                    $provision = 450;
                }
                else if($level >= 10){
                    $provision = 300;
                }
                else if($level >= 5){
                    $provision = 150;
                }
                else {
                    $provision = 0;
                }
                return $provision;
                break;
            }
            case 2: { //telemarketing
                if($level < 5) { //janki
                    switch($subsubsubtype) {
                        case 'rbh': {
                            if($subtype >= 100) { //target rbh
                                $provision = 150;
                            }
                            else {
                                $provision = 0;
                            }
                            break;
                        }
                        case 'ammount': {
                            if($subtype >= 100) { //target ammount
                                $provision = 150;
                            }
                            else {
                                $provision = 0;
                            }
                            break;
                        }
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