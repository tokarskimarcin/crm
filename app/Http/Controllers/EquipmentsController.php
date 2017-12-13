<?php

namespace App\Http\Controllers;

use App\Equipments;
use App\EquipmentTypes;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Department_info;
use App\ActivityRecorder;

class EquipmentsController extends Controller
{
    public function showEquipment()
    {
        return view('hr.showEquipment');
    }
    private function datatableEquipmentData($type) {
      $data = DB::table('equipments')
          ->select(DB::raw('
              equipments.*,
              departments.name as dep_name,
              department_type.name as dep_name_type,
              users.first_name,
              users.last_name
          '))
          ->leftJoin('users', 'users.id', '=', 'equipments.id_user')
          ->leftJoin('department_info', 'department_info.id', '=', 'equipments.department_info_id')
          ->leftJoin('departments', 'departments.id', '=', 'department_info.id_dep')
          ->leftJoin('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
          ->where('equipment_type_id', '=', $type)
          ->get();

          return $data;
    }

    public function datatableShowLaptop(Request $request) {
          $data = $this->datatableEquipmentData(1);

          return datatables($data)->make(true);
    }

    public function datatableShowTablet(Request $request) {
          $data = $this->datatableEquipmentData(2);

          return datatables($data)->make(true);
    }
    public function datatableShowPhone(Request $request) {
          $data = $this->datatableEquipmentData(3);

          return datatables($data)->make(true);
    }
    public function datatableShowSimCard(Request $request) {
          $data = $this->datatableEquipmentData(4);

          return datatables($data)->make(true); 
    }

    public function datatableShowMonitor(Request $request) {
          $data = $this->datatableEquipmentData(5);

          return datatables($data)->make(true);
    }
    public function datatableShowPrinter(Request $request) {
          $data = $this->datatableEquipmentData(6);

          return datatables($data)->make(true);
    }

    public function addEquipmentGet($type) {
        $equipments_types = EquipmentTypes::find($type);
        $users = User::where('status_work', '=', 1)
            ->groupBy('last_name')
            ->get();
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

        $data = [
            'Dodanie sprzętu firmowego' => '',
            'equipment_type' => $request->equipment_type,
            'serial_code' => $request->serial_code,
            'model' => $request->model,
        ];

        new ActivityRecorder(6, $data);

        Session::flash('message_ok', "Sprzęt został dodany pomyślnie!");
        return redirect('/show_equipment');
    }

    public function editEquipmentGet($id) {
        $equipment = Equipments::find($id);

        if ($equipment->deleted == 1) {
            return view('404');
        }

        $users = User::all();
        $department_info = Department_info::all();

        return view('hr.equipmentEdit')
            ->with('equipment', $equipment)
            ->with('department_info', $department_info)
            ->with('users', $users);
    }

    public function editEquipmentPost($id, Request $request) {
        $equipment = Equipments::find($id);

        $equipment->deleted = $request->status_delete;
        if ($request->status_delete == 1) {
          new ActivityRecorder(6, 'Usunięcie sprzętu o Id: ' . $equipment->id);
          $equipment->save();
          Session::flash('message_ok', "Sprzęt usunięty pomyślnie!");
          return redirect('/show_equipment');
        }
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

        $data = [
            'Edycja sprzętu firmowego' => '',
            'Id sprzętu: ' => $equipment->id,
            'model' => $request->model,
            'serial_code' => $request->serial_code,
            'description' => $request->description,
            'power_cable' => $request->power_cable,
            'user_id' => $request->user_id,
            'department_info_id' => $request->department_info_id,
            'department_info_id' => $request->department_info_id,
            'laptop_ram' => $request->laptop_ram,
            'laptop_hard_drive' => $request->laptop_hard_drive,
            'phone_box' => $request->phone_box,
            'tablet_modem' => $request->tablet_modem,
            'sim_number_phone' => $request->sim_number_phone,
            'sim_type' => $request->sim_type,
            'sim_pin' => $request->sim_pin,
            'sim_puk' => $request->sim_puk,
            'sim_net' => $request->sim_net,
            'signal_cable' => $request->signal_cable,
            'imei' => $request->imei,
            'tablet_modem' => $request->tablet_modem,
            'deleted' => $request->deleted
        ];

        new ActivityRecorder(6, $data);

        $equipment = Equipments::find($id);
        $users = User::all();
        $department_info = Department_info::all();

        Session::flash('message_ok', "Zmiany zapisano!");
        return Redirect::back();
    }
}
