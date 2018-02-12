
<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px" class="table table-bordered">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">RAPORT GODZINNY {{$date}}</font></td>
        <td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Ilość Zalogowanych</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Ilość Zaproszeń</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Suma Odsłuchanych</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Poprawne</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Niepoprawne</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Błędnych</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Odsłuchanych</th>
    </tr>
    </thead>
    <tbody>
    @php
        $sum_bad = $sum_good = $sum_all = $sum_succes = 0;
    @endphp
    @foreach($reports as $report)
            <tr>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->department_info->departments->name.' '.$report->department_info->department_type->name}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->online_consultant}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->success}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->count_all_check}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->count_good_check}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->count_bad_check}}</td>

                @php
                    $sum_bad += $report->count_bad_check;
                    $sum_good += $report->count_good_check;
                    $sum_all += $report->count_all_check;
                    $sum_succes += $report->success;
                    $proc_good = $proc_bad = 0;
                    if($report->count_all_check != 0)
                    {
                        $proc_good = round(($report->count_good_check*100) / $report->count_all_check,2);
                        $proc_bad = round(($report->count_bad_check*100) / $report->count_all_check,2);
                        $proc_check = round(($report->count_all_check*100) / $report->success,2);
                    }
                @endphp
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$proc_bad}} %</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$proc_check}} %</td>
            </tr>
    @endforeach




    @php
        $all_bad_proc = $all_good_proc = $all_check_proc = 0;
            if($sum_all != 0)
            {
                $all_bad_proc = round(($sum_bad*100) / $sum_all,2);
                $all_good_proc = round(($sum_good*100) / $sum_all,2);
                $all_check_proc = round( ($sum_all*100) / $sum_succes,2);
            }
    @endphp
    <tr>
        <td colspan="2" style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px"><b>Total</b></td>
        <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$sum_succes}}</td>
        <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$sum_all}}</td>
        <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$sum_good}}</td>
        <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$sum_bad}}</td>
        <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$all_bad_proc}} %</td>
        <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$all_check_proc}} %</td>
    </tr>
    </tbody>
</table>
