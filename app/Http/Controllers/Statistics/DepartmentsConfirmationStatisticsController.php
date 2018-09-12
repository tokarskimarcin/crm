<?php

namespace App\Http\Controllers\Statistics;

use App\Department_info;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentsConfirmationStatisticsController extends Controller
{
    //

    public function departmentsConfirmationGet(){
        $deps = Department_info::where('id_dep_type', 1)->with('departments')->with('department_type')->get();
        return view('statistics.departmentsConfirmationStatistics')->with('deps',$deps);
    }


    public function departmentsConfirmationStatisticsAjax(Request $request){
        $collect = collect();
        $collect->push(['name' => 'Marcin Tokarski1',   'shows' => 20,'provision' => 300, 'successful' => 12,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski4',   'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski3',   'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski2',   'shows' => 20,'provision' => 300, 'successful' => 12,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski5',   'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski8',   'shows' => 20,'provision' => 250, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski6',   'shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski19',  'shows' => 20,'provision' => 300, 'successful' => 12,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski7',   'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski20',  'shows' => 20,'provision' => 300, 'successful' => 12,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski11',  'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski16',  'shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski13',  'shows' => 20,'provision' => 100, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-01', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski15',  'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski9',   'shows' => 20,'provision' => 200, 'successful' => 10,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski10',  'shows' => 20,'provision' => -100, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski17',  'shows' => 20,'provision' => -200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski14',  'shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-02', 'dateGroup' => '2018-09-01 - 2018-09-09', 'trainer' => 'Małgorzata Wawrzyn']);
        $collect->push(['name' => 'Marcin Tokarski15',  'shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-11', 'dateGroup' => '2018-09-10 - 2018-09-16', 'trainer' => 'Piotr Sulisz']);
        $collect->push(['name' => 'Marcin Tokarski17',  'shows' => 20,'provision' => 200, 'successful' => 11,'successfulPct'=> 63.45, 'neutral' => 3, 'neutralPct'=> 30.20, 'date' => '2018-09-12', 'dateGroup' => '2018-09-10 - 2018-09-16', 'trainer' => 'Małgorzata Wawrzyn']);

        //wymagane operacje:
        $collect = $collect->sortByDesc('provision')->groupBy('dateGroup');
        if($request->trainersGrouping == 'true'){
            foreach ($collect as $dateGroup => $dateGroupCollection){
                $collect[$dateGroup] = $dateGroupCollection->groupBy('trainer');
            }
        }

        return $collect;
    }


}
