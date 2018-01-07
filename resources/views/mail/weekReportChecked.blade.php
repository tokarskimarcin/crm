<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
<thead style="color:#efd88f">
<tr>
<td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
<font size="6" face="Calibri">RAPORT ODSŁUCHANYCH ROZMÓW TYGODNIOWY</font></td>
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
@php($add_column = true)
@php($dep_avg = 0)
    <tr>
        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->dep_name . ' ' . $report->dep_name_type}}</td>

        @foreach($dkj as $item)
            @if($item->department_info_id == $report->department_info_id)
            @php
            if ($item->department_sum != 0) {
              $dep_avg = round($item->department_sum/ $report->success * 100, 2);
            } else {
              $dep_avg = 0;
            }
            @endphp
            @php($total_sum += $item->department_sum)
                @if($item->department_sum != 0)
                @php($add_column = false)
                  <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->department_sum}}</td>
                @endif
            @endif
        @endforeach
        @if($add_column == true)
            <td style="border:1px solid #231f20;text-align:center;padding:3px">0</td>
        @endif
          <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->success}}</td>
        @if(isset($dep_avg))
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$dep_avg}} %</td>
        @else
            <td style="border:1px solid #231f20;text-align:center;padding:3px">0 %</td>
        @endif

    </tr>
    @php($total_success += $report->success)
@endforeach
<tr>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">Lublin Potwierdzanie</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">1786</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">2557</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round(1786/ 2557 * 100, 2)}} %</td>
</tr>
<tr>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">Radom Potwierdzanie Wysyłka</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">4046</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">5619</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round(4046/ 5619 * 100, 2)}} %</td>
</tr>
<tr>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">Radom Potwierdzanie Badania</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">1503</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">2039</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round(1503/ 2039 * 100, 2)}} %</td>
</tr>
@php
    $total_sum += 1786+4046+1503;
    $total_success += 2557+5619+2039;
    if($total_success != 0)
        $total_proc = round($total_sum / $total_success * 100, 2);
    else
        $total_proc = 0;
@endphp
<tr>
    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>Total</b></td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_sum}}</td>
      <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_success}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_proc}} %</td>
</tr>
  </tbody>
</table>
