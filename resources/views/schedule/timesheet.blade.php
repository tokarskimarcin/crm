@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <div id="page_title">Lista obecności
              <span id="show_selected">
                @if(isset($selected_date))
                 {{$selected_date}}
                @endif
              </span>
            </div>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <form method="POST" action="{{URL::to('/timesheet/')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="timesheet_date"><h3>Wybierz datę:</h3></label>
            </div>
            <div class="form-group">
                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                <input class="form-control" name="timesheet_date" id="timesheet_date" type="text" value="@if(isset($selected_date)){{$selected_date}}
                @else{{date('Y-m-d')}}
                @endif
                " readonly>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span></div>
            </div>
            <div class="form-group">
                <button class="btn btn-default btn-lg" id="date_selected">Pokaż listę obecności</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Imie i nazwisko</th>
                        <th>Godziny pracy</th>
                        <th>Stawka</th>
                        <th>Ilość zgód</th>
                        <th>Średnia</th>
                        <th>Pensja</th>
                    </tr>
                </thead>
                <tbody>
                  @php
                      $total_time = 0;
                      $total_cash = 0;
                      $total_success = 0;
                      $total_avg_rate = 0;
                      $total_avg = 0;
                      $lp = 1;
                  @endphp
                  @if(isset($work_hours))
                      @foreach($work_hours as $item)
                          @if($item->user->department_info_id == Auth::user()->department_info_id && ($item->user->user_type_id == 1 || $item->user->user_type_id == 2))
                            <tr>
                                <td>{{$lp}}</td>
                                <td>{{$item->user->first_name . ' ' . $item->user->last_name}}</td>
                                <td>{{$item->accept_start . ' - ' . $item->accept_stop}}</td>
                                <td>{{$item->user->rate}} PLN/H</td>
                                <td>{{$item->success}}</td>
                                <td>
                                    @php
                                        $start_array = explode(":", $item->accept_start);
                                        $stop_array = explode(":", $item->accept_stop);
                                        $sum_start = (($start_array[0] * 60) + $start_array[1]);
                                        $sum_stop = (($stop_array[0] * 60) + $stop_array[1]);
                                        $hour_sum = ($sum_stop - $sum_start) / 60;
                                        $cash = round($hour_sum * $item->user->rate, 2);
                                        $avg = round($item->success / $hour_sum, 2);
                                        $lp++;

                                        $total_time += $hour_sum;
                                        $total_cash += $cash;
                                        $total_success += $item->success;
                                        $total_avg_rate += $item->user->rate;
                                        $total_avg += $avg;
                                    @endphp
                                    {{$avg}}
                                </td>
                                <td>
                                    {{$cash}} PLN
                                </td>
                            </tr>
                          @endif
                      @endforeach
                      @if($total_cash > 0)
                      <tr>
                        <td colspan="1"></td>
                        <td><b>SUMA</b></td>
                        <td><b>{{round($total_time, 2)}} RBH</b></td>
                        <td><b>-</b></td>
                        <td><b>{{$total_success}}</b></td>
                        <td><b>{{round($total_success / $total_time ,2)}}</b></td>
                        <td><b>{{$total_cash}} PLN</b></td>
                      </tr>
                      @endif
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
