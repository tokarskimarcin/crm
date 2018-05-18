<?php

namespace App\Http\Controllers;

use App\AuditCriterions;
use App\AuditHeaders;
use App\Department_info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;

class CrmRouteController extends Controller
{
    public function index()
    {
        $departments = Department_info::all();
        return view('crmRoute.index')->with('departments', $departments);
    }

    public function addNewRouteGet() {
        $headers = AuditHeaders::all();

        return view('crmRoute.addNewRoute')->with('headers', $headers);
    }

    public function addNewRoutePost(Request $request) {
        $voivode = $request->voivode;
        $city = $request->city;

        $voivodeArr = explode(',', $voivode);
        $cityArr = explode(',', $city);

        $request->session()->flash('adnotation', 'Trasa została dodana pomyślnie!');

        return Redirect::to('/addNewRoute');

    }

    /**
     * @param id of voivode
     * @return list of cities in each voivode
     */
    public function addNewRouteAjax(Request $request) {
        $cityId = $request->id;
        $all_criterions = AuditCriterions::where('id', '=', $cityId)->get();
        return $all_criterions;
    }

    /**
     * @return Return view show routes
     */
    public function showRoutesGet() {

        return view('crmRoute.showRoutes');
    }

    /**
     * @return This method sends data about all routes to datatable in showRoutes view
     */
    public function showRoutesAjax(Request $request) {
        $headers = AuditHeaders::all();

        return datatables($headers)->make(true);
    }

    public function routeGet($id) {
        $headers = AuditHeaders::all();

        return view('crmRoute.editRoute')->with('headers', $headers);
    }
}
