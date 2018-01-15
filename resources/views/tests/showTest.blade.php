@extends('layouts.main')
@section('content')

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
                        <td>Lp.</td>
                        <td>Data</td>
                        <td>Użytkownik</td>
                        <td>Nazwa testu</td>
                        <td>Aktywacja</td>
                        <td>Szczegóły</td>
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
        </div>
    </div>
    <div id="active" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 20px">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>Lp.</td>
                            <td>Data</td>
                            <td>Użytkownik</td>
                            <td>Nazwa testu</td>
                            <td>Szczegóły</td>
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
                                    <button class="btn btn-default">
                                        Szczegóły
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    <div id="finished" class="tab-pane fade">
        <div class="table-responsive" style="margin-top: 20px">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>Lp.</td>
                            <td>Data</td>
                            <td>Użytkownik</td>
                            <td>Nazwa testu</td>
                            <td>Szczegóły</td>
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
                                    <button class="btn btn-default">
                                        Szczegóły
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="judged" class="tab-pane fade"> eeee
        <div class="table-responsive" style="margin-top: 20px">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>Lp.</td>
                            <td>Data</td>
                            <td>Użytkownik</td>
                            <td>Nazwa testu</td>
                            <td>Szczegóły</td>
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
                                    <button class="btn btn-default">
                                        Szczegóły
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
