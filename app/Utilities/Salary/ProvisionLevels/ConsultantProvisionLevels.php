<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 09:37
 */

namespace App\Utilities\Salary\ProvisionLevels;


class ConsultantProvisionLevels
{
    public static function get($level, $subtype){
        $provision = 0;
        switch($subtype) {
            case '1': {
                if($level >= 40){ //case of number of people who show up after invitation.
                    $provision = 40;
                }else if($level >= 35){
                    $provision = 35;
                }else if($level >= 30 ){
                    $provision = 30;
                }else if($level >= 25){
                    $provision = 25;
                }else if($level >= 20){
                    $provision = 20;
                }else if($level >= 16){
                    $provision = 0;
                }else if($level >= 12){
                    $provision = -60;
                }else {
                    $provision = -180;
                }
                return $provision;
                break;
            }
            case '2': {
                if($level == 0) {
                    $provision = 50;
                }
                else { //case when at least one of shows is bad.
                    $provision = 0;
                }
                return $provision;
                break;
            }
        }
    }
}