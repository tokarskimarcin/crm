<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">Tygodniowy Raport Nieaktywnych Kont Konsultantów</font></td>
        <td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Ilość kont nieaktywnych od 7 dni</th>
        <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Ilość kont dezaktywowanych (14 dni)</th>
    </tr>
    </thead>
    <tbody>
    @php
        $all_warning = 0;
        $all_disable = 0;
    @endphp

    @foreach($department_info as $item)
        @if(count($users_warning->where('department_info_id','=',$item->id)) > 0 || count($users_disable->where('department_info_id','=',$item->id)) > 0)
            <tr>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->departments->name.' '.$item->department_type->name}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{count($users_warning->where('department_info_id','=',$item->id))}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{count($users_disable->where('department_info_id','=',$item->id))}}</td>
            </tr>
            @php
                $all_warning += count($users_warning->where('department_info_id','=',$item->id));
                $all_disable += count($users_disable->where('department_info_id','=',$item->id))
            @endphp
        @endif

    @endforeach
    <tr>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>Total</b></td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$all_warning}}</b></td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$all_disable}}</b></td>
    </tr>
    </tbody>
</table>
