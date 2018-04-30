
@for($level = 1; $level<= 3; $level ++)
    @php $name = ''; @endphp
    @if($level ==1 )
        @php $name = 'Średnie'; @endphp
    @elseif ($level == 2)
        @php $name = 'Jakość'; @endphp
    @else
        @php $name = 'RBH'; @endphp
    @endif
    <table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
        <thead style="color:#efd88f">
        <tr>
            <td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
                <font size="6" face="Calibri">RAPORT Coaching Dyrektor Tygodniowo/Miesięczny {{$dep_info->departments->name . ' ' . $dep_info->department_type->name}} TYP: {{$name}}  </font></td>
        </tr>
        </thead>
    </table>

    @php
        $total_sum_in_progress = 0;
        $total_sum_end_possitive = 0;
        $total_sum_end_negative = 0;
        $total_sum_general = 0;
        $total_unsettled = 0;
        $total_sum_of_coachings = 0;
        $week_number = 1;
        $startDate = '';
        $stopDate = '';
    @endphp
    <table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px; margin-bottom: 20px;">
        <thead style="color:#efd88f">
        <tr>
            <th colspan="3" style="border:1px solid #231f20;padding:3px;background:#231f20;text-align: center"></th>
            <th colspan="2" style="border:1px solid #231f20;padding:3px;background:#50504f;text-align: center;">Rozliczone</th>
            <th colspan="2" style="border:1px solid #231f20;padding:3px;background:#231f20;text-align: center"></th>
        </tr>
        <tr>
            {{--<th colspan="5" style="border:1px solid #231f20;padding:3px;background:#231f20;text-align: center">TYDZIEN </th>--}}

            <th style="border:1px solid #231f20;padding:3px;background:#231f20;text-align: center">Dyrektor</th>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">W toku</th>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Nierozliczone</th>
            <th style="border:1px solid #231f20;padding:3px;background:#50504f;text-align: center">Cel osiągnięty</th>
            <th style="border:1px solid #231f20;padding:3px;background:#50504f;text-align: center">Cel nieosiągnięty</th>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba coachingów</th>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Licznik celu</th>
        </tr>
        </thead>
        <tbody id="tableBody">
        {{--dla średniej--}}
        @foreach($all_coaching as $item)
            @php
                //ograniczenie coachingu dla sredniej
                $coaching_avg = $item->where('coaching_type','=',$level);
                    $sum_in_progress = 0;
                    $sum_end_possitive = 0;
                    $sum_end_negative = 0;
                    $sum_unsettled = 0;
                    $sum_general = 0;
                    $sum_of_coachings = 0;
            @endphp
            @if(is_object($coaching_avg))
                @foreach($coaching_avg as $coach)
                    @php
                        $coacing_sum_type = 0;
                            if($level == 1)
                                $coacing_sum_type = $coach->coaching_sum_avg;
                            elseif($level == 2)
                                $coacing_sum_type = $coach->coaching_sum_jakny;
                            else
                                $coacing_sum_type =$coach->coaching_sum_rgh;
                    @endphp
                    <tr>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$coach->first_name.' '.$coach->last_name}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$coach->in_progress}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$coach->unsettled}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$coach->end_possitive}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$coach->end_negative}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$coach->in_progress+$coach->unsettled+$coach->end_possitive+$coach->end_negative}}</td>
                        <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$coacing_sum_type}}</td>

                    </tr>
                    @php
                        $startDate = $coach->start_date;
                        $stopDate = $coach->stop_date;
                        $sum_unsettled += $coach->unsettled;
                        $sum_in_progress += $coach->in_progress;
                        $sum_end_possitive += $coach->end_possitive;
                        $sum_end_negative += $coach->end_negative;
                        $sum_general += $coach->in_progress+$coach->unsettled+$coach->end_possitive+$coach->end_negative;
                        $sum_of_coachings += $coacing_sum_type;
                    @endphp
                @endforeach
            @endif
            <tr style="background:#c67979;font-weight:bolder;">
                <td style="border:1px solid #231f20;text-align:center;padding:3px">Suma {{$startDate}} - {{$stopDate}} </td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$sum_in_progress}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$sum_unsettled}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$sum_end_possitive}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$sum_end_negative}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$sum_general}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$sum_of_coachings}}</td>
            </tr>
            @php
                $total_unsettled += $sum_unsettled;
                $total_sum_in_progress += $sum_in_progress ;
                $total_sum_end_possitive += $sum_end_possitive;
                $total_sum_end_negative += $sum_end_negative;
                $total_sum_general += $sum_general;
                $total_sum_of_coachings += $sum_of_coachings;
            @endphp
        @endforeach
        </tbody>
        <tfoot>
        <tr style="background:#efef7f;font-weight:bolder;">
            <td style="border:1px solid #231f20;text-align:center;padding:3px">Total</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_sum_in_progress}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_unsettled}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_sum_end_possitive}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_sum_end_negative}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_sum_general}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$total_sum_of_coachings}}</td>
        </tr>
        @php
            $total_unsettled = 0;
            $total_sum_in_progress = 0 ;
            $total_sum_end_possitive = 0;
            $total_sum_end_negative = 0;
            $total_sum_general = 0;
            $total_sum_of_coachings = 0;
        @endphp
        </tfoot>
    </table>

@endfor;
<script>


</script>