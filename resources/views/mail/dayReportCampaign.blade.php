<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">RAPORT DZIENNY KAMPANII {{$today}} </font></td>
        <td colspan="3" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Nazwa</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Wszystkie kampanie</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Wszystkie aktywne</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Wszystkie otrzymane</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Wszystkie nieotrzymane</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
            <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->name}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->all_campaigns}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->active_campaigns}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->received_campaigns}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->unreceived_campaigns}}</td>
            </tr>
    @endforeach
            <tr>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px">SUMA</td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px">{{$sum[0]->sum_campaign}} </td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px">{{$sum[0]->sum_active}} </td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px">{{$sum[0]->sum_received}} </td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px">{{$sum[0]->sum_unreceived}} </td>
            </tr>
    </tbody>
</table>
