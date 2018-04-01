<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="2" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">RAPORT Coaching Tygodniowo Miesięczny {{$dep->departments->name . ' ' . $dep->department_type->name}} </font></td>
    </tr>
    </thead>
</table>

@php
    $week_number = 1;
@endphp

@foreach($all_coaching as $item)

    <table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
        <thead style="color:#efd88f">
            <tr>
                <td rowspan="2" style="border:1px solid #231f20;padding:3px;background:#231f20;text-align: center">Trener</td>
                <td colspan="3" style="border:1px solid #231f20;padding:3px;background:#231f20;text-align: center">TYDZIEN {{$week_number++}}</td>
            </tr>
            <tr>
                <td style="border:1px solid #231f20;padding:3px;background:#231f20;">ZREALIZOWANE</td>
                <td style="border:1px solid #231f20;padding:3px;background:#231f20;">NIEZREALIZOWANE</td>
                <td style="border:1px solid #231f20;padding:3px;background:#231f20;">LICZBA COACHINGÓW</td>
            </tr>
        </thead>
        <tbody id="tableBody">
        @foreach($item as $coach)
            <tr>
                <td>{{$coach->first_name.' '.$coach->last_name}}</td>
                <td>{{$coach->end_possitive}}</td>
                <td>{{$coach->end_negative}}</td>
                <td>{{$coach->sum_all_coaching}}</td>
            </tr>

        @endforeach
        </tbody>
    </table>
@endforeach
<script>


</script>