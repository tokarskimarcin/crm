<?php

namespace App\Http\Controllers;

use App\VeronaMail;
use Illuminate\Http\Request;
use App\Department_info;
use App\HourReport;
use App\Pbx_report_extension;
use Illuminate\Support\Facades\Storage;
use Session;
use Mail;
use App\User;


class ScreensController extends Controller
{
    /**
     * @param $id
     * @return view, array, array
     * Metoda zwraca widok monitorów(monitors) wraz z tablicą wypełnioną rekordami konsultantów i
     * tablicą wypełnioną rekordami wydziałów
     */
    public function monitorMethod($id) {
        $hour = date('H'); //03
        $hour = $hour . ":00:00"; //03:00:00
        $date = date("Y-m-d"); //2000-10-11
        $userTable = [];
        $reportTable = [];

        $givenUsers = $this::getPbxRecords($date, $hour);
        $userTable = $this->filingArrayDI($givenUsers, $userTable, $id);
        $report = $this::getHourReportRecords($date, $hour);
        $reportTable = $this::filingArrayDT($report, $reportTable, 2);

        return view('screens.monitors')->with('userTable', $userTable)
            ->with('reportTable', $reportTable);
    }

    /**
     * @param Request $request
     * @return view, department_info data
     * Metoda zwraca widok tabeli monitorów(screen_table) oraz dane dot. poszczególnych departamentów
     */
    public function screenMethod(Request $request) {
        $department_info = Department_info::all();
        return view('screens.screen_table')->with('dane', $department_info);
    }

    /**
     * @param $date
     * @param $hour
     * @return records
     * Zwraca rekordy z tabeli pbx_report_extension, o podanej dacie i godzinie, posortowane po "average"
     */
    public function getPbxRecords($date, $hour) {
        return Pbx_report_extension::where('report_date', '=', $date)
            ->where('report_hour', '=', $hour)
            ->orderBy('average', 'DESC')
            ->get();
    }

    /**
     * @param $date
     * @param $hour
     * @return records
     * Zwraca rekordy z tabeli hour_report, o podanej dacie i godzinie
     */
    public function getHourReportRecords($date, $hour) {
        return HourReport::where('hour', $hour)
            ->where('report_date', $date)
            ->orderBy('average', 'DESC')
            ->get();
    }

    /**
     * @param $records
     * @param $arr
     * @param $id
     * @return filled array
     * Zwraca tablice wypełnioną rekordami, uwarunkowanymi department_info_id = $id
     */
    public function filingArrayDI($records, $arr, $id) {
        foreach($records as $item) {
            if(is_object($item->user)) {
                if($item->user->department_info_id == $id) {
                    array_push($arr, $item);
                }
            }
        }
        return $arr;
    }

    /**
     * @param $records
     * @param $arr
     * @param $id
     * @return filled array
     * Zwraca tablice wypełnioną rekordami, uwarunkowanymi department_type_id = $id
     */
    public function filingArrayDT($records, $arr, $id) {
        foreach($records as $r) {
            if(is_object($r)) {
                if($r->department_info->id_dep_type == $id) {
                    array_push($arr, $r);
                }
            }
        }
        return $arr;
    }

    /**
     * THis method return necesary data for displaying charts
     */
    public function showScreensGet() {
        $today = date("Y-m-d"); //2000-10-11
        $today = date("2018-08-22"); //2000-10-11
        $department_info = Department_info::where('id_dep_type', '=', '2')->get();

        $departmentsAveragesForEveryHour = StatisticsController::getDepartmentsAveragesForEveryHour($today, $department_info);

        return view('screens.charts')->with('departmentsAveragesForEveryHour', $departmentsAveragesForEveryHour);
    }

    public function showScreenGet($id){
        $today = date("Y-m-d"); //2000-10-11
        $today = date("2018-08-22"); //2000-10-11
        $department_info = Department_info::where('id_dep_type', '=', '2')->get();

        $departmentsAveragesForEveryHour = StatisticsController::getDepartmentsAveragesForEveryHour($today, $department_info);

        return view('screens.chart')->with('departmentsAveragesForEveryHour', $departmentsAveragesForEveryHour)->with('dep_info_id', $id);
    }

    /**
     * THis method return necesary data for displaying all charts at once
     */
    public function allCharts() {
        $today = date("Y-m-d");
        $today = date("2018-08-22"); //2000-10-11
        $department_info = Department_info::where('id_dep_type', '=', '2')->get();

        $departmentsAveragesForEveryHour = StatisticsController::getDepartmentsAveragesForEveryHour($today, $department_info);

        return view('screens.allCharts')->with('departmentsAveragesForEveryHour', $departmentsAveragesForEveryHour);
    }

    /**
     * Upload charts screenshots
     */
    public function uploadScreenshotsAjax(Request $request){
        $fileName = 'allChartsImage';
        $chartScreenshotsPath =  $fileName.'_files';

        $file = $request->file($fileName);

        if ($file !== null) {
            $img = $file->getClientOriginalName();

            // get uploaded file's extension`
            $ext = $this->getExtensionFromMimeType($file->getMimeType());

            if(in_array($ext, ['png','jpeg'])){
                if (!in_array('public/'.$chartScreenshotsPath, Storage::allDirectories())) {
                    Storage::makeDirectory('public/'.$chartScreenshotsPath);
                }
                Storage::delete('public/'.$chartScreenshotsPath.'/'.$fileName.'.'.$ext);
                $file->storeAs('public/'.$chartScreenshotsPath, $fileName.'.'.$ext);
                return 'success';
            }else{
                return 'fail';
            }
        }
        return 'fail';
    }

    private function getExtensionFromMimeType ($mime_type){
        $extensions = array(
            'image/jpeg' => 'jpeg',
            'image/png' => 'png',
        );

        // Add as many other Mime Types / File Extensions as you like

        return $extensions[$mime_type];
    }

    public function sendAllChartsMail(){

        $title = 'Godzinowy wykres Telemarketingu';
        $data = ['fileURL' => Storage::url("allChartsImage_files/allChartsImage.png")];
        $preperMail = new VeronaMail('allCharts',$data,$title,User::where('id',1364)->get());
        if($preperMail->sendMail()){
            return 'Mail wysłano';
        }else{
            return 'Błąd podczas wysyłania maila';
        }

//        Mail::send('mail/allCharts',['fileURL' => Storage::url("allChartsImage_files/allChartsImage.png")],function ($message){
//            //$message->from('noreply.verona@gmail.com', 'Verona Consulting');
//            $message->to('tokarski.verona@gmail.com','Marcin Tokarski')->subject('Statystki oddziałów');
//        });
    }
}
