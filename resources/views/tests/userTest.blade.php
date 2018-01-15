@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well my-well">Testy / Test kompetencji / {{$test->name}}</div>
        </div>
        @if($status != 3)
            <div class="col-md-9">
                @php
                    $progress = $actual_count / $question_count * 100;
                @endphp
                <div class="progress" style="height: 25px">
                    <div class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" aria-valuenow="{{$actual_count}}" aria-valuemin="0" aria-valuemax="{{$question_count}}" style="width:{{$progress}}%">
                        <span style="font-size: 25px; margin-top: 2 px">Pytanie {{$actual_count}}/{{$question_count}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-success" id="user_time">
                    <h3>
                        Pozostały czas: <span id="question_time_remaining"></span>
                    </h3>
                </div>
            </div>
        @endif
    </div>
</div>

@if($status == 2)

@php
    $max_time = 0;
    foreach ($test->questions as $item) {
        $max_time += $item->available_time;
    }
@endphp
    <div class="row" id="description">
        <div class="col-md-12">
            <div class="alert alert-success">
                <h1>
                    Przed Tobą test: <b>{{$test->name}}</b>
                </h1>
                <h3>
                    Test składa się z {{$question_count}} pytań, maksymalny czas trwania testu to: {{$max_time / 60 . ':00'}} minut.
                </h3>
                <hr>
                <h3>
                    Każde pytanie ma określony maksymalny czas - zegar znajduje się w prawym górnym rogu.
                    Po naciśnięciu przycisku "Start", pojawi się treść pytania oraz pole do wypełnienia. Odpowiedź do każdego pytania możesz przesłać wcześniej, klikając przycisk "Zapisz odpowiedź". Jeżeli nie zdążysz - Twoja odpowiedź zostanie automatycznie wysłana, a Ty zostaniesz przekierowany/na do następnego pytania.
                </h3>
                <h3>
                    <b>Powodzenia!</b>
                </h3>
            </div>
        </div>
    </div>
@endif

@if($status != 3 && $status != 4 && $question != null)
    <div class="row" id="question_info">
        <div class="col-md-12">
            <p>
                <h3>Czas na odpowiedź: <b><span id="question_time"></span></b>, możesz zakończyć wcześniej klikająć przycisk "Zapisz odpowiedź" na dole strony.</h3>
            </p>
            <button class="btn btn-success btn-lg" id="btn_start">Start</button>
        </div>
    </div>

    <div class="row" id="question_content" style="display: none">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Treść pytania
                </div>
                <div class="panel-body">
                    {{$question->testQuestion[0]->content}}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <form method="POST" action="{{URL::to('/test_user')}}" id="answer_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="question_id" value="{{$question->id}}" />
                <input type="hidden" name="answer_time" value="{{$question->available_time}}" id="answer_time"/>
                <div class="form-group">
                    <label for="user_answer">Twoja odpowiedź</label>
                    <textarea class="form-control" style="resize: none" rows="10" name="user_answer" id="user_answer" placeholder="Twoja odpowiedź..."></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-success btn-lg" value="Zapisz" id="btn_test_submit"/>
                </div>
            </form>
        </div>
    </div>


    <input type="hidden"  id="q_time_set" value="{{$question->available_time}}"/>

@else 
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h1>Ten test jest już zakończony! Wyniki otrzymasz drogą mailową.</h1>
                <h3>Możesz sprawdzić również wyniki testów w zakładce <a href="{{ URL::to('/all_user_tests') }}">moje testy.</a></h3>
            </div>
        </div>
    </div>
@endif

@endsection

@section('script')
<script>
var q_time = Number($('#q_time_set').val()); // tutaj podstawiamy ilość czasu na pytanie
var loopTime = 1000;
var actual_question = Number({{$actual_count}});
var total_questions = Number({{$question_count}});
$(document).ready(function() {
    
    /* Zdefiniowanie czasu początkowego na pytanie*/
    if (q_time == 60) {
        var time = 1;
    } else {
        var time = Math.ceil(q_time / 60) - 1;
    }
    var seconds = q_time % 60;
    if (seconds >= 0 && seconds < 10) {
        seconds = "0" + seconds;
    }
    
    $('#question_time_remaining, #question_time').text(time + ":" + seconds);
});

function stopTimer() {
    /* Funkcja zatrzymująca zegar  + automatyczne przesłanie formularza*/
    clearInterval(timeRemaining);
    /* Tutaj sprawdzenie czy pytanie było ostatnie w teście*/
    if (total_questions == actual_question) {
        swal('Czas się skończył! Było to ostanie pytanie, wynik testu otrzymasz drogą mailową.')
    } else {
        swal('Czas się skończył! Zostaniesz automatycznie przekierowany do następnego pytania.')
    }

    setTimeout(function(){
        $('#answer_form').submit();
    }, 5000);
}
$('#btn_start').click(function() {
    $('#description').slideUp(500);
    /* Funkcja odliczająca czas */
    timeRemaining = setInterval(function () {
              if (q_time > 0) {
                  q_time--;
              } else {
                  //przeładowanie do nast pytania
                  stopTimer();
              }
              console.log(q_time);
              $('#answer_time').val(q_time);
              var minutes = Math.ceil(q_time / 60) - 1;
              var seconds = q_time % 60;
              if (seconds == 0) {
                  minutes++;
                  seconds = "0" + seconds;
              } else if (seconds > 0 && seconds < 10) {
                  seconds = "0" + seconds;
              }
              var time_string = minutes + ":" + seconds;
              $('#question_time_remaining').text(time_string);
              if (q_time <= 30) {
                  $('#user_time').addClass('alert-danger');
              }
          }, loopTime);

    $('#question_info').fadeOut(0);
    $('#question_content').fadeIn(0);

});

/* Sprawdzenie czy pytanie jest ostanim*/
$('#btn_test_submit').click(function(e) {
    e.preventDefault();
    if (total_questions == actual_question) {
        swal('Było to ostanie pytanie, wynik testu otrzymasz drogą mailową.')
        setTimeout(function(){
            $('#answer_form').submit();
        }, 5000);
    } else {
        $('#answer_form').submit();
    }
});

</script>
@endsection
