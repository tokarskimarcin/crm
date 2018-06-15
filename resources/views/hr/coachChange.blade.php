@extends('layouts.main')
@section('content')
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

    @if(Session::has('message_ok'))
        <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Zmień trenera
                </div>

                <div class="panel-body">
                    <form action="{{URL::to('/coachChange')}}" method="post" id="formularz">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{--@if(Session::has('adnotation'))
                        <div class="alert alert-info">
                            {{Session::get('adnotation')}}
                        </div>
                        {{Session::forget('adnotation')}}
                        @endif--}}
                        {{--@if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif--}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="coach"><span class="trainers" id="trainers">Trenerzy prowadzący coachingi(z kogo)</span></label>
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
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->user_type_id == $user_type_id)
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Cofnij zmiany
                    </div>

                    <div class="panel-body">
                        <form action="{{URL::to('/coachChangeRevert')}}" method="post" id="formularz">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <table class="table">
                                <tr>
                                    <th>Trener</th>
                                    <th>Poprzedni trener</th>
                                    <th>Data zmiany</th>
                                    <th>Cofnięcie</th>
                                </tr>
                                @foreach($coachChanges as $coachChange)
                                    <tr>
                                        <td>{{$coachChange->c_first_name." ".$coachChange->c_last_name}}</td>
                                        <td>{{$coachChange->pc_first_name." ".$coachChange->pc_last_name}}</td>
                                        <td>{{$coachChange->created_at}}</td>
                                        <td>
                                            <button class="btn btn-info" type="submit"
                                                    id="revertbtn_{{$coachChange->id}}"
                                                    name="revertbtn"
                                                    value="{{$coachChange->id}}">
                                                Cofnij
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('script')
    <script>
        $('#submitButton').click((e) => {
            e.preventDefault();
            var coach = $('#coach').val();
            var newCoach = $('#newCoach').val();
            if (coach === "0" || newCoach === "0") {
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
                    closeOnConfirm: false
                }).then((result) => {
                    if (result.value) {
                        thisForm = document.getElementById('formularz');
                        thisForm.submit();

                    }
                });

            }
        });

        /*function changeCoach(coach_id, newCoach_id) {
            $.ajax({
                type: "POST",
                url: '*/{{--{{ route('api.coachChange') }}--}}/*',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "coach_id": coach_id,
                    "newCoach_id": newCoach_id
                },
                success: function (response) {
                    if (response == 'ok')
                        swal("Udało się!", "Trener został zmieniony", "success");
                    else if (response == 'error')
                        swal("Nie udało się!", "Coś poszło nie tak", "warning");
                }
            });
        }*/
    </script>
@endsection
