<?php

namespace App\Http\Controllers;

use App\Department_info;
use App\PBXDKJTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    // pobierania informacji dla potwierdzeń (czas na rekord)
    public function TimeOnRecordData()
    {
        $lp = 0;
        $spreadsheet_data = null;
        $report_type = 2;
        $department_id = null;
        $url = "https://vc.e-pbx.pl/callcenter/api/statistic-report?statType=27&groupType=" . $this->report_type_array[$report_type];
        $header_array = array(0=>'campain',1=>'team_name', 2=>'consultant_name', 13=>'time_on_record', 14=>'time_call');
        if (!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";
        if (($handle = fopen($url, "r")) !== FALSE) {
            while (($data1 = fgetcsv($handle, 1000, ";")) !== FALSE) {
                if ($lp > 2) {
                    $i = 0;
                    foreach ($data1 as $item) {
                            if ($item == '-' || $item == 'null')
                                $item = 0;
                            if ($i == 0 || $i == 1 || $i == 2 || $i == 13 || $i == 14) {
                                $spreadsheet_data[$lp][$header_array[$i]] = $this::w1250_to_utf8($item);  //utf8_encode($item);
                            }
                            $spreadsheet_data[$lp]['hour'] = date('H').":00:00";
                            $spreadsheet_data[$lp]['report_date'] = date('Y-m-d');
                        $i++;
                    }
                }
                $lp++;
            }
            fclose($handle);
        }
        DB::table('pbx_time_record')->insert($spreadsheet_data);

    }

    function w1250_to_utf8($text) {
        // map based on:
        // http://konfiguracja.c0.pl/iso02vscp1250en.html
        // http://konfiguracja.c0.pl/webpl/index_en.html#examp
        // http://www.htmlentities.com/html/entities/
        $map = array(
            chr(0x8A) => chr(0xA9),
            chr(0x8C) => chr(0xA6),
            chr(0x8D) => chr(0xAB),
            chr(0x8E) => chr(0xAE),
            chr(0x8F) => chr(0xAC),
            chr(0x9C) => chr(0xB6),
            chr(0x9D) => chr(0xBB),
            chr(0xA1) => chr(0xB7),
            chr(0xA5) => chr(0xA1),
            chr(0xBC) => chr(0xA5),
            chr(0x9F) => chr(0xBC),
            chr(0xB9) => chr(0xB1),
            chr(0x9A) => chr(0xB9),
            chr(0xBE) => chr(0xB5),
            chr(0x9E) => chr(0xBE),
            chr(0x80) => '&euro;',
            chr(0x82) => '&sbquo;',
            chr(0x84) => '&bdquo;',
            chr(0x85) => '&hellip;',
            chr(0x86) => '&dagger;',
            chr(0x87) => '&Dagger;',
            chr(0x89) => '&permil;',
            chr(0x8B) => '&lsaquo;',
            chr(0x91) => '&lsquo;',
            chr(0x92) => '&rsquo;',
            chr(0x93) => '&ldquo;',
            chr(0x94) => '&rdquo;',
            chr(0x95) => '&bull;',
            chr(0x96) => '&ndash;',
            chr(0x97) => '&mdash;',
            chr(0x99) => '&trade;',
            chr(0x9B) => '&rsquo;',
            chr(0xA6) => '&brvbar;',
            chr(0xA9) => '&copy;',
            chr(0xAB) => '&laquo;',
            chr(0xAE) => '&reg;',
            chr(0xB1) => '&plusmn;',
            chr(0xB5) => '&micro;',
            chr(0xB6) => '&para;',
            chr(0xB7) => '&middot;',
            chr(0xBB) => '&raquo;',
        );
        return html_entity_decode(mb_convert_encoding(strtr($text, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');
    }

}
