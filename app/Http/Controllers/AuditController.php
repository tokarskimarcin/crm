<?php

namespace App\Http\Controllers;

use App\Department_info;
use Illuminate\Http\Request;
use Session;
use App\User;

class AuditController extends Controller
{
    //

    public function auditMethodGet() {
        $dept = Department_info::all();
        $trainers = User::where('user_type_id', '=', '4')->OrderBy('first_name')->get();

        return view('audit.addAudit')->with('trainers', $trainers)->with('dept', $dept);
    }

    public function ajax(Request $request) {
        $trainers = User::where('user_type_id', '=', '4')->where('department_info_id', '=', $request->wybranaOpcja)->where('status_work', '=', '1')->get();
        return $trainers;
    }
}
