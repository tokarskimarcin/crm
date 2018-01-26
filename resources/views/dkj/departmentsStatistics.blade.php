@extends('layouts.main')
@section('content')


    {{--Header page --}}


    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">DKJ / Statystyki Oddziałów</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default"  id="panel1">
                <div class="panel-heading">
                    <a data-toggle="collapse" data-target="#collapseOne">
                        Wybierz Oddział
                    </a>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="col-md-12">
                                    <div class="well">
                                            <div class="form-group">
                                                <form action="" method="post" action="departmentsStatistics">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <label for ="dep">Miesiac:</label>
                                                <select class="form-control" name="month">
                                                    @for ($i=0; $i < 2; $i++)
                                                        @php
                                                            $date = date("Y-m",mktime(0,0,0,date("m")-$i,1,date("Y")));
                                                        @endphp
                                                       @if (isset($month) && $month== $date) {
                                                           <option selected>{{$date}}</option>
                                                        @else{
                                                            <option>{{$date}}</option>
                                                          @endif
                                                    @endfor
                                                </select>
                                                    <label>Oddział:</label>
                                                    <select class="form-control" name="department_info_id">
                                                        @foreach($departments_info as $department)
                                                            @if(isset($department_info_id) && $department_info_id == $department->id)
                                                                <option selected value={{$department->id}}>{{$department->departments->name.' '.$department->department_type->name}}</option>
                                                            @else
                                                                <option value={{$department->id}}>{{$department->departments->name.' '.$department->department_type->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <label ></label>
                                                    <input type="submit" class="form-control showhidetext btn btn-primary" value="Wyświetl" style="
						border-radius: 0px;">
                                                </form>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



                                @if(isset($user_info))
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="panel panel-default"  id="panel1">
                                                <div class="panel-heading">
                                                    <a data-toggle="collapse" data-target="#collapseOne">
                                                        Statystyki
                                                    </a>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div id="start_stop">
                                <div class="col-md-12">
                                  <div class="well">
                                    <div class="panel-body table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Odsłuchanych</th>
                                                <th>Poprawne</th>
                                                <th>Niepoprawne</th>
                                                <th>% Niepoprawnych.</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                            $today = DateTime::createFromFormat('Y-m-d', $month.'-01');
                                            $today = $today->format('Y-m-d H:i:s');
                                            $dateexplode = explode("-", $today);
                                            $daysinmonth = date("t",mktime(0,0,0,$dateexplode[1],1,$dateexplode[0]));
                                            $good_sum = 0;
                                            $bad_sum = 0;
                                            $all_sum  = 0;
                                            $proc_sum  = 0;
                                            $good_sum_week = 0;
                                            $bad_sum_week = 0;
                                            $all_sum_week = 0;
                                            @endphp
                                            @for ($i=0; $i < $daysinmonth; $i++)
                                                @php
                                                  $date = date("Y-m-d",mktime(0,0,0,$dateexplode[1],1+$i,$dateexplode[0]));
                                                  $check = 0;
                                                  $number_of_week = date("N",mktime(0,0,0,$dateexplode[1],1+$i,$dateexplode[0]));

                                                @endphp
                                                  <tr>
                                                      <td>{{$date}}</td>
                                                      @foreach ($user_info as $item)
                                                          @if($item->add_date == $date)
                                                              @php
                                                                  $check++;
                                                                  $all_sum +=$item->good + $item->bad;
                                                                  $good_sum += $item->good;
                                                                  $bad_sum += $item->bad;
                                                                  $good_sum_week += $item->good;
                                                                  $bad_sum_week += $item->bad;
                                                              @endphp
                                                              <td>{{$item->good + $item->bad}}</td>
                                                              <td>{{$item->good}}</td>
                                                              <td>{{$item->bad}}</td>
                                                              <td>{{round(($item->bad*100)/($item->good + $item->bad),2)}}%</td>
                                                          @endif
                                                      @endforeach
                                                      @if($check == 0)
                                                          <td>0</td>
                                                          <td>0</td>
                                                          <td>0</td>
                                                          <td>0%</td>
                                                      @endif
                                                  </tr>
                                                @if($number_of_week == 7)
                                                    <tr style="background: #fffad1; font-weight: bold">
                                                        <td>Podsumowanie Tygodnia</td>
                                                        <td>{{$good_sum_week+$bad_sum_week}}</td>
                                                        <td>{{$good_sum_week}}</td>
                                                        <td>{{$bad_sum_week}}</td>
                                                        <td>{{ ($good_sum_week+$bad_sum_week) === 0 ? 0 : round(($bad_sum_week*100)/($good_sum_week+$bad_sum_week),2) }}%</td>
                                                        @php
                                                            $good_sum_week = 0;
                                                            $bad_sum_week = 0;
                                                            $all_sum_week = 0;
                                                        @endphp
                                                        </tr>
                                                    @endif
                                                  @endfor
                                                  <tr style="background: #2ab27b;font-weight: bold">
                                                      <td>TOTAL</td>
                                                      <td>{{$all_sum}}</td>
                                                      <td>{{$good_sum}}</td>
                                                      <td>{{$bad_sum}}</td>
                                                      @if($all_sum == 0)
                                                          @php $proc_sum = 0 @endphp
                                                      <td>0 %</td>
                                                  @else
                                                      @php $proc_sum = ($bad_sum*100)/$all_sum @endphp
                                                      <td>{{round($proc_sum,2)}}%</td>
                                                  @endif

                                              </tr>
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                                </div>
                                  @endif
                              </div>
                          </div>
                      </div>
                  </div>
              </div>




          @endsection
          @section('script')
              <script>

              </script>
  @endsection
