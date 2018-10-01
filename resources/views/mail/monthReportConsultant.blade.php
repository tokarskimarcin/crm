<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">RAPORT MIESIĘCZNY KONSULTANCI  </font></td>
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
        $total_average = 0;
        $total_janky_proc = 0;
        $total_janky_count = 0;
        $total_received_calls = 0;
        $total_success = 0;
        $total_success_calls_proc = 0;
        $total_pause_time = 0;
        $total_login_time = 0;
        $show = true;
        $total_checked = 0;
        $total_bad = 0;
    @endphp

    @foreach($data as $item)
        @php
            if($onlyNewUser > 0){
                if(in_array($item['consultant']->id,$onlyUserID) && count($onlyUserID) != 0){
                    $total_success += $item['success'];
                    $total_received_calls += $item['received_calls'];
                    $total_pause_time += $item['pause_time'];
                    $total_login_time += $item['login_time'];
                    $total_janky_count += $item['janky_count'];
                    $total_checked += $item['all_checked'];
                    $total_bad += $item['all_bad'];
                    $jank = $item['all_checked'] > 0 ? round((100 * $item['all_bad'] / $item['all_checked']),2) : 0;
                    $show = true;
                }else{
                    $show = false;
                }
            }else{
                    $total_success += $item['success'];
                    $total_received_calls += $item['received_calls'];
                    $total_pause_time += $item['pause_time'];
                    $total_login_time += $item['login_time'];
                    $total_janky_count += $item['janky_count'];
                    $total_checked += $item['all_checked'];
                    $total_bad += $item['all_bad'];
                    $jank = $item['all_checked'] > 0 ? round((100 * $item['all_bad'] / $item['all_checked']),2) : 0;
            }

        @endphp
        @if($show)
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $item['consultant']->last_name . ' ' . $item['consultant']->first_name }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $item['average'] }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $jank }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $item['received_calls'] }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $item['success'] }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ $item['call_success_proc'] }} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ sprintf('%02d:%02d:%02d', ($item['pause_time']/3600),($item['pause_time']/60%60), $item['pause_time']%60) }}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{ sprintf('%02d:%02d:%02d', ($item['login_time']/3600),($item['login_time']/60%60), $item['login_time']%60) }}</td>
        </tr>
        @endif
    @endforeach

    @php
        $total_janky_proc = ($total_checked > 0) ? round($total_bad / $total_checked * 100 , 2) : 0 ;
        $total_average = ($total_login_time > 0) ? round($total_success / ($total_login_time / 3600), 2) : 0 ;
        $total_success_calls_proc = ($total_received_calls > 0 ) ? round($total_success / $total_received_calls * 100, 2) : 0 ;
    @endphp
    <tr>
        <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>SUMA</b></td>
        <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_average }}</b></td>
        <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_janky_proc }} %</b></td>
        <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_received_calls }}</b></td>
        <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_success }}</b></td>
        <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $total_success_calls_proc }} %</b></td>
        <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($total_pause_time/3600),($total_pause_time/60%60), $total_pause_time%60) }}</b></td>
        <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($total_login_time/3600),($total_login_time/60%60), $total_login_time%60) }}</b></td>
    </tr>
    </tbody>
</table>
