<?php

namespace App\Http\Controllers;

use App\Work_Hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkHoursController extends Controller
{

    public function __construct()
    {
        $this->actuall_date = date("Y-m-d");
        $this->actuall_hour = date("H:i:s");
    }

    public function acceptHour()
    {
        return view('workhours.acceptHour');
    }



    public function datatableAcceptHour(Request $request)
    {
        if($request->ajax()) {

            $query = Work_Hour::select('id', 'click_start');
            return datatables($query)->make(true);

        }
    }



    public function registerHour(Request $request)
    {
        if($request->ajax())
        {
            $time_register_start = $request->register_start;
            $time_register_stop = $request->register_stop;
            Work_Hour::where('id_user', Auth::id())
                ->where('date',$this->actuall_date)
                ->update(['register_start' => $time_register_start,'register_stop' => $time_register_stop]);

            $request->session()->flash('message', 'New customer added successfully.');
            $request->session()->flash('message-type', 'success');
            return response()->json(['status'=>'Hooray']);
        }
    }
    public function addHour()
    {
        return view('workhours.addHour');
    }
}
