@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <style>
        .button-edit-meeting,.button-edit-meeting{
            width: max-content;
        }
    </style>
@endsection
@section('content') {{--Header page --}}
<div class="row"><div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Panel Klientów</div>
        </div>
    </div>
</div>
<div class="row"><div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Zarządzanie klientami</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <button data-toggle="modal" class="btn btn-default" id="clientModal" data-target="#ModalClient" data-id="1" title="Nowy Klient" style="margin-bottom: 14px">
                            <span class="glyphicon glyphicon-plus"></span> <span>Dodaj Klienta</span>
                        </button>
                        <button data-toggle="modal" class="btn btn-primary" id="clientParameterModal" data-target="#ModalClientParameter" data-id="1" title="Parametry klientów" style="margin-bottom: 14px">
                            <span class="glyphicon glyphicon-info-sign"></span> <span>Parametry Klientów</span>
                        </button>
                        <table id="datatable" class="thead-inverse table table-striped row-border" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Nazwa klienta (Umawianie)</th>
                                <th>Uwaga</th>
                                <th>Podgląd</th>
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
{{--MODAL Parametrów klientów--}}
<div id="ModalClientParameter" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_title">Parametry klientów<span id="modal_category"></span></h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Parametry klientów
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            Upominki
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <div class="newGift" style="margin-bottom: 1%">
                                                    <div class="col-md-12">
                                                            <div class="form-inline">
                                                                <label>Dodaj nowy upominek do listy</label>
                                                                <input type="text" class="form-control" name="NewGiftName" id="NewGiftName" placeholder="Upominek..."/>
                                                                <button type="submit" class="btn btn-success" id="giftTypeNameBTN" value="Zapisz">
                                                                    <span class="glyphicon glyphicon-save"></span> <span>Zapisz</span>
                                                                </button>
                                                            </div>
                                                    </div>
                                                </div>
                                                <table id="giftTable" class="thead-inverse table table-striped row-border" cellspacing="0" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Nazwa</th>
                                                        <th>Edycja</th>
                                                        <th>Włącz/Wyłącz</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            Typy pokazów
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <div class="newGift">
                                                    <div class="col-md-12">
                                                        <div class="form-inline">
                                                            <label>Dodaj nowy typ trasy do listy</label>
                                                            <input type="text" class="form-control" name="NewMeetingTypeName" id="NewMeetingTypeName" placeholder="Typ trasy..."/>
                                                            <button type="submit" class="btn btn-success" id="meetingTypeNameBTN" value="Zapisz">
                                                                <span class="glyphicon glyphicon-save"></span> <span>Zapisz</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <table id="meetingTable" class="thead-inverse table table-striped row-border" cellspacing="0" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Nazwa</th>
                                                        <th>Edycja</th>
                                                        <th>Włącz/Wyłącz</th>
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
                        Formularz
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="myLabel">Nazwa klienta na umawianie</label>
                                            <input class="form-control" name="clientName" id="clientName" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="myLabel">Nazwa klienta do faktury</label>
                                            <input class="form-control" name="clientNameInvoice" id="clientNameInvoice" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="myLabel">Rodzaj Spotkania</label>
                                            <select class="form-control" id="clientMeetingType">
                                                <option value="0">Wybierz</option>
                                                @foreach($clientMeetingType as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="myLabel">Upominek</label>
                                            <select class="form-control" id="clientGiftType">
                                                <option value="0">Wybierz</option>
                                                @foreach($clientGiftType as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
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
                            </div>



                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Kontakt
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="myLabel">Płatności (telefon)</label>
                                                <input class="form-control" name="clientPaymentPhone" id="clientPaymentPhone" />
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="myLabel">Płatności (mail)</label>
                                                <input class="form-control" name="clientPaymentMail" id="clientPaymentMail" />
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="myLabel">Awarie (telefon)</label>
                                                <input class="form-control" name="clientFailuresPhone" id="clientFailuresPhone" />
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="myLabel">Awarie (mail)</label>
                                                <input class="form-control" name="clientFailuresMail" id="clientFailuresMail" />
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="myLabel">Grafiki (telefon)</label>
                                                <input class="form-control" name="clientSchedulePhone" id="clientSchedulePhone" />
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="myLabel">Grafik (mail)</label>
                                                <input class="form-control" name="clientScheduleMail" id="clientScheduleMail" />
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="myLabel">Szef (telefon)</label>
                                                <input class="form-control" name="clientManagerPhone" id="clientManagerPhone" />
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="myLabel">Szef (mail)</label>
                                                <input class="form-control" name="clientManagersMail" id="clientManagersMail" />
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="myLabel">Uwagi</label>
                                    <textarea class="form-control" name="clientComment" id="clientComment"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 editButtonSection">
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

        let editFlag = false;
        let saveButton = document.querySelector('#saveClient');
        let modalTitle = document.querySelector('#modal_title');
        var saveCityButtonClicked = false;

        /**
         * This function shows notification.
         */
        function notify(htmltext$string, type$string = info, delay$miliseconds$number = 5000) {
            $.notify({
                // options
                message: htmltext$string
            },{
                // settings
                type: type$string,
                z_index: 2000,
                delay: delay$miliseconds$number,
                animate: {
                    enter: 'animated fadeInRight',
                    exit: 'animated fadeOutRight'
                }
            });
        }

        function prepereModel(type) {
            if(type == 'Edit'){
                $('#ModalClient .modal-title').first().text('Edycja klienta');
                let saveClientModalButton = $('#ModalClient #saveClient');
                saveClientModalButton.first().show();
                saveClientModalButton.first().prop('class','btn btn-success form-control');
                saveClientModalButton.first().text('');
                saveClientModalButton.append($('<span class="glyphicon glyphicon-save"></span>'));
                saveClientModalButton.append(' Zapisz Klienta');
            }else if(type == 'Show'){
                $('#ModalClient .modal-title').first().text('Informacje o kliencie');
                let saveClientModalButton = $('#ModalClient #saveClient');
                saveClientModalButton.first().hide();
            }else if (type == 'Add'){
                $('#ModalClient .modal-title').first().text('Nowy klient');
                let saveClientModalButton = $('#ModalClient #saveClient');
                saveClientModalButton.first().show();
                saveClientModalButton.first().prop('class','btn btn-success form-control');
                saveClientModalButton.first().text('');
                saveClientModalButton.append($('<span class="glyphicon glyphicon-save"></span>'));
                saveClientModalButton.append(' Zapisz Klienta');
            }
        }
        //Pobranie informacji o kliencie i wstawienie go do modala
        function getInfoAboutClient(clientId) {
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
                    clearModal();
                    $('#clientName').val(response.name);
                    $('#clientPriority').val(response.priority);
                    $('#clientType').val(response.type);
                    $('#clientID').val(response.id);
                    $('#clientNameInvoice').val(response.invoice_name);
                    $('#clientMeetingType').val(response.meeting_type_id);
                    $('#clientGiftType').val(response.gift_type_id);
                    $('#clientPaymentPhone').val(response.payment_phone);
                    $('#clientPaymentMail').val(response.payment_mail);
                    $('#clientFailuresPhone').val(response.failures_phone);
                    $('#clientFailuresMail').val(response.failures_mail);
                    $('#clientSchedulePhone').val(response.schedule_phone);
                    $('#clientScheduleMail').val(response.schedule_mail);
                    $('#clientManagerPhone').val(response.manager_phone);
                    $('#clientManagersMail').val(response.manager_mail);
                    $('#clientComment').val(response.comment);
                    $('#ModalClient').modal('show');
                    editFlag = true;
                    modalTitle.textContent = "Edytuj klienta";
                }
            });
        }
        function clearModal() {
            $('#clientName').val("");
            $('#clientNameInvoice').val("");
            $('#clientMeetingType').val("0");
            $('#clientGiftType').val("0");
            $('#clientPaymentPhone').val("");
            $('#clientPaymentMail').val("");
            $('#clientFailuresPhone').val("");
            $('#clientFailuresMail').val("");
            $('#clientSchedulePhone').val("");
            $('#clientScheduleMail').val("");
            $('#clientManagerPhone').val("");
            $('#clientManagersMail').val("");
            $('#clientComment').val("");
            $('#clientPriority').val("0");
            $('#clientType').val("1");
            $('#clientID').val(0);
        }
        /**
         * This function validate phone input
         * @param e
         */
        $('#clientPaymentPhone,#clientFailuresPhone,#clientSchedulePhone,#clientManagerPhone').on("input propertychange",function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        //Zapisanie klienta
        function saveClient(e) {
            saveCityButtonClicked       = true;
            let clientName              = $('#clientName').val();
            let clientPriority          = $('#clientPriority').val();
            let clientType              = $('#clientType').val();
            let clientID                = $('#clientID').val();
            let clientNameInvoice       = $('#clientNameInvoice').val();
            let clientMeetingType       = $('#clientMeetingType').val();
            let clientGiftType          = $('#clientGiftType').val();
            let clientPaymentPhone      = $('#clientPaymentPhone').val();
            let clientPaymentMail       = $('#clientPaymentMail').val();
            let clientFailuresPhone     = $('#clientFailuresPhone').val();
            let clientFailuresMail      = $('#clientFailuresMail').val();
            let clientSchedulePhone     = $('#clientSchedulePhone').val();
            let clientScheduleMail      = $('#clientScheduleMail').val();
            let clientManagerPhone      = $('#clientManagerPhone').val();
            let clientManagersMail      = $('#clientManagersMail').val();
            let clientComment           = $('#clientComment').val();

            let validation = true;

            if(clientNameInvoice.trim().length == 0 || clientNameInvoice == ''){
                validation = false;
                swal("Podaj nazwę klienta (Faktura)")
            }
            if(clientPaymentPhone.trim().length == 0 || clientNameInvoice == ''){
                validation = false;
                swal("Podaj telefon kontaktowy (płatności)")
            }
            if(clientPaymentMail.trim().length == 0 || clientNameInvoice == ''){
                validation = false;
                swal("Podaj mail kontaktowy (płatności)")
            }
            if(clientFailuresPhone.trim().length == 0 || clientNameInvoice == ''){
                validation = false;
                swal("Podaj telefon kontaktowy (awarie)")
            }
            if(clientFailuresMail.trim().length == 0 || clientNameInvoice == ''){
                validation = false;
                swal("Podaj mail kontaktowy (awarie)")
            }
            if(clientSchedulePhone.trim().length == 0 || clientNameInvoice == ''){
                validation = false;
                swal("Podaj telefon kontaktowy (grafik)")
            }if(clientScheduleMail.trim().length == 0 || clientNameInvoice == ''){
                validation = false;
                swal("Podaj mail kontaktowy (grafik)")
            }
            if(clientManagerPhone.trim().length == 0 || clientNameInvoice == ''){
                validation = false;
                swal("Podaj telefon kontaktowy (Szef)")
            }
            if(clientManagersMail.trim().length == 0 || clientNameInvoice == ''){
                validation = false;
                swal("Podaj mail kontaktowy (Szef)")
            }
            if(clientMeetingType == 0){
                validation = false;
                swal("Wybierz typ spotkań")
            }
            if(clientGiftType == 0){
                validation = false;
                swal("Wybierz typ prezentów")
            }
            if(clientName.trim().length == 0 || clientNameInvoice == ''){
                validation = false;
                swal("Podaj nazwę klienta (umawianie)")
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
                saveButton.disabled = true; //after first click, disable button
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
                        'clientID'      : clientID,
                        'clientComment' : clientComment,
                        'clientNameInvoice' : clientNameInvoice,
                        'clientMeetingType' : clientMeetingType,
                        'clientGiftType'    : clientGiftType,
                        'clientPaymentPhone': clientPaymentPhone,
                        'clientPaymentMail' : clientPaymentMail,
                        'clientFailuresPhone' : clientFailuresPhone,
                        'clientFailuresMail'  : clientFailuresMail,
                        'clientSchedulePhone' : clientSchedulePhone,
                        'clientScheduleMail'  : clientScheduleMail,
                        'clientManagerPhone'  : clientManagerPhone,
                        'clientManagersMail'  : clientManagersMail,
                    },
                    success: function (response) {
                        if(editFlag == false) {
                            notify('<strong>Klient został pomyślnie dodany</strong>', 'success');
                        }
                        else {
                            notify('<strong>Klient został pomyślnie edytowany</strong>', 'success');
                            editFlag = false;
                            modalTitle.textContent = 'Dodaj nowego klienta';
                        }
                        $('#ModalClient').modal('hide');
                        saveButton.disabled = false; //after closing modal, enable button
                    }
                })
            }
        }

        $('#clientModal').click(() => {
            prepereModel('Add');
        });

        $(document).ready(function() {

            $('#giftTypeNameBTN').on('click',function () {
                let newGistName = $('#NewGiftName').val();
                let validate = true;
                if(newGistName.trim().length == 0 || newGistName == '' ){
                    validate = false;
                }
                if(validate){
                    swal({
                        title: 'Chcesz zapisać nowy upominek?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "Tak zapisz"
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('api.saveNewGift') }}", // do zamiany
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    'name'   : newGistName
                                },
                                success: function (response) {
                                    $('#NewGiftName').val("");
                                    notify("<strong>Upominek został dodany</strong>", 'success');
                                    giftTable.ajax.reload();
                                }
                            });
                        }})
                }else{
                    swal("Przed zapisem podaj nazwę nowego upominku")
                }
                //zapisane nowego upominku
            });

            $('#meetingTypeNameBTN').on('click',function () {
                let newMeetingName = $('#NewMeetingTypeName').val();
                let validate = true;
                if(newMeetingName.trim().length == 0 || newMeetingName == '' ){
                    validate = false;
                }
                if(validate){
                    swal({
                        title: 'Chcesz zapisać nowy typ pokazu?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "Tak zapisz"
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('api.saveNewMeeting') }}", // do zamiany
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    'name'   : newMeetingName
                                },
                                success: function (response) {
                                    $('#NewMeetingTypeName').val("");
                                    notify("<strong>Nowy typ pokazu zostałdodany</strong>", 'success');
                                    meetingTable.ajax.reload();
                                }
                            });
                        }})
                }else{
                    swal("Przed zapisem podaj nazwę nowego typu pokazu")
                }
            });

            $('#ModalClient').on('hidden.bs.modal',function () {
                $('#clientID').val("0");
                clearModal();
                if (saveCityButtonClicked) {
                    table.ajax.reload();
                    saveCityButtonClicked = false;
                }
            });
            var giftTable  = $('#giftTable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function (settings) {
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },
                "ajax": {
                    'url': "{{ route('api.getGiftType') }}",
                    'type': 'POST',
                    'data': function (d) {
                        // d.date_start = $('#date_start').val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"fnDrawCallback": function(settings){

                /**
                 * Zmiana statusu upominku
                 */
                $('.button-gift-status').on('click',function () {
                    let giftId = $(this).data('id');
                    let giftStatus = $(this).data('status');
                    let nameOfAction = "";
                    if(giftStatus == 0)
                        nameOfAction = "Tak, wyłącz upominek";
                    else
                        nameOfAction = "Tak, włącz upominek";
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
                                url: "{{ route('api.changeGiftStatus') }}", // do zamiany
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    'giftId'   : giftId
                                },
                                success: function (response) {
                                    notify("<strong>Status upominka został zmieniony</strong>", 'success');
                                    giftTable.ajax.reload();
                                }
                            });
                        }})
                });
                /**
                 * Edycja upominku
                 */
                $('.button-edit-gift').on('click',function () {
                    let actualAction = $(this).data('type');
                    let giftId = $(this).data('id');
                    if(actualAction == 1){
                        let row = $(this).closest('tr');
                        let cel = row.find("td:first");
                        let celVal = cel.text();
                        $(this).toggleClass('btn-info btn-success');
                        cel.html("<input type='text' class='form-control'  value="+celVal+" />");
                        $(this).html("<span class='glyphicon glyphicon-save'></span> <span>Zapisz</span>");
                        $(this).data('type',2);
                    }else if(actualAction == 2){
                        let element = $(this);
                        let row = $(this).closest('tr');
                        let cel = row.find("td:first");
                        let celInput = cel.find("input");
                        let celVal = celInput.val();
                        let validate = true;
                        if(celVal.trim().length == 0 || celVal == ''){
                            validate = false;
                            swal("Podaj nazwę upominku")
                        }
                        if(validate){
                            element.attr("disabled", true);
                            $.ajax({
                                type: "POST",
                                url: '{{ route('api.editGift') }}',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    "name"  : celVal,
                                    "id"    : giftId,
                                },
                                success: function (response) {
                                    element.toggleClass('btn-success btn-info');
                                    element.attr("disabled", false);
                                    notify("<strong>Zapisano zmiany</strong>", 'success');
                                    cel.html(celVal);
                                    element.html("<span class='glyphicon glyphicon-edit'></span> <span>Edycja</span>");
                                    element.data('type',1);
                                }
                            });
                        }

                    }
                });

            },"columns":[
                {"data":"name","width": "100%"},
                {
                    "data": function (data, type, dataToSet) {
                        return "<button class='button-edit-gift btn btn-info btn-block' data-id="+data.id+"  data-type='1' ><span class='glyphicon glyphicon-edit'></span> Edycja</button>";
                    }
                },
                    {"data":function (data, type, dataToSet) {
                            let returnButton = "";
                            if(data.status == 1)
                                returnButton += "<button class='button-gift-status btn btn-danger btn-block' data-id="+data.id+" data-status=0><span class='glyphicon glyphicon-off'></span> Wyłącz</button>";
                            else
                                returnButton += "<button class='button-gift-status btn btn-success btn-block' data-id="+data.id+" data-status=1 ><span class='glyphicon glyphicon-off'></span> Włącz</button>";
                            return returnButton;
                        },"orderable": false, "searchable": false,"width": "10%"
                    }
                ]
            });
             var meetingTable  = $('#meetingTable').DataTable({
                "processing": true,
                 "autoWidth": true,
                "serverSide": true,
                "drawCallback": function (settings) {
                },
                 "language": {
                     "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                 },
                "ajax": {
                    'url': "{{ route('api.getMeetingType') }}",
                    'type': 'POST',
                    'data': function (d) {
                        // d.date_start = $('#date_start').val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },"fnDrawCallback": function(settings){
                     /**
                      * Zmiana statusu typu pokazu
                      */
                     $('.button-status-meeting').on('click',function () {
                         let meeting = $(this).data('id');
                         let meetingStatus = $(this).data('status');
                         let nameOfAction = "";
                         if(meetingStatus == 0)
                             nameOfAction = "Tak, wyłącz upominek";
                         else
                             nameOfAction = "Tak, włącz upominek";
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
                                     url: "{{ route('api.changeMeetingStatus') }}", // do zamiany
                                     headers: {
                                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                     },
                                     data: {
                                         'meetingId'   : meeting
                                     },
                                     success: function (response) {
                                         notify("<strong>Status upominka został zmieniony</strong>", 'success');
                                         meetingTable.ajax.reload();
                                     }
                                 });
                             }})
                     });
                     /**
                      * Edycja typu trasy
                      */
                     $('.button-edit-meeting').on('click',function () {
                         let actualAction = $(this).data('type');
                         let meetingId = $(this).data('id');
                         let element = $(this);
                         if(actualAction == 1){
                             $(this).toggleClass('btn-info btn-success');
                             let row = $(this).closest('tr');
                             let cel = row.find("td:first");
                             let celVal = cel.text();
                             cel.html("<input type='text' class='form-control'  value="+celVal+" />");
                             $(this).html("<span class='glyphicon glyphicon-save'></span> <span>Zapisz</span>");
                             $(this).data('type',2);
                         }else if(actualAction == 2){
                             let row = $(this).closest('tr');
                             let cel = row.find("td:first");
                             let celInput = cel.find("input");
                             let celVal = celInput.val();
                             let validate = true;
                             if(celVal.trim().length == 0 || celVal == ''){
                                 validate = false;
                                 swal("Podaj nazwę typu poazau")
                             }
                             if(validate){
                                 element.attr("disabled", true);
                                 $.ajax({
                                     type: "POST",
                                     url: '{{ route('api.editMeeting') }}',
                                     headers: {
                                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                     },
                                     data: {
                                         "name"  : celVal,
                                         "id"    : meetingId,
                                     },
                                     success: function (response) {
                                         element.toggleClass('btn-success btn-info');
                                         element.attr("disabled", false);
                                         notify("<strong>Zapisano zmiany</strong>", 'success');
                                         cel.html(celVal);
                                         element.html("<span class='glyphicon glyphicon-edit'></span> <span>Edycja</span>");
                                         element.data('type',1);
                                     }
                                 });
                             }
                         }
                     });

                 },"columns":[
                    {"data":"name","width": "100%"},
                     {
                         "data": function (data, type, dataToSet) {
                            return "<button class='button-edit-meeting btn btn-info btn-block' data-id="+data.id+" data-type='1'><span class='glyphicon glyphicon-edit'></span> Edycja</button>";
                         }
                     },
                    {"data":function (data, type, dataToSet) {
                            let returnButton = "";
                            if(data.status == 1)
                                returnButton += "<button class='button-status-meeting btn btn-danger btn-block' data-id="+data.id+" data-status=0><span class='glyphicon glyphicon-off'></span> Wyłącz</button>";
                            else
                                returnButton += "<button class='button-status-meeting btn btn-success btn-block' data-id="+data.id+" data-status=1 ><span class='glyphicon glyphicon-off'></span> Włącz</button>";
                            return returnButton;
                        },"orderable": false, "searchable": false, "width": "10%"
                    }
                ]
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
                                        notify("<strong>Status klienta został zmieniony</strong>", 'success');
                                        table.ajax.reload();
                                    }
                                });
                            }})
                    });

                    /**
                     * This part is responsible for aplaying default heading to modal.
                     */
                    $("#ModalClient").on('hidden.bs.modal', function () {
                        modalTitle.textContent = "Dodaj nowego klienta";
                    });


                    /**
                     * Edycja clienta
                     */
                    $('.button-edit-client').on('click',function () {
                        prepereModel('Edit');
                        clientId = $(this).data('id');
                        getInfoAboutClient(clientId);
                    });
                    /**
                     *  Wyświetlenie informacji o kliencie bez klawisza zapisz
                     */
                    $('.show-client').on('click',function () {
                        prepereModel('Show');
                        clientId = $(this).data('id');
                        getInfoAboutClient(clientId);
                    });
                },"columns":[
                    {"data":"name"},
                    {"data":"comment"},
                    {
                        "data": function (data, type, dataToSet) {
                            return "<button class='btn'><span  style='font-size: 30px;' class='glyphicon glyphicon-search show-client' data-id="+data.id+"></span></button>";
                        },"orderable": false, "searchable": false,
                    },
                    {"data":function (data, type, dataToSet) {
                            let returnButton = "<button class='button-edit-client btn btn-info btn-block' data-id="+data.id+"><span class='glyphicon glyphicon-edit'></span> Edycja</button>";
                            if(data.status == 0)
                                returnButton += "<button class='button-status-client btn btn-danger btn-block' data-id="+data.id+" data-status=0><span class='glyphicon glyphicon-off'></span> Wyłącz</button>";
                            else
                                returnButton += "<button class='button-status-client btn btn-success btn-block' data-id="+data.id+" data-status=1 ><span class='glyphicon glyphicon-off'></span> Włącz</button>";
                            return returnButton;
                        },"orderable": false, "searchable": false, width:'10%'
                    }
                ],
            });
        });
    </script>
@endsection
