@extends('layouts.main')
@section('content')

    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Raport Dzienny Trenerzy</div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageDayReportCoaches') }}" id="my_coach_form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-4">
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
            <div class="col-md-4">
                <div class="form-group">
                    <label>Wybierz miesiąc:</label>
                    <select class="form-control" id="month_selected" name="month_selected">
                        @foreach($months as $key => $value)
                            <option @if($key == $month) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Wybierz dzień:</label>
                    <select class="form-control" id="day_select" name="day_select">
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
            <div class="col-md-4">
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
            <div class="col-md-4">
                <div class="form-group">
                    <label>Nowi konsultanci ~30RBH:</label>
                    <select class="form-control" name="onlyNewUser" id="onlyNewUser">
                        <option value="0" @if(isset($onlyNewUser) && $onlyNewUser == 0) selected  @endif>Z konsultantami ~30RBH</option>
                        <option value="1" @if(isset($onlyNewUser) && $onlyNewUser == 1) selected @endif>Tylko konsultanci ~30RBH</option>
                        <option value="2" @if(isset($onlyNewUser) && $onlyNewUser == 2) selected @endif>Bez konsultantów ~30RBH</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input id="get_coach" style="margin-top: 25px; width: 100%" type="submit" class="btn btn-info" value="Generuj raport">
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info"> <strong>Raport Dzienny Trenerzy - zestawienie statystyk dotyczących trenera w wybranym dniu miesiąca, o konkretnej godzinie. </strong></br>
                <div class="additional_info">Średnia = liczba godzin / umówienia </br>
                    % janków = 100% * ilość janków / wszystkie sprawdzone rozmowy </br>
                    % ilość um/poł = 100% * umówienia / ilość połączeń</div>
            </div>
        </div>
    </div>
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
            $('#month_selected').change(() => {
                var month_selected = $('#month_selected').val();
            $.ajax({
                type: "POST",
                url: '{{ route('api.getDaysInMonth') }}',
                data: {
                    "month_selected":month_selected,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#day_select option').remove();

                    var content = '';
                    $.each(response.data, function(key, value) {
                        content += `
                                <option>
                                    ${value}
                                </option>
                            `;
                    });
                    $('#day_select').append(content);
                }
            });
        });

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
