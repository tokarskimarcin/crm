
<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px" class="table table-bordered">
    <thead style="color:#efd88f">
    <tr>
        <th colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">LISTA OSÓB Z OSTATNIM LOGOWANIEM DŁUŻEJ NIŻ 2 TYGODNIE {{$date}}</font></th>
        <th colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></th>
    </tr>
    <tr>
        <th colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="5" face="Calibri">{{$dep_name}} </font></th>
        <th colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
           </th>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">LP</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Imię</th>
        <th colspan="2" style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwisko</th>
    </tr>
    </thead>
    <tbody>
    @php
        $lp = 1;
    @endphp
    @foreach($data as $user)
        <tr>
            <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$lp++}}</td>
            <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$user['first_name']}}</td>
            <td colspan="2" style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$user['last_name']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
