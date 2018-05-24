@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

    {{--Header page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Panel Klientów</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Zarządzanie klientami
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button data-toggle="modal" class="btn btn-default" id="clietnModal" data-target="#ModalClient" data-id="1" title="Nowy Klient" style="margin-bottom: 14px">
                                <span class="glyphicon glyphicon-plus"></span> <span>Dodaj Klienta</span>
                            </button>
                            <table id="datatable" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nazwa</th>
                                    <th>Priorytet</th>
                                    <th>Typ</th>
                                    <th style="text-align: center">Akcja</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--<tr id="clientId_1">--}}
                                    {{--<td class="client_name">Exito - Vigor Life</td>--}}
                                    {{--<td class="client_phone">798987985</td>--}}
                                    {{--<td class="client_type">Kamery</td>--}}
                                    {{--<td>--}}
                                        {{--<button class="btn btn-info"  data-id=1 onclick = "edit_client(this)" >Edycja</button>--}}
                                        {{--<button class="btn btn-danger" data-id=1 onclick = "edit_client(this)" >Wyłącz</button>--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--<input style="display: inline-block;" type="checkbox" class="client_check"/>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--MODAL Dodaj Klienta--}}
    <div id="Modal_Client" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal_title">Dodaj nowego klienta<span id="modal_category"></span></h4>
                </div>
                <div class="modal-body">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Nowy Klient
                        </div>
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Nazwa Klienta</label>
                                        <input class="form-control" name="client_name" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Priorytet</label>
                                        <input class="form-control" name="priority" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Typ</label>
                                        <select class="form-control" id="client_type">
                                            <option>Wybierz</option>
                                            <option>Badania</option>
                                            <option>Wysyłka</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-success form-control" id="save_client_modal" onclick = "save_client(this)" >Zapisz Klienta</button>
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

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $('.form_date').datetimepicker({
            language:  'pl',
            autoclose: 1,
            minView : 2,
            pickTime: false
        });

        $(document).ready(function() {

            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },
                "ajax": {
                    'url': "{{ route('api.getClient') }}",
                    'type': 'POST',
                    'data': function (d) {
                        // d.date_start = $('#date_start').val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"rowCallback": function( row, data, index ) {
                    if (data.status == 1) {
                        $(row).css('background','#c500002e')
                    }
                    $(row).attr('id', data.id);
                    return row;
                }
            });

            let mainContainer = document.querySelector('.routes-wrapper'); //zaznaczamy główny container
            function clear_modal() {
                // document.getElementsByName('client_name')[0]
                // document.getElementsByName('client_phone')[0].value ='';
                // document.getElementsByName('client_type')[0].value ='Wybierz';
                // console.log(document.getElementsByName('client_name')[0]);
            }

            function edit_client(e) {
                var client_id = e.getAttribute('data-id');
                var tr_line = e.closest('tr');
                var tr_line_name = tr_line.getElementsByClassName('client_name')[0].textContent;
                var tr_line_phone = tr_line.getElementsByClassName('client_phone')[0].textContent;
                var tr_line_type = tr_line.getElementsByClassName('client_type')[0].textContent;
                clear_modal();
                $('#Modal_Client').modal('show');
                console.log(tr_line);
            }

            function save_client(e) {
                alert('Klient dodany');
                $('#Modal_Client').modal('hide');
            }
        });
    </script>
@endsection
