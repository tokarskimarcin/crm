<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">RAPORT ODSŁUCHANYCH ROZMÓW {{$today}} </font></td>
        <td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Liczba zgód</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Liczba odsłuchanych</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">% Odsłuchanych</th>
    </tr>
    </thead>
    <tbody>
    @php
        $total_success = 0;
        $total_checked = 0;
    @endphp
    @if(count($hour_reports) != 0)
        @foreach($hour_reports as $report)

            <tr>
                @if($report->department_info_id == 13)
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">Radom Potwierdzenia Badania </td>
                @elseif($report->department_info_id == 4)
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">Radom Potwierdzenia Wysyłka </td>
                @elseif($report->department_info_id == 1)
                    <td style="border:1px solid #231f20;text-align:center;padding:3px;">Lublin Potwierdzenia Badania </td>
                @elseif($report->department_info_id == 15)
                    <td style="border:1px solid #231f20;text-align:center;padding:3px;">Lublin Potwierdzenia Wysyłka </td>
                @else
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->dep_name . ' ' . $report->dep_name_type}}</td>
                @endif
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->success}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$report->all_checked}}</td>

                @if($report->success > 0)
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{round($report->all_checked /$report->success * 100, 2)}} %</td>
                @else
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">0%</td>
                @endif

                @php
                    $total_success += $report->success;
                    $total_checked += $report->all_checked;
                @endphp
            </tr>
        @endforeach
        @php
            $total_proc = $total_success > 0 ? round($total_checked / $total_success * 100,2) . '%' : '0%';
        @endphp

            <tr>
                <td colspan="1" style="border:1px solid #231f20;text-align:center;padding:3px"><b>TOTAL</b></td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;font-weight:bold;">{{$total_success}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;font-weight:bold;">{{$total_checked}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;font-weight:bold;">{{$total_proc}}</td>
            </tr>
        @else
        <td style="text-align:center;">Brak danych</td>
        @endif
    </tbody>
</table>
