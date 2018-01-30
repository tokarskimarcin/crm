<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
<thead style="color:#efd88f;">
<tr>
    @php
        $date_start = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
        $date_stop = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
    @endphp
<td colspan="5" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
    <font size="5" face="sans-serif">Tygodniowy Raport Wykorzystania Bazy {{$date_start.' - '.$date_stop}}</font></td>
<td colspan="5" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
<img src="http://teambox.pl/image/logovc.png"></td>
</tr>
<tr>
    <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
    <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Imie</th>
    <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Nazwisko</th>
    <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Bisnode</th>
    <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Zgody</th>
    <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Event</th>
    <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Reszta</th>
    <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Exito</th>
    <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Suma</th>
</tr>
</thead>
    @php $i=1; @endphp
    <tbody>
    @if(!empty($overall_result) && $overall_result[0]->suma != 0)
    <tr>
        <td colspan=3 style="border:1px solid #231f20;text-align:center;padding:3px;"><b>Ogół</b></td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{round(($overall_result[0]->bisnode*100)/$overall_result[0]->suma)}} %</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{round(($overall_result[0]->zgody*100)/$overall_result[0]->suma)}} %</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{round(($overall_result[0]->event*100)/$overall_result[0]->suma)}} %</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{round(($overall_result[0]->reszta*100)/$overall_result[0]->suma)}} %</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{round(($overall_result[0]->exito*100)/$overall_result[0]->suma)}} %</td>
        <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$overall_result[0]->suma}}</td>
    </tr>
        @foreach($departments_statistic as $item)
            @if(!empty($item->suma) && $item->suma !=0)
            <tr>
                <td colspan=3 style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$item->name}}</b></td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{round(($item->bisnode*100)/$item->suma)}} %</b></td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{round(($item->zgody*100)/$item->suma)}} %</b></td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{round(($item->event*100)/$item->suma)}} %</b></td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{round(($item->reszta*100)/$item->suma)}} %</b></td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{round(($item->exito*100)/$item->suma)}} %</b></td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$item->suma}}</b></td>
            </tr>
            @foreach($employee_statistic as $value)
                @if( $item->id == $value->dep_id )
                    <tr>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i++}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$value->name}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$value->last}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round(($value->bisnode*100)/$value->suma)}} %</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round(($value->zgody*100)/$value->suma)}} %</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round(($value->event*100)/$value->suma)}} %</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round(($value->reszta*100)/$value->suma)}} %</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round(($value->exito*100)/$value->suma)}} %</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$value->suma}}</td>
                    </tr>
                @endif
            @endforeach
            @endif
        @endforeach
    @endif


    </tbody>
</table>
