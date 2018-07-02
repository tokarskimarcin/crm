{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view is responsible for connecting clients with routes--}}
{{--*@database tables: voivodeship, city, routes_info,--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: index, getSelectedRoute--}}
{{--*/--}}


@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <style>
        .client-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .client-container {
            background-color: white;
            padding: 2em;
            box-shadow: 0 1px 15px 1px rgba(39, 39, 39, .1);
            border: 0;
            border-radius: .1875rem;
            margin: 1em;

            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 90%;
            max-width: 90%;

        }

        .glyphicon-remove:hover {
            transform: scale(1.2) rotate(180deg);
            cursor: pointer;
        }

        .glyphicon-refresh {
            font-size: 2em;
            transition: all 1.8s ease-in-out;
            color: #0f10ff;
        }

        .glyphicon-refresh:hover {
            transform: scale(1.2) rotate(360deg);
            cursor: pointer;
        }

        .glyphicon-remove {
            font-size: 2em;
            transition: all 0.8s ease-in-out;
            float: right;
            color: red;
        }

        .glyphicon-remove:hover {
            transform: scale(1.2) rotate(180deg);
            cursor: pointer;
        }

        header {
            text-align: center;
            font-size: 2em;
            font-weight: bold;
        }

        .check {
            background: #B0BED9 !important;
        }

        .first-show-date {
            margin-top: 1em;
        }

    </style>

    {{--Header page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Tworzenie Tras</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Tworzenie Tras
                </div>
                <div class="panel-body">
                    <div class="row">
                        @if(Session::has('adnotation'))
                            <div class="alert alert-warning"
                                 style="font-size:1.2em;font-weight:bold;text-align:center;">
                                {{Session::get('adnotation')}}
                            </div>
                            {{Session::forget('adnotation')}}
                        @endif
                    </div>
                    <div class="client-wrapper">
                        <div class="client-container">
                            <header>Klient</header>
                            <div class="alert alert-info">
                                Wybierz klienta z listy. Jeśli nie ma klienta na liście, dodaj go wypełniając formularz,
                                który pojawi się po naciśnięciu przycisku <strong>Dodaj klienta</strong>
                            </div>
                            <div class="col-md-12">
                                <button data-toggle="modal" class="btn btn-default" id="clientModal"
                                        data-target="#ModalClient" data-id="1" title="Nowy Klient"
                                        style="margin-bottom: 14px">
                                    <span class="glyphicon glyphicon-plus"></span> <span>Dodaj Klienta</span>
                                </button>
                                <div class="table-responsive">
                                    <table id="table_client" class="table table-striped thead-inverse">
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

                    <div class="client-wrapper">
                        <div class="client-container">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <label>Klient:</label>
                                    <label id="client_choice_name"></label>
                                </div>
                                <div class="col-md-4">
                                    <label>Priorytet:</label>
                                    <label id="client_choice_priority"></label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="client_choice_type">Typ:</label>
                                        <select id="client_choice_type" class="form-control">
                                            <option value="0">Wybierz</option>
                                            <option value="1">Badania</option>
                                            <option value="2">Wysyłka</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {{--<div class="form-group" style="margin-top:1em;">--}}
                                {{--<label for="weekNumber">Wybierz tydzień</label>--}}
                                {{--<select id="weekNumber" class="form-control"></select>--}}
                                {{--</div>--}}
                                <div class="form-group first-show-date">
                                    <label class="myLabel">Data pierwszego pokazu:</label>
                                    <div class="input-group date form_date col-md-5" data-date-calendarWeeks="true"
                                         data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                        <input class="form-control first-show-date-input" name="date" id="date"
                                               type="text" value="{{date("Y-m-d")}}">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-th"></span></span>
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
                                    <h4 class="modal-title" id="modal_title">Dodaj nowego klienta<span
                                                id="modal_category"></span></h4>
                                </div>
                                <div class="modal-body">

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            Formularz
                                        </div>
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="myLabel">Nazwa Klienta</label>
                                                        <input class="form-control" name="clientName" id="clientName"/>
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
                                                <button class="btn btn-success form-control" id="saveClient"
                                                        onclick="saveClient(this)">Zapisz Klienta
                                                </button>
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
                    <input type="hidden" value="0" id="clientID"/>


                    <div class="client-wrapper">
                        <div class="client-container">
                            <div class="alert alert-info">
                                Wybierz szablon trasy z listy. Jeśli nie ma odpowiedniej trasy na liście, stwórz ją
                                naciskając na przycisk <strong>Dodaj trasę ręcznie</strong>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-default" id="add-new-route" style="margin-bottom: 14px;"><span
                                            class="glyphicon glyphicon-plus"></span>Dodaj trasę ręcznie
                                </button>
                            </div>
                            <table id="datatable" class="thead-inverse table table-striped table-bordered"
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
                        <div class="client-container route-here" id="jump-here">

                        </div>
                    </div>
                    <div class="client-wrapper">
                        <div class="client-container">
                            <button class="btn btn-primary" style="margin-top:1em;font-size:1.1em;font-weight:bold;"
                                    id="redirect"><span class='glyphicon glyphicon-repeat'></span> Powrót
                            </button>
                            <button class="btn btn-success"
                                    style="margin-top:1em;margin-bottom:1em;font-size:1.1em;font-weight:bold;"
                                    id="save"><span class='glyphicon glyphicon-save'></span> Zapisz
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>


        let saveClientClicked = false;

        function activateDatepicker() {
            $('.form_date').datetimepicker({
                language: 'pl',
                autoclose: 1,
                minView: 2,
                calendarWeeks: 'true',
                pickTime: false
            });
        }

        let editFlag = false;
        let saveButton = document.querySelector('#saveClient');

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
                delay: delay$miliseconds$number,
                animate: {
                    enter: 'animated fadeInRight',
                    exit: 'animated fadeOutRight'
                }
            });
        }

        activateDatepicker();

        $('#clientModal').click(() => {
            $('#ModalClient .modal-title').first().text('Dodawanie nowego klienta');
            let saveClientModalButton = $('#ModalClient #saveClient');
            saveClientModalButton.first().prop('class','btn btn-default form-control');
            saveClientModalButton.first().text('');
            saveClientModalButton.append($('<span class="glyphicon glyphicon-plus"></span>'));
            saveClientModalButton.append(' Dodaj Klienta');
        });

        //Clear Client modal
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
            if (clientName.trim().length == 0) {
                validation = false;
                swal("Podaj nazwę klienta")
            }
            if (clientPriority == 0) {
                validation = false;
                swal("Wybierz priorytet klienta")
            }
            if (clientType == 0) {
                validation = false;
                swal("Wybierz typ klienta")
            }
            if (validation) {
                saveClientClicked = true;
                saveButton.disabled = true; //after first click, disable button
                $.ajax({
                    type: "POST",
                    url: "{{route('api.saveClient')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'clientName': clientName,
                        'clientPriority': clientPriority,
                        'clientType': clientType,
                        'clientID': clientID
                    },
                    success: function (response) {
                        if(editFlag == false) {
                            notify('<strong>Klient został pomyślnie dodany</strong>', 'success');
                        }
                        else {
                            notify('<strong>Klient został pomyślnie edytowany</strong>', 'success');
                            editFlag = false;
                        }
                        $('#ModalClient').modal('hide');
                        saveButton.disabled = false; //after closing modal, enable button
                    }
                })
            }
        }


        $(document).ready(function () {

            let modalTitle = document.querySelector('#modal_title');

            $('#client_choice_type').attr('disabled', true).val(0);
            $('.city').select2();
            $('.voivodeship').select2();
            $('.voivodeship').off('select2:select'); //remove previous event listeners
            $('.voivodeship').on('select2:select', function (e) {
                getCitiesNameFromAjax(e); // Pobranie Miast bez ograniczenia 100KM
            });


            let today = new Date();
            let dd = today.getDate();
            let mm = today.getMonth() + 1; //January is 0!

            let yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            today = yyyy + '-' + mm + '-' + dd;

            let currentDate = today;


            //Ta funkcja działa analogicznie jak jQuerry .appendAfter();
            Element.prototype.appendAfter = function (element) {
                element.parentNode.insertBefore(this, element.nextSibling);
            }, false;

            //Ta funkcja działa analogicznie jak jQuerry .appendBefore();
            Element.prototype.appendBefore = function (element) {
                element.parentNode.insertBefore(this, element);
            }, false;

            const lastWeekOfYear ={{$lastWeek}};
            const weekSelect = document.querySelector('#weekNumber');
            if (weekSelect) {
                for (var i = 1; i <= lastWeekOfYear; i++) {
                    let optionElement = document.createElement('option');
                    optionElement.value = i;
                    optionElement.innerHTML = `${i}`;
                    weekSelect.appendChild(optionElement);
                }
            }


            let iterator = 1;
            let mainContainer = document.querySelector('.routes-wrapper'); //zaznaczamy główny container
            let route_id = 0;
            let client_id = 0;

//*********************START CLIENT SECTON***************************

            let finalClientId = null; //This variable is needed for form submit

            $('#ModalClient').on('hidden.bs.modal', function () {
                $('#clientID').val("0");
                clearModal();
                if(saveClientClicked) {
                    table_client.ajax.reload();
                    saveClientClicked = false;
                }
            });

            function writeCheckedClientInfo() {
                tr_line = document.getElementsByClassName('check')[0];
                var tr_line_name = tr_line.getElementsByClassName('client_name')[0].textContent;
                var tr_line_phone = tr_line.getElementsByClassName('client_priority')[0].textContent;
                var tr_line_type = tr_line.getElementsByClassName('client_type')[0].textContent;
                document.getElementById('client_choice_name').textContent = tr_line_name;
                document.getElementById('client_choice_priority').textContent = tr_line_phone;

                $('#client_choice_type').attr('disabled', false);
                if (tr_line_type == 'Badania') {
                    $('#client_choice_type').val(1);
                }
                else if (tr_line_type == 'Wysyłka') {
                    $('#client_choice_type').val(2);
                } else {
                    $('#client_choice_type').val(0);
                }
            }

            function clearCheckedClientInfo() {
                document.getElementById('client_choice_name').textContent = "";
                document.getElementById('client_choice_priority').textContent = "";
                $('#client_choice_type').attr('disabled', true).val(0);

            }

            function getCitiesNamesByVoievodeship(voivodeship_id) {
                let city;
                $.ajax({
                    type: "POST",
                    async: false,
                    url: '{{ route('api.getCitiesNames') }}',
                    data: {
                        "id": voivodeship_id,
                        "currentDate": currentDate
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        city = response;
                    }
                });
                return city;
            }

            //Pobranie miast do wojew. bez ograniczenia do 100 KM
            function getCitiesNameFromAjax(e) {
                let container = e.target.parentElement.parentElement.parentElement.parentElement;
                let headerId = e.params.data.id;
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.getCitiesNames') }}',
                    data: {
                        "id": headerId,
                        "currentDate": currentDate
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        //Funkcja ta tworzy select wraz z miastami i wkleja w odpowiednie miejsce
                        let placeToAppend2 = container.getElementsByClassName('city')[0];
                        placeToAppend2.innerHTML = '';
                        let basicOption = document.createElement('option');
                        basicOption.value = '0';
                        basicOption.textContent = 'Wybierz';
                        placeToAppend2.appendChild(basicOption);
                        for (var i = 0; i < response.length; i++) {
                            let responseOption = document.createElement('option');
                            responseOption.value = response[i].id;
                            if (response[i].block == 1) {
                                if (response[i].exceeded == 0) { //When city is still available
                                    responseOption.textContent = response[i].name + " [dostępne jeszcze " + response[i].used_hours + " godzin]";
                                    responseOption.setAttribute('data-max_hours', response[i].used_hours); //needed for auto setting hours
                                }
                                else { //when city is not available
                                    responseOption.textContent = response[i].name + " (KARENCJA do " + response[i].available_date + ") [przekroczono o " + response[i].used_hours + " godzin]";
                                    responseOption.setAttribute('data-max_hours', 0); //needed for auto setting hours
                                }
                            }
                            else {
                                responseOption.textContent = response[i].name;
                                if (response[i].max_hour > 0) {
                                    responseOption.setAttribute('data-max_hours', response[i].max_hour); //needed for auto setting hours
                                }
                            }

                            placeToAppend2.appendChild(responseOption);
                        }
                    }
                });
            }

            function generateNewShowButton(){
                let buttonStringAppend =
                    '<div class="row">' +
                    '<div class="col-lg-12 button_section button_new_show_section">\n' +
                    '<button class="btn btn-default btn-block btn_add_new_route" id="add_new_show"><span class="glyphicon glyphicon-plus"></span> Dodaj nowy pokaz</button>' +
                    '</div>\n' +
                    '</div>';

                let newRouteContainer = document.createElement('div');
                newRouteContainer.className = 'new-route-container';
                newRouteContainer.innerHTML = buttonStringAppend;
                return newRouteContainer;
            }

            function generateRouteDiv(showRemove, showRefresh, responseIterator, city, voievodes, placeToAppend) {
                let routeContainer = document.createElement('div');
                routeContainer.className = 'routes-container';

                let stringAppend =
                    '<div class="row">\n' +
                    '<div class="button_section">';
                if (showRemove)
                    stringAppend += '<span class="glyphicon glyphicon-remove" data-remove="show"></span>';
                stringAppend += '</div>' +
                    '<header>Pokaz </header>\n';
                if (showRefresh)
                    stringAppend += '<div class=colmd-12 style="text-align: center">' +
                        '<span class="glyphicon glyphicon-refresh" data-refresh="refresh" style="font-size: 30px"></span>' +
                        '</div>';

                stringAppend +=
                    '\n' +
                    '            <div class="col-md-6">\n' +
                    '                <div class="form-group">\n' +
                    '                    <label>Województwo</label>\n' +
                    '                    <select class="form-control voivodeship" data-type="voivode">\n';
                for (var j = 0; j < voievodes.length; j++) {
                    if (responseIterator.voivodeship_id == voievodes[j].id)
                        stringAppend += '<option value ="' + responseIterator.voivodeship_id + ' " selected>' + responseIterator.voivode_name + '</option>';
                    else
                        stringAppend += '<option value ="' + voievodes[j].id + '">' + voievodes[j].name + '</option>';
                }
                stringAppend += '                    </select>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '\n' +
                    '            <div class="col-md-6">\n' +
                    '                <div class="form-group">\n' +
                    '                    <label for="city">Miasto</label>\n' +
                    '                    <select class="form-control city">\n';
                for (var j = 0; j < city.length; j++) {
                    if (responseIterator.city_id == city[j].id) {
                        if (city[j].block == 1) {
                            if (city[j].exceeded == 0) { //When city is still available
                                stringAppend += '<option value="' + city[j].id + '" data-max_hours="' + city[j].used_hours + '"  selected>' + city[j].name + ' [dostępne jeszcze ' + city[j].used_hours + ' godzin]</option>\n';
                            }
                            else {
                                stringAppend += '<option value="' + city[j].id + '"  data-max_hours="0" selected>' + city[j].name + '(KARENCJA do ' + city[j].available_date + ') [przekroczono o ' + city[j].used_hours + ' godzin]</option>\n';
                            }

                        }
                        else {
                            stringAppend += '<option value="' + city[j].id + '"  data-max_hours="' + city[j].max_hour + '" selected>' + city[j].name + '</option>\n';
                        }

                    } else {
                        if (city[j].block == 1) {
                            if (city[j].exceeded == 0) { //When city is still available
                                stringAppend += '<option value="' + city[j].id + '" data-max_hours="' + city[j].used_hours + '">' + city[j].name + ' [dostępne jeszcze ' + city[j].used_hours + ' godzin]</option>\n';
                            }
                            else {
                                stringAppend += '<option value="' + city[j].id + '"  data-max_hours="0">' + city[j].name + '(KARENCJA' + city[j].available_date + ') [przekroczono o ' + city[j].used_hours + ' godzin]</option>\n';
                            }

                        }
                        else {
                            stringAppend += '<option value="' + city[j].id + '"  data-max_hours="' + city[j].max_hour + '">' + city[j].name + '</option>\n';
                        }

                    }
                }
                stringAppend += '                    </select>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '<div class="col-md-6">' +
                    '<div class="form-group">' +
                    '<label class="myLabel">Ilość godzin pokazów</label>' +
                    '<input class="form-control show-hours" min="0" type="number" placeholder="Np. 2">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '<div class="form-group">' +
                    '<label class="myLabel">Data:</label>' +
                    '<div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">';
                if (currentDate != "0") {
                    stringAppend += '<input class="form-control dateInput" type="text" value="' + currentDate + '">';
                }
                else {
                    stringAppend += '<input class="form-control dateInput" type="text" value="{{date("Y-m-d")}}">';
                }

                stringAppend += '<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '\n' +
                    '<div class="form-group hour_div">' +
                    '</div></div>';

                routeContainer.innerHTML = stringAppend;
                $(routeContainer).hide();
                placeToAppend.appendChild(routeContainer);
            }

            {{--let currentDate ={{$today}};--}}


                table_client = $('#table_client').DataTable({
                "autoWidth": true,
                scrollY: '40vh',
                "processing": true,
                "serverSide": true,
                "drawCallback": function (settings) {
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
                }, "rowCallback": function (row, data, index) {
                    if (data.status == 1) {
                        $(row).css('background', '#c500002e')
                    }
                    $(row).attr('id', "clientId_" + data.id);
                    return row;
                }, "fnDrawCallback": function (settings) {
                    /**
                     * Zmiana statusu klienta
                     */
                    $('.button-status-client').on('click', function () {
                        let clientId = $(this).data('id');
                        let clienStatus = $(this).data('status');
                        let nameOfAction = "";
                        if (clienStatus == 0)
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
                                        'clientId': clientId
                                    },
                                    success: function (response) {
                                        notify("<strong>Status klienta został zmieniony</strong>", 'info');
                                        table_client.ajax.reload();
                                    }
                                });
                            }
                        })
                    });


                    $('.button-edit-client').on('click', function () {
                        $('#ModalClient .modal-title').first().text('Edytowanie klienta');
                        let saveClientModalButton = $('#ModalClient #saveClient');
                        saveClientModalButton.first().prop('class','btn btn-info form-control');
                        saveClientModalButton.first().text('');
                        saveClientModalButton.append($('<span class="glyphicon glyphicon-edit"></span>'));
                        saveClientModalButton.append(' Edytuj Klienta');
                        clientId = $(this).data('id');
                        $.ajax({
                            type: "POST",
                            url: "{{ route('api.findClient') }}", // do zamiany
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'clientId': clientId
                            },
                            success: function (response) {
                                clearModal();
                                $('#clientName').val(response.name);
                                $('#clientPriority').val(response.priority);
                                $('#clientType').val(response.type);
                                $('#clientID').val(response.id);
                                $('#ModalClient').modal('show');
                                editFlag = true;
                                modalTitle.textContent = "Edytuj klienta";
                            }
                        });
                    });
                    //Zaznaczenie kolumny
                    $('#table_client tbody tr').on('click', function (e) {
                        if ($(this).hasClass('check')) {
                            $(this).removeClass('check');
                            $(this).find('.client_check').prop('checked', false);
                            client_id = 0;
                            finalClientId = 0;
                            clearCheckedClientInfo();
                        }
                        else {
                            if(e.target.dataset.noaction != 1) {
                                table_client.$('tr.check').removeClass('check');
                                $.each($('#table_client').find('.client_check'), function (item, val) {
                                    $(val).prop('checked', false);
                                });
                                $(this).addClass('check');
                                $(this).find('.client_check').prop('checked', true);
                                client_id = $(this).attr('id');
                                finalClientId = $(this).attr('id');
                                writeCheckedClientInfo();
                            }

                        }
                    });

                    /**
                     * This part is responsible for aplaying default heading to modal.
                     */
                    $("#ModalClient").on('hidden.bs.modal', function () {
                        modalTitle.textContent = "Dodaj nowego klienta";
                    });

                }, "columns": [
                    {"data": "name", "className": "client_name"},
                    {
                        "data": function (data, type, dataToSet) {
                            return data.priorityName;
                        }, "name": "priorityName", "className": "client_priority"
                    },
                    {"data": "type", "className": "client_type"},
                    {
                        "data": function (data, type, dataToSet) {
                            let returnButton = "<button class='button-edit-client btn btn-block btn-info' data-id=" + data.id + " data-noaction='1'><span class='glyphicon glyphicon-edit'></span> Edycja</button>";
                            if (data.status == 0)
                                returnButton += "<button class='button-status-client btn btn-block btn-danger' data-id=" + data.id + " data-status=0 data-noaction='1'><span class='glyphicon glyphicon-off'></span> Wyłącz</button>";
                            else
                                returnButton += "<button class='button-status-client btn btn-block btn-success' data-id=" + data.id + " data-status=1 data-noaction='1'><span class='glyphicon glyphicon-off'></span> Włącz</button>";
                            return returnButton;
                        }, "orderable": false, "searchable": false, width:'10%'
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            return ' <input style="display: inline-block;" type="checkbox" class="client_check"/>';
                        }, "orderable": false, "searchable": false
                    }
                ],
            });

            $('#menu-toggle').change(()=>{
                table_client.columns.adjust().draw();
            });

//*********************END CLIENT SECTON***************************

//*********************START ROUTE(ROUND) SECTON***************************

            let addNewRouteButton = document.getElementById('add-new-route');
            // Tabela zawierająca szablony tras
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
                        d.date = currentDate;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                }, "rowCallback": function (row, data, index) {
                    $(row).attr('id', 'route_' + data.id);
                    if (data.changeColor == '0') {
                        //Gdy trasa jest zajęta
                        $(row).css('background', '#c500002e');
                    }
                    return row;
                }, "fnDrawCallback": function (settings) {
                    $('#datatable tbody tr').on('click', function () {
                        // klikamy  na zaznaczony checkboxa trasy, oddzacz i usuń trase
                        if ($(this).hasClass('check')) {
                            $(this).removeClass('check');
                            $(this).find('.route_check').prop('checked', false);
                            route_id = 0; // przypisuje route_id = 0 gdy odznaczamy checkboxa
                            let placeToAppend = document.querySelector('.route-here');
                            placeToAppend.innerHTML = '';
                        }// klikamy  na odznaczony checkboxa trasy, zaznacz i dodaj trase
                        else {
                            table.$('tr.check').removeClass('check');
                            $.each($('#datatable').find('.route_check'), function (item, val) {
                                $(val).prop('checked', false);
                            });
                            $(this).addClass('check');
                            $(this).find('.route_check').prop('checked', true);
                            route_id = $(this).attr('id'); // przypisuje route_id gdy zaznaczamy checkboxa
                            let placeToAppend = document.querySelector('.route-here');
                            placeToAppend.innerHTML = '';

                            // Pobranie informacji o zaznaczonej trasie
                            $.ajax({
                                type: "POST",
                                url: '{{ route('api.getRoute') }}',
                                data: {
                                    "route_id": route_id
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
                                    var voievodes = @json($voivodes);
                                    //Dla wybranego wojew.. z trasy, pobierz wszystkie
                                    //miasta
                                    for (var i = 0; i < response.length; i++) {
                                        //Pobranie miast dla danego wojew
                                        var city = getCitiesNamesByVoievodeship(response[i].voivodeship_id);
                                        //Generowanie Div'a
                                        if (i == 0)
                                            generateRouteDiv(false, false, response[i], city, voievodes, placeToAppend);
                                        else if (i + 1 == response.length)
                                            generateRouteDiv(true, true, response[i], city, voievodes, placeToAppend);
                                        $('.city').select2();
                                        $('.voivodeship').select2();
                                        $('.voivodeship').off('select2:select'); //remove previous event listeners
                                        $('.voivodeship').on('select2:select', function (e) {
                                            getCitiesNameFromAjax(e); // Pobranie Miast bez ograniczenia 100KM
                                        });
                                        activateDatepicker();
                                    }
                                    placeToAppend.appendChild(generateNewShowButton());
                                    $('.city').on('select2:select', function (e) {
                                        setHoursValue(e);
                                    });
                                    $('.routes-container').slideDown(1000,()=>{
                                        $("html, body").animate({scrollTop: $(document).height()}, "slow");
                                    });
                                }
                            });
                        }
                    });
                },
                "columns": [
                    {
                        "data": function (data, type, dataToSet) {
                            return '<span id="' + data.id + '">' + data.name + '</span>';
                        }, "name": "name", "orderable": true
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            return '<input type="checkbox" style="display:inline-block" class="route_check">';
                        }, "orderable": false, "searchable": false
                    }
                ]
            });

            //tworzenie nowego diva z opcją ograniczenia 100KM
            function createNewShow(voivodes) {
                newElement = document.createElement('div');
                newElement.className = 'routes-container';
                let stringAppend = '        <div class="row">\n' +
                    '<div class="button_section button_section_gl_nr">' +
                    '<span class="glyphicon glyphicon-remove" data-remove="show"></span>' +
                    '</div>' +
                    '        <header>Pokaz </header>\n' +
                    '<div class=colmd-12 style="text-align: center">' +
                    '   <span class="glyphicon glyphicon-refresh" data-refresh="refresh" style="font-size: 30px"></span>' +
                    '</div>' +
                    '\n' +
                    '            <div class="col-md-6">\n' +
                    '                <div class="form-group">\n' +
                    '                    <label>Województwo</label>\n' +
                    '                    <select class="form-control voivodeship" data-type="voivode">\n' +
                    '                        <option value="0">Wybierz</option>\n';
                for (let i = 0; i < voivodes.length; i++) {
                    stringAppend += '<option value =' + voivodes[i]['id'] + '>' + voivodes[i]['name'] + '</option>';
                }
                stringAppend += '                    </select>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '\n' +
                    '            <div class="col-md-6">\n' +
                    '                <div class="form-group">\n' +
                    '                    <label for="city">Miasto</label>\n' +
                    '                    <select class="form-control city">\n' +
                    '                        <option value="0">Wybierz</option>\n' +
                    '                    </select>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '<div class="col-md-6">' +
                    '<div class="form-group">' +
                    '<label class="myLabel">Ilość godzin pokazów</label>' +
                    '<input class="form-control show-hours" min="0" type="number" placeholder="Np. 2">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '<div class="form-group">' +
                    '<label class="myLabel">Data:</label>' +
                    '<div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">';
                if (currentDate != "0") {
                    stringAppend += '<input class="form-control dateInput" type="text" value="' + currentDate + '">';
                }
                else {
                    stringAppend += '<input class="form-control dateInput" type="text" value="{{date("Y-m-d")}}">';
                }
                stringAppend += '<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                newElement.innerHTML = stringAppend;
                return newElement;
            }

            //Ta funkcja jest globalnym event listenerem na click
            function buttonHandler(e) {
                if (e.target.id == 'add-new-route') {
                    $(e.target).prop('disabled',true);
                    let basicDate = document.querySelector('.first-show-date-input');
                    currentDate = basicDate.value; //every time user clicks on manual show creation, date resets
                    let appendPlace = document.querySelector('.route-here');
                    appendPlace.innerHTML = "";
                    appendPlace.appendChild(generateNewShowButton());

                    let newShow = addNewShow(0, 0); //otrzymujemy nowy formularz z pokazem.
                    removeGlyInFirstShow();
                    $('.city').select2();
                    $('.voivodeship').select2();
                    $('.voivodeship').off('select2:select'); //remove previous event listeners
                    $('.city').off('select2:select'); //remove previous event listeners
                    $('.voivodeship').on('select2:select', function (e) {
                        getCitiesNameFromAjax(e); // Pobranie Miast bez ograniczenia 100KM
                    });
                    $('.city').on('select2:select', function (e) {
                        setHoursValue(e);
                    });

                    activateDatepicker();
                }
                else if (e.target.id == 'add_new_show') {
                    //Get lest child of voievoidship
                    let AllVoievoidship = document.getElementsByClassName('voivodeship');
                    let countAllVoievoidship = AllVoievoidship.length;
                    //Get lest child of City
                    let AllCitySelect = document.getElementsByClassName('city');
                    let countAllCitySelect = AllCitySelect.length;
                    let cityId = 0;
                    let voievodeshipId = 0;

                    let thisContainer = $('.routes-container').last();
                    let dateInput = thisContainer.find('.dateInput').first(); //we select date input and append its value to currentDate variable
                    currentDate = dateInput.val();

                    let validation = true;
                    // Walidacja wybrania
                    if (countAllVoievoidship != 0 && countAllCitySelect != 0) {
                        voievodeshipId = AllVoievoidship[countAllVoievoidship - 1].value;
                        cityId = AllCitySelect[countAllCitySelect - 1].value;
                    }
                    else {
                        voievodeshipId = AllVoievoidship[countAllVoievoidship].value;
                        cityId = AllCitySelect[countAllCitySelect].value;
                    }
                    if (voievodeshipId == 0) {
                        swal("Przed dodaniem nowego pokazu, uprzednio wybierz Województwo");
                        validation = false;
                    } else if (cityId == 0) {
                        swal("Przed dodaniem nowego pokazu, uprzednio wybierz Miasto");
                        validation = false;
                    }
                    if (validation) {
                        $(e.target).prop('disabled', true);
                        $.ajax({
                            type: "POST",
                            url: '{{ route('api.getVoivodeshipRound') }}',
                            data: {
                                "voievodeshipId": voievodeshipId,
                                "cityId": cityId,
                                "currentDate": currentDate
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                addNewShow(response['voievodeInfo'], 1);
                                $('.city').select2();
                                $('.voivodeship').select2(); //attach select2 look
                                $('.voivodeship').off('select2:select'); //remove previous event listeners
                                $('.city').off('select2:select'); //remove previous event listeners
                                $('.voivodeship').on('select2:select', function (e) {
                                    let container = e.target.parentElement.parentElement.parentElement.parentElement;
                                    let headerId = e.params.data.id;
                                    let placeToAppend = container.getElementsByClassName('city')[0];
                                    placeToAppend.innerHTML = '';
                                    let basicOption = document.createElement('option');
                                    basicOption.value = '0';
                                    basicOption.textContent = 'Wybierz';
                                    placeToAppend.appendChild(basicOption);
                                    var cityInfo = response['cityInfo'][headerId];
                                    if (typeof cityInfo !== 'undefined') {
                                        //zmiana ręczna województwa
                                        for (var i = 0; i < cityInfo.length; i++) {
                                            if (cityInfo[i].id == headerId) {
                                                let responseOption = document.createElement('option');
                                                responseOption.value = cityInfo[i].city_id;
                                                if (cityInfo[i].block == 1) {
                                                    if (cityInfo[i].exceeded == 0) { //When city is still available
                                                        responseOption.textContent = cityInfo[i].city_name + " [dostępne jeszcze " + cityInfo[i].used_hours + " godzin]";
                                                        responseOption.setAttribute('data-max_hours', cityInfo[i].used_hours); //needed for auto setting hours
                                                    }
                                                    else { //when city is not available
                                                        responseOption.textContent = cityInfo[i].city_name + " (KARENCJA do " + cityInfo[i].available_date + ") [przekroczono o " + cityInfo[i].used_hours + " godzin]";
                                                        responseOption.setAttribute('data-max_hours', 0); //needed for auto setting hours
                                                    }
                                                }
                                                else {
                                                    responseOption.textContent = cityInfo[i].city_name;
                                                    if (cityInfo[i].max_hour > 0) {
                                                        responseOption.setAttribute('data-max_hours', cityInfo[i].max_hour); //needed for auto setting hours
                                                    }
                                                }
                                                placeToAppend.appendChild(responseOption);
                                            }
                                        }
                                    } else {
                                        getCitiesNameFromAjax(e); // Pobranie Miast bez ograniczenia 100KM
                                    }
                                });
                                $('.city').on('select2:select', function (e) {
                                    setHoursValue(e);
                                });
                            }
                        });
                    }
                }
                else if (e.target.dataset.remove == 'show') { // click on X glyphicon

                    swal({
                        title: "Jesteś pewien?",
                        type: "warning",
                        text: "Czy chcesz usunąć pokaz?",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Tak, usuń!",

                    }).then((result) => {
                        if(result.value) {
                            let showContainer = e.target.parentElement.parentElement.parentElement;
                            removeGivenShow(showContainer);
                        }
                    });
                }
                else if (e.target.dataset.refresh == 'refresh') { // click on X glyphicon
                    //get contener with select (actual and previous)
                    var actualContener = e.target.parentNode.parentNode;
                    var previousContener = e.target.parentNode.parentNode.parentNode.previousElementSibling;
                    var actualSelectCity = actualContener.getElementsByClassName('city')[0];
                    var actualSelectVoievode = actualContener.getElementsByClassName('voivodeship')[0];
                    if (previousContener != null) {
                        var previousSelectCityVal = previousContener.getElementsByClassName('city')[0].value;
                        var previousSelectVoievodeVal = previousContener.getElementsByClassName('voivodeship')[0].value;
                        let dateFromPreviousContainer = previousContener.querySelector('.dateInput').value; //we are selecting date value from previous contener and assing it into currentDate variable
                        currentDate = dateFromPreviousContainer;

                        let validation = true;
                        if (previousSelectVoievodeVal == 0) {
                            swal("Przed synchronizacją nowego pokazu, uprzednio wybierz Województwo");
                            validation = false;
                        } else if (previousSelectCityVal == 0) {
                            swal("Przed synchronizacją nowego pokazu, uprzednio wybierz Miasto");
                            validation = false;
                        }
                        if (validation) {
                            $.ajax({
                                type: "POST",
                                url: '{{ route('api.getVoivodeshipRound') }}',
                                data: {
                                    "voievodeshipId": previousSelectVoievodeVal,
                                    "cityId": previousSelectCityVal,
                                    "currentDate": currentDate
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
                                    $(actualSelectVoievode).off('select2:select');
                                    $('.city').off('select2:select'); //remove previous event listeners

                                    let stringAppend = '<option value=0>Wybierz</option>';
                                    for (let i = 0; i < response['voievodeInfo'].length; i++) {
                                        stringAppend += '<option value =' + response['voievodeInfo'][i]['id'] + '>' + response['voievodeInfo'][i]['name'] + '</option>';
                                    }
                                    actualSelectVoievode.innerHTML = stringAppend;
                                    stringAppend = '<option value=0>Wybierz</option>';
                                    actualSelectCity.innerHTML = stringAppend;

                                    $(actualSelectVoievode).on('select2:select', function (e) {
                                        let container = e.target.parentElement.parentElement.parentElement.parentElement;
                                        let headerId = e.params.data.id;

                                        let placeToAppend2 = container.getElementsByClassName('city')[0];
                                        placeToAppend2.innerHTML = '';
                                        let basicOption = document.createElement('option');
                                        basicOption.value = '0';
                                        basicOption.textContent = 'Wybierz';
                                        placeToAppend2.appendChild(basicOption);
                                        let responseObject = response['cityInfo'];
                                        for (var i = 0; i < responseObject[headerId].length; i++) {


                                            let responseOption = document.createElement('option');
                                            responseOption.value = responseObject[headerId][i].city_id;

                                            if (responseObject[headerId][i].block == 1) {
                                                if (responseObject[headerId][i].exceeded == 0) { //When city is still available
                                                    responseOption.textContent = responseObject[headerId][i].city_name + " [dostępne jeszcze " + responseObject[headerId][i].used_hours + " godzin]";
                                                    responseOption.setAttribute('data-max_hours', responseObject[headerId][i].used_hours); //needed for auto setting hours
                                                }
                                                else { //when city is not available
                                                    responseOption.textContent = responseObject[headerId][i].city_name + " (KARENCJA do " + responseObject[headerId][i].available_date + ") [przekroczono o " + responseObject[headerId][i].used_hours + " godzin]";
                                                    responseOption.setAttribute('data-max_hours', 0); //needed for auto setting hours
                                                }
                                            }
                                            else {
                                                responseOption.textContent = responseObject[headerId][i].city_name;
                                                if (responseObject[headerId][i].max_hour > 0) {
                                                    responseOption.setAttribute('data-max_hours', responseObject[headerId][i].max_hour); //needed for auto setting hours
                                                }
                                            }

                                            placeToAppend2.appendChild(responseOption);
                                        }
                                    });
                                    $('.city').on('select2:select', function (e) {
                                        setHoursValue(e);
                                    });
                                }
                            });
                        }
                    }
                }
                else if (e.target.id == "save") {
                    let everythingIsGood = undefined;
                    //Zaznaczenie wszystkich niezbędnych inputów
                    let voivodeElements = Array.from(document.getElementsByClassName('voivodeship'));
                    let cityElements = Array.from(document.getElementsByClassName('city'));
                    let showElements = Array.from(document.getElementsByClassName('show-hours'));
                    let dateElements = Array.from(document.getElementsByClassName('dateInput'));

                    //Deklaracja tablic które będą przechowywały wartości inputów
                    let voivodeArr = [];
                    let cityArr = [];
                    let hourArr = [];
                    let dateArr = [];

                    //Wypełnianie powyższych tablic wartościami z inputów
                    voivodeElements.forEach(function (element) {
                        voivodeArr.push(element.options[element.selectedIndex].value);
                    });

                    cityElements.forEach(function (element) {
                        cityArr.push(element.options[element.selectedIndex].value);
                    });

                    showElements.forEach(function (element) {
                        hourArr.push(element.value);
                    });

                    dateElements.forEach(function (element) {
                        dateArr.push(element.value);
                    });

                    everythingIsGood = finalClientId != null && finalClientId != '0' ? formValidation(voivodeArr, cityArr, hourArr) : false;

                    if (everythingIsGood == true) {
                        const clientTypeValue = $('#client_choice_type option:selected').val();
                        if (clientTypeValue != '0') {
                            let formContainer = document.createElement('div');
                            formContainer.innerHTML = '<form method="post" action="{{URL::to('/crmRoute_index')}}" id="user_form"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" value="' + voivodeArr + '" name="voivode"><input type="hidden" value="' + cityArr + '" name="city"><input type="hidden" value="' + hourArr + '" name="hour"><input type="hidden" name="clientId" value="' + finalClientId + '"><input type="hidden" name="date" value="' + dateArr + '"><input type="hidden" name="clientType" value="' + clientTypeValue + '"></form>';
                            let place = document.querySelector('.route-here');
                            place.appendChild(formContainer);
                            let userForm = document.getElementById('user_form');
                            userForm.submit();
                        }
                        else {
                            swal('Wybierz typ klienta (badania/wysyłka)');
                        }

                    }
                    else {
                        clearArrays(voivodeArr, cityArr, hourArr);
                        if (finalClientId == null || finalClientId == '0') {
                            swal('Wybierz klienta');
                        }
                        else {
                            swal('Wszystkie pola muszą zostać wybrane!');
                        }

                    }
                }
                else if (e.target.id == 'redirect') {
                    location.href = "{{URL::to('/showClientRoutes')}}";
                }

            }


            /**
             * Parameters: e - select2 event after selecting of city
             * Result: This function automatically sets value of hours input basing on attribute data-max_hours in option element.
             */
            function setHoursValue(e) {
                const maxHours = e.target.selectedOptions[0].dataset.max_hours; // maximum hours we that we can use for given city.
                const entireRow = e.target.parentNode.parentNode.parentNode;
                const hoursInput = entireRow.querySelector('.show-hours'); // selecting hoursInput of given show
                hoursInput.value = maxHours;
            }

            /*
            This function removes "X" button from first show container
             */
            function removeGlyInFirstShow() {
                let firstShow = document.getElementsByClassName('routes-container')[0];
                let removeGlyphicon = firstShow.getElementsByClassName('glyphicon-remove')[0];
                let removeGlyphiconRefresh = firstShow.getElementsByClassName('glyphicon-refresh')[0];
                removeGlyphicon.parentNode.removeChild(removeGlyphicon);
                removeGlyphiconRefresh.parentNode.removeChild(removeGlyphiconRefresh);
            }

            /**
             * This function clear given arrays;
             */
            function clearArrays() {
                let args = arguments;
                for (var i = 0; i < args.length; i++) {
                    args[i] = [];
                }
            }

            /**
             * This function validate form
             */
            function formValidation() {
                let args = arguments;
                let flag = true;
                for (var i = 0; i < args.length; i++) {
                    if (args[i].length != '0') {
                        args[i].forEach(function (value) {
                            if (value == '0' || value == '' || value == null) {
                                flag = false;
                            }
                        });
                    }
                    else {
                        flag = false;
                    }
                }
                return flag;
            }

            /**
             * This function remove whole route container while user click on red cross button
             */
            function removeGivenShow(container) {

                //while removing show, we are appending currentDate value equal to last container's date value.
                let previousContainer = container.previousElementSibling;
                if (previousContainer) {
                    let dateInput = previousContainer.querySelector('.dateInput');
                    currentDate = dateInput.value;
                }
                else {
                    let basisDate = document.querySelector('.first-show-date-input').value;
                    currentDate = basisDate;
                }


                $(container).slideUp(1000, () => {
                    $.notify({
                        // options
                        icon: 'glyphicon glyphicon-trash',
                        title: '',
                        message: 'Pokaz został usunięty'
                    }, {
                        // settings
                        type: 'danger'
                    });
                        container.remove();
                });

            }

            /**
             * This method is reposnosible for adding cross button and dodaj nowy pokaz buttons on previous container.
             */
            function addButtonsToPreviousContainer(container) {
                let previousContainer = container.previousElementSibling;
                let placeInPreviousContainer = previousContainer.getElementsByClassName('hour_div')[0];
                let buttonsElement = document.createElement('div');
                buttonsElement.classList.add('col-lg-12');
                buttonsElement.classList.add('button_section');
                buttonsElement.innerHTML = '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_show" value="Dodaj nowy pokaz" style="width:100%;margin-bottom:1em;font-size:1.1em;font-weight:bold;">';
                buttonsElement.appendAfter(placeInPreviousContainer);
            }

            function addNewShow(ajaxResponse, type) {
                //removeButtonsFromLastShow();
                let routePlace = document.querySelector('.route-here');
                if (type == 0) {
                    var voievodes = @json($voivodes);
                    var newShow = createNewShow(voievodes); //otrzymujemy nowy formularz z pokazem.
                }
                else {
                    var newShow = createNewShow(ajaxResponse); //otrzymujemy nowy formularz z pokazem.
                }
                $(newShow).hide();
                routePlace.insertBefore(newShow, document.querySelector('.new-route-container'));

                $(newShow).slideDown(1000,()=>{
                    activateDatepicker();
                    $("html, body").animate({scrollTop: $(document).height()}, "slow");
                    $('#add-new-route').prop('disabled',false);
                    $('#add_new_show').prop('disabled', false);
                });

            }

            function removeButtonsFromLastShow() {
                let buttonSection = document.getElementsByClassName('button_section')[document.getElementsByClassName('button_section').length - 1];
                if (buttonSection != null) {
                    buttonSection.parentNode.removeChild(buttonSection);
                }
            }

            document.addEventListener('click', buttonHandler);
            $('.form_date').on('change.dp', function (e) {
                currentDate = e.target.value;
                table.ajax.reload();
            });


        });


    </script>
@endsection
