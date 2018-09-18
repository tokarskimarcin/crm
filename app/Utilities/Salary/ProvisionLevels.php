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
     * @param $occupation - name of role
     * @param null $subtype - different variations of provisions for some type, defined as integer.
     * @return int - value of provision
     */
    public static function get($occupation, $level, $subtype = 1, $subsubtype = null, $subsubsubtype = null) {
        $provision = 0;
        switch($occupation) {
            case 'consultant': {
                switch($subtype) {
                    case '1': { //case of number of people who show up after invitation.
                        if($level >= 40){
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
                        if($level >= 95){
                            $provision = 350;
                        }else if($level >= 90){
                            $provision = 300;
                        }else if($level >= 85 ){
                            $provision = 250;
                        }else if($level >= 80){
                            $provision = 200;
                        }else {
                            $provision = 0;
                        }
                        return $provision;
                        break;
                    }
                    case '3':{ // Telemarketing
                        if($level < 5) {
                            switch($subsubsubtype) {
                                case 'avg': {
                                    if($subsubtype > 100) { //target avg in %
                                        $provision = 150;
                                    }
                                    else {
                                        $provision = 0;
                                    }
                                    break;
                                }
                                case 'ammount': {
                                    if($subsubtype > 100) { //target ammount in %
                                        $provision = 150;
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
                        break;
                    }
                }
                break;
            }
            case 'HR': {
                switch ($subsubtype) {
                    case 1: { //confirmation
                        if($level >= 15) { // ammout of new peoples
                            $provision = 450;
                        } else if($level >= 10){
                            $provision = 300;
                        } else{
                            $provision = 150;
                        }
                        return $provision;
                        break;
                    }
                    case 2: { //telemarketing
                        if($level < 5) { //janki
                            switch($subsubsubtype) {
                                case 'rbh': {
                                    if($subtype > 100) { //target rbh
                                        $provision = 150;
                                    }
                                    else {
                                        $provision = 0;
                                    }
                                    break;
                                }
                                case 'ammount': {
                                    if($subtype > 100) { //target ammount
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
                        if($level >= 95){
                            $provision = 350;
                        }else if($level >= 90){
                            $provision = 300;
                        }else if($level >= 85 ){
                            $provision = 250;
                        }else if($level >= 80){
                            $provision = 200;
                        }else {
                            $provision = 0;
                        }
                        return $provision;
                        break;
                    }
                    case '3':{ // Telemarketing
                        if($level < 5) {
                            switch($subsubsubtype) {
                                case 'avg': {
                                    if($subsubtype > 100) { //target avg in %
                                        $provision = 150;
                                    }
                                    else {
                                        $provision = 0;
                                    }
                                    break;
                                }
                                case 'ammount': {
                                    if($subsubtype > 100) { //target ammount in %
                                        $provision = 150;
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
                        break;
                    }
                }
                break;
            }
            case 'koordynator': {
                if($level < 10) { //database use
                    if($subtype > 100) { //target > 100%
                        if($subsubtype > 90) { //working more than 90 days as coordinator
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
                break;
            }
            case 'coordinator leader': {
                if($level < 10) { //database use
                    if($subtype > 100) { //target > 100%
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