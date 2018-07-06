<?php

namespace App\Http\Controllers;

use App\ActivityRecorder;
use App\AuditCriterions;
use App\AuditHeaders;
use App\Cities;
use App\Clients;
use App\ClientRoute;
use App\ClientRouteInfo;
use App\Department_info;
use App\Hotel;
use App\PbxCrmInfo;
use App\Route;
use App\RouteInfo;
use App\Voivodes;
use DateTime;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use function MongoDB\BSON\toJSON;
use Session;
use Symfony\Component\HttpKernel\Client;

class CrmRouteController extends Controller
{
    public function index()
    {
        $departments = Department_info::all();
        $today = date('Y-m-d');
        $today .= '';
        $voivodes = Voivodes::all();
        $year = date('Y',strtotime("this year"));
        $numberOfLastYearsWeek = date('W',mktime(0, 0, 0, 12, 27, $year));
        return view('crmRoute.index')
            ->with('departments', $departments)
            ->with('voivodes', $voivodes)
            ->with('lastWeek', $numberOfLastYearsWeek)
            ->with('today', $today);
    }

    /**
     * This method saves new routes connected with client
     */
    public function indexPost(Request $request) {
        //Get values from form elements
        $voivode = $request->voivode;
        $city = $request->city;
        $hour = $request->hour;
        $date = $request->date;
        $clientType = $request->clientType; // 1 - badania, 2 - wysyłka
        $clientIdNotTrimmed = $request->clientId;

        //explode values into arrays
        $voivodeArr = explode(',', $voivode);
        $cityArr = explode(',', $city);
        $hourArr = explode(',',$hour);
        $clientId = explode('_',$clientIdNotTrimmed)[1];
        $dateArr = explode(',',$date);

        $loggedUser = Auth::user();

        //New insertion into ClientRoute table
        $clientRoute = new ClientRoute();
        $clientRoute->client_id = $clientId;
        $clientRoute->user_id = $loggedUser->id;
        $clientRoute->status = 0;
        $clientRoute->type = $clientType; // 1 - badania, 2 - wysyłka
        $clientRoute->save();

        //New insertions into ClientRouteInfo table
        for($i = 0; $i < count($voivodeArr); $i++) {
            for($j = 1; $j <= $hourArr[$i] ; $j++) { // for example if user type 2 hours, method will insert 2 insertions with given row.
                $clientRouteInfo = new ClientRouteInfo();
                $clientRouteInfo->client_route_id = $clientRoute->id;
                $clientRouteInfo->city_id = $cityArr[$i];
                $clientRouteInfo->voivode_id = $voivodeArr[$i];
                $clientRouteInfo->date = $dateArr[$i];
                $clientRouteInfo->verification = 0; // 0 - not set, 1 - set
                $day = substr($dateArr[$i],8,2);

                $month = substr($dateArr[$i],5,2);

                $year = substr($dateArr[$i], 0,4);

                $date = mktime(0, 0, 0, $month, $day, $year);
                $weekOfYear = date('W',$date);
                $clientRouteInfo->weekOfYear = $weekOfYear;
                $clientRouteInfo->save();
            }
        }

        new ActivityRecorder(12,'ClientRouteId: '. $clientRoute->id,196,1);
        $request->session()->flash('adnotation', 'Trasa została pomyślnie przypisana dla klienta');

        return Redirect::back();

    }


    /**
     * This method saves new routes connected with client
     */
    public function indexEditPost(Request $request) {
//        dd($request);
        //Get values from form elements
        $voivode = $request->voivode;
        $city = $request->city;
        $hour = $request->hour;
        $date = $request->date;
        $type = $request->type;
        $clientIdNotTrimmed = $request->clientId;

        //explode values into arrays
        $voivodeArr = explode(',', $voivode);
        $cityArr = explode(',', $city);
        $hourArr = explode(',',$hour);
        $clientId = explode('_',$clientIdNotTrimmed)[1];
        $dateArr = explode(',',$date);

        $loggedUser = Auth::user();

//        dd($hourArr);
        //New insertion into ClientRoute table
        $clientRoute = ClientRoute::find($request->route_id);
        $clientRoute->client_id = $clientId;
        $clientRoute->user_id = $loggedUser->id;
        $clientRoute->status = 0;
        $clientRoute->type = $type;
        $clientRoute->save();

        ClientRouteInfo::where('client_route_id','=',$request->route_id)->delete();
        //New insertions into ClientRouteInfo table
        for($i = 0; $i < count($voivodeArr); $i++) {
            for($j = 1; $j <= $hourArr[$i] ; $j++) { // for example if user type 2 hours, method will insert 2 insertions with given row.
                $clientRouteInfo = new ClientRouteInfo();
                $clientRouteInfo->client_route_id = $clientRoute->id;
                $clientRouteInfo->city_id = $cityArr[$i];
                $clientRouteInfo->voivode_id = $voivodeArr[$i];
                $clientRouteInfo->date = $dateArr[$i];
                $clientRouteInfo->verification = 0; // 0 - not set, 1 - set
                $day = substr($dateArr[$i],8,2);

                $month = substr($dateArr[$i],5,2);

                $year = substr($dateArr[$i], 0,4);

                $date = mktime(0, 0, 0, $month, $day, $year);
                $weekOfYear = date('W',$date);
                $clientRouteInfo->weekOfYear = $weekOfYear;
                $clientRouteInfo->save();
            }
        }
        $request->session()->flash('adnotation', 'Trasa została pomyślnie przypisana dla klienta');

        new ActivityRecorder(12,'ClientRouteId: '. $request->route_id, 206,2);

        return Redirect::back();

    }

    /**
     * This method shows specific route
     */
    public function specificRouteGet($id, $onlyResult = null) {
        $clientRouteInfo = ClientRouteInfo::select('client_route_info.limits as limits', 'client_route_info.department_info_id as department_info_id', 'client_route_info.id as id', 'city.name as cityName', 'voivodeship.name as voivodeName', 'client_route.id as client_route_id', 'city.id as city_id', 'voivodeship.id as voivode_id', 'client_route_info.date as date', 'client_route_info.hotel_id as hotel_id', 'client_route_info.hour as hour', 'client_route.client_id as client_id', 'client_route_info.weekOfYear as weekOfYear')
            ->join('client_route', 'client_route.id', '=', 'client_route_info.client_route_id')
            ->join('city', 'city.id', '=', 'client_route_info.city_id')
            ->join('voivodeship', 'voivodeship.id', '=', 'client_route_info.voivode_id')
            ->where('client_route_id', '=', $id)
            ->get();

        $clients = Clients::all();
        $hotels = Hotel::whereIn('status', [1,0]);

        $clientRouteInfoExtended = array();
        $insideArr = array();
        $cityId = null;
        $flag = 0; //indices whether $insideArr push into $clientRouteInfoExtended 1 - push, 0 - don't push
        $iterator = 0; //It count loops of foreach
        $iteratorFinish = count($clientRouteInfo); // indices when condition inside foreach should push array into $clientRouteInfoExtended array.
        $clientName = null;

        foreach($clientRouteInfo as $info) {
            if($cityId == null) {
                $flag = 0;
                $cityId = $info->city_id;
            }
            else if($info->city_id == $cityId) {
                $flag = 0;
                $cityId = $info->city_id;
            }
            else {
                array_push($clientRouteInfoExtended, $insideArr);
                $insideArr = [];
                $flag = 1;
                $cityId = $info->city_id;
            }

            if($clientName == null) {
                $clientRId = $info->client_id;
                $clientName = Clients::find($clientRId)->name;
            }

            $stdClass = new \stdClass();

            foreach($clients as $client) {
                if($info->client_route_id == $client->id) {
                    $stdClass->clientName = $client->name;
                }
            }

            $stdClass->id = $info->id;
            $stdClass->client_route_id = $info->client_route_id;
            $stdClass->city_id = $info->city_id;
            $stdClass->cityName = $info->cityName;
            $stdClass->voivode_id = $info->voivode_id;
            $stdClass->voivodeName = $info->voivodeName;
            $stdClass->date = $info->date;
            $stdClass->hotel_id = $info->hotel_id;
            $stdClass->hotel_info = Hotel::find($info->hotel_id);
            $stdClass->hour = $info->hour;
            $stdClass->limit = $info->limits == null ? 0 : $info->limits;
            $stdClass->department_info_id = $info->department_info_id;
            $stdClass->weekNumber = $info->weekOfYear;
            array_push($insideArr, $stdClass);
            if($flag == 1) {
                $flag = 0;
            }
            if($iterator == ($iteratorFinish - 1)) {
                array_push($clientRouteInfoExtended, $insideArr);
            }
            $iterator++;
        }

        $clientRouteInfo = collect($clientRouteInfoExtended);

        $clientRouteInfo->each(function ($city, $key) use ($hotels) {
            foreach($city as $showHour){
                $hotels->each(function ($hotel, $key) use ($showHour) {
                    if($hotel->id == $showHour->hotel_id){
                        $showHour->hotel_page = intval(floor($key/10));
                    }
                });
            }
        });

        if($onlyResult == null)
            return view('crmRoute.specificInfo')
                ->with('clientRouteInfo', $clientRouteInfoExtended)
                //->with('hotels', $hotels)
                ->with('clientName', $clientName);
        else
            return $clientRouteInfo->sortByDesc('date');
    }

    /**
     * This method shows specific route
     */
    public function specificRouteEditGet($id) {
        $clients = Clients::all();
        $cities = Cities::all();
        $voivodes = Voivodes::all();
        $departments = Department_info::all(); //niezbędne

        $clientRouteInfo = ClientRouteInfo::select('client_route_info.id', 'voivodeship.name as voivode', 'client_route_info.voivode_id as voivode_id','city.name as city', 'client_route_info.city_id as city_id', 'client_route.client_id as client_id', 'client_route_info.client_route_id as client_route_id', 'client_route_info.date as date', 'client_route_info.hotel_id as hotel_id', 'client_route_info.hour as hour', 'client_route.type as type')
            ->join('city', 'city.id', '=', 'client_route_info.city_id')
            ->join('voivodeship', 'voivodeship.id', '=', 'client_route_info.voivode_id')
            ->join('client_route', 'client_route.id', '=', 'client_route_info.client_route_id')
            ->where('client_route_id', '=', $id)
            ->get();

        $clientRouteInfoExtended = array();
        $insideArr = array();
        $cityId = null;
        $flag = 0; //indices whether $insideArr push into $clientRouteInfoExtended 1 - push, 0 - don't push
        $iterator = 0; //It count loops of foreach
        $iteratorFinish = count($clientRouteInfo); // indices when condition inside foreach should push array into $clientRouteInfoExtended array.
        $clientName = null;
        $clientType = null;

        foreach($clientRouteInfo as $info) {
            if($iterator == 0) {
                $clientType = $info->type;
            }

            if($cityId == null) {
                $flag = 0;
                $cityId = $info->city_id;
            }
            else if($info->city_id == $cityId) {
                $flag = 0;
                $cityId = $info->city_id;
            }
            else {
                array_push($clientRouteInfoExtended, $insideArr);
                $insideArr = [];
                $flag = 1;
                $cityId = $info->city_id;
            }

            $clientRId = $info->client_id;

            $stdClass = new \stdClass();

            foreach($clients as $client) {
                if($info->client_id == $client->id) {
                    $stdClass->clientName = $client->name;
                }
            }

            $stdClass->client_route_id = $info->client_route_id;
            $stdClass->city_id = $info->city_id;
            $stdClass->voivode_id = $info->voivode_id;
            $stdClass->date = $info->date;
            $stdClass->hotel_id = $info->hotel_id;
            $stdClass->hour = $info->hour;

            array_push($insideArr, $stdClass);
            if($flag == 1) {
                $flag = 0;
            }
            if($iterator == ($iteratorFinish - 1)) {
                array_push($clientRouteInfoExtended, $insideArr);
            }
            $iterator++;
        }

        $clientRouteInfo = collect($clientRouteInfoExtended);

        $today = date('Y-m-d');
        $today .= '';
        $year = date('Y',strtotime("this year"));
        $numberOfLastYearsWeek = date('W',mktime(0, 0, 0, 12, 27, $year));

        $clientRouteInfo = $clientRouteInfo->sortByDesc('date');
        $clientRouteInfoAll = ClientRouteInfo::select('date','city_id')->get();
        $clientRouteInfo->map(function($item) use($cities,$clientRouteInfoAll) {
            $cityObject = $cities->where('id','=',$item[0]->city_id)->first();
            $item[0]->cities = $this::findCityByDistance($cityObject, '2000-01-01',$clientRouteInfoAll);
            return $item;
        });

        return view('crmRoute.editSpecificRoute')
            ->with('departments', $departments)
            ->with('voivodes', $voivodes)
            ->with('lastWeek', $numberOfLastYearsWeek)
            ->with('today', $today)
            ->with('clientRouteInfo',$clientRouteInfo)
            ->with('clientRId', $clientRId)
            ->with('routeId',$id)
            ->with('clientType', $clientType);
    }


    /**
     * This method saves changes about specific route
     */
    public function specificRoutePost(Request $request) {
        $all_data = json_decode($request->JSONData); //we obtain 2 dimensional array
        $clientRouteInfoIds = 'clientRouteInfoIds: ';
        foreach($all_data as $city) {
            $clientRouteInfo = ClientRouteInfo::where([
                ['city_id', '=', $city->cityId],
                ['voivode_id', '=', $city->voivodeId],
                ['client_route_id', '=', $city->clientRouteId]
            ])
                ->get();
            $numberOfRecords = count($clientRouteInfo);
            $iterator = 0;
            foreach($clientRouteInfo as $item) {
                if($city->timeHotelArr[$iterator]->time == '') {
                    $item->hour = null;
                }
                else {
                    $item->hour = $city->timeHotelArr[$iterator]->time;
                }

                $item->hotel_id = $city->timeHotelArr[$iterator]->hotelId;
                $item->department_info_id = null; //At this point nobody choose it's value, can't be 0 because
                $item->save();
                $iterator++;
                $clientRouteInfoIds .= $item->id . ', ';
            }
        }
        new ActivityRecorder(12,$clientRouteInfoIds, 206,2);

        return $all_data;
    }

    /**
     * Return ready route with info
     * @param Request $request
     */
    public function getReadyRoute(Request $request){
        $data = $this::specificRouteGet($request->route_id,true);
        return $data;
    }

    /**
     * Save Campaign Option (department's and limit)
     * @param Request $request
     */
    public function saveCampaignOption(Request $request){
        if($request->ajax()){
            $clientRouteIds = 'ClientRouteInfoIds: ';
            $objectOfChange = $request->objectOfChange;
            foreach ($objectOfChange as $item){
                $clientRoadInfo = ClientRouteInfo::find($item['id']);
                $clientRoadInfo->limits = $item['limit'];
                $clientRoadInfo->department_info_id = $item['department_info_id'];
                $clientRoadInfo->save();
                $clientRouteIds .= $item['id'] .', ';
            }
            new ActivityRecorder(12,$clientRouteIds,200,2);

            return 200;
        }else
            return 500;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getSelectedRoute(Request $request) {
        $idNotTrimmed = $request->route_id;
        $posOfId = strpos($idNotTrimmed,'_');
        $id = substr($idNotTrimmed, $posOfId + 1);
        $voivodes = Voivodes::all();
        $cities = Cities::all();
        $route = RouteInfo::where([
            ['routes_id', '=', $id],
            ['status', '=', 1]
        ])
            ->get();

        $routeExtendedArr = array();
        foreach($route as $item) {
            $routeExtended = new \stdClass();
            $routeExtended->id = $item->id;
            $routeExtended->routes_id = $item->routes_id;
            $routeExtended->voivodeship_id = $item->voivodeship_id;
            $routeExtended->city_id = $item->city_id;
            $routeExtended->status = $item->status;
            foreach($voivodes as $voivode) {
                if($item->voivodeship_id == $voivode->id) {
                    $routeExtended->voivode_name = $voivode->name;
                }
            }
            foreach($cities as $city) {
                if($item->city_id == $city->id) {
                    $routeExtended->city_name = $city->name;
                }
            }
            array_push($routeExtendedArr, $routeExtended);
        }

        $routeExt = collect($routeExtendedArr);
        return $routeExt;
    }

    /**
     * @return $this method sends to server view showClientRoutes with data about weeks in given year.
     */
    public function showClientRoutesGet() {
        $departments = Department_info::getAllInfoAboutDepartment()
        ->whereIn('id_dep_type',[2]);
        $year = date('Y',strtotime("this year"));
        $numberOfThisYearsWeek = date('W',mktime(0, 0, 0, 12, 30, $year));
        return view('crmRoute.showClientRoutes')
            ->with('lastWeek', $numberOfThisYearsWeek)
            ->with('departments', $departments)
            ->with('year', $year);
    }

    /**
     * @param year
     * @return Number of Weeks in given year
     */
    public function getYearWeeksAjax(Request $request) {
        $year = $request->year;
        $numberOfLastYearsWeek = date('W',mktime(0, 0, 0, 12, 29, $year));
        return $numberOfLastYearsWeek;
    }

    public function showClientRoutesAjax(Request $request) {
        $clients = Clients::all();
        $client_route_info = DB::table('client_route')
            ->select(DB::raw('
                client.id as id,
                client.name as name'))
            ->join('client', 'client.id', '=', 'client_route.client_id')
            ->distinct()
            ->get();


//        $newEmptyClient = new Clients();
//        $newEmptyClient->
        return datatables($client_route_info)->make(true);
    }

    /**
     * @param showAllClients(true/false/null), showOnlyAssigned(true/false/null), id(number/null), selectedWeek(number), year(number), typ(number), state(number)
     * This method return data about all client routes to datatable in showClientRoutes
     */
    public function showClientRoutesInfoAjax(Request $request) {
        $clientId = $request->id;
        $clientId = $clientId == '-1' ? '%' : $clientId;

        $showOnlyAssigned = $request->showOnlyAssigned;
        if($request->year > 0) {
            $year = $request->year;
        }
        else {
            $year = date('Y',strtotime("this year"));
        }

        $selectedWeek = $request->selectedWeek;
        $selectedWeek = $selectedWeek == '0' ? '%' : $selectedWeek;

        $typ = $request->typ;
        $typ = $typ == '0' ? '%' : $typ;

        $state = $request->state;
        $state = $state == '-1' ? '%' : $state;

        //SELECT weekOfYear, client.name as clientName, city.name as cityName, date, client_route.status FROM client_route_info
        //  JOIN client_route ON client_route.id = client_route_id
        //  JOIN client ON client.id = client_route.client_id
        //  JOIN city ON city.id = city_id

        $client_route_info = DB::table('client_route_info')
            ->select('client_route_info.id','weekOfYear','hour', 'hotel_id', 'client.name as clientName', 'city.name as cityName', 'date', 'client_route.status', 'client_route.type', 'client_route_id')
            ->join('client_route' ,'client_route.id','=','client_route_id')
            ->join('client' ,'client.id','=','client_route.client_id')
            ->join('city' ,'city.id','=', 'city_id')
            ->where('client_route.client_id','like',$clientId)
            ->where('date', 'like', $year . '%')
            ->where('weekOfYear', 'like', $selectedWeek)
            ->where('client_route.type', 'like', $typ)
            ->where('client_route.status', 'like', $state);

        $client_route_info =  $client_route_info->get();

        $client_route_ids = $client_route_info->pluck('client_route_id')->unique();

        $fullArray = [];
        foreach($client_route_ids as $client_route_id){
            $grouped_by_day_client_routes= [];
            foreach($client_route_info->where('client_route_id','=',$client_route_id)->sortBy('date')->groupBy('date') as $client_route_day){
                array_push($grouped_by_day_client_routes, $client_route_day);
            }
            $client_routes = [];
            foreach($grouped_by_day_client_routes as $client_route_day){
                foreach ($client_route_day->sortBy('hour') as $client_route){
                    array_push($client_routes, $client_route);
                }
            }
            $route_name = $client_routes[0]->cityName;
            $hourOrHotelUnassigned = $client_routes[0]->hour == null || $client_routes[0]->hotel_id == null ? false : true;
            for($i = 1; $i < count($client_routes);$i++){
                if($client_routes[$i]->cityName !== $client_routes[$i-1]->cityName)
                    if($client_routes[$i]->date!=$client_routes[$i-1]->date){
                        $route_name .= ' | '.$client_routes[$i]->cityName;
                    }else
                        $route_name .= '+'.$client_routes[$i]->cityName;
                if($hourOrHotelUnassigned && ($client_routes[$i]->hotel_id == null || $client_routes[$i]->hour == null) )
                    $hourOrHotelUnassigned = false;
            }
            $client_routes[0]->hotelOrHour = $hourOrHotelUnassigned;
            $client_routes[0]->route_name = $route_name;
            array_push($fullArray, $client_routes[0]);
        }
        $full_clients_routes = collect($fullArray);

        if($showOnlyAssigned == 'true'){
            $full_clients_routes = $full_clients_routes->where('hotelOrHour','=', false);
        }

        return datatables($full_clients_routes)->make(true);
    }

    /**
     * @param $collection of client_route_info
     * @return array of client_route_id's that has at least one hotel assigned
     */
    private function getExcludedIds($collection) {
        //now we are checking if every client_route group has at least one hotel assigned
        $alreadyCheckedIds = array(); //here we store all already checked client_route_ids.
        $clientRouteIdArr = array(); //here we store all client_route_ids that has at least one hotel

        foreach($collection as $info) {
            $hasHotel = 0;
            $checkedFlag = false;
            foreach($alreadyCheckedIds as $checked) {
                if($info->client_route_id == $checked) {
                    $checkedFlag = true;
                }
            }
            if($checkedFlag == false) {
                $clientRouteId = $info->client_route_id;
                $allClientRouteIdInsertions = ClientRouteInfo::where('client_route_id', '=', $clientRouteId)->get();

                if($allClientRouteIdInsertions->where('hotel_id', '!=', null)->count() > 0) {
                    $hasHotel++;
                }
                if($hasHotel > 0) {
                    array_push($clientRouteIdArr, $clientRouteId);
                }
                array_push($alreadyCheckedIds, $clientRouteId);
            }

        }

        return $clientRouteIdArr;
    }

    /**
     * @param $clientRouteId, $toDelete(0,1,2)
     * This method changes status of client_route
     */
    public function showClientRoutesStatus(Request $request) {
        $clientRouteId = $request->clientRouteId;
        $toDelete = $request->delete; // 0,1,2 - actual values 0 - not ready, 1 - started, 2 - finished
        $success = 0;
        if($clientRouteId && $toDelete == '0') {
            $clientRoute = ClientRoute::find($clientRouteId);
            $clientRoute->status = 1;
            $clientRoute->save();
            $success = 1;
            new ActivityRecorder(12,'Aktywacja kampanii',200,4);
        }
        else if($clientRouteId && $toDelete == '1') {
            $clientRoute = ClientRoute::find($clientRouteId);
            $clientRoute->status = 2;
            $clientRoute->save();
            $success = 1;
            new ActivityRecorder(12,'Zakończenie kampanii',200,4);
        }
        else if($clientRouteId && $toDelete == '2') {
            $clientRoute = ClientRoute::find($clientRouteId);
            $clientRoute->status = 0;
            $clientRoute->save();
            $success = 1;
            new ActivityRecorder(12,'Zmiana statusu na "nie gotowa"',200,4);
        }

        return $success;
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
            $nameOfRoute .= $name . ' | ';
        }
        $nameOfRoute = trim($nameOfRoute, ' | ');

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
        new ActivityRecorder(12, null,185,1);

        $request->session()->flash('adnotation', 'Trasa została dodana pomyślnie!');

        return Redirect::to('/showRoutes');

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

            $request->session()->flash('adnotation', 'Trasa została usunięta pomyślnie!');

            new ActivityRecorder(12,'Route_id: ' . $request->route_id,188,3);

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
                $nameOfRoute .= $name . ' | ';
            }
            $nameOfRoute = trim($nameOfRoute, ' | ');

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
            $request->session()->flash('adnotation', 'Trasa została edytowana pomyślnie!');

            new ActivityRecorder(12,'Route_id: ' . $request->route_id,188,2);

            return Redirect::to('/showRoutes');
        }

    }

    /**
     * @param id of voivode
     * @return list of cities in each voivode
     */
    public function addNewRouteAjax(Request $request) {
        $voivodeId = $request->id;
        $currentDate = $request->currentDate;
        if($currentDate != 0) {
            $all_cities = Cities::where('voivodeship_id', '=', $voivodeId)->get();
            $properDate = date_create($currentDate);

            //lista miast we wszystkich trasach.
//            $citiesAvailable = DB::table('routes_info')->select(DB::raw('
//            city_id as cityId
//            '))
//                ->pluck('cityId')
//                ->toArray();

            //Rekordy clientRoutesInfo w których były użyte miasta
            $clientRoutesInfoWithUsedCities = ClientRouteInfo::select('city_id', 'date')->get();
            $checkedCities = array(); //In this array we indices cities that should not be in route
            foreach($clientRoutesInfoWithUsedCities as $item) {
                $properDate = date_create($currentDate);
                //wartość karencji dla danego miasta
                $gracePeriod = Cities::find($item->city_id)->grace_period;

                $goodDate = date_create($item->date);
                $dateDifference = date_diff($properDate,$goodDate, true);
                $dateDifference = $dateDifference->format('%a');
                $dateString = $dateDifference . " days";
                $availableAtDate = date_add($properDate,date_interval_create_from_date_string($dateString));
                $availableAtDate = date_format($availableAtDate, "Y-m-d");

                $arrayFlag = false;
                if($dateDifference <= $gracePeriod) {
//                    foreach($checkedCities as $cities) {
//                        if($item->city_id == $cities->city_id) {
//                            $arrayFlag = true;
//                        }
//                    }
                    if($arrayFlag == false) {
                        $cityInfoObject = new \stdClass();
                        $cityInfoObject->city_id = $item->city_id;
                        $cityInfoObject->available_date = $availableAtDate;
                        array_push($checkedCities, $cityInfoObject);
                    }
                }

            }
            $all_cities->map(function($item) use($checkedCities){
                $hourNumber = 0; //This variable counts how many times city was used in grace period
                foreach($checkedCities as $cityRecords) {
                    if ($cityRecords->city_id == $item->id) {
                        $hourNumber++;
                    }
                }

                $blockFlag = false;
                foreach($checkedCities as $blockedCity) {
                    if($blockedCity->city_id == $item->id) {
                        $blockFlag = true;
                        $item->block = 1;
                        $item->available_date = $blockedCity->available_date;
                        if($item->max_hour > $hourNumber) { // limit of hours isn't exceeded
                            $hourDifference = $item->max_hour - $hourNumber;
                            $item->exceeded = 0; // indices that this city is still available for couple of hours
                            $item->used_hours = $hourDifference;
//                            $item->used_hours = $hourNumber;
                        }
                        else {
                            $hourDifference = $hourNumber - $item->max_hour;
                            $item->used_hours = $hourDifference;
                            $item->exceeded = 1; // indices that this city is not available.
                        }

                    }
                }

                if($blockFlag == false) {
                    $item->block = 0;
                    $item->available_date = 0;
                    $item->used_hours = 0;
                    $item->exceeded = 0;
                }

                return $item;
            });
        }
        else {
            $all_cities = Cities::where('voivodeship_id', '=', $voivodeId)->get();
        }
//        $all_cities = Cities::where('voivodeship_id', '=', $voivodeId)->get();
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
        $date = $request->date;
        if($date) {
            $properDate = date_create($date);

            //lista miast we wszystkich trasach.
            $citiesAvailable = DB::table('routes_info')->select(DB::raw('
            city_id as cityId
            '))
                ->pluck('cityId')
                ->toArray();

            //Rekordy clientRoutesInfo w których były użyte miasta
            $clientRoutesInfoWithUsedCities = ClientRouteInfo::select('city_id', 'date')->whereIn('city_id', $citiesAvailable)->get();
            $checkedCities = array(); //In this array we indices cities that should not be in route
            foreach($clientRoutesInfoWithUsedCities as $item) {
                //wartość karencji dla danego miasta
                $gracePeriod = Cities::find($item->city_id)->grace_period;
                $goodDate = date_create($item->date);
                $dateDifference = date_diff($properDate,$goodDate, true);
                $dateDifference = $dateDifference->format('%a');

                $arrayFlag = false;
                if($dateDifference <= $gracePeriod) {
                    foreach($checkedCities as $cities) {
                        if($item->city_id == $cities) {
                            $arrayFlag = true;
                        }
                    }
                    if($arrayFlag == false) {
                        array_push($checkedCities, $item->city_id);
                    }
                }

            }

            $rout = RouteInfo::select('routes_id')->whereIn('city_id', $checkedCities)->where('status', '=', 1)->groupBy('routes_id')->pluck('routes_id')->toArray();
            $routesFiltered = Route::select('id', 'name')->whereNotIn('id', $rout)->where('status', '=', 1)->get();
            $routes = Route::where('status', '=', 1)->get();
            $routes->map(function($item) use($routesFiltered){
                $item->changeColor = 0;

                if(!$routesFiltered->where('id','=',$item->id)->isEmpty()){
                    $item->changeColor = 1;
                }

                return $item;
            });
        }
        else {
            $routes = Route::where('status', '=', 1)->get();
        }

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
        $clientRouteInfo = ClientRouteInfo::all();
        $routeInfo->map(function ($item) use($clientRouteInfo){
            $city = Cities::find($item->city_id);
            $item->cities = $this::findCityByDistance($city,'2000-01-01',$clientRouteInfo);
            return $item;
        });
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

        $hotelName = trim($hotelName);

        $newHotel = new Hotel();
        $newHotel->name = $hotelName;
        $newHotel->voivode_id = $voivodeId;
        $newHotel->city_id = $cityId;
        $newHotel->price = $price;
        $newHotel->comment = $comment;
        $newHotel->status = $status;
        $newHotel->save();

        new ActivityRecorder(12,null,188,1);

        $request->session()->flash('adnotation', 'Hotel został zapisany pomyślnie!');

        return redirect()->route('showHotels');

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
            $hotels = Hotel::whereIn('hotels.status', [1,0]);
        }
        else if(!is_null($voivodeIdArr) != 0 && is_null($cityIdArr)) {
            $hotels = Hotel::whereIn('hotels.status', [1,0])
                ->whereIn('hotels.voivode_id', $voivodeIdArr);
        }
        else if(is_null($voivodeIdArr) && !is_null($cityIdArr) != 0) {
            $hotels = Hotel::whereIn('hotels.status', [1,0])
                ->whereIn('hotels.city_id', $cityIdArr);
        }
        else {
            $hotels = Hotel::whereIn('status', [1,0]);
        }
        $hotels = $hotels->select(DB::raw(
        '
         hotels.id,
         hotels.name,
         hotels.status,
         hotels.street,
         hotels.voivode_id,
         hotels.city_id,
         voivodeship.name as voivodeName,
         city.name as cityName
        '))
            ->join('city','city.id','city_id')
            ->join('voivodeship','voivodeship.id','voivode_id')
            ->get();
        return datatables($hotels)->make(true);
    }

    /**
     * This method returns view hotel with data about given hotel
     */
    public function hotelGet($id) {
        $hotel = Hotel::find($id);
        $voivodes = Voivodes::all();
        $cities = Cities::where('voivodeship_id','=',$hotel->voivode_id)->get();
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
            new ActivityRecorder(12,$id, 191,3);

            $request->session()->flash('adnotation', 'Hotel został usunięty pomyślnie!');
        }
        else {
            $hotel = Hotel::find($id);
            $hotel->name = $request->name;
            $hotel->voivode_id = $request->voivode;
            $hotel->city_id = $request->city;
            $hotel->price = $request->price;
            $hotel->comment = $request->comment;
            $hotel->save();
            new ActivityRecorder(12,$id, 191,2);

            $request->session()->flash('adnotation', 'Hotel został edytowany pomyślnie!');
        }
        return redirect()->route('showHotels');
    }

    /**
     * Panel to managment all settings about city (VIEW)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cityPanel(){
        $allVoivodeship = Voivodes::all();
        $allCity = Cities::all();
        return view('crmRoute.cityPanel')
            ->with('allVoivodeship',$allVoivodeship);
    }

    /**
     *  Return all city with info
     */
    public function getCity(Request $request){
        if($request->ajax()){
            $cities = Cities::select(['city.id','city.name','city.max_hour'
                ,'city.grace_period','city.status','voivodeship.name as vojName',
                'voivodeship.id as vojId',
                'city.zip_code','city.latitude','city.longitude'])
                ->join('voivodeship','voivodeship.id','city.voivodeship_id')
                ->get();
            return datatables($cities)->make(true);
        }
    }

    public function findCityByDistance($city, $currentDate,$clientRoutesInfoWithUsedCities){
        $distance = 100;
        $voievodeshipRound = Cities::select(DB::raw('voivodeship.id as id,voivodeship.name,city.name as city_name,city.id as city_id, city.max_hour as max_hour,
            ( 3959 * acos ( cos ( radians('.$city->latitude.') ) * cos( radians( `latitude` ) )
             * cos( radians( `longitude` ) - radians('.$city->longitude.') ) + sin ( radians('.$city->latitude.') )
              * sin( radians( `latitude` ) ) ) ) * 1.60 AS distance'))
            ->join('voivodeship','voivodeship.id','city.voivodeship_id')
            ->having('distance', '<', $distance)
            ->get();

        //part responsible for grace period
        if($currentDate != 0) {
            $properDate = date_create($currentDate);
            $checkedCities = array(); //In this array we indices cities that should not be in route
            foreach($clientRoutesInfoWithUsedCities as $item) {
                $properDate = date_create($currentDate); //function date_add, changes $properDate variable, so in each loop it has to be reassigned
                //wartość karencji dla danego miasta
                if($item->city_id == $city->id){
                    $gracePeriod = $city->grace_period;
                }else{
                    $gracePeriod = null;
                }
                $goodDate = date_create($item->date);
                $dateDifference = date_diff($properDate,$goodDate, true);
                $dateDifference = $dateDifference->format('%a');
                $dateString = $dateDifference . " days";
                $availableAtDate = date_add($properDate,date_interval_create_from_date_string($dateString));
                $availableAtDate = date_format($availableAtDate, "Y-m-d");
                if($dateDifference <= $gracePeriod) {
                        $cityInfoObject = new \stdClass();
                        $cityInfoObject->city_id = $item->city_id;
                        $cityInfoObject->available_date = $availableAtDate;
                        array_push($checkedCities, $cityInfoObject);
                }
            }
            $voievodeshipRound->map(function($item) use($checkedCities){
                $hourNumber = 0; //This variable counts how many times city was used in grace period
                foreach($checkedCities as $cityRecords) {
                    if ($cityRecords->city_id == $item->city_id) {
                        $hourNumber++;
                    }
                }
                $blockFlag = false;
                foreach($checkedCities as $blockedCity) {
                    if($blockedCity->city_id == $item->city_id) {
                        $blockFlag = true;
                        $item->block = 1;
                        $item->available_date = $blockedCity->available_date;
                        if($item->max_hour > $hourNumber) { // limit of hours isn't exceeded
                            $hourDifference = $item->max_hour - $hourNumber;
                            $item->exceeded = 0; // indices that this city is still available for couple of hours
                            $item->used_hours = $hourDifference;
//                            $item->used_hours = $hourNumber;
                        }
                        else {
                            $hourDifference = $hourNumber - $item->max_hour;
                            $item->used_hours = $hourDifference;
                            $item->exceeded = 1; // indices that this city is not available.
                        }
                    }
                }

                if($blockFlag == false) {
                    $item->block = 0;
                    $item->available_date = 0;
                    $item->used_hours = 0;
                    $item->exceeded = 0;
                }

                return $item;
            });

        }

        return $voievodeshipRound;
    }

    /**
     *  Return Round Voievodeship and city
     */
    public function getVoivodeshipRound(Request $request){
        if($request->ajax()) {
            $cityId = $request->cityId;
            $currentDate = $request->currentDate;
            $city = Cities::where('id', '=', $cityId)->first();
            //part responsible for grace period
            $clientRouteInfoAll = ClientRouteInfo::select('date','city_id')->get();
            $voievodeshipRound = $this::findCityByDistance($city, $currentDate,$clientRouteInfoAll);

            $voievodeshipRound = $voievodeshipRound->groupBy('id');
            $voievodeshipDistinc = array();
            foreach ($voievodeshipRound as $item){
                array_push($voievodeshipDistinc,$item->first());
            }
            $responseArray['voievodeInfo'] = $voievodeshipDistinc;
            $responseArray['cityInfo'] = $voievodeshipRound;
            return $responseArray;
        }
    }


    /**
     * Save new/edit hotel
     * @param Request $request
     */
    public function saveNewHotel(Request $request){
        if($request->ajax()){
            if($request->hotelId == 0) // new Hotel
                $newHotel = new Hotel();
            else    // Edit Hotel
                $newHotel = HOtel::find($request->hotelId);
            $newHotel->city_id     = $request->city;
            $newHotel->street     = $request->street;
            $newHotel->price    = $request->price;
            $newHotel->name     = $request->name;
            $newHotel->voivode_id  = $request->voivode;
            $newHotel->comment  = $request->comment;
            $newHotel->status  = $request->hotelStatus;
            $newHotel->save();
//            new ActivityRecorder(12,null, 193, 1);
            return 200;
        }
    }

    /**
     * Save new/edit city
     * @param Request $request
     */
    public function saveNewCity(Request $request){
        if($request->ajax()){
            if($request->cityID == 0) // new city
                $newCity = new Cities();
            else    // Edit city
                $newCity = Cities::find($request->cityID);
            $newCity->voivodeship_id = $request->voiovedshipID;
            $newCity->name = $request->cityName;
            $newCity->max_hour = $request->eventCount;
            $newCity->grace_period = $request->gracePeriod;
            $newCity->latitude = $request->latitude;
            $newCity->longitude = $request->longitude;
            $newCity->zip_code = $request->zipCode;

            if(is_null($request->status)) {
                $newCity->status = 0;
            }
            else {
                $newCity->status = $request->status;
            }


//            if($request->weekGrace != '') {
//                $newCity->grace_week = $request->weekGrace;
//            }
//            else {
//                $newCity->grace_week = 0;
//            }

            $newCity->save();
            new ActivityRecorder(12,null, 193, 1);

            return 200;
        }
    }

    /**
     * turn off hotel change status to 0 disable or 1 avaible
     * @param Request $request
     */
    public function changeStatusHotel(Request $request){
        if($request->ajax()){
            $newHotel = Hotel::find($request->hotelId);
            if($newHotel->status == 0) {
                $newHotel->status = 1;
//                new ActivityRecorder(12,null, 193, 3);
            }

            else {
                $newHotel->status = 0;
//                new ActivityRecorder(12,null, 193, 4);
            }

            $newHotel->save();
        }
    }

    /**
     * turn off city change status to 1 disable or 0 avaible
     * @param Request $request
     */
    public function changeStatusCity(Request $request){
        if($request->ajax()){
            $newCity = Cities::find($request->cityId);
            if($newCity->status == 0) {
                $newCity->status = 1;
                new ActivityRecorder(12,null, 193, 3);
            }

            else {
                $newCity->status = 0;
                new ActivityRecorder(12,null, 193, 4);
            }

            $newCity->save();
        }
    }
    /**
     * find city by id
     */
    public function findCity(Request $request){
        if($request->ajax()){
            $city = Cities::find($request->cityId);
            return $city;
        }
    }
    /**
     * find Hotel by id
     */
    public function findHotel(Request $request){
        if($request->ajax()){
            $hotel = Hotel::find($request->hotelId);
            return $hotel;
        }
    }


    /**
     * This method returns view showRoutesDetailed
     */
    public function showRoutesDetailedGet() {
        $year = date('Y',strtotime("this year"));

        $weeksString = date('W', strtotime("this week"));
        $numberOfLastYearsWeek = date('W',mktime(0, 0, 0, 12, 30, $year));

        $departmentInfo = DB::table('department_info')->select(DB::raw('
        department_info.id as id, 
        department_type.name as name, 
        departments.name as name2
        '))
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->get();

        return view('crmRoute.showRoutesDetailed')
            ->with('lastWeek', $numberOfLastYearsWeek)
            ->with('currentWeek', $weeksString)
            ->with('currentYear', $year)
            ->with('departmentInfo', $departmentInfo);
    }

    /**
     * @param
     * @return This method send to datatable info about client_route_info records.
     */
    public function showRoutesDetailedAjax(Request $request) {
        $detailedInfo = ClientRouteInfo::all();
        $cities = Cities::all();
//        $detailedInfo->map(function($item) {
//
//            return $item;
//        });

        return datatables($detailedInfo)->make(true);
    }


    /**
     * CampaignsInfo
     * @params: weeks - array, years - array, departments - array, typ - array
     * This method returns to server data for datatable about all records from ClientRouteInfo table.
     */

    public function campaignsInfo(Request $request){
        $years = $request->years;
        $weeks = $request->weeks;
        $departments = $request->departments;
        $typ = $request->typ;

        $campaignsInfo = ClientRouteInfo::select(DB::raw('
        client_route_info.id as id,
        client_route_info.date as date,
        client_route_info.hour as hour,
        client_route_info.pbx_campaign_id as nrPBX,
        client_route_info.baseDivision as baseDivision,
        client_route_info.verification as verification,
        client_route_info.weekOfYear as weekOfYear,
        client_route_info.limits as limits,
        client_route_info.actual_success as actual_success,
        YEAR(client_route_info.date) as year,       
        (client_route_info.actual_success - client_route_info.limits) as loseSuccess,       
        client.name as clientName,
        departments.name as departmentName,
        department_type.name as departmentName2,
        client_route_info.comment as comment,
        city.name as cityName,
        0 as totalScore,
        client_route.type as typ
        '))
        ->join('client_route','client_route.id','client_route_info.client_route_id')
        ->leftjoin('client','client.id','client_route.client_id')
        ->leftjoin('city','city.id','client_route_info.city_id')
        ->leftjoin('department_info','department_info.id','client_route_info.department_info_id')
        ->leftjoin('departments','departments.id','department_info.id_dep')
        ->leftjoin('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
        ->whereIn('client_route.status',[1,2]);

        if($years[0] != '0') {
            $campaignsInfo = $campaignsInfo->whereIn(DB::raw('YEAR(client_route_info.date)'), $years);
        }

        if($weeks[0] != '0') {
            $campaignsInfo = $campaignsInfo->whereIn('weekOfYear', $weeks);
        }

        if($departments[0] != '0') {
            $campaignsInfo = $campaignsInfo->whereIn('client_route_info.department_info_id', $departments);
        }

        if($typ[0] != '0') {
            $campaignsInfo = $campaignsInfo->whereIn('client_route.type', $typ);
        }

        return datatables($campaignsInfo->get())->make(true);
    }

    /**
     * @param ids - array, limit - number, comment - text, verification - (0,1), invitation - number, department - number
     * @return adnotation for user
     * This method changes limits for selected by user records.
     */
    public function showRoutesDetailedUpdateAjax(Request $request) {
        $ids = json_decode($request->ids);
        $nrPBX = $request->nrPBX;
        if(!is_numeric($nrPBX)){
            $nrPBX = null;
        }

        $baseDivision = $request->baseDivision;
        $limit = $request->limit;
        $comment = $request->comment;
        $invitation = $request->invitation;
        $department = $request->department;
        $verification = $request->verification;
        $liveInvitations = $request->liveInvitation;

        $clientRouteInfoRecords = ClientRouteInfo::whereIn('id', $ids)->get();

        if($nrPBX !=''){
            foreach($clientRouteInfoRecords as $record) {
                $record->pbx_campaign_id = $nrPBX;
                $record->save();
            }
        }
        if($baseDivision !=''){
            foreach($clientRouteInfoRecords as $record) {
                $record->baseDivision = $baseDivision;
                $record->save();
            }
        }
        if($limit != '') {
            foreach($clientRouteInfoRecords as $record) {
                $record->limits = $limit;
                $record->save();
            }
        }

        if($comment != '') {
            foreach($clientRouteInfoRecords as $record) {
                $record->comment = $comment;
                $record->save();
            }
        }

        if($invitation != '') {
            foreach($clientRouteInfoRecords as $record) {

            }
        }

        if($department != '') {
            foreach($clientRouteInfoRecords as $record) {
                $record->department_info_id = $department;
                $record->save();
            }
        }

        if($verification != '') {
            foreach($clientRouteInfoRecords as $record) {
                $record->verification = $verification;
                $record->save();
            }
        }

        if($liveInvitations != '') {
            foreach($clientRouteInfoRecords as $record) {
                $record->actual_success = $liveInvitations;
                $record->save();
            }
        }


        if(count($clientRouteInfoRecords) > 1) {
            $adnotation = "Rekordy zostały zmienione";
        }
        else {
            $adnotation = "Rekord został zmieniony";
        }

        $log = "ClientRouteInfoIds: ";
        foreach($clientRouteInfoRecords as $record) {
            $log .= $record->id . ', ';
        }

        new ActivityRecorder(12,$log,212,2);

        return $adnotation;
    }

    /**
     * This method returns view showCitiesStatistics
     */
    public function showCitiesStatisticsGet() {

        return view('crmRoute.showCitiesStatistics');
    }

    /**
     * @param dateStart, dateStop
     * This method returns data about cities used in given range of dates
     */
    public function showCitiesStatisticsAjax(Request $request) {
        $dateStart = $request->startDate;
        $dateStop = $request->stopDate;

        $clientRouteInfo = DB::table('client_route_info')->select(DB::raw('
            city.name as cityName,
            city.id as cityId,
            voivodeship.name as voivodeName,
            COUNT(client_route_info.city_id) as ilosc
        '))
            ->join('city', 'city.id', '=', 'client_route_info.city_id')
            ->join('voivodeship', 'voivodeship.id', '=', 'city.voivodeship_id')
            ->whereBetween('client_route_info.date', [$dateStart, $dateStop])
            ->groupBy('voivodeship.name', 'city.name')
            ->get();
        return datatables($clientRouteInfo)->make(true);
    }

    /**
     * @param City_id, dateStart, dateStop
     * This method returns data about clientROuteInfo records from given range of dates.
     */
    public function getClientRouteInfoRecords(Request $request) {
        $cityId = $request->cityId;
        $dateStart = $request->dateStart;
        $dateStop = $request->dateStop;

        $clientRouteInfoRecords = ClientRouteInfo::select('city.name as cityName', 'client_route_info.date as date')
            ->join('city', 'city.id', '=', 'client_route_info.city_id')
            ->where('city_id', '=', $cityId)
            ->whereBetween('date', [$dateStart, $dateStop])
            ->get();

        return $clientRouteInfoRecords;
    }

    /**
     * Show aheadPlanning view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function aheadPlanningGet(){
        $year = date('Y',strtotime("this year"));

        $weeksString = date('W', strtotime("this week"));
        $numberOfLastYearsWeek = date('W',mktime(0, 0, 0, 12, 30, $year));

        $departmentInfo = DB::table('department_info')->select(DB::raw('
        department_info.id as id, 
        department_type.name as name, 
        departments.name as name2
        '))
        ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
        ->join('departments', 'department_info.id_dep', '=', 'departments.id')
        ->where('id_dep_type','=',2)
        ->get();

        return view('crmRoute.aheadPlanning')
            ->with('lastWeek', $numberOfLastYearsWeek)
            ->with('currentWeek', $weeksString)
            ->with('currentYear', $year)
            ->with('departmentInfo', $departmentInfo);
    }

    public function getaHeadPlanningInfo(Request $request){
        $departmentInfo = DB::table('department_info')->select(DB::raw('
        department_info.id as id, 
        department_type.name as name, 
        departments.name as name2
        '))
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->where('id_dep_type','=',2)
            ->get();

        $startDate  = $request->startDate;
        $stopDate   = $request->stopDate;
        $actualDate = $startDate;
        $allInfoCollect = collect();

        $routeInfoOverall = ClientRouteInfo::select(DB::raw('
            date,
            department_info_id,
            SUM(limits) as sumOfLimits,
            SUM(actual_success) as sumOfActualSuccess
        '))
            ->groupBy('date', 'department_info_id')
            ->get();

        while($actualDate <= $stopDate){
            $dayCollect = collect();
            $dayCollect->offsetSet('numberOfWeek',date('W',strtotime($actualDate)));
            $dayCollect->offsetSet('dayName',$this::getNameOfWeek($actualDate));
            $dayCollect->offsetSet('day',$actualDate);
            $totalScore = 0;
            $allSet = true;
            foreach ($departmentInfo as $item){
                $routeInfo = $routeInfoOverall
                    ->where('department_info_id' ,'=', $item->id)
                    ->where('date', '=', $actualDate)
                    ->first();

                $dayLimit = $routeInfo['sumOfLimits'];
                $daySuccess = $routeInfo['sumOfActualSuccess'];
                $wynik = $dayLimit - $daySuccess;
                $dayCollect->offsetSet($item->name2, $wynik);

                $totalScore += $wynik;
            }
            $isSet = ClientRouteInfo::where('date','=',$actualDate)
                ->where('department_info_id','=',null)
                ->get()
                ->count();
            if($isSet != 0)
                $allSet = "Nie";
            else
                $allSet = "Tak";
            $dayCollect->offsetSet('allSet',$allSet);
            $dayCollect->offsetSet('totalScore',$totalScore);
            $allInfoCollect->push($dayCollect);
            $actualDate = date('Y-m-d', strtotime($actualDate. ' + 1 days'));
        }
        return datatables($allInfoCollect)->make(true);
    }

    public function getNameOfWeek($date){
        $arrayOfWeekName = [
            '1' => 'Poniedziałek',
            '2' => 'Wtorek',
            '3' => 'Środa',
            '4' => 'Czwartek',
            '5' => 'Piątek',
            '6' => 'Sobota',
            '7' => 'Niedziela'];
        return $arrayOfWeekName[date('N',strtotime($date))+0];

    }

    /**
     * This method returns view presentationStatistics with a data necessary for table
     */
    public function presentationStatisticsGet()
    {

        $year = date('Y',strtotime("this year"));
        $currentMonth = date('m', strtotime("now"));

        $actualMonth = date('Y-m');
        $actualClientsId = ClientRouteInfo::
            join('client_route','client_route.id','client_route_info.client_route_id')
            ->where('date','like',$actualMonth.'%')
            ->groupBy('client_route.client_id')
            ->get()
            ->pluck('client_id')->toArray();
        $date = new DateTime(date('Y-m').'-01');
        $week = $date->format("W");
        //Pobranie równych czterech tygodni
        $split_month = $this->monthPerWeekDivision(date('m'),date('Y'));
        $allInfo = Clients::select(DB::raw(
                'client.id,
                client.name,
                client.type,
                count(client_route_info.client_route_id) as amount,
                client_route_info.date
                '))
            ->join('client_route','client_route.client_id','client.id')
            ->join('client_route_info','client_route_info.client_route_id','client_route.id')
            ->whereIn('client.id',$actualClientsId)
            ->whereBetween('client_route_info.date',[$split_month[0]->date,$split_month[count($split_month)-1]->date])
            ->groupBy('id','date')
            ->get();

        $groupAllInfo = $allInfo->groupBy('type');
        $uniqueClients = $allInfo->unique('name')->groupBy('type');

        //add last sum item to split_month
        $sumObj = new \stdClass();
        $sumObj->date = 'Suma';
        $sumObj->name = 'Suma';
        array_push($split_month, $sumObj);

        $clientArr = array();
        $objectsArr = array();

        /**
         * This part is responsible for creating additional arrays of objects to $groupAllInfo. Each array represents all records for given client with amount, date, name.
         */
        foreach($groupAllInfo as $item) {
            $clientCollect = $uniqueClients[$item->first()->type]->pluck('name');
            $typeArray = array();
            foreach($clientCollect as $clientList) {
                $clientArray = array();
                $weekSum = 0;
                $weekIterator = 1;
                $foreachIterator = 0;
                foreach($split_month as $day) {
                    if($day->name != 'Suma') {
                        $insertionsWithClientAndDate = $item->where('date', '=', $day->date)->where('name', '=', $clientList); //wszystkie wpisy z danego dnia dla danego klienta
                        $dataObject = new \stdClass();
                        $dataObject->name = $clientList;
                        $dataObject->date = $day->date;
                        $dataObject->dayNumber = $day->dayNumber;
                        $dataObject->week = $weekIterator;
                        $dataObject->type = 0; // 0 - day data

                        if($insertionsWithClientAndDate->count() == 0) { //jesli nie ma takiego wpisu
                            $dataObject->amount = 0;
                        }
                        else {
                            $insertionsWithClientAndDate = $insertionsWithClientAndDate->first();
                            $dataObject->amount = $insertionsWithClientAndDate->amount;
                        }
                        $weekSum += $dataObject->amount;
                        array_push($clientArray, $dataObject);
                    }
                    else {
                        $dataObject = new \stdClass();
                        $dataObject->name = $clientList;
                        $dataObject->amount = $weekSum;
                        $dataObject->type = 1; // 1 - sum
                        $dataObject->week = $weekIterator;

                        array_push($clientArray, $dataObject);
                        $weekSum = 0;
                        $weekIterator++;
                    }

                    if($foreachIterator == count($split_month)) {
                        $dataObject = new \stdClass();
                        $dataObject->name = $clientList;
                        $dataObject->amount = $weekSum;
                        $dataObject->type = 1; // 1 - sum
                        $dataObject->week = $weekIterator;

                        array_push($clientArray, $dataObject);
                    }
                    $foreachIterator++;
                }
            $item->offsetSet($clientList,$clientArray);
            }
        }

        //This part is responsible for generating sum row.
        foreach($groupAllInfo as $group) {
            $sumArray = array();
            $clientCollect = $uniqueClients[$group->first()->type]->pluck('name');
            $sumAmount = 0;
            foreach($split_month as $day) {
                $daySum = 0;
                if($day->name == "Suma") {
                    $sumObject = new \stdClass();
                    $sumObject->date = 'Suma';
                    $sumObject->daySum = 0;
                    array_push($sumArray, $sumObject);
                }
                else {
                    foreach ($clientCollect as $oneClient) {
                        foreach ($group[$oneClient] as $key => $value) {
                            if($value->type == 0) { //day data
                                if($day->date == $value->date) {
                                        $daySum += $value->amount;
                                    }
                                }
                        }

                    }
                    $sumObject = new \stdClass();
                    $sumObject->date = $day->date;
                    $sumObject->daySum = $daySum;
                    array_push($sumArray, $sumObject);
                }
            }
            $daySumObject = new \stdClass();
            $group->offsetSet("daySum", $sumArray);
        }

        return view('crmRoute.presentationStatistics')
            ->with('clients',$uniqueClients)
            ->with('days',$split_month)
            ->with('allInfo',$groupAllInfo)
            ->with('months',$this->monthArray())
            ->with('month',date('m'))
            ->with('currentYear', $year)
            ->with('currentMonth', $currentMonth);
    }


    /**
     * @params: year - "2017", month - '4'
     * This function returns data to datatable about statistics from given year and month
     */
    public function presentationStatisticsPost(Request $request) {
        $year = $request->year;
        $month = $request->month;
        if($month < 10) {
            $month = '0' . $month;
        }

        $actualMonth = date($year . '-' . $month);
        $currentMonth = date($month);
        $actualClientsId = ClientRouteInfo::
        join('client_route','client_route.id','client_route_info.client_route_id')
            ->where('date','like',$actualMonth.'%')
            ->groupBy('client_route.client_id')
            ->get()
            ->pluck('client_id')->toArray();
        $date = new DateTime($year. '-' . $month . '-01');
        $week = $date->format("W");
        //Pobranie równych czterech tygodni
        $split_month = $this->monthPerWeekDivision($month,$year);
        $allInfo = Clients::select(DB::raw(
            'client.id,
                client.name,
                client.type,
                count(client_route_info.client_route_id) as amount,
                client_route_info.date
                '))
            ->join('client_route','client_route.client_id','client.id')
            ->join('client_route_info','client_route_info.client_route_id','client_route.id')
            ->whereIn('client.id',$actualClientsId)
            ->whereBetween('client_route_info.date',[$split_month[0]->date,$split_month[count($split_month)-1]->date])
            ->groupBy('id','date')
            ->get();
        $groupAllInfo = $allInfo->groupBy('type');
        $uniqueClients = $allInfo->unique('name')->groupBy('type');

        //add last sum item to split_month
        $sumObj = new \stdClass();
        $sumObj->date = 'Suma';
        $sumObj->name = 'Suma';
        array_push($split_month, $sumObj);

        $clientArr = array();
        $objectsArr = array();

        /**
         * This part is responsible for creating additional arrays of objects to $groupAllInfo. Each array represents all records for given client with amount, date, name.
         */
        foreach($groupAllInfo as $item) {
            $clientCollect = $uniqueClients[$item->first()->type]->pluck('name');
            $typeArray = array();
            foreach($clientCollect as $clientList) {
                $clientArray = array();
                $weekSum = 0;
                $weekIterator = 1;
                $foreachIterator = 0;
                foreach($split_month as $day) {
                    if($day->name != 'Suma') {
                        $insertionsWithClientAndDate = $item->where('date', '=', $day->date)->where('name', '=', $clientList); //wszystkie wpisy z danego dnia dla danego klienta
                        $dataObject = new \stdClass();
                        $dataObject->name = $clientList;
                        $dataObject->date = $day->date;
                        $dataObject->dayNumber = $day->dayNumber;
                        $dataObject->week = $weekIterator;
                        $dataObject->type = 0; // 0 - day data

                        if($insertionsWithClientAndDate->count() == 0) { //jesli nie ma takiego wpisu
                            $dataObject->amount = 0;
                        }
                        else {
                            $insertionsWithClientAndDate = $insertionsWithClientAndDate->first();
                            $dataObject->amount = $insertionsWithClientAndDate->amount;
                        }
                        $weekSum += $dataObject->amount;
                        array_push($clientArray, $dataObject);
                    }
                    else {
                        $dataObject = new \stdClass();
                        $dataObject->name = $clientList;
                        $dataObject->amount = $weekSum;
                        $dataObject->type = 1; // 1 - sum
                        $dataObject->week = $weekIterator;

                        array_push($clientArray, $dataObject);
                        $weekSum = 0;
                        $weekIterator++;
                    }

                    if($foreachIterator == count($split_month)) {
                        $dataObject = new \stdClass();
                        $dataObject->name = $clientList;
                        $dataObject->amount = $weekSum;
                        $dataObject->type = 1; // 1 - sum
                        $dataObject->week = $weekIterator;

                        array_push($clientArray, $dataObject);
                    }
                    $foreachIterator++;
                }
                $item->offsetSet($clientList,$clientArray);
            }
        }

        //This part is responsible for generating sum row.
        foreach($groupAllInfo as $group) {
            $sumArray = array();
            $clientCollect = $uniqueClients[$group->first()->type]->pluck('name');
            $sumAmount = 0;
            foreach($split_month as $day) {
                $daySum = 0;
                if($day->name == "Suma") {
                    $sumObject = new \stdClass();
                    $sumObject->date = 'Suma';
                    $sumObject->daySum = 0;
                    array_push($sumArray, $sumObject);
                }
                else {
                    foreach ($clientCollect as $oneClient) {
                        foreach ($group[$oneClient] as $key => $value) {
                            if($value->type == 0) { //day data
                                if($day->date == $value->date) {
                                    $daySum += $value->amount;
                                }
                            }
                        }

                    }
                    $sumObject = new \stdClass();
                    $sumObject->date = $day->date;
                    $sumObject->daySum = $daySum;
                    array_push($sumArray, $sumObject);
                }
            }
            $daySumObject = new \stdClass();
            $group->offsetSet("daySum", $sumArray);
        }

        return view('crmRoute.presentationStatistics')
            ->with('clients',$uniqueClients)
            ->with('days',$split_month)
            ->with('allInfo',$groupAllInfo)
            ->with('months',$this->monthArray())
            ->with('month',date('m'))
            ->with('currentYear', $year)
            ->with('currentMonth', $currentMonth);
    }

    public function monthPerWeekDivision($month,$year){
        $arrayOfWeekName = [
            '1' => 'Poniedziałek',
            '2' => 'Wtorek',
            '3' => 'Środa',
            '4' => 'Czwartek',
            '5' => 'Piątek',
            '6' => 'Sobota',
            '7' => 'Niedziela'];

        $days_in_month = date('t', strtotime($year . '-' . $month));
        $numberOfWeekPreviusMonth = $this::getWeekNumber(date('Y-m-d', strtotime($year.'-'.$month.'-01'. ' - 1 days')));
        $weeks = [];
        for ($i = 1; $i <= $days_in_month; $i++) {
            $loop_day = ($i < 10) ? '0' . $i : $i ;
            $date = $year.'-'.$month.'-'.$loop_day;
            $actualWeek = $this::getWeekNumber($date);
            if($actualWeek != $numberOfWeekPreviusMonth){
                foreach($arrayOfWeekName as $key => $value) {
                    if($value == $this::getNameOfWeek($date)) {
                        $weeksObj = new \stdClass();
                        $weeksObj->date = $date;
                        $weeksObj->name = $this::getNameOfWeek($date);
                        $weeksObj->dayNumber = $key;
                        array_push($weeks,$weeksObj);

                        // czy niedziela
                        if($weeksObj->name == $arrayOfWeekName[7]) {
                            $sumObj = new \stdClass();
                            $sumObj->date = 'Suma';
                            $sumObj->name = 'Suma';
                            array_push($weeks, $sumObj);
                        }
                    }
                }
            }
        }
        $lastNumberOfWeek = $actualWeek;
        $dateNextMonth = date('Y-m-d', strtotime($date . ' + 1 days'));
        $daysInNextMonth = date('t', strtotime($dateNextMonth));
        for ($i = 1; $i <= $daysInNextMonth; $i++) {
            $loop_day = ($i < 10) ? '0' . $i : $i ;
            $date = date('Y-m',strtotime($dateNextMonth)).'-'.$loop_day;
            $actualWeek = $this::getWeekNumber($date);
            if($actualWeek == $lastNumberOfWeek){
                foreach($arrayOfWeekName as $key => $value) {
                    if($value == $this::getNameOfWeek($date)) {
                        $weeksObj = new \stdClass();
                        $weeksObj->date = $date;
                        $weeksObj->name = $this::getNameOfWeek($date);
                        $weeksObj->dayNumber = $key;
                        array_push($weeks,$weeksObj);
                    }
                }
            }else{
                break;
            }
        }
        return $weeks;
    }

    public function getWeekNumber($date){
        $actualWeek = new DateTime($date);
        $actualWeek = $actualWeek->format("W");
        return $actualWeek;
    }

    public function monthArray(){
        /**
         * Tabela z miesiącami
         */
        $months = [
            '01' => 'Styczeń',
            '02' => 'Luty',
            '03' => 'Marzec',
            '04' => 'Kwiecień',
            '05' => 'Maj',
            '06' => 'Czerwiec',
            '07' => 'Lipiec',
            '08' => 'Sierpień',
            '09' => 'Wrzesień',
            '10' => 'Październik',
            '11' => 'Listopad',
            '12' => 'Grudzień'
        ];
        return $months;
    }
    public function getPresentationInfo(){
        $client = Clients::select('name','type')->get();
        return datatables($client)->make(true);
    }
}
