<?php

namespace App\Http\Controllers;

use App\CsvReader;
use App\Department_info;
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

        $today = date('Y-m-d');

        $hour_stop = $today . ' ' . date('H', time() + 36000) . ':00:00'; //tutaj zmienic przy wydawaniu na produkcję na  date('H') - 1
        $hour_start = $today . ' ' . date("H", time() - 3600) . ':00:00';

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
                    foreach ($data1 as $item) {
                        if ($i == 0) {
                            preg_match_all('!\d+!', $item, $matches);
                            if (!empty($matches[0])) {
                                if ($matches[0][0] != 0)
                                {
                                    // id_department dodać orm
                                    $department_id = Department_info::where('pbx_id',$matches[0][0])->first();
                                    if(isset($department_id->id) && $department_id->id != null)
                                        $spreadsheet_data[$lp][$header_array[$i]] = $department_id->id;
                                    if($matches[0][0] == 12 || $matches[0][0] == 9)
                                    {
                                        if($matches[0][0] == 12)
                                        {
                                            $spreadsheet_data[$lp][$header_array[$i]] = 4;
                                        }else{
                                            $spreadsheet_data[$lp][$header_array[$i]] = -4;
                                        }
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
                            $spreadsheet_data[$lp]['hour'] = date('H:i');
                            $spreadsheet_data[$lp]['report_date'] = date('Y-m-d');
                            $spreadsheet_data[$lp]['is_send'] = 1;
                        }
                        $i++;
                    }
                }
                $lp++;
            }
            fclose($handle);
        }
        dd($spreadsheet_data);
        //array_pop($spreadsheet_data);


    }
}
