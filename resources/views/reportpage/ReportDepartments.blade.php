@extends('layouts.main')
@section('content')


    {{--<style>--}}

        {{--.sticky {--}}
    {{--position: fixed;--}}
    {{--top: 0;--}}
    {{--display: block;--}}
            {{--width: 80%;--}}
    {{--}--}}
        {{--.sticky-help {--}}
            {{--width: 900px;--}}
        {{--}--}}
        {{--.hidden {--}}
            {{--display:none;--}}
        {{--}--}}
        {{--.display {--}}
            {{--display:table;--}}
        {{--}--}}



    {{--</style>--}}
    <style>
        .second_row {
            overflow-x: scroll;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Raport Miesięczny Oddziały</div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageReportDepartments') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if(isset($director_departments))
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <strong>Raport dla oddziałów:</strong> <br>
                        @foreach($director_departments as $dep)
                            {{$dep->departments->name}} {{$dep->department_type->name}},
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Oddział:</label>

                    @if(Auth::user()->user_type_id != 4)

                    <select class="form-control" name="selected_dep">
                        <optgroup label="Oddziały">
                            @foreach($departments as $dep)
                                <option value="{{$dep->id}}" @if(($wiev_type == 'department') && $dep->id == $dep_id) selected @endif>{{$dep->departments->name . ' ' . $dep->department_type->name}}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Kierownik Regionalny HR">
                            @foreach($directorsHR as $director)
                                @php
                                    $allDepartments = $departments->where('director_hr_id', '=', $director->id);
                                @endphp
                                <option data-toggle="tooltip" data-placement="right" title="@foreach($allDepartments as $dep){{$dep->departments->name}} {{$dep->department_type->name}}, @endforeach"
                                        @if($wiev_type == 'director' && ('10' . $director->id == $dep_id)) selected @endif
                                value="10{{ $director->id }}">{{ $director->last_name . ' ' . $director->first_name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Szkoleniowiec Regionalny">
                            @foreach($regionalManagersInstructors as $regionalManagersInstructor)
                                @php
                                    $allDepartments = $departments->where('instructor_regional_id', '=', $regionalManagersInstructor->id);
                                @endphp
                                <option data-toggle="tooltip" data-placement="right" title="@foreach($allDepartments as $dep){{$dep->departments->name}} {{$dep->department_type->name}}, @endforeach"
                                        @if($wiev_type == 'director' && ('10' . $regionalManagersInstructor->id == $dep_id)) selected @endif
                                        value="10{{ $regionalManagersInstructor->id }}">{{ $regionalManagersInstructor->last_name . ' ' . $regionalManagersInstructor->first_name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Kierownik Regionalny">
                            @foreach($regionalManagers as $director)
                                @php
                                    $allDepartments = $departments->where('regionalManager_id', '=', $director->id);
                                @endphp
                                <option data-toggle="tooltip" data-placement="right" title="@foreach($allDepartments as $dep){{$dep->departments->name}} {{$dep->department_type->name}}, @endforeach"
                                        @if($wiev_type == 'director' && ('10' . $director->id == $dep_id)) selected @endif
                                value="10{{ $director->id }}">{{ $director->last_name . ' ' . $director->first_name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Dyrektorzy">
                            @foreach($directors as $director)
                                @php
                                    $allDepartments = $departments->where('director_id', '=', $director->id);
                                @endphp
                                <option data-toggle="tooltip" data-placement="right" title="@foreach($allDepartments as $dep){{$dep->departments->name}} {{$dep->department_type->name}}, @endforeach"
                                    @if($wiev_type == 'director' && ('10' . $director->id == $dep_id)) selected @endif
                                value="10{{ $director->id }}">{{ $director->last_name . ' ' . $director->first_name }} <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right" title="
                                @foreach($allDepartments as $dep)
                                    {{$dep->departments->name}} {{$dep->department_type->name}},
                                @endforeach
                                            "></span></option>
                            @endforeach
                        </optgroup>
                            <optgroup label="Suma oddziałów">
                                <option @if($dep_id == 100001) selected @endif value="1000001">Suma oddziałów</option>
                            </optgroup>
                    </select>
                    @else
                        <select class="form-control" name="selected_dep">
                                @foreach($departments as $dep)
                                        @if($dep->id == Auth::user()->department_info_id)
                                            <option value="{{$dep->id}}" @if(($wiev_type == 'department') && $dep->id == $dep_id) selected @endif>{{$dep->departments->name . ' ' . $dep->department_type->name}}</option>
                                        @endif
                                @endforeach
                        </select>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Miesiąc:</label>
                    <select class="form-control" name="month_selected">
                        @foreach($months as $key => $value)
                            <option @if($month == $key) selected @endif value="{{$key}}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input style="margin-top: 25px; width: 100%" type="submit" class="btn btn-info" value="Generuj raport">
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info"> <strong>Raport Miesięczny Oddziały - zestawienie statystyk dotyczących wybranego oddziału w wybranym miesiącu, podzielonych na tygodnie. </strong></br>
                <div class="additional_info">
                    RBH Plan = Roboczogodziny w grafiku </br>
                    RBH Real = Roboczogodziny zaakceptowane, podsumowanie tygodniowe (godziny w tygodniu / godziny weekendowe) </br>
                    Średnia = Zgody / RBH real </br>
                    % janków = 100% * liczba janków / liczba odsłuchanych </br>
                    % celu = 100% * zgody / cel zgody </br>
                    Cel RBH = cel zgody / średnia na projekcie</br>
                    Grafik Real = 100% * RBH Real / Cel RBH (procent zaakceptowanych RBH względem zaplanowanych RBH)

                </div>
            </div>
            <div class="alert alert-warning">Aby uzyskać możliwie najlepszy podgląd raportu zaleca się wyłączenie panelu nawigacyjnego</br> naciskając przycisk OFF w górnym lewym rogu dokumentu. </br> Raport można przewijać horyzontalnie!</div>
        </div>
    </div>

    <div class="row second_row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="start_stop">
                            <div class="panel-body">
                                @if($wiev_type == 'director')
                                    @include('mail.monthReportDirectors')
                                @else
                                    @include('mail.reportDepartments')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

@section('script')

    <script>
        // $(document).ready(function() {
            // window.onscroll = function() {myFunction()};
            // var header = document.getElementById('header');
            // var wind = $(window);
            // // var doc = $(document);
            //
            // var sticky = $('#header').offset().top;
            // var sticky2 = $('#header').innerHeight();
            //
            // var testInput = document.getElementById('testInput');
            // var testInput2 = document.getElementById('testInput2');
            //
            // // testInput.value = $(document).offset().top;
            // // testInput2.value = wind;
            // function myFunction() {
            //     if(window.pageYOffset >= sticky) {
            //         $('#header').toggleClass('sticky');
            //     }
            //     else {
            //         $('#header').toggleClass('sticky');
            //     }
            // }
        //
        //     wind.on('scroll', function() {
        //         console.log('typeofPageYoffset: ' + typeof(window.pageYOffset));
        //         console.log('PageYoffset: ' + window.pageYOffset);
        //         console.log('sticky: ' + sticky);
        //         console.log('sticky typeof: ' + typeof(sticky));
        //         console.log('difference: ' + (sticky - window.pageYOffset));
        //         console.log('typeofdifference: ' + typeof(sticky - window.pageYOffset));
        //
        //         if(window.pageYOffset >= sticky) {
        //             $('#header').addClass('sticky');
        //             $('#tableHidden').addClass('display');
        //             $('#tableHidden').removeClass('hidden');
        //
        //         }
        //         else {
        //             $('#header').removeClass('sticky');
        //             $('#tableHidden').addClass('hidden');
        //             $('#tableHidden').removeClass('display');
        //
        //         }
        //        console.log(window.pageYOffset);
        //        console.log('sticky: ' + sticky);
        //     });
        // });

    </script>
@endsection
