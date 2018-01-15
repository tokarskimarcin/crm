@extends('layouts.main')
@section('content')
<style>
    .xsm-col-th {
        width: 5%
    }
    .sm-col-th {
        width: 15%
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Wszystkie testy (osoba testująca)</h1>
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
                <thead>
                    <tr>
                        <td class="xsm-col-th">Lp.</td>
                        <td class="sm-col-th">Data</td>
                        <td>Użytkownik</td>
                        <td>Nazwa testu</td>
                        <td class="sm-col-th">Aktywacja</td>
                        <td class="sm-col-th">Szczegóły</td>
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
                                <button class="btn btn-default">
                                    Szczegóły
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <h3>Brak danych!</h3>
            @endif
        </div>
    </div>
    <div id="active" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-striped">
                <thead>
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
                                tutaj nie można zaglądać
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <h3>Brak danych!</h3>
            @endif
        </div>
    </div>

    <div id="finished" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-striped">
                <thead>
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
                <h3>Brak danych!</h3>
            @endif
        </div>
    </div>
    <div id="judged" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 20px">
            <table class="table table-striped">
                <thead>
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
                                <a class="btn btn-default" href="{{ URL::to('/check_test') }}/{{$test->id}}">
                                    Szczegóły
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <h3>Brak danych!</h3>
            @endif
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function change(e) {
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
                console.log(response);
                if (response == 1) {
                    swal('Test został aktywowany!');
                    location.reload();
                } else {
                    swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!');
                }
            }
        });
}



</script>
@endsection
