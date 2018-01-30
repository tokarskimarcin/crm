
<table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
    <thead style="color:#efd88f;">
        <tr>
            <td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
            <font size="6" face="sans-serif">RAPORT GODZINNY<br>DKJ - WYSYŁKA</td>
            <td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
            <img src="http://xdes.pl/logovc.png"></td>
            </tr>
            <tr>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Oddział</th>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Godzina</th>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Jany</th>
            <th style="border:1px solid #231f20;padding:3px;background:#231f20;">% błędnych</th>
        </tr>
    </thead>
<tbody>

@php $i = 1; @endphp
@foreach($dkj as $dk)
      @if($dk->type == 'Wysyłka' || $dk->type == 'Badania/Wysyłka')
      <tr>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$i}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;width:170px;">{{$dk->dep_name  .' '. $dk->dep_type}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$hour}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$dk->sum}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$dk->good}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$dk->bad}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($dk->bad / $dk->sum, 2) * 100}} % </td>
      </tr>
        @php $i++;  @endphp
    @endif

@endforeach


<tbody>
    </table><br>
    <table style ="width:100%;border:1px solid #231f20;border-collapse:collapse;padding:3px;">
        <thead style="color:#efd88f;">
            <tr>
                <td colspan="6" style="border:1px solid #231f20;text-align:center;padding:3px;background:#231f20; color:#efd88f;">
                <font size="6" face="sans-serif">RAPORT GODZINNY<br>DKJ - BADANIA</td>
                <td colspan="6" style="border:1px solid #231f20;text-align:left;padding:6px;background:#231f20;">
                <img src="http://xdes.pl/logovc.png"></td>
                </tr>
                <tr>
                <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Lp.</th>
                <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Oddział</th>
                <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Godzina</th>
                <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba odsłuchanych rozmów</th>
                <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Liczba poprawnych rozmów</th>
                <th style="border:1px solid #231f20;padding:3px;background:#231f20;">Jany</th>
                <th style="border:1px solid #231f20;padding:3px;background:#231f20;">% błędnych</th>
            </tr>
        </thead>
<tbody>
@php $y = 1;  @endphp
@foreach($dkj as $dk)
      @if($dk->type == 'Badania' || $dk->type == 'Badania/Wysyłka')
      <tr>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$y}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;width:170px;">{{$dk->dep_name  .' '. $dk->dep_type}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$hour}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$dk->sum}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$dk->good}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{$dk->bad}}</td>
          <td style="border:1px solid #231f20;text-align:center;padding:3px;">{{round($dk->bad / $dk->sum, 2) * 100}} % </td>
      </tr>
        @php $y++;  @endphp
    @endif

@endforeach


  <tbody>
  </table>
  <div style="width:10px;height:20px;"></div>
  <div style="display: block;">
  Wiadomość została wygenerowana automatycznie, prosimy na nią nie odpowiadać.</div>
  <div style="display: block;">
  Wszelkie uwagi oraz sugestie proszę kierować na adres: jarzyna.verona@gmail.com</div>
