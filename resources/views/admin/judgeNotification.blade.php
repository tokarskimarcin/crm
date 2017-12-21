@extends('layouts.main')
@section('content')
<style type="text/css">
      body{margin:40px;}
      .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
      }
      .btn-circle.btn-lg {
        width: 50px;
        height: 50px;
        padding: 13px 13px;
        font-size: 18px;
        line-height: 1.33;
        border-radius: 25px;
      }
      .selected-span {
        font-size: 30px;
        margin-left: 10px;
      }

</style>
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Ocena wykonania zgłoszenia</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading">ID zgłoszenia {{$notification->id}}</div>
          <div class="panel-body">
              <p><b>Tytuł:</b></p>
              <p>{{$notification->title}}</p>
              <hr>
              <p><b>Treść:</b></p>
              <p>{{$notification->content}}</p>
              <hr>
          </div>
      </div>
    </div>
</div>

@if(isset($judgeResult) && $judgeResult != null)
@php($judgeResult = $judgeResult[0])

<div class="row col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
              <tr>
                <td style="width: 50%"></td>
                <td style="width: 50%"></td>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Serwisant:</td>
                    <td>{{$judgeResult->user_it->first_name . ' ' . $judgeResult->user_it->last_name}}</td>
                </tr>
                <tr>
                    <td>Czy problem został naprawiony:</td>
                    <td>
                        @if($judgeResult->repaired == 1)
                            TAK
                        @else
                            NIE
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Jakość wykonania zgłoszenia:</td>
                    <td>{{$judgeResult->judge_quality}}/6</td>
                </tr>
                <tr>
                    <td>Kontakt z serwisantem:</td>
                    <td>{{$judgeResult->judge_contact}}/6</td>
                </tr>
                <tr>
                    <td>Czas wykonywania zgłoszenia:</td>
                    <td>{{$judgeResult->judge_time}}/6</td>
                </tr>
                <tr>
                    <td>Technik kontaktował się po zakończeniu zgłoszenia:</td>
                    <td>
                        @if($judgeResult->repaired == 1)
                            TAK
                        @else
                            NIE
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Ogólna ocena:</td>
                    <td>{{$judgeResult->judge_sum}}/6</td>
                </tr>
                <tr>
                    <td>Komentarz:</td>
                    <td>{{$judgeResult->comment}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@else
<div class="col-md-6">
<form method="POST" action="{{URL::to('/judge_notification')}}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="notification_id" value="{{$notification->id}}">
    <div class="form-group">
        <h4>Czy problem został naprawiony?</h4></br>
        <div data-toggle="buttons">
            <label id="q1_yes" class="btn btn-success btn-circle btn-lg"><input type="radio"  name="q1" value="1"><i class="glyphicon glyphicon-ok"></i></label>
            <label id="q1_no"  class="btn btn-danger btn-circle btn-lg"><input type="radio" name="q1" value="2"><i class="glyphicon glyphicon-remove"></i></label>
            <span class="selected-span" id="q1_span"></span>
        </div>
    </div>
    <div class="alert alert-danger" style="display: none" id="alert_fixed">
        Zaznacz odpowiednią wartość!
    </div>
    <div class="form-group">
        <h4>Oceń jakość wykonania zgłoszenia:</h4></br>
        <div data-toggle="buttons">
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q2" value="1">1</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q2" value="2">2</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q2" value="3">3</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q2" value="4">4</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q2" value="5">5</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q2" value="6">6</label>
        </div>
    </div>
    <div class="alert alert-danger" style="display: none" id="alert_quality">
        Zaznacz odpowiednią wartość!
    </div>
    <div class="form-group">
        <h4>Oceń kontakt serwisantem:</h4></br>
        <div data-toggle="buttons">
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q3" value="1">1</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q3" value="2">2</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q3" value="3">3</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q3" value="4">4</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q3" value="5">5</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q3" value="6">6</label>
        </div>
    </div>
    <div class="alert alert-danger" style="display: none" id="alert_contact">
        Zaznacz odpowiednią wartość!
    </div>
    <div class="form-group">
        <h4>Oceń czas wykonywania zgłoszenia:</h4></br>
        <div data-toggle="buttons">
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q4" value="1">1</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q4" value="2">2</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q4" value="3">3</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q4" value="4">4</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q4" value="5">5</label>
            <label class="btn btn-default btn-circle btn-lg"><input type="radio" name="q4" value="6">6</label>
        </div>
    </div>
    <div class="alert alert-danger" style="display: none" id="alert_time">
        Zaznacz odpowiednią wartość!
    </div>
    <div class="form-group">
        <h4>Czy technik kontaktował się po zakończeniu zgłoszenia?</h4></br>
        <div data-toggle="buttons">
            <label id="q5_yes" class="btn btn-success btn-circle btn-lg"><input type="radio" name="q5" value="1"><i class="glyphicon glyphicon-ok"></i></label>
            <label id="q5_no" class="btn btn-danger btn-circle btn-lg"><input type="radio" name="q5" value="2"><i class="glyphicon glyphicon-remove"></i></label>
            <span class="selected-span" id="q5_span"></span>
        </div>
    </div>
    <div class="alert alert-danger" style="display: none" id="alert_after">
        Zaznacz odpowiednią wartość!
    </div>
    <div class="form-group">
        <label for="judge_comment">Dodatkowe uwagi (opcjonalnie):</label>
        <textarea id="judge_comment" name="judge_comment" class="form-control" placeholder="Dodaj komentarz..."></textarea>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-success" value="Prześlij opinię" id="send_opinion">
    </div>
</form>
</div>
@endif
@endsection
@section('script')

<script>

$("#q1_yes").on('click', () => {
    $('#q1_span').text('TAK');
});
$("#q1_no").on('click', () => {
    $('#q1_span').text('NIE');
});
$("#q5_yes").on('click', () => {
    $('#q5_span').text('TAK');
});
$("#q5_no").on('click', () => {
    $('#q5_span').text('NIE');
});

$('#send_opinion').on('click', function() {
    var fixedValue = $('input[name=q1]:checked').val();
    var qualityValue = $('input[name=q2]:checked').val();
    var contactValue = $('input[name=q3]:checked').val();
    var timeValue = $('input[name=q4]:checked').val();
    var afterValue = $('input[name=q5]:checked').val();

    var validation = true;

    if (fixedValue == null) {
        $('#alert_fixed').slideDown(1000);
        validation = false;
    } else {
        $('#alert_fixed').slideUp(1000);
    }

    if (qualityValue == null) {
        $('#alert_quality').slideDown(1000);
        validation = false;
    } else {
        $('#alert_quality').slideUp(1000);
    }

    if (contactValue == null) {
        $('#alert_contact').slideDown(1000);
        validation = false;
    } else {
        $('#alert_contact').slideUp(1000);
    }

    if (timeValue == null) {
        $('#alert_time').slideDown(1000);
        validation = false;
    } else {
        $('#alert_time').slideUp(1000);
    }

    if (afterValue == null) {
        $('#alert_after').slideDown(1000);
        validation = false;
    } else {
        $('#alert_after').slideUp(1000);
    }

    return validation;

});

</script>
@endsection
