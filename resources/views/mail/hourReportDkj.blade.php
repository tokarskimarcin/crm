<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
<td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
<font size="6" face="sans-serif">RAPORT GODZINNY<br>DKJ - WYSYŁKA</td>
<td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://xdes.pl/logovc.png"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Oddział</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Godzina</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Jany</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">% błędnych</th>
</tr>
</thead>
<tbody>


@php($i = 1)
@php($total_badania = 0)
@php($total_bad_badania = 0)

@foreach($dkj as $item)
    @if($item->type == 'Badania/Wysyłka' && $item->badania > 0)
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type . ' Badania'}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$date_stop}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania - $item->bad_badania}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_badania}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_badania / $item->badania * 100, 2)}} %</td>
        </tr>
        @php($i++)
        @php($total_badania += $item->badania)
        @php($total_bad_badania += $item->bad_badania)
    @elseif($item->type == 'Badania')
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type . ' Badania'}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$date_stop}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania - $item->bad_badania}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_badania}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_badania / $item->badania * 100, 2)}} %</td>
        </tr>
        @php($i++)
        @php($total_badania += $item->badania)
        @php($total_bad_badania += $item->bad_badania)
    @endif

@endforeach

<tr>
    <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;"><b>Total</b></td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$date_stop}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_badania}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_badania - $total_bad_badania}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_bad_badania}}</td>
    @if($total_bad_badania != 0)
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($total_bad_badania / $total_badania * 100, 2)}} %</td>
    @else
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
    @endif
</tr>

</tbody>
</table><br>
<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
<td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
<font size="6" face="sans-serif">RAPORT GODZINNY<br>DKJ - BADANIA</td>
<td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://xdes.pl/logovc.png"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Oddział</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Godzina</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Jany</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">% błędnych</th>
</tr>
</thead>
<tbody>


@php($y = 1)
@php($total_wysylka = 0)
@php($total_bad_wysylka = 0)

@foreach($dkj as $item)
    @if($item->type == 'Badania/Wysyłka' && $item->wysylka > 0)
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$y}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type . ' Wysyłka'}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$date_stop}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka - $item->bad_wysylka}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_wysylka}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_wysylka / $item->wysylka * 100, 2)}} %</td>
        </tr>
          @php($y++)
          @php($total_wysylka += $item->wysylka)
          @php($total_bad_wysylka += $item->bad_wysylka)
    @elseif($item->type == 'Wysylka')
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$y}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type . ' Wysyłka'}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$date_stop}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka - $item->bad_wysylka}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_wysylka}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_wysylka / $item->wysylka * 100, 2)}} %</td>
        </tr>
          @php($y++)
          @php($total_wysylka += $item->wysylka)
          @php($total_bad_wysylka += $item->bad_wysylka)
    @endif

@endforeach

<tr>
    <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;"><b>Total</b></td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$date_stop}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_wysylka}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_wysylka - $total_bad_wysylka}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_bad_wysylka}}</td>
    @if($total_bad_wysylka != 0)
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($total_bad_wysylka / $total_wysylka * 100, 2)}} %</td>
    @else
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
    @endif

</tr>

</tbody>
</table>

