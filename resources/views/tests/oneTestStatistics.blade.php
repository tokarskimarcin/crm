@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Statystyki szablonu / {{$test->template_name}}</div>
        </div>
    </div>
</div>

<div class="row equal">
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Tytuł szablonu</b>
            </div>
            <div class="panel-body">
                {{$test->template_name}}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Autor</b>
            </div>
            <div class="panel-body" style="min-height: 100%">
                {{$test->cadre->first_name . ' ' . $test->cadre->last_name}}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Data dodania</b>
            </div>
            <div class="panel-body">
                {{substr($test->created_at, 0, 10)}}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Ilość przeprowadzonych testów</b>
            </div>
            <div class="panel-body">
                {{$test->tests->count()}}
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div id="donutchart" style="width: 900; height: 250;"></div>
    </div>
</div>

<hr>
<h3>
    Pytania w szablonie
</h3>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 5%">Lp.</th>
                        <th style="width: 15%">Kategoria</th>
                        <th>Treść pytania</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($test->templateQuestions as $item)
                        @php($i++)
                        <tr>
                            <td><b>{{$i}}</b></td>
                            <td>{{$item->category->name}}</td>
                            <td>{!! $item->content !!}</td>
                        </tr>
                    @endforeach
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
        ['Ilość', 'Statystyki'],
        ['Zaliczone', {{$test->tests->where('result', '=', 1)->count()}}],
        ['Niezaliczone', {{$test->tests->where('result', '=', 2)->count()}}],
        ['Brak oceny', {{$test->tests->where('result', '=', null)->count()}}]
        ]);

        var options = {
        title: 'Wyniki pracowników',
        pieHole: 0.4,
        colors: ['#53e041', '#d81c32', '#e6f207']
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }

</script>
@endsection
