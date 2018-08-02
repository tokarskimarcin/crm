@php
    $active_proc = 0;
    $dep_names = array(
    'WR' => 'Radom',
    'TSK' => 'Skarżysko Kamienna',
    'TOS' => 'Ostrowiec Św.',
    'TST' => 'Starachowice',
    'LBN' => 'Lublin',
    'LKR' => 'Kraśnik',
    'LZA' => 'Zamość',
    'LCH' => 'Chełm',
    'LDZ' => 'Łódź',
    'BST' => 'Białystok',
    'EDZ' => 'Zduńska Wola'
    );
@endphp

<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">RAPORT DZIENNY ZUŻYCIA BAZY {{$today}} </font></td>
        <td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Nazwa</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Wszystkie kampanie</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Wszystkie aktywne</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align:center;">Procent aktywnych</th>
    </tr>
    </thead>
    <tbody>

    @foreach($dep_names as $key => $value)
        @php
            $item = $data->where('split_name', '=', $key)->first();
        @endphp
        @if(is_object($item))
            @php
                $active_proc = $item->all_campaigns > 0 ? round((100* $item->active_campaigns) / $item->all_campaigns,2) : 0;
            @endphp
            <tr>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$value}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->all_campaigns}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->active_campaigns}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$active_proc}}%</td>
            </tr>
            @php
                $active_proc = 0;
            @endphp
        @else
            <tr>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$value}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">0</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">0</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">0%</td>
            </tr>
        @endif
    @endforeach
    @php
        $active_proc_total = $sum[0]->sum_campaign > 0 ? round((100* $sum[0]->sum_active) / $sum[0]->sum_campaign,2) : 0;
    @endphp
            <tr>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px">SUMA</td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px">{{$sum[0]->sum_campaign}} </td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px">{{$sum[0]->sum_active}} </td>
                <td style="background-color: #efef7f;border:1px solid #231f20;text-align:center;padding:3px">{{$active_proc_total}}%</td>
            </tr>
    </tbody>
</table>
