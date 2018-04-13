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

    <div class="panel panel-default">
        <div class="panel-heading">
            Legenda
        </div>
        <div class="panel-body">
            <div class="alert alert-success">
                <h4>
                    <p>Średnia wyjściowa - średnia przed rozpoczęciem coachingu. </p>
                    <p>Aktualna średnia - średnia z aktualnie zaakceptowanych godzin (przyrostowa), liczona od daty rozpoczęcia coachingu.</p>
                    <p>Aktualna RBH - ilość aktualnych zaakceptowanych godzin (przyrostowa), liczone od daty rozpoczęcia coachingu.</p>
                    <p>Cel - Średnia wymagana.</p>
                </h4>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="myLabel">Zakres od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start_in_progress" name="date_start_in_progress" type="text" value="{{date('Y-m-01')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                                        <th>Średnia wyjściowa</th>
                                        <th>Aktualna średnia</th>
                                        <th>Aktualne RBH</th>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="myLabel">Zakres od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start_unsettled" name="date_start_unsettled" type="text" value="{{date('Y-m-01')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                                    <th>Średnia wyjściowa</th>
                                    <th>Aktualna średnia</th>
                                    <th>Aktualne RBH</th>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="myLabel">Zakres od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start_settled" name="date_start_settled" type="text" value="{{date('Y-m-01')}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                                    <th>Średnia wyjściowa</th>
                                    <th>Osiągnieta średnia</th>
                                    <th>Końcowe RBH</th>
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

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Legenda
                        </div>
                        <div class="panel-body">
                            <div class="alert alert-success">
                                <h4>
                                    Aktualna średnia wyliczana jest na podstawie ostatnich ~18 RBH danego konsultanta.
                                </h4>
                                <h4>
                                    W przypadku gdy, aktualna średnia jest większa niż 0.5, wymagane jest aby średnia docelowa mieściła się w przedziale od 10% do 30% aktualnej średniej.
                                </h4>
                                <h4>
                                    Konsultant wyświetli się na liście, po zaakceptowaniu przynajmniej jednej godziny.
                                </h4>
                            </div>
                        </div>
                    </div>

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
                                        <input type="number" lang="en" class="form-control" name="coaching_actual_avg" id="coaching_actual_avg" placeholder="Wprawoadź aktualną średnią"/>
                                    </div>
                                </div>

                                {{--<div class="col-md-4">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="myLabel">Cel od</label>--}}
                                        {{--<input type="number" class="form-control" id="coaching_goal_min" placeholder="Wprawoadź minimalny cel"/>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="myLabel">Średnia docelowa</label>
                                        <input type="number" class="form-control" id="coaching_goal" placeholder="Wprawoadź maksymalny cel"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-success form-control" id="save_coaching_modal" onclick = "save_coaching(this)" >Dodaj Coaching</button>
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

    <input type="hidden" value="0" id="status_coauching" />

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
            //let coaching_goal_min = $("#coaching_goal_min").val();
            let coaching_goal = $("#coaching_goal").val();
            let coaching_comment = $("#comment_coaching").val();
            let coaching_actual_avg = $("#coaching_actual_avg").val();
            let coaching_actual_avg_pom = parseFloat(coaching_actual_avg);
            let proc_coaching_goal_min = Math.round((((coaching_actual_avg_pom*10)/100) + coaching_actual_avg_pom)*100)/100;
            let proc_coaching_goal_max = Math.round((((coaching_actual_avg_pom*30)/100) + coaching_actual_avg_pom)*100)/100;
            console.log(proc_coaching_goal_min+' '+proc_coaching_goal_max);
            let validation = true;
            if(consultant_id == 'Wybierz'){
                validation = false;
                swal('Wybierz konsultanta')
            }else if(subject.trim('').length == 0){
                validation = false;
                swal('Dodaj temat')
            }else if(new Date(coaching_date).getTime() < new Date("{!! date('Y-m-d') !!}").getTime() && $('#status_coauching').val() == 0){
                validation = false;
                swal('Błędna data')
            }else if(coaching_actual_avg.trim('').length == 0 || isNaN(coaching_actual_avg) ){
                validation = false;
                swal('Błędna aktualna średnia')
            }
            // else if(coaching_goal_min.trim('').length == 0 || isNaN(coaching_goal_min) ){
            //     validation = false;
            //     swal('Błędna minimalna średnia')
            // }
            else if(coaching_goal.trim('').length == 0 || isNaN(coaching_goal) || coaching_goal <= 0){
                validation = false;
                swal('Błędna docelowa średnia')
            }

            else if(coaching_actual_avg_pom > 0.5){
            if(coaching_goal < proc_coaching_goal_min){
                    validation = false;
                    swal('Minimalna średnia musi być większa niż 10% aktualnej średniej')
                }
                else if(coaching_goal > proc_coaching_goal_max){
                    validation = false;
                    swal('Minimalna średnia musi być mniejsza niż 30% aktualnej średniej')
                }
            }
            // else if(coaching_goal_min > coaching_goal_max) {
            //     validation = false;
            //     swal('Błędny przedział')
            // }

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
                        // 'coaching_goal_min'     : coaching_goal_min,
                        'coaching_goal'     : coaching_goal,
                        'coaching_comment'      : coaching_comment,
                        'coaching_actual_avg'   : coaching_actual_avg,
                        'status'               : $('#status_coauching').val(),
                    },
                    success: function (response) {
                        console.log(response);
                        $('#Modal_Coaching').modal('hide');
                    }
                })
            }
        }



        $(document).ready(function(){
            $('#new_coaching_modal').on('click',function () {
                clear_moda();
                $('#save_coaching_modal').text('Dodaj Coaching');
            });
            $('#Modal_Coaching').on('hidden.bs.modal',function () {
                in_progress_table.ajax.reload();
                $('#status_coauching').val("0");
                clear_moda();
            });

            var consultant = JSON.parse('{!!$consultant!!}');
            $('#couaching_user_id').on('change',function () {
                for(var i =0;i<consultant.length;i++){
                    if(consultant[i].id == $(this).val()){
                        $('input[name="coaching_actual_avg"]').val(consultant[i].avg_consultant);
                        break;
                    }else{
                        $('#coaching_actual_avg').val('');
                    }
                }
            });

            function clear_moda() {
                $('#couaching_user_id').val('Wybierz');
                $('#coaching_subject').val('');
                $('#coaching_actual_avg').val('');
                $('#coaching_goal').val('');
                $('#date_start_new_coaching').val("{!! date('Y-m-d') !!}");
            }


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
                },"rowCallback": function( row, data, index ) {
                    if (data.couching_rbh >= 64800) {
                        $(row).hide();
                    }
                    $(row).attr('id', data.id);
                    return row;
                },"fnDrawCallback": function(settings){

                    /**
                     * Usunięcie coachingu
                     */
                    $('.button-delete-coaching').on('click',function () {
                        coaching_id = $(this).data('id');
                        swal({
                            title: 'Jesteś pewien?',
                            text: "Nie będziesz w stanie cofnąć zmian!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Tak, usuń coaching!'
                        }).then((result) => {
                            if (result.value) {

                            $.ajax({
                                type: "POST",
                                url: "{{ route('api.deleteCoaching') }}", // do zamiany
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    'coaching_id'         : coaching_id
                                },
                                success: function (response) {
                                    in_progress_table.ajax.reload();
                                }
                            });
                        }})
                    });

                    /**
                     * Educja coachingu
                     */
                    $('.button-edit-coaching').on('click',function () {
                        clear_moda();
                        coaching_id = $(this).data('id');
                        $.ajax({
                            type: "POST",
                            url: "{{ route('api.getCoaching') }}", // do zamiany
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'coaching_id'         : coaching_id
                            },
                            success: function (response) {
                                $('#couaching_user_id').val(response.consultant_id);
                                $('#coaching_subject').val(response.subject);
                                $('#coaching_actual_avg').val(response.coaching_actual_avg);
                                $('#coaching_goal').val(response.average_goal);
                                $('#date_start_new_coaching').val(response.coaching_date);
                                $('#save_coaching_modal').text('Edytuj Coaching');
                                $('#status_coauching').val(response.id);
                                $('#Modal_Coaching').modal('show');
                                in_progress_table.ajax.reload();
                            }
                        });


                    });


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
                        {"data": "coaching_actual_avg"},
                        {"data":function (data, type, dataToSet) {
                                let color = 'green';
                                if(parseFloat(data.avg_consultant) < parseFloat(data.average_goal))
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
                        d.report_status = 0;
                        d.date_start = $('#date_start_unsettled').val();
                        d.date_stop =  $('#date_stop_unsettled').val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"rowCallback": function( row, data, index ) {
                    if (data.couching_rbh < 64800) {
                        $(row).hide();
                    }
                    $(row).attr('id', data.id);
                    return row;
                }
                ,"fnDrawCallback": function(settings){

                    $('.btn-accept_coaching').on('click',function () {
                        let coaching_id = $(this).data('id');
                        let coaching_comment = $('#text_'+coaching_id).val();
                        let row = $(this).closest('tr');
                        let avrage_end =  row.find('td:nth-child(6)').text();

                        let rbh_end = row.find('td:nth-child(7)').text();
                        console.log(avrage_end+' '+rbh_end+' '+coaching_id);
                        swal({
                            title: 'Jesteś pewien?',
                            text: "Nie będziesz w stanie cofnąć zmian!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Tak, akceptuj coaching!'
                        }).then((result) => {
                            if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('api.acceptCoaching') }}", // do zamiany
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    'coaching_id'           : coaching_id,
                                    'coaching__comment'     : coaching_comment,
                                    'avrage_end'            : avrage_end,
                                    'rbh_end'               : rbh_end,
                                    'status'                : 1
                                },
                                success: function (response) {
                                    console.log(response)
                                    table_unsettled.ajax.reload();
                                    table_settled.ajax.reload();
                                }
                            });
                        }})
                    });
                }
                ,"columns":[
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
                    {"data": "coaching_actual_avg"},
                    {"data":function (data, type, dataToSet) {
                            let color = 'green';
                            if(parseFloat(data.avg_consultant) < parseFloat(data.average_goal))
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
                        d.report_status = 1;
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
                    {"data": "coaching_actual_avg"},
                    {"data":function (data, type, dataToSet) {
                            let color = 'green';
                            if(parseFloat(data.avrage_end) < parseFloat(data.average_goal))
                                color = 'red';
                            return '<span style="color:' + color + '">' + data.avrage_end + '</span>';
                        },"name": "avrage_end","searchable": false
                    },
                    {"data":function (data, type, dataToSet) {
                            return Math.round(data.rbh_end,2);
                        },"name": "rbh_end","searchable": false
                    },
                    {"data": "average_goal"},
                    {"data":"comment"},
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
