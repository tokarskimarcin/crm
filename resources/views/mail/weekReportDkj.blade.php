<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
<td colspan="5" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
<font size="6" face="sans-serif">RAPORT TYGODNIOWY PRACOWNICY DKJ - BADANIA</td>
<td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://xdes.pl/logovc.png"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Pracownik DKJ</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Zakres Dat</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba nie poprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Czas Pracy</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Średnia na godzinę</th>
</tr>
</thead>
<tbody>

@php($i = 1)
@php($total_user_sum = 0)
@php($total_user_janek = 0)
@php($total_user_not_janek = 0)
@php($total_work_hour = 0)
@php($total_avg = 0)
@foreach($dkj as $item)
    @if($item->dating_type == 0)
        @php($create_total_up = true)

        @php($time_array_user = explode(":", $item->work_time))
        @php($user_time = round((($time_array_user[0] * 3600) + ($time_array_user[1] * 60) + $time_array_user[2]) / 3600, 2))
        @php($user_avg = round(intval($item->user_sum) / $user_time, 2))
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->first_name . ' ' . $item->last_name}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$date_start . ' - ' . $date_stop}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_sum}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_janek}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_not_janek}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->work_time}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$user_avg}}</td>
        </tr>

        @php($total_user_sum += $item->user_sum)
        @php($total_user_janek += $item->user_janek)
        @php($total_user_not_janek += $item->user_not_janek)
        @php($total_work_hour += $user_time)
        @php($total_avg += $user_avg)
        @php($i++)
    @endif
@endforeach

@if(isset($create_total_up) && $create_total_up == true)
    <tr>
        <td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;"><b>Total</b></td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_sum}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_janek}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_not_janek}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_work_hour}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($total_user_sum / $total_work_hour, 2)}}</td>
    </tr>
@endif

</tbody>
</table>
<br>
<br>
<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
<td colspan="5" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
<font size="6" face="sans-serif">RAPORT TYGODNIOWY PRACOWNICY DKJ - WYSYŁKA</td>
<td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://xdes.pl/logovc.png"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Pracownik DKJ</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Zakres Dat</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba nie poprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Czas Pracy</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Średnia na godzinę</th>
</tr>
</thead>
<tbody>

@php($y = 1)
@php($total_user_sum = 0)
@php($total_user_janek = 0)
@php($total_user_not_janek = 0)
@php($total_work_hour = 0)
@php($total_avg = 0)
@foreach($dkj as $item)
    @if($item->dating_type == 1)
        @php($create_total_down = true)

        @php($time_array_user = explode(":", $item->work_time))
        @php($user_time = round((($time_array_user[0] * 3600) + ($time_array_user[1] * 60) + $time_array_user[2]) / 3600, 2))
        @php($user_avg = round(intval($item->user_sum) / $user_time, 2))
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$y}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->first_name . ' ' . $item->last_name}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$date_start . ' - ' . $date_stop}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_sum}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_janek}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_not_janek}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->work_time}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$user_avg}}</td>
        </tr>

        @php($total_user_sum += $item->user_sum)
        @php($total_user_janek += $item->user_janek)
        @php($total_user_not_janek += $item->user_not_janek)
        @php($total_work_hour += $user_time)
        @php($total_avg += $user_avg)
        @php($y++)
    @endif
@endforeach

@if(isset($create_total_down) && $create_total_down == true)
    <tr>
        <td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;"><b>Total</b></td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_sum}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_janek}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_not_janek}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_work_hour}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($total_user_sum / $total_work_hour, 2)}}</td>
    </tr>
@endif




</tbody>
</table>
<div style="width:10px;height:20px;"></div>
<div style="display: block;">
Wiadomość została wygenerowana automatycznie, prosimy na nią nie odpowiadać.</div>
<div style="display: block;">
Wszelkie uwagi oraz sugestie proszę kierować na adres: wojciech.mazur@veronaconsulting.pl</div>
