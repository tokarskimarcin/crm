@extends('layouts.main')
@section('content')
<style>
.panel-info > .panel-heading {
    background-color: #a36bce;
    color: white;
}

.panel-heading a: {
    font-family:'Glyphicons Halflings';
    content:"\e114";
    float: right;
    color: white;
}

.panel-heading a:after {
    font-family:'Glyphicons Halflings';
    content:"\e114";
    float: right;
    color: white;
}
.panel-heading a.collapsed:after {
    content:"\e080";
}
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well">Testy / Rezultat testu / {{$test->name}}</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="row">
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
                        <b>Osoba exterminowana</b>
                    </div>
                    <div class="panel-body">
                        {{$test->user->first_name . ' ' . $test->user->last_name}}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <b>Osoba przeprowadzająca test</b>
                    </div>
                    <div class="panel-body">
                        {{$test->cadre->first_name . ' ' . $test->cadre->last_name}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <b>Data testu</b>
                    </div>
                    <div class="panel-body">
                        {{substr($test->test_start, 0, 10)}}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <b>Czas rozpoczęcia/zakończenia</b>
                    </div>
                    <div class="panel-body">
                        {{substr($test->test_start, 11, 20)}} - {{substr($test->test_stop, 11, 20)}}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <b>Rezultat</b>
                    </div>
                    <div class="panel-body">
                        @if($test->result == 1)
                            <b style="color: red">NEGATYWNY</b>
                        @else
                            <b style="color: green">POZYTYWNY</b>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php($i = 0)
@foreach($test->questions as $item)
    @php($i++)
    <div class="row" id="questions">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <a style="color:white" data-toggle="collapse" href="#question_panel{{$i}}" aria-expanded="true"><b>Pytanie {{$i}}</b></a>
                </div>
                <div id="question_panel{{$i}}" class="panel-collapse collapse">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <p><b>Treść pytania:</b></p>
                            <div class="alert alert-info">{{$item->testQuestion[0]->content}}</div>
                        </li>
                        <li class="list-group-item">
                            <p><b>Odpowiedź pracownika:</b></p>
                            <div class="alert alert-info">{{$item->user_answer}}</div>
                        </li>
                        <li class="list-group-item">
                            <p><b>Komentarz osoby testującej:</b></p>
                            <div class="alert alert-info">{{$item->cadre_comment}}</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endforeach


@endsection
@section('script')



</script>
@endsection
