<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 10:23
 */

namespace App\Utilities\Salary\IncreaseSalary;


use App\User;

class CoordinatorIncreaseSalary
{
    public static function set($user, $daysInPosition){
        $log = null;
        if($daysInPosition > 90) {
            if($user->salary < 3000) {
                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 3000';
                User::find($user->id)->update(['salary' => 3000]);
            }
        }
        else {
            if($user->salary < 2500) {
                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
                User::find($user->id)->update(['salary' => 2500]);
            }
        }
        return $log;
    }
}