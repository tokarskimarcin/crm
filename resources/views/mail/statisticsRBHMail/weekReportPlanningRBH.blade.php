

<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
    <thead style="color:#efd88f;">
    <tr>
        <td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
            <font size="6" face="sans-serif">Tygodniowy Raport (Planowanie) {{$SfirstDate}} {{$SlastDate}}</td>
        <td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
            <img src="http://teambox.pl/image/logovc.png"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Oddział</th>
        @for($i=1;$i<=7;$i++)
            @if($i == 1)
                @php
                    $SfirstDatepom = date('m-d', strtotime($SfirstDate))
                @endphp
            @else
                @php
                    $SfirstDate = date('Y-m-d', strtotime($SfirstDate. ' + 1 days'));
                    $SfirstDatepom = date('m-d', strtotime($SfirstDate))
                @endphp
            @endif
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">{{\App\Schedule::$polishDate[$i-1]}} {{$SfirstDatepom}}</th>
        @endfor
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Suma</th>
    </tr>
    </thead>
    <tbody>

    @php
    $i = 1;
    @endphp
    @foreach($CsheduleInfo as $item)
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i++}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->departmentConcatName}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sec_monday}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sec_tuesday}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sec_wednesday}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sec_thursday}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sec_friday}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sec_saturday}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sec_sunday}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->sec_sum}}</td>
        </tr>
    @endforeach
    <tr>
    <tbody>
</table>
