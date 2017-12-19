@extends('layouts.main')
@section('style')
    <style>
        .table td {
                 text-align: center;
             }
        table {
            table-layout: fixed;
            word-wrap: break-word;
        }
        .action{
            width: 69px;
        }

    </style>
    @endsection
@section('content')


    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Podgląd godzin</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
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
                                                <td>{{round(($item->bad*100)/($item->good + $item->bad), 2)}}%</td>
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
                                            <td>{{round($proc_sum, 2)}}%</td>
                                        @endif

                                    </tr>
                                    {{--<tr>--}}
                                        {{--<td colspan="3"></td>--}}
                                        {{--<td>Kara:</td>--}}
                                        {{--@foreach($janky_system as $janky)--}}
                                            {{--@if(($proc_sum >= $janky->min_proc) && $proc_sum< $janky->max_proc )--}}
                                                {{--<td>{{$janky->cost * $bad_sum}} PLN</td>--}}
                                            {{--@endif--}}
                                        {{--@endforeach--}}
                                    {{--</tr>--}}
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
