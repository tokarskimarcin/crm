<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
<thead style="color:#efd88f">
<tr>
<td colspan="5" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
<font size="6" face="Calibri">Raport Dzienny Telemarketing </font></td>
<td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
<img src="https://ci3.googleusercontent.com/proxy/2Yaz8WsJ34uYOsanmpfkEZKbZDP2-sOQDVLB5TQdLCq6R7YzBCfaGc6K2bNRItA=s0-d-e1-ft#http://xdes.pl/logovc.png" class="CToWUd"></td>
</tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Średnia</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Real RBH</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba Zaproszeń</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Janków</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Wykorzystania Bazy</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Czas Rozmów</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Celu</th>
    </tr>
</thead>
    <tbody>
      @php($total_avg_average = 0)
      @php($total_realRBH = 0)
      @php($total_sum_success = 0)
      @php($total_sum_janky_count = 0)
      @php($total_janky = 0)
      @php($total_avg_wear_base = 0)
      @php($total_sum_call_time = 0)
      @php($count = 0)
      @php($total_goal = 0)

      @foreach($reports as $report)
      @php($add_column = true)
        @php($goal = (5 * $report->dep_aim) + $report->dep_aim_week)
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->dep_name . ' ' . $report->dep_type_name}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round($report->avg_average, 2)}}</td>
            @foreach($work_hours as $work_hour)
                @if($work_hour->id == $report->id && $work_hour->realRBH != null)
                @php($add_column = false)
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$work_hour->realRBH}}</td>
                @endif
            @endforeach
            @if($add_column == true)
                <td style="border:1px solid #231f20;text-align:center;padding:3px">Brak pracowników</td>
            @endif
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round($report->sum_success, 2)}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round($report->sum_janky_count, 2)}} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round($report->avg_wear_base, 2)}} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round($report->sum_call_time, 2)}} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round(($report->sum_success / $goal) * 100, 2)}} %</td>
        </tr>

        @php($total_avg_average += $report->avg_average)
        @php($total_sum_success += $report->sum_success)
        @php($total_sum_janky_count += $report->sum_janky_count)
        @php($total_janky += $report->sum_janky_count)
        @php($total_avg_wear_base += $report->avg_wear_base)
        @php($total_goal += round(($report->sum_success / $goal) * 100, 2))
        @php($total_sum_call_time += $report->sum_call_time)
        @php($count++)

      @endforeach

      @php
          if($count == 0)
          {

                  $total_goal_proc = 0;
                  $total_avg_wear_base_proc = 0;
                  $total_janky_proc = 0;
                  $total_avg_average_proc = 0;
                  $total_sum_call_time_poc = 0;
          }else
            {
                  $total_goal_proc = round($total_goal / $count, 2);
                  $total_avg_wear_base_proc = round($total_avg_wear_base / $count, 2);
                  $total_janky_proc = round($total_janky / $count, 2);
                  $total_avg_average_proc = round($total_avg_average / $count, 2);
                  $total_sum_call_time_poc = round($total_sum_call_time / $count, 2);
                  $total_realRBH = round($total_realRBH / 3600, 2);
            }
      @endphp

      <tr>
          <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>Total:</b></td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_avg_average_proc}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_realRBH}} godzin</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_sum_success}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_janky_proc}} %</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_avg_wear_base_proc}} %</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_sum_call_time_poc}} %</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_goal_proc}} %</td>
      </tr>
  </tbody>
</table>
