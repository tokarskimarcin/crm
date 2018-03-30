@extends('layouts.main')
@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <style>
        table{
            font-size: 12px;
            text-align: center;
        }
        th input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }
        select{
            color: black;
        }

    </style>

{{--Header page --}}

    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Dział HR / Zarządzaj Pracownikami</div>
            </div>
        </div>
    </div>
    @if(Session::has('adnotation'))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success">{{Session::get('adnotation') }}</div>
            </div>
        </div>
        @php
            Session::forget('adnotation');
        @endphp
    @endif
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
                                    <table id="datatable"  class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Imię</th>
                                            <th>Nazwisko</th>
                                            <th>Lider</th>
                                            <th>Data rozp.</th>
                                            <th>Data zak.</th>
                                            <th>Nr. Tel.</th>
                                            <th>
                                                <label>Dokumenty</label><br>
                                                <select class="search-input-text" data-column="6">
                                                    <option value=""></option>
                                                    <option value="">Posiada</option>
                                                    <option value="">Brak</option>
                                                </select>
                                            </th>
                                            <th>
                                                <label>Student</label><br>
                                                <select class="search-input-text" data-column="7">
                                                    <option value=""></option>
                                                    <option value="">Tak</option>
                                                    <option value="">Nie</option>
                                                </select>
                                            </th>
                                            <th>Ost. log</th>
                                            {{--<th><input type="text" data-column="9"  class="search-input-text"></th>--}}
                                            <th>
                                                <label>Status</label><br>
                                                <select class="search-input-text" data-column="9">
                                                    <option value=""></option>
                                                    <option value="">Pracujący</option>
                                                    <option value="">Niepracujący</option>
                                                </select>
                                            </th>
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
            "autoWidth": true,
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
                {"data": function (data, type, dataToSet) {
                        if(data.coach_first_name == null || data.coach_last_name == null){
                            return '';
                        }else
                            return data.coach_first_name+' '+data.coach_last_name;
                    },"name": "coach.last_name"
                },
                {"data": "start_work"},
                {"data": "end_work"},
                {"data": "private_phone"},
                {"data": function (data, type, dataToSet) {
                    console.log(data);
                    if(data.documents == 1)
                        data.documents = "Posiada";
                    else if(data.documents == 0)
                        data.documents = "Brak";
                    return data.documents;
                },"orderable": false,"name": "documents"},
                {"data": function (data, type, dataToSet) {
                    if(data.student == 1)
                        data.student = "Tak";
                    else if(data.student == 0)
                        data.student = "Nie";
                    return data.student;
                },"orderable": false,"name": "student"},
                {"data": "last_login"},
                {"data": function (data, type, dataToSet) {
                    if(data.status_work == 1)
                        data.status_work = "Pracujący";
                    else if(data.status_work == 0)
                        data.status_work = "Niepracujący";
                    return data.status_work;
                },"orderable": false,"name": "status_work"},
                {"data": function (data, type, dataToSet) {
                    return '<a href="edit_consultant/'+data.id+'" >Edytuj</a>'
                },"orderable": false, "searchable": false }
                ]
        });

        $('.search-input-text').on( 'change', function () {   // for text boxes
            var i =$(this).attr('data-column');  // getting column index
            var v = $(this).find("option:selected").text()  // getting search input value
            table.columns(i).search(v).draw();
        } );
    });

</script>
@endsection
