<?php
/**
 * Created by PhpStorm.
 * User: Verona
 * Date: 03.10.2018
 * Time: 14:19
 */

namespace App\Utilities\Salary;


use App\User;

class IncreaseSalary
{
    public static function set(User $user) {
        $daysInPosition = IncreaseSalary::getWorkingDays($user);

        switch($user->user_type_id) {
            case 5: { //HR
                switch($user->id_dep_type) {
                    case 1: { //Potwierdzanie
                        if($daysInPosition > 90) {
                            if($user->salary < 2500) {
                                User::find($user->id)->update(['salary' => 2500]);
                            }

                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 90) {
                            if($user->salary < 2200) {
                                User::find($user->id)->update(['salary' => 2200]);
                            }
                        }
                        break;
                    }
                    case 2: { //telemarketing
                        if($daysInPosition > 365) {
                            if($user->salary < 2500) {
                                User::find($user->id)->update(['salary' => 2500]);
                            }
                        }
                        else if($daysInPosition > 180) {
                            if($user->salary < 2250) {
                                User::find($user->id)->update(['salary' => 2250]);
                            }
                        }
                        else if($daysInPosition > 60) {
                            if($user->salary < 2000) {
                                User::find($user->id)->update(['salary' => 2000]);
                            }
                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 60) {
                            if($user->salary < 1600) {
                                User::find($user->id)->update(['salary' => 1600]);
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
            case 4: { //trener
                switch($user->id_dep_type) {
                    case 1: { //Potwierdzanie
                        if($daysInPosition > 90) {
                            if($user->salary < 2500) {
                                User::find($user->id)->update(['salary' => 2500]);
                            }

                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 90) {
                            if($user->salary < 2200) {
                                User::find($user->id)->update(['salary' => 2200]);
                            }
                        }
                        break;
                    }
                    case 2: { //telemarketing
                        if($daysInPosition > 365) {
                            if($user->salary < 2500) {
                                User::find($user->id)->update(['salary' => 2500]);
                            }
                        }
                        else if($daysInPosition > 180) {
                            if($user->salary < 2250) {
                                User::find($user->id)->update(['salary' => 2250]);
                            }
                        }
                        else if($daysInPosition > 60) {
                            if($user->salary < 2000) {
                                User::find($user->id)->update(['salary' => 2000]);
                            }
                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 60) {
                            if($user->salary < 1600) {
                                User::find($user->id)->update(['salary' => 1600]);
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
            case 19: { //Szkoleniowiec
                switch($user->id_dep_type) {
                    case 1: { //Potwierdzanie
                        if($daysInPosition > 90) {
                            if($user->salary < 2500) {
                                User::find($user->id)->update(['salary' => 2500]);
                            }

                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 90) {
                            if($user->salary < 2200) {
                                User::find($user->id)->update(['salary' => 2200]);
                            }
                        }
                        break;
                    }
                    case 2: { //telemarketing
                        if($daysInPosition > 365) {
                            if($user->salary < 2500) {
                                User::find($user->id)->update(['salary' => 2500]);
                            }
                        }
                        else if($daysInPosition > 180) {
                            if($user->salary < 2250) {
                                User::find($user->id)->update(['salary' => 2250]);
                            }
                        }
                        else if($daysInPosition > 60) {
                            if($user->salary < 2000) {
                                User::find($user->id)->update(['salary' => 2000]);
                            }
                        }
                        else if($daysInPosition > 0 && $daysInPosition <= 60) {
                            if($user->salary < 1600) {
                                User::find($user->id)->update(['salary' => 1600]);
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
        }
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