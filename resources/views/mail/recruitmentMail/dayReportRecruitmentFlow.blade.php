@php
    $id = 0;
@endphp

<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="1" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">Statystyki spływu rekrutacji  </font></td>
        <td colspan="5" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Spływ</th>
        @foreach($data['source'] as $src)
            <th style="border:1px solid #231f20;padding:3px;background:#231f20" data-source_id="{{$src->id}}">{{$src->name}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($data['data'] as $item)
        <tr>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->name.' '.$item->dep_type}}</td>
            <td  style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->count_flow}}</td>
            @foreach($data['source'] as $src)
                @php
                    $id = $src->id;
                @endphp
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->$id->count_source}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
