
<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px" class="table table-bordered">
<thead style="color:#efd88f">
<tr>
<td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
<font size="5" face="Calibri">RAPORT POTWIERDZANIE POŁĄCZENIA LUBLIN {{$date}}</font></td>
<td colspan="3" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
<img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
</tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">LP</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa Konsultanta</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Czas na rekord</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Czas rozmów</th>
    </tr>
</thead>
    <tbody>
      @php
        $lp = 1;
      @endphp
      @foreach($reports as $report)
          @if($report->team_name == 'LUBLIN_POTWIERDZENIA')
              <tr>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$lp++}}</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->campain}}</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->consultant_name}}</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->time_on_record}}</td>
                  <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->time_call}} %</td>
              </tr>
          @endif
      @endforeach
    </tbody>
</table>


<table style="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px" class="table table-bordered">
    <thead style="color:#efd88f">
    <tr>
        <td colspan="3" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20;color:#efd88f">
            <font size="5" face="Calibri">RAPORT POTWIERDZANIE POŁĄCZENIA RADOM WYSYŁKA {{$date}}</font></td>
        <td colspan="3" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20">
            <img src="http://teambox.pl/image/logovc.png" class="CToWUd"></td>
    </tr>
    <tr>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">LP</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Nazwa Konsultanta</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Czas na rekord</th>
        <th style="border:1px solid #231f20;padding:3px;background:#231f20">Czas rozmów</th>
    </tr>
    </thead>
    <tbody>
    @php
        $lp = 1;
    @endphp
    @foreach($reports as $report)
        @if($report->team_name == 'Potwierdzenia_Wysylka')
            <tr>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$lp++}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->campain}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->consultant_name}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->time_on_record}}</td>
                <td style="font-weight: bold;border:1px solid #231f20;text-align:center;padding:3px">{{$report->time_call}} %</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
