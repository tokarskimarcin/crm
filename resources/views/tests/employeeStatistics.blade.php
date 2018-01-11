@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Statystyki użytkownika</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Imie i naswisko</b>
            </div>
            <div class="panel-body">
                Jarosław Polskezbaw
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Oddział</b>
            </div>
            <div class="panel-body">
                Lublin Telemarketing
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Stanowisko</b>
            </div>
            <div class="panel-body">
                Kierownik
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div id="donutchart" style="width: 450; height: 250;"></div>
    </div>
    <div class="col-md-4">
        <div id="drawChartTester" style="width: 450; height: 250;"></div>
    </div>
    <div class="col-md-4">
        <div id="drawChartType" style="width: 450; height: 250;"></div>
    </div>
</div>

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



@endsection

@section('script')
<script>

google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Ilość', 'Statystyki'],
          ['Zaliczone', 3],
          ['Niezaliczone',      4]
        ]);

        var options = {
          title: 'Rezultaty pracownika',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }

google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChartTester);
      function drawChartTester() {
        var data = google.visualization.arrayToDataTable([
          ['Ilość', 'Osoba testująca'],
          ['Jan Kowalski', 3],
          ['Donald Tusk', 5]
        ]);

        var options = {
          title: 'Osoba testująca',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('drawChartTester'));
        chart.draw(data, options);
      }

google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChartType);
      function drawChartType() {
        var data = google.visualization.arrayToDataTable([
          ['Ilość', 'Statystyki'],
          ['FUKO', 3],
          ['SMART', 4],
          ['FART', 2],
          ['ART', 3],
          ['ŻART', 9]
        ]);

        var options = {
          title: 'Typy zagadnień',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('drawChartType'));
        chart.draw(data, options);
      }
</script>
@endsection
