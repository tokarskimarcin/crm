{{--/*--}}
{{--*@category: Coachings,--}}
{{--*@info: This view is responsible for ascripting new coach to "in progress" and "unsettled" coachings managed by another coach,--}}
{{--*@Database tables: coaching_director, users,--}}
{{--*@controller: CoachingController,--}}
{{--*@methods: coachAscriptionGet, coachAscriptionPost--}}
{{--*/--}}

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
                <div class="alert gray-nav ">Przepisywanie coachingów</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Przepisz coaching
                </div>
                <div class="panel-body">
                    <form action="{{URL::to('/coachAscription')}}" method="post" id="formularz">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @if(Session::has('adnotation'))
                            <div class="alert alert-info">
                                {{Session::get('adnotation')}}
                            </div>
                            {{Session::forget('adnotation')}}
                        @endif
                        <div class="alert alert-danger">
                            Wprowadzone zmiany są <strong>Ostateczne</strong> i <strong><span
                                        style="text-decoration: underline">NIE</span></strong> mogą być cofnięte.
                        </div>
                        <div class="alert alert-info">
                            Lista <strong><span class="trainers" data-info="first">Trenerzy prowadzący coachingi</span></strong>
                            zawiera trenerów z oddziału zalogowanego użytkownika, którzy prowadzą coachingi <i>"w
                                toku"</i> oraz <i>"nierozliczone"</i>, </br>
                            Lista <strong><span class="newCoaches" data-info="second">Dostępni trenerzy</span></strong>
                            zawiera trenerów, dla których mogą zostać przypisane coachingi.
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="coaches"><span class="trainers" id="trainers">Trenerzy prowadzący coachingi(z kogo)</span></label>
                                    <select name="coaches" id="coaches" class="form-control" required>
                                        <option value="0">Wybierz</option>
                                        @foreach($coachingOwners as $coach)
                                            <option value="{{$coach->id}}">{{$coach->first_name}} {{$coach->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="newCoach"><span class="newCoaches" id="newCoaches2">Dostępni trenerzy(na kogo)</span></label>
                                    <select name="newCoach" id="newCoach" class="form-control" required>
                                        <option value="0">Wybierz</option>
                                        @foreach($allTrainers as $coach)
                                            <option value="{{$coach->id}}">{{$coach->first_name}} {{$coach->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input id="submitButton" type="submit" class="btn btn-success" value="Zapisz!"
                                       style="width:100%">
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
    @if(Auth::user()->user_type_id == 3)
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Cofnij przepisanie
                    </div>
                    <div id="revertbtns">
                        <div class="panel-body">
                            <table id='tableCoachAscription' class="table table-striped cell-border hover order-column row-border" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Trener prowadzący</th>
                                    <th>Poprzedni trener</th>
                                    <th>Data zmiany</th>
                                    <th>Cofnięcie</th>
                                </tr>
                                </thead>
                                {{--@foreach($coachDirectorChanges as $coachDirectorChange)
                                    <tr>
                                        <td>{{$coachDirectorChange->c_first_name." ".$coachDirectorChange->c_last_name}}</td>
                                        <td>{{$coachDirectorChange->pc_first_name." ".$coachDirectorChange->pc_last_name}}</td>
                                        <td>{{$coachDirectorChange->created_at}}</td>
                                        <td>
                                            <button type="submit" class="btn btn-info"
                                                    id="revertbtn_{{$coachDirectorChange->id}}"
                                                    name="coach_director_change_id" data-type="revert_button"
                                                    value="{{$coachDirectorChange->id}}">
                                                Cofnij
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach--}}
                                <tbody></tbody>
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
        tableCoachAscription = null;
        $(document).ready(function () {
            tableCoachAscription = $('#tableCoachAscription').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "scrollY": '40vh',
                "order": [[2, "desc"]],
                "ajax": {
                    'url': `{{ route('api.datatableCoachAscription') }}`,
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
                                'name="coach_director_change_id" value="' +
                                data.id +
                                '" data-type="revert_button">Cofnij</button>';
                        }, "name": "id", "orderable": false, "searchable": false
                    }
                ]
            });
        });

        $('#submitButton').click((e) => {
            e.preventDefault();
            let newCoachSelected = $('#coaches').val();
            let prevCoachSelected = $('#newCoach').val();
            if (prevCoachSelected === "0" || newCoachSelected === "0") {
                swal('Wybierz trenerów w obu polach');
            }
            else {
                swal({
                    title: 'Jesteś pewien?',
                    text: "Po potwierdzeniu, brak możliwości cofnięcia zmian!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Tak, zamień!",
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        var resp = null;
                        changeAscriptionAjax(prevCoachSelected, newCoachSelected, function (response) {
                            resp = response;
                        });
                        return resp;
                    }
                }).then((response) => {
                    swal(response.value['title'], response.value['msg'], response.value['type']);
                    if (response.value['type'] === "success")
                        tableCoachAscription.ajax.reload();
                });
            }
        });


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
                        coachAscriptionRevertAjax(e.target.value, function (response) {
                            resp = response;
                        });
                        return resp;
                    }
                }).then((response) => {
                    swal(response.value['title'], response.value['msg'], response.value['type']);
                    if (response.value['type'] === "success")
                        tableCoachAscription.ajax.reload();
                });

            }
        });


        /* ----------- AJAX ----------- */
        function changeAscriptionAjax(coach_id, newCoach_id, callback) {
            $.ajax({
                async: false,
                type: "POST",
                url: '{{ route('api.coachAscription') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "action": 'coachAscription',
                    "coaches": coach_id,
                    "newCoach": newCoach_id
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

        function coachAscriptionRevertAjax(coachDirectorChangeId, callback) {
            $.ajax({
                async: false,
                type: "POST",
                url: '{{ route('api.coachAscriptionRevert') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "action": 'coachAscriptionRevert',
                    "coach_director_change_id": coachDirectorChangeId
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

        //PART RESPONSIBLE FOR EFFECTS ON LEGEND
        let trainers = Array.from(document.getElementsByClassName('trainers'));
        trainers.forEach(function (element) {
            element.addEventListener('mouseover', function (e) {
                if (e.target.dataset.info == "first") {
                    let firstResponse = document.getElementById('trainers');
                    firstResponse.style.color = 'red';
                    e.target.style.cursor = "help";
                    firstResponse.style.fontSize = '1.1em';
                    e.target.style.color = 'red';
                }
            });
            element.addEventListener('mouseleave', function (e) {
                if (e.target.dataset.info == "first") {
                    let firstResponse = document.getElementById('trainers');
                    firstResponse.style.color = 'black';
                    firstResponse.style.fontSize = '1em';
                    e.target.style.color = '#31708f';
                }
            })
        });

        let newCoaches = Array.from(document.getElementsByClassName('newCoaches'));
        newCoaches.forEach(function (element) {
            element.addEventListener('mouseover', function (e) {
                if (e.target.dataset.info == "second") {
                    let firstResponse = document.getElementById('newCoaches2');
                    firstResponse.style.color = 'red';
                    e.target.style.cursor = "help";
                    firstResponse.style.fontSize = '1.1em';
                    e.target.style.color = 'red';
                }
            });
            element.addEventListener('mouseleave', function (e) {
                if (e.target.dataset.info == "second") {
                    let firstResponse = document.getElementById('newCoaches2');
                    firstResponse.style.color = 'black';
                    firstResponse.style.fontSize = '1em';
                    e.target.style.color = '#31708f';
                }
            });
        });
        /*});*/


    </script>
@endsection
