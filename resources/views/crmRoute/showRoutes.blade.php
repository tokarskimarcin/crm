@extends('layouts.main')
@section('style')

@endsection
@section('content')



    {{--Header page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Podgląd Szablonów Tras</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Wybierz trasę
                </div>
                <div class="panel-body">
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
                    <div class="row" >
                            <div class="col-md-12">
                                <button id="addNewRoute" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span>
                                    Przejdz do dodawania tras</button>
                            </div>
                    </div>
                    <div class="row" style="margin-top: 1em">
                        <div class="col-md-12">
                            <table id="datatable" class="thead-inverse table table-striped row-border"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nazwa</th>
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
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function (event) {
            const addNewRouteInput = document.querySelector('#addNewRoute');
            addNewRouteInput.addEventListener('click', (e) => {
                window.location.href = '{{URL::to('/addNewRoute')}}';
            });
            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function (settings) {
                },
                "ajax": {
                    'url': "{{ route('api.showRoutesAjax') }}",
                    'type': 'POST',
                    'data': function (d) {
                        // d.date_start = $('#date_start').val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                }, "columns": [
                    {
                        "data": function (data, type, dataToSet) {
                            return data.name;
                        }, "name": "name", "orderable": true
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            return '<a href="{{URL::to("route")}}/' + data.id + '" style="text-decoration:none;" class="links"><button class="btn btn-block btn-info"><span class="glyphicon glyphicon-edit"></span> Edycja</button></a>';
                        }, "orderable": false, "searchable": false, "width": "20%"
                    }
                ]
            });
        });
    </script>
@endsection
