@extends('layouts.main')
@section('content')
<style>
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
            <div class="well gray-nav">Testy / Rezultat testu / {{$test->name}}</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading alert-destroyer">
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
                        <b>Pracownik</b>
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
                        @if($test->test_stop != null)
                            {{substr($test->test_start, 0, 10)}}
                        @else
                            Użytkownik nie wypełnił jeszcze testu.
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <b>Czas rozpoczęcia/zakończenia</b>
                    </div>
                    <div class="panel-body">
                        @if($test->test_stop != null)
                            {{substr($test->test_start, 11, 20)}} - {{substr($test->test_stop, 11, 20)}}
                        @else
                            Użytkownik nie wypełnił jeszcze testu.
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <b>Rezultat</b>
                    </div>
                    <div class="panel-body">
                        @if($test->result == 2)
                            <b style="color: red">NEGATYWNY</b>
                        @elseif($test->result == 1)
                            <b style="color: green">POZYTYWNY</b>
                        @else
                            <b>Brak oceny</b>
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
                            <div class="alert alert-info">
                                @if($item->testQuestion[0]->content != null)
                                    {!! $item->testQuestion[0]->content !!}
                                @else
                                    Brak treści pytania.Skontaktuj się z administratorem!
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item">
                            <p><b>Odpowiedź pracownika:</b></p>
                            <div class="alert alert-info">
                                @if($item->user_answer != null)
                                    {{$item->user_answer}}
                                @else
                                    Użytkownik nie wypełnił jeszcze testu!
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item">
                            <p><b>Komentarz osoby testującej:</b></p>
                            <div class="alert alert-info">
                                @if($item->cadre_comment != null)
                                    {{$item->cadre_comment}}
                                @else
                                    Brak komentarza!
                                @endif
                            </div>
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
