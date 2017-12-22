@extends('layouts.main')
@section('content')
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

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
array("y" => round($data->user_judge_sum, 2), "label" => "Średnia ocena"),
array("y" => round($data->user_quality, 2), "label" => "Ocena jakości wykonania"),
array("y" => round($data->user_contact, 2), "label" => "Ocena kontaktu z serwisantem"),
array("y" => round($data->user_time, 2), "label" => "Ocena czasu wykonania")
);
@endphp
<?php

?>
<div class="row">
  <div class="col-md-6">
      <div id="chartContainer"></div>
  </div>
  <div class="col-md-6">
      <div class="panel panel-green" style="height: 100%">
          <div class="panel-heading"><h3>{{$data->first_name . ' ' . $data->last_name}}</h3></div>
          <div class="panel-body">
              <div class="list-group">
                  <div class="list-group-item">Liczba ocen pozytywnych: <b>{{$data->user_sum_repaired . '/' . $data->user_sum}}</b></div>
                  <div class="list-group-item">Średni czas realizacji: {{round($data->notifications_time_sum /3600, 2)}} h</div>
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

$(function () {
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title: {
		text: "Średnia ocen"
	},
	data: [
	{
		type: "column",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}
	]
});
chart.render();
});

</script>
@endsection
