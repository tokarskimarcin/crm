
<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px" class="table table-bordered">
<thead style="color:#efd88f">
<tr>
<td colspan="5" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
<font size="6" face="Calibri">RAPORT GODZINNY {{$date}}</font></td>
<td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
<img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
</tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Godzina</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Średnia</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba Zaproszeń</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba Zalogowanych</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Janków</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Wykorzystania Bazy</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Czas Rozmów</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Celu</th>
    </tr>
</thead>
    <tbody>
      @php
          $total_average = 0;
          $total_success = 0;
          $total_employee_count = 0;
          $total_janky_count = 0;
          $total_call_time = 0;
          $total_wear_base = 0;
          $total_success_proc = 0;
          $sum = 0;
      @endphp
      @foreach($reports as $report)
        @if($report->department_info->id_dep_type == 2)
          <tr>
              <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->department_info->departments->name}}</td>
              <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$hour}}</td>
              <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->average}}</td>
              <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->success}}</td>
              <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->employee_count}}</td>
              <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->janky_count}} %</td>
              <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->wear_base}} %</td>
              <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->call_time}} %</td>
              @if(date('N') <= 5)
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{round(($report->success / $report->department_info->dep_aim) * 100, 2)}} %</td>
                  @php $total_success_proc += round(($report->success / $report->department_info->dep_aim) * 100, 2); @endphp
              @else
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{round(($report->success / $report->department_info->dep_aim_week) * 100, 2)}} %</td>
                  @php $total_success_proc += round(($report->success / $report->department_info->dep_aim_week) * 100, 2); @endphp
              @endif
              @php
                  $total_average += $report->average;
                  $total_success += $report->success;
                  $total_employee_count += $report->employee_count;
                  $total_janky_count += $report->janky_count;
                  $total_call_time += $report->call_time;
                  $total_wear_base += $report->wear_base;
                  $sum++;
              @endphp
          </tr>
        @endif
      @endforeach
      @php
        if($sum == 0)
        {
            $total_success_proc = 0;
            $total_wear_proc = 0;
            $total_avg_proc = 0;
        }else
          {
          $total_success_proc = round($total_success_proc / $sum, 2);
          $total_wear_proc = round($total_wear_base / $sum, 2);
          $total_avg_proc = round($total_average / $sum, 2);
          $total_janky_count = round($total_janky_count / $sum, 2);
          $total_call_time = round($total_call_time / $sum, 2);
          }
      @endphp
      <tr>
          <td colspan="2" style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px"><b>Total</b></td>
          <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$total_avg_proc}}</td>
          <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$total_success}}</td>
          <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$total_employee_count}}</td>
          <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$total_janky_count}} %</td>
          <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$total_wear_proc}} %</td>
          <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$total_call_time}} %</td>
          <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$total_success_proc}} %</td>
      </tr>
    </tbody>
</table>
