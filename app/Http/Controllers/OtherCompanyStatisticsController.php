<?php

namespace App\Http\Controllers;

use App\HourRepoerOtherCompany;
use App\PBXDKJTeamOtherCompany;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;
class OtherCompanyStatisticsController extends Controller
{



//Strona z raportem
// Wyswietlenie raportu godzinnego na stronie
    public function pageHourReportTelemarketing()
    {
        $data = $this::hourReportTelemarketing();
        return view('reportpage.otherCompanyReport.HourReportTelemarketing')
            ->with('reports', $data['reports'])
            ->with('hour', $data['hour'])
            ->with('date', $data['date'])
            ->with('last_reports', $data['last_reports']);
    }
    // Dane do raportu godzinnego Telemarketing
    private function hourReportTelemarketing()
    {
        $date = date('Y-m-d');
        $hour = date('H') . ':00:00'; //tutaj zmienic przy wydawaniu na produkcję na  date('H') - 1

        $reports = HourRepoerOtherCompany::where('report_date', '=', $date)
            ->where('hour', $hour)
            ->get();
        $last_reports = HourRepoerOtherCompany::where('report_date', '=', $date)
            ->where('hour', date('H')-1 . ':00:00')
            ->get();
        $data = [
            'hour' => $hour,
            'date' => $date,
            'reports' => $reports,
            'last_reports' => $last_reports
        ];
        return $data;
    }

    // Wyswietlenie raportu dziennego na stronie 'telemarketing'
    public function pageDayReportTelemarketing() {
        $data = $this::dayReportTelemarketing('today');
        return view('reportpage.otherCompanyReport.dayReportTelemarketing')
            ->with('date', $data['date'])
            ->with('reports', $data['reports']);
    }

    //dane do raportu dziennego telemarketing
    private function dayReportTelemarketing($type)
    {
        if ($type == 'today') {
            $date = date('Y-m-d');
        } else if ($type == 'yesterday') {
            $date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        }
        $reports = DB::table('hour_report_other_company')
            ->select(DB::raw(
                'SUM(call_time) as sum_call_time,
                  AVG(average) as avg_average,
                  SUM(success) as sum_success,
                  AVG(wear_base) as avg_wear_base,
                  hour_report_other_company.department_info_id as id
                   '))
            ->whereIn('hour_report_other_company.id', function($query) use($date){
                $query->select(DB::raw(
                    'MAX(hour_report_other_company.id)'
                ))
                    ->from('hour_report_other_company')
                    ->where('call_time', '!=',0)
                    ->where('report_date', '=',$date)
                    ->groupBy('department_info_id');
            })
            ->groupBy('hour_report_other_company.department_info_id')
            ->get();
        $pbx_dkj_data = DB::table('pbx_dkj_team_other_company')
            ->select(DB::raw('
             (SUM(count_bad_check) * 100) / SUM(count_all_check) as janky_proc,
             pbx_dkj_team_other_company.department_info_id as id
            '))
            ->whereIn('pbx_dkj_team_other_company.id', function($query) use($date){
                $query->select(DB::raw(
                    'MAX(pbx_dkj_team_other_company.id)'
                ))
                    ->from('pbx_dkj_team_other_company')
                    ->where('report_date', '=',$date)
                    ->groupBy('department_info_id','report_date');
            })
            ->groupBy('pbx_dkj_team_other_company.department_info_id')
            ->get();

        $reports_with_dkj = $reports->map(function($item) use ($pbx_dkj_data) {
            $info_with_janky = $pbx_dkj_data->where('id', '=', $item->id)->first();
            $item->janki = $info_with_janky != null ? $info_with_janky->janky_proc : 0;
            return $item;
        });

        $data = [
            'date' => $date,
            'reports' => $reports_with_dkj,
        ];
        return $data;
    }


    // Wyswietlenie raportu tygodniowego na stronie 'telemarketing'
    public function pageWeekReportTelemarketing() {
        $data = $this::weekReportTelemarketing();
//
        return view('reportpage.otherCompanyReport.WeekReportTelemarketing')
            ->with('reports', $data['reports'])
            ->with('date_start', $data['date_start'])
            ->with('date_stop', $data['date_stop']);
    }
    // Przygotowanie danych do raportu tygodniowego telemarketing
    private function weekReportTelemarketing()
    {
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

        $reports = DB::table('hour_report_other_company')
            ->select(DB::raw(
                'SUM(call_time)/count(`call_time`) as sum_call_time,
                  SUM(success)/sum(`hour_time_use`) as avg_average,
                  SUM(success) as sum_success,
                  sum(`hour_time_use`) as hour_time_use,
                  SUM(wear_base)/count(`call_time`) as avg_wear_base,
                  hour_report_other_company.department_info_id as id
                   '))
            ->whereIn('hour_report_other_company.id', function($query) use($date_start, $date_stop){
                $query->select(DB::raw(
                    'MAX(hour_report_other_company.id)'
                ))
                    ->from('hour_report_other_company')
                    ->whereBetween('report_date', [$date_start, $date_stop])
                    ->where('call_time', '!=',0)
                    ->groupBy('department_info_id','report_date');
            })
            ->groupBy('hour_report_other_company.department_info_id')
            ->get();

        $pbx_dkj_data = DB::table('pbx_dkj_team_other_company')
            ->select(DB::raw('
             (SUM(count_bad_check) * 100) / SUM(count_all_check) as janky_proc,
             pbx_dkj_team_other_company.department_info_id as id
            '))
            ->whereIn('pbx_dkj_team_other_company.id', function($query) use($date_start, $date_stop){
                $query->select(DB::raw(
                    'MAX(pbx_dkj_team_other_company.id)'
                ))
                    ->from('pbx_dkj_team_other_company')
                    ->whereBetween('report_date', [$date_start, $date_stop])
                    ->groupBy('department_info_id','report_date');
            })
            ->groupBy('pbx_dkj_team_other_company.department_info_id')
            ->get();
        $reports_with_dkj = $reports->map(function($item) use ($pbx_dkj_data) {
            $info_with_janky = $pbx_dkj_data->where('id', '=', $item->id)->first();
            $item->janki = $info_with_janky != null ? $info_with_janky->janky_proc : 0;
            return $item;
        });

        $data = [
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'reports' => $reports_with_dkj,
        ];

        return $data;
    }

// wyswietlenie raportu miesiecznego
    public function pageMonthReportTelemarketing()
    {
        $month = date('m');
        $year = date('Y');
        $data = $this::monthReportTelemarketing($month,$year);
        return view('reportpage.otherCompanyReport.MonthReportTelemarketing')
            ->with('month_name', $data['month_name'])
            ->with('result_days', $data['result_days'])
            ->with('reports', $data['reports']);
    }

    // przygotowanie danych do miesiecznego raportu telemarketingu
    private function monthReportTelemarketing($month,$year){
        //Pobranie danych na temat ilości przepracowanych dni w danym miesiącu
        $check_working_days = DB::table('hour_report_other_company')
            ->select(DB::raw('
                DISTINCT(report_date),
                hour_report_other_company.department_info_id
            '))
            ->whereBetween('report_date', [$year . '-' . $month . '-01', $year . '-' . $month . '-31'])
            ->where('average', '!=', 0)
            ->get();


        //Zdefiniowanie głównej tablicy do przekazania do widoku
        $result_days = array();
        //Zdefiniowanie tablicy tymczasowej
        $list = array();

        //Pogrupowanie danych ze względu na departamenty
        $departments_keys = $check_working_days->groupBy('department_info_id');
        //ilteracja po poszczegolnych oddziałach
        foreach($departments_keys as $key => $value) {

            //Iteracja 31 razy
            for($d = 1; $d <= 31; $d++)
            {
                //Zdefioniowanie czasu
                $time=mktime(12, 0, 0, $month, $d, $year);
                //przepierdolenie czasu do czytelnego formatu
                $time_format = date('Y-m-d', $time);

                //Flaga (ustala czy oddział pracował w danym dniu i dzien się wlicza)
                $add_date = false;

                //Sprawdzenie czy dzien był dla oddziału pracujący
                foreach ($value as $key2 => $value2) {
                    if($value2->report_date == $time_format) {
                        $add_date = true;
                        break;
                    }
                }

                //Jezeli dzien miesci się w zakresie dni z danego miesiaca i dzien był dla oddziału pracujący
                if (date('m', $time)==$month && $add_date == true) {
                    //dodanie dnia do listy
                    $list[$key][]=date('N', $time);
                }
            }
        }

        //Sumowanie dni dla poszczegolnych oddziałów (z podziałem na dni zwykłe/weekendowe)
        foreach($list as $key => $item) {
            $normal_day = 0;
            $weekend_day = 0;

            foreach($item as $value) {
                //sprawdzenie typu dnia
                if ($value == '6' || $value == '7') {
                    $weekend_day++;
                } else {
                    $normal_day++;
                }
            }

            $result_days[$key]['normal_day'] = $normal_day;
            $result_days[$key]['week_day'] = $weekend_day;
        }
        $month_name = $this::monthReverseName($month);
        $date = $year . "-" . $month . "-%";
        $month = date('Y') . '-' . $month . '%';

        $reports = DB::table('hour_report_other_company')
            ->select(DB::raw(
                'SUM(call_time)/count(`call_time`) as sum_call_time,
                       SUM(success)/sum(`hour_time_use`) as avg_average,
                       sum(`hour_time_use`) as hour_time_use,
                      SUM(success) as sum_success,
                      SUM(wear_base)/count(`call_time`) as avg_wear_base,
                      SUM(janky_count)/count(`call_time`)  as sum_janky_count,
                      department_info_id as id
                     '))
            ->whereIn('hour_report_other_company.id', function($query) use($month){
                $query->select(DB::raw(
                    'MAX(hour_report_other_company.id)'
                ))
                    ->from('hour_report_other_company')
                    ->where('report_date', 'like', $month)
                    ->groupBy('department_info_id','report_date');
            })
            ->groupBy('hour_report_other_company.department_info_id')
            ->get();

        $pbx_dkj_data = DB::table('pbx_dkj_team_other_company')
            ->select(DB::raw('
             (SUM(count_bad_check) * 100) / SUM(count_all_check) as janky_proc,
             pbx_dkj_team_other_company.department_info_id as id
            '))
            ->whereIn('pbx_dkj_team_other_company.id', function($query) use($month){
                $query->select(DB::raw(
                    'MAX(pbx_dkj_team_other_company.id)'
                ))
                    ->from('pbx_dkj_team_other_company')
                    ->where('report_date', 'like', $month)
                    ->groupBy('department_info_id','report_date');
            })
            ->groupBy('pbx_dkj_team_other_company.department_info_id')
            ->get();

        $reports_with_dkj = $reports->map(function($item) use ($pbx_dkj_data) {
            $info_with_janky = $pbx_dkj_data->where('id', '=', $item->id)->first();
            $item->janki = $info_with_janky != null ? $info_with_janky->janky_proc : 0;
            return $item;
        });
        $data = [
            'month_name' => $month_name,
            'reports' => $reports_with_dkj,
            'result_days' => $result_days
            //'days_list' => $days_list,
        ];
        return $data;
    }
    //zwracanie nazwy miesiąca którego dotyczy statystyka
    private function monthReverseName($month) {
        $month_names = array( 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień' );
        $month -= 1;
        $month = ($month < 0) ? 11 : $month ;
        return $month_names[$month];
    }

    private function monthReverse($month) {
        $month -= 1;
        return ($month < 1) ? 12 : $month ;
    }



    /*
     * DKJ
     */
    public function pageHourReportDKJ()
    {
        $data = $this::hourReportDkj_PBX_READY();// Gotowe na pbx
        return view('reportpage.otherCompanyReport.hourReportDkj')
            ->with('date', date('H') . ':00:00')
            ->with('reports', $data['reports']);
    }
    // Przygotowanie danych do raportu godzinnego DKJ
    private function hourReportDkj_PBX_READY() {

        $date = date('Y-m-d');
        $hour = date('H') . ':00:00'; //tutaj zmienic przy wydawaniu na produkcję na  date('H') - 1

        $reports = PBXDKJTeamOtherCompany::where('report_date', '=', $date)
            ->where('hour', $hour)
            ->get();
        $data = [
            'hour' => $hour,
            'date' => $date,
            'reports' => $reports
        ];
        return $data;
    }

    public function pageDayReportDKJ() {
        $data = $this->dayReportDkjData('today');
        return view('reportpage.otherCompanyReport.DayReportDkj')
            ->with('today', $data['today'])
            ->with('dkj', $data['dkj']);
    }

    private function dayReportDkjData($type) {
        if ($type == 'today') {
            $date = date('Y-m-d');
        } else if ($type == 'yesterday') {
            $date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        }

        $dkj = DB::table('pbx_dkj_team_other_company')
            ->select(DB::raw(
                '
                  SUM(success) as success,
                  sum(count_all_check) as sum_all_talks,
                  sum(count_good_check) as sum_correct_talks,
                  sum(count_bad_check) as sum_janky
                   '))
            ->whereIn('pbx_dkj_team_other_company.id', function($query) use($date){
                $query->select(DB::raw(
                    'MAX(pbx_dkj_team_other_company.id)'
                ))
                    ->from('pbx_dkj_team_other_company')
                    ->where('report_date', '=',$date)
                    ->groupBy('department_info_id');
            })
            ->groupBy('pbx_dkj_team_other_company.department_info_id')
            ->get();

        $data = [
            'dkj' => $dkj,
            'today' => $date
        ];
        return $data;
    }

    //wyswietlanie danych raportu tygodniowego dla DKJ
    public function pageWeekReportDKJ() {
        $data = $this->weekReportDkjData();

        return view('reportpage.otherCompanyReport.WeekReportDkj')
            ->with('date_start', $data['date_start'])
            ->with('date_stop', $data['date_stop'])
            ->with('dkj', $data['dkj']);
    }

    //przygotowanie danych do raportu tygodniowego dkj
    private function weekReportDkjData() {
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));


        $dkj = DB::table('pbx_dkj_team_other_company')
            ->select(DB::raw(
                'SUM(count_all_check) as sum_all_talks,
                SUM(count_good_check) as sum_correct_talks,
                SUM(count_bad_check) as sum_janky,
                SUM(success) as success
                   '))
            ->whereIn('pbx_dkj_team_other_company.id', function($query) use($date_start, $date_stop){
                $query->select(DB::raw(
                    'MAX(pbx_dkj_team_other_company.id)'
                ))
                    ->from('pbx_dkj_team_other_company')
                    ->whereBetween('report_date', [$date_start, $date_stop])
                    ->groupBy('department_info_id','report_date');
            })
            ->groupBy('pbx_dkj_team_other_company.department_info_id')
            ->get();

//            dd($dkj);
        $data = [
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'dkj' => $dkj
        ];
        return $data;
    }

    //wyswietlanie raoprtu miesiecznego pracownicy dkj
    public function pageMonthReportDKJ(){
        $data = $this->MonthReportDkjData(0);

        return view('reportpage.otherCompanyReport.MonthReportDkj')
            ->with('month_name', $data['month_name'])
            ->with('dkj', $data['dkj']);
    }

    //przygotowanie danych do raportu miesięcznego dkj
    //type - 0 bierzący miesiac, 1 poprzedni
    private function MonthReportDkjData($type) {
        $month = $this->monthReverse(date('m'));
        $year = date('Y');
        if ($month < 10) {
            $month = '0' . $month;
        }
        if ($month == 12) {
            $year -= 1;
        }
        $selected_date = $year . '-' . $month . '%';
        if($type == 0)
        {
            $month_ini = new DateTime("first day of this month");
            $date_start = $month_ini->format('Y-m-d');
            $month_end = new DateTime("last day of this month");
            $date_stop = $month_end->format('Y-m-d');
        }else{
            $month_ini = new DateTime("first day of last month");
            $date_start = $month_ini->format('Y-m-d');
            $month_end = new DateTime("last day of last month");
            $date_stop = $month_end->format('Y-m-d');
        }

        $dkj = DB::table('pbx_dkj_team_other_company')
            ->select(DB::raw(
                'SUM(count_all_check) as sum_all_talks,
                SUM(count_good_check) as sum_correct_talks,
                SUM(count_bad_check) as sum_janky,
                 SUM(success) as success
                   '))
            ->whereIn('pbx_dkj_team_other_company.id', function($query) use($date_start, $date_stop){
                $query->select(DB::raw(
                    'MAX(pbx_dkj_team_other_company.id)'
                ))
                    ->from('pbx_dkj_team_other_company')
                    ->whereBetween('report_date', [$date_start, $date_stop])
                    ->groupBy('department_info_id','report_date');
            })
            ->groupBy('pbx_dkj_team_other_company.department_info_id')
            ->get();

        $data = [
            'month_name' => $this->monthReverseName($month),
            'date_start' => $date_start,
            'date_stop' => $date_stop,
            'dkj' => $dkj
        ];

        return $data;
    }



    public  function getUserToSendMessage(){
        $user = User::whereIn('id',[1364,6,11])->get();
        $szczesny = new User();
        $szczesny->username = 'bartosz.szczesny@veronaconsulting.pl';
        $szczesny->first_name = 'Bartosz';
        $szczesny->last_name = 'Szczęsny';
        $user = $user->push($szczesny);
        return $user;
    }

    // MAILE
    // Mail do raportu godzinnego telemarketing
    public function MailhourReportTelemarketing() {
        $data = $this::hourReportTelemarketing();
        $title = 'Raport godzinny telemarketing Gniezno ' . date('Y-m-d');
        $user = $this::getUserToSendMessage();
        $this->sendMailByVerona('otherCompanyMail.hourReportTelemarketing', $data, $title,$user);
    }
    //Mail do raportu Tygodniowego Telemarketing
    public function MailweekReportTelemarketing() {
        $data = $this::weekReportTelemarketing();
        $user = $this::getUserToSendMessage();
        $title = 'Raport tygodniowy telemarketing Gniezno ';
        $this->sendMailByVerona('otherCompanyMail.weekReportTelemarketing', $data, $title,$user);
    }
    // Wysłanie maila z raportem miesiecznym
    public function MailmonthReportTelemarketing() {
        $month = date('m') - 1;
        if ($month < 10) {
            $month = '0' . $month;
        }
        $year = date('Y');

        if ($month == 12) {
            $year -= 1;
        }
        $data = $this::monthReportTelemarketing($month,$year);
        $user = $this::getUserToSendMessage();
        $title = 'Raport miesięczny telemarketing Gniezno ';
        $this->sendMailByVerona('otherCompanyMail.monthReportTelemarketing', $data, $title,$user);
    }
    public function MailDayReportTelemarketing() {
        $data = $this::dayReportTelemarketing('yesterday');
        $user = $this::getUserToSendMessage();
        $title = 'Raport dzienny telemarketing Gniezno '.date("d.m.Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        $this->sendMailByVerona('otherCompanyMail.dayReportTelemarketing', $data, $title,$user);
    }
    // Mail do godzinnego raportu DKJ
    public function MailhourReportDkj()
    {
        //$data = $this::hourReportDkj();
        $data = $this::hourReportDkj_PBX_READY(); //Gotowe na pbx
        $title = 'Raport godzinny DKJ Gniezno ' . date('Y-m-d');
        $user = $this::getUserToSendMessage();
        $this->sendMailByVerona('otherCompanyMail.hourReportDkj', $data, $title,$user);
    }

    public function dayReportDkj() {
        $data = $this->dayReportDkjData('yesterday');
        $user = $this::getUserToSendMessage();
        $title = 'Raport dzienny DKJ Gniezno '.date("d.m.Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
        $this->sendMailByVerona('otherCompanyMail.dayReportDkj', $data, $title,$user);
    }

    //wysyłanie email (raport tygodniowy dkj)
    public function MailWeekReportDkj() {
        $data = $this->weekReportDkjData();
        $user = $this::getUserToSendMessage();
        $title = 'Raport tygodniowy DKJ Gniezno ' . $data['date_start'] . ' - ' . $data['date_stop'];
        $this->sendMailByVerona('otherCompanyMail.weekReportDkj', $data, $title,$user);
    }

    public function monthReportDkj() {
        $data = $this->MonthReportDkjData(1);
        $user = $this::getUserToSendMessage();
        $title = 'Raport miesięczny DKJ Gniezno ';
        $this->sendMailByVerona('otherCompanyMail.monthReportDkj', $data, $title,$user);
    }



    /******** Główna funkcja do wysyłania emaili*************/
    /*
    * $mail_type - jaki mail ma być wysłany - typ to nazwa ścieżki z web.php
    * $data - $dane przekazane z metody
    *
    */

    private function sendMailByVerona($mail_type, $data, $mail_title, $default_users = null) {
        if ($default_users !== null) {
            $email = [];
            $mail_type_pom = $mail_type;
            $mail_without_folder = explode(".",$mail_type);
            // podfoldery
            $mail_type = $mail_without_folder[count($mail_without_folder)-1];
            $mail_type2 = ucfirst($mail_type);
            $mail_type2 = 'page' . $mail_type2;
//            dd($mail_type2);
            $accepted_users = $default_users;
//            dd(gettype($accepted_users));
        } else {
            $email = [];
            $mail_type_pom = $mail_type;
            $mail_without_folder = explode(".",$mail_type);
            // podfoldery
            $mail_type = $mail_without_folder[count($mail_without_folder)-1];
            $mail_type2 = ucfirst($mail_type);
            $mail_type2 = 'page' . $mail_type2;
            $accepted_users = DB::table('users')
                ->select(DB::raw('
            users.first_name,
            users.last_name,
            users.username,
            users.email_off
            '))
                ->join('privilage_relation', 'privilage_relation.user_type_id', '=', 'users.user_type_id')
                ->join('links', 'privilage_relation.link_id', '=', 'links.id')
                ->where('links.link', '=', $mail_type2)
                ->where('users.status_work', '=', 1)
                ->where('users.id', '!=', 4592) // tutaj szczesna
                ->get();

            $szczesny = new User();
            $szczesny->username = 'bartosz.szczesny@veronaconsulting.pl';
            $szczesny->first_name = 'Bartosz';
            $szczesny->last_name = 'Szczęsny';
            $accepted_users->push($szczesny);
        }

//    $accepted_users = [
//        'cytawa.verona@gmail.com',
//        'jarzyna.verona@gmail.com'
//    ];

//        $mail_type = $mail_type_pom;
//     Mail::send('mail.' . $mail_type, $data, function($message) use ($accepted_users, $mail_title)
//     {
//        $message->from('noreply.verona@gmail.com', 'Verona Consulting');
//        foreach ($accepted_users as $key => $user) {
//          if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
//              $message->to($user)->subject($mail_title);
//          }
//        }
//     });

        $mail_type = $mail_type_pom;
        /* UWAGA !!! ODKOMENTOWANIE TEGO POWINNO ZACZĄC WYSYŁAĆ MAILE*/
        Mail::send('mail.' . $mail_type, $data, function($message) use ($accepted_users, $mail_title)
        {
            $message->from('noreply.verona@gmail.com', 'Verona Consulting');
            foreach($accepted_users as $user) {
//               dd($user); -> zwraca ID tylko
                if (filter_var($user->username, FILTER_VALIDATE_EMAIL)) {
                    $message->to($user->username, $user->first_name . ' ' . $user->last_name)->subject($mail_title);
                }
                if (filter_var($user->email_off, FILTER_VALIDATE_EMAIL)) {
                    $message->to($user->email_off, $user->first_name . ' ' . $user->last_name)->subject($mail_title);
                }
            }
        });
    }
}
