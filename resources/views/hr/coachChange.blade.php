@extends('layouts.main')
@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <style>
        .trainers, .newCoaches {
            transition: all 0.8s ease-in-out;
        }

        .alert-danger {
            animation-name: animacja;
            animation-duration: 3s;
            color: white;
            font-size: 1.1em;
            animation-iteration-count: infinite;
        }

        @keyframes animacja {
            0% {
                background-color: red;
            }
            50% {
                background-color: lightcoral;
            }
            100% {
                background-color: red;
            }
        }
    </style>

    {{--Header page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Zmiana trenera</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Zmień trenera
                </div>

                <div class="panel-body">
                    <div class="alert alert-info">
                        Lista <strong>Trenerzy</strong> zawiera trenerów z oddziału zalogowanego użytkownika, którzy
                        posiadają konsultantów w swojej grupie trenerskiej.<br/>
                        Lista <strong>Dostępni trenerzy</strong> zawiera trenerów, do których można dopisać
                        konsultantów.
                    </div>
                    <form action="{{URL::to('/coachChange')}}" method="post" id="formularz">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{--@if(Session::has('adnotation'))
                        <div class="alert alert-info">
                            {{Session::get('adnotation')}}
                        </div>
                        {{Session::forget('adnotation')}}
                        @endif--}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="coach"><span class="trainers"
                                                             id="trainers">Trenerzy (z kogo)</span></label>
                                    <select name="coach_id" id="coach" class="form-control" required>
                                        <option value="0">Wybierz</option>
                                        @foreach($coaches as $coach)
                                            <option value="{{$coach->id}}">{{$coach->first_name}} {{$coach->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="newCoach"><span class="newCoaches" id="newCoaches">Dostępni trenerzy(na kogo)</span></label>
                                    <select name="newCoach_id" id="newCoach" class="form-control" required>
                                        <option value="0">Wybierz</option>
                                        @foreach($newCoaches as $coach)
                                            <option value="{{$coach->id}}">{{$coach->first_name}} {{$coach->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="submit" id="submitButton" class="btn btn-success btn-block"
                                       value="Zapisz!">
                            </div>
                        </div>
                    </form>
                    @if(Session::has('message_ok'))
                        <div class="alert alert-success" style="margin-top: 1em">{{ Session::get('message_ok') }}</div>
                    @endif
                    @if(Session::has('message_warning'))
                        <div class="alert alert-warning"
                             style="margin-top: 1em">{{ Session::get('message_warning') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{--tabela zmian pojawia sie gdy zalogowany użytkownik jest super adminem--}}
    @if(Auth::user()->user_type_id == 3)
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Cofnij zmiany
                    </div>
                    <div id="revertbtns">
                        <div class="panel-body">
                            <table id='tableCoachChange' class="table table-striped cell-border hover order-column row-border"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>Trener</th>
                                    <th>Poprzedni trener</th>
                                    <th>Data zmiany</th>
                                    <th>Cofnięcie</th>
                                </tr>
                                </thead>
                                {{--<form action="{{URL::to('/coachChangeRevert')}}" method="post" id="formularz">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <tbody>
                                    @foreach($coachChanges as $coachChange)
                                        <tr>
                                            <td>{{$coachChange->c_first_name." ".$coachChange->c_last_name}}</td>
                                            <td>{{$coachChange->pc_first_name." ".$coachChange->pc_last_name}}</td>
                                            <td>{{$coachChange->created_at}}</td>
                                            <td>
                                                <button class="btn btn-info" type="submit"
                                                        id="revertbtn_{{$coachChange->id}}"
                                                        name="coach_change_id"
                                                        value="{{$coachChange->id}}" data-type="revert_button">
                                                    Cofnij
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </form>--}}
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
@endsection
@section('script')
    <script>
        tableCoachChange = null;
        $(document).ready(function () {

            tableCoachChange = $('#tableCoachChange').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "scrollY": '40vh',
                "order": [[2, "desc"]],
                "ajax": {
                    'url': `{{ route('api.datatableCoachChange') }}`,
                    'type': 'POST',
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                }, "columns": [
                    {
                        "data": function (data, type, dataToSet) {
                            return data.c_first_name + " " + data.c_last_name;
                        }, "name": "c_last_name"
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            return data.pc_first_name + " " + data.pc_last_name;
                        }, "name": "pc_last_name"
                    },
                    {"data": "created_at"},
                    {
                        "data": function (data, type, dataToSet) {
                            return '<button class="btn btn-info type="submit" id="revertbtn_' +
                                data.id +
                                'name="coach_change_id" value="' +
                                data.id +
                                '" data-type="revert_button">Cofnij</button>';
                            /*'<button class="btn btn-info" type="submit" id="revertbtn_'+data.id+
                            "name=\"coach_change_id"+
                            "value="+data.id+"data-type=\"revert_button\">Cofnij</button>";*/
                        }, "name": "id", "orderable": false, "searchable": false
                    }
                ]
            });
        });
        /* ----------- Variables----------- */

        /* ----------- Event Listeners ----------- */

        //zdarzenie nacisniecia przycisku zmiany trenera
        $('#submitButton').click((e) => {
            e.preventDefault();
            var coachId = $('#coach').val();
            var newCoachId = $('#newCoach').val();
            if (coachId === "0" || newCoachId === "0") {    //sprawdzenie czy trenerzy zostali wybrani
                swal('Wybierz trenerów w obu polach');
            }
            else {
                swal({
                    title: "Jesteś pewien?",
                    type: "warning",
                    text: "Czy chcesz zmienić trenera?",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Tak, zamień!",
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        var resp = null;
                        changeCoachAjax(coachId, newCoachId, function (response) {
                            resp = response;
                        });
                        return resp;
                    }
                }).then((response) => {
                    swal(response.value['title'], response.value['msg'], response.value['type']);
                    if (response.value['type'] === "success")
                        tableCoachChange.ajax.reload();
                });
            }
        });

        //zdarzenie nacisniecia przycisku cofaniecia zmian
        $('#revertbtns').click(function (e) {
            if (e.target.dataset.type === "revert_button") {
                e.preventDefault();
                swal({
                    title: "Jesteś pewien?",
                    type: "warning",
                    text: "Czy chcesz cofnąć zmianę?",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Tak, cofnij!",
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        var resp = null;
                        changeCoachRevertAjax(e.target.value, function (response) {
                            resp = response;
                        });
                        return resp;
                    }
                }).then((response) => {
                    swal(response.value['title'], response.value['msg'], response.value['type']);
                    if (response.value['type'] === "success")
                        tableCoachChange.ajax.reload();
                });

            }
        });

        /* ----------- AJAX ----------- */

        function changeCoachAjax(coach_id, newCoach_id, callback) {
            $.ajax({
                async: false,
                type: "POST",
                url: '{{ route('api.coachChange') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "action": 'coachChange',
                    "coach_id": coach_id,
                    "newCoach_id": newCoach_id
                },

                success: function (response) {
                    callback(response);
                },
                error: function (jqXHR, textStatus, thrownError) {
                    console.log(jqXHR);
                    console.log('textStatus: ' + textStatus);
                    console.log('hrownError: ' + thrownError);
                    callback({type: 'error', msg: 'Wystąpił błąd: ' + thrownError, title: 'Błąd ' + jqXHR.status});
                }
            });
        }

        function changeCoachRevertAjax(coachChangeId, callback) {
            $.ajax({
                async: false,
                type: "POST",
                url: '{{ route('api.coachChangeRevert') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "action": 'coachChangeRevert',
                    "coach_change_id": coachChangeId
                },
                success: function (response) {
                    callback(response);
                },
                error: function (jqXHR, textStatus, thrownError) {
                    console.log(jqXHR);
                    console.log('textStatus: ' + textStatus);
                    console.log('thrownError: ' + thrownError);
                    callback({type: 'error', msg: 'Wystąpił błąd: ' + thrownError, title: 'Błąd ' + jqXHR.status});
                }
            });
        }
    </script>
@endsection