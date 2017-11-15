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

class TestORM extends Controller
{
    public function test() {

        $user = User::find(1);
        $links = Links::find(3);

      return view('testorm')->with('user', $user);



    }

}
