<?php

namespace App\Http\Controllers\Statistics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentsConfirmationStatisticsController extends Controller
{
    //

    public function departmentsConfirmationGet(){
        return view('statistics.departmentsConfirmationStatistics');
    }


    public function departmentsConfirmationStatisticsAjax(){
        $collect = collect();
        $collect->push(['name' => 'Marcin Tokarski', 'shows' => 20,'provision' => 300, 'successful' => 12,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski2','shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski3','shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski', 'shows' => 20,'provision' => 300, 'successful' => 12,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski2','shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski3','shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski4','shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski2','shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski3','shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski4','shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski4','shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski2','shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski3','shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski4','shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski4','shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski4','shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski4','shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-11', 'dateGroup' => '2018-09-10 - 2018-09-16', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski4','shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-12', 'dateGroup' => '2018-09-10 - 2018-09-16', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect = $collect->groupBy('dateGroup');
        foreach ($collect as $dateGroup => $dateGroupCollection){
            $collect[$dateGroup] = $dateGroupCollection->groupBy('trainer');
        }
        return $collect;
    }
}
