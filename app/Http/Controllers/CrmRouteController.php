<?php

namespace App\Http\Controllers;

use App\AuditCriterions;
use App\AuditHeaders;
use App\Cities;
use App\Department_info;
use App\Hotel;
use App\Route;
use App\RouteInfo;
use App\Voivodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Session;

class CrmRouteController extends Controller
{
    public function index()
    {
        $departments = Department_info::all();
        return view('crmRoute.index')->with('departments', $departments);
    }

    /**
     * @return $this method returns view addNewRoute with data about all voivodes
     */
    public function addNewRouteGet() {
        $voivodes = Voivodes::all();

        return view('crmRoute.addNewRoute')->with('voivodes', $voivodes);
    }

    /**
     * This method saves new route to database
     */
    public function addNewRoutePost(Request $request) {
        $voivode = $request->voivode;
        $city = $request->city;

        $voivodeIdArr = explode(',', $voivode);
        $cityIdArr = explode(',', $city);

        $cityNamesArr = array();

        foreach($cityIdArr as $city) {
            $givenCity = Cities::where('id', '=', $city)->first();
            $name = $givenCity->name;
            array_push($cityNamesArr,$name);
        }

        $nameOfRoute = '';
        foreach($cityNamesArr as $name) {
            $nameOfRoute .= $name . '-';
        }
        $nameOfRoute = trim($nameOfRoute, '-');

        $newRoute = new Route();
        $newRoute->status = 1; // 1 - aktywne dane, 0 - usunięte dane
        $newRoute->name = $nameOfRoute;
        $newRoute->save();

        foreach($voivodeIdArr as $voivodekey => $voivode) {
            foreach($cityIdArr as $citykey => $city) {
                if($voivodekey == $citykey) {
                    $newRouteInfo = new RouteInfo();
                    $newRouteInfo->routes_id = $newRoute->id;
                    $newRouteInfo->voivodeship_id = $voivode;
                    $newRouteInfo->city_id = $city;
                    $newRouteInfo->status = 1; // 1 - aktywne dane, 0 - usunięte dane
                    $newRouteInfo->save();
                }
            }

        }

        $request->session()->flash('adnotation', 'Trasa została dodana pomyślnie!');

        return Redirect::to('/route/' . $newRoute->id);

    }

    /**
     * This method saves changes related to given route.
     */
    public function editRoute(Request $request)
    {
        if(isset($request->toDelete)) { // usuwamy
            $oldRoute = Route::find($request->route_id);
            $oldRoute->status = 0; // status 0 - usunięty, 1 - aktywny
            $oldRoute->save();

            $oldRoutesInfo = RouteInfo::where('routes_id', '=', $request->route_id)->get();
            foreach($oldRoutesInfo as $oldInfo) {
                $oldInfo->status = 0; // status 0 - usunięty, 1 - aktywny
                $oldInfo->save();
            }

            return Redirect::to('/showRoutes');
        }
        else { //edytujemy
            $voivode = $request->voivode;
            $city = $request->city;

            $voivodeIdArr = explode(',', $voivode);
            $cityIdArr = explode(',', $city);

            $cityNamesArr = array();

            foreach ($cityIdArr as $city) {
                $givenCity = Cities::where('id', '=', $city)->first();
                $name = $givenCity->name;
                array_push($cityNamesArr, $name);
            }

            $nameOfRoute = '';
            foreach ($cityNamesArr as $name) {
                $nameOfRoute .= $name . '-';
            }
            $nameOfRoute = trim($nameOfRoute, '-');

            $thisRoute = Route::find($request->route_id);
            $thisRoute->name = $nameOfRoute;
            $thisRoute->save();

            $oldRoute = RouteInfo::where([
                ['routes_id', '=', $request->route_id],
                ['status', '=', 1]
            ])
                ->get();

            foreach ($oldRoute as $item) {
                $item->status = '0';
                $item->save();
            }

            foreach ($voivodeIdArr as $voivodekey => $voivode) {
                foreach ($cityIdArr as $citykey => $city) {
                    if ($voivodekey == $citykey) {
                        $newRouteInfo = new RouteInfo();
                        $newRouteInfo->routes_id = $request->route_id;
                        $newRouteInfo->voivodeship_id = $voivode;
                        $newRouteInfo->city_id = $city;
                        $newRouteInfo->status = 1; // 1 - aktywne dane, 0 - usunięte dane
                        $newRouteInfo->save();
                    }
                }
            }
            return Redirect::to('/route/' . $request->route_id);
        }

    }

    /**
     * @param id of voivode
     * @return list of cities in each voivode
     */
    public function addNewRouteAjax(Request $request) {
        $cityId = $request->id;
        $all_cities = Cities::where('voivodeship_id', '=', $cityId)->get();
        return $all_cities;
    }

    /**
     * @return Return view "show routes"
     */
    public function showRoutesGet() {

        return view('crmRoute.showRoutes');
    }

    /**
     * @return This method sends data about all routes to datatable in showRoutes view
     */
    public function showRoutesAjax(Request $request) {
        $routes = Route::where('status', '=', 1)->get();

        return datatables($routes)->make(true);
    }

    /**
     * @param $id of given route
     * @return view with data of given route
     */
    public function routeGet($id) {
        $routeInfo = RouteInfo::where([
            ['routes_id', '=', $id],
            ['status', '=', 1]
        ])->get();
        $voivodes = Voivodes::all();
        $route = Route::where('id', '=', $id)->first();
        $editFlag = true;

        return view('crmRoute.editRoute')
            ->with('routeInfo', $routeInfo)
            ->with('voivodes', $voivodes)
            ->with('editFlag', $editFlag)
            ->with('route', $route);
    }

    /**
     * This method return view addNewHotel with data about voivodes
     */
    public function addNewHotelGet() {
        $voivodes = Voivodes::all();
        return view('crmRoute.addNewHotel')->with('voivodes', $voivodes);
    }

    /**
     * This method saves newly added hotel to database
     */
    public function addNewHotelPost(Request $request) {
        $hotelName = $request->name;
        $voivodeId = $request->voivode;
        $cityId = $request->city;
        $price = $request->price;
        $comment = $request->comment;
        $status = 1; // 1 - aktywne dane, 0 - usunięte dane

        $newHotel = new Hotel();
        $newHotel->name = $hotelName;
        $newHotel->voivode_id = $voivodeId;
        $newHotel->city_id = $cityId;
        $newHotel->price = $price;
        $newHotel->comment = $comment;
        $newHotel->status = $status;
        $newHotel->save();

        $request->session()->flash('adnotation', 'Hotel został dodany pomyślnie!');

        return Redirect::back();

    }

    /**
     * This method returns view showHotels
     */
    public function showHotelsGet() {
        $voivodes = Voivodes::all()->sortByDesc('name');
        $cities = Cities::all()->sortBy('name');
        return view('crmRoute.showHotels')
            ->with('voivodes', $voivodes)
            ->with('cities', $cities);
    }

    /**
     * This method sends data to ajax request about all hotels for view showHotels
     */
    public function showHotelsAjax(Request $request) {
        $voivodeIdArr = $request->voivode;
        $cityIdArr = $request->city;

        if(is_null($voivodeIdArr) && is_null($cityIdArr)) {
            $hotels = Hotel::where('status', '=', 1)->get();
        }
        else if(!is_null($voivodeIdArr) != 0 && is_null($cityIdArr)) {
            $hotels = Hotel::where('status', '=', 1)
                ->whereIn('voivode_id', $voivodeIdArr)
                ->get();
        }
        else if(is_null($voivodeIdArr) && !is_null($cityIdArr) != 0) {
            $hotels = Hotel::where('status', '=', 1)
                ->whereIn('city_id', $cityIdArr)
                ->get();
        }
        else {
            $hotels = Hotel::where('status', '=', 1)->get();
        }
//        if(($voivodeId == 0 || $voivodeId == "null") && ($cityId == 0 || $cityId == "null")) {
//            $hotels = Hotel::where('status', '=', 1)->get();
//        }
//        else if(($cityId == 0 || $cityId == "null") && ($voivodeId != 0 || $voivodeId != "null")){
//            $hotels = Hotel::where('status', '=', 1)
//                ->where('voivode_id', '=', $voivodeId)
//                ->get();
//        }
//
//        else if(($voivodeId == 0 || $voivodeId == "null") && ($cityId != 0 || $cityId != "null")) {
//            $hotels = Hotel::where('status', '=', 1)
//                ->where('city_id', '=', $cityId)
//                ->get();
//        }

        $voivodes = Voivodes::all();
        $cities = Cities::all();
        $hotelArr = array();
        foreach($hotels as $hotel) {
            $hotelsExtended = new \stdClass();
            $hotelsExtended->id = $hotel->id;
            $hotelsExtended->name = $hotel->name;
            $hotelsExtended->voivode_id = $hotel->voivode_id;
            $hotelsExtended->city_id = $hotel->city_id;
            foreach($voivodes as $voivode) {
                if($hotel->voivode_id == $voivode->id) {
                    $hotelsExtended->voivodeName = $voivode->name;
                }
            }
            foreach($cities as $city) {
                if($hotel->city_id == $city->id) {
                    $hotelsExtended->cityName = $city->name;
                }
            }
            array_push($hotelArr,$hotelsExtended);
        }
        $colection = collect($hotelArr);


        return datatables($colection)->make(true);
    }

    /**
     * This method returns view hotel with data about given hotel
     */
    public function hotelGet($id) {
        $hotel = Hotel::find($id);
        $voivodes = Voivodes::all();
        $cities = Cities::all();
        $idOfHotel = $id;

        return view('crmRoute.hotel')
            ->with('hotel', $hotel)
            ->with('voivodes', $voivodes)
            ->with('cities', $cities)
            ->with('id', $id);
    }

    /**
     * This method saves or deletes given hotel
     */
    public function hotelPost(Request $request, $id) {
        $usun = false;
        if(!is_null($request->usun)) {
            $usun = true;
        }
        if($usun) {
            $hotel = Hotel::find($id);
            $hotel->status = 0; // status 0 - usuniety, status = 1 - aktywny
            $hotel->save();
        }
        else {
            $hotel = Hotel::find($id);
            $hotel->name = $request->name;
            $hotel->voivode_id = $request->voivode;
            $hotel->city_id = $request->city;
            $hotel->price = $request->price;
            $hotel->comment = $request->comment;
            $hotel->save();
        }
        return Redirect::to('/hotel/'. $id);
    }

    public function addNewClientGet() {


        return view('crmRoute.addNewClient');
    }

    public function addNewClientPost(Request $request) {

    }
}
