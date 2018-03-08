<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">Miesięczne Statystyki Spływu Rekrutacji  </font></td>
        <td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Imie</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwisko</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Spływ</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as  $item)
        <tr>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->first_name}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->last_name}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->name}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->count_flow}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
