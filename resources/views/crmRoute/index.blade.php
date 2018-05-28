/*
*@category: CRM,
*@info: This view is responsible for connecting clients with routes
*@database tables: voivodeship, city, routes_info,
*@controller: CrmRouteController,
*@methods: index, getSelectedRoute
*/


@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <style>
    .client-wrapper {
    display:flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
    }

    .client-container {
    background-color: white;
    padding: 2em;
    box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
    border: 0;
    border-radius: .1875rem;
    margin: 1em;

    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 90%;
    max-width: 90%;

    }

    .glyphicon-remove {
        font-size: 2em;
        transition: all 0.8s ease-in-out;
        float: right;
        color:red;
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
    .check{
    background: #B0BED9 !important;
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

                    </div>
                    <div class="client-wrapper">
                        <div class="client-container">
                            <header>Klient</header>
                            <div class="alert alert-info">
                                Wybierz klienta z listy. Jeśli nie ma klienta na liście, dodaj go wypełniając formularz, który pojawi się po naciśnięciu przycisku <strong>Dodaj klienta</strong>
                            </div>
                            <div class="col-md-12">
                                <button data-toggle="modal" class="btn btn-default new_client_to_modal" id="new_client_modal" data-target="#Modal_Client" data-id="1" title="Nowy Klient" style="margin-bottom: 14px">
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
                                        <tr id="clientId_1">
                                            <td class="client_name">Exito - Vigor Life</td>
                                            <td class="client_phone">798987985</td>
                                            <td class="client_type">Kamery</td>
                                            <td>
                                                <button class="btn btn-info"  data-id=1 onclick = "edit_client(this)" >Edycja</button>
                                                <button class="btn btn-danger" data-id=1 onclick = "edit_client(this)" >Wyłącz</button>
                                            </td>
                                            <td>
                                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                                            </td>
                                        </tr>
                                        <tr id="clientId_2">
                                            <td class="client_name">Pollana Med - Pro Active	</td>
                                            <td class="client_phone">798987985</td>
                                            <td class="client_type">Kamery</td>
                                            <td>
                                                <button class="btn btn-info" data-id=2 onclick = "edit_client(this)" >Edycja</button>
                                                <button class="btn btn-danger" data-id=2 onclick = "edit_client(this)" >Wyłącz</button>
                                            </td>
                                            <td>
                                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                                            </td>
                                        </tr>
                                        <tr id="clientId_3">
                                            <td class="client_name">Damages</td>
                                            <td class="client_phone">798987985</td>
                                            <td class="client_type">Badania</td>
                                            <td>
                                                <button class="btn btn-info" data-id=3 onclick = "edit_client(this)" >Edycja</button>
                                                <button class="btn btn-danger" data-id=3 onclick = "edit_client(this)" >Wyłącz</button>
                                            </td>
                                            <td>
                                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                                            </td>
                                        </tr>
                                        <tr id="clientId_4">
                                            <td class="client_name">PromoMedi</td>
                                            <td class="client_phone">798987985</td>
                                            <td class="client_type">Kamery</td>
                                            <td>
                                                <button class="btn btn-info" data-id=4 onclick = "edit_client(this)" >Edycja</button>
                                                <button class="btn btn-danger" data-id=4 onclick = "edit_client(this)" >Wyłącz</button>
                                            </td>
                                            <td>
                                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                                            </td>
                                        </tr>
                                        <tr id="clientId_5">
                                            <td class="client_name">MarMed</td>
                                            <td class="client_phone">798987985</td>
                                            <td class="client_type">Badania</td>
                                            <td>
                                                <button class="btn btn-info" data-id=5 onclick = "edit_client(this)" >Edycja</button>
                                                <button class="btn btn-danger" data-id=5 onclick = "edit_client(this)" >Wyłącz</button>
                                            </td>
                                            <td>
                                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                                            </td>
                                        </tr>
                                        <tr id="clientId_6">
                                            <td class="client_name">Exito - Vigor Life</td>
                                            <td class="client_phone">798987985</td>
                                            <td class="client_type">Badania</td>
                                            <td>
                                                <button class="btn btn-info" onclick = "edit_client(this)" >Edycja</button>
                                                <button class="btn btn-danger" onclick = "edit_client(this)" >Wyłącz</button>
                                            </td>
                                            <td>
                                                <input style="display: inline-block;" type="checkbox" class="client_check"/>
                                            </td>
                                        </tr>
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
                                    <label>Typ:</label>
                                    <label id="client_choice_type"></label>
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


                        <div class="client-wrapper">
                            <div class="client-container">
                                <div class="alert alert-info">
                                    Wybierz szablon trasy z listy. Jeśli nie ma odpowiedniej trasy na liście, stwórz ją naciskając na przycisk <strong>Dodaj trasę ręcznie</strong>
                                </div>
                                <div class="col-md-4">
                                    <a href="#jump-here">
                                        <button class="btn btn-default" id="add-new-route" style="margin-bottom: 14px;"><span class="glyphicon glyphicon-plus"></span>Dodaj trasę ręcznie</button>
                                    </a>
                                </div>
                                <table id="datatable" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
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
                                <button class="btn btn-success" style="margin-top:1em;margin-bottom:1em;font-size:1.1em;font-weight:bold;" id="save">Zapisz</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $('.form_date').datetimepicker({
            language:  'pl',
            autoclose: 1,
            minView : 2,
            pickTime: false
        });

        $(document).ready(function() {

            Element.prototype.appendAfter = function (element) {
                element.parentNode.insertBefore(this, element.nextSibling);
            },false;

            Element.prototype.appendBefore = function (element) {
                element.parentNode.insertBefore(this, element);
            },false;

            let iterator = 1;
            let mainContainer = document.querySelector('.routes-wrapper'); //zaznaczamy główny container
            let route_id = 0;
            let client_id = 0;


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
            function save_client(e){
                alert('Klient dodany');
                $('#Modal_Client').modal('hide');
            }
            function writeCheckedClientInfo(){
                tr_line = document.getElementsByClassName('check')[0];
                var tr_line_name = tr_line.getElementsByClassName('client_name')[0].textContent;
                var tr_line_phone = tr_line.getElementsByClassName('client_phone')[0].textContent;
                var tr_line_type = tr_line.getElementsByClassName('client_type')[0].textContent;
                document.getElementById('client_choice_name').textContent = tr_line_name;
                document.getElementById('client_choice_priority').textContent = tr_line_phone;
                document.getElementById('client_choice_type').textContent = tr_line_type;
            }
            function clearCheckedClientInfo(){
                document.getElementById('client_choice_name').textContent = "";
                document.getElementById('client_choice_priority').textContent = "";
                document.getElementById('client_choice_type').textContent = "";
            }

            var table_client = $('#table_client').DataTable({
                "autoWidth": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"columns":[
                    {"width": "10%"},
                    {"width": "5%"},
                    {"width": "10%"},
                    {"width": "10%"},
                    {"width": "1%"},
                ]
            });

            $('#table_client tbody tr').on('click',function () {
                if ( $(this).hasClass('check') ) {
                    $(this).removeClass('check');
                    $(this).find('.client_check').prop('checked',false);
                    client_id = 0;
                    clearCheckedClientInfo();
                }
                else {
                    table_client.$('tr.check').removeClass('check');
                    $.each($('#table_client').find('.client_check'), function (item,val) {
                        $(val).prop('checked',false);
                    });
                    $(this).addClass('check');
                    $(this).find('.client_check').prop('checked',true);
                    client_id = $(this).attr('id');
                    writeCheckedClientInfo();
                }
            });



            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
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
                },"rowCallback": function( row, data, index ) {
                    $(row).attr('id', 'route_' + data.id);
                    return row;
                },"fnDrawCallback": function(settings){
                    $('#datatable tbody tr').on('click', function() {

                        if($(this).hasClass('check')) {
                            $(this).removeClass('check');
                            $(this).find('.route_check').prop('checked',false);
                            route_id = 0; // przypisuje route_id = 0 gdy odznaczamy checkboxa
                            let placeToAppend = document.querySelector('.route-here');
                            placeToAppend.innerHTML = '';
                        }
                        else {
                            table.$('tr.check').removeClass('check');
                            $.each($('#datatable').find('.route_check'), function (item,val) {
                                $(val).prop('checked',false);
                            });
                            $(this).addClass('check');
                            $(this).find('.route_check').prop('checked',true);
                            route_id = $(this).attr('id'); // przypisuje route_id gdy zaznaczamy checkboxa
                            let placeToAppend = document.querySelector('.route-here');
                            placeToAppend.innerHTML = '';

                                $.ajax({
                                    type: "POST",
                                    url: '{{ route('api.getRoute') }}',
                                    data: {
                                        "route_id": route_id
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {

                                        for(var i = 0; i < response.length; i++) {
                                            let routeContainer = document.createElement('div');
                                            routeContainer.className = 'routes-container';
                                            routeContainer.innerHTML += '<div class="row">\n' +
                                            '<div class="button_section">' +
                                                '<span class="glyphicon glyphicon-remove" data-remove="show"></span>' +
                                            '</div>' +
                                            '        <header>Pokaz </header>\n' +
                                            '\n' +
                                            '            <div class="col-md-6">\n' +
                                            '                <div class="form-group">\n' +
                                            '                    <label>Województwo</label>\n' +
                                            '                    <select class="form-control voivodeship" data-type="voivode">\n' +
                                                '<option value ="' + response[i].voivodeship_id + '">' + response[i].voivode_name + '</option>' +
                                                '                    </select>\n' +
                                            '                </div>\n' +
                                            '            </div>\n' +
                                            '\n' +
                                            '            <div class="col-md-6">\n' +
                                            '                <div class="form-group">\n' +
                                            '                    <label for="city">Miasto</label>\n' +
                                            '                    <select class="form-control city">\n' +
                                            '                        <option value="' + response[i].city_id + '">' + response[i].city_name + '</option>\n' +
                                            '                    </select>\n' +
                                            '                </div>\n' +
                                            '            </div>\n' +
                                            '<div class="col-md-4">' +
                                            '</div>' +
                                                '<div class="col-md-4">' +
                                                '<div class="form-group">' +
                                                '<label class="myLabel">Ilość godzin pokazów</label>' +
                                                '<input class="form-control show-hours" min="0" type="number" step="0.1" placeholder="Np. 2">' +
                                                '</div>' +
                                                '</div>' +
                                                    '<div class="col-md-4">' +
                                                    '</div>' +
                                            '\n' +
                                                '<div class="form-group hour_div">' +
                                                '</div>' +
                                                '            <div class="col-lg-12 button_section button_new_show_section">\n' +
                                                '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_show" value="Dodaj nowy pokaz" style="width:100%;margin-bottom:1em;font-size:1.1em;font-weight:bold;">' +
                                                '            </div>\n' +
                                            '        </div>';

                                            placeToAppend.appendChild(routeContainer);

                                            $('.form_date').datetimepicker({
                                                language:  'pl',
                                                autoclose: 1,
                                                minView : 2,
                                                pickTime: false
                                            });

                                        }
                                        //This part is responsible for button showing on given route
                                        const allNewShowSections = Array.from(document.querySelectorAll('.routes-container .button_new_show_section'));
                                        for(let i = allNewShowSections.length - 2; i >= 0; i--) {
                                            allNewShowSections[i].parentNode.removeChild(allNewShowSections[i]);
                                        }
                                    }
                                });


                        }
                    });
                },
                "columns":[
                    {"data":function (data, type, dataToSet) {
                            return '<span id="' + data.id + '">' + data.name + '</span>';
                        },"name":"name","orderable": true
                    },
                    {"data":function (data, type, dataToSet) {
                            return '<input type="checkbox" style="display:inline-block" class="route_check">';
                        },"orderable": false, "searchable": false
                    }
                ]
            });

            let addNewRouteButton = document.getElementById('add-new-route');

            function createNewShow() {
                newElement = document.createElement('div');
                newElement.className = 'routes-container';
                newElement.innerHTML = '        <div class="row">\n' +
                    '<div class="button_section button_section_gl_nr">' +
                    '<span class="glyphicon glyphicon-remove" data-remove="show"></span>' +
                    '</div>' +
                    '        <header>Pokaz </header>\n' +
                    '\n' +
                    '            <div class="col-md-6">\n' +
                    '                <div class="form-group">\n' +
                    '                    <label>Województwo</label>\n' +
                    '                    <select class="form-control voivodeship" data-type="voivode">\n' +
                    '                        <option value="0">Wybierz</option>\n' +
                        @foreach($voivodes as $voivode)
                            '<option value ="{{$voivode->id}}">{{$voivode->name}}</option>' +
                        @endforeach
                            '                    </select>\n' +
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
                    '<div class="col-md-4">' +
                    '</div>' +
                    '<div class="col-md-4">' +
                    '<div class="form-group">' +
                    '<label class="myLabel">Ilość godzin pokazów</label>' +
                    '<input class="form-control" min="0" type="number" step="0.1" placeholder="Np. 2">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-4">' +
                    '</div>' +
                    '\n' +
                    '<div class="form-group hour_div">' +
                    '</div>' +
                    '            <div class="col-lg-12 button_section">\n' +
                    '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_show" value="Dodaj nowy pokaz" style="width:100%;margin-bottom:1em;font-size:1.1em;font-weight:bold;">' +
                    '            </div>\n' +
                    '        </div>';
                return newElement;
            }
            function buttonHandler(e) {
                if(e.target.id == 'add-new-route') {

                    let newShow = createNewShow(); //otrzymujemy nowy formularz z pokazem.
                    let appendPlace = document.querySelector('.route-here');
                    appendPlace.innerHTML = ''; //czyści container
                    let newRouteContainer = document.createElement('div');
                    newRouteContainer.classList.add('routes-container');
                    appendPlace.appendChild(newShow);

                    $('.city').select2();
                    $('.voivodeship').select2();
                    $('.voivodeship').off('select2:select'); //remove previous event listeners
                    $('.voivodeship').on('select2:select', function (e) {
                        let container = e.target.parentElement.parentElement.parentElement.parentElement;
                        let headerId = e.params.data.id;
                        $.ajax({
                            type: "POST",
                            url: '{{ route('api.getCitiesNames') }}',
                            data: {
                                "id": headerId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                let placeToAppend2 = container.getElementsByClassName('city')[0];
                                placeToAppend2.innerHTML = '';
                                let basicOption = document.createElement('option');
                                basicOption.value = '0';
                                basicOption.textContent = 'Wybierz';
                                placeToAppend2.appendChild(basicOption);
                                for(var i = 0; i < response.length; i++) {
                                    let responseOption = document.createElement('option');
                                    responseOption.value = response[i].id;
                                    responseOption.textContent = response[i].name;
                                    placeToAppend2.appendChild(responseOption);
                                }

                            }
                        });

                    });

                    $('.form_date').datetimepicker({
                        language:  'pl',
                        autoclose: 1,
                        minView : 2,
                        pickTime: false
                    });
                }
                else if(e.target.id == 'add_new_show') {
                    addNewShow();
                    $('.city').select2();
                    $('.voivodeship').select2(); //attach select2 look
                    $('.voivodeship').off('select2:select'); //remove previous event listeners
                    $('.voivodeship').on('select2:select', function (e) {
                        let container = e.target.parentElement.parentElement.parentElement.parentElement;
                        let headerId = e.params.data.id;
                        $.ajax({
                            type: "POST",
                            url: '{{ route('api.getCitiesNames') }}',
                            data: {
                                "id": headerId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                let placeToAppend2 = container.getElementsByClassName('city')[0];
                                placeToAppend2.innerHTML = '';
                                let basicOption = document.createElement('option');
                                basicOption.value = '0';
                                basicOption.textContent = 'Wybierz';
                                placeToAppend2.appendChild(basicOption);
                                for(var i = 0; i < response.length; i++) {
                                    let responseOption = document.createElement('option');
                                    responseOption.value = response[i].id;
                                    responseOption.textContent = response[i].name;
                                    placeToAppend2.appendChild(responseOption);
                                }

                            }
                        });

                    });
                }
                else if(e.target.dataset.remove == 'show') { // click on X glyphicon
                    let showContainer = e.target.parentElement.parentElement.parentElement;
                    removeGivenShow(showContainer);
                }
                else if(e.target.id == "save") {
                    let everythingIsGood = undefined;
                    let voivodeElements = Array.from(document.getElementsByClassName('voivodeship'));
                    let cityElements = Array.from(document.getElementsByClassName('city'));
                    let showElements = Array.from(document.getElementsByClassName('show-hours'));

                    let voivodeArr = [];
                    let cityArr = [];
                    let hourArr = [];
                    voivodeElements.forEach(function(element) {
                        voivodeArr.push(element.options[element.selectedIndex].value);
                    });

                    cityElements.forEach(function(element) {
                        cityArr.push(element.options[element.selectedIndex].value);
                    });

                    showElements.forEach(function(element) {
                       hourArr.push(element.value);
                    });

                    everythingIsGood = formValidation(voivodeArr, cityArr, hourArr);

                    if(everythingIsGood == true) {
                        let formContainer = document.createElement('div');
                        formContainer.innerHTML = '<form method="post" action="" id="user_form"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" value="' + voivodeArr + '" name="voivode"><input type="hidden" value="' + cityArr + '" name="city"><input type="hidden" value="' + hourArr + '" name="city"></form>';
                        let place = document.querySelector('.route-here');
                        place.appendChild(formContainer);
                        let userForm = document.getElementById('user_form');
                        console.log(userForm);
                        // userForm.submit();
                    }
                    else {
                        clearArrays(voivodeArr, cityArr, hourArr);
                        swal('W każdym polu wartości muszą zostać wybrane!');
                    }
                }

            }

            /**
             * This function clear given arrays;
             */
            function clearArrays() {
                let args = arguments;
                for(var i = 0; i < args.length; i++) {
                    args[i] = [];
                }
            }

            /**
             * This function validate form
             */
            function formValidation() {
                let args = arguments;
                let flag = true;
                for(var i = 0; i < args.length; i++) {
                    if(args[i].length != '0') {
                        args[i].forEach(function(value) {
                            if(value == '0' || value == '' || value == null) {
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

            function removeGivenShow(container) {
                let allShows = document.getElementsByClassName('routes-container');
                let lastShowContainer = allShows[allShows.length - 1];
                if(container == lastShowContainer) {
                    addButtonsToPreviousContainer(container);
                    container.parentNode.removeChild(container);
                }
                else {
                    container.parentNode.removeChild(container);
                }
            }

            function addButtonsToPreviousContainer(container) {
                let previousContainer = container.previousElementSibling;
                let placeInPreviousContainer = previousContainer.getElementsByClassName('hour_div')[0];
                let buttonsElement = document.createElement('div');
                buttonsElement.classList.add('col-lg-12');
                buttonsElement.classList.add('button_section');
                buttonsElement.innerHTML = '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_show" value="Dodaj nowy pokaz" style="width:100%;margin-bottom:1em;font-size:1.1em;font-weight:bold;">';
                buttonsElement.appendAfter(placeInPreviousContainer);
            }

            function addNewShow() {
                removeButtonsFromLastShow();
                let routePlace = document.querySelector('.route-here');
                let newShow = createNewShow(); //otrzymujemy nowy formularz z pokazem.
                routePlace.appendChild(newShow);


                $('.form_date').datetimepicker({
                    language:  'pl',
                    autoclose: 1,
                    minView : 2,
                    pickTime: false
                });
            }

            function removeButtonsFromLastShow() {
                let buttonSection = document.getElementsByClassName('button_section')[document.getElementsByClassName('button_section').length - 1];
                if(buttonSection != null) {
                    buttonSection.parentNode.removeChild(buttonSection);
                }
            }

            document.addEventListener('click', buttonHandler);

        });


    </script>
@endsection
