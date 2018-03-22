<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoachingController extends Controller
{
    public function progress_tableGET(){
        return view('coaching.progress_table');
    }
}
