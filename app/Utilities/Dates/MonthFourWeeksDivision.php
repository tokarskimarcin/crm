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
        $lastDay = date('t',strtotime($year.'-'.$month));
        if(date('j',strtotime($dividedMonthIntoCompanyWeeks[0]->firstDay)) != 1){
            $dividedMonthIntoCompanyWeeks[0]->firstDay = date('Y-m-d',strtotime($year.'-'.$month));
            $dividedMonthIntoCompanyWeeks[3]->lastDay = date('Y-m-d',strtotime($year.'-'.$month.'-'.$lastDay));
        }else{
            $dividedMonthIntoCompanyWeeks[3]->lastDay = date('Y-m-d',strtotime($year.'-'.$month.'-'.$lastDay));
            unset($dividedMonthIntoCompanyWeeks[4]);
        }

        $dividedMonthIntoFourWeeks = [];
        foreach($dividedMonthIntoCompanyWeeks as $week){
            array_push($dividedMonthIntoFourWeeks,(object)['firstDay'=>$week->firstDay, 'lastDay'=>$week->lastDay]);
        }
        return $dividedMonthIntoFourWeeks;
    }
}