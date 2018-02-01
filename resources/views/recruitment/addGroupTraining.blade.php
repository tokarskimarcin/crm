@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        color: #aaa;
        font-size: 20px;
    }
    .left-container{
        height: 300px;
        overflow-y: auto;
        border: 1px solid #e5e5e5;
    }
    .right-container{
        height: 300px;
        overflow-y: auto;
        border: 1px solid #e5e5e5;
    }
    .list-group{
        padding-left: 0;
        margin-bottom: 20px;
    }
    .list-group-item{
        position: relative;
        display: block;
        padding: 10px 15px;
        margin-bottom: -1px;
        background-color: #fff;
        border: 1px solid #ddd;
    }
    a.list-group-item:hover, a.list-group-item:focus {
        color: #555;
        text-decoration: none;
        background-color: #f5f5f5;
    }
    .list-group-item:first-child {
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
    .pull-right{
        float: right!important;
    }
    .pull-left{
        float: right!important;
    }
    .search_candidate{
        margin-bottom: 15px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Dział Szkoleń</div>
        </div>
    </div>
</div>


<button data-toggle="modal" class="btn btn-default training_to_modal" data-target="#myModalgroup" data-category_id="{{1}}" title="Dodaj szkolenie" style="margin-bottom: 14px">
    <span class="glyphicon glyphicon-plus"></span> <span>Dodaj szkolenie</span>
</button>

<div id="myModalgroup" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ustalanie szkolenia<span id="modal_category"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="myLabel">Data:</label>
                                <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control" name="start_date_training" type="text" value="{{date("Y-m-d")}}" readonly />
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="myLabel">Godzina:</label>
                                <div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                                    <input id="start_time_training" class="form-control" size="16" type="text" value="" readonly/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="myLabel">Prowadzący:</label>
                                <select class="form-control" id="id_user">
                                    @foreach($cadre as $item)
                                        <option id={{$item->id}} value={{$item->id}} >{{$item->last_name.' '.$item->first_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="myLabel">Komentarz:</label>
                                <textarea id="training_comment"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <label class="myLabel">Dostępni kandydaci:</label>
                            <div class="search_candidate">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="left_search" placeholder="Wyszukaj kandydata"/>
                                    <div class="input-group-addon">
                                        <input type="checkbox" class="all-put-right" style="display: block">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="left-container">
                                    <div class="list_group" id="list_candidate">

                                        <a class="list-group-item">
                                            Jan Kowalski
                                            <input type="checkbox" class="pull-right" style="display: block">
                                        </a>


                                        <a class="list-group-item checked">
                                            Jan Kowalski
                                            <input type="checkbox" class="pull-right" style="display: block">
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button id="move_right" class="btn btn-default center-block add">
                                <i class="glyphicon glyphicon-chevron-right"></i>
                            </button>
                            <button id="move_left" class="btn btn-default center-block remove">
                                <i class="glyphicon glyphicon-chevron-left"></i>
                            </button>
                        </div>
                        <div class="col-md-5">
                            <label class="myLabel">Osoby na szkolenie:</label>
                            <div class="search_candidate">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="right_search" placeholder="Wyszukaj osobe na szkoleniu"/>
                                    <div class="input-group-addon">
                                        <input type="checkbox" class="all-put-right" style="display: block">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="right-container">
                                    <div class="list_group" id="list_candidate_choice">

                                        <a class="list-group-item checked">
                                            Jan Kowalski
                                            <input type="checkbox" class="pull-left" style="display: block">
                                        </a>
                                        <a class="list-group-item">
                                            Jan Kowalski
                                            <input type="checkbox" class="pull-left" style="display: block">
                                        </a>
                                        <a class="list-group-item">
                                            Jan Kowalski
                                            <input type="checkbox" class="pull-left" style="display: block">
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="save_button">Dodaj szkolenie</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Lista szkoleń
            </div>
            <div class="panel-body">
                <div class="row">
                    <ul class="nav nav-tabs" style="margin-bottom: 25px">
                        <li class="active"><a data-toggle="tab" href="#home">Dostępne</a></li>
                        <li><a data-toggle="tab" href="#menu1">Zakończone</a></li>
                        <li><a data-toggle="tab" href="#menu2">Anulowane</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <row>
                                        <table id="activ_training_group" class="table thead-inverse table-striped table-bordered" cellspacing="0" width="100%" >
                                            <thead>
                                            <tr>
                                                <td>Data</td>
                                                <td>Godzina</td>
                                                <td>Liczba osób</td>
                                                <td>Akcja</td>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </row>
                                </div>
                            </div>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <div class="panel panel-default">
                                    <div class="panel-body">
                                        <row>
                                            <table id="end_training_group" class="table thead-inverse table-striped table-bordered" cellspacing="0" width="100%" >
                                                <thead>
                                                <tr>
                                                    <td>Data</td>
                                                    <td>Godzina</td>
                                                    <td>Liczba osób</td>
                                                    <td>Akcja</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </row>
                                    </div>
                                </div>
                            </div>
                        <div id="menu2" class="tab-pane fade">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <row>
                                        <table id="cancel_training_group" class="table thead-inverse table-striped table-bordered" cellspacing="0" width="100%" >
                                            <thead>
                                            <tr>
                                                <td>Data</td>
                                                <td>Godzina</td>
                                                <td>Liczba osób</td>
                                                <td>Akcja</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </row>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
@section('script')
<script>
    $(document).ready(function() {
        var id_training_group = 0;
        var training_group_response;
        var is_open = 0;
        var action_row =
            '<a class="btn btn-default info_active" href="#">'+
            '<span style="color: green" class="glyphicon glyphicon glyphicon-info-sign"></span> Szczegóły'+
            '</a>'+
            '<a class="btn btn-default end_active" href="#">'+
            '<span style="color: green" class="glyphicon glyphicon glyphicon-pencil"></span> Zakończ'+
            '</a>'+
            '<a class="btn btn-default cancle_active" data-id ={{1}} href="#">'+
            '<span style="color: green" class="glyphicon glyphicon glyphicon-trash"></span> Anuluj'+
            '</a>';

        var action_row_end_cancel =
            '<a class="btn btn-default info_active" href="#">'+
            '<span style="color: green" class="glyphicon glyphicon glyphicon-info-sign"></span> Szczegóły'+
            '</a>';

        $('.form_date').datetimepicker({
            language: 'pl',
            autoclose: 1,
            minView: 2,
            pickTime: false,
        });
        $('.form_time').datetimepicker({
            language:  'pl',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 1,
            minView: 0,
            maxView: 1,
            forceParse: 0
        });
        // open modal
        $('#myModalgroup').on('show.bs.modal', function() {
            if(is_open == 0)
            {
                clearLeftColumn();
                getGroupTrainingInfo();
                is_open = 1;
            }
        });

        function clearLeftColumn()
        {
            $(".list_group a").remove();
        }
        // usuniecie podstawowych infromacji o szkoleniu
        function clearModalBasicInfo () {
            $("input[name='start_date_training']").val("");
            $("input[id='start_time_training']").val("");
            $("#id_user").prop("selectedIndex", 0);
            $("#training_comment").val("");
        }
        // pobranie danych o szkoleniu
        function getGroupTrainingInfo() {
            // gdy tworzone jest nowe szkolenie
            if(id_training_group == 0){
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.getCandidateForGrpupTrainingInfo') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "id_training_group": id_training_group
                    },
                    success: function (response) {
                        console.log(response);
                        if (response.length != 0) {


                        } else {

                        }
                    }
                });
            }// istniejące
            else if(id_training_group != 0 ) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.getGrpupTrainingInfo') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "id_training_group": id_training_group
                    },
                    success: function (response) {
                        console.log(response);
                        training_group_response = response;
                        if (response.length != 0) {
                            for (var i = 0; i < response['group_training'].length; i++) {
                                $("input[name='start_date_training']").val(response['group_training'][i].training_date);
                                $("input[id='start_time_training']").val(response['group_training'][i].training_hour.slice(0, -3));
                                $("#id_user").val("2826");
                                $("#training_comment").val(response['group_training'][i].comment);
                            }
                            for (var i = 0; i < response['candidate'].length; i++) {
                                var html = '<a class="list-group-item" id=' + response['candidate'][i].id + '>' +
                                    response['candidate'][i].first_name + ' ' + response['candidate'][i].last_name +
                                    '<input type="checkbox" class="pull-right" style="display: block">' +
                                    '</a>';
                                if (response['candidate'][i].attempt_status_id == 5) {
                                    $('#list_candidate').append(html);
                                } else if (response['candidate'][i].attempt_status_id == 6) {
                                    $('#list_candidate_choice').append(html);
                                }
//                            $("input[name='start_date_training']").val(response['group_training'][i].training_date);
//                            $("input[id='start_time_training']").val(response['group_training'][i].training_hour.slice(0,-3));
//                            $("#id_user").val("2826");
//                            $("#training_comment").val(response['group_training'][i].comment);
                            }
                        } else {

                        }
                    }
                });
            }
        }
        //cancel modal
        $('#myModalgroup').on('hidden.bs.modal',function () {
                id_training_group = 0;
                clearModalBasicInfo();
                clearLeftColumn();
                is_open = 0;
        });
        //tabela dostępnych szkoleń
        var table_activ_training_group = $('#activ_training_group').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },"ajax": {
                'url': "{{ route('api.datatableTrainingGroupList') }}",
                'type': 'POST',
                'data': function (d) {
                    d.list_type = 1;
                },
                'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },
            "columns": [
                {"data": "training_date"},
                {"data": "training_hour"},
                {"data": "candidate_count"},
                {
                    "data": function (data, type, dataToSet) {
                        return action_row;
                    }
                }
            ],"fnDrawCallback": function(settings){ // działanie po wyrenderowaniu widoku
                // po kliknięcu w szczegóły otwórz modal z możliwością edycji
                $('.info_active').on('click',function (e) {
                    //główny wiersz
                    var tr = $(this).closest('tr');
                    id_training_group = tr.attr('id');
                    $('#myModalgroup').modal("show");
                });
            },"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                // Dodanie id do wiersza
                $(nRow).attr('id', aData.id);
                return nRow;
            }
        });
        // tabela zakończonych szkoleń
        var table_end_training_group = $('#end_training_group').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },"ajax": {
                'url': "{{ route('api.datatableTrainingGroupList') }}",
                'type': 'POST',
                'data': function (d) {
                    d.list_type = 2;
                },
                'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },"columns": [
                {"data": "training_date"},
                {"data": "training_hour"},
                {"data": "candidate_count"},
                {
                    "data": function (data, type, dataToSet) {
                        return action_row_end_cancel;
                    }
                }
            ]
        });
        // tabela skaswoanych szkoleń
        var table_cancel_training_group = $('#cancel_training_group').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },"ajax": {
                'url': "{{ route('api.datatableTrainingGroupList') }}",
                'type': 'POST',
                'data': function (d) {
                    d.list_type = 0;
                },
                'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },"columns": [
                {"data": "training_date"},
                {"data": "training_hour"},
                {"data": "candidate_count"},
                {
                    "data": function (data, type, dataToSet) {
                        return action_row_end_cancel;
                    }
                }
            ]
        });


    });
</script>
@endsection
