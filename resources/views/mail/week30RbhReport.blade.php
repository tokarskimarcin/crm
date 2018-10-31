<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="1" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f; width: 40%;">
            <font size="6" face="Calibri">Tygodniowy 30 Rbh(zbiorczy) {{$date_start}} - {{$date_stop}}</font></td>
        <td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Oddział</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Imie i nazwisko</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Zgody</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Średnia</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Czas pracy</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Janki</th>
    </tr>
    </thead>
<tbody>
@php
    $total_iterator = 0;
    $total_success = 0;
    $total_rbh = 0;
    $total_calls = 0;
    $total_checked_talks = 0;
    $total_janki = 0;
@endphp
@foreach($regionalManagersInstructors as $instructor)
    @php
        $instructor_total_iterator = 0;
        $instructor_total_success = 0;
        $instructor_total_rbh = 0;
        $instructor_total_calls = 0;
        $instructor_total_checked_talks = 0;
        $instructor_total_janki = 0;
        $instructor_iterator = 0;
    @endphp

    <tr style="background-color: lightslategrey; font-size: 1.3em;text-align: center;font-weight: bold;">
        <td  colspan="6">{{$instructor->first_name}} {{$instructor->last_name}}</td>
    </tr>
    @foreach($data as $department_name => $department_info)
        @php
            $firstItem = isset($department_info[0]) ? $department_info[0] : null;
        @endphp

        @if($firstItem->instructor_regional_id == $instructor->instructor_regional_id)

            @php
                $i = 0;

                $iterator = 0;
                $sum_success = 0;
                $sum_janki = 0;
                $sum_rbh = 0;
                $average = 0;

                $sum_calls = 0;
                $sum_checked_talks = 0;
                $janki_percent = 0;

                foreach($department_info as $item) {
                    $iterator++;
                    $rbh_temp = round($item->sec_sum / 3600,2);
                    $sum_success += $item->success;
                    $sum_janki += $item->janki;
                    $sum_calls += $item->received_calls;
                    $sum_checked_talks += $item->all_checked_talks;
                    $sum_rbh += $rbh_temp;
                }
                $average = $sum_rbh > 0 ? round($sum_success / $sum_rbh,2) : 0;
                $janki_percent = $sum_checked_talks > 0 ? round(100 * $sum_janki / $sum_checked_talks,2) : 0;
            @endphp
            @foreach($department_info as $info)
                <tr style="@if($info->average > $average) background-color:#d4f7ce; @else background-color:#ffe3e6; @endif">
                    @if($i == 0)
                        <td rowspan="{{$iterator}}" style="border:1px solid #231f20;text-align:center;padding:3px;font-size:1.5em;font-weight:bold; background-color: lightgrey;">{{$department_name}}</td>
                    @endif
                    @php
                        $i++;
                        $rbh = round($info->sec_sum / 3600,2);
                        $total_success += $info->success;
                        $instructor_total_success += $info->success;
                        $instructor_iterator += $iterator;
                    @endphp
                    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$info->first_name}} {{$info->last_name}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$info->success}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$info->average}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$rbh}}</td>
                    <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$info->janki}}</td>
                </tr>
            @endforeach
            <tr>

                @php
                    $total_rbh += $sum_rbh;
                    $total_janki += $sum_janki;
                    $total_calls += $sum_calls;
                    $total_checked_talks += $sum_checked_talks;

                    $instructor_total_iterator = 0;

                    $instructor_total_rbh += $sum_rbh;
                    $instructor_total_calls += $sum_calls;
                    $instructor_total_checked_talks += $sum_checked_talks;
                    $instructor_total_janki += $sum_janki;
                @endphp

                <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: #464a51; color: white;">Podsumowanie {{$department_name}}</td>
                <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: #464a51; color: white;">{{$iterator}}</td>
                <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: #464a51; color: white;">{{$sum_success}}</td>
                <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: #464a51; color: white;">{{$average}}</td>
                <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: #464a51; color: white;">{{$sum_rbh}} RBH</td>
                <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: #464a51; color: white;">{{$janki_percent}}%</td>
            </tr>
        @endif
    @endforeach
    @php
        $total_instructor_average = $instructor_total_rbh > 0 ? round($instructor_total_success / $instructor_total_rbh, 2) : 0;
        $total_instructor_janki_percent = $instructor_total_checked_talks > 0 ? round(100 * $instructor_total_janki / $instructor_total_checked_talks,2) : 0;
    @endphp

    <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: black; color: white;">Podsumowanie {{$instructor->first_name}} {{$instructor->last_name}}</td>
    <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: black; color: white;">{{$instructor_iterator}}</td>
    <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: black; color: white;">{{$instructor_total_success}} </td>
    <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: black; color: white;">{{$total_instructor_average}} </td>
    <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: black; color: white;">{{$instructor_total_rbh}} </td>
    <td style="text-align: center; font-size: 1.2em; font-weight: bold; background-color: black; color: white;">{{$total_instructor_janki_percent}}%</td>

@endforeach

@php
    $total_average = $total_rbh > 0 ? round($total_success / $total_rbh, 2) : 0;
    $total_janki_percent = $total_checked_talks > 0 ? round(100 * $total_janki / $total_checked_talks,2) : 0;
@endphp

<tr style="background-color: orange; font-size: 1.4em;">
    <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>TOTAL</b></td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$total_iterator}}</b></td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$total_success}}</b></td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$total_average}}</b></td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$total_rbh}} RBH</b></td>
    <td style="border:1px solid #231f20;text-align:center;padding:3px;"><b>{{$total_janki_percent}}%</b></td>
</tr>

</tbody>
</table>


