@extends('layouts.main')
@section('content')
    {{--*******************************************--}}
    {{--THIS PAGE DISPLAYS TABLE WITH FILLED AUDITS--}}
    {{--*******************************************--}}

    <style>
        td:nth-of-type(5)::after {
            content: '%';
        }

    </style>
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <div class="alert gray-nav">Tabela wykonanych audytów</div>
                </div>
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
                                    <div class="panel-body table-responsive">
                                        @if(Session::has('adnotation'))
                                                    <div class="alert alert-success">{{Session::get('adnotation') }}</div>
                                            @php
                                                Session::forget('adnotation');
                                            @endphp
                                        @endif
                                        <table id="datatable" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th class="search-input-text" data-column="1">Wypełniającey</th>
                                                <th>Trener</th>
                                                <th>Department</th>
                                                <th>Data</th>
                                                <th class="score">Wynik</th>
                                                <th>Podgląd/Edycja</th>
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
    </div>
@endsection
@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(document).ready( function () {

            //ajax reponsible for receiving and displaying data through datatable
            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "searching": false,
                "drawCallback": function( settings ) {
                },
                "ajax": {
                    'url': "{{ route('api.auditTable') }}",
                    'type': 'POST',
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"columns":[
                    {"data": "user_name"},
                    {"data": "trainer"},
                    {"data": "department"},
                    {"data": "date_audit"},
                    {"data": "audit_score"},
                    {"data":function (data, type, dataToSet) {
                            return '<a href="{{URL::to("audit")}}/' + data.audit_id + '">Link</a>';
                        },"orderable": false, "searchable": false
                    }
                ]
            });

            $('.search-input-text').on( 'change', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v = $(this).text()  // getting search input value
                table.columns(i).search(v).draw();
            } );

        });
    </script>
@endsection
