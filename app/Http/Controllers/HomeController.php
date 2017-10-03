<?php

namespace App\Http\Controllers;

use App\Work_Hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $actuall_date;
    private $actuall_hour;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->actuall_date = date("Y-m-d");
        $this->actuall_hour = date("H:i:s");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.index');
    }

    public function startWork(Request $request)
    {
        $work_hour = new Work_Hour;
        $work_hour->status = 1;
        $work_hour->accept_sec = 0;
        $work_hour->success = 0;
        $work_hour->date = $this->actuall_date;
        $work_hour->id_user = Auth::id();
        $work_hour->save();
    }

    public function admin()
    {
        return view('admin');
    }
}
