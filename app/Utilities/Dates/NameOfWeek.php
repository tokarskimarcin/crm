<?php
/**
 * Created by PhpStorm.
 * User: Verona
 * Date: 12.09.2018
 * Time: 09:29
 */

namespace App\Utilities\Dates;


class NameOfWeek
{
    /**
     * This method returns name of week day.
     * @param $date
     * @param $type
     * @return mixed
     */
    public static function get($date, $type){
        $arrayOfWeekName = [];

        switch($type) {
            case 'short': {
                $arrayOfWeekName = [
                    '1' => 'Pn',
                    '2' => 'Wt',
                    '3' => 'Śr',
                    '4' => 'Czw',
                    '5' => 'Pt',
                    '6' => 'Sb',
                    '7' => 'Nd'];
                break;
            }
            case 'long': {
                $arrayOfWeekName = [
                    '1' => 'Poniedziałek',
                    '2' => 'Wtorek',
                    '3' => 'Środa',
                    '4' => 'Czwartek',
                    '5' => 'Piątek',
                    '6' => 'Sobota',
                    '7' => 'Niedziela'];
                break;
            }
            default: {

            }
        }

        return $arrayOfWeekName[date('N',strtotime($date))+0];
    }
}