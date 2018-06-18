<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="7" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="5" face="Calibri">RANKING TRENERZY </font></td>
        <td colspan="3" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">L.P</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Trener</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Średnia</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">% Realizacji średniej</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">% janków</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Ilość połączeń</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Umówienia</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Czas przerw</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba godzin</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">Czas przerw/Liczba godzin</td>
    </tr>
    </thead>
    <tbody>
    @php
         $lp = 1;
    @endphp
    @foreach($data as $item)
        @php
               $leader = $item['leader'];
               $coachData = $item;
               $projectAvgCommision = $leader->department_info()->first()->commission_avg;
               $color = $coachData['avg'] < $projectAvgCommision ? "background-color:#ff000038" : "";
        @endphp
                <tr>
                    <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $lp++ }}</b></td>
                    <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $leader->first_name.' '.$leader->last_name}}</b></td>
                    <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $coachData['avg'] }}</b></td>
                    <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $coachData['commissionProc'] }} %</b></td>
                    <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $coachData['jankyProc'] }} %</b></td>
                    <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $coachData['received_calls'] }}</b></td>
                    <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $coachData['success'] }}</b></td>
                    <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($coachData['pause_time']/3600),($coachData['pause_time']/60%60), $coachData['pause_time']%60) }}</b></td>
                    <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ sprintf('%02d:%02d:%02d', ($coachData['login_time']/3600),($coachData['login_time']/60%60), $coachData['login_time']%60) }}</b></td>
                    <td style="{{$color}};border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $coachData['pasueTimeToLoginTime']}} %</b></td>
                </tr>
    @endforeach
    </tbody>
</table>
<div style="width: 100%; height: 25px"></div>