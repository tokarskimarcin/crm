@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav">Testy / Statystyki testów</div>
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
</div>

@endsection

@section('script')
<script>

google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Task', 'Hours per Day'],
        ['Zaliczone', {{$results->good}}],
        ['Niezaliczone', {{$results->bad}}]
        ]);

        var options = {
        title: 'Ogólna ocena testów',
        pieHole: 0.4,
        colors: ['#53e041', '#d81c32', '#dae23d']
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
                {{--  ['{{$item->user_type}}', '{{$item->user_type_sum}}'],  --}}
                ['123', 10],
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

</script>
@endsection
