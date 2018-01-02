<?php

namespace App\Http\Controllers;

use App\CsvReader;
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
//        $reader = new CsvReader();
//        $reader->pbx_report_extension(0);
            $report_type = 0;
            $lp = 0;
            $dont_save = false;
            $url = "https://vc.e-pbx.pl/callcenter/api/statistic-report?statType=23&groupType=".$this->report_type_array[$report_type];
            $header_array = array('department_info_id', 'user_id', 'hour', 'report_date', 'is_send', 'average', 'success', 'employee_count', 'janky_count', 'wear_base', 'call_time');
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
                                        // id_department
                                        $spreadsheet_data[$lp][$header_array[$i]] = $matches[0][0];
                                    }
                                    else
                                        $dont_save = true;
                                }

                            } else if (!$dont_save) {
                                if ($item == '-' || $item == 'null')
                                    $item = 0;
                                if ($i =! 2 && $i =! 1 && $i =! 7 ) {
                                    $split = explode(" ", $item);
                                    $item = $split[0];
                                }
                                $spreadsheet_data[$lp][$header_array[$i]] = utf8_encode($item);
                            }
                            $i++;
                        }
                    }
                    $lp++;
                }
                fclose($handle);
            }

        array_pop($spreadsheet_data);

            //DB::table('pbx_report_extension')->insert($spreadsheet_data);
    }
}
