

<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
    <thead style="color:#efd88f;">
    <tr>
        <td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
            <font size="6" face="sans-serif">RAPORT DZIENNY DKJ {{$today}}</td>
        <td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
            <img src="http://teambox.pl/image/logovc.png"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba Zaproszeń</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba błędnych rozmów</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">% błędnych</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">% Odsłuchanych</th>
    </tr>
    </thead>
    <tbody>

    @php
        $i = 1;
        $sum_all_talks = 0;
        $sum_all_good = 0;
        $sum_all_bad = 0;
        $sum_proc = 0;
        $sum_succes = 0;
        $sum_proc_check = 0;
    @endphp
    @foreach($dkj as $item)
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
            @if($item->department_info_id == 13)
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">Radom Potwierdzenia Badania </td>
            @elseif($item->department_info_id == 4)
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">Radom Potwierdzenia Wysyłka </td>
            @else
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$item->dep}} {{$item->depname}}</td>
            @endif
            <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$item->success}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sum_all_talks}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sum_correct_talks}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sum_janky}}</td>
            @php
                $bad_proc = $item->sum_all_talks > 0 ? (100*$item->sum_janky) / $item->sum_all_talks : 0;
                $check_proc = $item->success > 0 ? (100*$item->sum_all_talks) / $item->success : 0;
                $i++;
                $sum_all_talks += $item->sum_all_talks;
                $sum_all_good += $item->sum_correct_talks;
                $sum_all_bad += $item->sum_janky;
                $sum_succes += $item->success;
                $sum_proc = $sum_all_talks > 0 ? (100*$sum_all_bad) / $sum_all_talks : 0;
                $sum_proc_check = $sum_succes > 0 ? (100*$sum_all_talks) / $sum_succes : 0;

            @endphp
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($bad_proc,2)}} %</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($check_proc,2)}} %</td>
        </tr>
    @endforeach
    <tr>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;font-weight:bolder;" colspan="2">Total</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;font-weight:bolder;">{{$sum_succes}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;font-weight:bolder;">{{$sum_all_talks}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;font-weight:bolder;">{{$sum_all_good}} </td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;font-weight:bolder;">{{$sum_all_bad}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;font-weight:bolder;">{{round($sum_proc,2)}} %</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;font-weight:bolder;">{{round($sum_proc_check,2)}} %</td>
    </tr>

    <tbody>
</table>

