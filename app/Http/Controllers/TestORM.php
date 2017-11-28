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
use App\MultipleDepartments;
use App\ActivityRecorder;
use Illuminate\Support\Facades\Storage;

class TestORM extends Controller
{
    public function test() {

      // $file = asset('storage/file.txt');
      // var_dump($file);

      // $visibility = Storage::getVisibility('activity.txt');
      //
      // Storage::setVisibility('activity.txt', 'public');

      // $contents = Storage::get('activity.txt');
      // echo $contents;
    new ActivityRecorder('My new acctivityag rg rtg wrth wrht wrht wrht wrth  trhw rthw rht wrth wrht wrth hrwht wrht wr htwrht wwrt');


    }

}
