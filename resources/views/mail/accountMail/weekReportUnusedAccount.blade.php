@if($user_to_show->user_type_id == 3)
    <div class="alert alert-info"><strong>ADMIN info</strong> Raport dla dyrektorów</div>
@endif
{{-- table for directors--}}
@if(in_array($user_to_show->user_type_id, $user_type_ids_for_departments_report) > 0)
    <table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
        <thead style="color:#efd88f">
        <tr>
            <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
                <font size="6" face="Calibri">Raport Nieaktywnych Kont Konsultantów</font></td>
            <td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
                <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
        </tr>
        <tr>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba kont nieaktywnych od 7 dni</th>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba kont dezaktywowanych (dzisiaj) - nieaktywnych od 14 dni</th>
        </tr>
        </thead>
        <tbody>
        @php
            $all_warning = 0;
            $all_disable = 0;
        @endphp

        @foreach($department_info as $item)
            @if(count($users_warning->where('department_info_id','=',$item->id)) > 0 || count($users_disable->where('department_info_id','=',$item->id)) > 0)
                <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$item->departments->name.' '.$item->department_type->name}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{count($users_warning->where('department_info_id','=',$item->id))}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{count($users_disable->where('department_info_id','=',$item->id))}}</td>
                </tr>
                @php
                    $all_warning += count($users_warning->where('department_info_id','=',$item->id));
                    $all_disable += count($users_disable->where('department_info_id','=',$item->id))
                @endphp
            @endif

        @endforeach
        <tr>
            <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>Total</b></td>
            <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>{{$all_warning}}</b></td>
            <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>{{$all_disable}}</b></td>
        </tr>
        </tbody>
    </table>
@endif

@if($user_to_show->user_type_id == 3)
    <div class="alert alert-info" style="margin-top: 1em"><strong>ADMIN info</strong>  Raport dla kierowników i kierowników regionalnych</div>
@endif
{{-- table for managers--}}
@if(in_array($user_to_show->user_type_id, $user_type_ids_for_managers_report) > 0)
    <table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
        <thead style="color:#efd88f">
        <tr>
            <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
                <font size="6" face="Calibri">Raport Nieaktywnych Kont Konsultantów</font></td>
            <td colspan="2" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
                <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
        </tr>
        <tr>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Trener</th>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba kont nieaktywnych od 7 dni</th>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba kont dezaktywowanych (dzisiaj) - nieaktywnych od 14 dni</th>
        </tr>
        </thead>
        <tbody>
        @php
            $all_warning = 0;
            $all_disable = 0;
            $departments = $department_info;
            if($user_to_show->user_type_id != 3){

                if($user_to_show->user_type_id == 7){
                    $departments = $department_info->where('menager_id', $user_to_show->id);
                }
                if($user_to_show->user_type_id == 17){
                    $departments = $department_info->where('regionalManager_id', $user_to_show->id);
                }
            }
        @endphp

        @foreach($departments as $dep_info)
            @if(count($users_warning->where('department_info_id','=',$dep_info->id)) > 0 || count($users_disable->where('department_info_id','=',$dep_info->id)) > 0)
                <tr>
                    <th colspan="3" style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f">{{$dep_info->departments->name.' '.$dep_info->department_type->name}}</th>
                </tr>
            @endif
            @foreach($coaches->where('department_info_id',$dep_info->id) as $coach)
                @if(count($users_warning->where('coach_id','=',$coach->id)) > 0 || count($users_disable->where('coach_id','=',$coach->id)) > 0)
                    <tr>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$coach->last_name.' '.$coach->first_name}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{count($users_warning->where('coach_id','=',$coach->id))}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{count($users_disable->where('coach_id','=',$coach->id))}}</td>
                    </tr>
                    @php
                        $all_warning += count($users_warning->where('coach_id','=',$coach->id));
                        $all_disable += count($users_disable->where('coach_id','=',$coach->id));
                    @endphp
                @endif

            @endforeach
            @if(count($users_warning->where('department_info_id',$dep_info->id)->where('coach_id',null)) > 0 || count($users_disable->where('department_info_id',$dep_info->id)->where('coach_id',null)) > 0)
                <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px"> - </td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{count($users_warning->where('department_info_id',$dep_info->id)->where('coach_id',null))}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{count($users_disable->where('department_info_id',$dep_info->id)->where('coach_id',null))}}</td>
                </tr>
                @php
                    $all_warning += count($users_warning->where('department_info_id',$dep_info->id)->where('coach_id',null));
                    $all_disable += count($users_disable->where('department_info_id',$dep_info->id)->where('coach_id',null));
                @endphp
            @endif
        @endforeach
        <tr>
            <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>Total</b></td>
            <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>{{$all_warning}}</b></td>
            <td style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"><b>{{$all_disable}}</b></td>
        </tr>
        </tbody>
    </table>
@endif

@if($user_to_show->user_type_id == 3)
    <div class="alert alert-info" style="margin-top: 1em"><strong>ADMIN info</strong>  Raporty dla trenerów</div>
@endif
{{-- table for trainers--}}
@if(in_array($user_to_show->user_type_id, $user_type_ids_for_trainers_report) > 0)
    <table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
        <thead style="color:#efd88f">
        <tr>
            <td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
                <font size="6" face="Calibri">Raport Dezaktywowanych Kont Konsultantów</font></td>
            <td  style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
                <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
        </tr>
        <tr>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20">Lp. </th>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwisko i imię konsultanta</th>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Niezalogowany od X dni</th>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Data ostatniego logowania</th>
        </tr>
        </thead>
        <tbody>
        @php
            $total = 0;
        @endphp
        @foreach($department_info as $dep_info)
            @php
                $lp = 1;
                $all_warning = 0;
                $all_disable = 0;
                $users_disable_for_trainer = $users_disable->where('department_info_id',$dep_info->id);
                if ($user_to_show->user_type_id != 3){
                    $users_disable_for_trainer = $users_disable_for_trainer->where('coach_id', $user_to_show->id);
                }
            @endphp

            @if(count($users_disable_for_trainer)>0)
                <tr>
                    <th colspan="4" style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f">{{$dep_info->departments->name.' '.$dep_info->department_type->name}}</th>
                </tr>
            @endif
            @foreach($users_disable_for_trainer->sortBy('last_login') as $user)
                <tr>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$lp++}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$user->last_name.' '.$user->first_name}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{intval(abs(strtotime($user->last_login)-strtotime(date('Y-m-d')))/86400)}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$user->last_login}}</td>
                </tr>
                @php
                    $total++;
                @endphp
            @endforeach
        @endforeach
        <tr>
            <th style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f">Total</th>
            <th style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f">{{$total}}</th>
            <th colspan="2" style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"></th>
        </tr>
        </tbody>
    </table>
    <table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
        <thead style="color:#efd88f">
        <tr>
            <td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
                <font size="6" face="Calibri">Raport Nieaktywnych Kont Konsultantów</font></td>
            <td  style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
                <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
        </tr>
        <tr>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20">Lp. </th>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwisko i imię konsultanta</th>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Niezalogowany od X dni</th>
            <th  style="border:1px solid #231f20;padding:3px;background:#231f20">Data ostatniego logowania</th>
        </tr>
        </thead>
        <tbody>
        @php
            $total = 0;
        @endphp
        @foreach($department_info as $dep_info)
            @php
                $lp = 1;
                $all_warning = 0;
                $all_disable = 0;
                $users_warning_for_trainer = $users_warning->where('department_info_id',$dep_info->id);
                if ($user_to_show->user_type_id != 3){
                    $users_warning_for_trainer = $users_warning_for_trainer->where('coach_id', $user_to_show->id);
                }
            @endphp

            @if(count($users_warning_for_trainer)>0)
            <tr>
                <th colspan="4" style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f">{{$dep_info->departments->name.' '.$dep_info->department_type->name}}</th>
            </tr>
            @endif
            @foreach($users_warning_for_trainer->sortBy('last_login') as $user)
                    <tr>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$lp++}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$user->last_name.' '.$user->first_name}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{intval(abs(strtotime($user->last_login)-strtotime(date('Y-m-d')))/86400)}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$user->last_login}}</td>
                    </tr>
                    @php
                        $total++;
                    @endphp
            @endforeach
        @endforeach
        <tr>
            <th style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f">Total</th>
            <th style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f">{{$total}}</th>
            <th colspan="2" style="background: #444444;border:1px solid #231f20;text-align:center;padding:3px; color:#efd88f"></th>
        </tr>
        </tbody>
    </table>

@endif