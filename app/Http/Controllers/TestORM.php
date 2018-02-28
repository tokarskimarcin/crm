<?php

namespace App\Http\Controllers;

use App\CsvReader;
use App\Department_info;
use App\HourReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Request;


class TestORM extends Controller
{
    private $report_type_array = array('TEAM','TERMINAL','PRESENTATION');
    public function test()
    {

        $today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        $hour_stop = $today . ' ' . '23:00:00'; //tutaj zmienic przy wydawaniu na produkcję na  date('H') - 1
        $hour_start = $today . ' ' . '00:00:00';

        $dkj = DB::table('dkj')
            ->select(DB::raw('
              users.department_info_id,
              departments.name as dep_name,
              department_type.name as dep_name_type,
              department_info.type,
              count(dkj.id) as liczba_odsluchanych,
              sum(CASE WHEN users.dating_type = 0 THEN 1 ELSE 0 END) as badania,
              sum(CASE WHEN users.dating_type = 1 THEN 1 ELSE 0 END) as wysylka,
              SUM(CASE WHEN dkj.dkj_status = 1 AND users.dating_type = 0 THEN 1 ELSE 0 END) as bad_badania,
              SUM(CASE WHEN dkj.dkj_status = 1 AND users.dating_type = 1 THEN 1 ELSE 0 END) as bad_wysylka
          '))
            ->join('users', 'users.id', '=', 'dkj.id_user')
            ->join('department_info', 'department_info.id', '=', 'users.department_info_id')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->whereBetween('add_date', [$hour_start, $hour_stop])
            ->groupBy('users.department_info_id')
            ->groupBy('users.dating_type')
            ->get();


        $lp = 0;
        $dont_save = false;
        $report_type = 0;
        $department_id = null;
        $url = "https://vc.e-pbx.pl/callcenter/api/statistic-report?statType=23&groupType=" . $this->report_type_array[$report_type];
        $header_array = array('department_info_id', 'average', 'success', '', '', 'wear_base', 'call_time', 'employee_count', 'user_id', 'hour', 'report_date', 'is_send', 'janky_count');
        if (!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";
        if (($handle = fopen($url, "r")) !== FALSE) {
            while (($data1 = fgetcsv($handle, 1000, ";")) !== FALSE) {
                if ($lp > 2) {
                    $dont_save = false;
                    $i = 0;
                    $typ = 0;
                    $janky = 0;
                    $count_cehck =0;
                    foreach ($data1 as $item) {
                        if ($i == 0) {
                            preg_match_all('!\d+!', $item, $matches);
                            if (!empty($matches[0])) {
                                if ($matches[0][0] != 0)
                                {
                                    // id_department dodać orm
                                    $department_id = Department_info::where('pbx_id',$matches[0][0])->first();
                                    if(isset($department_id->id) && $department_id->id != null){
                                        $spreadsheet_data[$lp][$header_array[$i]] = $department_id->id;
                                    }else{
                                        $dont_save = true;
                                    }
                                }
                                else
                                    $dont_save = true;
                            }else
                                $dont_save = true;

                        } else if (!$dont_save) {
                            if ($item == '-' || $item == 'null')
                                $item = 0;
                            if ($i == 1 || $i == 2 || $i == 7) {
                                $spreadsheet_data[$lp][$header_array[$i]] = utf8_encode($item);
                            }
                            if ($i == 5 || $i == 6) {
                                $split = explode(" ", $item);
                                $item = $split[0];
                                $spreadsheet_data[$lp][$header_array[$i]] = utf8_encode($item);
                            }
                            $spreadsheet_data[$lp]['user_id'] = 1364;
                            $spreadsheet_data[$lp]['hour'] = date('H').":00:00";
                            $spreadsheet_data[$lp]['report_date'] = date('Y-m-d');
                            $spreadsheet_data[$lp]['is_send'] = 0;

                            if($department_id->id == 13)
                            {
                                $colection = $dkj->where('department_info_id',4)->where('badania','!=',0)->first();
                                if(isset($colection->liczba_odsluchanych) && $colection->liczba_odsluchanych != null)
                                {
                                    $count_cehck = $colection->liczba_odsluchanych;
                                    $janky = $colection->bad_badania;
                                }

                            }else if($department_id->id == 4){
                                $colection = $dkj->where('department_info_id',$spreadsheet_data[$lp]['department_info_id'])->where('wysylka','!=',0)->first();
                                if(isset($colection->liczba_odsluchanych) && $colection->liczba_odsluchanych != null)
                                {
                                    $count_cehck = $colection->liczba_odsluchanych;
                                    $janky = $colection->bad_wysylka;
                                }
                            }else{
                                $colection = $dkj->where('department_info_id',$spreadsheet_data[$lp]['department_info_id'])->first();
                                if(isset($colection->liczba_odsluchanych) && $colection->liczba_odsluchanych != null) {
                                    if ($colection->type == 'Badania') {
                                        $count_cehck = $colection->liczba_odsluchanych;
                                        $janky = $colection->bad_badania;
                                    } else if ($colection->type == 'Wysyłka') {
                                        $count_cehck = $colection->liczba_odsluchanych;
                                        $janky = $colection->bad_wysylka;
                                    }
                                }
                            }
                            if($count_cehck != 0){
                                $janky_count = round($janky / $count_cehck * 100, 2);
                            }else{
                                $janky_count = 0;
                            }

                            $spreadsheet_data[$lp]['janky_count'] = $janky_count;
                            if($i == 7)
                            {
                                if($spreadsheet_data[$lp]['average'] != 0){
                                    $spreadsheet_data[$lp]['hour_time_use'] = round($spreadsheet_data[$lp]['success']/$spreadsheet_data[$lp]['average'],2);
                                }else{
                                    $spreadsheet_data[$lp]['hour_time_use'] = 0;
                                }
                            }
                        }
                        $i++;
                    }
                }
                $lp++;
            }
            fclose($handle);
        }
        HourReport::insert($spreadsheet_data);

    }
}
