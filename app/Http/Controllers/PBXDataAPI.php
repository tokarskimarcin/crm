<?php

namespace App\Http\Controllers;

use App\Department_info;
use App\PBXDKJTeam;
use Illuminate\Http\Request;

class PBXDataAPI extends Controller
{
    private $report_type_array = array('TEAM','TERMINAL','PRESENTATION');
    public function TeamDKJHourData()
    {

        $today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

        $hour_stop = $today . ' ' . '23:00:00'; //tutaj zmienic przy wydawaniu na produkcję na  date('H') - 1
        $hour_start = $today . ' ' . '00:00:00';

        $lp = 0;
        $spreadsheet_data = null;
        $dont_save = false;
        $report_type = 0;
        $department_id = null;
        $url = "https://vc.e-pbx.pl/callcenter/api/statistic-report?statType=30&groupType=" . $this->report_type_array[$report_type];
        //$url = "https://vc.e-pbx.pl/callcenter/api/statistic-report?statType=30&groupType=TEAM&date=2018-02-10";
        $header_array = array('department_info_id', 'online_consultant', 'success', 'consultant_without_check', 'count_all_check', 'count_good_check', 'count_bad_check');
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
                            if ($i == 1 || $i == 2 || $i == 3 || $i == 4 || $i == 5 || $i == 6) {
                                $spreadsheet_data[$lp][$header_array[$i]] = utf8_encode($item);
                            }

                            $spreadsheet_data[$lp]['hour'] = date('H').":00:00";
                            $spreadsheet_data[$lp]['report_date'] = date('Y-m-d');
                        }
                        $i++;
                    }
                }
                $lp++;
            }
            fclose($handle);
        }
        //dd($spreadsheet_data);
        PBXDKJTeam::insert($spreadsheet_data);

    }
}
