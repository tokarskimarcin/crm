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

    {{--MODAL Dodaj Klienta--}}
    <div id="ModalClient" class="modal fade" role="dialog">
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
                                        <input class="form-control" name="clientName" id="clientName" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Priorytet</label>
                                        <select class="form-control" id="clientPriority">
                                            <option value="0">Wybierz</option>
                                            <option value="1">Niski</option>
                                            <option value="2">Średni</option>
                                            <option value="3">Wysoki</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Typ</label>
                                        <select class="form-control" id="clientType">
                                            <option value="0">Wybierz</option>
                                            <option>Badania</option>
                                            <option>Wysyłka</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-success form-control" id="saveClient" onclick = "saveClient(this)" >Zapisz Klienta</button>
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
    <input type="hidden" value="0" id="clientID" />
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

        function clearModal() {
            $('#clientName').val("");
            $('#clientPriority').val("0");
            $('#clientType').val("1");
            $('#clientID').val(0);
        }
        //Zapisanie klienta
        function saveClient(e) {
            let clientName = $('#clientName').val();
            let clientPriority = $('#clientPriority').val();
            let clientType = $('#clientType').val();
            let clientID = $('#clientID').val();
            let validation = true;
            if(clientName.trim().length == 0){
                validation = false;
                swal("Podaj nazwę klienta")
            }
            if(clientPriority == 0){
                validation = false;
                swal("Wybierz priorytet klienta")
            }
            if(clientType == 0){
                validation = false;
                swal("Wybierz typ klienta")
            }
            if(validation){
                $.ajax({
                    type: "POST",
                    url: "{{route('api.saveClient')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'clientName'    : clientName,
                        'clientPriority': clientPriority,
                        'clientType'    : clientType,
                        'clientID'      : clientID
                    },
                    success: function (response) {
                        $('#ModalClient').modal('hide');
                    }
                })
            }
        }


        $(document).ready(function() {
            $('#ModalClient').on('hidden.bs.modal',function () {
                $('#clientID').val("0");
                clearModal();
                table.ajax.reload();
            });

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
                },"fnDrawCallback": function(settings){
                    /**
                     * Zmiana statusu klienta
                     */
                    $('.button-status-client').on('click',function () {
                        let clientId = $(this).data('id');
                        let clienStatus = $(this).data('status');
                        let nameOfAction = "";
                        if(clienStatus == 0)
                            nameOfAction = "Tak, wyłącz Klienta";
                        else
                            nameOfAction = "Tak, włącz Klienta";
                        swal({
                            title: 'Jesteś pewien?',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: nameOfAction
                        }).then((result) => {
                            if (result.value) {

                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('api.changeStatusClient') }}", // do zamiany
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: {
                                        'clientId'   : clientId
                                    },
                                    success: function (response) {
                                        table.ajax.reload();
                                    }
                                });
                            }})
                    });

                    /**
                     * Educja coachingu
                     */
                    $('.button-edit-client').on('click',function () {
                        clientId = $(this).data('id');
                        $.ajax({
                            type: "POST",
                            url: "{{ route('api.findClient') }}", // do zamiany
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'clientId'         : clientId
                            },
                            success: function (response) {
                                console.log(response);
                                clearModal();
                                $('#clientName').val(response.name);
                                $('#clientPriority').val(response.priority);
                                $('#clientType').val(response.type);
                                $('#clientID').val(response.id);
                                $('#ModalClient').modal('show');
                            }
                        });
                    });
                },"columns":[
                    {"data":"name"},
                    {
                        "data": function (data, type, dataToSet) {
                            if(data.priority == 1){
                                return "Niski";
                            }else if(data.priority == 2){
                                return "Średni"
                            }else{
                                return "Wysoki";
                            }
                        },"name": "priority"
                    },
                    {"data":"type"},
                    {"data":function (data, type, dataToSet) {
                            let returnButton = "<button class='button-edit-client btn btn-warning' style='margin: 3px;' data-id="+data.id+">Edycja</button>";
                            if(data.status == 0)
                                returnButton += "<button class='button-status-client btn btn-danger' data-id="+data.id+" data-status=0 >Wyłącz</button>";
                            else
                                returnButton += "<button class='button-status-client btn btn-success' data-id="+data.id+" data-status=1 >Włącz</button>";
                            return returnButton;
                        },"orderable": false, "searchable": false
                    }
                    ],
            });
        });
    </script>
@endsection
