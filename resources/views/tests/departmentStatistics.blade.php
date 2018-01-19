@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Testy / Statystyki oddziałów @isset($department) / {{$department->departments->name . ' ' . $department->department_type->name}} @endisset</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <form action="{{ URL::to('/department_statistics') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="form-group">
                    <select class="form-control" name="dep_id">
                        <option value="0">Wybierz</option>
                        @foreach($department_info as $item)
                            <option @if(isset($id) && $id == $item->id) selected @endif value="{{$item->id}}">{{$item->departments->name . ' ' . $item->department_type->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button role="submit" class="btn btn-info">
                        <span></span> Pokaż statystyki
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(isset($department) && $department != null)
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Oddział</b>
            </div>
            <div class="panel-body">
                {{$department->departments->name . ' ' . $department->department_type->name}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Ilość przeprowadzonych testów</b>
            </div>
            <div class="panel-body">
                {{$dep_sum}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Ilość pracowników kadry</b>
            </div>
            <div class="panel-body">
                {{$department->users->whereNotIn('user_type_id', [1,2])->where('status_work', '=', 1)->count()}}
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

@endif
@endsection

@section('script')
@if(isset($department) && $department != null)
<script>

    @php
        $total_ok = 0;
        $total_nok = 0;
        $total_sum = 0;
    @endphp
google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChartUsers);
      function drawChartUsers() {
        var data = google.visualization.arrayToDataTable([
          ['Ilość', 'Statystyki'],
          @foreach($tests_by_user as $item)
            ['{{$item->first_name . ' ' . $item->last_name}}', {{$item->user_sum}}],
            @php
                $total_ok += $item->user_pass;
                $total_nok += $item->user_not_pass;
                $total_sum += $item->user_sum;
            @endphp
          @endforeach
            ['', 0]
        ]);

        var options = {
          title: 'Pracownicy',
          pieHole: 0.0,
        };

        var chart = new google.visualization.PieChart(document.getElementById('drawChartUsers'));
        chart.draw(data, options);
      }

google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Ilość', 'Statystyki'],
        ['Zaliczone', {{$total_ok}}],
        ['Niezaliczone', {{$total_nok}}]
        ]);

        var options = {
        title: 'Rezultaty oddziału',
        pieHole: 0.0,
        colors: ['#53e041', '#d81c32', '#e6f207']
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
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
        ['', 0]
        ]);

        var options = {
        title: 'Typy zagadnień',
        pieHole: 0.0,
        };

        var chart = new google.visualization.PieChart(document.getElementById('drawChartType'));
        chart.draw(data, options);
    }

google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChartTester);
      function drawChartTester() {
        var data = google.visualization.arrayToDataTable([
          ['Ilość', 'Statystyki'],
          @foreach($tests_by_cadre as $item)
            ['{{$item->first_name . ' ' . $item->last_name}}', {{$item->user_sum}}],
          @endforeach
          ['', 0]
        ]);

        var options = {
          title: 'Osoba testująca',
          pieHole: 0.0,
        };

        var chart = new google.visualization.PieChart(document.getElementById('drawChartTester'));
        chart.draw(data, options);
      }
</script>
@endif
@endsection
