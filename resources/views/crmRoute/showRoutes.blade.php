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
                    <div class="alert alert-info">
                        Moduł <strong>Szablony tras</strong> służy do edycji, podglądu oraz tworzenia szablonów tras, które później mogą być wykorzystane
                        w zakładkach <strong>Przypisanie trasy do klienta</strong> oraz przy <strong>Edycji trasy przypisanej do klienta</strong>.
                        Nazwa trasy posiada specjalne znaczniki: <br><strong>|</strong> - oznaczający, że pomiędzy kolejnymi miastami jest <i>jeden</i> dzień różnicy.
                        <br><strong>+</strong>  - oznaczający, że miasta są odwiedzane <i>tego samego</i> dnia.
                    </div>
                    <div class="row" >
                            <div class="col-md-12">
                                <button id="addNewRoute" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span>
                                    Przejdz do tworzenia szablonów tras</button>
                            </div>
                    </div>
                    <div class="row" style="margin-top: 1em;">
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
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function (event) {
            const addNewRouteInput = document.querySelector('#addNewRoute');
            addNewRouteInput.addEventListener('click', (e) => {
                window.location.href = '{{URL::to('/addNewRouteTemplate')}}';
            });
            let table = $('#datatable').DataTable({
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
                            return '<a href="{{URL::to("editRouteTemplates")}}/' + data.id + '" style="text-decoration:none;" class="links"><button class="btn btn-block btn-info" style="width: 50%; display: inline !important;"><span class="glyphicon glyphicon-edit"></span> Edycja</button></a>  <button id="removeTemplateButton" class="btn btn-block btn-danger" data-templateid="' + data.id + '" style="width: 40%; display: inline !important;"><span class="glyphicon glyphicon-delete"></span> Usuń</button>';
                        }, "orderable": false, "searchable": false, "width": "20%"
                    }
                ]
            });

            function globalClickHandler(e) {
                if(e.target.matches('#removeTemplateButton')) { //remove route template
                    swal({
                        title: 'Jesteś pewien?',
                        text: "Brak możliwości cofnięcia zmian!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Tak, usuń!'
                    }).then((result) => {
                        if (result.value) {
                            let templateId = e.target.dataset.templateid;
                            const ourHeaders = new Headers();
                            ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                            ourHeaders.set('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                            let sendData = new FormData();
                            sendData.append('templateId', templateId);

                            fetch('{{route('api.deleteRouteTemplate')}}', {
                                method: 'post',
                                headers: ourHeaders,
                                body: sendData,
                                credentials: "same-origin"
                            })
                                .then(resp => resp.text())
                                .then(resp => {
                                    swal(
                                        'Usunięto!',
                                        resp,
                                        'success'
                                    )
                                    table.ajax.reload();
                                })
                        }
                    })
                }
            }

            document.addEventListener('click', globalClickHandler);
        });


    </script>
@endsection
