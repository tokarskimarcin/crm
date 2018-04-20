@foreach($data as $data_item)

    @php
        $leader = $data_item['trainer'];
        $coachData = $data_item['trainer_data'];
        $date_start = $data_item['date'][0];
        $date_stop = $data_item['date'][1];
    @endphp
    @if(!$coachData->isEmpty())
    <table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
        <thead style="color:#efd88f">
        <tr>
            <td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
                <font size="5" face="Calibri">RAPORT TRENERZY - {{$leader->last_name . ' ' . $leader->first_name}} {{date('Y-m',strtotime($date_start)) . '-01 - ' . date('Y-m-t',strtotime($date_stop))}} </font></td>
            <td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
                <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
        </tr>
        <tr>
            <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Konsultant</td>
            <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Średnia</td>
            <td style="border:1px solid #231f20;padding:3px;background:#231f20;">% janków</td>
            <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Ilość połączeń</td>
            <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Umówienia</td>
            <td style="border:1px solid #231f20;padding:3px;background:#231f20;">% ilość um/poł</td>
            <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Czas przerw</td>
            <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba godzin</td>
        </tr>
        </thead>
        <tbody>

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



            @foreach($coachData as $item)
                @php
                        $collect_week->push($item[$i]);
                        $data = $item[$i];
                        $week_success += $data['success'];
                        $week_checked += $data['all_checked'];
                        $week_all_bad += $data['all_bad'];
                        $week_pause_time += $data['pause_time'];
                        $week_login_time += $data['login_time'];
                        $week_received_calls += $data['received_calls'];
                        $week_janky_count += $data['total_week_yanky'];

                        $total_success += $data['success'];
                        $total_checked += $data['all_checked'];
                        $total_bad += $data['all_bad'];
                        $total_pause_time += $data['pause_time'];
                        $total_login_time += $data['login_time'];
                        $total_received_calls += $data['received_calls'];
                        $total_janky_count += $data['total_week_yanky'];
                @endphp
            @endforeach

            @foreach($collect_week->sortbyDESC('average') as $item)

                @php
                    $jank = $item['all_checked'] ? round((100 * $item['all_bad'] / $item['all_checked']),2) : 0;
                @endphp
                <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item['last_name'] . ' ' . $item['first_name'] }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item['average'] }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $jank }} %</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item['received_calls'] }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item['success'] }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item['proc_received_calls'] }} %</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($item['pause_time']/3600),($item['pause_time']/60%60), $item['pause_time']%60) }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item['login_time'] }} h</b></td>
                </tr>
            @endforeach

            @php
                    $collect_week = collect();
                   $week_average = ($week_success > 0) ? round(($week_success / $week_login_time), 2) : 0 ;
                   $week_received_calls_proc = ($week_received_calls > 0) ? round(($week_success / $week_received_calls) * 100 , 2) : 0 ;
                   $week_janky_proc = ($week_checked > 0) ? round(($week_all_bad / $week_checked) * 100, 2) : 0 ;
                   $week_janky_count = 0;
            @endphp
            <tr>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>SUMA {{ (isset($data['first_week_day'])) ? $data['first_week_day'] : 'null'}} - {{(isset($data['last_week_day']) ? $data['last_week_day'] : 'null')}}</b></td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $week_average }}</b></td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $week_janky_proc}} %</b></td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $week_received_calls }}</b></td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $week_success }}</b></td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $week_received_calls_proc }} %</b></td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($week_pause_time/3600),($week_pause_time/60%60), $week_pause_time%60) }}</b></td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $week_login_time }} h</b></td>
            </tr>

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
            $total_average = ($total_success > 0) ? round(($total_success / $total_login_time), 2) : 0 ;
            $total_received_calls_proc = ($total_received_calls > 0) ? round(($total_success / $total_received_calls) * 100, 2) : 0 ;
            $total_janky_proc = ($total_checked > 0) ? round(($total_bad / $total_checked) * 100, 2) : 0 ;
        @endphp
        <tr>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>SUMA</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_average }}</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_janky_proc }} %</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_received_calls }}</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_success }}</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_received_calls_proc }} %</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($total_pause_time/3600),($total_pause_time/60%60), $total_pause_time%60) }}</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_login_time }} h</b></td>
        </tr>

        </tbody>
    </table>
<div style="width: 100%; height: 25px"></div>
    @endif
@endforeach