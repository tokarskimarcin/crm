<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 14.09.18
 * Time: 09:14
 */

namespace App\Utilities\Dates;


class MonthIntoCompanyWeeksDivision
{
    public static function get($month, $year){
        $dividedMonth = MonthPerWeekDivision::get($month,$year);

        $newDividedMonth = [];
        foreach ($dividedMonth as $day){
            if($day->date !== 'Suma'){
                array_push($newDividedMonth, $day);
            }
        }
        //dd($newDividedMonth);
        /*$weekDateArr = []; // array of objects with week info
        //*****Generating weekDateArr
        $tempFirstDate = $dividedMonth[0]->date;
        $tempLastDate = null;
        $tempWeek = $dividedMonth[0]->weekNumber;
        $tempDate = $dividedMonth[0]->date;
        $i = 0;
        for($i = 0; $i < count($dividedMonth); $i++) {
            $dateObj = new \stdClass();

            $dateObj->firstDay = null;
            $dateObj->lastDay = null;
            $dateObj->weekNumber = null;
            if($dividedMonth[$i]->date == 'Suma') {
                $tempLastDate = $tempDate;
            }
            if($dividedMonth[$i]->weekNumber != $tempWeek) {
                $dateObj->lastDay = $tempLastDate;
                $dateObj->firstDay = $tempFirstDate;
                $dateObj->weekNumber = $tempWeek;
                array_push($weekDateArr, $dateObj);
                $tempWeek = $dividedMonth[$i]->weekNumber;
                $tempDate = $dividedMonth[$i]->date;
                $tempFirstDate = $dividedMonth[$i]->date;
            }
            else {
                $tempDate = $dividedMonth[$i]->date;
            }

            if($i == count($dividedMonth) - 1) {
                $dateObj->weekNumber = $dividedMonth[$i]->weekNumber;
                $dateObj->firstDay = $tempFirstDate;
                $dateObj->lastDay = $tempLastDate;//$dividedMonth[$i]->date == 'Suma' ? $tempLastDate : $dividedMonth[$i]->date;
                //dd($dateObj);
                array_push($weekDateArr, $dateObj);
            }
        }*/
        $newDividedMonth = collect($newDividedMonth);
        $weekDateArr = [];
        $weekNumbers = $newDividedMonth->pluck('weekNumber')->unique()->toArray();
        foreach ($weekNumbers as $weekNumber){
            $weekData = $newDividedMonth->where('weekNumber',$weekNumber)->sortBy('dayNumber');
            array_push($weekDateArr, (object)['weekNumber' => $weekNumber,'firstDay' => $weekData->first()->date,'lastDay'=> $weekData->last()->date]);
        }
        //*****End of generating weekDateArr
        return $weekDateArr;
    }
}