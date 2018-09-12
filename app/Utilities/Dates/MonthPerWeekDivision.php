<?php
/**
 * Created by PhpStorm.
 * User: Verona
 * Date: 12.09.2018
 * Time: 09:21
 */

namespace App\Utilities\Dates;

class MonthPerWeekDivision
{
    public static function get($month,$year){
        $arrayOfWeekName = [
            '1' => 'Poniedziałek',
            '2' => 'Wtorek',
            '3' => 'Środa',
            '4' => 'Czwartek',
            '5' => 'Piątek',
            '6' => 'Sobota',
            '7' => 'Niedziela'];

        $days_in_month = date('t', strtotime($year . '-' . $month));

        $numberOfWeekPreviusMonth = WeekNumber::get(date('Y-m-d', strtotime($year.'-'.$month.'-01'. ' - 1 days')));

        $weeks = [];
        for ($i = 1; $i <= $days_in_month; $i++) {
            $loop_day = ($i < 10) ? '0' . $i : $i ;
            $date = $year.'-'.$month.'-'.$loop_day;
            $actualWeek = WeekNumber::get($date);

            if($actualWeek != $numberOfWeekPreviusMonth){
                foreach($arrayOfWeekName as $key => $value) {
                    if($value == NameOfWeek::get($date, 'long')) {
                        $weeksObj = new \stdClass();
                        $weeksObj->date = $date;
                        $weeksObj->weekNumber = date('W', strtotime($date));
                        $weeksObj->name = NameOfWeek::get($date, 'short');
                        $weeksObj->dayNumber = $key;
                        array_push($weeks,$weeksObj);

                        // czy niedziela
                        if($weeksObj->name == 'Nd') {
                            $sumObj = new \stdClass();
                            $sumObj->date = 'Suma';
                            $sumObj->weekNumber = date('W', strtotime($date));
                            $sumObj->name = 'Suma';
                            array_push($weeks, $sumObj);
                        }
                    }
                }
            }
        }

        $lastNumberOfWeek = $actualWeek;
        $dateNextMonth = date('Y-m-d', strtotime($date . ' + 1 days'));
        $daysInNextMonth = date('t', strtotime($dateNextMonth));
        for ($i = 1; $i <= $daysInNextMonth; $i++) {
            $loop_day = ($i < 10) ? '0' . $i : $i ;
            $date = date('Y-m',strtotime($dateNextMonth)).'-'.$loop_day;
            $actualWeek = WeekNumber::get($date);
            if($actualWeek == $lastNumberOfWeek){
                foreach($arrayOfWeekName as $key => $value) {
                    if($value == NameOfWeek::get($date, 'short')) {
                        $weeksObj = new \stdClass();
                        $weeksObj->date = $date;
                        $weeksObj->weekNumber = date('W', strtotime($date));
                        $weeksObj->name = NameOfWeek::get($date, 'short');
                        $weeksObj->dayNumber = $key;
                        array_push($weeks,$weeksObj);
                    }
                }
            }else{
                break;
            }
        }
        return $weeks;
    }
}