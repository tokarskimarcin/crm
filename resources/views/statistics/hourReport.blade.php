@extends('layouts.main')
@section('content')
<style>
    #send_button{
        width: 100%;
        height: 33px;
        margin-top: 25px;
        text-align: center;
    }
</style>

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Raport Godzinny</h1>
        </div>
    </div>


        @if (session()->has('add_hour_report'))
        <div id="success_div" class='alert alert-success'>Raport został wysłany.</div>
    @endif


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Uzupełnij raport
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="well">
                                <div class="panel-body">
                                            <form method="post" action="hour_report">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-md-3">
                                                    <label>Godzina:</label>
                                                    <select name="hour" class="form-control" style="font-size:18px;">
                                                        <option>Wybierz</option>
                                                        @for ($i=9; $i < 22; $i++)
                                                            @php($godz = $i.':00')
                                                            @if ($godz == '9:00')
                                                                @php($godz = '09:00')
                                                            @endif
                                                            @if($reports->where('hour',$godz.':00')->isEmpty())
                                                                <option value={{$godz.':00'}}>{{$godz}}</option>
                                                            @endif
                                                        @endfor
                                                    </select>
                                                </div>


                                                <div class="col-md-3">
                                                    <label>Średnia:</label>
                                                    <input class="form-control numeric" name="average" type="text">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Liczba Zaproszeń:</label>
                                                    <input class="form-control" name="success" type="number" value="">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Liczba Osób:</label>
                                                    <input class="form-control" name="employee_count" type="text" value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>% Janków:</label>
                                                    <input class="form-control" name="janky_count" type="text" value="">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>% Wykorzystania Bazy</label>
                                                    <input class="form-control" name="wear_base" type="text" value="">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Czas Rozmów:</label>
                                                    <input class="form-control" name="call_Time" type="text" value=""></br>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-primary" name="hour_report_send" id="send_button">Wyślij</button>
                                                </div>
                                            </form>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Raport z Dnia
                </div>
                <div class="panel-body">
                    <div class="row">
                        <table class="table table-bordered">
                            <thead>
                            <tr align="center">
                                <th align="center">Godzina</th>
                                <th align="center">Średnia</th>
                                <th align="center">Liczba Zaproszeń</th>
                                <th align="center">Liczba Zalogowanych</th>
                                <th align="center">% Janków</th>
                                <th align="center">% Wykorzystania Bazy</th>
                                <th align="center">Czas Rozmów</th>
                                <th align="center">% Celu</th>
                                <th align="center">Status</th>
                                <th align="center">Akcja</th>
                            </tr>
                            </thead>
                            @for ($i=9; $i < 22; $i++)
                                @php($is_set = 0)
                                <tr>
                                @php($godz = $i.':00')
                                @if ($godz == '9:00')
                                    @php($godz = '09:00')
                                @endif
                                    <td>{{$godz}}</td>
                                    @foreach($reports as $report)
                                        @if($report->hour ==  $godz.':00')
                                            @php($is_set = 1)
                                            <td>{{$report->average}}</td>
                                            <td>{{$report->success}}</td>
                                            <td>{{$report->employee_count}}</td>
                                            <td>{{$report->janky_count}} %</td>
                                            <td>{{$report->wear_base}} %</td>
                                            <td>{{$report->call_time}} %</td>
                                            @if(date('N', strtotime(date('Y-m-d'))) >= 6)
                                                <td>{{round(($report->success*100)/$report->department_info->dep_aim_week,2)}}</td>
                                            @else
                                                <td>{{round(($report->success*100)/$report->department_info->dep_aim,2)}}</td>
                                            @endif
                                            <td id="status_{{$i}}">{{ $report->is_send == 1 ? "Wysłany" : "Oczekuje na wysłanie" }}</td>
                                            @if($report->is_send == 1)
                                            <td>
                                                <button name={{$i}} class="btn btn-primary disabled">Edycja</button>
                                            </td>
                                            @else
                                                <td>
                                                    <button name={{$i}} class="btn btn-primary active" type="button" data-toggle="modal" data-target="#edit" id={{$report->id}}>Edycja</button>
                                                </td>
                                            @endif
                                        @endif
                                    @endforeach
                                    @if($is_set == 0)
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                                </tr>
                            @endfor
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>

    (function () {
        function checkTime(i) {
            return (i < 10) ? "0" + i : i;
        }

        function startTime() {
            var today = new Date(),
                h = checkTime(today.getHours()),
                m = checkTime(today.getMinutes());
            if(m>=8)
            {
                var button = document.getElementsByName(h).item(0);
                document.getElementById('status_'+h).innerHTML = "Wysłany";
                if(button != null)
                    button.setAttribute('disabled', true);
            }
            t = setTimeout(function () {
                startTime()
            }, 5000);
        }
        startTime();
    })();

</script>
@endsection
