<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
<thead style="color:#efd88f">
<tr>
<td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
<font size="6" face="Calibri">RAPORT ODSŁUCHANYCH ROZMÓW TYGODNIOWY </font></td>
<td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
<img src="https://ci3.googleusercontent.com/proxy/2Yaz8WsJ34uYOsanmpfkEZKbZDP2-sOQDVLB5TQdLCq6R7YzBCfaGc6K2bNRItA=s0-d-e1-ft#http://xdes.pl/logovc.png" class="CToWUd"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba odsłuchanych</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba zgód</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20">% Odsłuchanych</th>
</tr>
</thead>
  <tbody>

@php($total_success = 0)
@php($total_sum = 0)
@foreach($hour_reports as $report)

    <tr>
        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->dep_name . ' ' . $report->dep_name_type}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->success}}</td>
        @foreach($dkj as $item)
            @if($item->department_info_id == $report->department_info_id)
            @php($dep_avg = round($item->department_sum/ $report->success * 100, 2))
            @php($total_sum += $item->department_sum)
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->department_sum}}</td>
            @endif
        @endforeach
        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$dep_avg}} %</td>
    </tr>
    @php($total_success += $report->success)

@endforeach
<tr>
    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>Total</b></td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_success}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_sum}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round($total_sum / $total_success * 100, 2)}} %</td>
</tr>
  </tbody>
</table>
