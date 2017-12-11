<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
<thead style="color:#efd88f">
<tr>
<td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
<font size="6" face="Calibri">Raport Godzinny % Odsłuchanych {{$date}} {{$hour}}</font></td>
<td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
<img src="https://ci3.googleusercontent.com/proxy/2Yaz8WsJ34uYOsanmpfkEZKbZDP2-sOQDVLB5TQdLCq6R7YzBCfaGc6K2bNRItA=s0-d-e1-ft#http://xdes.pl/logovc.png" class="CToWUd"></td>
</tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Godzina</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba Zaproszeń</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Odsłuchane rozmowy</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Odsłuchanych</th>
    </tr>
</thead>
    <tbody>

@php($total_success = 0)
@php($total_dkj_sum = 0)

@foreach($reports as $report)
@php($column = true)
    <tr>
        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->dep_name . ' ' . $report->dep_name_type}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$hour}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->success}}</td>
        @foreach($dkj as $item)
            @if($item->department_info_id == $report->department_info_id)
            @php($avg_department = round(($item->dkj_sum / $report->success) * 100, 2))
            @php($total_dkj_sum += $item->dkj_sum)
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->dkj_sum}}</td>
            @php($column = false)
            @endif
        @endforeach
           @if($column == true)
              <td style="border:1px solid #231f20;text-align:center;padding:3px">0</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px">0 %</td>
           @else
              <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$avg_department}} %</td>
           @endif
           @php($total_success += $report->success)
    </tr>

@endforeach

@if($total_success > 0)
    <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px"><b>TOTAL</b></td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_success}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_dkj_sum}}</td>
    @if($total_dkj_sum > 0)
        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round($total_dkj_sum / $total_success * 100, 2)}}</td>
    @else
        <td style="border:1px solid #231f20;text-align:center;padding:3px">0 %</td>
    @endif
@endif

    </tbody>
</table>