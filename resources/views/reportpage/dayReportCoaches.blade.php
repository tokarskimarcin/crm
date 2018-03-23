@extends('layouts.main')
@section('content')

    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Raport Dzienny Trenerzy</h1>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageDayReportCoaches') }}" id="my_coach_form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Trener:</label>
                    <select class="form-control" name="coach_id" id="coach_id">
                        <option value="0">Wybierz</option>
                        @foreach($coaches as $item)
                            <option @if($coach_id == $item->id) selected @endif value="{{ $item->id }}">{{ $item->last_name . ' ' . $item->first_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Wybierz dzień:</label>
                    <select class="form-control" name="day_select">
                        @for($i = 1; $i <= $days; $i++)
                            @php
                                $day = ($i < 10) ? '0' . $i : $i ;
                                $loop_day = $year . '-' . $month . '-' . $day;
                            @endphp
                            <option @if($loop_day == $date_selected) selected @endif>{{ $loop_day }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Wybierz godzinę:</label>
                    <select class="form-control" name="hour_select">
                        @for($i = 9; $i <= 20; $i++)
                            @php
                                $hour = ($i < 10) ? '0' . $i : $i ;
                                $loop_hour = $hour . ':00:00';
                            @endphp
                            <option @if($loop_hour == $hour_selected) selected @endif>{{ $loop_hour }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input id="get_coach" style="margin-top: 25px; width: 100%" type="submit" class="btn btn-info" value="Generuj raport">
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
                                @isset($data)
                                    @include('mail.dayReportCoach')
                                @endisset
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

        $(document).ready(function () {
            $('#get_coach').click((e) => {
                e.preventDefault();
                if ($('#coach_id').val() == 0) {
                    swal('Wybierz trenera!');
                    return false;
                } else {
                    $('#my_coach_form').submit();
                }
            });
        });

    </script>
@endsection
