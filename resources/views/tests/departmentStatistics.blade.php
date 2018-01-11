@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Statystyki oddziału</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Oddział</b>
            </div>
            <div class="panel-body">
                Lublin potwierdzanie
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Ilość przeprowadzonych testów</b>
            </div>
            <div class="panel-body">
                53
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Ilość pracowników kadry</b>
            </div>
            <div class="panel-body">
                12
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div id="donutchart" style="width: 550; height: 350;"></div>
    </div>
    <div class="col-md-6">
        <div id="drawChartUsers" style="width: 550; height: 350;"></div>
    </div>
    <div class="col-md-6">
        <div id="drawChartType" style="width: 550; height: 350;"></div>
    </div>
    <div class="col-md-6">
        <div id="drawChartTester" style="width: 550; height: 350;"></div>
    </div>
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
          title: 'Rezultaty oddziału',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }

google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChartUsers);
      function drawChartUsers() {
        var data = google.visualization.arrayToDataTable([
          ['Ilość', 'Statystyki'],
          ['Mariola Pindola', 3],
          ['Adam Małysz',      4],
          ['Badam Małysz',      4],
          ['Ladam Małysz',      4],
          ['Amadam Małysz',      4]
        ]);

        var options = {
          title: 'Pracownicy',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('drawChartUsers'));
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


google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChartTester);
      function drawChartTester() {
        var data = google.visualization.arrayToDataTable([
          ['Ilość', 'Statystyki'],
          ['Alicja Galicja', 3],
          ['Józef Zenoniuk', 4],
          ['Robert Biedroń', 2]
        ]);

        var options = {
          title: 'Osoba testująca',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('drawChartTester'));
        chart.draw(data, options);
      }
</script>
@endsection
