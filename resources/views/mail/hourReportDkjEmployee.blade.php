<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
<td colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
<font size="4" face="sans-serif">RAPORT GODZINNY PRACOWNICY DKJ - BADANIA ({{$hour}})</td>
<td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://teambox.pl/image/logovc.png"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Pracownik DKJ</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba niepoprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba podważonych janków</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba usuniętych</th>
</tr>
</thead>
<tbody>

@php
    $i = 1;
    $total_user_sum = 0;
    $total_user_janek = 0;
    $total_user_not_janek = 0;
    $total_manager_disagre = 0;
    $total_dkj_deleted = 0;
@endphp
@foreach($dkj as $item)
    @if($item->dating_type == 0)
    @php
        $create_column = true;
        $create_total_up = true;
    @endphp
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->first_name . ' ' . $item->last_name}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_sum}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_not_janek}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_janek}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_manager_disagre}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dkj_deleted}}</td>
        </tr>

        @php
            $total_user_sum += $item->user_sum;
            $total_user_janek += $item->user_janek;
            $total_user_not_janek += $item->user_not_janek;
            $total_manager_disagre += $item->user_manager_disagre;
            $total_dkj_deleted += $item->dkj_deleted;
        @endphp

        @php $i++; @endphp
    @endif
@endforeach

@if(isset($create_total_up) && $create_total_up == true)
    <tr>
        <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;"><b>Total</b></td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_sum}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_not_janek}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_janek}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_manager_disagre}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_dkj_deleted}}</td>
    </tr>
@endif

</tbody>
</table>
<br>
<br>
<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
<td colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
<font size="4" face="sans-serif">RAPORT GODZINNY PRACOWNICY DKJ - WYSYŁKA ({{$hour}})</td>
<td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://teambox.pl/image/logovc.png"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Pracownik DKJ</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba niepoprawnych rozmów</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba podważonych janków</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba usuniętych</th>
</tr>
</thead>
<tbody>

@php
    $y = 1;
    $total_user_sum = 0;
    $total_user_janek = 0;
    $total_user_not_janek = 0;
    $total_manager_disagre = 0;
    $total_dkj_deleted = 0;
@endphp
@foreach($dkj as $item)
    @if($item->dating_type == 1)
    @php
        $create_column = true;
        $create_total_down = true;
    @endphp
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$y}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->first_name . ' ' . $item->last_name}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_sum}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_not_janek}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_janek}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->user_manager_disagre}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$item->dkj_deleted}}</td>
        </tr>

        @php
            $total_user_sum += $item->user_sum;
            $total_user_janek += $item->user_janek;
            $total_user_not_janek += $item->user_not_janek;
            $total_manager_disagre += $item->user_manager_disagre;
            $total_dkj_deleted += $item->dkj_deleted;
        @endphp

        @php $y++; @endphp
    @endif
@endforeach

@if(isset($create_total_down) && $create_total_down == true)
    <tr>
        <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;"><b>Total</b></td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_sum}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_not_janek}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_user_janek}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_manager_disagre}}</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$total_dkj_deleted}}</td>
    </tr>
@endif




</tbody>
</table>
