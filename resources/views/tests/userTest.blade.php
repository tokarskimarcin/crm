@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Nazwa testu</h1>
        </div>
        <div class="col-md-9">
            <h3>
                Pytanie <span id="actual_question"></span>/<span id="total_questions"></span>
            </h3>
        </div>
        <div class="col-md-3">
            <div class="alert alert-success" id="user_time">
                <h3>
                    Pozostały czas: <span id="question_time_remaining"></span>
                </h3>
            </div>
        </div>
    </div>
</div>

<div class="row" id="question_info">
    <div class="col-md-12">
        <p>
            <h3>Czas na odpowiedź: <b><span id="question_time"></span></b>, możesz zakończyć wcześniej klikająć przycisk "Zapisz" na dole strony.</h3>
        </p>
        <button class="btn btn-success btn-lg" id="btn_start">Start</button>
    </div>
</div>

<div class="row" id="question_content" style="display: none">
    <div class="col-md-12">
        <div class="panel panel-default">
              <div class="panel-heading">
                  Treść pytania
              </div>
              <div class="panel-body">
                  Ala ma 10 lat i 3 browary, znając wzrost (150 cm) i wagę (63 kg) oblicz ilość promilii w krwi Alicji.
              </div>
        </div>
    </div>
    <div class="col-md-12">
        <form method="POST" action="{{URL::to('/test_user')}}" id="answer_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="user_answer">Twoja odpowiedź</label>
                <textarea class="form-control" style="resize: none" rows="10" name="user_answer" id="user_answer"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Zapisz" id="btn_test_submit"/>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>

var q_time = 10; // tutaj podstawiamy ilość czasu na pytanie
var total_questions = 5;
var actual_question = 5;
var loopTime = 1000;

$(document).ready(function() {
    /* Zdefiniowanie czasu początkowego na pytanie*/
    var time = Math.ceil(q_time / 60) - 1;
    var seconds = q_time % 60;
    $('#question_time_remaining, #question_time').text(time + ":" + seconds);
    $('#total_questions').text(total_questions);
    $('#actual_question').text(actual_question);
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
    /* Funkcja odliczająca czas */
    timeRemaining = setInterval(function () {
              if (q_time > 0) {
                  q_time--;
              } else {
                  //przeładowanie do nast pytania
                  stopTimer();
              }

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
