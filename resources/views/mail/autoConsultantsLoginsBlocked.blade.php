
<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px" class="table table-bordered">
    <thead style="color:#efd88f">
    <tr>
        <th colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">AUTOMATYCZNIE ZABLOKOWANE KONTA</font></th>
        <th colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></th>
    </tr>

    <tr>
        <th colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="5" face="Calibri">{{$date}} </font></th>
        <th colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
        </th>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">LP</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa oddziału</th>
        <th colspan="2" style="border:1px solid #231f20;padding:3px;background:#231f20">Liczba zablokowanych kont</th>
    </tr>
    </thead>
    <tbody>
    @php
        $lp = 1;
        $suma = 0;
    @endphp
    @foreach($data as $department)
        <tr>
            <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$lp++}}</td>
            <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$department['dep_name']}}</td>
            <td colspan="2" style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$department['count']}}</td>
        </tr>

        @php
            $suma += $department['count'];
        @endphp
        @endforeach
    <tr>
        <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px;background:#fffc8b"></td>
        <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px;background:#fffc8b">SUMA:</td>
        <td colspan="2" style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px;background:#fffc8b">{{$suma}}</td>
    </tr>
    </tbody>
</table>
