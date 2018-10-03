<?php

namespace App\Http\Controllers;

use App\Cities;
use App\ClientRoute;
use App\ClientRouteInfo;
use App\Schedule;
use App\Pbx_report_extension;
use App\ClientRouteCampaigns;
use App\User;
use App\Work_Hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutoScriptController extends Controller
{

    //Temporary method for assigning successes for given date from pbx report
    public function pbx_update() {

        $date = '2018-10-02';

        $workHour = Work_Hour::where('date', '=', $date)->get();

        $maxIds = DB::table('pbx_report_extension')
            ->select(DB::raw('
                    MAX(id) as id
                '))
            ->groupBy('user_id')
            ->where('report_date', '=', $date)
            ->get();

        $pbx = Pbx_report_extension::where('report_date', '=', $date)->whereIn('id', $maxIds->pluck('id')->toArray())->get()->groupBy('user_id')->sortBy('id')->toArray();

        foreach($workHour as $hourItem) {
            if(array_key_exists($hourItem->id_user, $pbx)) {
                $record = $pbx[$hourItem->id_user][0];
                $success =  $record['success'];

                Work_Hour::where('id', '=', $hourItem->id)->update(['success' => $success]);
            }
        }

    }

    //Pobranie zgód dla miast
    public function setCityApprovalPart1(){

        $allCity = Cities::all();
        $partCity = $allCity->where('id','<=',150);
        set_time_limit(10000);
        foreach ($partCity as $item){
            $url = 'http://baza.teambox.pl/baza/getRaportCityInfoAPI/'.$item->name;
            $url = preg_replace("/ /", "%20", $url);
            $json =  file_get_contents($url);
            $obj = json_decode($json);
            $item->approval_count = $obj->zgody;
            try{
                $item->save();
            }catch (\Exception $exception){
                // did nothing
            }
        }
    }
    public function setCityApprovalPart2(){
        $allCity = Cities::whereBetween('id',[150, 300])->get();
        set_time_limit(10000);
        foreach ($allCity as $item){
            $url = 'http://baza.teambox.pl/baza/getRaportCityInfoAPI/'.$item->name;
            $url = preg_replace("/ /", "%20", $url);
            $json =  file_get_contents($url);
            $obj = json_decode($json);
            $item->approval_count = $obj->zgody;
            try{
                $item->save();
            }catch (\Exception $exception){
                // did nothing
            }
        }
    }

    public function setCityApprovalPart3(){
        $allCity = Cities::whereBetween('id',[300, 450])->get();
        set_time_limit(10000);
        foreach ($allCity as $item){
            $url = 'http://baza.teambox.pl/baza/getRaportCityInfoAPI/'.$item->name;
            $url = preg_replace("/ /", "%20", $url);
            $json =  file_get_contents($url);
            $obj = json_decode($json);
            $item->approval_count = $obj->zgody;
            try{
                $item->save();
            }catch (\Exception $exception){
                // did nothing
            }
        }
    }

    public function setCityApprovalPart4(){
        $allCity = Cities::whereBetween('id',[450, 600])->get();
        set_time_limit(10000);
        foreach ($allCity as $item){
            $url = 'http://baza.teambox.pl/baza/getRaportCityInfoAPI/'.$item->name;
            $url = preg_replace("/ /", "%20", $url);
            $json =  file_get_contents($url);
            $obj = json_decode($json);
            $item->approval_count = $obj->zgody;
            try{
                $item->save();
            }catch (\Exception $exception){
                // did nothing
            }
        }
    }
    public function setCityApprovalPart5(){
        $allCity = Cities::whereBetween('id',[600, 750])->get();
        set_time_limit(10000);
        foreach ($allCity as $item){
            $url = 'http://baza.teambox.pl/baza/getRaportCityInfoAPI/'.$item->name;
            $url = preg_replace("/ /", "%20", $url);
            $json =  file_get_contents($url);
            $obj = json_decode($json);
            $item->approval_count = $obj->zgody;
            try{
                $item->save();
            }catch (\Exception $exception){
                // did nothing
            }
        }
    }
    public function setCityApprovalPart6(){
        $allCity = Cities::where('id','>=',750)->get();
        set_time_limit(10000);
        foreach ($allCity as $item){
            $url = 'http://baza.teambox.pl/baza/getRaportCityInfoAPI/'.$item->name;
            $url = preg_replace("/ /", "%20", $url);
            $json =  file_get_contents($url);
            $obj = json_decode($json);
            $item->approval_count = $obj->zgody;
            try{
                $item->save();
            }catch (\Exception $exception){
                // did nothing
            }
        }
    }

    //AutoChange Route Status from 1 to 2
    public function autoChangeRouteStatus(){
        $toDay = date('Y-m-d');
        $allClientRowToChangeStatus = ClientRoute::
        join('client_route_info','client_route_info.client_route_id','client_route.id')
        ->where('client_route.status','=','1')
        ->where('client_route_info.date','<=',$toDay)
        ->update(['client_route.status' => 2]);
    }
    //Auto invoice penalty
    public function checkPenatly(){
        $allSendInvoice = ClientRouteCampaigns::
            select(DB::raw('
                    id,
                    TIME_TO_SEC( TIMEDIFF ( now(), invoice_send_date) ) as waitSeconds
                '))
                ->where('invoice_status_id','=',3)
                ->get();
        foreach ($allSendInvoice as $item){
            if($item->waitSeconds > 172800){
                $waitSeconds = $item->waitSeconds - 172800;
                $penalty = intval($waitSeconds/86400) * 50;
                $item->penalty = $penalty;
                $item->save();
            }
        }
    }

    public function setEngraverForConfirming() {
        $limitDate = Date('W', strtotime('-50 days'));

        $limitDateFull = Date('Y-m-d', strtotime('-50 days'));
        $lastMonthFull = Date('Y-m-d', strtotime('-30 days'));

        $clientRouteInfoRecords = ClientRouteInfo::select(
            'client_route_info.id as id',
            'confirmDate',
            'client.name',
            'priority',
            'client_route_info.date',
            'confirmingUser',
            'city_id',
            'limits',
            'frequency'
        )
            ->join('client_route', 'client_route_info.client_route_id', '=', 'client_route.id')
            ->join('client', 'client_route.client_id', '=', 'client.id')
            ->OnlyActive()
            ->orderBy('priority', 'desc')
            ->orderBy('client_route_info.date');

        $clientRouteInfoRecords2 = ClientRouteInfo::select(
            'client_route_info.id as id',
            'confirmDate',
            'client.name',
            'priority',
            'client_route_info.date',
            'confirmingUser',
            'city_id',
            'limits',
            'frequency'
        )
            ->join('client_route', 'client_route_info.client_route_id', '=', 'client_route.id')
            ->join('client', 'client_route.client_id', '=', 'client.id')
            ->OnlyActive()
            ->orderBy('priority', 'desc')
            ->orderBy('client_route_info.date');

        $scheduleData = Schedule::select('id_user as userId', 'users.first_name as name','department_info.id as depId' ,'users.last_name as surname', 'week_num', 'year', 'monday_comment as pon', 'tuesday_comment as wt', 'wednesday_comment as sr', 'thursday_comment as czw', 'friday_comment as pt', 'saturday_comment as sob','sunday_comment as nd')
            ->join('users', 'schedule.id_user', '=', 'users.id')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->where('week_num', '>', $limitDate)
//            ->where('department_info.id_dep_type', '=', 1) //gdy beda juz grafiki dla potwierdzen
            ->where('users.status_work', '=', 1)
            ->orderBy('surname')
            ->get();

        $scheduleGroupedByUser = $scheduleData->groupBy('userId', 'week_num');
        $usersWorkingLessThan30RBH = Work_Hour::usersWorkingRBHSelector(30,'<');
        $cl = $clientRouteInfoRecords->where('confirmingUser', '!=', null)->where('confirmDate', '<>', null)->where('confirmDate', '>', $lastMonthFull);

        $confirmingUsersArr = $cl->pluck('confirmingUser')->toArray();
        $confirmingUsersArrWithoutDuplicates = array_unique($confirmingUsersArr); //array of confirming users ids
        //This part is responsible for creating user objects with date field and pass it to userArr
        $userArr = [];

        foreach($scheduleGroupedByUser as $id => $data) {
            $user = new \stdClass();
            $user->user_id = $id; //id of consultant
            $user->new_user = 0; //indices whether consultant it is new consultant(working less than x RBH)
            foreach($usersWorkingLessThan30RBH as $newUser) { //change new_user fild value to 1 if it is new consultant
                if($newUser->id_user == $id) {
                    $user->new_user = 1;
                }
            }

            $dataArr = [];
            $i = 0;
            foreach($data as $item) {
                if($i == 0) {
                    $user->name = $item->name;
                    $user->surname = $item->surname;
                    $user->dep_id = $item->depId;
                }
                $i++;
                $firstDayOfGivenWeek = Date('Y-m-d', strtotime($item->year . 'W' . $item->week_num));
                if($item->pon != '') {
                    array_push($dataArr, $firstDayOfGivenWeek);
                }

                if($item->wt != '') {
                    array_push($dataArr, Date('Y-m-d', strtotime($firstDayOfGivenWeek . '+ 1 day')));
                }

                if($item->sr != '') {
                    array_push($dataArr, Date('Y-m-d', strtotime($firstDayOfGivenWeek . '+ 2 days')));
                }

                if($item->czw != '') {
                    array_push($dataArr, Date('Y-m-d', strtotime($firstDayOfGivenWeek . '+ 3 days')));
                }

                if($item->pt != '') {
                    array_push($dataArr, Date('Y-m-d', strtotime($firstDayOfGivenWeek . '+ 4 days')));
                }

                if($item->sob != '') {
                    array_push($dataArr, Date('Y-m-d', strtotime($firstDayOfGivenWeek . '+ 5 days')));
                }

                if($item->nd != '') {
                    array_push($dataArr, Date('Y-m-d', strtotime($firstDayOfGivenWeek . '+ 6 days')));
                }
            }
            $user->date = $dataArr;
            $user->info = []; //this field contains info about scoring for each city

            $finalCoefficientArr = [];
            $coefficientArr = [];

            foreach($confirmingUsersArrWithoutDuplicates as $confUser) {
                $cityArray = []; //array of all cities that user confirmed in past 30 days
                if($id == $confUser) { //if user has client route info records from past 30 days where he was confirming person
                    $allUserClRecords = ClientRouteInfo::where('confirmingUser', '=', $confUser)->where('confirmingUser', '!=', null)->where('confirmDate', '<>', null)->where('confirmDate', '>', $lastMonthFull)->get(); //all confirming user client route info records

                    //computing scores based on city.
                    foreach($allUserClRecords as $rec) {
                        $coefficient = ($rec->limits != 0 || $rec->limits != null) && ($rec->frequency != 0 || $rec->frequency != null) ? round($rec->frequency / $rec->limits,2) : 0;

                        $obj = new \stdClass();
                        $obj->user_id = $rec->confirmingUser;
                        $obj->city_id = $rec->city_id;
                        $obj->coefficient = $coefficient;
                        array_push($coefficientArr, $obj);
                        array_push($cityArray, $rec->city_id);
                    }

                    $cityArray = array_unique($cityArray);
                    foreach($cityArray as $cityItem) {
                        $sumCoefficient = 0;
                        $sumItems = 0;
                        $i = 0;
                        $city = null;
                        $user_id = null;
                        foreach($coefficientArr as $coefficientItem) {
                           if($coefficientItem->city_id == $cityItem) {
                               if($i == 0) {
                                   $city = $coefficientItem->city_id;
                                   $user_id = $coefficientItem->user_id;
                               }
                               $sumItems++;
                               $sumCoefficient += $coefficientItem->coefficient;
                               $i++;
                           }
                        }
                        $avgCoefficient = $sumItems != 0 ? round($sumCoefficient / $sumItems,2) : 0;

                        $finalObj = new \stdClass();
                        $finalObj->coefficient = $avgCoefficient;
                        $finalObj->city_id = $city;
                        $finalObj->user_id = $user_id;
                        array_push($finalCoefficientArr, $finalObj);
                    }

                $user->info = $finalCoefficientArr;
                }
            }

            //Now we are counting scoring for user
//            $user->scoring = rand(0,1);
            array_push($userArr, $user);
        }

        $dayCollect = new \stdClass(); //creating object with fields indicating 100 days. Inside each day object there is array of people who are available this date with theier statistics
        for($i = 0; $i < 100; $i++) {
            $availableUserArr = [];
            $givenDate = date('Y-m-d', strtotime($limitDateFull . '+ ' . $i . 'days'));
            foreach($userArr as $user) {
                foreach($user->date as $date) {
                    if($date == $givenDate) {
                        array_push($availableUserArr, $user);
                    }
                }
            }

            $dayCollect->$givenDate = $availableUserArr;
        }

        $clientRouteInfoRec = $clientRouteInfoRecords2->where('confirmingUser', '=', null)->where('confirmDate', '>', $limitDateFull)->get();

        //assigning confirming perseon for each client route info row without this person.
        foreach($clientRouteInfoRec as $singleShow) {
            $day = $singleShow->confirmDate;
            $city_id = $singleShow->city_id;
            $bestConsultant = null;
            $i = 0;
            $actualGreatesScore = 0;
            foreach($dayCollect->$day as $singleConsultant) {

                if($i == 0) {
                    $bestConsultant = $singleConsultant;

                    foreach($singleConsultant->info as $scoring) {
                        if($city_id == $scoring->city_id) {
                            $actualGreatesScore = $scoring->coefficient;
                        }
                    }
                }
                else {
                    foreach($singleConsultant->info as $scoring) {
                        if($city_id == $scoring->city_id) {
                            if($actualGreatesScore < $scoring->coefficient) {
                                $bestConsultant = $singleConsultant;
                                $actualGreatesScore = $scoring->coefficient;
                            }
                        }
                    }

                }
                $i++;
            }

            if($bestConsultant != null) {
                ClientRouteInfo::where('id', '=', $singleShow->id)->update(['confirmingUser' => $bestConsultant->user_id]);
            }
        }
    }

    public function autoSalaryIncrease() {
        $allActiveUsers = User::getActiveUsers();
        $allActiveUsersGrouped = $allActiveUsers->groupBy('user_type_id');
        foreach($allActiveUsersGrouped as $groupId => $groupMembers) {
            switch($groupId) {
                case 5: {

                    break;
                }
            }
        }
    }

}
