@extends('layouts.main')
@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <style>
        th,td{
            text-align: center;
        }
        .myLabel {
            color: #aaa;
            font-size: 20px;
        }
        .left-container{
            height: 500px;
            overflow-y: auto;
            border: 1px solid #e5e5e5;
        }
        .right-container{
            height: 210px;
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
            color: #555 !important;
            text-decoration: none !important;
            background-color: #f5f5f5 !important;
        }
        a.check:hover, a.check:focus{
            color: #fff !important;
            background-color: #3470ae !important;
            border-color: #2d659c !important;
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
        .check{
            color: #fff !important;
            background-color: #337ab7;
            border-color: #2e6da4;
        }

        #button_move_area, #button_move_area_absent{
            margin-top: 150px;
        }


    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="well gray-nav">Rekrutacja / Dział Szkoleń</div>
            </div>
        </div>
    </div>

    <button data-toggle="modal" class="btn btn-default training_to_modal" id="training_to_modal_stage1" data-target="#myModalgroup" data-id="1" title="Dodaj szkolenie (Etap 1)" style="margin-bottom: 14px">
        <span class="glyphicon glyphicon-plus"></span> <span>Dodaj szkolenie (Etap 1)</span>
    </button>
    <br>
    <button data-toggle="modal" class="btn btn-default training_to_modal_stage2" id="training_to_modal_stage2" data-target="#myModalgroup" data-id="2" title="Dodaj szkolenie (Etap 2)" style="margin-bottom: 14px">
        <span class="glyphicon glyphicon-plus"></span> <span>Dodaj szkolenie (Etap 2)</span>
    </button>


    <div id="myModalgroup" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal_title">Ustalanie szkolenia<span id="modal_category"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="header_modal">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Data:</label>
                                    <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                        <input class="form-control" name="start_date_training" type="text" value="{{date("Y-m-d")}}" readonly />
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        <div class="input-group-addon" id="hidden_content_date">
                                        </div>
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
                                        <div class="input-group-addon" id="hidden_content_time">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Prowadzący:</label>
                                    <select class="form-control" id="id_user">
                                        <option id="0" value="0">Wybierz</option>
                                        @foreach($cadre as $item)
                                            <option id={{$item->id}} value={{$item->id}} >{{$item->last_name.' '.$item->first_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Komentarz:</label>
                                    <textarea id="training_comment" class="form-control" style="height: 50px;" placeholder="Wprowadź na komentarz"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="modal_full" >
                            <div class="col-md-5">
                                <label class="myLabel">Dostępni kandydaci:</label>
                                <div class="search_candidate">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="left_search" placeholder="Wyszukaj kandydata"/>
                                        <div class="input-group-addon">
                                            <input type="checkbox" id="all-put-left" style="display: block">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="left-container">
                                        <div class="list_group" id="list_candidate">
                                            <a class="list-group-item">
                                                Jan Kowalski
                                                <input type="checkbox" class="pull-left" style="display: block">
                                            </a>
                                            <a class="list-group-item checked">
                                                Jan Kowalski
                                                <input type="checkbox" class="pull-left" style="display: block">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="col-md-12">
                                    <div class="col-md-2" id="button_move_area">
                                        <button id="move_right" class="btn btn-default center-block add" style="margin-bottom: 15px">
                                            <i class="glyphicon glyphicon-chevron-right"></i>
                                        </button>
                                        <button id="move_left" class="btn btn-default center-block remove">
                                            <i class="glyphicon glyphicon-chevron-left"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-10">
                                        <label class="myLabel">Osoby obecne na szkoleniu:</label>
                                        <div class="search_candidate">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="right_search" placeholder="Wyszukaj osobe na szkoleniu"/>
                                                <div class="input-group-addon">
                                                    <input type="checkbox" id="all-put-right" style="display: block">
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
                                <div class="col-md-12">
                                    <div class="col-md-2" id="button_move_area_absent">
                                        <button id="move_right_absent" class="btn btn-default center-block add" style="margin-bottom: 15px">
                                            <i class="glyphicon glyphicon-chevron-right"></i>
                                        </button>
                                        <button id="move_left_absent" class="btn btn-default center-block remove">
                                            <i class="glyphicon glyphicon-chevron-left"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-10">
                                        <label class="myLabel">Osoby nieobecne na szkoleniu:</label>
                                        <div class="form-group">
                                            <div class="right-container">
                                                <div class="list_group" id="list_candidate_choice_absent">
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
                        </div>
                        {{--modal dla usunietych szkoleń--}}
                        <div class="col-md-12" id="modal_cancel" style="display: none">
                            <div class="col-md-12">
                                <label class="myLabel">Osoby zapisane na szkolenie (Anulowane):</label>
                                <div class="search_candidate">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="right_search_cancle" placeholder="Wyszukaj osobe na szkoleniu"/>
                                        <div class="input-group-addon">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="right-container">
                                        <div class="list_group" id="list_candidate_choice_cancel">

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


                        {{--modal dla zakończonych szkoleń--}}
                        <div class="col-md-12" id="modal_end" style="display: none">
                            <div class="col-md-12">
                                <label class="myLabel">Osoby biorące udział w szkoleniu:</label>
                                <div class="form-group">
                                    <div class="right-container">
                                        <div class="list_group" id="list_candidate_choice_end">
                                            <table class="table table-striped type_table thead-inverse">
                                                <thead>
                                                <tr>
                                                    <th style="width:5%">Lp.</th>
                                                    <th>Imie i nazwisko</th>
                                                    <th class="category_column">Komentarz</th>
                                                    <th class="category_column">Następny etap</th>
                                                    <th class="category_column" colspan="2">Odrzuć</th>
                                                    <th class="category_column">Status końcowy</th>
                                                </tr>
                                                </thead>
                                                <tbody id="candidate_end_training_decision">

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-success btn-block" id="save_button">Dodaj szkolenie</button>
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
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Lista szkoleń
                </div>
                <div class="alert alert-success" style = "display:none" id="succes_add_training">
                    <span colspan="1">Szkolenie zostało dodane</span>
                </div>
                <div class="alert alert-danger" style = "display:none" id="succes_delete_training">
                    <span colspan="1">Szkolenie zostało usuniete</span>
                </div>
                <div class="alert alert-info" style = "display:none" id="succes_end_training">
                    <span colspan="1">Szkolenie zostało zakończone</span>
                </div>
                <div class="alert alert-warning" style = "display:none" id="succes_edit_training">
                    <span colspan="1">Szkolenie zostało zmodyfikowane</span>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <ul class="nav nav-tabs" style="margin-bottom: 25px">
                            <li class="active"><a data-toggle="tab" href="#home" id="#home">Dostępne (Etap 1)</a></li>
                            <li><a data-toggle="tab" href="#menu1" id="#menu1">Zakończone (Etap 1)</a></li>
                            <li><a data-toggle="tab" href="#menu2" id="#menu2">Usuniete (Etap 1)</a></li>
                            <li><a data-toggle="tab" href="#menu3" id="#menu3">Dostępne (Etap 2)</a></li>
                            <li><a data-toggle="tab" href="#menu4" id="#menu4">Zakończone (Etap 2)</a></li>
                            <li><a data-toggle="tab" href="#menu5" id="#menu5">Usuniete (Etap 2)</a></li>
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
                                                    <td>Liczba osób <br> (Obecne/Nieobecne/Dostępne)</td>
                                                    <td>Osoba Prowadząca</td>
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
                                                    <td>Liczba osób <br> (Obecne/Nieobecne/Dostępne)</td>
                                                    <td>Osoba Prowadząca</td>
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
                                                    <td>Liczba osób <br> (Obecne/Nieobecne/Dostępne)</td>
                                                    <td>Usunieto przez</td>
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

                            {{--ETAP 2--}}
                            <div id="menu3" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <row>
                                            <table id="activ_training_group_stage2" class="table thead-inverse table-striped table-bordered" cellspacing="0" width="100%" >
                                                <thead>
                                                <tr>
                                                    <td>Data</td>
                                                    <td>Godzina</td>
                                                    <td>Liczba osób <br> (Obecne/Nieobecne/Dostępne)</td>
                                                    <td>Osoba Prowadząca</td>
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

                            <div id="menu4" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <row>
                                            <table id="end_training_group_stage2" class="table thead-inverse table-striped table-bordered" cellspacing="0" width="100%" >
                                                <thead>
                                                <tr>
                                                    <td>Data</td>
                                                    <td>Godzina</td>
                                                    <td>Liczba osób <br> (Obecne/Nieobecne/Dostępne)</td>
                                                    <td>Osoba Prowadząca</td>
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


                            <div id="menu5" class="tab-pane fade">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <row>
                                            <table id="cancel_training_group_stage2" class="table thead-inverse table-striped table-bordered" cellspacing="0" width="100%" >
                                                <thead>
                                                <tr>
                                                    <td>Data</td>
                                                    <td>Godzina</td>
                                                    <td>Liczba osób <br> (Obecne/Nieobecne/Dostępne)</td>
                                                    <td>Usunieto przez</td>
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
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        var candidate_to_left = [];
        var candidate_to_right = [];
        var candidate_to_right_absent = [];
        var candidate_to_left_absent = [];
        var id_training_group = 0;
        var training_group_response;
        var is_open = 0;
        var cancel_candidate = 0; // 1- usuniete szkolenie, 2 - zakończone szkolenie, 0 brak stanu nowe
        var saving_type = 1; // 1 - nowy wpis, 0 - edycja
        // wybrany etap szkolenia
        var actual_stage = 0;
        var action_row =
            '<a class="btn btn-default info_active" href="#">'+
            '<span style="color: green" class="glyphicon glyphicon glyphicon-info-sign"></span> Szczegóły'+
            '</a>'+
            '<a class="btn btn-default end_active" href="#" style="width:106px">'+
            '<span style="color: green" class="glyphicon glyphicon glyphicon-pencil"></span> Zakończ'+
            '</a>'+
            '<a class="btn btn-default cancle_active" style="width:106px" data-id ={{1}} href="#">'+
            '<span style="color: green" class="glyphicon glyphicon glyphicon-trash"></span> Usuń'+
            '</a>';
        var first_load = true;
        var action_row_end_cancel =
            '<a class="btn btn-default info_cancel" href="#">'+
            '<span style="color: green" class="glyphicon glyphicon glyphicon-info-sign"></span> Szczegóły'+
            '</a>';
        var action_row_end =
            '<a class="btn btn-default info_end" href="#">'+
            '<span style="color: green" class="glyphicon glyphicon glyphicon-info-sign"></span> Szczegóły'+
            '</a>';


        function  accept_candidate_finaly(e) {
            let row = $(e).closest('tr');
            let candidate_id_end = $(e).closest('tr').attr('id');
            let id_training = $(e).closest('tr').attr('data-id');
            let comment_text = $(e).closest('tr').find('.commnet').val();

            let text_swal_text = "";
            let text_confirm_text = "";

            if(actual_stage == 1){
                text_swal = "Spowoduje to przejście kandydata do etapu 2";
                text_confirm_text = "Tak, zakończ szkolenie etapu 1";
            }else{
                text_swal = "Spowoduje to pozytywne zakończenie procesu szkolenia";
                text_confirm_text = "Tak, zakończ szkolenie etapu 2";
            }
            swal({
                title: text_swal,
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: text_confirm_text
            }).then((result) => {
                if(result.value)
                    {
                        $.ajax({
                            type: "POST",
                            url: '{{ route('api.EndGroupTrainingForCandidate') }}',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                "candidate_id_end": candidate_id_end,
                                "training_group_id": id_training,
                                "status": 1,
                                "comment": comment_text,
                                'training_stage': actual_stage
                            },
                            success: function (response) {
                                if (response == 1) {
                                    swal(
                                        'Szkolenie kandydata zostało zakończone!',
                                        'Szkolenie kandydata zostało zakończone.',
                                        'success'
                                    )
                                    $(e).closest('tr').find('.candidate_status').find('span').text('Zaakceptowany');
                                    $(e).closest('tr').find('.glyphicon-ok').css({'color': 'gray'});
                                    $(e).closest('tr').find('.glyphicon-ok').attr('onclick','');
                                    $(e).closest('tr').find('.glyphicon-remove').css({'color': 'gray'});
                                    $(e).closest('tr').find('.glyphicon-remove').attr('onclick','');
                                }
                            }
                        });
                    }
                });
        }
        function cancel_candidate_finaly(e) {
            let row = $(e).closest('tr');
            let candidate_id_end = $(e).closest('tr').attr('id');
            let id_training = $(e).closest('tr').attr('data-id');
            let comment_text = $(e).closest('tr').find('.commnet').val();
            let attempt_result_status = $(e).closest('tr').find('.attempt_status_stage_one').val();
            if(attempt_result_status == 0){
                swal('Wybierz powód odrzucenia kandydata')
            }else
            swal({
                title: 'Jesteś pewien?',
                text: "Spowoduje to zakończenie szkolenia kandydata, ze statusem odrzucony!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tak, zakończ szkolenie kandydata!'
            }).then((result) => {
                if(result.value)
            {
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.EndGroupTrainingForCandidate') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "candidate_id_end": candidate_id_end,
                        "training_group_id": id_training,
                        "status": 0,
                        "comment": comment_text,
                        'training_stage': actual_stage,
                        'attempt_result_status': attempt_result_status
                    },
                    success: function (response) {
                        if (response == 1) {
                            swal(
                                'Szkolenie kandydata zostało zakończone!',
                                'Szkolenie kandydata zostało zakończone.',
                                'success'
                            )
                            $(e).closest('tr').find('.glyphicon-ok').css({'color': 'gray'});
                            $(e).closest('tr').find('.glyphicon-ok').attr('onclick','');
                            $(e).closest('tr').find('.glyphicon-remove').css({'color': 'gray'});
                            $(e).closest('tr').find('.glyphicon-remove').attr('onclick','');
                            $(e).closest('tr').find('.candidate_status').find('span').text('Odrzucony');
                            $(e).closest('tr').find('.candidate_status').find('span').text('Odrzucony');
                        }
                    }
                });
            }
        });

        }


        function onclickRowLeft(e)
        {
            var candidate_id = e.id;
            var a_row = $('#'+candidate_id);
            var class_name = a_row.attr('class');
            var candidate_name = a_row.text();
            class_name = class_name.split(" ");
            //zaznaczony kandydat do szkolenia
            if(class_name[1] == 'nocheck'){
                a_row.find('[type=checkbox]').prop('checked', true);
                a_row.removeClass('nocheck');
                a_row.addClass('check');
                // wpisanie kandydata do tablicy
                candidate_to_right.push({id:candidate_id,name:candidate_name});
            }else{ // zaznaczony kandydat do usuniecie ze szkolenia
                a_row.find('[type=checkbox]').prop('checked', false);
                a_row.removeClass('check');
                a_row.addClass('nocheck');
                // usunięcie z tablicy
                removeFunction(candidate_to_right,"id",candidate_id);
            }
        }
        function onclickRowRight(e)
        {
            // id kandydata
            var candidate_id = e.id;
            //pobranie wirsza z po id kandydata
            var a_row = $('#'+candidate_id);
            // wysłuskanie nazwy klasy(czy jest zaznaczona czy nie)
            var class_name = a_row.attr('class');
            var candidate_name = a_row.text();
            class_name = class_name.split(" ");

            //zaznaczony kandydat do szkolenia (dodanie odpowiedniej klasy i wpisanie do tablicy
            // dzięki której będzie można przepisać z osobę z jednej tabeli do drugiej
            if(class_name[1] == 'nocheck'){
                a_row.find('[type=checkbox]').prop('checked', true);
                a_row.removeClass('nocheck');
                a_row.addClass('check');
                // wpisanie kandydata do tablicy
                candidate_to_left.push({id:candidate_id,name:candidate_name});
            }else{ // zaznaczony kandydat do usuniecie ze szkolenia ( odznaczenie)
                a_row.find('[type=checkbox]').prop('checked', false);
                a_row.removeClass('check');
                a_row.addClass('nocheck');
                // usunięcie z tablicy po id
                removeFunction(candidate_to_left,"id",candidate_id);
            }
        }

        // Funkcja do usuwania elementów z tablicy no indeksie
        function removeFunction (myObjects,prop,valu)
        {
            var what_delete = null;
            for(var i=0;i<myObjects.length;i++)
            {
                if(myObjects[i][prop] == valu)
                {
                    what_delete = i;
                    break;
                }
            }
            if(what_delete != null)
                myObjects.splice(what_delete,1);
            return myObjects;
        }
        $(document).ready(function() {

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
            $('#save_button').on('click',function (e) {
                var start_date_training = $("input[name='start_date_training']").val();
                var start_hour_training = $("input[id='start_time_training']").val();
                var cadre_id = $("#id_user").val();
                var comment_about_training = $("#training_comment").val();
                var check_all = true;
                var avaible_candidate = [];
                var choice_candidate = [];
                var choice_candidate_ansent = [];
                if(start_hour_training.trim() == 0)
                {
                    swal("Nie wyznaczyłeś godziny szkolenia.")
                }else if(cadre_id == 0)
                {
                    swal("Wyznacz osobę prowadzącą szkolenie.")
                }else{
                    $("#save_button").attr('disabled', true);

                    // wszyscy kandydaci do wyboru
                    $('#list_candidate a').each(function (key, value) {
                        avaible_candidate.push(value.id) ;
                    });
                    // wyszycy kandydaci
                    $('#list_candidate_choice a').each(function (key, value) {
                        choice_candidate.push(value.id) ;
                    });
                    //Kandydaci nieobecni
                    $('#list_candidate_choice_absent a').each(function (key,value) {
                        choice_candidate_ansent.push(value.id);
                    });

                    $.ajax({
                        type: "POST",
                        url: '{{ route('api.saveGroupTraining') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            "start_date_training":start_date_training,
                            "start_hour_training":start_hour_training,
                            "cadre_id": cadre_id,
                            "comment_about_training":comment_about_training,
                            "avaible_candidate":avaible_candidate,
                            "choice_candidate":choice_candidate,
                            "saving_type":saving_type,
                            "id_training_group" : id_training_group,
                            "actual_stage" : actual_stage,
                            "choice_candidate_ansent":choice_candidate_ansent
                        },
                        success: function (response) {
                            if(response == 1)
                            {
                                $('#myModalgroup').modal('hide');
                                if(id_training_group  == 0 )
                                {
                                    $('#succes_add_training').fadeIn(1000);
                                    $('#succes_add_training').delay(3000).fadeOut(1000);
                                }else{
                                    $('#succes_edit_training').fadeIn(1000);
                                    $('#succes_edit_training').delay(3000).fadeOut(1000);
                                }
                                $("#save_button").attr('disabled', false);
                                table_activ_training_group.ajax.reload();
                                activ_training_group_stage2.ajax.reload();
                                if(actual_stage == 1){
                                    console.log(1);
                                    $('.nav-tabs a[href="#home"]').tab('show');
                                }else if(actual_stage == 2){
                                    $('.nav-tabs a[href="#menu3"]').tab('show');
                                }
                            }else if(response == 0){
                                swal('Wystąpił problem z zapise, skontaktuj się z administratorem !!')
                            }
                        }
                    });

                }
            });
            // zaznacz wszystko z lewej kolumny
            $('#all-put-left').change(function (e) {
                $('#list_candidate a').each(function (key, value) {
                    let status_select_all = $('#all-put-left').prop('checked');
                    // zaznaczamy wszsytkie checkbox
                    if(status_select_all)
                    {
                        if(!$(this).find('input:checkbox').prop('checked'))
                        {
                            $(this).trigger('click');
                        }
                    }else { // odznaczamy wszystko
                        // zaznacza niezaznaczone
                    if($(this).find('input:checkbox').prop('checked'))
                        {
                            $(this).trigger('click');
                        }
                    }
                });
            });
            $('#all-put-right').change(function (e) {
                $('#list_candidate_choice a').each(function (key,value) {
                    let status_select_all = $('#all-put-right').prop('checked');
                    if(status_select_all)
                    {
                        if(!$(this).find('input:checkbox').prop('checked'))
                        {
                            $(this).trigger('click');
                        }
                    }else{
                        if($(this).find('input:checkbox').prop('checked'))
                        {
                            $(this).trigger('click');
                        }
                    }
                })
            });

            //po kliknieciu na dodaj szkolenie
            $('#training_to_modal_stage1,#training_to_modal_stage2').on('click',function (e) {

                let stage_name = $(this).attr('id');
                if(stage_name == 'training_to_modal_stage2')
                {
                    actual_stage = 2;
                    $('.nav-tabs a[href="#menu3"]').tab('show');
                }else{
                    actual_stage = 1;
                    $('.nav-tabs a[href="#home"]').tab('show');
                }
            });

            $('#move_right_absent').on('click',function (e) {
                // kod html z tabelą
                var html_right_column = '';
                for(var i = 0;i < candidate_to_right.length; i++)
                {
                    html_right_column += '<a class="list-group-item nocheck" onclick = "onclickRowRight(this)" id=' + candidate_to_right[i].id + '>' +
                        candidate_to_right[i].name +
                        '<input type="checkbox" class="pull-right" style="display: block">' +
                        '</a>';
                    // usunięcie użytkownika z lewej tabeli
                    $('#'+candidate_to_right[i].id).remove();
                }
                $('#list_candidate_choice_absent').append(html_right_column);
                candidate_to_right = [];
                // oddznaczenie 'select all'
                $('#all-put-left').prop('checked',false);

            });

            // przeniesienie do prawej tabeli (wybrani użytkownicy)
            $('#move_right').on('click',function (e) {
                // kod html z tabelą
                var html_right_column = '';
                for(var i = 0;i < candidate_to_right.length; i++)
                {
                    html_right_column += '<a class="list-group-item nocheck" onclick = "onclickRowRight(this)" id=' + candidate_to_right[i].id + '>' +
                        candidate_to_right[i].name +
                        '<input type="checkbox" class="pull-right" style="display: block">' +
                        '</a>';
                    // usunięcie użytkownika z lewej tabeli
                    $('#'+candidate_to_right[i].id).remove();
                }
                $('#list_candidate_choice').append(html_right_column);
                candidate_to_right = [];
                // oddznaczenie 'select all'
                $('#all-put-left').prop('checked',false);
            });

            $('#move_left_absent').on('click',function (e) {
                var html_left_column = '';
                for(var i = 0;i < candidate_to_left.length; i++)
                {
                    html_left_column += '<a class="list-group-item nocheck" onclick = "onclickRowLeft(this)" id=' + candidate_to_left[i].id + '>' +
                        candidate_to_left[i].name +
                        '<input type="checkbox" class="pull-left" style="display: block">' +
                        '</a>';
                    $('#'+candidate_to_left[i].id).remove();
                }
                $('#list_candidate').append(html_left_column);
                candidate_to_left = [];
                $('#all-put-right').prop('checked',false);
            });

            $('#move_left').on('click',function (e) { // analogiczne
                var html_left_column = '';
                for(var i = 0;i < candidate_to_left.length; i++)
                {
                    html_left_column += '<a class="list-group-item nocheck" onclick = "onclickRowLeft(this)" id=' + candidate_to_left[i].id + '>' +
                        candidate_to_left[i].name +
                        '<input type="checkbox" class="pull-left" style="display: block">' +
                        '</a>';
                    $('#'+candidate_to_left[i].id).remove();
                }
                $('#list_candidate').append(html_left_column);
                candidate_to_left = [];
                $('#all-put-right').prop('checked',false);
            });
            // wyszukiwanie
            $("#left_search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".left-container .list_group a").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // wyszukiwanie
            $("#right_search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".right-container .list_group a").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $('#right_search_cancle').on('keyup', function (e) {
                let value = $(this).val().toLowerCase();
                $("#list_candidate_choice_cancel a").filter(function (e) {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                })
            });
            $('#right_search_end').on('keyup', function (e) {
                let value = $(this).val().toLowerCase();
                $("#list_candidate_choice_end a").filter(function (e) {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                })
            });
            $("input[name='start_date_training']").on('change',function (e) {
                clearLeftColumn();
                getGroupTrainingInfo();
            });
            // open modal
            $('#myModalgroup').on('show.bs.modal', function(e) {

                if(actual_stage == 1)
                {
                    $('#modal_title').text('Nowe Szkolenie (Etap 1)');
                    $('#save_button').text('Dodaj szkolenie');
                }else{
                    $('#modal_title').text('Nowe Szkolenie (Etap 2)');
                    $('#save_button').text('Dodaj szkolenie');
                }

                if(is_open == 0)
                {
                    if(saving_type == 1){
                        $("input[name='start_date_training']").val("{{date('Y-m-d')}}");
                    }
                    if(cancel_candidate == 1 || cancel_candidate == 2)
                    {
                        if(cancel_candidate == 2){
                            $('#modal_title').text('Szkolenie zakończone');
                        }else{
                            $('#modal_title').text('Szkolenie usuniete');
                        }
                        $('#header_modal input,select,textarea').prop('disabled',true).off();
                        $('#header_modal .input-group-addon').hide();
                        $('#hidden_content_time').css({"display":"table-cell"});
                        $('#hidden_content_date').css({"display":"table-cell"});
                    }else{

                        $('#hidden_content_time').css({"display":"none"});
                        $('#hidden_content_date').css({"display":"none"});
                    }
                    clearLeftColumn();
                    getGroupTrainingInfo();
                    is_open = 1;
                }
            });
            // Czyszczenie kolumn
            function clearLeftColumn()
            {
                candidate_to_right = [];
                candidate_to_left = [];
                candidate_to_right_absent = [];
                candidate_to_left_absent = [];
                $(".list_group a").remove();
            }
            // usuniecie podstawowych infromacji o szkoleniu
            function clearModalBasicInfo () {
                $("input[name='start_date_training']").val("");
                $("input[id='start_time_training']").val("");
                $("#id_user").prop("selectedIndex", 0);
                $("#training_comment").val("");
            }
            // pobranie danych o szkoleniu nowym gdy id_training_group  == 0 lub istniejącym != 0
            function getGroupTrainingInfo() {
                // gdy tworzone jest nowe szkolenie
                if(id_training_group == 0){
                    $.ajax({
                        type: "POST",
                        url: '{{ route('api.getCandidateForGroupTrainingInfo') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'training_stage': actual_stage,
                            'date_training': $("input[name='start_date_training']").val()
                        },
                        success: function (response) {
                            console.log(response);
                            if (response.length != 0) {
                                for (var i = 0; i < response.length; i++) {
                                    var html = '<a class="list-group-item nocheck" onclick = "onclickRowLeft(this)" id=' + response[i].id + '>' +
                                        response[i].first_name + ' ' + response[i].last_name +' '+response[i].phone+
                                        '<input type="checkbox" class="pull-left" style="display: block">' +
                                        '</a>';
                                    if (response[i].attempt_status_id == 5 || response[i].attempt_status_id == 12) {
                                        $('#list_candidate').append(html);
                                    }
                                }
                            }
                        }
                    });
                }// istniejące
                else if(id_training_group != 0 ) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('api.getGroupTrainingInfo') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            "id_training_group": id_training_group,
                            'date_training': $("input[name='start_date_training']").val(),
                            "cancel_candidate": cancel_candidate,
                            'training_stage': actual_stage
                        },
                        success: function (response) {
                            console.log(response);
                            training_group_response = response;
                            let lp = 1;
                            $('#candidate_end_training_decision tr').remove();
                            if (response.length != 0) {
                                for (var i = 0; i < response['group_training'].length; i++) {
                                    // pobranie danych przy pierwszym otworzeniu modala, poźniej pomijaj ten etap.
                                    if(first_load == true)
                                    {
                                        $("input[name='start_date_training']").val(response['group_training'][i].training_date);
                                        $("input[id='start_time_training']").val(response['group_training'][i].training_hour.slice(0, -3));
                                        $("#id_user").val(response['group_training'][i].leader_id);
                                        $("#training_comment").val(response['group_training'][i].comment);
                                        first_load = false;
                                    }

                                }
                                for (var i = 0; i < response['candidate'].length; i++) {
                                                            //ETAP 1
                                    if (response['candidate'][i].attempt_status_id == 5 && cancel_candidate == 0) {
                                        var html = '<a class="list-group-item nocheck" onclick = "onclickRowLeft(this)" id=' + response['candidate'][i].id + '>' +
                                            response['candidate'][i].first_name + ' ' + response['candidate'][i].last_name +' '+response['candidate'][i].phone+
                                            '<input type="checkbox" class="pull-left" style="display: block">' +
                                            '</a>';
                                        $('#list_candidate').append(html);
                                    } else if (response['candidate'][i].attempt_status_id == 6  && cancel_candidate == 0) {
                                        var html = '<a class="list-group-item nocheck" onclick = "onclickRowRight(this)" id=' + response['candidate'][i].id + '>' +
                                            response['candidate'][i].first_name + ' ' + response['candidate'][i].last_name +' '+response['candidate'][i].phone+
                                            '<input type="checkbox" class="pull-right" style="display: block">' +
                                            '</a>';
                                        $('#list_candidate_choice').append(html);
                                    }               //ETAP 2
                                    else if (response['candidate'][i].attempt_status_id == 12 && cancel_candidate == 0) {
                                        var html = '<a class="list-group-item nocheck" onclick = "onclickRowLeft(this)" id=' + response['candidate'][i].id + '>' +
                                            response['candidate'][i].first_name + ' ' + response['candidate'][i].last_name +' '+response['candidate'][i].phone+
                                            '<input type="checkbox" class="pull-left" style="display: block">' +
                                            '</a>';
                                        $('#list_candidate').append(html);
                                    } else if (response['candidate'][i].attempt_status_id == 13  && cancel_candidate == 0) {
                                        var html = '<a class="list-group-item nocheck" onclick = "onclickRowRight(this)" id=' + response['candidate'][i].id + '>' +
                                            response['candidate'][i].first_name + ' ' + response['candidate'][i].last_name +' '+response['candidate'][i].phone+
                                            '<input type="checkbox" class="pull-right" style="display: block">' +
                                            '</a>';
                                        $('#list_candidate_choice').append(html);
                                    }else if ((response['candidate'][i].attempt_status_id == 18 ||response['candidate'][i].attempt_status_id == 19 )  && cancel_candidate == 0) {
                                        var html = '<a class="list-group-item nocheck" onclick = "onclickRowRight(this)" id=' + response['candidate'][i].id + '>' +
                                            response['candidate'][i].first_name + ' ' + response['candidate'][i].last_name +' '+response['candidate'][i].phone+
                                            '<input type="checkbox" class="pull-right" style="display: block">' +
                                            '</a>';
                                        $('#list_candidate_choice_absent').append(html);
                                    }
                                    else if (cancel_candidate != 0) {
                                        var html = '<a class="list-group-item nocheck" id=' + response['candidate'][i].id + '>' +
                                            response['candidate'][i].first_name + ' ' + response['candidate'][i].last_name +' '+response['candidate'][i].phone+
                                            '</a>';
                                        if(cancel_candidate == 1)
                                            $('#list_candidate_choice_cancel').append(html);
                                        else if(cancel_candidate  == 2){
                                            var status_ended = '';
                                            var span_color_succes = 'green';
                                            var span_color_faild = 'red';
                                            var fucnction_on_click_succes = 'onclick=accept_candidate_finaly(this)';
                                            var fucnction_on_click_cancel = 'onclick=cancel_candidate_finaly(this)';
                                            var select_status = '';
                                            var comment_text = response['candidate'][i].recruitment_story_comment;
                                            if(comment_text == null)
                                            {
                                                comment_text= '';
                                            }
                                            if(response['candidate'][i].completed_training != null || (response['candidate'][i].recruitment_story_id == 19 || response['candidate'][i].recruitment_story_id == 18))
                                            {
                                                if(response['candidate'][i].recruitment_story_id == 8 || response['candidate'][i].recruitment_story_id == 15)
                                                {
                                                    status_ended ='<td class="candidate_status">'+
                                                        '<span>Zaakceptowany do etapu 2</span>'+
                                                        '</td>';
                                                }else if(response['candidate'][i].recruitment_story_id == 19 || response['candidate'][i].recruitment_story_id == 18){
                                                    status_ended ='<td class="candidate_status">'+
                                                        '<span>Nieobecny na szkoleniu</span>'+
                                                        '</td>';
                                                }
                                                else{
                                                    status_ended = '<td class="candidate_status">'+
                                                        '<span>Odrzucony</span>'+
                                                        '</td>';
                                                }
                                                span_color_succes = span_color_faild= 'gray';
                                                fucnction_on_click_succes= '';
                                                fucnction_on_click_cancel= '';
                                                select_status = 'disabled';

                                            }else{
                                                status_ended = '<td class="candidate_status">'+
                                                    '<span>Oczekuje na weryfikacje</span>'+
                                                    '</td>';
                                            }

                                            html = '<tr id='+response['candidate'][i].id+' data-id='+id_training_group+'>'+
                                                '<td>'+(lp++)+'</td>'+
                                                '<td>'+response['candidate'][i].first_name+' '+ response['candidate'][i].last_name+'</td>'+
                                                '<td>'+
                                                ' <input type="text" class="form-control commnet" value="'+comment_text+'">'+
                                                ' </input>'+
                                                ' </td>'+
                                                '<td>'+
                                                '<span style="color:'+span_color_succes+';font-size: 20px;margin-right: 5px" class="glyphicon glyphicon-ok" '+fucnction_on_click_succes+'></span>'+
                                                ' </td>'+
                                                '<td>'+
                                                '<select class="form-control attempt_status_stage_one" '+select_status+'>' +
                                                '<option value=0>Wybierz</option>';
                                                for (var j = 0; j < response['attempt_status'].length; j++) {
                                                    if(response['candidate'][i].recruitment_attempt_result_id == response['attempt_status'][j].id){
                                                        html += '<option selected value='+response['attempt_status'][j].id+'>'+response['attempt_status'][j].name+'</option>';
                                                    }else
                                                        html += '<option value='+response['attempt_status'][j].id+'>'+response['attempt_status'][j].name+'</option>';
                                                }
                                            html +='</select>' +
                                                '</td>'+
                                                '<td>'+
                                                '<span style="color:'+span_color_faild+';font-size: 20px;" class="glyphicon glyphicon-remove" '+fucnction_on_click_cancel+' ></span>'+
                                                ' </td>';

                                            html +=status_ended+'</tr>';
                                            $('#candidate_end_training_decision').append(html);
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }

            //cancel modal
            $('#myModalgroup').on('hidden.bs.modal',function () {
                $('#header_modal input,select,textarea').prop('disabled',false).on();
                $('#hidden_content_date').css({"display":"none"});
                $('#hidden_content_time').css({"display":"none"});
                $('.input-group-addon').show();
                $('.hidden_content').hide();
                // pierwsze otworzenie modala
                first_load = true;
                // id szkolenia
                id_training_group = 0;
                clearModalBasicInfo();
                clearLeftColumn();
                is_open = 0;
                cancel_candidate = 0;
                saving_type = 1;
                $('#all-put-right').prop('checked',false);
                $('#all-put-left').prop('checked',false);
                $("#save_button").css({'display':'block'});
                $("#modal_full").css({'display':'block'});
                $("#modal_cancel").css({'display':'none'});
                $("#modal_end").css({'display':'none'});
            });

            //tabela dostępnych szkoleń
            var table_activ_training_group = $('#activ_training_group').DataTable({
                "order": [[ 0, "desc" ]],
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
                        d.training_stage = 1;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "columns": [
                    {"data": "training_date"},
                    {"data": "training_hour"},
                    {
                        "data": function (data, type, dataToSet) {
                            return data.candidate_choise_count+'/'+data.candidate_absent_count+'/'+data.candidate_avaible_count;
                        },"name":"group_training.candidate_choise_count"
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            return data.last_name+' '+data.first_name;
                        },"name":"leader.last_name"
                    },
                    {"width":"10%",
                        "data": function (data, type, dataToSet) {
                            return action_row;
                        },"searchable": false,"orderable": false
                    }
                ],"fnDrawCallback": function(settings){ // działanie po wyrenderowaniu widoku
                    // po kliknięcu w szczegóły otwórz modal z możliwością edycji
                    $('#activ_training_group .info_active').on('click',function (e) {
                        saving_type = 0;
                        actual_stage  = 1;
                        //główny wiersz
                        var tr = $(this).closest('tr');
                        id_training_group = tr.attr('id');
                        $('#myModalgroup').modal("show");
                        $('#modal_title').text('Szczegóły szkolenia');
                        $('#save_button').text('Zapisz zmiany');
                    });
                    // zakończenie szkolenia
                    $('#activ_training_group .end_active').click(function (e) {
                        //pobranie id szkolenia
                        let training_group_to_end = $(this).closest('tr').attr('id');
                        //pobranie informacji o liczbie kandydatów (wybrni/nieobecni/dostępni)
                        // nie przepuszczej gdy ktoś jest dostępny
                        let row_with_count_info = $(this).closest('tr').find('td:eq(2)').text();
                        // liczba osób dosępnych do wybrania
                        let avaible_candidate = row_with_count_info.split("/");
                        avaible_candidate = avaible_candidate[2];
                        if(parseInt(avaible_candidate) > 0)
                        {
                            swal('Aby zakończyć szkolenie musisz określić wszystkich kandydatów')
                        }else
                        swal({
                            title: 'Jesteś pewien?',
                            text: "Spowoduje to zakończenie szkolenia, bez możliwości cofnięcia zmian!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Tak, zakończ szkolenie!'
                        }).then((result) => {
                            if (result.value)
                            {
                                $.ajax({
                                    type: "POST",
                                    url: '{{ route('api.EndGroupTraining') }}',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: {
                                        "training_group_to_end" : training_group_to_end,
                                        "training_stage":1
                                    },
                                    success: function (response) {
                                        if(response == 1)
                                        {
                                            swal(
                                                'Szkolenie zakończone!',
                                                'Szkolenie zostało zakończone.',
                                                'success'
                                            )
                                            $('#succes_end_training').fadeIn(1000);
                                            $('#succes_end_training').delay(3000).fadeOut(1000);
                                            table_activ_training_group.ajax.reload();
                                            table_cancel_training_group.ajax.reload();
                                            table_end_training_group.ajax.reload();

                                            activ_training_group_stage2.ajax.reload();
                                            table_end_training_group_stage2.ajax.reload();
                                            table_cancel_training_group_stage2.ajax.reload();

                                            //przekierowanie do zakłądki z zakończonymi szkoleniami
                                            // etap 1
                                            $('.nav-tabs a[href="#menu1"]').tab('show');
                                        }
                                    }
                                });
                            }
                        });
                    });
                    //usunięcie szkolenia
                    $(' #activ_training_group .cancle_active').on('click',function (e) {
                        let id_training_group_to_delete = $(this).closest('tr').attr('id');
                        swal({
                            title: 'Jesteś pewien?',
                            text: "Spowoduje to usuniecie szkolenia, cofnięcie zmian nie będzie możliwe!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Tak, usuń szkolenie!'
                        }).then((result) => {
                            if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: '{{ route('api.deleteGroupTraining') }}',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    "id_training_group_to_delete" : id_training_group_to_delete,
                                    "training_stage":1
                                },
                                success: function (response) {
                                    if(response == 1)
                                    {
                                        swal(
                                            'Usunięto szkolenie!',
                                            'Szkolenie zostało usunięte. Kandydaci zostali umieszczeni w poczekali',
                                            'success'
                                        )
                                        $('#succes_delete_training').fadeIn(1000);
                                        $('#succes_delete_training').delay(3000).fadeOut(1000);
                                        table_activ_training_group.ajax.reload();
                                        table_cancel_training_group.ajax.reload();

                                        //przekierowanie do zakłądki z usunietymi szkoleniami
                                        // etap 1
                                        $('.nav-tabs a[href="#menu2"]').tab('show');
                                    }
                                }
                            });
                        }
                    });
                    });
                },"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                    // Dodanie id do wiersza
                    $(nRow).attr('id', aData.id);
                    return nRow;
                }
            });
            // tabela zakończonych szkoleń
            var table_end_training_group = $('#end_training_group').DataTable({
                "order": [[ 0, "desc" ]],
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
                        d.training_stage = 1;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"columns": [
                    {"data": "training_date"},
                    {"data": "training_hour"},
                    {
                        "data": function (data, type, dataToSet) {
                            return data.candidate_choise_count+'/'+data.candidate_absent_count+'/'+data.candidate_avaible_count;
                        },"name":"group_training.candidate_choise_count"
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            return data.last_name+' '+data.first_name;
                        },"name":"leader.last_name"
                    },
                    { "width": "10%",
                        "data": function (data, type, dataToSet) {
                            return action_row_end;
                        },"searchable": false,"orderable": false
                    }
                ],"fnDrawCallback": function(settings){
                    $('#end_training_group .info_end').on('click',function (e) {
                        $("#modal_full").css({'display':'none'});
                        $("#modal_cancel").css({'display':'none'});
                        $("#modal_end").css({'display':'block'});

                        $("#save_button").css({'display':'none'});
                        saving_type = 0;
                        cancel_candidate = 2;
                        actual_stage = 1;
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
            // tabela skaswoanych szkoleń
            var table_cancel_training_group = $('#cancel_training_group').DataTable({
                "order": [[ 0, "desc" ]],
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
                        d.training_stage = 1;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"columns": [
                    {"data": "training_date"},
                    {"data": "training_hour"},
                    {
                        "data": function (data, type, dataToSet) {
                            return data.candidate_choise_count+'/'+data.candidate_absent_count+'/'+data.candidate_avaible_count;
                        },"name":"group_training.candidate_choise_count"
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            return data.last_name+' '+data.first_name;
                        },"name":"edit_cadre.last_name"
                    },
                    { "width": "10%",
                        "data": function (data, type, dataToSet) {
                            return action_row_end_cancel;
                        },"searchable": false,"orderable": false
                    }
                ],"fnDrawCallback": function(settings){
                        $('.info_cancel').on('click',function (e) {
                            $("#modal_full").css({'display':'none'});
                            $("#modal_end").css({'display':'none'});
                            $("#modal_cancel").css({'display':'block'});
                            $("#save_button").css({'display':'none'});
                            saving_type = 0;
                            cancel_candidate = 1;
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

            // Dostępne szkolenia etapu 2

            var activ_training_group_stage2 =$('#activ_training_group_stage2').DataTable({
                "order": [[ 0, "desc" ]],
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
                        d.training_stage = 2;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "columns": [
                    {"data": "training_date"},
                    {"data": "training_hour"},
                    {
                        "data": function (data, type, dataToSet) {
                            return data.candidate_choise_count+'/'+data.candidate_absent_count+'/'+data.candidate_avaible_count;
                        },"name":"group_training.candidate_choise_count"
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            return data.last_name+' '+data.first_name;
                        },"name":"leader.last_name"
                    },
                    {"width":"10%",
                        "data": function (data, type, dataToSet) {
                            return action_row;
                        },"searchable": false,"orderable": false
                    }
                ],"fnDrawCallback": function(settings){ // działanie po wyrenderowaniu widoku
                    // po kliknięcu w szczegóły otwórz modal z możliwością edycji
                    $('#activ_training_group_stage2 .info_active').on('click',function (e) {
                        saving_type = 0;
                        //główny wiersz
                        var tr = $(this).closest('tr');
                        id_training_group = tr.attr('id');
                        actual_stage  = 2;
                        $('#myModalgroup').modal("show");
                        $('#modal_title').text('Szczegóły szkolenia');
                        $('#save_button').text('Zapisz zmiany');
                    });
                    // zakończenie szkolenia
                    $('#activ_training_group_stage2 .end_active').click(function (e) {
                        let training_group_to_end = $(this).closest('tr').attr('id');
                        let row_with_count_info = $(this).closest('tr').find('td:eq(2)').text();
                        // liczba osób dosępnych do wybrania
                        let avaible_candidate = row_with_count_info.split("/");
                        avaible_candidate = avaible_candidate[2];
                        if(parseInt(avaible_candidate) > 0)
                        {
                            swal('Aby zakończyć szkolenie musisz określić wszystkich kandydatów')
                        }else
                        swal({
                            title: 'Jesteś pewien?',
                            text: "Spowoduje to zakończenie szkolenia, bez możliwości cofnięcia zmian!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Tak, zakończ szkolenie!'
                        }).then((result) => {
                            if (result.value)
                        {
                            $.ajax({
                                type: "POST",
                                url: '{{ route('api.EndGroupTraining') }}',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    "training_group_to_end" : training_group_to_end,
                                    "training_stage":2
                                },
                                success: function (response) {
                                    if(response == 1)
                                    {
                                        swal(
                                            'Szkolenie zakończone!',
                                            'Szkolenie zostało zakończone.',
                                            'success'
                                        )
                                        $('#succes_end_training').fadeIn(1000);
                                        $('#succes_end_training').delay(3000).fadeOut(1000);
                                        table_activ_training_group.ajax.reload();
                                        table_cancel_training_group.ajax.reload();
                                        table_end_training_group.ajax.reload();

                                        activ_training_group_stage2.ajax.reload();
                                        table_end_training_group_stage2.ajax.reload();
                                        table_cancel_training_group_stage2.ajax.reload();
                                        //przekierowanie do zakłądki z zakończonymi szkoleniami
                                        // etap 2
                                        $('.nav-tabs a[href="#menu4"]').tab('show');
                                    }
                                }
                            });
                        }
                    });
                    });
                    //usunięcie szkolenia
                    $('#activ_training_group_stage2 .cancle_active').on('click',function (e) {
                        let id_training_group_to_delete = $(this).closest('tr').attr('id');
                        swal({
                            title: 'Jesteś pewien?',
                            text: "Spowoduje to usuniecie szkolenia, cofnięcie zmian nie będzie możliwe!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Tak, usuń szkolenie!'
                        }).then((result) => {
                            if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: '{{ route('api.deleteGroupTraining') }}',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    "id_training_group_to_delete" : id_training_group_to_delete,
                                    "training_stage":2
                                },
                                success: function (response) {
                                    if(response == 1)
                                    {
                                        swal(
                                            'Usunięto szkolenie!',
                                            'Szkolenie zostało usunięte. Kandydaci zostali umieszczeni w poczekali',
                                            'success'
                                        )
                                        $('#succes_delete_training').fadeIn(1000);
                                        $('#succes_delete_training').delay(3000).fadeOut(1000);
                                        table_activ_training_group.ajax.reload();
                                        table_cancel_training_group.ajax.reload();

                                        activ_training_group_stage2.ajax.reload();
                                        table_cancel_training_group_stage2.ajax.reload();

                                        //przekierowanie do zakłądki z usuniętymi szkoleniami
                                        // etap 2
                                        $('.nav-tabs a[href="#menu5"]').tab('show');
                                    }
                                }
                            });
                        }
                    });
                    });
                },"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                    // Dodanie id do wiersza
                    $(nRow).attr('id', aData.id);
                    return nRow;
                }
            });

            // tabela zakończonych szkoleń
            var table_end_training_group_stage2 = $('#end_training_group_stage2').DataTable({
                "order": [[ 0, "desc" ]],
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
                        d.training_stage = 2;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"columns": [
                    {"data": "training_date"},
                    {"data": "training_hour"},
                    {
                        "data": function (data, type, dataToSet) {
                            return data.candidate_choise_count+'/'+data.candidate_absent_count+'/'+data.candidate_avaible_count;
                        },"name":"group_training.candidate_choise_count"
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            return data.last_name+' '+data.first_name;
                        },"name":"leader.last_name"
                    },
                    { "width": "10%",
                        "data": function (data, type, dataToSet) {
                            return action_row_end;
                        },"searchable": false,"orderable": false
                    }
                ],"fnDrawCallback": function(settings){
                    $('#end_training_group_stage2 .info_end').on('click',function (e) {
                        $("#modal_full").css({'display':'none'});
                        $("#modal_cancel").css({'display':'none'});
                        $("#modal_end").css({'display':'block'});

                        $("#save_button").css({'display':'none'});
                        saving_type = 0;
                        //Zakończone szkolenie etap 2
                        cancel_candidate = 2;
                        //główny wiersz
                        var tr = $(this).closest('tr');
                        id_training_group = tr.attr('id');
                        actual_stage = 2;
                        $('#myModalgroup').modal("show");
                    });
                },"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                    // Dodanie id do wiersza
                    $(nRow).attr('id', aData.id);
                    return nRow;
                }
            });

            // tabela skaswoanych szkoleń
            var table_cancel_training_group_stage2 = $('#cancel_training_group_stage2').DataTable({
                "order": [[ 0, "desc" ]],
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
                        d.training_stage = 2;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"columns": [
                    {"data": "training_date"},
                    {"data": "training_hour"},
                    {
                        "data": function (data, type, dataToSet) {
                            return data.candidate_choise_count+'/'+data.candidate_absent_count+'/'+data.candidate_avaible_count;
                        },"name":"group_training.candidate_choise_count"
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            return data.last_name+' '+data.first_name;
                        },"name":"edit_cadre.last_name"
                    },
                    { "width": "10%",
                        "data": function (data, type, dataToSet) {
                            return action_row_end_cancel;
                        },"searchable": false,"orderable": false
                    }
                ],"fnDrawCallback": function(settings){
                    $('#cancel_training_group_stage2 .info_cancel').on('click',function (e) {
                        $("#modal_full").css({'display':'none'});
                        $("#modal_end").css({'display':'none'});
                        $("#modal_cancel").css({'display':'block'});
                        $("#save_button").css({'display':'none'});
                        saving_type = 0;
                        //Usuniete szkolenia
                        cancel_candidate = 1;
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

        });
    </script>
@endsection
