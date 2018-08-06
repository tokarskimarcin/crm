<font size="6" face="Calibri">RAPORT RBH Dzień: {{$sDayToHeader}}</font></td>

@foreach($allUsersForReport as $item)
    @php
    $i = 1;
    $item = $item->sortByDESC('avg');
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
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{\App\Schedule::secondToHour($value['sec_sum'])}}</td>
            </tr>
            @endforeach
    </tbody>
</table>
@endforeach
