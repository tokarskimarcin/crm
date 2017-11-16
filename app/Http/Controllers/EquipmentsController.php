<?php

namespace App\Http\Controllers;

use App\Equipments;
use App\EquipmentTypes;
use Illuminate\Http\Request;

class EquipmentsController extends Controller
{
    public function showEquipment()
    {
        $equipments = Equipments::all();
        $equipments_types = EquipmentTypes::all();
        return view('hr.showEquipment')
            ->with('equipments',$equipments)
            ->with('equipments_types',$equipments_types);
    }
}
