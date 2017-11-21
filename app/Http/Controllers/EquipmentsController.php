<?php

namespace App\Http\Controllers;

use App\Equipments;
use App\EquipmentTypes;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Department_info;

class EquipmentsController extends Controller
{
    public function showEquipment()
    {
        $equipments_types = Equipments::all();

        return view('hr.showEquipment')
            ->with('equipments_types', $equipments_types);
    }

    public function addEquipmentGet($type) {
        $equipments_types = EquipmentTypes::find($type);
        $users = User::all();
        $department_info = Department_info::all();

        return view('hr.addEquipment')
            ->with('equipments_types', $equipments_types)
            ->with('department_info', $department_info)
            ->with('users', $users);
    }

    public function addEquipmentPost(Request $request) {
        $equipment = new Equipments();

        $equipment->model = $request->model;
        $equipment->equipment_type_id = $request->equipment_type;
        $equipment->serial_code = $request->serial_code;
        $equipment->description = $request->description;
        $equipment->power_cable = $request->power_cable;
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
        $equipment->imei = $request->imei;
        $equipment->tablet_modem = $request->tablet_modem;
        $equipment->id_manager = Auth::user()->id;
        if($request->user_id == -1) {
              $equipment->id_user = 0;
        } else {
              $equipment->id_user = $request->user_id;
              $equipment->status = 1;
        }
        $equipment->department_info_id = $request->department_info_id;
        $equipment->created_at = date("Y-m-d H:i:s");
        $equipment->save();

        Session::flash('message_ok', "Sprzęt został dodany pomyślnie!");
        return redirect('/show_equipment');
    }

    public function editEquipmentGet($id) {
        $equipment = Equipments::find($id);
        $users = User::all();
        $department_info = Department_info::all();

        return view('hr.equipmentEdit')
            ->with('equipment', $equipment)
            ->with('department_info', $department_info)
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
        $equipment->department_info_id = $request->department_info_id;
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
        $equipment->imei = $request->imei;
        $equipment->tablet_modem = $request->tablet_modem;
        $equipment->id_manager = Auth::user()->id;
        $equipment->updated_at = date("Y-m-d H:i:s");
        $equipment->save();

        $equipment = Equipments::find($id);
        $users = User::all();
        $department_info = Department_info::all();

        Session::flash('message_ok', "Zmiany zapisano!");
        return Redirect::back();
    }
}
