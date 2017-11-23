<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Department_info;
use App\Department_types;
use App\Departments;
use App\UserTypes;
use App\Agencies;
use App\Schedule;
use App\PenaltyBonus;
use App\Work_hour;
use App\Dkj;
use App\Links;
use App\LinkGroups;
use App\PrivilageRelation;
use App\SummaryPayment;
use App\EquipmentTypes;
use App\Equipments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestORM extends Controller
{
    public function test() {

      $sec = DB::select("SELECT SEC_TO_TIME(TIME_TO_SEC(data_stop) - TIME_TO_SEC(data_start) ) as time from notifications where id = 9");
      var_dump($sec[0]->time);

    }

}
