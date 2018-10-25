<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 14.09.18
 * Time: 11:00
 */

namespace App\Utilities\Dates;


class MonthFourWeeksDivision
{
    public static function get($year, $month){
        $dividedMonthIntoCompanyWeeks = MonthIntoCompanyWeeksDivision::get($month,$year);
        //dd($dividedMonthIntoCompanyWeeks);
        $lastDay = date('t',strtotime($year.'-'.$month));
        $dividedMonthIntoCompanyWeeks[0]->firstDay = date('Y-m-d',strtotime($year.'-'.$month));
        $dividedMonthIntoCompanyWeeks[3]->lastDay = date('Y-m-d',strtotime($year.'-'.$month.'-'.$lastDay));
        if(count($dividedMonthIntoCompanyWeeks) > 4){
            unset($dividedMonthIntoCompanyWeeks[4]);
        }
        $dividedMonthIntoFourWeeks = [];
        foreach($dividedMonthIntoCompanyWeeks as $week){
            array_push($dividedMonthIntoFourWeeks,(object)['firstDay'=>$week->firstDay, 'lastDay'=>$week->lastDay]);
        }

        return $dividedMonthIntoFourWeeks;
    }
}