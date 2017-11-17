@extends('layouts.main')
@section('content')


    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Janki Weryfikacja</h1>
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
                                <div class="col-md-3">
                                    <div class="well">
                                            <div class="form-group">
                                                <form action="" method="post" action="departmentStatistics">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <label for ="dep">Miesiac:</label>
                                                <select class="form-control" name="month">
                                                    @for ($i=0; $i < 2; $i++)
                                                        @php
                                                            $date = date("Y-m",mktime(0,0,0,date("m")-$i,1,date("Y")));
                                                        @endphp
//                                                        @if ($_POST['month'] == $date) {
//                                                            <option selected>{{$date}}</option>
//                                                        @else{
                                                            <option>{{$date}}</option>
                                                          @endif
                                                    @endfor
                                                </select>
                                                    <input type="submit" class="form-control showhidetext btn btn-primary" value="Wyświetl" style="
						border-radius: 0px;">
                                                </form>
                                            </div>
                                    </div>
                                </div>

                                @if(isset($user_info))
                                <div class="col-md-9">
                                    <div class="panel-body">
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
                                            <?php
                                            $today = date("Y-m-d H:i:s");
                                            $dateexplode = explode("-", $today);
                                            $daysinmonth = date("t",mktime(0,0,0,$dateexplode[1],1,$dateexplode[0]));
                                            $good_sum = 0;
                                            $bad_sum = 0;
                                            $all_sum  = 0;
                                            $proc_sum  = 0;
                                            ?>
                                            @for ($i=0; $i < $daysinmonth; $i++)
                                                <?php
                                                $date = date("Y-m-d",mktime(0,0,0,$dateexplode[1],1+$i,$dateexplode[0]));
                                                $check = 0;
                                                ?>
                                                <tr>
                                                    <td>{{$date}}</td>
                                                    @foreach ($user_info as $item)
                                                        @if($item->add_date == $date)
                                                            <?php $check++;
                                                            $all_sum +=$item->good + $item->bad;
                                                            $good_sum += $item->good;
                                                            $bad_sum += $item->bad;?>
                                                            <td>{{$item->good + $item->bad}}</td>
                                                            <td>{{$item->good}}</td>
                                                            <td>{{$item->bad}}</td>
                                                            <td>{{($item->bad*100)/($item->good + $item->bad)}}%</td>
                                                        @endif
                                                    @endforeach
                                                    @if($check == 0)
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0%</td>
                                                    @endif
                                                </tr>
                                            @endfor
                                            <tr>
                                                <td>TOTAL</td>
                                                <td>{{$all_sum}}</td>
                                                <td>{{$good_sum}}</td>
                                                <td>{{$bad_sum}}</td>
                                                @if($all_sum == 0)
                                                    <?php $proc_sum = 0 ?>
                                                    <td>0 %</td>
                                                @else
                                                    <?php $proc_sum = ($bad_sum*100)/$all_sum ?>
                                                    <td>{{$proc_sum}}%</td>
                                                @endif

                                            </tr>
                                            </tbody>
                                        </table>
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
