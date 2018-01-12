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
                {{$user->first_name . ' ' . $user->last_name}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Oddział</b>
            </div>
            <div class="panel-body">
                {{$user->department_info->departments->name . ' ' . $user->department_info->departments->name}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Stanowisko</b>
            </div>
            <div class="panel-body">
                {{$user->user_type->name}}
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
              <th>Osoba testująca</th>
              <th>Rezultat</th>
          </tr>
        </thead>
        <tbody>
            @php($i = 0)
            @foreach($user->userTests as $test)
                @php($i++)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$test->name}}</td>
                    <td>{{$test->cadre->first_name . ' ' . $test->cadre->last_name}}</td>
                    <td>
                        @if($test->result == 1)
                            <span style="color:green">Pozytywny</span>
                        @elseif($test->result == 2)
                            <span style="color:red">Negatywny</span>
                        @else
                            <span>Brak oceny</span>
                        @endif
                    </td>
                </tr>
            @endforeach
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
          ['Zaliczone', Number({{$user->userTests->where('result', '=', 1)->count()}})],
          ['Niezaliczone', Number({{$user->userTests->where('result', '=', 2)->count()}})]
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
            @foreach($cadre as $item)
                ['{{$item->first_name . ' ' . $item->last_name}}', {{$item->cadre_sum}}],
            @endforeach
            ['Default', 0]
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
             @foreach($categories as $item)
                ['{{$item->name}}', {{$item->sum}}],
            @endforeach
            ['Default', 0]
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
