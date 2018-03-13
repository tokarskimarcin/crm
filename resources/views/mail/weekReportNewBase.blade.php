<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
<td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
    @php
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
    @endphp
<font size="5" face="sans-serif">Tygodniowy raport nowych zgód {{$date_start.' - '.$date_stop}}</td>
<td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://teambox.pl/image/logovc.png"></td>
</tr>
<tr>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Baza</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">Ilość</th>
<th style="border:1px solid #231f20;padding:3px;background:#231f20;">% Całości</th>
</tr>
</thead>
<tbody>
@php $all_record = $bisnode +$aggree+$event+$exito+$rest; @endphp
<tr>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{1}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">Bisnode</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$bisnode}}</td>
    @php
        $proc_bis = 0;
        if($all_record != 0)
            $proc_bis = round($bisnode*100/$all_record,2);
    @endphp
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$proc_bis}} %</td>
</tr>
<tr>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{2}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">Zgody</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$aggree}}</td>
    @php
        $proc_agr = 0;
        if($all_record != 0)
            $proc_agr = round($aggree*100/$all_record,2);
    @endphp
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$proc_agr}} %</td>
</tr>
<tr>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{3}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">Event</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$event}}</td>
    @php
        $proc_event = 0;
        if($all_record != 0)
            $proc_event = round($event*100/$all_record,2);
    @endphp
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$proc_event}} %</td>
</tr>
<tr>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{4}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">Reszta</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$rest}}</td>
    @php
        $proc_rest = 0;
        if($all_record != 0)
            $proc_rest = round($rest*100/$all_record,2);
    @endphp
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$proc_rest}} %</td>
</tr>
<tr>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{5}}</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">Exito</td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$exito}}</td>
    @php
        $proc_exito = 0;
        if($all_record != 0)
            $proc_exito = round($exito*100/$all_record,2);
    @endphp
    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$proc_exito}} %</td>
</tr>
<tr>
    <td colspan=2 style="border:1px solid #231f20;text-align:center;padding:3px;">Suma</td>
    <td colspan=2 style="border:1px solid #231f20;text-align:center;padding:3px;">{{$all_record}}</td>
</tr>



  <tbody>
</table>
