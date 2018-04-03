@extends('layouts.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Raport Dzienny Trenerzy (Zbiorczy)</h1>
        </div>
    </div>
    <form method="POST" action="{{ URL::to('/pageSummaryDayReportCoaches') }}" id="my_dep_form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Oddział:</label>
                    <select class="form-control" name="dep_id" id="dep_id">
                        <option value="0">Wybierz</option>
                        @foreach($department_info as $item)
                            <option @if($dep_id == $item->id) selected @endif value="{{ $item->id }}">{{ $item->departments->name . ' ' . $item->department_type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Wybierz miesiąc:</label>
                    <select class="form-control" id="month_selected" name="month_selected">
                        @foreach($months as $key => $value)
                            <option @if($key == $month) selected @endif value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="form-group">
                    <input id="get_dep" style="margin-top: 25px; width: 100%" type="submit" class="btn btn-info" value="Generuj raport">
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
                                    @include('mail.hourReportCoach')
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




            $('#get_dep').click((e) => {
                e.preventDefault();
            if ($('#dep_id').val() == 0) {
                swal('Wybierz oddział!');
                return false;
            } else {
                $('#my_dep_form').submit();
            }
        });
        });

    </script>
@endsection
