@extends('layouts.main')
@section('content')

    <style>

    </style>

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Zarządzaj Pracownikami(Konsultant)</h1>
        </div>
    </div>

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
                                <div class="panel-body">
                                    <table id="datatable">
                                        <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Imię</th>
                                            <th>Nazwisko</th>
                                            <th>Login</th>
                                            <th>Data rozp.</th>
                                            <th>Data zak.</th>
                                            <th>Nr. Tel.</th>
                                            <th>Dok.</th>
                                            <th>Student</th>
                                            <th>Ost. log</th>
                                            <th>Status</th>
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
        </div>
    </div>

@endsection
@section('script')

<script>

    $(document).ready( function () {

        table = $('#datatable').DataTable({
            "autoWidth": false,
            "width": "10%",
            "processing": true,
            "serverSide": true,
            "drawCallback": function( settings ) {
            },
            "ajax": {
                'url': "{{ route('api.datatableEmployeeManagement') }}",
                'type': 'POST',
                'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },"columns":[
                {"data": "id"},
                {"data": "first_name"},
                {"data": "last_name"},
                {"data": "username"},
                {"data": "start_work"},
                {"data": "end_work"},
                {"data": "phone"},
                {"data": "documents"},
                {"data": "student"},
                {"data": "last_login"},
                ]
        });
    });

</script>
@endsection
