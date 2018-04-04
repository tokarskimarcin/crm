@extends('layouts.main')
@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="well gray-nav">Tabela postępów Zbiorczy</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Oddział:</label>
                <select class="form-control" id="selected_dep" name="selected_dep">
                    <optgroup label="Oddziały">
                        @foreach($departments as $dep)
                            <option value="{{$dep->id}}" @if(($wiev_type == 'department') && $dep->id == $dep_id) selected @endif>{{$dep->departments->name . ' ' . $dep->department_type->name}}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Dyrektorzy">
                        @foreach($directors as $director)
                            <option
                                    @if($wiev_type == 'director' && ('10' . $director->id == $dep_id)) selected @endif
                            value="10{{ $director->id }}">{{ $director->last_name . ' ' . $director->first_name }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>Trener:</label>
                <select class="form-control" id="coach_dep" name="coach_dep">
                    <option>Wszyscy</option>
                    @foreach($coach as  $item)
                        <option value={{$item->id}}>{{$item->first_name.' '.$item->last_name}}</option>
                     @endforeach
                </select>
            </div>
        </div>

    </div>

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
                                        <th>Aktualne RBH</th>
                                        <th>Cel</th>
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
                                    <th>Aktualne RBH</th>
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
                                    <th>Wynik aktualny</th>
                                    <th>Aktualne RBH</th>
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

@endsection

@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>

        $(document).ready(function(){

            $('#selected_dep').on('change',function () {
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.getcoach_list') }}',
                    data: {
                        "department_info_id": $(this).val(),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#coach_dep option').remove();
                        let option_select = '<option>Wszyscy</option>';
                        for(var i=0;i<response.length;i++){
                            option_select += '<option value='+
                                response[i].id+'>'+response[i].first_name+' '+response[i].last_name+'</option>';
                        }
                        console.log(option_select);
                        $('#coach_dep').append(option_select);
                        //console.log(response);
                    }
                });
            });
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
                        d.report_status     = 0;
                        d.date_start        = $('#date_start_in_progress').val();
                        d.date_stop         = $('#date_stop_in_progress').val();
                        d.department_info   = $('#selected_dep').val();
                        d.coach_id          = $('#coach_dep').val();
                        d.type              = 'manager';
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"rowCallback": function( row, data, index ) {
                    if (data.couching_rbh > 64800) {
                        $(row).hide();
                    }
                    $(row).attr('id', data.id);
                    return row;
                },
                "columns":[
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
                        {"data":function (data, type, dataToSet) {
                                let color = 'green';
                                if(data.avg_consultant < data.average_goal)
                                    color = 'red';
                                if(data.avg_consultant == null)
                                    return 'Brak';
                                 return '<span style="color:' + color + '">' + data.avg_consultant + '</span>';
                            },"name": "avg_consultant","searchable": false
                        },
                        {"data":function (data, type, dataToSet) {
                                return Math.round(data.couching_rbh/3600,2);
                            },"name": "couching_rbh","searchable": false
                        },
                        {"data": "average_goal"},
                        {"data":function (data, type, dataToSet) {
                                return "<button class='button-edit-coaching btn btn-warning' style='margin: 3px;' data-id="+data.id+">Edycja</button>" +
                                    "<button class='button-delete-coaching btn btn-danger' data-id="+data.id+">Usuń</button>";
                            },"orderable": false, "searchable": false
                        },
                    ],
            });

            $('#date_start_in_progress, #date_stop_in_progress,#selected_dep,#coach_dep').on('change',function (e) {
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
                        d.report_status = 0;
                        d.date_start = $('#date_start_unsettled').val();
                        d.date_stop =  $('#date_stop_unsettled').val();
                        d.department_info   = $('#selected_dep').val();
                        d.coach_id          = $('#coach_dep').val();
                        d.type              = 'manager';
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

                    {"data":function (data, type, dataToSet) {
                            let color = 'green';
                            if(data.avg_consultant < data.average_goal)
                                color = 'red';
                            return '<span style="color:' + color + '">' + data.avg_consultant + '</span>';
                        },"name": "avg_consultant","searchable": false
                    },
                    {"data":function (data, type, dataToSet) {
                            return Math.round(data.couching_rbh/3600,2);
                        },"name": "couching_rbh","searchable": false
                    },
                    {"data": "average_goal"},
                    {"data":function (data, type, dataToSet) {
                            let comment = 'Brak';
                            if(data.comment != null){
                                comment = data.comment;
                            }
                            return "<textarea class='form-control comment_class' id=text_"+data.id+">"+comment+"</textarea>";
                        },"name": "comment"
                    },
                    {"data":function (data, type, dataToSet) {
                            return "<button class='btn-accept_coaching btn btn-success' data-id="+data.id+" >Akceptuj</button>";
                        },"orderable": false, "searchable": false
                    },
                ],

            });

            $('#date_start_unsettled, #date_stop_unsettled,#selected_dep,#coach_dep').on('change',function (e) {
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
                        d.report_status = 1;
                        d.date_start = $('#date_start_settled').val();
                        d.date_stop =  $('#date_stop_settled').val();
                        d.department_info   = $('#selected_dep').val();
                        d.coach_id          = $('#coach_dep').val();
                        d.type              = 'manager';
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
                    {"data":function (data, type, dataToSet) {
                            let color = 'green';
                            if(data.avg_consultant < data.average_goal)
                                color = 'red';
                            return '<span style="color:' + color + '">' + data.avg_consultant + '</span>';
                        },"name": "avg_consultant","searchable": false
                    },
                    {"data":function (data, type, dataToSet) {
                            return Math.round(data.couching_rbh/3600,2);
                        },"name": "couching_rbh","searchable": false
                    },
                    {"data": "average_goal"},
                    {"data":"comment"},
                ]
            });
            $('#date_start_settled, #date_stop_settled,#selected_dep,#coach_dep').on('change',function (e) {
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
