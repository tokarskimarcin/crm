@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Pomoc / Profil pracownika IT</div>
        </div>
    </div>
</div>
@php
    $polish_month = array( '', 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień' );
    $data = $user_results[0];
    $avg_grade = 0;
    $count_grade  = 0;
    $dataPoints = array(
    round($data->user_judge_sum, 2),
    round($data->user_quality, 2),
    round($data->user_contact, 2),
    round($data->user_time, 2)
    );
@endphp
<?php

?>
<div class="row">
  <div class="col-md-6">
    <div id="dual_x_div" style="width: 400px; height: 400px;"></div>
  </div>
  <div class="col-md-6">
      <div class="panel panel-green" style="height: 100%">
          <div class="panel-heading"><h3>{{$user_data->first_name . ' ' . $user_data->last_name.' ('.$polish_month[intval (date('m'))].')'}}</h3></div>
          <div class="panel-body">
              <div class="list-group">
                  <div class="list-group-item">Liczba ocen pozytywnych: <b>{{$data->user_sum_repaired . '/' . $data->user_sum}}</b></div>
                  <div class="list-group-item">Średni czas realizacji: <b>{{round($data->notifications_time_sum, 2)}} h</b></div>
                  <div class="list-group-item">Oddzwonienia: <b>{{$data->response_after . '/' . $data->user_sum}}</b></div>
              </div>
          </div>
          <div class="panel-footer"></div>
      </div>
  </div>
</div>
<br  /><br />
<br  /><br />
<br  /><br />
{{--<div class="row">--}}
  {{--<div class="col-md-12">--}}
      {{--<div class="panel panel-default">--}}
          {{--<div class="panel-heading">--}}
              {{--Komentarze--}}
          {{--</div>--}}
          {{--<div class="panel-body">--}}
            {{--<div class="list-group">--}}
              {{--@foreach($comments as $comment)--}}
                  {{--<div class="list-group-item"><p>{{$comment->comment}}</p><small>Dodał: {{$comment->first_name . ' ' . $comment->last_name . ' ' . $comment->add_time}}</small></div>--}}
              {{--@endforeach--}}
              {{--@if($comments->count() == 0)--}}
                  {{--<div class="list-group-item"><p>Brak komentarzy!</p></div>--}}
              {{--@endif--}}
            {{--</div>--}}
          {{--</div>--}}
      {{--</div>--}}
  {{--</div>--}}
{{--</div>--}}


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Historia zgłoszeń
            </div>
            <div class="panel-body">
                <div class="table_of_conntent">
                    <div id="menu1" class="tab-pane fade in active">
                        <div class="table-responsive" style="margin-top: 30px;">
                            <table id="history_of_notification" class="table table-striped table-bordered thead-inverse" cellspacing="0" width="100%" >
                                <thead>
                                <tr>
                                    <td style="width: 15%">Data złoszenia</td>
                                    <td style="width: 15%">Data przyjęcia zgłoszenia</td>
                                    <td style="width: 15%">Data zakończenia zgłoszenia</td>
                                    <td style="width: 10%">Zgłoszone przez</td>
                                    <td style="width: 10%">Ocena końcowa</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($story_of_problem as $item)
                                    <tr>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->data_start}}</td>
                                        @if($item->data_stop == null)
                                            <td>W trakcie wykonywania</td>
                                        @else
                                            <td>{{$item->data_stop}}</td>
                                        @endif
                                        <td>{{$item->first_name.' '.$item->last_name}}</td>
                                        @if($item->judge_sum == null)
                                            <td>Brak Oceny</td>
                                        @else
                                            @php
                                                $avg_grade += $item->judge_sum;
                                                $count_grade++;
                                            @endphp
                                            <td>{{$item->judge_sum}}</td>
                                        @endif
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.select.min.js')}}"></script>
<script>
google.charts.load('current', {'packages':['bar']});
google.charts.setOnLoadCallback(drawStuff);

$(document).ready( function () {
    var table = $('#history_of_notification').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
        },
        "order": [[ 0, "desc" ]]
    });
});
function drawStuff() {
  var data = google.visualization.arrayToDataTable([
   ['', 'Ocena', { role: 'style' }],
   ['Suma', {{$dataPoints[0]}} + "/6", 'gray'],            // RGB value
   ['Jakość', {{$dataPoints[1]}} + "/6", 'silver'],            // English color name
   ['Kontakt', {{$dataPoints[2]}} + "/6", 'gold'],
   ['Czas wykonania', {{$dataPoints[3]}} + "/6", 'color: gray' ], // CSS-style declaration
]);
  var month = '{{$polish_month[intval (date('m'))]}}';
  var options = {
    width: 500,
    chart: {
      title: "Średnia ocena użytkownika "+month,
      subtitle: 'Ocena w skali od 0 do 6'
    },
    bars: 'horizontal', // Required for Material Bar Charts.
    axes: {
      x: {
        distance: {label: 'parsecs'}, // Bottom x-axis.
        brightness: {side: 'top', label: 'apparent magnitude'} // Top x-axis.
      }
    }
  };

var chart = new google.charts.Bar(document.getElementById('dual_x_div'));
chart.draw(data, options);
}

</script>
@endsection
