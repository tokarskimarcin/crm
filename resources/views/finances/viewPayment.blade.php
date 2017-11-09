@extends('layouts.main')
@section('content')
<style>
    button{
        width: 100%;
        height: 50px;
    }
</style>

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Podgląd Wypłat</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Podgląd Wypłat
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                        <div class="well">
                                            <form action="view_payment" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-md-8">
                                                    <select name="search_money_month" class="form-control" style="font-size:18px;">
                                                        @for ($i=0; $i < 3; $i++)
                                                            @php
                                                            $date = date("Y-m", mktime(0, 0, 0, date("m")-$i, 1, date("Y")));
                                                            @endphp
                                                            @if (isset($month))
                                                                @if ($month == $date)
                                                                    <option selected>{{$date}}</option>
                                                                @else{
                                                                    <option>{{$date}}</option>
                                                                @endif
                                                            @else
                                                                <option>{{$date}}</option>
                                                            @endif
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <button class="btn btn-primary" id="show_load_data_info" style="width:100%;">Wyświetl</button>
                                                </div></br></br>
                                            </form>
                                        </div>
                                    </div>

                                <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <div style="float:left;">
                                                        <h4 style="margin-top:20px;"><b>Tabela Wypłat - Umowy Szkoleniowe:</b></h4>
                                                        </div><br><br><br>
                                                @if(isset($month))
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>Lp.</th>
                                                            <th>Nazwisko</th>
                                                            <th>Imię</th>
                                                            <th>Stawka</th>
                                                            <th>Średnia</th>
                                                            <th>RBH</th>
                                                            <th>%Janków</th>
                                                            <th>Kara Janki</th>
                                                            <th>Podstawa</th>
                                                            <th>Premia</th>
                                                            <th>Prowizja</th>
                                                            <th>Stu.</th>
                                                            <th>Dok.</th>
                                                            <th>Wynagrodzenie</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($salary as $item => $key)
                                                                @if($item == 2)
                                                                    {{--Typ Umowy--}}
                                                                    @foreach($key as $item2)
                                                                        @php // set variable
                                                                            $avg = 0;
                                                                            $rbh = round($item2->sum/3600,2);
                                                                            $janky_cost_per_price = 0;
                                                                            if($item2->success == 0)
                                                                                $avg = 0;
                                                                             else
                                                                                $avg = round($item2->success/($item2->sum/3600),2);
                                                                            if($item2->ods == 0)
                                                                                $janky_proc = 0;
                                                                            else
                                                                                $janky_proc = round(($item2->janki*100)/$item2->ods ,2);
                                                                            foreach ($janky_system as $system_item)
                                                                            {
                                                                               $system_item->max_proc;
                                                                               if($janky_proc >= $system_item->min_proc && $janky_proc < $system_item->max_proc)
                                                                               {
                                                                                    $janky_cost_per_price = $system_item->cost;
                                                                               }
                                                                            }
                                                                            $janky_cost = $item2->janki * $janky_cost_per_price;
                                                                            $standart_salary = $rbh * $item2->rate;
                                                                            $bonus_penalty = $item2->premia -$item2->kara;
                                                                            $student = ($item2->student == 0) ? "Nie" : "Tak";
                                                                            $documents = ($item2->documents == 0) ? "Nie" : "Tak";
                                                                            //System prowizyjny
                                                                              if ($rbh >= $department_info->commission_hour AND $janky_proc < $department_info->commission_janky) {
                                                                                    $lp = 1;
                                                                                    for ($step = $department_info->commission_step; $step <= 20; $step = ($step+0.5)) {
                                                                                          $avg_min = ($department_info->commission_avg-0.25)+(0.25*$lp);
                                                                                          $avg_max = $department_info->commission_avg+(0.25*$lp);
                                                                                          if ($avg >=$avg_min AND $avg < $avg_max) {
                                                                                              $bonus_per_hour = $step;
                                                                                          }
                                                                                      $lp++;
                                                                                    }
                                                                            }else{
                                                                                     $bonus_per_hour = 0;
                                                                                  }
                                                                            $bonus_salary = $rbh * $bonus_per_hour;
                                                                            $salary_total = $standart_salary+$bonus_salary-$janky_cost;
                                                                        @endphp
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>{{($item2->first_name)}}</td>
                                                                            <td>{{($item2->last_name)}}</td>
                                                                            <td>{{($item2->rate.'('.$bonus_per_hour.')')}}</td>
                                                                            <td>{{($avg)}}</td>
                                                                            <td>{{$rbh}}</td>
                                                                            <td>{{($janky_proc)}}%</td>
                                                                            <td>{{($janky_cost)}} PLN</td>
                                                                            <td>{{($standart_salary)}} PLN</td>
                                                                            <td>{{($bonus_penalty)}} PLN</td>
                                                                            <td>{{($bonus_salary)}} PLN</td>
                                                                            <td>{{($student)}}</td>
                                                                            <td>{{($documents)}}</td>
                                                                            <td>{{($salary_total)}} PLN</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
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
        </div>
    </div>

@include('workhours.registerHour');
@endsection

@section('script')

<script>


</script>
@endsection
