<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">RAPORT TRENERZY {{$department->departments->name . ' ' . $department->department_type->name}} {{ $report_date }} {{ (isset($report_hour)) ? $report_hour : '' }} </font></td>
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
            $day_total_received_calls = 0;
            $day_total_success = 0;
            $day_total_pause_time = 0;
            $day_total_login_time = 0;

            $day_total_janky_count = 0;
            $day_total_all_checked_talks = 0;
            $janky_percent = 0;
        @endphp

        @foreach($data as $coach)
            @php
                $total_received_calls = 0;
                $total_success = 0;
                $total_pause_time = 0;
                $total_login_time = 0;

                $total_janky_count = 0;
                $total_all_checked_talks = 0;
            @endphp
            <tr>
                <td colspan="8" style="background-color: #5eff80;border:1px solid #231f20;text-align:center;padding:3px">{{ $coach['last_name'] . ' ' . $coach['first_name'] }}</td>
            </tr>
            @foreach($coach as $item)
                @if(is_object($item))
                    @php
                    if($item->all_checked_talks != 0 && $item->all_checked_talks != null) {
                        $janky_percent = round($item->all_bad_talks / $item->all_checked_talks * 100, 2);
                    }
                    else {
                        $janky_percent = 0;
                    }
                    @endphp

                    <tr>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $item->user_last_name . ' ' . $item->user_first_name }}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $item->average }}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $janky_percent }} %</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $item->received_calls }}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $item->success }}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ ($item->received_calls > 0) ? round(($item->success / $item->received_calls) * 100, 2) : 0 }} %</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ sprintf('%02d:%02d:%02d', ($item->time_pause/3600),($item->time_pause/60%60), $item->time_pause%60) }}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $item->login_time }}</td>
                    </tr>
                    @php
                        $total_received_calls += $item->received_calls;
                        $total_success += $item->success;
                        $total_pause_time += $item->time_pause;
                        $hours_array = explode(':', $item->login_time);
                        $total_login_time += (($hours_array[0] * 3600) + ($hours_array[1] * 60) + $hours_array[2]);
                        $total_janky_count += $item->all_bad_talks;
                        $total_all_checked_talks += $item->all_checked_talks;

                        $day_total_received_calls += $item->received_calls;
                        $day_total_success += $item->success;
                        $day_total_pause_time += $item->time_pause;
                        $hours_array = explode(':', $item->login_time);
                        $day_total_login_time += (($hours_array[0] * 3600) + ($hours_array[1] * 60) + $hours_array[2]);
                        $day_total_janky_count += $item->all_bad_talks;
                        $day_total_all_checked_talks += $item->all_checked_talks;
                    @endphp

                @endif
            @endforeach
            @php
                $total_time = $total_login_time / 3600;
                $total_avg = ($total_time > 0) ? round(($total_success / $total_time), 2) : 0 ;
                $total_janky_proc = ($total_all_checked_talks > 0) ? round(($total_janky_count / $total_all_checked_talks * 100), 2) : 0 ;
            @endphp

            <tr>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>SUMA</b></td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_avg }} %</b></td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_janky_proc }} %</b></td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_received_calls }}</b></td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_success }}</b></td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ ($total_received_calls > 0) ? round(($total_success / $total_received_calls) * 100, 2) : 0 }} %</b></td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($total_pause_time/3600),($total_pause_time/60%60), $total_pause_time%60) }}</b></td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($total_login_time/3600),($total_login_time/60%60), $total_login_time%60) }}</b></td>
            </tr>
        @endforeach

        @php
            $day_total_time = $day_total_login_time / 3600;
            $day_total_avg = ($day_total_time > 0) ? round(($day_total_success / $day_total_time), 2) : 0 ;
            $day_total_janky_proc = ($day_total_all_checked_talks > 0) ? round(($day_total_janky_count / $day_total_all_checked_talks * 100), 2) : 0 ;
        @endphp
        <tr>
            <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">SUMA</td>
            <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{ $day_total_avg }}</td>
            <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{ $day_total_janky_proc }} %</td>
            <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{ $day_total_received_calls }}</td>
            <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{ $day_total_success }}</td>
            <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{ ($day_total_received_calls > 0) ? round(($day_total_success / $day_total_received_calls) * 100, 2) : 0 }} %</td>
            <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{ sprintf('%02d:%02d:%02d', ($day_total_pause_time/3600),($day_total_pause_time/60%60), $day_total_pause_time%60) }}</td>
            <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{ sprintf('%02d:%02d:%02d', ($day_total_login_time/3600),($day_total_login_time/60%60), $day_total_login_time%60) }}</td>
        </tr>
    </tbody>
</table>
