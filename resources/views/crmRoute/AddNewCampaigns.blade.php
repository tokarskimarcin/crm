@extends('layouts.main')
@section('style')

@endsection
@section('content')

    <style>
        .check {
            background: #B0BED9 !important;
        }
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
            margin-bottom: 3em;

            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 90%;
            max-width: 90%;

            line-height: 2em;

        }
    </style>

    {{--Header page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Tworzenie Kampanii</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Wybierz gotową trasę
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="showAllClients">Pokaż wszystkich klientów</label>
                                <input type="checkbox" style="display:inline-block" id="showAllClients">
                            </div>
                            <table id="datatable" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nazwa Klienta</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" style="margin-top:1em;">
                                <label for="weekNumber">Wybierz tydzień</label>
                                <select id="weekNumber" class="form-control">
                                    <option value="0">Wybierz</option>
                                </select>
                            </div>
                            <table id="datatable2" class="thead-inverse table table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Tydzień</th>
                                    <th>Klient</th>
                                    <th>Trasa</th>
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

    <div class="routes-container">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Pokaz Dzień : X
                    </div>
                    <div class="panel-body">
                        <div class="row">
                                <table class="table table-striped thead-inverse table-bordered">
                                    <thead>
                                    <th>Tydzień</th>
                                    <th>Data</th>
                                    <th>Godzina</th>
                                    <th>Województwo</th>
                                    <th>Miasto</th>
                                    <th>Hotel</th>
                                    <th>Hotel</th>
                                    <th>Limit</th>
                                    <th>Akcja</th>
                                    <tbody id="sources">
                                        <tr>
                                            <td>32</td>
                                            <td>2018-03-24</td>
                                            <td>18:00</td>
                                            <td>Mazowieckie</td>
                                            <td>Warszawa</td>
                                            <td>Viktoria</td>
                                            <td>
                                                <select class="form-control">
                                                    <option> Lublin</option>
                                                </select>
                                            </td>
                                            <td><input class="form-control" type="number" value="32"></td>
                                            <td><input type="button" class="btn btn-success" value="Zapisz"></td>
                                        </tr>

                                        <tr>
                                            <td>32</td>
                                            <td>2018-03-24</td>
                                            <td>18:00</td>
                                            <td>Mazowieckie</td>
                                            <td>Warszawa</td>
                                            <td>Viktoria</td>
                                            <td>
                                                <select class="form-control">
                                                    <option> Lublin</option>
                                                </select>
                                            </td>
                                            <td><input class="form-control" type="number" value="32"></td>
                                            <td><input type="button" class="btn btn-success" value="Zapisz"></td>
                                        </tr>
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
        document.addEventListener('DOMContentLoaded', function(event) {

            //This part is responsible for listing every week number into select
            const lastWeekOfYear ={{$lastWeek}};
            const weekSelect = document.querySelector('#weekNumber');
            for(var i = 1; i <= lastWeekOfYear ; i++) {
                let optionElement = document.createElement('option');
                optionElement.value = i;
                optionElement.innerHTML = `${i}`;
                weekSelect.appendChild(optionElement);
            }

            const showAllClientsInput = document.querySelector('#showAllClients');
            const selectedWeekInput = document.querySelector('#weekNumber');
            let id = null; //after user click on 1st table row, it assing clientRouteId to this variable
            let selectedWeek = null;
            let rowIterator = null;
            let colorIterator = 0;
            let showAllClients = null; //this variable indices whether checkbox "Pokaż wszystkich klientó" is checked
            let showOnlyAssigned = null; //This variable indices whether checkbox "Pokaż tylko trasy bez przypisanego hotelu lub godziny" is checked
            let colorArr = ['#e1e4ea', '#81a3ef', '#5a87ed', '#b2f4b8', '#6ee578', '#e1acef', '#c54ae8'];
            let objectArr = [];

            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },
                "rowCallback": function( row, data, index ) {
                    $(row).attr('id', "client_" + data.id);
                    return row;
                },"fnDrawCallback": function(settings) {
                    $('#datatable tbody tr').on('click', function() {
                        if(showAllClients === true) { //all clients checkbox = true + selecting one client
                            showAllClientsInput.checked = false;
                            showAllClients = false;
                        }
                        test = $(this).closest('table');
                        if($(this).hasClass('check')) {
                            $(this).removeClass('check');
                            id = null;
                        }
                        else {
                            test.find('tr.check').removeClass('check');
                            $.each(test.find('.checkbox_info'), function (item, val) {
                                $(val).prop('checked', false);
                            });
                            $(this).addClass('check');
                            id = $(this).attr('id');
                            indexOfUnderscore = id.lastIndexOf('_');
                            id = id.substr(indexOfUnderscore + 1);
                        }
                        rowIterator = null;
                        colorIterator = 0;
                        objectArr = [];
                        // showAllClients = null; //remove effect of show all clients checkbox
                        table2.ajax.reload();
                    })
                },"ajax": {
                    'url': "{{route('api.getClientRoutes')}}",
                    'type': 'POST',
                    'data': function (d) {
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },
                "columns":[
                    {"data":function (data, type, dataToSet) {
                            return data.name;
                        },"name":"name"
                    }
                ]
            });


            //tworzenie nowego diva z opcją ograniczenia 100KM
            function createRouteInfo(data) {
                newElement = document.createElement('div');
                newElement.className = 'routes-container';
                let stringAppend ='        <div class="row">\n' +
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
                for(let i = 0; i < voivodes.length ; i++){
                    stringAppend += '<option value ='+voivodes[i]['id']+'>'+voivodes[i]['name']+'</option>';
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
                if(currentDate != '0') {
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
                    '</div>' +
                    '            <div class="col-lg-12 button_section">\n' +
                    '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_show" value="Dodaj nowy pokaz" style="width:100%;margin-bottom:1em;font-size:1.1em;font-weight:bold;">' +
                    '            </div>\n' +
                    '        </div>';
                newElement.innerHTML = stringAppend;
                return newElement;
            }

            table2 = $('#datatable2').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "fnDrawCallback": function(settings) {
                        $('#datatable2 tbody tr').on('click', function() {
                            if($(this).hasClass('check')) {
                                $(this).removeClass('check');
                                id = 0;
                            }
                            else {
                                $(this).find('tr.check').removeClass('check');
                                $(this).addClass('check');
                                id = $(this).attr('id');
                                id = id.split('_');
                                id = id[1];
                                $.ajax({
                                    type: "POST",
                                    url: '{{ route('api.getReadyRoute') }}',
                                    data: {
                                        "route_id": id
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        createRouteInfo(response);
                                    }
                                });
                            }
                        })
                },
                "rowCallback": function( row, data, index ) {
                    $(row).attr('id', "clientRouteInfoId_" + data.clientRouteId);
                    return row;
                },
                "ajax": {
                    'url': "{{route('api.getClientRouteInfo')}}",
                    'type': 'POST',
                    'data': function (d) {
                        d.id = id;
                        d.showAllClients    = showAllClients;
                        d.showOnlyAssigned  = showOnlyAssigned;
                        d.onlyAccept        = true;
                        d.selectedWeek      = selectedWeek;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },
                "columns":[
                    {"data":function (data, type, dataToSet) {
                            return data.weekOfYear;
                        },"name":"weekOfYear"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.clientName;
                        },"name":"clientName"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.clientRouteName;
                        },"name":"clientRouteName"
                    }
                ]
            });

            function showAllClientsInputHandler(e) {
                const checkedRow = document.querySelector('.check');
                // console.assert(checkedRow, "Brak podswietlonego wiersza");
                if(checkedRow) { //remove row higlight and reset id variable
                    checkedRow.classList.remove('check');
                    id = null;
                }

                if(e.target.checked === true) {
                    showAllClients = true;
                }
                else {
                    showAllClients = false;
                }
                table2.ajax.reload();
            }



            function selectedWeekHandler(e) {
                if(e.target.value != 0) {
                    selectedWeek = e.target.value;
                }
                else {
                    selectedWeek = 0;
                }
                table2.ajax.reload();
            }

            function actionButtonHandler(e) {
                const clientRouteId = e.target.dataset.clientrouteid;
                const url =`{{URL::to('/showClientRoutesStatus')}}`;
                const ourHeaders = new Headers();
                ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                ourHeaders.set('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                const formData = new FormData();
                formData.append('clientRouteId', clientRouteId);
                formData.append('delete', '0');

                fetch(url, {
                    method: 'post',
                    headers: ourHeaders,
                    credentials: "same-origin",
                    body: formData
                }).then(resp => resp.json())
                    .then(resp => {
                        table2.ajax.reload();
                    })

            }

            function actionButtonHandlerAccepted(e) {
                const clientRouteId = e.target.dataset.clientrouteid;
                const url =`{{URL::to('/showClientRoutesStatus')}}`;
                const ourHeaders = new Headers();
                ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                // ourHeaders.set('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                const formData = new FormData();
                formData.append('clientRouteId', clientRouteId);
                formData.append('delete', '1');

                fetch(url, {
                    method: 'post',
                    headers: ourHeaders,
                    credentials: "same-origin",
                    body: formData
                }).then(resp => resp.json())
                    .then(resp => {
                        if(resp == 0) {
                            console.log("Operacja się nie powiodła");
                        }
                        table2.ajax.reload();
                    })
            }
            showAllClientsInput.addEventListener('change', showAllClientsInputHandler);
            selectedWeekInput.addEventListener('change', selectedWeekHandler);

        });
    </script>
@endsection
