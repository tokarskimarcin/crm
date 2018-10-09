<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
        <tr>
            <td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
                <font size="6" face="Calibri">Raport Rotacji Rekrutacji</font></td>
            <td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
                <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
        </tr>
        <tr>
            <th style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">Oddział</th>
            <th style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">Lb. osób zatrudnionych</th>
            <th style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">Lb. osób, które odeszły (w tym przez system)</th>
            <th style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">Współczynnik rotacji</th>
            <th style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">Lb. osób, które odeszły (<30RBH)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $department)
        @php
            $dep = $departments->where('id', $department->id)->first()
        @endphp
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$dep->departments->name}} {{$dep->department_type->name}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$department->new_accounts_sum}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$department->end_work_sum}} ({{$department->disabled_by_system_sum}})</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$department->working_users_sum > 0 ? round($department->end_work_sum*100/$department->working_users_sum,2) : 0}}%</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$department->users_less_30rbh_sum}}</td>
        </tr>
    @endforeach
    </tbody>
</table>