<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 10:25
 */

namespace App\Utilities\Salary\IncreaseSalary;


use App\User;

class RegionalManagerHRIncreaseSalary
{
    public static function set($user){
        $log = null;
        if($user->salary < 3500) {
            $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 3500';
            User::find($user->id)->update(['salary' => 3500]);
        }
        return $log;
    }
}