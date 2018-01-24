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
.panel-info > .panel-heading {
    background-color: #4a4e54;
    color: white;
}

</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Testy / Ocena testu / {{$test->name}}</div>
        </div>
    </div>
</div>

@if (Session::has('message_ok'))
    <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Data testu</b>
            </div>
            <div class="panel-body">
                {{$test->created_at}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Tytuł testu</b>
            </div>
            <div class="panel-body">
                {{$test->name}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Użytkownik</b>
            </div>
            <div class="panel-body">
                {{$test->user->first_name . ' ' . $test->user->last_name}}
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs">
    @php($i = 0)
    @foreach($test->questions as $item)
        @php($i++)
        <li @if($i == 1 && $test->result == null) class="active" @endif>
            <a data-toggle="tab" href="#question{{$item->id}}">
                Pytanie nr {{$i}}
            </a>
        </li>
    @endforeach
    <li @if($test->result != null) class="active" @endif><a data-toggle="tab" href="#question_total">Ocena ogólna</a></li>
</ul>

<form method="POST" action="{{URL::to('/check_test')}}" id="checkForm">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="tab-content">

        @php($i = 0)
        @foreach($test->questions as $item)
            @php($i++)
            <div id="question{{$item->id}}" class="tab-pane @if($i == 1 && $test->result == null) fade in active @endif">
                    <div class="form-group" style="margin-top: 30px">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <b>Treść pytania</b>
                            </div>
                            <div class="panel-body">
                                {{--  Dlaczego tu kurwa jebane gówno nie umie wyciągnąć tego normalnie z kolekcji? nie wiem  --}}
                                @foreach($item->testQuestion as $shit)
                                    {!! $shit->content !!} 
                                @endforeach
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <b>Odpowiedź użytkownika</b>
                            </div>
                            <div class="panel-body">
                                {!! $item->user_answer !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Dodaj ocenę pytania:</label>
                            <select class="form-control input-lg" name="question_result[]">
                                <option value="1">Zaliczone</option>
                                <option value="0">Niezaliczone</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Dodaj komentarz (opcjonalne):</label>
                            <textarea class="form-control" name="comment_question[]" placeholder="Twój komentarz..." rows="5">{{$item->cadre_comment}}</textarea>
                        </div>
                    </div>
                </div>
        @endforeach
        
        <div id="question_total" class="tab-pane fade @if($test->result != null) in active @endif">
            @if($test->result == null)
                <div class="form-group">
                    <div class="alert alert-info">
                        <h3>Użytkownik zostanie poinformowany o wyniku testu drogą mailową.</h3>
                    </div>
                <div>
                <br />
                <div class="form-group">
                    <button role="submit" class="btn btn-info" id="send_opinion"/>
                        <span class="glyphicon glyphicon-send"></span> Prześlij opinię
                    </button>
                <div>
            @else
                <div class="alert alert-info">
                    <h1>
                        Test został już oceniony przez {{$test->checkedBy->first_name . ' ' . $test->checkedBy->last_name}}.
                    </h1>
                    <h1>
                        Ocena: 
                        {{$test->result}}/{{$test->questions->count()}}
                    </h1>
                    <h3>
                        Statyki pracownika możesz sprawdzić <a href="{{URL::to('/employee_statistics/')}}/{{$test->user_id}}">tutaj.</a>
                    </h3>
                    <h3>
                        <a class="btn btn-info" href="{{ URL::to('test_result') }}/{{$test->id}}">Podgląd testu</a>
                    </h3>
                </div>
            @endif
        </div>
    </div>
    <input type="hidden" value="{{$test->id}}" name="test_id" />
</form>

@endsection

@section('script')
<script>

</script>
@endsection