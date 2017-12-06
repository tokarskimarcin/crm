<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
<td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
<font size="6" face="sans-serif">RAPORT DZIENNY DKJ {{$today}}</td>
<td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://xdes.pl/logovc.png"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Oddział</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Data</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Jany</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">% błędnych</th>
</tr>
</thead>
<tbody>

@php($i = 1)
@php($total_all = 0)
@php($total_good = 0)
@php($total_bad = 0)

@foreach($dkj as $item)

    @if($item->type == 'Badania/Wysyłka' && $item->wysylka != 0)
        <tr>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type}} Wysyłka</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$today}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania - $item->bad_badania}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_badania}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_badania / $item->badania * 100, 2)}} %</td>

              @php($total_all += $item->badania)
              @php($total_good += $item->badania - $item->bad_badania)
              @php($total_bad += $item->bad_badania)
              @php($i++)
        </tr>
    @elseif($item->type == 'Badania/Wysyłka' && $item->badania != 0)
      <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type}} Badania</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$today}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka - $item->bad_wysylka}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_wysylka}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_wysylka / $item->wysylka * 100, 2)}} %</td>

            @php($total_all += $item->wysylka)
            @php($total_good += $item->wysylka - $item->bad_wysylka)
            @php($total_bad += $item->bad_wysylka)
            @php($i++)
      </tr>
    @elseif($item->type = 'Badania')
        <tr>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$today}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania - $item->bad_badania}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_badania}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_badania / $item->badania * 100, 2)}} %</td>
        </tr>
        @php($total_all += $item->badania)
        @php($total_good += $item->badania - $item->bad_badania)
        @php($total_bad += $item->bad_badania)
        @php($i++)
    @else
        <tr>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$today}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka - $item->bad_wysylka}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_wysylka}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_wysylka / $item->wysylka * 100, 2)}} %</td>
        </tr>
        @php($total_all += $item->wysylka)
        @php($total_good += $item->wysylka - $item->bad_wysylka)
        @php($total_bad += $item->bad_wysylka)
        @php($i++)
    @endif

@endforeach

<tr>
      <td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;"><b>TOTAL</b></td>
      <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_all}}</td>
      <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_good}}</td>
      <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_bad}}</td>
      <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($total_bad / $total_all * 100, 2)}} %</td>
</tr>


  <tbody>
</table>
<div style="width:10px;height:20px;"></div>
<div style="display: block;">
Wiadomość została wygenerowana automatycznie, prosimy na nią nie odpowiadać.</div>
<div style="display: block;">
Wszelkie uwagi oraz sugestie proszę kierować na adres: wojciech.mazur@veronaconsulting.pl</div>
