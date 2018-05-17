<?php

namespace App\Http\Controllers;

use App\Department_info;
use Illuminate\Http\Request;

class CrmRouteController extends Controller
{
    public function index()
    {
        $departments = Department_info::all();
        return view('crmRoute.index')->with('departments', $departments);
    }
}
