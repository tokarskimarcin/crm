<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
<td colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
<font size="6" face="sans-serif">RAPORT SPÓŹNIONYCH ODDZIAŁÓW (DZIENNY) {{$today}}</td>
<td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://teambox.pl/image/logovc.png"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Oddział</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Data</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba wysłanych raportów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba spóźnioncyh raportów</th>
</tr>
</thead>
  <tbody>
    @php
        $i = 1;
        $total_send = 0;
        $total_missed = 0;
    @endphp

    @foreach($reports as $report)
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$i}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->dep_name . ' ' . $report->dep_name_type}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$today}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->send}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->missed}}</td>
        </tr>
        @php
            $total_send += $report->send;
            $total_missed += $report->missed;
            $i++;
        @endphp
    @endforeach
    <tr>
        <td style="border:1px solid #231f20;text-align:center;padding:3px" colspan="1">{{$i}}</td>
        <td></td>
        <td style="text-align:center"><b>Total</b></td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_send}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_missed}}</td>
    </tr>
  <tbody>
</table>


