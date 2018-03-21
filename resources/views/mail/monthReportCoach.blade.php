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
            $week_average = 0;
            $week_janky_proc = 0;
            $week_success = 0;
            $week_call_success_proc = 0;
            $week_pause_time = 0;
            $week_login_time = 0;
            $week_received_calls = 0;
            $week_received_calls_proc = 0;

            $total_average = 0;
            $total_janky_proc = 0;
            $total_success = 0;
            $total_pause_time = 0;
            $total_login_time = 0;
            $total_received_calls = 0;
            $total_received_calls_proc = 0;
        @endphp

        @for($i = 1; $i <= 4; $i++)

            @foreach($coachData as $item)
                @php
                    $data = $item[$i];

                    $week_success += $data['success'];
                    $week_pause_time += $data['pause_time'];
                    $week_login_time += $data['login_time'];
                    $week_received_calls += $data['received_calls'];

                    $total_success += $data['success'];
                    $total_pause_time += $data['pause_time'];
                    $total_login_time += $data['login_time'];
                    $total_received_calls += $data['received_calls'];
                @endphp
                <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $data['last_name'] . ' ' . $data['first_name'] }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $data['average'] }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $data['janky_proc'] }} %</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $data['received_calls'] }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $data['success'] }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $data['proc_received_calls'] }} %</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($data['pause_time']/3600),($data['pause_time']/60%60), $data['pause_time']%60) }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $data['login_time'] }} h</b></td>
                </tr>
            @endforeach

            @php
                $week_average = ($week_success > 0) ? round(($week_success / $week_login_time), 2) : 0 ;
                $week_received_calls_proc = ($week_received_calls > 0) ? round(($week_success / $week_received_calls) * 100 , 2) : 0 ;
            @endphp
            <tr>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>SUMA {{ $data['first_week_day'] . '-' . $data['last_week_day']}}</b></td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $week_average }}</b></td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $week_janky_proc }} %</b></td>
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
                $week_received_calls_proc = 0;
                $week_pause_time = 0;
                $week_login_time = 0;
            @endphp

        @endfor

        @php
            $total_average = ($total_success > 0) ? round(($total_success / $total_login_time), 2) : 0 ;
            $total_received_calls_proc = ($total_received_calls > 0) ? round(($total_success / $total_received_calls) * 100, 2) : 0 ;
        @endphp

        <tr>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>SUMA</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_average }}</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_janky_proc }}</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_received_calls }}</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_success }}</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_received_calls_proc }} %</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($total_pause_time/3600),($total_pause_time/60%60), $total_pause_time%60) }}</b></td>
            <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_login_time }} h</b></td>
        </tr>

    </tbody>
</table>
