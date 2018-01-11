@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Statystyki testu</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Tytuł testu</b>
            </div>
            <div class="panel-body">
                Test kompetencji dla kierownika
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Stwórca</b>
            </div>
            <div class="panel-body">
                Imie Nazwisko
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Data dodania</b>
            </div>
            <div class="panel-body">
                2019-12-12
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Ilość testów</b>
            </div>
            <div class="panel-body">
                10
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div id="donutchart" style="width: 450; height: 250;"></div>
    </div>
    <div class="col-md-4">
        <div id="drawChartType" style="width: 450; height: 250;"></div>
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
