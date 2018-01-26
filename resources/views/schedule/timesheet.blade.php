@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <div id="page_title">Lista obecności</div>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{URL::to('/timesheet/')}}">
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


<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped thead-inverse" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Imie i nazwisko</th>
                        <th>Godziny pracy</th>
                        <th>Stawka</th>
                        <th>Ilość zgód</th>
                        <th>Średnia</th>
                        <th>Podstawa</th>
                    </tr>
                </thead>
                <tbody>
                  @php
                      $total_time = 0;
                      $total_cash = 0;
                      $total_success = 0;
                      $total_avg_rate = 0;
                      $total_avg = 0;
                      $lp = 0;
                  @endphp
                  @if(isset($hours))

                      @foreach($hours as $hour)
                          @php
                              $lp++;
                              $avg = round($hour->user_success / $hour->user_sum, 2);
                              $user_cash = round($hour->user_sum * $hour->rate , 2);
                              $total_time += $hour->user_sum;
                              $total_avg_rate += $hour->rate;
                              $total_success += $hour->user_success;
                              $total_avg += round($hour->user_success / $hour->user_sum, 2);
                              $total_cash += $user_cash;
                          @endphp
                          <tr>
                              <td>{{$lp}}</td>
                              <td>{{$hour->last_name . ' ' . $hour->first_name}}</td>
                              <td>{{round($hour->user_sum, 2)}}</td>
                              <td>{{$hour->rate}}</td>
                              <td>{{$hour->user_success}}</td>
                              <td>{{$avg}}</td>
                              <td>{{$user_cash}}</td>
                          </tr>
                      @endforeach

                      <tr>
                          <td colspan="1"></td>
                          <td><b>SUMA</b></td>
                          <td><b>{{round($total_time)}}</b></td>
                          @if($lp > 0)
                              <td><b>{{round($total_avg_rate / $lp, 2)}}</b></td>
                          @else
                              <td><b>0</b></td>
                          @endif
                          <td><b>{{$total_success}}</b></td>
                          @if($lp > 0)
                              <td><b>{{round($total_success / $total_time, 2)}}</b></td>
                          @else
                              <td><b>0</b></td>
                          @endif
                          <td><b>{{round($total_cash, 2)}}</b></td>
                      </tr>
                  @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

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
