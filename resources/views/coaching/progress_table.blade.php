@extends('layouts.main')
@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="well gray-nav">Tabela postępów</div>
            </div>
        </div>
    </div>

    <button data-toggle="modal" class="btn btn-default training_to_modal" id="new_coaching_modal" data-target="#Modal_Coaching" data-id="1" title="Nowy Coaching" style="margin-bottom: 14px">
        <span class="glyphicon glyphicon-plus"></span> <span>Nowy Coaching</span>
    </button>

    {{--Tabela z coaching w toku--}}
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                W toku
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start_in_progress" name="date_start_in_progress" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres do:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_stop_in_progress" name="date_stop_in_progress" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                                <table id="table_in_progress" class="table table-striped thead-inverse">
                                    <thead>
                                    <tr>
                                        <th>Trener</th>
                                        <th>Konsultant</th>
                                        <th>Data</th>
                                        <th>Temat</th>
                                        <th>Wynik</th>
                                        <th>Cel</th>
                                        <th>Akcja</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{--Tabela z coaching w Nierozliczone--}}
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                Nierozliczone
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start_unsettled" name="date_start_unsettled" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres do:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_stop_unsettled" name="date_stop_unsettled" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="table_unsettled" class="table table-striped thead-inverse">
                                <thead>
                                <tr>
                                    <th>Trener</th>
                                    <th>Konsultant</th>
                                    <th>Data</th>
                                    <th>Temat</th>
                                    <th>Wynik</th>
                                    <th>Cel</th>
                                    <th>Komentarz</th>
                                    <th>Akcja</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{--Tabela z coaching w Rozliczone--}}
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                Rozliczone
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start_settled" name="date_start_settled" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Zakres do:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_stop_settled" name="date_stop_settled" type="text" value="{{date('Y-m-d')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="table_settled" class="table table-striped thead-inverse">
                                <thead>
                                <tr>
                                    <th>Trener</th>
                                    <th>Konsultant</th>
                                    <th>Data</th>
                                    <th>Temat</th>
                                    <th>Wynik</th>
                                    <th>Cel</th>
                                    <th>Komentarz</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="Modal_Coaching" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal_title">Ustal Coaching<span id="modal_category"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="header_modal">

                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel">Konsultant</label>
                                        <select class="form-control" id="couaching_user_id">
                                            <option>Wybierz</option>
                                            @foreach($consultant as $list)
                                                <option value={{$list->id}}>{{$list->first_name.' '.$list->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Temat</label>
                                        <input type="text" class="form-control" id="coaching_subject" placeholder="Podaj temat"/>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Data Coaching'u:</label>
                                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                            <input class="form-control" id="date_start_new_coaching" name="date_start_new_coaching" value='{{date('Y-m-d')}}' type="text"  >
                                            <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Aktualna średnia</label>
                                        <input type="number" lang="en" class="form-control" id="coaching_actual_avg" placeholder="Wprawoadź aktualną średnią"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Cel od</label>
                                        <input type="number" class="form-control" id="coaching_goal_min" placeholder="Wprawoadź minimalny cel"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Cel do</label>
                                        <input type="number" class="form-control" id="coaching_goal_max" placeholder="Wprawoadź maksymalny cel"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel">Komentarz</label>
                                        <textarea  id="comment_coaching" class="form-control" placeholder="Opcjonalny komentarz"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-success form-control" onclick = "save_coaching(this)" >Dodaj Coaching</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>

        /**
         * Zapisywanie nowego coaching'u
         * @param e
         */
        function save_coaching(e) {
            let consultant_id = $('#couaching_user_id').val();
            let subject = $('#coaching_subject').val();
            let coaching_date = $('#date_start_new_coaching').val();
            let coaching_goal_min = $("#coaching_goal_min").val();
            let coaching_goal_max = $("#coaching_goal_max").val();
            let coaching_comment = $("#comment_coaching").val();
            let coaching_actual_avg = $("#coaching_actual_avg").val();
            let validation = true;
            if(consultant_id == 'Wybierz'){
                validation = false;
                swal('Wybierz konsultanta')
            }else if(subject.trim('').length == 0){
                validation = false;
                swal('Dodaj temat')
            }else if(new Date(coaching_date).getTime() < new Date('{{date('Y-m-d')}}').getTime()){
                validation = false;
                swal('Błędna data')
            }else if(coaching_actual_avg.trim('').length == 0 || isNaN(coaching_actual_avg) ){
                validation = false;
                swal('Błędna aktualna średnia')
            }else if(coaching_goal_min.trim('').length == 0 || isNaN(coaching_goal_min) ){
                validation = false;
                swal('Błędna minimalna średnia')
            }else if(coaching_goal_min.trim('').length == 0 || isNaN(coaching_goal_min) ){
                validation = false;
                swal('Błędna maksymalna średnia')
            }

            if(validation){
                $.ajax({
                    type: "POST",
                    url: "{{route('api.saveCoaching')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'consultant_id'         : consultant_id,
                        'subject'               : subject,
                        'coaching_date'         : coaching_date,
                        'coaching_goal_min'     : coaching_goal_min,
                        'coaching_goal_max'     : coaching_goal_max,
                        'coaching_comment'      : coaching_comment,
                        'coaching_actual_avg'   : coaching_actual_avg
                    },
                    success: function (response) {
                        console.log(response);
                    }
                })
            }
        }
        $(document).ready(function(){

            var in_progress_table = $('#table_in_progress').DataTable({
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"ajax": {
                    'url': "{{ route('api.datatableCoachingTable') }}",
                    'type': 'POST',
                    'data': function (d) {
                        d.report_status = 0;
                        d.date_start    = $('#date_start_in_progress').val();
                        d.date_stop     = $('#date_stop_in_progress').val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"columns":[
                        {"data":function (data, type, dataToSet) {
                                return data.manager_first_name + " " + data.manager_last_name;
                            },"name": "manager.last_name"
                        },
                        {"data":function (data, type, dataToSet) {
                                return data.consultant_first_name + " " + data.consultant_last_name;
                            },"name": "consultant.last_name"
                        },
                        {"data":"coaching_date"},
                        {"data": "subject"},
                        {"data": "subject"},
                        {"data": "subject"}
                    ]
            });

            $('#date_start_in_progress, #date_stop_in_progress').on('change',function (e) {
                in_progress_table.ajax.reload();
            });

            var table_unsettled = $('#table_unsettled').DataTable({
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"ajax": {
                    'url': "{{ route('api.datatableCoachingTable') }}",
                    'type': 'POST',
                    'data': function (d) {
                        d.report_status = 1;
                        d.date_start = $('#date_start_unsettled').val();
                        d.date_stop =  $('#date_stop_unsettled').val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"columns":[
                    {"data":function (data, type, dataToSet) {
                            return data.manager_first_name + " " + data.manager_last_name;
                        },"name": "manager.last_name"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.consultant_first_name + " " + data.consultant_last_name;
                        },"name": "consultant.last_name"
                    },
                    {"data":"coaching_date"},
                    {"data": "subject"},
                    {"data": "subject"},
                    {"data": "subject"}
                ]
            });

            $('#date_start_unsettled, #date_stop_unsettled').on('change',function (e) {
                table_unsettled.ajax.reload();
            });

            var table_settled = $('#table_settled').DataTable({
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"ajax": {
                    'url': "{{ route('api.datatableCoachingTable') }}",
                    'type': 'POST',
                    'data': function (d) {
                        d.report_status = 2;
                        d.date_start = $('#date_start_settled').val();
                        d.date_stop =  $('#date_stop_settled').val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"columns":[
                    {"data":function (data, type, dataToSet) {
                            return data.manager_first_name + " " + data.manager_last_name;
                        },"name": "manager.last_name"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.consultant_first_name + " " + data.consultant_last_name;
                        },"name": "consultant.last_name"
                    },
                    {"data":"coaching_date"},
                    {"data": "subject"},
                    {"data": "subject"},
                    {"data": "subject"}
                ]
            });

            $('#date_start_settled, #date_stop_settled').on('change',function (e) {
                table_settled.ajax.reload();
            });

            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false,
            });
        })
    </script>
@endsection
