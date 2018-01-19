@extends('layouts.main')
@section('content')
<style>
    .xsm-col-th {
        width: 5%
    }
    .md-col-th {
        width: 10%
    }
    .sm-col-th {
        width: 15%
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav">Testy / Testy stworzone przez Ciebie</div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#waiting">Oczekujące</a></li>
    <li><a data-toggle="tab" href="#active">Aktywowane</a></li>
    <li><a data-toggle="tab" href="#finished">Zakończone</a></li>
    <li><a data-toggle="tab" href="#judged">Ocenione</a></li>
</ul>

<div class="tab-content">
    <div id="waiting" class="tab-pane fade in active">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-striped">
                <thead class="black-head">
                    <tr>
                        <td class="xsm-col-th">Lp.</td>
                        <td class="sm-col-th">Data</td>
                        <td>Użytkownik</td>
                        <td>Nazwa testu</td>
                        <td class="md-col-th">Aktywacja</td>
                        <td class="md-col-th">Szczegóły</td>
                        <td class="md-col-th">Edycja</td>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($tests->where('status', '=', 1) as $test)
                        @php($i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$test->created_at}}</td>
                            <td>{{$test->user->first_name . ' ' . $test->user->last_name}}</td>
                            <td>{{$test->name}}</td>
                            <td>
                                <button class="btn btn-default test_activate" onclick="change(this)" id="{{$test->id}}">
                                    <span class="glyphicon glyphicon-ok" style="color: green"></span> Aktywuj test
                                </button>
                            </td>
                            <td>
                                <a class="btn btn-default" href="{{ URL::to('/test_result') }}/{{$test->id}}">
                                    <span style="color: green" class="glyphicon glyphicon glyphicon-info-sign"></span> Szczegóły
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-default" href="{{ URL::to('/view_test') }}/{{$test->id}}">
                                    <span style="color: green" class="glyphicon glyphicon glyphicon-pencil"></span> Edytuj
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <div class="alert alert-destroyer">Brak testów w tej kategorii!</div>
            @endif
        </div>
    </div>
    <div id="active" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-striped">
                <thead class="black-head">
                    <tr>
                        <td class="xsm-col-th">Lp.</td>
                        <td class="sm-col-th">Data</td>
                        <td>Użytkownik</td>
                        <td>Nazwa testu</td>
                        <td class="sm-col-th">Szczegóły</td>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($tests->where('status', '=', 2) as $test)
                        @php($i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$test->created_at}}</td>
                            <td>{{$test->user->first_name . ' ' . $test->user->last_name}}</td>
                            <td>{{$test->name}}</td>
                            <td>
                                <a class="btn btn-default" href="{{ URL::to('/test_result') }}/{{$test->id}}">
                                    <span style="color: green" class="glyphicon glyphicon glyphicon-info-sign"></span> Szczegóły
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <div class="alert alert-destroyer">Brak testów w tej kategorii!</div>
            @endif
        </div>
    </div>

    <div id="finished" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-striped">
                <thead class="black-head">
                    <tr>
                        <td class="xsm-col-th">Lp.</td>
                        <td class="sm-col-th">Data</td>
                        <td>Użytkownik</td>
                        <td>Nazwa testu</td>
                        <td class="md-col-th">Szczegóły</td>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($tests->where('status', '=', 3) as $test)
                        @php($i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$test->created_at}}</td>
                            <td>{{$test->user->first_name . ' ' . $test->user->last_name}}</td>
                            <td>{{$test->name}}</td>
                            <td>
                                <a class="btn btn-default" href="{{ URL::to('/check_test') }}/{{$test->id}}">
                                    <span class="glyphicon glyphicon-education" style="color: green"></span> Oceń
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <div class="alert alert-destroyer">Brak testów w tej kategorii!</div>
            @endif
        </div>
    </div>
    <div id="judged" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-striped">
                <thead class="black-head">
                    <tr>
                        <td class="xsm-col-th">Lp.</td>
                        <td class="sm-col-th">Data</td>
                        <td>Użytkownik</td>
                        <td>Nazwa testu</td>
                        <td class="sm-col-th">Szczegóły</td>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($tests->where('status', '=', 4) as $test)
                        @php($i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$test->created_at}}</td>
                            <td>{{$test->user->first_name . ' ' . $test->user->last_name}}</td>
                            <td>{{$test->name}}</td>
                            <td>
                                <a class="btn btn-default" href="{{ URL::to('/test_result') }}/{{$test->id}}">
                                    <span style="color: green" class="glyphicon glyphicon glyphicon-info-sign"></span> Szczegóły
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <div class="alert alert-destroyer">Brak testów w tej kategorii!</div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
/*
    Funkcja aktywująca test dla pracownika
*/
function change(e) {
    //pobranie id testu do aktywacji
    var test_id = $(e).attr('id');
  
    $.ajax({
        type: "POST",
        url: '{{ route('api.activateTest') }}',
        data: {
            "id":test_id
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response == 1) {
                swal('Test został aktywowany!')
                location.reload();
            } else {
                swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!')
            }
        },
        error: function(response) {
            swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!')
        }
    });
}

</script>
@endsection
