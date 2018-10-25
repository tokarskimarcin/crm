<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 25.10.18
 * Time: 10:39
 */

namespace App\Utilities\Salary\IncreaseSalary;


use App\User;

class CoordinatorManagerIncreaseSalary
{
    public static function set($user){
        $log = null;
        if($user->salary < 4000) {
            $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 4000';
            User::find($user->id)->update(['salary' => 4000]);
        }
        return $log;
    }
}