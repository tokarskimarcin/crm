<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
<thead style="color:#efd88f">
<tr>
<td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
<font size="6" face="Calibri">Raport Podważonych Janków {{$date_start}} - {{$date_stop}}</font></td>
<td colspan="5" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
<img src="https://ci3.googleusercontent.com/proxy/2Yaz8WsJ34uYOsanmpfkEZKbZDP2-sOQDVLB5TQdLCq6R7YzBCfaGc6K2bNRItA=s0-d-e1-ft#http://xdes.pl/logovc.png" class="CToWUd"></td>
</tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba Zatwierdzonych Janków</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba Podważonych Janków</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Pozostawione</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Anulowane</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Usuniete</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Zatwierdzonych</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Podważonych Janków</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Pozostawionych</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Anulowanych</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">% Usunietych</th>

    </tr>
</thead>
<tbody>
@php
    $total_janky_sum = 0;
    $total_confirmed_janky = 0;
    $total_unconfirmed_janky = 0;
    $total_unchecked_janky = 0;
    $total_anulled_janky = 0;
    $total_deleted_janky = 0;
@endphp
@foreach($dkj as $item)
    <tr>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dep_name . ' ' . $item->dep_name_type}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->confirmed_janky}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->unconfirmed_janky}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->unchecked_janky}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->anulled_janky}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->deleted_janky}}</td>
        @if($item->confirmed_janky != 0)
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->confirmed_janky / $item->janky_sum * 100, 2)}} %</td>
        @else
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
        @endif
        @if($item->unconfirmed_janky != 0)
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->unconfirmed_janky / $item->janky_sum * 100, 2)}} %</td>
        @else
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
        @endif
        @if($item->unchecked_janky != 0)
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->unchecked_janky / $item->janky_sum * 100, 2)}} %</td>
        @else
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
        @endif
        @if($item->anulled_janky != 0)
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->anulled_janky / $item->janky_sum * 100, 2)}} %</td>
        @else
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
        @endif
        @if($item->deleted_janky != 0)
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($item->deleted_janky / $item->janky_sum * 100, 2)}} %</td>
        @else
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">0 %</td>
        @endif
    </tr>
    @php
        $total_janky_sum += $item->janky_sum;
        $total_confirmed_janky += $item->confirmed_janky;
        $total_unconfirmed_janky += $item->unconfirmed_janky;
        $total_unchecked_janky += $item->unchecked_janky;
        $total_anulled_janky += $item->anulled_janky;
        $total_deleted_janky += $item->deleted_janky;
    @endphp

@endforeach

    <tr>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>TOTAL</b></td>
    </tr>

    <tbody>
