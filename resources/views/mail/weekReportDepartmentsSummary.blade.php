@php
    $ip = 0;
@endphp
@foreach($data as $item)
<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px; margin-bottom: 20px;">
    <thead style="color:#efd88f">
        <tr>
            <td colspan="{{ $item[0]['data']->count() }}" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
                <font size="6" face="Calibri">Raport Dzienny {{ $item[0]['data_start'] . ' - ' . $item[0]['data_stop'] }}</font></td>
            <td colspan="3" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
                <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
        </tr>
        <tr>
            <td style="border:1px solid #231f20;padding:3px;background:#231f20; width: 15%;">ODDZIAŁ</td>
            <td style="border:1px solid #231f20;padding:3px;background:#231f20;">KATEGORIA</td>

            @php
                $headers = [];
            @endphp

            @foreach($item[0]['data'] as $report_data)
                @php
                    $headers[] = $report_data->report_date;
                @endphp
                <td style="border:1px solid #231f20;padding:3px;background:#231f20;">{{ $report_data->report_date }}</td>
            @endforeach
            <td style="border:1px solid #231f20;padding:3px;background:#231f20;">TYDZIEŃ</td>

        </tr>
    </thead>
    <tbody>

        @foreach($item as $department_repo)
            @php
                $dep_id = (is_object($department_repo['data']->first())) ? ($department_repo['data']->first()->department_info_id) : 0 ;
                $dep = ($dep_id > 0) ? ($departments->where('id', '=', $dep_id)->first()) : null ;
            @endphp
            @if($dep !== null)
                @php


                        $week_avg = 0;
                        $week_rbh = 0;
                        $week_goal_proc = 0;
                        $week_dkj_sum = 0;
                        $week_avg_call_time = 0;
                        $week_success = 0;
                        $week_goal = 0;
                        $week_hour_time_use = 0;

                        $day_avg =0;
                        $day_succes = 0;
                        $day_rbh = 0;
                        $day_check = 0;
                        $day_bad = 0;

                        $total_rbh_without_weekends = 0;
                        $number_day_weekends = 0;
                        $number_day_without_weekends = 0;
                        $total_rbh_weekends = 0;

                        $week_day_count = 0;
                        foreach($department_repo['data'] as $value) {
                            $day_number = date('N', strtotime($value->report_date));
                            $week_rbh += $value->day_time_sum;
                            if($day_number < 6){
                                $total_rbh_without_weekends += round($value->day_time_sum,2);
                                if(round($value->day_time_sum,2) > 0){
                                    $number_day_without_weekends++;
                                }
                            }else{
                                $total_rbh_weekends += round($value->day_time_sum,2);
                                if(round($value->day_time_sum,2) > 0){
                                    $number_day_weekends++;
                                }
                            }
                            $week_success += $value->success;
                            if($value->day_time_sum != 0){
                               $week_goal += (date('N', strtotime($value->report_date)) < 6) ? $dep->dep_aim : $dep->dep_aim_week ;
                               $week_day_count++;
                            }
                            $week_hour_time_use += ($value->day_time_sum * $value->call_time) / 100;
                            $week_dkj_sum += ($value->success * $value->janky_count) / 100;
                            $day_check+=    $value->janky_count_all_check;
                            $day_bad+=  $value->count_bad_check;
                        }

                        $total_rbh_without_weekends = $number_day_without_weekends > 0 ? round($total_rbh_without_weekends/$number_day_without_weekends,2) : 0;
                        $total_rbh_weekends = $number_day_weekends > 0 ? round($total_rbh_weekends/$number_day_weekends,2) : 0;

                        $week_avg = ($week_rbh > 0) ? round($week_success / $week_rbh, 2) : 0 ;
                        $week_hour_time_use = ($week_rbh > 0) ? round($week_hour_time_use / $week_rbh * 100, 2) : 0 ;
                        $week_rbh = ($week_day_count > 0) ? round($week_rbh / $week_day_count, 2) : 0 ;
                        $week_goal_proc = ($week_goal > 0) ? round($week_success / $week_goal * 100, 2) : 0 ;
                        $week_dkj_proc = ($day_check > 0) ? round((($day_bad*100) / $day_check), 2) : 0 ;

                @endphp
                <tr>
                    <td rowspan="5" style="border:1px solid #231f20;text-align:center;padding:3px"><b>@if($dep !== null) {{ $dep->departments->name . ' ' . $dep->department_type->name }} @endif</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>ŚREDNIA</b></td>
                    @foreach($headers as $report_date)
                        @php
                            $repo = (is_object($department_repo['data']->where('report_date', '=', $report_date)->first())) ? $department_repo['data']->where('report_date', '=', $report_date)->first() : null;
                            if(is_object($repo))
                                $day_avg = $repo->day_time_sum != 0 ? round($repo->success/$repo->day_time_sum,2) : 0;
                            else
                                $day_avg = 0;
                        @endphp
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">@if($day_avg !== null) {{$day_avg}} @else 0 @endif</td>
                    @endforeach
                    <td style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{ $week_avg }}</td>
                </tr>

                <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>RBH</b></td>
                    @foreach($headers as $report_date)
                        @php
                            $repo = (is_object($department_repo['data']->where('report_date', '=', $report_date)->first())) ? $department_repo['data']->where('report_date', '=', $report_date)->first() : null;
                        @endphp
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">@if($repo !== null) {{ round($repo->day_time_sum, 2) }} @else 0 @endif</td>
                    @endforeach
                    <td style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{ $total_rbh_without_weekends.'/'.$total_rbh_weekends }}</td>
                </tr>

                <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>% CELU</b></td>
                    @foreach($headers as $report_date)
                        @php
                            $repo = (is_object($department_repo['data']->where('report_date', '=', $report_date)->first())) ? $department_repo['data']->where('report_date', '=', $report_date)->first() : null;
                            if ($repo)
                                $day_goal = (date('N', strtotime($repo->report_date)) < 6) ? $dep->dep_aim : $dep->dep_aim_week ;
                            else
                                $day_goal = null;
                        @endphp
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ ($repo && $day_goal) ? round($repo->success / $day_goal * 100 , 2) : 0 }} %</td>
                    @endforeach
                    <td style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{ $week_goal_proc }} %</td>
                </tr>

                <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>JAKOŚĆ</b></td>
                    @foreach($headers as $report_date)
                        @php
                            $repo = (is_object($department_repo['data']->where('report_date', '=', $report_date)->first())) ? $department_repo['data']->where('report_date', '=', $report_date)->first() : null;
                        @endphp
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">@if($repo !== null && $repo->janky_count_all_check !=0 ) {{ round(($repo->count_bad_check*100)/$repo->janky_count_all_check,2) }} @else 0 @endif %</td>
                    @endforeach
                    <td style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{ $week_dkj_proc }} %</td>
                </tr>

                <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>CZAS ROZMÓW</b></td>
                    @foreach($headers as $report_date)
                        @php
                            $repo = (is_object($department_repo['data']->where('report_date', '=', $report_date)->first())) ? $department_repo['data']->where('report_date', '=', $report_date)->first() : null;
                        @endphp
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">@if($repo !== null) {{ $repo->call_time }} @else 0 @endif %</td>
                    @endforeach
                    <td style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{  $week_hour_time_use }} %</td>
                </tr>

                <tr>
                    <td colspan="{{  $item[0]['data']->count() + 3 }}" style="background-color: #464a51;border:1px solid #231f20;text-align:center;padding:3px; height: 25px"> </td>
                </tr>
            @endif
        @endforeach

    </tbody>
</table>
    @php
        $ip++;
    @endphp
@endforeach