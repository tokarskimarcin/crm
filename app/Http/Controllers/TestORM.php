<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Request;


class TestORM extends Controller
{
    public function test() {
        $lp = 0;
        $dont_save = false;
        $header_array = array('pbx_id', 'login_time', 'count_private_pause', 'count_lesson_pause', 'received_calls', 'closed_arranged', 'closed_bilingual', 'away_contacts', 'succes', 'wrong_number', 'avg_time_pause', 'avg_time_wait_per_hour', 'avg_time_wait', 'avg_succes_per_hour', 'use_working_time', 'avg_decision_time', 'avg_delayed_time', 'report_date');
        if(!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";
        if (($handle = fopen("http://vc.e-pbx.pl/callcenter/api/statistic-report", "r")) !== FALSE) {
            while (($data1 = fgetcsv($handle, 1000, ";")) !== FALSE) {
                if ($lp > 1 && $lp <= 399)
                {
                    $dont_save = false;
                    $i = 0;
                    foreach ($data1 as $item)
                    {
                        if($i == 0)
                        {
                            preg_match_all('!\d+!', $item, $matches);
                            if(!empty($matches[0]))
                            {
                                if($matches[0][0] != 0)
                                    $spreadsheet_data[$lp][$header_array[$i]] = $matches[0][0];
                                else
                                    $dont_save = true;
                            }

                        }else if(!$dont_save)
                        {
                            if($item == '-' || $item == 'null')
                                $item = null;
                            if($i == 14)
                            {
                                $split = explode(" ",$item);
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

        DB::table('pbx_report_extension')->insert($spreadsheet_data);




    }

}
