@extends('layouts.main')
@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <style>
        table{
            font-size: 12px;
            text-align: center;
        }
    </style>

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Zarządzaj Pracownikami(Konsultant)</h1>
        </div>
    </div>

    @if(isset($saved))
        <div class="alert alert-success">
            <strong>Sukces!</strong> Konto użytkownika: {{$saved['first_name'] . ' ' . $saved['last_name']}} zostało zmodyfikowane!
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
                                    <table id="datatable"  class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
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
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
<script>

    $(document).ready( function () {

        table = $('#datatable').DataTable({
            "autoWidth": false,
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
                {"data": "first_name"},
                {"data": "last_name"},
                {"data": "username"},
                {"data": "start_work"},
                {"data": "end_work"},
                {"data": "private_phone"},
                {"data": function (data, type, dataToSet) {
                    if(data.documents == 1)
                        data.documents = "Posiada";
                    else if(data.documents == 0)
                        data.documents = "Brak";
                    return data.documents;
                },"name": "documents"},
                {"data": function (data, type, dataToSet) {
                    if(data.student == 1)
                        data.student = "Tak";
                    else if(data.student == 0)
                        data.student = "Nie";
                    return data.student;
                },"name": "student"},
                {"data": "last_login"},
                {"data": function (data, type, dataToSet) {
                    if(data.status_work == 1)
                        data.status_work = "Pracujący";
                    else if(data.status_work == 0)
                        data.status_work = "Niepracujący";
                    return data.status_work;
                },"name": "status_work"},
                {"data": function (data, type, dataToSet) {
                    return '<a href="edit_consultant/'+data.id+'" >Edytuj</a>'
                },"orderable": false, "searchable": false }
                ]
        });
    });

</script>
@endsection
