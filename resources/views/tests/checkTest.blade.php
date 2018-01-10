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
      .btn {
         outline: none !important;
         box-shadow: none !important;
      }

</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Ocena testu</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Data testu</b>
            </div>
            <div class="panel-body">
                2017-12-12
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Tytuł testu</b>
            </div>
            <div class="panel-body">
                Chuje muje dzikie węże ple pe rgergn  erg erge rger
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Użytkownik</b>
            </div>
            <div class="panel-body">
                Czesław Miłosz
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#question1">Pytanie nr 1</a></li>
    <li><a data-toggle="tab" href="#question2">Pytanie nr 2</a></li>
    <li><a data-toggle="tab" href="#question3">Pytanie nr 3</a></li>
    <li><a data-toggle="tab" href="#question4">Pytanie nr 4</a></li>
    <li><a data-toggle="tab" href="#question5">Pytanie nr 5</a></li>
    <li><a data-toggle="tab" href="#question_total">Ocena ogólna</a></li>
</ul>

<form method="POST" action="{{URL::to('/check_test')}}" id="checkForm">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="tab-content">
        <div id="question1" class="tab-pane fade in active">
            <div class="form-group" style="margin-top: 30px">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <b>Treść pytania</b>
                    </div>
                    <div class="panel-body">
                        Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg ?
                    </div>
                </div>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <b>Odpowiedź użytkownika</b>
                    </div>
                    <div class="panel-body">
                        Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg 
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="comment_question1">Dodaj komentarz (opcjonalne):</label>
                <textarea class="form-control" name="comment_question1" placeholder="Twój komentarz..." rows="5"></textarea>
            </div>
        </div>
        <div id="question2" class="tab-pane fade">
            <div class="form-group" style="margin-top: 30px">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <b>Treść pytania</b>
                </div>
                <div class="panel-body">
                    Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg ?
                </div>
            </div>
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <b>Odpowiedź użytkownika</b>
                </div>
                <div class="panel-body">
                    Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg 
                </div>
            </div>
            </div>
            <div class="form-group">
                <button class="btn btn-default btn-lg add_comment"><span class="glyphicon glyphicon-plus"></span>Dodaj komentarz</button>
            </div>
            <div class="form-group" id="comment_div_1" style="display: none">
                <label for="comment_question1">Twój komentarz:</label>
                <textarea class="form-control" name="comment_question1" placeholder="Twój komentarz..." rows="5"></textarea>
            </div>
        </div>
        <div id="question3" class="tab-pane fade">
            <div class="form-group" style="margin-top: 30px">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <b>Treść pytania</b>
                </div>
                <div class="panel-body">
                    Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg ?
                </div>
            </div>
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <b>Odpowiedź użytkownika</b>
                </div>
                <div class="panel-body">
                    Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg 
                </div>
            </div>
            </div>
            <div class="form-group">
                <label for="comment_question1">Dodaj komentarz (opcjonalne):</label>
                <textarea class="form-control" name="comment_question1" placeholder="Twój komentarz..." rows="5"></textarea>
            </div>
        </div>
        <div id="question4" class="tab-pane fade">
        <div class="form-group" style="margin-top: 30px">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <b>Treść pytania</b>
            </div>
            <div class="panel-body">
                Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg ?
            </div>
        </div>
        <div class="panel panel-warning">
            <div class="panel-heading">
                <b>Odpowiedź użytkownika</b>
            </div>
            <div class="panel-body">
                Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg 
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="comment_question1">Dodaj komentarz (opcjonalne):</label>
        <textarea class="form-control" name="comment_question1" placeholder="Twój komentarz..." rows="5"></textarea>
    </div>
        </div>
        <div id="question5" class="tab-pane fade">
        <div class="form-group" style="margin-top: 30px">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <b>Treść pytania</b>
            </div>
            <div class="panel-body">
                Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg ?
            </div>
        </div>
        <div class="panel panel-warning">
            <div class="panel-heading">
                <b>Odpowiedź użytkownika</b>
            </div>
            <div class="panel-body">
                Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg Plelele sfsdf sdf sfsdf sd   sdksgjdnfgkjn sg drg ergregerg erg ergergnekjrgnekj e nkrgn ekrjg e e rgjne krjgn ekjrg 
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="comment_question1">Dodaj komentarz (opcjonalne):</label>
        <textarea class="form-control" name="comment_question1" placeholder="Twój komentarz..." rows="5"></textarea>
    </div>
        </div>
        <div id="question_total" class="tab-pane fade">
            <div class="form-group" style="margin-top: 30px">
                <label>Test został zaliczony:</label>
                <div data-toggle="buttons">
                    <label id="q1_yes" class="btn btn-success btn-circle btn-lg"><input type="radio"  name="q1" value="1"><i class="glyphicon glyphicon-ok"></i></label>
                    <label id="q1_no"  class="btn btn-danger btn-circle btn-lg"><input type="radio" name="q1" value="2"><i class="glyphicon glyphicon-remove"></i></label>
                    <span class="selected-span" id="q1_span"></span>
                </div>
            </div>
            <div class="alert alert-danger" style="display: none" id="alert_checked">
                Zaznacz wynik testu!
            </div>
            <div class="form-group">
                <h3>Użytkownik zostanie poinformowany o wyniku testu drogą mailową.</h3>
            <div>
            <br />
            <div class="form-group">
                <input type="submit" class="btn btn-success btn-lg" value="Prześlij ocenę" id="send_opinion"/>
            <div>
        </div>
    </div>
</form>

@endsection

@section('script')
<script>

$('.add_comment').click(function(e) {
    e.preventDefault();
    $('.add_comment').fadeOut(0);
    $('#comment_div_1').slideDown(500);

});

$("#q1_yes").on('click', () => {
    $('#q1_span').text('TAK');
});
$("#q1_no").on('click', () => {
    $('#q1_span').text('NIE');
});


$('#send_opinion').on('click', function(e) {
    e.preventDefault();
    var checkStatus = $('input[name=q1]:checked').val();

    if (checkStatus == null) {
        $('#alert_checked').slideDown(1000);
        return false;
    } else {
        $('#alert_checked').slideUp(1000);
        $('#checkForm').submit();
    }

});
</script>
@endsection
