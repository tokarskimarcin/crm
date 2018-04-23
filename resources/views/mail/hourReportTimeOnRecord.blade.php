
<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px" class="table table-bordered">
<thead style="color:#efd88f">
<tr>
<td colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
<font size="6" face="Calibri">RAPORT POTWIERDZANIE POŁĄCZENIA LUBLIN {{$date}}</font></td>
<td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
<img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
</tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">LP</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa Konsultanta</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Czas na rekord</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Czas rozmów</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Pozostało</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Zgody</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Odmowy</th>
    </tr>
</thead>
    <tbody>
      @php
        $lp = 1;
      @endphp
      @foreach($reports->sortby('time_on_record') as $report)
          @if($report->team_name == 'LUBLIN_POTWIERDZENIA')
              @if($report->time_on_record < '00:02:30')
                  <tr style="background-color: #e25454a3;">
              @else
                  <tr>
              @endif
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$lp++}}</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->campain}}</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->consultant_name}}</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->time_on_record}}</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->time_call}} %</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->left_record}}</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->approvals}}</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->refusals}}</td>
              </tr>
          @endif
      @endforeach
    </tbody>
</table>


<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px" class="table table-bordered">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="4" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="6" face="Calibri">RAPORT POTWIERDZANIE POŁĄCZENIA RADOM WYSYŁKA {{$date}}</font></td>
        <td colspan="4" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">LP</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa Konsultanta</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Czas na rekord</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Czas rozmów</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Pozostało</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Zamknięte</th>
    </tr>
    </thead>
    <tbody>
    @php
        $lp = 1;
    @endphp
    @foreach($reports->sortby('time_on_record') as $report)
        @if($report->team_name == 'Potwierdzenia_Wysylka')
            @if($report->time_on_record < '00:02:30')
                <tr style="background-color: #e25454a3;">
             @else
                <tr>
            @endif
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$lp++}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->campain}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->consultant_name}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->time_on_record}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->time_call}} %</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->left_record}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->closed_record}}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
