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


        @if (session()->has('status'))
            @if(Session::get('status') == 1)
                <div id="success_div" class='alert alert-success'>{{Session::get('message')}}</div>
            @elseif(Session::get('status') == 0)
                <div id="success_div" class='alert alert-danger'>{{Session::get('message')}}</div>
            @endif
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
                                            <form method="post" action="hour_report" id="form_add">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-md-3">
                                                    <label>Godzina:</label>
                                                    <select id="hour" name="hour" class="form-control" style="font-size:18px;">
                                                        <option>Wybierz</option>
                                                        @for ($i=9; $i < 22; $i++)
                                                            @php $godz = $i.':00';  @endphp
                                                            @if ($godz == '9:00')
                                                                @php $godz = '09:00';  @endphp
                                                            @endif
                                                            @if($reports->where('hour',$godz.':00')->isEmpty())
                                                                <option value={{$godz.':00'}}>{{$godz}}</option>
                                                            @endif
                                                        @endfor
                                                    </select>
                                                </div>


                                                <div class="col-md-3">
                                                    <label>Średnia:</label>
                                                    <input class="form-control numeric" name="average" type="text" required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Liczba Zaproszeń:</label>
                                                    <input class="form-control" name="success" type="text" value="" required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Liczba Osób:</label>
                                                    <input class="form-control" name="employee_count" type="text" value="" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>% Janków:</label>
                                                    <input class="form-control" name="janky_count" type="text" value="" required>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>% Wykorzystania Bazy</label>
                                                    <input class="form-control" name="wear_base" type="text" value="" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Czas Rozmów:</label>
                                                    <input class="form-control" name="call_Time" type="text" value="" required></br>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <input type="submit" class="btn btn-primary add_report " name="hour_report_send" id="send_button"  value="Wyślij raport"/>
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
                    <div class="row table-responsive">
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
                                @php $is_set = 0;  @endphp
                                <tr>
                                @php $godz = $i.':00';  @endphp
                                @if ($godz == '9:00')
                                    @php $godz = '09:00';  @endphp
                                @endif
                                    <td>{{$godz}}</td>
                                    @foreach($reports as $report)
                                        @if($report->hour ==  $godz.':00')
                                            @php
                                                $is_set = 1;
                                                $old_aim = 0;
                                            @endphp
                                            <td class="average">{{$report->average}}</td>
                                            <td class="success_count">{{$report->success}}</td>
                                            <td class="employee_count">{{$report->employee_count}}</td>
                                            <td class="janky_count">{{$report->janky_count}} %</td>
                                            <td class="wear_base">{{$report->wear_base}} %</td>
                                            <td class="call_time">{{$report->call_time}} %</td>
                                            @if(date('N', strtotime(date('Y-m-d'))) >= 6)
                                                @if($report->department_info->dep_aim_week == 0)
                                                    <td>Brak Danych o oddziale</td>
                                                @else
                                                    <td>{{round(($report->success*100)/$report->department_info->dep_aim_week,2)}} %</td>
                                                @endif
                                            @else
                                                @if($report->department_info->dep_aim == 0)
                                                    <td>Brak Danych o oddziale</td>
                                                @else
                                                    <td>{{round(($report->success*100)/$report->department_info->dep_aim,2)}} %</td>
                                                @endif
                                            @endif
                                            <td id="status_{{$i}}">{{ $report->is_send == 1 ? "Wysłany" : "Oczekuje na wysłanie" }}</td>
                                            @if($report->is_send == 1)
                                            <td>
                                                <button name={{$i}} class="btn btn-primary disabled">Edycja</button>
                                            </td>
                                            @else
                                                <td>
                                                    <button name={{$i}} class="btn btn-primary active edit" type="button" data-toggle="modal"  data-id={{$report->id}}>Edycja</button>
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



<!-- Modal -->
<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edycja Raportu Godzinnego</h4>
            </div>
            <form method="post" action="hour_report_edit" id="form_edit">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="record_id" name="record_id">
            <div class="modal-body">
                <div>
                    <label>Średnia:</label>
                    <input class="form-control numeric" id="average"  name="average" type="text">
                </div>

                <div>
                    <label>Liczba Zaproszeń:</label>
                    <input class="form-control" id="success" type="number" name="success" value="">
                </div>

                <div>
                     <label>Liczba Osób:</label>
                    <input class="form-control" id="employee_count" name="employee_count" type="number" value="">
                </div>
                <div>
                    <label>% Janków:</label>
                    <input class="form-control" id="janky_count" name="janky_count" type="text" value="">
                </div>

                <div>
                    <label>% Wykorzystania Bazy</label>
                    <input class="form-control" id="wear_base" name="wear_base" type="text" value="">
                </div>
                <div>
                    <label>Czas Rozmów:</label>
                    <input class="form-control" id="call_time" name="call_Time" type="text" value=""></br>
                </div>
                <button id="edit_hour" class="btn btn-primary" style="font-size:18px; width:100%;">Edytuj</button>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close" data-dismiss="modal">Anuluj</button>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        $(".edit").click(function(){
            var record_id = $(this).data('id');
            var row = $(this).closest("tr");
            var success = row.find(".success_count").text();
            var average = row.find(".average").text();
            var employee_count = row.find(".employee_count").text();
            var janky_count = row.find(".janky_count").text().slice(0,-1);
            var wear_base = row.find(".wear_base").text().slice(0,-1);
            var call_time = row.find(".call_time").text().slice(0,-1);
            $("input[name=record_id]:hidden").val(record_id);
            $(".modal-body #average").val(average);
            $(".modal-body #success").val(success);
            $(".modal-body #employee_count").val(employee_count);
            $(".modal-body #janky_count").val(parseInt(janky_count));
            $(".modal-body #wear_base").val(parseInt(wear_base));
            $(".modal-body #call_time").val(parseInt(call_time));
            $('#edit').modal('show');
        });
        $("#edit_hour").click(function () {
            var check = true;
            if($(".modal-body #average").val() == '' || isNaN($(".modal-body #average").val()))
            {
                swal('Podaj prawidłową średnią!')
                check = false;
            }
            else if($(".modal-body #success").val() == '' || isNaN($(".modal-body #success").val()))
            {
                swal('Podaj prawidłową ilość zgód!')
                check = false;
            }
            else if($(".modal-body #employee_count").val() == '' || isNaN($(".modal-body #employee_count").val()))
            {
                swal('Podaj prawidłową ilość pracowników!')
                check = false;
            }
            else if($(".modal-body #janky_count").val() == '' || isNaN($(".modal-body #janky_count").val()))
            {
                swal('Podaj prawidłową ilość janków!')
                check = false;
            }
            else if($(".modal-body #wear_base").val() == '' || isNaN($(".modal-body #wear_base").val()))
            {
                swal('Podaj prawidłową ilość wykorzystania bazy!')
                check = false;
            }
            else if($(".modal-body #call_time").val() == '' || isNaN($(".modal-body #call_time").val()))
            {
                swal('Podaj prawidłowy czas!')
                check = false;
            }
            if(check)
            {
                $('#form_edit').submit(function(){
                    $(this).find(':submit').attr('disabled','disabled');
                });
            }else{
                return false;
            }
        });

        $(".add_report").click(function(){
            var success = $("input[name='success']" ).val();
            var average = $("input[name='average']" ).val();
            var employee_count = $("input[name='employee_count']" ).val();
            var janky_count = $("input[name='janky_count']" ).val();
            var wear_base = $("input[name='wear_base']" ).val();
            var call_time = $("input[name='call_Time']" ).val();
            var hour = $( "#hour" ).val();
            var check = true;
            if(hour == '' || hour=='Wybierz')
            {
                swal('Wybierz godzinę')
                check = false;
            }
            else if(average == '' || isNaN(average))
            {
                swal('Podaj prawidłową średnią!')
                check = false;
            }
            else if(success == '' || isNaN(success))
            {
                swal('Podaj ilość zgód!')
                check = false;
            }
            else if(employee_count == '' || isNaN(employee_count))
            {
                swal('Podaj liczbę pracowników!')
                check = false;
            }
            else if(janky_count == '' || isNaN(janky_count))
            {
                swal('Podaj ilość janków!')
                check = false;
            }
            else if(wear_base == '' || isNaN(wear_base))
            {
                swal('Podaj wartość wykorzystania bazy!')
                check = false;
            }
            else if(call_time == '' || isNaN(call_time))
            {
                swal('Podaj czas rozmów!')
                check = false;
            }
            if(check)
            {
                $('#form_add').submit(function(){
                    $(this).find(':submit').attr('disabled','disabled');
                });
            }else{
                return false;
            }
        });
    });

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
                var text = document.getElementById('status_'+h)
                    if(text != null)
                        text.innerHTML = "Wysłany";
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
