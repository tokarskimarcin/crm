@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Statystyki szablonu @if(isset($test)) / {{$test->template_name}} @endif</div>
        </div>
    </div>
</div>

<div class="row" style="margin-bottom: 50px;">
    <div class="col-md-12">
        <div class="col-md-4">
            <form id="template_form" method="POST" action="{{ URL::to('/one_test_statistics') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="form-group">
                    <label>Wybierz szablon:</label>
                    <select class="form-control" name="template_id" id="template_id">
                        <option value="0">Wybierz</option>
                        @foreach($templates as $item)
                            <option @if(isset($test) && $test->id == $item->id) selected @endif value="{{$item->id}}">{{$item->template_name}}</options>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button role="submit" class="btn btn-info" id="show_template">
                        <span class="glyphicon glyphicon-ok"></span> Pokaż statystyki
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(isset($test))
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
                <table class="table table-striped table-bordered thead-inverse">
                    <thead>
                        <tr>
                            <th style="width: 5%">Lp.</th>
                            <th style="width: 15%">Kategoria</th>
                            <th>Treść pytania</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 0;  @endphp
                        @foreach($test->templateQuestions as $item)
                            @php $i++;  @endphp
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
@endif
@endsection

@section('script')
<script>

@if(isset($test))
    google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
            ['Ilość', 'Statystyki'],
            ['Zaliczone', {{$results->good}}],
            ['Niezaliczone', {{$results->bad}}],
            ['Brak oceny', {{$results->not_judged}}]
            ]);

            var options = {
            title: 'Wyniki pracowników',
            pieHole: 0.0,
            colors: ['#53e041', '#d81c32', '#e6f207']
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
@endif

$('#show_template').click(function(e) {
    e.preventDefault();
    var template_id = $('#template_id').val();

    if (template_id == 0) {
        swal('Wybierz szablon!')
        return;
    }

    $('#template_form').submit();
});

</script>
@endsection
