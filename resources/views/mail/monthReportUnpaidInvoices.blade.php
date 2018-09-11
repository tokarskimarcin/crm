<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px">
<thead style="color:#efd88f">
<tr>
<td colspan="5" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
<font size="6" face="Calibri">Raport Niezapłaconych faktur</font></td>
<td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
<img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
</tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa klienta</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa hotelu</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Data wysłania</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Kara</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Tel. do płatności</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Mail do płatności</th>
    </tr>
</thead>
    <tbody>
    @php
        $totalPenalty = 0;
        $totalRecords = 0;
    @endphp
    @foreach($info as $data)
            <tr>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$data->invoice_name}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$data->name}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$data->invoice_send_date}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$data->penalty}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$data->payment_phone}}</td>
                <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$data->payment_mail}}</td>
            </tr>

        @php
            $totalRecords++;
            $totalPenalty += $data->penalty
        @endphp
    @endforeach
        <tr>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"><b>Total:</b></td>
              <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$totalRecords}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px">{{$totalPenalty}}</td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"></td>
            <td style="border:1px solid #231f20;text-align:center;padding:3px"></td>
        </tr>

    </tbody>
</table>
