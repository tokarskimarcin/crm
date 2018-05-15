<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrmRouteController extends Controller
{
    public function index()
    {
        return view('crmRoute.index');
    }
}
