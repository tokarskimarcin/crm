<?php
/**
 * Created by PhpStorm.
 * User: Verona
 * Date: 03.10.2018
 * Time: 14:19
 */

namespace App\Utilities\Salary;


use App\ActivityRecorder;
use App\Department_info;
use App\User;
use Illuminate\Support\Facades\DB;

class IncreaseSalary
{
    public static function set(User $user) {
        $daysInPosition = IncreaseSalary::getWorkingDays($user); //Number of days at current position
        $count = 0;
        $log = null;

        switch($user->user_type_id) {
            case 4: { //trener
                switch($user->id_dep_type) {
                    case 1: { //Potwierdzanie
                        if($daysInPosition > 90) {
                            if($user->salary < 2500) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
//                                User::find($user->id)->update(['salary' => 2500]);
                            }

                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 90) {
                            if($user->salary < 2200) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2200';
//                                User::find($user->id)->update(['salary' => 2200]);
                            }
                        }
                        break;
                    }
                    case 2: { //telemarketing
                        if($daysInPosition > 365) {
                            if($user->salary < 2500) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
//                                User::find($user->id)->update(['salary' => 2500]);
                            }
                        }
                        else if($daysInPosition > 180) {
                            if($user->salary < 2250) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2250';
//                                User::find($user->id)->update(['salary' => 2250]);
                            }
                        }
                        else if($daysInPosition > 60) {
                            if($user->salary < 2000) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2000';
//                                User::find($user->id)->update(['salary' => 2000]);
                            }
                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 60) {
                            if($user->salary < 1600) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 1600';
//                                User::find($user->id)->update(['salary' => 1600]);
                            }
                        }
                        break;
                    }
                    default: {
                        break;
                    }
                }

                break;
            }
            case 5: { //HR
                switch($user->id_dep_type) {
                    case 1: { //Potwierdzanie
                        if($daysInPosition > 90) {
                            if($user->salary < 2500) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
//                                User::find($user->id)->update(['salary' => 2500]);
                            }

                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 90) {
                            if($user->salary < 2200) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2200';
//                                User::find($user->id)->update(['salary' => 2200]);
                            }
                        }
                        break;
                    }
                    case 2: { //telemarketing
                        if($daysInPosition > 365) {
                            if($user->salary < 2500) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
//                                User::find($user->id)->update(['salary' => 2500]);
                            }
                        }
                        else if($daysInPosition > 180) {
                            if($user->salary < 2250) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2250';
//                                User::find($user->id)->update(['salary' => 2250]);
                            }
                        }
                        else if($daysInPosition > 60) {
                            if($user->salary < 2000) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2000';
//                                User::find($user->id)->update(['salary' => 2000]);
                            }
                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 60) {
                            if($user->salary < 1600) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 1600';
//                                User::find($user->id)->update(['salary' => 1600]);
                            }
                        }
                        break;
                    }
                    default: {
                        break;
                    }
                }

                break;
            }
            case 8: { //koordynatorzy
                if($daysInPosition > 90) {
                    if($user->salary < 3000) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 3000';
//                        User::find($user->id)->update(['salary' => 3000]);
                    }
                }
                else {
                    if($user->salary < 2500) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
//                        User::find($user->id)->update(['salary' => 2500]);
                    }
                }

                break;
            }
            case 14: { //kierownik regionalny HR
                if($user->salary < 3500) {
                    $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 3500';
//                    User::find($user->id)->update(['salary' => 3500]);
                }

                break;
            }
            case 17: { //kierownik regionalny
                $numberOfDepartments = Department_info::numberOfDepartments($user, 'regionalManager_id');

                if($numberOfDepartments >= 3) {
                    if($user->salary < 4000) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 4000';
//                        User::find($user->id)->update(['salary' => 4000]);
                    }
                }
                else if($numberOfDepartments >= 2) {
                    if($user->salary < 3500) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 3500';
//                        User::find($user->id)->update(['salary' => 3500]);
                    }
                }
                else {
                    if($user->salary < 2500) {
                        $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
//                        User::find($user->id)->update(['salary' => 2500]);
                    }
                }
                break;
            }
            case 19: { //Szkoleniowiec
                switch($user->id_dep_type) {
                    case 1: { //Potwierdzanie
                        if($daysInPosition > 90) {
                            if($user->salary < 2500) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
//                                User::find($user->id)->update(['salary' => 2500]);
                            }

                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 90) {
                            if($user->salary < 2200) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2200';
//                                User::find($user->id)->update(['salary' => 2200]);
                            }
                        }
                        break;
                    }
                    case 2: { //telemarketing
                        if($daysInPosition > 365) {
                            if($user->salary < 2500) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2500';
//                                User::find($user->id)->update(['salary' => 2500]);
                            }
                        }
                        else if($daysInPosition > 180) {
                            if($user->salary < 2250) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2250';
//                                User::find($user->id)->update(['salary' => 2250]);
                            }
                        }
                        else if($daysInPosition > 60) {
                            if($user->salary < 2000) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 2000';
//                                User::find($user->id)->update(['salary' => 2000]);
                            }
                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 60) {
                            if($user->salary < 1600) {
                                $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 1600';
//                                User::find($user->id)->update(['salary' => 1600]);
                            }
                        }
                        break;
                    }
                    default: {
                        break;
                    }
                }

                break;
            }
            case 20: { //trener mobilny
                if($user->salary < 4000) {
                    $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 4000';
//                    User::find($user->id)->update(['salary' => 4000]);
                }

                break;
            }
            case 21: { //szkoleniowiec regionalny
                if($user->salary < 3500) {
                    $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 3500';
//                    User::find($user->id)->update(['salary' => 3500]);
                }

                break;
            }
            case 22: {
                if($user->salary < 4000) {
                    $log = 'ID: ' . $user->id . ' Before: ' . $user->salary . ' After: 4000';
//                    User::find($user->id)->update(['salary' => 4000]);
                }

                break;
            }
            default: {

                break;
            }
        }

    return $log == null ? null : $log;
    }

    /**
     * @param User $user
     * @return int|mixed
     * This method returns number of days user work in his/her current occupation
     */
    private static function getWorkingDays(User $user) {
        $today = new \DateTime();
        $start_work = new \DateTime($user->start_work);
        $promotion_date = $user->promotion_date ? new \DateTime($user->promotion_date) : null;
        $degradation_date = $user->degradation_date ? new \DateTime($user->promotion_date) : null;
        $dayInPosition = 0;

        if($promotion_date != null) { //user was promoted
            $dayInPosition = $today->diff($promotion_date)->days;
        }
        else if($degradation_date != null) {
            $dayInPosition = $today->diff($degradation_date)->days;
        }
        else { //user wasnt promoted <=> first occupation
            $dayInPosition = $today->diff($start_work)->days; //How long is he/she working at current occupation
        }

        return $dayInPosition;
    }
}