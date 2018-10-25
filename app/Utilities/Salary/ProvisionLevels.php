<?php
/**
 * Created by PhpStorm.
 * User: Verona
 * Date: 13.09.2018
 * Time: 11:53
 */

namespace App\Utilities\Salary;


use App\Utilities\Salary\ProvisionLevels\ConsultantProvisionLevels;
use App\Utilities\Salary\ProvisionLevels\CoordinatorLeaderProvisionLevels;
use App\Utilities\Salary\ProvisionLevels\CoordinatorProvisionLevels;
use App\Utilities\Salary\ProvisionLevels\HRProvisionLevels;
use App\Utilities\Salary\ProvisionLevels\InstructorProvisionLevels;
use App\Utilities\Salary\ProvisionLevels\ManagerHRProvisionLevels;
use App\Utilities\Salary\ProvisionLevels\ManagerInstructorProvisionLevels;
use App\Utilities\Salary\ProvisionLevels\ManagerProvisionLevels;
use App\Utilities\Salary\ProvisionLevels\TrainerProvisionLevels;

class ProvisionLevels
{
    /**
     * //This method return premium money amount in case of frequency amount
     * @param $level - float number sometimes it is percent sometimes it is value, depends of type or subtype
     * @param $occupation - name of role
     * @param null $subtype - different variations of provisions for some type, defined as integer.
     * @return int - value of provision
     */
    public static function get($occupation, $level, $subtype = 1, $subsubtype = null, $subsubsubtype = null, $subsubsubsubtype = null) {
                switch($occupation) {
            case 'consultant': {
                return ConsultantProvisionLevels::get($level, $subtype);
                break;
            }
            case 'trainer': {
                return TrainerProvisionLevels::get($level, $subtype, $subsubtype, $subsubsubtype);
                break;
            }
            case 'manager': {
                return ManagerProvisionLevels::get($level, $subsubtype, $subsubsubtype, $subsubsubsubtype);
                break;
            }
            case 'managerInctructor' : {
                return ManagerInstructorProvisionLevels::get($level, $subsubtype, $subsubsubtype);
                break;
            }
            case 'managerHR' : {
                return ManagerHRProvisionLevels::get($level, $subsubtype, $subsubsubtype);
                break;
            }
            case 'HR': {
                return HRProvisionLevels::get($level, $subtype, $subsubtype, $subsubsubtype);
                break;
            }
            case 'instructor': {
                return InstructorProvisionLevels::get($level, $subtype, $subsubtype, $subsubsubtype);
                break;
            }
            case 'koordynator': {
                return CoordinatorProvisionLevels::get($level, $subtype, $subsubtype);
                break;
            }
            case 'coordinator leader': {
                return CoordinatorLeaderProvisionLevels::get($level, $subtype);
                break;
            }
        }
        throw new \Exception('Wrong parameters in get() method');
    }
}