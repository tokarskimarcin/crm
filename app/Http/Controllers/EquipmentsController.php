<?php

namespace App\Http\Controllers;

use App\Equipments;
use App\EquipmentTypes;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Session;
use Illuminate\Support\Facades\Auth;

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

    public function editEquipmentGet($id) {
        $equipment = Equipments::find($id);
        $users = User::all();

        return view('hr.equipmentEdit')
            ->with('equipment', $equipment)
            ->with('users', $users);
    }

    public function editEquipmentPost($id, Request $request) { // do poprawienia
        $equipment = Equipments::find($id);

        $equipment->model = $request->model;
        $equipment->serial_code = $request->serial_code;
        $equipment->description = $request->description;
        $equipment->power_cable = $request->power_cable;
        if($request->user_id == -1) {
            $equipment->id_user = 0;
        } else {
              $equipment->id_user = $request->user_id;
              $equipment->status = 1;
        }
        if($request->user_set == null && $request->user_id != -1) {
            $equipment->to_user = date("Y-m-d H:i:s");
        }
        $equipment->laptop_processor = $request->laptop_processor;
        $equipment->laptop_ram = $request->laptop_ram;
        $equipment->laptop_hard_drive = $request->laptop_hard_drive;
        $equipment->phone_box = $request->phone_box;
        $equipment->tablet_modem = $request->tablet_modem;
        $equipment->sim_number_phone = $request->sim_number_phone;
        $equipment->sim_type = $request->sim_type;
        $equipment->sim_pin = $request->sim_pin;
        $equipment->sim_puk = $request->sim_puk;
        $equipment->sim_net = $request->sim_net;
        $equipment->signal_cable = $request->signal_cable;
        $equipment->sim_type = $request->sim_type;
        $equipment->sim_type = Auth::user()->id;
        $equipment->updated_at = date("Y-m-d H:i:s");
        $equipment->save();

        $equipment = Equipments::find($id);
        $users = User::all();

        return view('hr.equipmentEdit')
            ->with('equipment', $equipment)
            ->with('message_ok', 'Dane zapisane!')
            ->with('users', $users);
    }
}
