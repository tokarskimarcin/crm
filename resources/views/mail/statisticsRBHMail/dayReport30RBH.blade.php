<font size="6" face="Calibri">RAPORT RBH Dzień: {{$sDayToHeader}}</font></td>

@foreach($allUsersForReport as $item)
    @php
    $i = 1;
    $item = $item->sortByDESC('avg');
    $sumSecond30RBH = 0;
    $sumSecondAll = 0;
    @endphp
<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="9" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">RAPORT DZIENNY 30RBH {{$item[0]['dep_city']}} {{$item[0]['dep_type']}}</font></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">L.p</th>
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

            @foreach($item as $value)
            <tr>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i++}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$value['userNameInfo']}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$value['avg']}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$value['jankyProc']}} %</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$value['received_calls']}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$value['success']}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$value['received_callsProc']}} %</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$value['pause_time']}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{isset($value['secondStop']) ? \App\Schedule::secondToHour($value['secondStop']).' / ' : ' '}}  {{\App\Schedule::secondToHour($value['sec_sum'])}} </td>
                @php
                    $sumSecond30RBH += isset($value['secondStop']) ? $value['secondStop'] : $value['sec_sum'];
                @endphp
            </tr>
            @endforeach
            <tr>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px" colspan="2">Suma</td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{$item->sum('secondStop') != 0 ? round($item->sum('success')/($item->sum('secondStop')/3600),2): 0}}</td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{$item->sum('bad_talks') != 0 ? round($item->sum('all_checked_talks')/$item->sum('bad_talks'),2): 0}} %</td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{$item->sum('received_calls')}}</td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{$item->sum('success')}}</td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{$item->sum('received_calls') != 0 ? round($item->sum('success')/($item->sum('received_calls')) * 100,2): 0}} %</td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{\App\Schedule::secondToHour($item->sum('pause_timeSec'))}}</td>
                <td style="background-color: #c67979;border:1px solid #231f20;text-align:center;padding:3px">{{\App\Schedule::secondToHour($sumSecond30RBH).' / '}}  {{\App\Schedule::secondToHour($item->sum('sec_sum'))}} </td>
            </tr>
    </tbody>
</table>
@endforeach
