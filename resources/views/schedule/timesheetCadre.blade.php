@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <div id="page_title">Lista obecności kadra</div>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{URL::to('/timesheet_cadre/')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label>
                    <h3>Wybierz datę:
                        <span id="show_selected">
                          <b>
                            @if(isset($date_start))
                                {{$date_start . ' - ' . $date_stop}}
                            @endif
                          </b>
                        </span>
                  </h3>
                </label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                    <input class="form-control" name="timesheet_date_start" id="timesheet_date_start" type="text" value="@if(isset($date_start)){{$date_start}}
                    @else{{date('Y-m-d')}}
                    @endif
                    " readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span></div>
                </div>
                <div class="form-group">
                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                    <input class="form-control" name="timesheet_date_stop" id="timesheet_date_stop" type="text" value="@if(isset($date_stop)){{$date_stop}}
                    @else{{date('Y-m-d')}}
                    @endif
                    " readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span></div>
                </div>
                <div class="form-group">
                    <button class="btn btn-default btn-lg" id="date_selected">Pokaż listę obecności</button>
                </div>
            </div>
        </form>
    </div>
</div>


@if(isset($hours))
    @php
        $lp = 0;
        $total_sum = 0;
    @endphp
    <div class="table-responsive">
        <table class="table table-striped thead-inverse">
            <thead>
                <tr>
                    <th>Lp.</th>
                    <th>Imie i nazwisko</th>
                    <th>Ilość godzin</th>
                </tr>
            </thead>
            <tbody>
            @foreach($hours as $hour)
                @php
                    $lp++;
                    $total_sum += $hour->user_sum;
                @endphp
                <tr>
                    <td>{{$lp}}</td>
                    <td>{{$hour->last_name . ' ' . $hour->first_name}}</td>
                    <td>{{round($hour->user_sum, 2)}}</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="1"></td>
                    <td><b>SUMA</b></td>
                    <td><b>{{round($total_sum, 2)}} RBH</b></td>
                </tr>
            </tbody>
        </table>
    </div>
@endif


@endsection
@section('script')
<script>

$('.form_date').datetimepicker({
    language: 'pl',
    autoclose: 1,
    minView: 2,
    pickTime: false,
});

</script>
@endsection
