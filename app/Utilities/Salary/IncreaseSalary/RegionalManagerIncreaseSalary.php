<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 10:26
 */

namespace App\Utilities\Salary\IncreaseSalary;


use App\Department_info;
use App\User;

class RegionalManagerIncreaseSalary
{
    public static function set($user){
        $log = null;
        $numberOfDepartments = Department_info::numberOfDepartments($user, 'regionalManager_id');

        if($numberOfDepartments >= 3) {
            if($user->salary < 4000) {
                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 4000';
                User::find($user->id)->update(['salary' => 4000]);
            }
        }
        else if($numberOfDepartments >= 2) {
            if($user->salary < 3500) {
                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 3500';
                User::find($user->id)->update(['salary' => 3500]);
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