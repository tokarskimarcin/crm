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

    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Raport Miesięczny Oddziały</div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageReportDepartments') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                        <optgroup label="Dyrektorzy">
                            @foreach($directors as $director)
                                <option
                                    @if($wiev_type == 'director' && ('10' . $director->id == $dep_id)) selected @endif
                                value="10{{ $director->id }}">{{ $director->last_name . ' ' . $director->first_name }}</option>
                            @endforeach
                        </optgroup>
                        @if(Auth::user()->id == 1364)
                            <optgroup label="Suma oddziałów">
                                <option @if($dep_id == 100001) selected @endif value="100001">Suma oddziałów</option>
                            </optgroup>
                        @endif
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
