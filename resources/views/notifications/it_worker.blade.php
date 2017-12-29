@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <span id="page_title">Profil pracownika IT</span>
        </h1>
    </div>
</div>
@php
$data = $user_results[0];

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
          <div class="panel-heading"><h3>{{$data->first_name . ' ' . $data->last_name}}</h3></div>
          <div class="panel-body">
              <div class="list-group">
                  <div class="list-group-item">Liczba ocen pozytywnych: <b>{{$data->user_sum_repaired . '/' . $data->user_sum}}</b></div>
                  <div class="list-group-item">Średni czas realizacji: <b>{{round($data->notifications_time_sum /3600, 2)}} h</b></div>
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
<div class="row">
  <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading">
              Komentarze
          </div>
          <div class="panel-body">
            <div class="list-group">
              @foreach($comments as $comment)
                  <div class="list-group-item"><p>{{$comment->comment}}</p><small>Dodał: {{$comment->first_name . ' ' . $comment->last_name . ' ' . $comment->add_time}}</small></div>
              @endforeach
              @if($comments->count() == 0)
                  <div class="list-group-item"><p>Brak komentarzy!</p></div>
              @endif
            </div>
          </div>
      </div>
  </div>
</div>


@endsection
@section('script')
<script>
google.charts.load('current', {'packages':['bar']});
google.charts.setOnLoadCallback(drawStuff);

function drawStuff() {
  var data = google.visualization.arrayToDataTable([
   ['', 'Ocena', { role: 'style' }],
   ['Suma', {{$dataPoints[0]}} + "/6", 'gray'],            // RGB value
   ['Jakość', {{$dataPoints[1]}} + "/6", 'silver'],            // English color name
   ['Kontakt', {{$dataPoints[2]}} + "/6", 'gold'],
   ['Czas wykonania', {{$dataPoints[3]}} + "/6", 'color: gray' ], // CSS-style declaration
]);

  var options = {
    width: 500,
    chart: {
      title: 'Średnia ocena użytkownika',
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
};

</script>
@endsection
