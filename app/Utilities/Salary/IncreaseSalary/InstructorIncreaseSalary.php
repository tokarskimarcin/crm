<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 10:28
 */

namespace App\Utilities\Salary\IncreaseSalary;


use App\User;

class InstructorIncreaseSalary
{
    public static function set($user, $daysInPosition){
        $log = null;
        switch($user->id_dep_type) {
            case 1: { //Potwierdzanie
                if($daysInPosition > 90) {
                    if($user->salary < 2500) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
                        User::find($user->id)->update(['salary' => 2500]);
                    }

                }
                else if($daysInPosition > 0 && $daysInPosition <= 90) {
                    if($user->salary < 2200) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2200';
                        User::find($user->id)->update(['salary' => 2200]);
                    }
                }
                break;
            }
            case 2: { //telemarketing
                if($daysInPosition > 365) {
                    if($user->salary < 2500) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
                        User::find($user->id)->update(['salary' => 2500]);
                    }
                }
                else if($daysInPosition > 180) {
                    if($user->salary < 2250) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2250';
                        User::find($user->id)->update(['salary' => 2250]);
                    }
                }
                else if($daysInPosition > 60) {
                    if($user->salary < 2000) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2000';
                        User::find($user->id)->update(['salary' => 2000]);
                    }
                }
                else if($daysInPosition > 0 && $daysInPosition <= 60) {
                    if($user->salary < 1600) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 1600';
                        User::find($user->id)->update(['salary' => 1600]);
                    }
                }
                break;
            }
            default: {
                break;
            }
        }
        return $log;
    }
}