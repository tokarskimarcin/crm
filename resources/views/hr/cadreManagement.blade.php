@extends('layouts.main')
@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <style>
        table{
            font-size: 12px;
            text-align: center;
        }
        .table-striped tr td:first-child + td + td + td + td+ td +td{
            word-break: break-all;
        }
    </style>

{{--Header page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Dział HR / Kadra Informacje</div>
            </div>
        </div>
    </div>

    @if(isset($saved))
        <div class="alert alert-success">
            <strong>Success!</strong> Konto użytkownika {{$saved}} zostało zmodyfikowane.
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Lista Pracowników
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body table-responsive">
                                    <table id="datatable"   class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Imię</th>
                                            <th>Nazwisko</th>
                                            <th>Oddział</th>
                                            <th>Dział</th>
                                            <th>Stanowisko</th>
                                            <th>Nr. Tel.</th>
                                            <th>E-mail</th>
                                            @if(Auth::user()->user_type->all_departments == 1)
                                                <th>Akcja</th>
                                            @endif
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
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>

<script>

    function checkEmail(email) {
        var filter = /^[A-Za-z0-9_\-]+([.][A-Za-z0-9_\-]+)*[@][A-Za-z0-9_\-]+([.][A-Za-z0-9_\-]+)+$/;
        if (!filter.test(email.value)) {
            return false;
        }else
            return true;
    }

    $(document).ready( function () {
        var show_action = {{Auth::user()->user_type->all_departments}};

        table = $('#datatable').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "drawCallback": function( settings ) {
            },
            "ajax": {
                'url': "{{ route('api.datatableCadreManagement') }}",
                'type': 'POST',
                'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },"columns":[
                {"data": "first_name"},
                {"data": "last_name"},
                {"data": "department_name","name":"departments.name"},
                {"data": "department_type_name","name":"department_type.name"},
                {"data": "user_type_name","name":"user_types.name"},
                {"data": "phone"},
                {"data": function (data, type, dataToSet) {
                    var n = data.username.indexOf('@');
                    if(data.username.indexOf('@') != -1  && (data.email_off == '' || data.email_off == null))
                    {
                        return data.username;
                    }else if(data.email_off != '' && data.email_off != null)
                    {
                        return data.email_off;
                    }else {
                        return "Brak informacji";
                    }
                },"orderable": false, "searchable": false },
                {"data": function (data, type, dataToSet) {
                    if(show_action == 1)
                        return '<a href="edit_cadre/'+data.id+'" >Edytuj</a>';
                    else return null;
                },"orderable": false, "searchable": false }
                ]
        });

        $('#datatable_info').text('');
    });

</script>
@endsection
