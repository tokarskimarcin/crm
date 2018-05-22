@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav">Testy / Statystyki oddziałów @isset($department) / {{$department->departments->name . ' ' . $department->department_type->name}} @endisset</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

            <form action="{{ URL::to('/department_statistics') }}" method="POST" id="dep_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control" name="dep_id" id="dep_id">
                            <option value="0">Wybierz</option>
                            @foreach($department_info as $item)
                                @if($item->id != 13)
                                    <option @if(isset($id) && $id == $item->id) selected @endif value="{{$item->id}}">{{$item->departments->name . ' ' . $item->department_type->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">

                        <select class="form-control" name="type_statistic" id="type_statistic">
                            <option value="0">Wybierz</option>
                            <option value="1" @if(isset($type_statistic) && $type_statistic == 1) selected @endif>Trenerzy</option>
                            <option value="2" @if(isset($type_statistic) && $type_statistic == 2) selected @endif>Kadra</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <button role="submit" class="btn btn-info" id="show_department">
                        <span></span> Pokaż statystyki
                    </button>
                </div>
            </form>
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
            @if($type_statistic == 1)
                <div class="panel-body">
                    {{$department->users->whereIn('user_type_id', [1,2])->where('status_work', '=', 1)->count()}}
                </div>
            @else
                <div class="panel-body">
                    {{$department->users->whereNotIn('user_type_id', [1,2])->where('status_work', '=', 1)->count()}}
                </div>
            @endif
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

<script>
$(document).ready(function() {
    $('#show_department').click(function(e) {
        e.preventDefault();
        var dep_id = $('#dep_id').val();
        var type_id = $('#type_statistic').val();
        if (dep_id == 0 ) {
            swal('Wybierz oddział!')
            return;
        }
        if (type_id == 0) {
            swal('Wybierz Typ statystyk!')
            return;
        }

        $('#dep_form').submit();
    });
})
</script>

@if(isset($department) && $department != null)
<script>

google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChartUsers);
      function drawChartUsers() {
        var data = google.visualization.arrayToDataTable([
          ['Ilość', 'Statystyki'],
          @foreach($tests_by_user as $item)
            ['{{$item->first_name . ' ' . $item->last_name}}', {{$item->user_sum}}],
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
            ['Poprawne', {{($results->dep_good != null) ? $results->dep_good : 0}}],
            ['Niepoprawne', {{($results->dep_wrong != null) ? $results->dep_wrong : 0}}]
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
