@extends('layouts.main')
@section('content')

    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/select.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/editor.bootstrap.min.css')}}" rel="stylesheet">
     <style>
        .panel-heading a:after {
            font-family:'Glyphicons Halflings';
            content:"\e114";
            float: right;
            color: grey;
        }
        .panel-heading a.collapsed:after {
            content:"\e080";
        }
        .table-striped tr td:first-child + td + td + td + td{
            word-break: break-all;
        }
        .table-striped tr td:first-child + td + td + td + td + td{
            word-break: break-all;
        }
        .table-striped tr td:first-child + td + td + td + td + td+ td+ td+ td{
            word-break: break-all;
        }

    </style>

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Weryfikacja Janków</h1>
        </div>
    </div>

            <div class="panel panel-default"  id="panel2">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop" class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Data Dodania</th>
                                                <th>Data Ważności</th>
                                                <th>Konsultant</th>
                                                <th>Telefon</th>
                                                <th>Kampania</th>
                                                <th>Komentarz</th>
                                                <th>Weryfikacja Trenera</th>
                                                <th>Komentarz trenera</th>
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

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Janki zweryfikowane</h1>
        </div>
    </div>
    <div class="panel panel-default"  id="panel2">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div id="start_stop" class="table-responsive">
                        <table id="datatable_verified" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Data Dodania</th>
                                <th>Data Ważności</th>
                                <th>Konsultant</th>
                                <th>Telefon</th>
                                <th>Kampania</th>
                                <th>Komentarz</th>
                                <th>Status Janka</th>
                                <th>Weryfikacja Trenera</th>
                                <th>Komentarz trenera</th>
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

@endsection
@section('script')
<script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.select.min.js')}}"></script>
<script>
    $(document).ready(function() {
        table = $('#datatable').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },
            "drawCallback": function (settings) {
            },
            "ajax": {
                'url': "{{ route('api.datatableDkjVerification') }}",
                'type': 'POST',
                'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            }, "columns": [
                {"data": "add_date"},
                {"data": "expiration_date","orderable": false, "searchable": false},
                {
                    "data": function (data, type, dataToSet) {
                        return data.user_first_name + " " + data.user_last_name;
                    }, "name": "user.last_name"
                },
                {"data": "phone"},
                {"data": "campaign"},
                {"data": "comment"},
                {"data":null,"targets": -3,"orderable": false, "searchable": false },
                {"data":null,"targets": -2,"orderable": false, "searchable": false },
                {"data":null,"targets": -1,"orderable": false, "searchable": false },
            ],
            "columnDefs": [ {
                "targets": -3,
                "data": "manager_status",
                "defaultContent": "<select class='form-control my_manager' showhidetext name=manager_status style=border-radius: 0px;>"+
                            "<option value=-1>Wybierz</option>"+
                            "<option value=0>Tak</option>"+
                            "<option value=1>Nie</option>"
            },
                {
                    "targets": -2,
                    "data": "manager_status",
                    "defaultContent": "<textarea class=manager_comment></textarea>"
                },{
                    "targets": -1,
                    "data": "id",
                    "defaultContent": "<button disabled class='button-save btn btn-default my_button' >Zapisz</button>"
                }
                ],
        });


        table_verified = $('#datatable_verified').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },
            "drawCallback": function (settings) {
            },
            "ajax": {
                'url': "{{ route('api.datatableShowDkjVerification') }}",
                'type': 'POST',
                'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            }, "columns": [
                {"data": "add_date"},
                {"data": "expiration_date","orderable": false, "searchable": false},
                {
                    "data": function (data, type, dataToSet) {
                        return data.user_first_name + " " + data.user_last_name;
                    }, "name": "user.last_name"
                },
                {"data": "phone"},
                {"data": "campaign"},
                {"data": "comment"},
                {
                    "data": function (data, type, dataToSet) {
                        if(data.dkj_status == 2)
                            return "Anulowany"
                        else if(data.dkj_status == 1)
                            return "Zatwierdzony"
                        else if(data.dkj_status == 0)
                            return '???'
                    }, "name": "dkj_status"
                },
                {
                    "data": function (data, type, dataToSet) {
                        if(data.manager_status == 1)
                            return "Brak Janka"
                        else if(data.manager_status == 0)
                            return "Janek"
                    }, "name": "manager_status"
                },
                {"data": "comment_manager"},
            ],
        });

    });
    $('#datatable tbody').on('change', '.my_manager', function () {
        var data = table.row( $(this).parents('tr') ).data();
        var manager_status = $(this).closest("tr").find("select[name='manager_status']").val();


        if (manager_status != -1) {
            $(this).closest("tr").find(".my_button").removeAttr('disabled');
        } else {
            $(this).closest("tr").find(".my_button").attr('disabled', true);
        }


    });

    $('#datatable tbody').on('click', '.button-save', function () {
        var data = table.row( $(this).parents('tr') ).data();
        var manager_coment = $(this).closest("tr").find(".manager_comment").val();
        var manager_status = $(this).closest("tr").find("select[name='manager_status']").val();
        var id = data.id;

            $.ajax({
                type: "POST",
                url: '{{ route('api.saveDkjVerification') }}',
                data: {
                    "id": id,
                    "manager_coment": manager_coment,
                    "manager_status": manager_status
                },

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response == 1) {
                        table_verified.ajax.reload();
                        table.ajax.reload();
                    } else {
                        swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem.')
                    }

                }
            });


    });


</script>
@endsection
