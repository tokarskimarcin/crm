@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Statystyki testów</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div id="donutchart" style="width: 450px; height: 250px;"></div>
    </div>
    <div class="col-md-4">
        <div id="piechart" style="width: 450px; height: 250px;"></div>
    </div>
    <div class="col-md-4">
        <div id="donutchartType" style="width: 450px; height: 250px;"></div>
    </div>
    <div class="col-md-12">
        <div id="months" style="width: 100%; height: 500px;"></div>
    </div>
</div>



<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#by_users">Pracownicy</a></li>
  <li><a data-toggle="tab" href="#by_departments">Oddziały</a></li>
  <li><a data-toggle="tab" href="#by_tests">Testy</a></li>
</ul>

<div class="tab-content">
  <div id="by_users" class="tab-pane fade in active">
  <div class="table-responsive" style="margin-top: 20px">
  <table class="table table-striped">
      <thead>
          <tr>
              <th>Lp.</th>
              <th>Użytkownik</th>
              <th>Ilosć testów</th>
              <th>Zaliczone</th>
              <th>Niezaliczone</th>
              <th>Skuteczność %</th>
          </tr>
      </thead>
      <tbody>
          <tr>
              <td>1</td>
              <td>Antoni Macierewicz</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr>
          <tr>
          <td>1</td>
          <td>Antoni Macierewicz</td>
          <td>10</td>
          <td>5</td>
          <td>5</td>
          <td>50%</td>
      </tr>
      <tr>
              <td>1</td>
              <td>Antoni Macierewicz</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr>
          <tr>
              <td>1</td>
              <td>Antoni Macierewicz</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr>
          <tr>
              <td>1</td>
              <td>Antoni Macierewicz</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr>
          
      </tbody>
  </table>
</div>
  </div>
  <div id="by_departments" class="tab-pane fade">
  <div class="table-responsive" style="margin-top: 20px">
  <table class="table table-striped">
      <thead>
          <tr>
              <th>Lp.</th>
              <th>Oddział</th>
              <th>Ilosć testów</th>
              <th>Zaliczone</th>
              <th>Niezaliczone</th>
              <th>Skuteczność %</th>
          </tr>
      </thead>
      <tbody>
          <tr>
              <td>1</td>
              <td>Lublin telemarketing</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr>
          <tr>
          <td>1</td>
          <td>Lublin telemarketing</td>
          <td>10</td>
          <td>5</td>
          <td>5</td>
          <td>50%</td>
      </tr><tr>
              <td>1</td>
              <td>Lublin telemarketing</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr><tr>
              <td>1</td>
              <td>Lublin telemarketing</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr>
          
      </tbody>
  </table>
</div>
  </div>
  <div id="by_tests" class="tab-pane fade">
  <div class="table-responsive" style="margin-top: 20px">
  <table class="table table-striped">
      <thead>
          <tr>
              <th>Lp.</th>
              <th>Nazwa testu</th>
              <th>Ilosć testów</th>
              <th>Zaliczone</th>
              <th>Niezaliczone</th>
              <th>Skuteczność %</th>
          </tr>
      </thead>
      <tbody>
          <tr>
              <td>1</td>
              <td>Super trudny test kompetencji</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr>
          <tr>
              <td>1</td>
              <td>Super trudny test kompetencji</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr> <tr>
              <td>1</td>
              <td>Super trudny test kompetencji</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr> <tr>
              <td>1</td>
              <td>Super trudny test kompetencji</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr> <tr>
              <td>1</td>
              <td>Super trudny test kompetencji</td>
              <td>10</td>
              <td>5</td>
              <td>5</td>
              <td>50%</td>
          </tr>
          
      </tbody>
  </table>
</div>
  </div>
</div>






@endsection

@section('script')
<script>

google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Zaliczone', {{$tests->where('result', '=', 1)->count()}}],
          ['Niezaliczone', {{$tests->where('result', '=', 2)->count()}}]
        ]);

        var options = {
          title: 'Ogólna ocena testów',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }

        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChartDep);
        function drawChartDep() {
            var data = google.visualization.arrayToDataTable([
            ['Language', 'Speakers (in millions)'],
            @foreach($departments_stats as $item)
                ['{{$item->dep_name . ' ' .  $item->dep_type_name}}', {{$item->dep_sum}}],
            @endforeach
            ['', 0]
        ]);

        var options = {
            legend: 'none',
            pieSliceText: 'label',
            title: 'Ilość testów w oddziałach',
            pieStartAngle: 100,
      };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }

      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChartType);
      function drawChartType() {
            var data = google.visualization.arrayToDataTable([
            ['Task', 'Ilość rozmów'],
            @foreach($stats_by_user_type as $item)
                ['{{$item->user_type}}', '{{$item->user_type_sum}}'],
            @endforeach
            ['', 0]
        ]);

        var options = {
          title: 'Ilość testów (typ użytkownika)',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchartType'));
        chart.draw(data, options);
      }

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChartmonths);

      function drawChartmonths() {
        var data = google.visualization.arrayToDataTable([
          ['Miesiąc', 'Suma testów', 'Testy zaliczone', 'Testy niezaliczone'],
          ['Styczeń',  1000,      400, 20],
          ['Luty',  1170,      460, 50],
          ['Marzec',  660,       1120, 100],
          ['Kwiecień',  1030,      540, 0],
          ['Maj',  1030,      540, 56],
          ['Czerwiec',  1030,      540, 0],
          ['Lipiec',  1030,      540, 500],
          ['Sierpień',  1030,      540, 0],
          ['Wrzesień',  1030,      540, 20],
          ['Październik',  1030,      540, 0],
          ['Listopad',  0,      0, 0],
          ['Grudzień',  0,      0, 0]
        ]);

        var options = {
          title: 'Statystyki roczne',
          hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('months'));
        chart.draw(data, options);
      }

     
</script>
@endsection
