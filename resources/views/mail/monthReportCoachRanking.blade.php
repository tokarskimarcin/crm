
@php
    $lp = 1;
    $all_data_collect = collect();
@endphp
    @foreach($data as $data_item)

        @php
            $leader = $data_item['trainer'];
            $coachData = $data_item['trainer_data'];
            $date_start = $data_item['date'][0];
            $date_stop = $data_item['date'][1];
        @endphp
        @if(!$coachData->isEmpty())
            @php
                $collect_week = collect();
                $week_average = 0;
                $week_janky_proc = 0;
                $week_success = 0;
                $week_call_success_proc = 0;
                $week_pause_time = 0;
                $week_login_time = 0;
                $week_received_calls = 0;
                $week_received_calls_proc = 0;
                $week_janky_count = 0;

                $week_checked = 0;
                $week_all_bad = 0;
                $total_week_checked = 0;
                $total_week_all_bad = 0;

                $total_checked = 0;
                $total_bad = 0;

                $total_average = 0;
                $total_janky_proc = 0;
                $total_success = 0;
                $total_pause_time = 0;
                $total_login_time = 0;
                $total_received_calls = 0;
                $total_received_calls_proc = 0;
                $total_janky_count = 0;
            @endphp

            @for($i = 1; $i <= 4; $i++)



                @foreach($coachData as $value)
                    @foreach($value as $item)
                        @php
                            $collect_week->push($item[$i]);
                            $data = $item[$i];
                            $week_success += $data['success'];
                            $week_checked += $data['all_checked'];
                            $week_all_bad += $data['all_bad'];
                            $week_pause_time += $data['pause_time'];
                            $week_login_time += $data['login_time_sec'];
                            $week_received_calls += $data['received_calls'];
                            $week_janky_count += $data['total_week_yanky'];

                            $total_success += $data['success'];
                            $total_checked += $data['all_checked'];
                            $total_bad += $data['all_bad'];
                            $total_pause_time += $data['pause_time'];
                            $total_login_time += $data['login_time_sec'];
                            $total_received_calls += $data['received_calls'];
                            $total_janky_count += $data['total_week_yanky'];
                        @endphp
                    @endforeach
                @endforeach

                @foreach($collect_week->sortbyDESC('average') as $item)
                    @php
                        $jank = $item['all_checked'] ? round((100 * $item['all_bad'] / $item['all_checked']),2) : 0;
                    @endphp
                @endforeach

                @php
                    $collect_week = collect();
                   $week_average = ($week_success > 0) ? round(($week_success / ($week_login_time/3600)), 2) : 0 ;
                   $week_received_calls_proc = ($week_received_calls > 0) ? round(($week_success / $week_received_calls) * 100 , 2) : 0 ;
                   $week_janky_proc = ($week_checked > 0) ? round(($week_all_bad / $week_checked) * 100, 2) : 0 ;
                   $week_janky_count = 0;
                @endphp
                @php
                    $week_average = 0;
                    $week_janky_proc = 0;
                    $week_received_calls = 0;
                    $week_success = 0;
                    $week_checked = 0;
                    $week_all_bad = 0;
                    $week_received_calls_proc = 0;
                    $week_pause_time = 0;
                    $week_login_time = 0;
                @endphp


            @endfor

            @php
                $total_average = ($total_success > 0) ? round(($total_success / ($total_login_time/3600)), 2) : 0 ;
                $total_received_calls_proc = ($total_received_calls > 0) ? round(($total_success / $total_received_calls) * 100, 2) : 0 ;
                $total_janky_proc = ($total_checked > 0) ? round(($total_bad / $total_checked) * 100, 2) : 0 ;
                $data_collect = new \stdClass();
                    $data_collect->leader = $leader;
                    $data_collect->department_info_id = $leader->department_info_id;
                    $commissionAvg = $leader->department_info()->first()->commission_avg;
                    $data_collect->commissionProc = $commissionAvg != 0 ? round($total_average*100/$commissionAvg,2) : 0;
                    $data_collect->total_average = $total_average;
                    $data_collect->total_success = $total_success;
                    $data_collect->total_login_time = $total_login_time;
                    $data_collect->total_received_calls = $total_received_calls;
                    $data_collect->pasueTimeToLoginTime = $total_login_time != 0 ? round( ($total_pause_time*100)/ $total_login_time,2) : 0;
                    $data_collect->total_bad = $total_bad;
                    $data_collect->total_checked = $total_checked;
                    $data_collect->total_pause_time = $total_pause_time;
                    $data_collect->total_janky_proc = $total_janky_proc;
                    $data_collect->total_received_calls_proc = $total_received_calls_proc;

                $all_data_collect->push($data_collect);
            @endphp
        @endif
    @endforeach

<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="8" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="5" face="Calibri">RANKING TRENERZY </font></td>
        <td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Oddział</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">L.P</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Trener</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Średnia</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">% Realizacji średniej</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">% janków</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Ilość połączeń</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Umówienia</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">% ilość um/poł</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Czas przerw</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba godzin</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Czas przerw/Liczba godzin %</td>
    </tr>
    </thead>
    <tbody>
        @foreach($departments as $item)
            @php
                $coachFromDepartment = $all_data_collect->where('department_info_id','=',$item->id);
                $coachFromDepartment = $coachFromDepartment->sortByDesc('commissionProc');
                $lp = 1;
                $project_avg = $item->commission_avg;
            @endphp
            @if(!$coachFromDepartment->isEmpty())
                @foreach($coachFromDepartment as $value)
                    @php
                        $color = "background-color: white";

                        if($value-> total_average  < $project_avg)
                              $color = "background-color: #ff000038";
                    @endphp
                    <tr>
                        @if($lp == 1)
                            <td rowspan = {{count($coachFromDepartment)}} style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{$item->departments->name.' '.$item->department_type->name}}</b></td>
                            @php
                                $lp = 1;
                            @endphp
                        @endif
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $lp }}</b></td>
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $value->leader->first_name.' '.$value->leader->last_name}}</b></td>
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $value->total_average }}</b></td>
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $value->commissionProc }} %</b></td>
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $value->total_janky_proc }} %</b></td>
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $value->total_received_calls }}</b></td>
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $value->total_success }}</b></td>
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $value->total_received_calls_proc }} %</b></td>
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($value->total_pause_time/3600),($value->total_pause_time/60%60), $value->total_pause_time%60) }}</b></td>
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($value->total_login_time/3600),($value->total_login_time/60%60), $value->total_login_time%60) }}</b></td>
                        <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $value->pasueTimeToLoginTime }}%</b></td>
                    </tr>
                    @php
                        $lp++;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="12" style="background-color: #464a51;border:1px solid #231f20;text-align:center;padding:3px; height: 25px"> </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
<div style="width: 100%; height: 25px"></div>