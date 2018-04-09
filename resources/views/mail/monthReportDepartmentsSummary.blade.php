<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">PODSUMOWANIE ODDZIAŁÓW</font></td>
        <td colspan="3" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20; width: 15%;">ODDZIAŁ</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">KATEGORIA</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">I TYDZIEŃ</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">II TYDZIEŃ</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">III TYDZIEŃ</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">IV TYDZIEŃ</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">MIESIĄC</td>

    </tr>
    </thead>
    <tbody>

    @foreach($data as $department)

        @php

            $week_one_time_sum = $department[0]['data']->map(function($item) {  return $item->day_time_sum; });
            $week_two_time_sum = $department[1]['data']->map(function($item) { return $item->day_time_sum; });
            $week_three_time_sum = $department[2]['data']->map(function($item) { return $item->day_time_sum; });
            $week_four_time_sum = $department[3]['data']->map(function($item) { return $item->day_time_sum; });



            /**
            Usunięcie zerowych wartości
            **/
            $week_one_time_sum = $week_one_time_sum->filter(function($value, $key) {
            return  $value != null;
            });

            $week_two_time_sum = $week_two_time_sum->filter(function($value, $key) {
            return  $value != null;
            });

            $week_three_time_sum = $week_three_time_sum->filter(function($value, $key) {
            return  $value != null;
            });

            $week_four_time_sum = $week_four_time_sum->filter(function($value, $key) {
            return  $value != null;
            });


            $week_one_success_sum = $department[0]['data']->map(function($item) { return $item->success; });
            $week_two_success_sum = $department[1]['data']->map(function($item) { return $item->success; });
            $week_three_success_sum = $department[2]['data']->map(function($item) { return $item->success; });
            $week_four_success_sum = $department[3]['data']->map(function($item) { return $item->success; });

            $week_one_call_time = $department[0]['data']->map(function($item) { return $item->hour_time_use * ($item->call_time / 100); });
            $week_two_call_time = $department[1]['data']->map(function($item) { return $item->hour_time_use * ($item->call_time / 100); });
            $week_three_call_time = $department[2]['data']->map(function($item) { return $item->hour_time_use * ($item->call_time / 100); });
            $week_four_call_time = $department[3]['data']->map(function($item) { return $item->hour_time_use * ($item->call_time / 100); });

            $week_one_janky_sum = $department[0]['data']->map(function($item) { return $item->success * ($item->janky_count / 100); });
            $week_two_janky_sum = $department[1]['data']->map(function($item) { return $item->success * ($item->janky_count / 100); });
            $week_three_janky_sum = $department[2]['data']->map(function($item) { return $item->success * ($item->janky_count / 100); });
            $week_four_janky_sum = $department[3]['data']->map(function($item) { return $item->success * ($item->janky_count / 100); });

            $week_one_janky_proc = ($week_one_success_sum->sum() > 0) ? round(($week_one_janky_sum->sum() / $week_one_success_sum->sum()) * 100, 2) : 0 ;
            $week_two_janky_proc = ($week_two_success_sum->sum() > 0) ? round(($week_two_janky_sum->sum() / $week_two_success_sum->sum()) * 100, 2) : 0 ;
            $week_three_janky_proc = ($week_three_success_sum->sum() > 0) ? round(($week_three_janky_sum->sum() / $week_three_success_sum->sum()) * 100, 2) : 0 ;
            $week_four_janky_proc = ($week_four_success_sum->sum() > 0) ? round(($week_four_janky_sum->sum() / $week_four_success_sum->sum()) * 100, 2) : 0 ;

            $week_one_avg = ($week_one_time_sum->avg() > 0) ? round(($week_one_success_sum->sum() / $week_one_time_sum->sum()), 2) : 0 ;
            $week_two_avg = ($week_two_time_sum->avg() > 0) ? round(($week_two_success_sum->sum() / $week_two_time_sum->sum()), 2) : 0 ;
            $week_three_avg = ($week_three_time_sum->avg() > 0) ? round(($week_three_success_sum->sum() / $week_three_time_sum->sum()), 2) : 0 ;
            $week_four_avg = ($week_four_time_sum->avg() > 0) ? round(($week_four_success_sum->sum() / $week_four_time_sum->sum()), 2) : 0 ;

            $week_one_time_call_avg = ($week_one_time_sum->sum() > 0) ? round(($week_one_call_time->sum() / $week_one_time_sum->sum() * 100), 2) : 0 ;
            $week_two_time_call_avg = ($week_two_time_sum->sum() > 0) ? round(($week_two_call_time->sum() / $week_two_time_sum->sum() * 100), 2) : 0 ;
            $week_three_time_call_avg = ($week_three_time_sum->sum() > 0) ? round(($week_three_call_time->sum() / $week_three_time_sum->sum() * 100), 2) : 0 ;
            $week_four_time_call_avg = ($week_four_time_sum->sum() > 0) ? round(($week_four_call_time->sum() / $week_four_time_sum->sum() * 100), 2) : 0 ;

            $month_sum_rbh = $week_one_time_sum->merge($week_two_time_sum);
            $month_sum_rbh = $month_sum_rbh->merge($week_three_time_sum);
            $month_sum_rbh = $month_sum_rbh->merge($week_four_time_sum);

            $month_sum_success = $week_one_success_sum->merge($week_two_success_sum);
            $month_sum_success = $month_sum_success->merge($week_three_success_sum);
            $month_sum_success = $month_sum_success->merge($week_four_success_sum);

            $month_sum_call_time = $week_one_call_time->merge($week_two_call_time);
            $month_sum_call_time = $month_sum_call_time->merge($week_three_call_time);
            $month_sum_call_time = $month_sum_call_time->merge($week_four_call_time);

            $month_sum_janky = $week_one_janky_sum->merge($week_two_janky_sum);
            $month_sum_janky = $month_sum_janky->merge($week_three_janky_sum);
            $month_sum_janky = $month_sum_janky->merge($week_four_janky_sum);

            $month_avg = ($month_sum_rbh->sum() > 0) ? round($month_sum_success->sum() / $month_sum_rbh->sum(), 2) : 0 ;
            $month_call_time_avg = ($month_sum_rbh->sum() > 0) ? round(($month_sum_call_time->sum() / $month_sum_rbh->sum()) * 100, 2) : 0 ;

            $week_goals = [];
            foreach ($department as $week) {
                $dep_aim = $department['department_info']->dep_aim;
                $dep_aim_week = $department['department_info']->dep_aim_week;
                if (is_array($week)) {


                    $week_goal = $week['data']->map(function($day) use ($dep_aim, $dep_aim_week) {
                    if($day->day_time_sum != 0)
                        return (date('N', strtotime($day->report_date)) < 6) ? $dep_aim : $dep_aim_week ;
                    });
                    $week_goals[] = $week_goal->sum();
                }
            }

            $week_goals = collect($week_goals);

            $week_one_goal_proc = ($week_goals[0] > 0) ? round(($week_one_success_sum->sum() / $week_goals[0]) * 100, 2) : 0 ;
            $week_two_goal_proc = ($week_goals[1] > 0) ? round(($week_two_success_sum->sum() / $week_goals[1]) * 100, 2) : 0 ;
            $week_three_goal_proc = ($week_goals[2] > 0) ? round(($week_three_success_sum->sum() / $week_goals[2]) * 100, 2) : 0 ;
            $week_four_goal_proc = ($week_goals[3] > 0) ? round(($week_four_success_sum->sum() / $week_goals[3]) * 100, 2) : 0 ;
            $month_goal_proc = ($week_goals->sum() > 0) ? round(($month_sum_success->sum() / $week_goals->sum()) * 100, 2) : 0 ;

            $month_janky_proc = ($month_sum_success->sum() > 0) ? round(($month_sum_janky->sum() / $month_sum_success->sum()) * 100, 2) : 0 ;



        @endphp

        <tr>
            <td rowspan="5" style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $department['department_info']->departments->name . ' ' . $department['department_info']->department_type->name }}</b></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>ŚREDNIA</b></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_one_time_sum->sum() == 0 ? '0' : round($week_one_success_sum->sum()/$week_one_time_sum->sum(),2) }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_two_time_sum->sum() == 0 ? '0' : round($week_two_success_sum->sum()/$week_two_time_sum->sum(),2) }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_three_time_sum->sum() == 0 ? '0' : round($week_three_success_sum->sum()/$week_three_time_sum->sum(),2) }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_four_time_sum->sum() == 0 ? '0' : round($week_four_success_sum->sum()/$week_four_time_sum->sum(),2) }}</td>
            <td style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{ $month_avg }}</td>
        </tr>

        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>RBH</b></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ round($week_one_time_sum->avg(), 2) }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ round($week_two_time_sum->avg(), 2) }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ round($week_three_time_sum->avg(), 2) }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ round($week_four_time_sum->avg(), 2) }}</td>
            <td style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{ round($month_sum_rbh->avg(), 2) }}</td>
        </tr>

        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>% CELU</b></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_one_goal_proc }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_two_goal_proc }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_three_goal_proc }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_four_goal_proc }} %</td>
            <td style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{ $month_goal_proc }} %</td>
        </tr>

        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>JAKOŚĆ</b></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_one_janky_proc }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_two_janky_proc }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_three_janky_proc }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_four_janky_proc }} %</td>
            <td style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{ $month_janky_proc }} %</td>
        </tr>

        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>CZAS ROZMÓW</b></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_one_time_call_avg }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_two_time_call_avg }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_three_time_call_avg }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $week_four_time_call_avg }} %</td>
            <td style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{ $month_call_time_avg }} %</td>
        </tr>

        <tr>
            <td colspan="7" style="background-color: #464a51;border:1px solid #231f20;text-align:center;padding:3px; height: 25px"> </td>
        </tr>
    @endforeach

    </tbody>
</table>
