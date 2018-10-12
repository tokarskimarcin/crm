<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
<thead style="color:#efd88f">
<tr>
<td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
<font size="6" face="Calibri">Tygodniowy 30 Rbh {{$date_start}} - {{$date_stop}}</font></td>
<td colspan="5" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
<img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
</tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba Zatwierdzonych Janków</th>
    </tr>
</thead>
<tbody>
@php

@endphp

@foreach($data as $department_info_id)
    <h1></h1>
@endforeach

@foreach($dkj as $item)
    <tr>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->confirmed_janky}}</td>

    </tr>
    @php

    @endphp

@endforeach

    <tr>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>TOTAL</b></td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$total_confirmed_janky}}</b></td>
    </tr>

</tbody>
</table>
