<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
        <tr>
            <td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
                <font size="6" face="Calibri">Raport Rotacji Rekrutacji <br>{{date('Y.m.d', strtotime($period->date_start)).'-'.date('Y.m.d', strtotime($period->date_stop))}}</font></td>
            <td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
                <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
        </tr>
        <tr>
            <th style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">Oddział</th>
            <th style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">Lb. osób <br>zatrudnionych</th>
            <th style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">Lb. osób, które odeszły/<br>zwolnione (przez system)</th>
            <th style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">Współczynnik rotacji</th>
            <th style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">Lb. osób, które <br>odeszły (<30RBH)</th>
        </tr>
    </thead>
    <tbody>
    @php
        $new_accounts_total = 0;
        $end_work_total = 0;
        $disabled_by_system_total = 0;
        $working_users_total = 0;
        $users_less_30rbh_total = 0;
    @endphp
    @foreach($data as $department)
        @php
            $dep = $departments->where('id', $department->id)->first();
            $new_accounts_total += $department->new_accounts_sum;
            $end_work_total += $department->end_work_sum;
            $disabled_by_system_total += $department->disabled_by_system_sum;
            $working_users_total += $department->working_users_sum;
            $users_less_30rbh_total += $department->users_less_30rbh_sum;
        @endphp
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$dep->departments->name}} {{$dep->department_type->name}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$department->new_accounts_sum}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$department->end_work_sum}} ({{$department->disabled_by_system_sum}})</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$department->working_users_sum > 0 ? round($department->end_work_sum*100/$department->working_users_sum,2) : 0}}%</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$department->users_less_30rbh_sum}}</td>
        </tr>
    @endforeach
    <tr>
        <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>Total</b></td>
        <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>{{$new_accounts_total}}</b></td>
        <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>{{$end_work_total}} ({{$disabled_by_system_total}})</b></td>
        <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>{{$working_users_total > 0 ? round($end_work_total*100/$working_users_total,2) : 0}}%</b></td>
        <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>{{$users_less_30rbh_total}}</b></td>
    </tr>
    </tbody>
</table>