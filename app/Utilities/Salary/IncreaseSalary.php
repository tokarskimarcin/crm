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
use App\Utilities\Salary\IncreaseSalary\CoordinatorIncreaseSalary;
use App\Utilities\Salary\IncreaseSalary\CoordinatorManagerIncreaseSalary;
use App\Utilities\Salary\IncreaseSalary\HRIncreaseSalary;
use App\Utilities\Salary\IncreaseSalary\InstructorIncreaseSalary;
use App\Utilities\Salary\IncreaseSalary\MobileTrainerIncreaseSalary;
use App\Utilities\Salary\IncreaseSalary\RegionalManagerHRIncreaseSalary;
use App\Utilities\Salary\IncreaseSalary\RegionalManagerIncreaseSalary;
use App\Utilities\Salary\IncreaseSalary\TrainerIncreaseSalary;
use Illuminate\Support\Facades\DB;

class IncreaseSalary
{
    public static function set(User $user) {
        $daysInPosition = IncreaseSalary::getWorkingDays($user); //Number of days at current position
        $count = 0;
        $log = null;

        switch($user->user_type_id) {
            case 4: { //trener
                $log = TrainerIncreaseSalary::set($user, $daysInPosition);
                break;
            }
            case 5: { //HR
                $log = HRIncreaseSalary::set($user, $daysInPosition);
                break;
            }
            case 8: { //koordynatorzy
                $log = CoordinatorIncreaseSalary::set($user, $daysInPosition);
                break;
            }
            case 14: { //kierownik regionalny HR
                $log = RegionalManagerHRIncreaseSalary::set($user);
                break;
            }
            case 17: { //kierownik regionalny
                $log = RegionalManagerIncreaseSalary::set($user);
                break;
            }
            case 19: { //Szkoleniowiec
                $log = InstructorIncreaseSalary::set($user, $daysInPosition);
                break;
            }
            case 20: { //trener mobilny
                $log = MobileTrainerIncreaseSalary::set($user);
                break;
            }
            case 21: { //szkoleniowiec regionalny
                $log = RegionalManagerIncreaseSalary::set($user);
                break;
            }
            case 22: { //kierownik kordynatorow
                $log = CoordinatorManagerIncreaseSalary::set($user);
                break;
            }
            default: {

                break;
            }
        }

    return $log;
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