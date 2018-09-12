<?php

namespace App\Http\Controllers;

use App\ClientRoute;
use App\ClientRouteInfo;
use App\Schedule;
use App\ClientRouteCampaigns;
use App\Work_Hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutoScriptController extends Controller
{
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

        $scheduleData = Schedule::select('id_user as userId', 'users.first_name as name','department_info.id as depId' ,'users.last_name as surname', 'week_num', 'year', 'monday_comment as pon', 'tuesday_comment as wt', 'wednesday_comment as sr', 'thursday_comment as czw', 'friday_comment as pt', 'saturday_comment as sob','sunday_comment as nd')
            ->join('users', 'schedule.id_user', '=', 'users.id')
            ->join('department_info', 'users.department_info_id', '=', 'department_info.id')
            ->where('week_num', '>', $limitDate)
//            ->where('department_info.id_dep_type', '=', 1) //gdy beda juz grafiki dla potwierdzen
            ->where('users.status_work', '=', 1)
            ->orderBy('surname')
            ->get();

        $scheduleGroupedByUser = $scheduleData->groupBy('userId', 'week_num');

        $usersWorkingLessThan30RBH = Work_Hour::usersWorkingLessThan(30);

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

            //Now we are counting scoring for user
            $user->scoring = rand(0,1);
            array_push($userArr, $user);
        }

        $clientRouteInfoRecords = ClientRouteInfo::select(
            'client_route_info.id as id',
            'confirmDate',
            'confirmingUser',
            'client.name',
            'priority',
            'client_route_info.date',
            'confirmingUser'
        )
            ->join('client_route', 'client_route_info.client_route_id', '=', 'client_route.id')
            ->join('client', 'client_route.client_id', '=', 'client.id')
            ->OnlyActive()
            ->where('date', '>', $limitDateFull)
            ->where('confirmDate', '<>', null)
            ->where('confirmingUser', '=', null)
            ->orderBy('priority', 'desc')
            ->orderBy('client_route_info.date')
            ->get();
//        dd($clientRouteInfoRecords);

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

//        dd($clientRouteInfoRecords);

        foreach($clientRouteInfoRecords as $singleShow) {
            $day = $singleShow->confirmDate;
            $bestConsultant = null;
            $i = 0;
            $actualGreatesScore = 0;
            foreach($dayCollect->$day as $singleConsultant) {
                if($i == 0) {
                    $bestConsultant = $singleConsultant;
                }
                else {
                    if($bestConsultant->scoring < $singleConsultant->scoring) {
                        $bestConsultant = $singleConsultant;
                    }
                }
                $i++;

            }
            if($bestConsultant != null) {
//                dd('1');
                ClientRouteInfo::where('id', '=', $singleShow->id)->update(['confirmingUser' => $bestConsultant->user_id]);
            }
        }

//        $j = '2018-09-09';

//        dd($userArr[0]);
    }
}
