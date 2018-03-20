<?php

namespace App\Http\Controllers;

use App\Department_info;
use App\PBXDKJTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pbx_report_extension;

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
        $header_array = array('department_info_id','success','count_all_check','count_good_check','count_bad_check','','','online_consultant',  'consultant_without_check','all_jaky_disagreement','','good_jaky_disagreement');
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
                            if ($i == 1 || $i == 2 || $i == 3 || $i == 4 || $i == 7 || $i == 8 ||$i == 9 ||$i == 11 ) {
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

    /**
     * Pobranie danych dla raportu tygodniowego konsultanci
     */
    public function PBXReportExtension() {
        $department_id = null;
        $url = "https://vc.e-pbx.pl/callcenter/api/statistic-report?statType=23&groupType=TERMINAL";

        if (!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";
        if (($handle = fopen($url, "r")) !== FALSE) {

            $row = 0;
            $data_to_insert = [];
            while (($rowData = fgetcsv($handle, 1000, ";")) !== false) {
                if ($row > 2) {
                    $temp_key = null;
                    foreach ($rowData as $key => $rowItem) {
                        $removeData = false;
                        if ($key == 0) {
                            $pbx_number_array = explode(' ', $rowItem);
                            if (count($pbx_number_array) > 1) {
                                $pbx_number = $pbx_number_array[count($pbx_number_array) - 1];
                                $temp_key = $pbx_number;
                                $data_to_insert[$temp_key]['pbx_id'] = $pbx_number;
                            }
                        } else if ($key == 1) {
                            $data_to_insert[$temp_key]['average'] = $rowItem;
                        } else if ($key == 2) {
                            $data_to_insert[$temp_key]['success'] = $rowItem;
                        } else if ($key == 3) {
                            $data_to_insert[$temp_key]['count_private_pause'] = floatval($rowItem);
                        } else if ($key == 4) {
                            $data_to_insert[$temp_key]['count_lesson_pause'] = floatval($rowItem);
                        } else if ($key == 5) {
                            $data_to_insert[$temp_key]['base_use_proc'] = floatval($rowItem);
                        } else if ($key == 6) {
                            $data_to_insert[$temp_key]['call_time_proc'] = floatval($rowItem);
                        } else if ($key == 7) {
                            // Ten wiersz jest nieistotny
                        } else if ($key == 8) {
                            $data_to_insert[$temp_key]['login_time'] = $rowItem;
                        } else if ($key == 9) {
                            $data_to_insert[$temp_key]['dkj_proc'] = floatval($rowItem);
                        } else if ($key == 10) {
                            $data_to_insert[$temp_key]['received_calls'] = intval($rowItem);

                            $data_to_insert[$temp_key]['report_date'] = date('Y-m-d');
                            $data_to_insert[$temp_key]['report_hour'] = date('H:') . '00:00';

                            /**
                             * Sumowanie danych
                             */
                            //zliczenie czasu przerw
                            $sum_proc_pause = $data_to_insert[$temp_key]['count_private_pause'] + $data_to_insert[$temp_key]['count_lesson_pause'];
                            $time_sum_sec_array = explode(":", $data_to_insert[$temp_key]['login_time']);
                            $time_sum_sec = (($time_sum_sec_array[0] * 3600) + ($time_sum_sec_array[1] * 60) + $time_sum_sec_array[2]);
                            $data_to_insert[$temp_key]['time_pause'] = intval(($time_sum_sec * $sum_proc_pause) / 100);

                            //Tutaj sprawdzenie czy dodajemy ten wpis

                            if ($data_to_insert[$temp_key]['login_time'] == '00:00:00') {
                                $removeData = true;
                            }
                            if (!isset($data_to_insert[$temp_key]['pbx_id'])) {
                                $removeData = true;
                            }
                        }

                        if ($removeData !== null && $removeData === true) {
                            unset($data_to_insert[$temp_key]);
                        }
                    }
                }
                $row++;
            }
            Pbx_report_extension::insert($data_to_insert);
            fclose($handle);
        }
    }
}
