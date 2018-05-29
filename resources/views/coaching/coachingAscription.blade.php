{{--/*--}}
{{--*@category: Coachings,--}}
{{--*@info: This view is responsible for ascripting new coach to "in progress" and "unsettled" coachings managed by another coach,--}}
{{--*@Database tables: coaching_director, users,--}}
{{--*@controller: CoachingController,--}}
{{--*@methods: coachAscriptionGet, coachAscriptionPost--}}
{{--*/--}}

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
            0% {background-color: red;}
            50% {background-color: lightcoral;}
            100% {background-color: red;}
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
                            Wprowadzone zmiany są <strong>Ostateczne</strong> i <strong><span style="text-decoration: underline">NIE</span></strong> mogą być cofnięte.
                        </div>
                        <div class="alert alert-info">
                            Lista <strong><span class="trainers" data-info="first">Trenerzy prowadzący coachingi</span></strong> zawiera trenerów z oddziału zalogowanego użytkownika, którzy prowadzą coachingi <i>"w toku"</i> oraz <i>"nierozliczone"</i>, </br>
                            Lista <strong><span class="newCoaches" data-info="second">Dostępni trenerzy</span></strong> zawiera trenerów, dla których mogą zostać przypisane coachingi.
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
                                <input id="submitButton" type="submit" class="btn btn-success" value="Zapisz!" style="width:100%">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function(event) {

            let submitButton = document.getElementById('submitButton');
            submitButton.addEventListener('click', function(e) {
               e.preventDefault();
               let prevCoachInput = document.getElementById('coaches');
               let newCoachInput = document.getElementById('newCoach');
               let newCoachSelected = newCoachInput.options[newCoachInput.selectedIndex].value;
               let prevCoachSelected = prevCoachInput.options[prevCoachInput.selectedIndex].value;
               if(prevCoachSelected === "0" || newCoachSelected === "0") {
                   swal('Wybierz trenerów w obu polach');
               }
               else {

                   swal({
                       title: 'Jesteś pewien?',
                       text: "Po potwierdzeniu, brak możliwości cofnięcia zmian!",
                       type: 'warning',
                       showCancelButton: true,
                       confirmButtonColor: '#3085d6',
                       cancelButtonColor: '#d33',
                       confirmButtonText: 'Przypisz!'
                   }).then((result) => {
                       if (result.value) {
                           thisForm = document.getElementById('formularz');
                           thisForm.submit();
                           swal(
                               'Przypisano!',
                               'Użytkownik został przypisany',
                               'Sukces'
                           )
                       }
                   });

               }
            });

            //PART RESPONSIBLE FOR EFFECTS ON LEGEND
            let trainers = Array.from(document.getElementsByClassName('trainers'));
            trainers.forEach(function(element) {
                element.addEventListener('mouseover', function(e) {
                    if(e.target.dataset.info == "first") {
                        let firstResponse = document.getElementById('trainers');
                        firstResponse.style.color = 'red';
                        e.target.style.cursor = "help";
                        firstResponse.style.fontSize = '1.1em';
                        e.target.style.color = 'red';
                    }
                });
                element.addEventListener('mouseleave', function(e) {
                    if(e.target.dataset.info == "first") {
                        let firstResponse = document.getElementById('trainers');
                        firstResponse.style.color = 'black';
                        firstResponse.style.fontSize = '1em';
                        e.target.style.color = '#31708f';
                    }
                })
            });

            let newCoaches = Array.from(document.getElementsByClassName('newCoaches'));
            newCoaches.forEach(function(element) {
                element.addEventListener('mouseover', function(e) {
                    if(e.target.dataset.info == "second") {
                        let firstResponse = document.getElementById('newCoaches2');
                        firstResponse.style.color = 'red';
                        e.target.style.cursor = "help";
                        firstResponse.style.fontSize = '1.1em';
                        e.target.style.color = 'red';
                    }
                });
                element.addEventListener('mouseleave', function(e) {
                    if(e.target.dataset.info == "second") {
                        let firstResponse = document.getElementById('newCoaches2');
                        firstResponse.style.color = 'black';
                        firstResponse.style.fontSize = '1em';
                        e.target.style.color = '#31708f';
                    }
                });
            });
        });
    </script>
@endsection
