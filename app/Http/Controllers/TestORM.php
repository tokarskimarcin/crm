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

class TestORM extends Controller
{
    public function test() {

        $equipments_types = Equipments::all();

        return view('hr.showEquipment')
            ->with('equipments_types', $equipments_types);

    }

}
