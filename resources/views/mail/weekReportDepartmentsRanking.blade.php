<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px; margin-bottom: 20px;">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="5" face="Calibri">Raport Filii {{ $data->first()->start_day. ' - ' . $data->first()->stop_day }}</font></td>
    </tr>
    <tr>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20; width: 15%;">FILIA</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">% CELU</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">% JANKÓW</td>
        <td style="border:1px solid #231f20;padding:3px;background:#231f20;">NAGRODA</td>
    </tr>
    </thead>
    <tbody>
    @php
        $bonus = 150;
        $lp = 0;
        $in_ranking = $data->where('janky_proc','<=',5);
        $outOfRankign = $data->where('janky_proc','>',5);
    @endphp
@foreach($in_ranking as $item)
                <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item->department_name }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item->week_goal_proc }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item->janky_proc }}</b></td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $lp < 3 ? $bonus : ''}}</b></td>
                    @php
                        $bonus -= 50;
                        $lp++;
                    @endphp
                </tr>
@endforeach
    @foreach($outOfRankign as $item)
        <tr style="background: rgb(244,204,204) ">
            <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item->department_name }}</b></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item->week_goal_proc }}</b></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>{{ $item->janky_proc }}</b></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"><b></b></td>
        </tr>
    @endforeach
    </tbody>
</table>