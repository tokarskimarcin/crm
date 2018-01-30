<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
<td colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
<font size="4" face="sans-serif">RAPORT TYGODNIOWY DKJ {{$date_start . ' - ' . $date_stop}} </td>
<td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://teambox.pl/image/logovc.png"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Oddział</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Jany</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">% błędnych</th>
</tr>
</thead>
<tbody>

@php
    $i = 1;
    $total_all = 0;
    $total_good = 0;
    $total_bad = 0;
@endphp

@foreach($dkj as $item)

    @if($item->type == 'Badania/Wysyłka' && $item->wysylka != 0)
        <tr>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type}} Wysyłka</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka - $item->bad_wysylka}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_wysylka}}</td>
              @if($item->bad_wysylka > 0)
                  <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_wysylka / $item->wysylka * 100, 2)}} %</td>
              @else
                  <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
              @endif

              @php
                  $total_all += $item->wysylka;
                  $total_good += $item->wysylka - $item->bad_wysylka;
                  $total_bad += $item->bad_wysylka;
                  $i++;
            @endphp
        </tr>
    @endif
    @if($item->type == 'Badania/Wysyłka' && $item->badania != 0)
      <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type}} Badania</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania - $item->bad_badania}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_badania}}</td>
            @if($item->bad_badania > 0)
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_badania / $item->badania * 100, 2)}} %</td>
            @else
                <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
            @endif

            @php
                $total_all += $item->badania;
                $total_good += $item->badania - $item->bad_badania;
                $total_bad += $item->bad_badania;
                $i++;
          @endphp
      </tr>
    @endif
    @if($item->type == 'Badania')
        <tr>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type}} Badania</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->badania - $item->bad_badania}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_badania}}</td>
              @if($item->bad_badania > 0)
                  <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_badania / $item->badania * 100, 2)}} %</td>
              @else
                  <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
              @endif
        </tr>
        @php
            $total_all += $item->badania;
            $total_good += $item->badania - $item->bad_badania;
            $total_bad += $item->bad_badania;
            $i++;
        @endphp
    @elseif($item->type == 'Wysyłka')
        <tr>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type}} Wysyłka</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->wysylka - $item->bad_wysylka}}</td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->bad_wysylka}}</td>
              @if($item->bad_wysylka > 0)
                  <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->bad_wysylka / $item->wysylka * 100, 2)}} %</td>
              @else
                  <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
              @endif
        </tr>
        @php
            $total_all += $item->wysylka;
            $total_good += $item->wysylka - $item->bad_wysylka;
            $total_bad += $item->bad_wysylka;
            $i++;
        @endphp
    @endif

@endforeach

<tr>
      <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;"><b>TOTAL</b></td>
      <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_all}}</td>
      <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_good}}</td>
      <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_bad}}</td>
      @if($total_all > 0)
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($total_bad / $total_all * 100, 2)}} %</td>
      @else
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
      @endif

</tr>


  <tbody>
</table>
