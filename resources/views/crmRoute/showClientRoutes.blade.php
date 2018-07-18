{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view shows list of available campaigns,--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: showHotelsAjax, showHotelsGet--}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <link rel="stylesheet" href="{{asset('/css/fixedHeader.dataTables.min.css')}}">
@endsection
@section('content')

    <style>
        .check {
            background: #B0BED9 !important;
        }

        a {
            text-decoration: none !important;
        }
        .alert-info {
            font-size: 1.5em;
        }

        .activated{
            background-color: #449d44 !important;
            color: white !important;
            font-weight: bold;
        }

        .deactivated{
            background-color: #31b0d5 !important;
            color: white !important;
            font-weight: bold;
        }

        .unready{
            background-color: #c9302c !important;
            color: white !important;
            font-weight: bold;
        }
    </style>

    {{--Header page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Plik klientów</div>
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
                    <div class="row">
                        <div class="col-md-12 info-section">
                            <div class="alert alert-info">
                                Moduł plik klientów pozwala na podgląd tras oraz zarządzanie nimi. Tabelę z
                                trasami można filtrować dostępnymi polami jak również filtrować za pomocą dowolnych fraz
                                wpisanych w polu "Szukaj". Trasy dzielą się na: </br>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Niegotowe</strong> oznaczone jako <button class="btn btn-danger" style="font-weight:bold;"s>Niegotowa</button> - Trasa nie jest widocza w "Informacje o kampaniach" oraz nie jest wliczana w "Planowanie wyprzedzenia" </li>
                                    <li class="list-group-item"><strong>Aktywne</strong> oznaczone jako <button class="btn btn-success" style="font-weight:bold;">Aktywna</button> - Trasa jest gotowa do użycia (wszystkie godziny oraz hotele muszą być określone aby mogła być aktywna), pobierane są informacje z PBX odnośnie takich kampanii, wyświetlają się we wszystkich statystykach</li>
                                    <li class="list-group-item"><strong>Zakończone</strong> oznaczone jako <button class="btn btn-info" style="font-weight:bold;">Zakończona</button> - Trasa automatycznie zmienia stan z Aktywnej na Zakończoną, gdy data ostatniego pokazu zostaje osiągnięta</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="addNewClientRoute" class="btn btn-info"
                                    style="margin-bottom: 1em; font-weight: bold;">Przejdź do przypisywania tras
                                klientom
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <table id="datatable" class="thead-inverse table table-striped table-bordered"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nazwa Klienta</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="showAllClients">Pokaż wszystkich klientów</label>
                                <input type="radio" style="display:inline-block" id="showAllClients" checked="checked" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="showOnlyAssigned">Pokaż tylko trasy bez przypisanego hotelu lub
                                    godziny</label>
                                <input type="checkbox" style="display:inline-block" id="showOnlyAssigned">
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group" style="margin-top:1em;">
                                <label for="year">Wybierz rok</label>
                                <select id="year" class="form-control">
                                    <option value="0">Wszystkie</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group" style="margin-top:1em;">
                                <label for="weekNumber">Wybierz tydzień</label>
                                <select id="weekNumber" class="form-control">
                                    <option value="0">Wszystkie</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group" style="margin-top:1em;">
                                <label for="type">Typ</label>
                                <select id="type" class="form-control">
                                    <option value="0">Wszystkie</option>
                                    <option value="1">Wysyłka</option>
                                    <option value="2">Badania</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group" style="margin-top:1em;">
                                <label for="campaignState">Status Kampanii</label>
                                <select id="campaignState" class="form-control">
                                    <option value="-1">Wszystkie</option>
                                    <option value="0">Niegotowe</option>
                                    <option value="1">Aktywne</option>
                                    <option value="2">Zakończone</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-top:2.6em;">
                                <button class="btn btn-default" id="makeReportClient">Generuj Raport Klienta</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table id="datatable2" class="thead-inverse table table-striped row-border" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Tydzień</th>
                                    <th>Klient</th>
                                    <th>Data I pokazu</th>
                                    <th>Trasa</th>
                                    <th>Hotel i godziny</th>
                                    <th>Status trasy</th>
                                    <th>Edycja (Hoteli i godzin)</th>
                                    <th>Edycja (Trasy)</th>
                                    <th>Edycja parametrów (Kampanii)</th>
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

    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Parametry Kampani</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="routes-container">
                        <div id="insertModalHere">

                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success" style="width: 100%" id="saveCampaingOption"
                                    onclick="saveOptions(this)">Zapisz
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <br>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $('#menu-toggle').change(()=>{
            table2.columns.adjust().draw();
        });
        function saveOptions(e) {
            let allRow = document.getElementsByClassName('campainsOption');
            let arrayOfObject = new Array();
            let validation = true;
            for (var i = 0; i < allRow.length; i++) {
                let id = allRow[i].getAttribute('id');
                id = id.split('_');
                id = id[1];
                let department_info_id = 0;
                let departmentInfoSelect = allRow[i].querySelector('.optionDepartment').querySelector('.form-control');
                department_info_id = departmentInfoSelect.options[departmentInfoSelect.selectedIndex].value;
                if (department_info_id == 0) {
                    validation = false;
                    swal('Wybierz oddział');
                    break;
                }
                let limit = allRow[i].querySelector('.optionLimit').querySelector('.form-control').value;
                if (limit == '') {
                    validation = false;
                    swal('Brak Limitów');
                    break;
                }
                // let nrPBX = allRow[i].querySelector('.nrPBX').querySelector('.form-control').value;
                // if (nrPBX == '') {
                //     validation = false;
                //     swal('Brak Nr kampanii PBX');
                //     break;
                // }
                var obj = {id: id, department_info_id: department_info_id, limit: limit};
                arrayOfObject.push(obj);
            }
            //Save campain option
            if (validation) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.saveCampaignOption') }}',
                    data: {
                        "objectOfChange": arrayOfObject
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response == 200) {
                            swal("Opcje Kampanii zostały zapisane pomyślnie")
                            $('#myModal').modal('hide');
                        } else {
                            swal("Wystąpił błąd podczas zapisu, spróbuj ponownie.")
                        }

                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function (event) {


            $('#makeReportClient').on('click',function (e) {
               swal('Kiedyś to wygeneruje raprot')
            });
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

            let yearInput = $('#year');
            let typInput = $('#type');
            let stateInput = $('#campaignState');

            //This part is responsible for listing every week number into select
            const lastWeekOfYear ={{$lastWeek}};
            const weekSelect = document.querySelector('#weekNumber');
            for (var i = 1; i <= lastWeekOfYear; i++) {
                let optionElement = document.createElement('option');
                optionElement.value = i;
                optionElement.innerHTML = `${i}`;
                weekSelect.appendChild(optionElement);
            }

            //this part is responsible for redirect button
            const addNewClientRouteInput = $('#addNewClientRoute');
            addNewClientRouteInput.click((e) => {
                window.location.href = '{{URL::to('/crmRoute_index')}}';
            });

            const showOnlyAssignedInput = $('#showOnlyAssigned');
            const showAllClientsInput = $('#showAllClients');
            const selectedWeekInput = $('#weekNumber');
            let id = -1; //after user click on 1st table row, it assing clientRouteId to this variable
            let rowIterator = null;
            // let colorIterator = 0;
            // let showAllClients = null; //this variable indices whether checkbox "Pokaż wszystkich klientó" is checked
            // let showOnlyAssigned = null; //This variable indices whether checkbox "Pokaż tylko trasy bez przypisanego hotelu lub godziny" is checked
            // let colorArr = ['#e1e4ea', '#81a3ef', '#5a87ed', '#b2f4b8', '#6ee578', '#e1acef', '#c54ae8'];
            let objectArr = [];

            function createModalContent(response, placeToAppend) {
                let departmentsJson = @json($departments);
                let routeContainer = document.createElement('div');
                routeContainer.className = 'campain-container';
                var content = '';
                for (var i = 0; i < response.length; i++) {
                    content += '<div class="row">\n' +
                        '                            <div class="col-lg-12">\n' +
                        '                                <div class="panel panel-default">\n' +
                        '                                    <div class="panel-heading">\n' +
                        '                                        Pokaz Dzień : ' + response[i][0].date +
                        '                                    </div>\n' +
                        '                                    <div class="panel-body">\n' +
                        '                                        <div class="row">\n' +
                        '                                            <table class="table table-striped thead-inverse table-bordered table-modal">\n' +
                        '                                                <thead>\n' +
                        '                                                <th>Tydzień</th>\n' +
                        '                                                <th>Data</th>\n' +
                        '                                                <th>Godzina</th>\n' +
                        '                                                <th>Województwo</th>\n' +
                        '                                                <th>Miasto</th>\n' +
                        '                                                <th>Hotel</th>\n' +
                        '                                                <th>Oddział</th>\n' +
                        '                                                <th style="width: 10%">Limit</th>\n' + /*
                        '                                                <th>Nr kampanii (PBX)</th>\n' +*/
                        '                                            </thead>' +
                        '                                                <tbody>\n';

                    for (var j = 0; j < response[i].length; j++) {
                        let hotel_name = "Brak";
                        if (response[i][j].hotel_info != null) {
                            hotel_name = response[i][j].hotel_info.name;
                        }
                        content += '                                                <tr class="campainsOption" id="routeInfoId_' + response[i][j].id + '">\n' +
                            '                                                    <td>' + response[i][j].weekNumber + '</td>\n' +
                            '                                                    <td>' + response[i][j].date + '</td>\n' +
                            '                                                    <td>' + response[i][j].hour + '</td>\n' +
                            '                                                    <td>' + response[i][j].voivodeName + '</td>\n' +
                            '                                                    <td class="cityName" >' + response[i][j].cityName + '</td>\n' +
                            '                                                    <td>' + hotel_name + '</td>\n' +
                            '                                                    <td class="optionDepartment">\n' +
                            '                                                        <select class="form-control">\n' +
                            '<option value=0> Wybierz </option>';
                        for (var item in departmentsJson) {
                            content += '<option value="' + departmentsJson[item].id + '"';
                            if (response[i][j].department_info_id == departmentsJson[item].id)
                                content += 'selected';
                            content += '>' + departmentsJson[item].department_name + ' ' + departmentsJson[item].type_name + '</option>\n';
                        }
                        content += ' </select>\n' +
                            '                                                    </td>\n' +
                            '                                                    <td class="optionLimit"><input class="form-control" type="number" value="' + response[i][j].limit + '"></td>\n' +
                            /*'<td class="nrPBX"><input class="form-control" type="text" value="'+1+'"></td>'+*/
                            '                                                </tr>\n';
                    }
                    content += '                                                </tbody>\n' +
                        '                                            </table>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                        </div>';
                }

                routeContainer.innerHTML = content;
                placeToAppend.appendChild(routeContainer);

                /* $('.table-modal td.nrPBX > input')
                     .change((e)=>{changeRowsValueOfTable(e)})
                     .blur((e)=>{changeRowsValueOfTable(e)});*/
                /*$('.table-modal td.nrPBX > input').keyup((e)=>{
                    if(!$.isNumeric(e.target.value)){
                        e.target.value = parseInt(e.target.value);
                    }else
                        changeRowsValueOfTable(e);
                });*/
            }

            function changeRowsValueOfTable(e) {
                if (!$.isNumeric(e.target.value) || e.target.value !== "Brak") {
                    e.target.value = parseInt(e.target.value);
                    if (e.target.value === "NaN")
                        e.target.value = "Brak";
                }
                let changedInput = $(e.target);
                let tableChangedRow = changedInput.parent().parent();
                let cityNameOfChangedRow = tableChangedRow.find(".cityName").text();
                let tableContainingChangedInput = changedInput.parents().has('table').first().find('table');
                let tableRowsToChange = tableContainingChangedInput.find('tr[id!=' + tableChangedRow.prop("id") + ']').has('.cityName:contains(' + cityNameOfChangedRow + ')');
                let inputsToChange = tableRowsToChange.find('.nrPBX > input');
                inputsToChange.each(function () {
                    $(this).val(e.target.value);
                });
            }

            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function (settings) {
                },
                "rowCallback": function (row, data, index) {
                    $(row).attr('id', "client_" + data.id);
                    return row;
                }, "fnDrawCallback": function (settings) {
                    $('#datatable tbody tr').click(function () {
                        if (showAllClientsInput.prop('checked') === true) { //all clients checkbox = true + selecting one client
                            showAllClientsInput.prop('checked', false)
                        }
                        test = $(this).closest('table');
                        if ($(this).hasClass('check')) {
                            $(this).removeClass('check');
                            id = -1;
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
                        // colorIterator = 0;
                        objectArr = [];
                        // showAllClients = null; //remove effect of show all clients checkbox

                        table2.ajax.reload();
                    });
                    if (sessionStorage.getItem('idOfClient')) {
                        let idOfClient = sessionStorage.getItem('idOfClient');
                        const allClientsInTable = document.querySelectorAll('#datatable tr');
                        allClientsInTable.forEach(client => {
                            if (client.id == idOfClient) {
                                client.classList.add('check');
                                const clientIdNotTrimmed = client.id;
                                indexOfUnderscore = clientIdNotTrimmed.lastIndexOf('_');
                                id = clientIdNotTrimmed.substr(indexOfUnderscore + 1);
                                sessionStorage.removeItem('idOfClient');
                            }
                        });
                        table2.ajax.reload();
                    }
                }, "ajax": {
                    'url': "{{route('api.getClientRoutes')}}",
                    'type': 'POST',
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },
                "columns": [
                    {
                        "data": function (data, type, dataToSet) {
                            return data.name;
                        }, "name": "name"
                    }
                ]
            });


            table2 = $('#datatable2').DataTable({
                autoWidth: true,
                processing: true,
                serverSide: true,
                scrollY: '45vh',
                scrollX: true,
                fnDrawCallback: function (settings) {
                    objectArr = [];
                    $('#datatable2 select').change(changeStatus);

                    $/*('.action-buttons-0').click(actionButtonHandler);
                    $('.action-buttons-1').click(actionButtonHandlerAccepted);
                    $('.action-buttons-2').click(actionButtonHandlerFinished);*/

                    $('.show-modal-with-data').click(function (e) {
                        let selectTR = e.currentTarget.parentNode.parentNode;
                        let routeId = $(selectTR).closest('tr').prop('id');
                        routeId = routeId.split('_');
                        routeId = routeId[1];
                        $.ajax({
                            type: "POST",
                            url: '{{ route('api.getReadyRoute') }}',
                            data: {
                                "route_id": routeId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                let placeToAppend = document.querySelector('#insertModalHere');
                                placeToAppend.innerHTML = '';
                                createModalContent(response, placeToAppend);
                                $('#myModal').modal('show');
                            }
                        });
                    });
                },
                "rowCallback": function (row, data, index) {
                    if (sessionStorage.getItem('search')) {
                        let searchBox = document.querySelector('input[type="search"][aria-controls="datatable2"]');
                        let searchInfo = sessionStorage.getItem('search');
                        searchBox.value = searchInfo;
                        sessionStorage.removeItem('search');
                        table2.ajax.reload();
                    }
                    /*if (data.status == 0) {
                        row.style.backgroundColor = "#ffc6c6";
                    }
                    else if (data.status == 2) {
                        row.style.backgroundColor = "#7cf76c";
                    }
                    else {
                        row.style.backgroundColor = "#b3c7f4";
                    }*/
                    $(row).attr('id', "clientRouteInfoId_" + data.client_route_id);
                    return row;
                },
                ajax: {
                    'url': "{{route('api.getClientRouteInfo')}}",
                    'type': 'POST',
                    'data': function (d) {
                        d.id = id;
                        d.showOnlyAssigned = showOnlyAssignedInput.prop('checked');
                        d.year = yearInput.val();
                        d.selectedWeek = selectedWeekInput.val();
                        d.typ = typInput.val();
                        d.state = stateInput.val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },
                columns: [
                    {"data": "weekOfYear"},
                    {"data": "clientName"},
                    {"data": "date"},
                    {
                        "data": function (data, type, dataToSet) {
                            let finalName = '';
                            if (data.type == '1') {
                                finalName = data.route_name + ' (B)';
                            }
                            else {
                                finalName = data.route_name + ' (W)';
                            }
                            return finalName;
                        }, "name": "route_name"
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            if (data.hotelOrHour) {
                                return '<span style="color: darkgreen;">Przypisane</span>';
                            }
                            else {
                                return '<span style="color: red;">Nieprzypisane</span>';
                            }
                        }, "name": "hotelOrHour"
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            disabledOption = '';
                            if(!data.hotelOrHour){
                                disabledOption = 'disabled';
                            }
                            option1 = '<option value="0" '+disabledOption+' >Niegotowa</option>\n';
                            option2 = '<option value="1" '+disabledOption+'>Aktywna</option>\n';
                            option3 = '<option value="2" '+disabledOption+'>Zakończona</option>\n';
                            color='';
                            type = '';

                            if(data.status == 0){
                                option1 = '<option value="0" selected="selected">Niegotowa</button></option>\n';
                                color = 'unready';

                            }else if(data.status ==1){
                                option2 = '<option value="1" selected="selected" '+disabledOption+'>Aktywna</option>\n';
                                color = 'activated';

                            }else {
                                option3 = '<option value="2" selected="selected" '+disabledOption+'>Zakończona</option>\n';
                                color = 'deactivated';
                                type = 'disabled';
                            }
                            select  ='<select class="form-control '+ color+'" data-status="'+data.status+'" data-clientRouteId="'+ data.client_route_id+'" '+type+'>' +
                                option1 +
                                option2 +
                                option3 +
                                '</select>';
                            return select;
                            /*if (data.status == 0) {
                                return '<button data-clientRouteId="' + data.client_route_id + '" class="btn btn-success action-buttons-0" style="width:100%">Aktywuj kampanie</button>';
                            }
                            else if (data.status == 2) {
                                return '<button data-clientRouteId="' + data.client_route_id + '" class="btn btn-primary action-buttons-2" style="width:100%">Trasa niegotowa</button>';
                            }
                            else {
                                return '<button data-clientRouteId="' + data.client_route_id + '" class="btn btn-warning action-buttons-1" style="width:100%">Zakończ kampanie</button>';
                            }
*/
                        }, "name": "status", searchable: false,width:'15%'
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            type = '';
                            if(data.status == 2)
                                type = 'disabled';
                            return '<a href="{{URL::to("/specificRoute")}}/' + data.client_route_id + '"><button class="btn btn-info btn-block" data-type="1" '+type+'><span class="glyphicon glyphicon-edit"></span> Edytuj</button></a>';
                        }, "name": "link", width: '10%', searchable: false, orderable: false
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            if(data.status == 2)
                                type = 'disabled';
                            return '<a href="{{URL::to("/specificRouteEdit")}}/' + data.client_route_id + '"><button class="btn btn-info btn-block" data-type="2" '+type+'><span class="glyphicon glyphicon-edit"></span> Edytuj</button></a>';
                        }, "name": "link", width: '10%', searchable: false, orderable: false
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            if(data.status == 2)
                                type = 'disabled';
                            return '<button class="btn btn-info btn-block show-modal-with-data" '+type+'><span class="glyphicon glyphicon-edit " data-route_id ="' + data.client_route_id + '" ></span> Edytuj</button>';
                        }, "name": "link", width: '10%', searchable: false, orderable: false

                    }
                ]
            });

            function showAllClientsInputHandler(e) {
                const checkedRow = document.querySelector('.check');
                // console.assert(checkedRow, "Brak podswietlonego wiersza");
                if (checkedRow) { //remove row higlight and reset id variable
                    checkedRow.classList.remove('check');
                    id = -1;
                }
                table2.ajax.reload();
            }

            function changeStatus(e) {
                    swal({
                        title: "Czy na pewno?",
                        type: "warning",
                        text: "Czy chcesz zmienić status trasy na "+$(e.target).find('option:selected').text()+"?",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Tak, zmień!",

                    }).then((result) => {
                        if (result.value) {
                            let selectedValue = parseInt($(e.target).val());
                            status = selectedValue-1 < 0 ? 2 : selectedValue-1;
                            const clientRouteId = $(e.target).data('clientrouteid');
                            const url = `{{URL::to('/showClientRoutesStatus')}}`;
                            const ourHeaders = new Headers();
                            ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                            ourHeaders.set('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                            const formData = new FormData();
                            formData.append('clientRouteId', clientRouteId);
                            formData.append('delete', status);

                            fetch(url, {
                                method: 'post',
                                headers: ourHeaders,
                                credentials: "same-origin",
                                body: formData
                            }).then(resp => resp.text())
                                .then(resp => {
                                    if (resp == 0) {
                                        notify("Operacja się nie powiodła", 'danger');
                                        $(e.target).val($(e.target).data('status'));
                                    }
                                    else {
                                        notify('Trasa <strong>'+$(e.target).find('option:selected').text()+'</strong>', 'success');

                                        $(e.target).data('status',$(e.target).val());
                                    }
                                    table2.ajax.reload();
                                });
                        }else{
                            $(e.target).val($(e.target).data('status'));
                        }
                    });
                }
            /**
             * This function changes campaign status from nto ready to started.
             */
            function actionButtonHandler(e) {
                swal({
                    title: "Czy na pewno?",
                    type: "warning",
                    text: "Czy chcesz aktywować kampanię?",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Tak, aktywuj!",

                }).then((result) => {
                    if (result.value) {
                        const clientRouteId = e.target.dataset.clientrouteid;
                        const url = `{{URL::to('/showClientRoutesStatus')}}`;
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
                        }).then(resp => resp.text())
                            .then(resp => {
                                if (resp == 0) {
                                    notify("Operacja się nie powiodła", 'danger');
                                }
                                else {
                                    notify(`<strong>Kampania została aktywowana</strong>`, 'success');
                                    $(e.target).prop('class', "btn btn-warning action-buttons-1");
                                    $(e.target).text('Zakończ kampanie');
                                    $(e.target).off();
                                    $(e.target).click(actionButtonHandlerAccepted);
                                }
                                //table2.ajax.reload();
                            });
                    }
                });
            }

            /**
             * This function changes campaign status from started to finished
             */
            function actionButtonHandlerAccepted(e) {
                swal({
                    title: "Czy na pewno?",
                    type: "warning",
                    text: "Czy chcesz zakończyć kampanię?",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Tak, zakończ!",

                }).then((result) => {
                    if (result.value) {
                        const clientRouteId = e.target.dataset.clientrouteid;
                        const url = `{{URL::to('/showClientRoutesStatus')}}`;
                        const ourHeaders = new Headers();
                        ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        const formData = new FormData();
                        formData.append('clientRouteId', clientRouteId);
                        formData.append('delete', '1');

                        fetch(url, {
                            method: 'post',
                            headers: ourHeaders,
                            credentials: "same-origin",
                            body: formData
                        }).then(resp => resp.text())
                            .then(resp => {
                                if (resp == 0) {
                                    notify("Operacja się nie powiodła", 'danger');
                                }
                                else {
                                    notify(`<strong>Kampania została zakończona</strong>`, 'success');
                                    $(e.target).prop('class', "btn btn-primary action-buttons-2");
                                    $(e.target).text('Trasa niegotowa');
                                    $(e.target).off();
                                    $(e.target).click(actionButtonHandlerFinished);
                                }
                                //table2.ajax.reload();
                            })
                    }
                });
            }

            /**
             * This function changes campaign status from finished to not ready
             */
            function actionButtonHandlerFinished(e) {
                swal({
                    title: "Czy na pewno?",
                    type: "warning",
                    text: "Czy chcesz ustawić kampanię na niegotową?",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Tak, ustaw na niegotową!",

                }).then((result) => {
                    if (result.value) {
                        const clientRouteId = e.target.dataset.clientrouteid;
                        const url = `{{URL::to('/showClientRoutesStatus')}}`;
                        const ourHeaders = new Headers();
                        ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        const formData = new FormData();
                        formData.append('clientRouteId', clientRouteId);
                        formData.append('delete', '2');

                        fetch(url, {
                            method: 'post',
                            headers: ourHeaders,
                            credentials: "same-origin",
                            body: formData
                        }).then(resp => resp.text())
                            .then(resp => {
                                if (resp == 0) {
                                    notify("Operacja się nie powiodła", 'danger');
                                }
                                else {
                                    notify(`<strong>Kampania została przeniesiona w stan "nie gotowa"</strong>`, 'success');
                                    $(e.target).prop('class', "btn btn-success action-buttons-0");
                                    $(e.target).text('Aktywuj kampanię');
                                    $(e.target).off();
                                    $(e.target).click(actionButtonHandler);
                                }
                                //table2.ajax.reload();
                            })
                    }
                });
            }

            /**
             * @param e
             * This method append list of weeks in selected year to weekInput
             */
            function yearHandler(e) {
                const selectedYear = e.target.value;

                if (selectedYear > 0) {
                    //part responsible for sending to server info about selected year
                    const header = new Headers();
                    header.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                    let data = new FormData();
                    data.append('year', selectedYear);

                    const url = '{{route('api.getWeeks')}}';

                    fetch(url, {
                        method: 'post',
                        headers: header,
                        credentials: "same-origin",
                        body: data
                    })
                        .then(response => response.json())
                        .then(response => {
                            const weeksInYear = response;
                            selectedWeekInput.innerHTML = '';
                            const basicOptionElement = document.createElement('option');
                            basicOptionElement.value = 0;
                            basicOptionElement.textContent = 'Wybierz';
                            selectedWeekInput.appendChild(basicOptionElement);
                            for (let i = 1; i <= weeksInYear + 1; i++) { //we are iterating to weeksInYear+1 because we are getting week number for 30.12, and in 31.12 can be monday(additional week)
                                const optionElement = document.createElement('option');
                                optionElement.value = i;
                                optionElement.textContent = i;
                                selectedWeekInput.appendChild(optionElement);
                            }
                        })
                        .catch(err => console.log(err));
                    table2.ajax.reload();
                }

            }

            /**
             * This function refresh datatable after changing a type.
             */

            /**
             * This funciton sets values of inputs to session storage on page leave
             */
            function setItemsToSeessionStorage() {
                const yearInput = document.querySelector('#year');
                const weekNumber = document.querySelector('#weekNumber');
                const type = document.querySelector('#type');
                const campaignState = document.querySelector('#campaignState');
                const showAllClientsCheckbox = document.querySelector('#showAllClients');
                const showOnlyAssignedCheckbox = document.querySelector('#showOnlyAssigned');

                if (document.querySelector('.check')) {
                    let idOfClient = document.querySelector('.check').id;
                    sessionStorage.setItem('idOfClient', idOfClient);
                }

                const searchBox = document.querySelector('input[type="search"][aria-controls="datatable2"');
                sessionStorage.setItem('search', searchBox.value);

                sessionStorage.setItem('year', yearInput.options[yearInput.selectedIndex].value);
                sessionStorage.setItem('weekNumber', weekNumber.options[weekNumber.selectedIndex].value);
                sessionStorage.setItem('type', type.options[type.selectedIndex].value);
                sessionStorage.setItem('campaignState', campaignState.options[campaignState.selectedIndex].value);
                sessionStorage.setItem('showAllClients', showAllClientsCheckbox.checked);
                sessionStorage.setItem('showOnlyAssigned', showOnlyAssignedCheckbox.checked);
            }

            /**
             * This function sets input values from sessionStorage
             */
            (function setValuesFromSessionStorage() {
                let somethingChanged = false;
                if (sessionStorage.getItem('addnotation')) {
                    const adnotation = sessionStorage.getItem('addnotation');

                    $.notify({
                        // options
                        message: adnotation
                    }, {
                        // settings
                        type: 'success'
                    });
                    sessionStorage.removeItem('addnotation');
                }

                const yearInput = document.querySelector('#year');
                if (sessionStorage.getItem('year')) {
                    const year = sessionStorage.getItem('year');
                    somethingChanged = year !== '0' ? true : somethingChanged;
                    for (let i = 0; i < yearInput.length; i++) {
                        if (yearInput[i].value == year) {
                            yearInput[i].selected = true;
                        }
                    }
                    sessionStorage.removeItem('year');
                }


                const weekNumber = document.querySelector('#weekNumber');
                if (sessionStorage.getItem('weekNumber')) {
                    const week = sessionStorage.getItem('weekNumber');
                    somethingChanged = week !== '0' ? true : somethingChanged;
                    for (let i = 0; i < weekNumber.length; i++) {
                        if (weekNumber[i].value == week) {
                            weekNumber[i].selected = true;
                            selectedWeek = weekNumber[i].value;
                        }
                    }
                    sessionStorage.removeItem('weekNumber');
                }

                const type = document.querySelector('#type');
                if (sessionStorage.getItem('type')) {
                    const typ = sessionStorage.getItem('type');
                    somethingChanged = typ !== '0' ? true : somethingChanged;
                    for (let i = 0; i < type.length; i++) {
                        if (type[i].value == typ) {
                            type[i].selected = true;
                        }
                    }
                    sessionStorage.removeItem('type');
                }

                const campaignState = document.querySelector('#campaignState');
                if (sessionStorage.getItem('campaignState')) {
                    const state = sessionStorage.getItem('campaignState');
                    somethingChanged = state !== '-1' ? true : somethingChanged;
                    for (let i = 0; i < campaignState.length; i++) {
                        if (campaignState[i].value == state) {
                            campaignState[i].selected = true;
                        }
                    }
                    sessionStorage.removeItem('campaignState');
                }

                let showAllClientsCheckbox = document.querySelector('#showAllClients');
                if (sessionStorage.getItem('showAllClients')) {
                    const isChecked = sessionStorage.getItem('showAllClients');
                    somethingChanged = isChecked === 'true' ? true : somethingChanged;
                    if (isChecked == 'false') {
                        showAllClientsCheckbox.checked = false;
                    }
                    else {
                        showAllClientsCheckbox.checked = true;
                    }
                    sessionStorage.removeItem('showAllClients');
                }

                if (sessionStorage.getItem('showOnlyAssigned')) {
                    const isChecked = sessionStorage.getItem('showOnlyAssigned');
                    somethingChanged = isChecked === 'true' ? true : somethingChanged;
                    if (isChecked == 'false') {
                        showOnlyAssignedInput.prop('checked', false);
                    }
                    else {
                        showOnlyAssignedInput.prop('checked', true);
                    }
                    sessionStorage.removeItem('showOnlyAssigned');
                }

                if(somethingChanged) {
                    table2.ajax.reload();
                }
            })();

            showAllClientsInput.change(showAllClientsInputHandler);
            showOnlyAssignedInput.change(() => {
                table2.ajax.reload();
            });
            selectedWeekInput.change(() => {
                table2.ajax.reload();
            });

            yearInput.change(yearHandler);
            typInput.change(() => {
                table2.ajax.reload();
            });
            stateInput.change(() => {
                table2.ajax.reload();
            });

            window.addEventListener('pagehide', setItemsToSeessionStorage);

            function globalClickHandler(e) {

                //after clicking on hotels and hours edit, we append to localStorage info about givenRole
                /*if(e.target.dataset.type == 1) {
                    const clickedRow = e.target.parentElement.parentElement.parentElement;
                    const weekNumber = $(clickedRow).children().get(0).innerHTML;
                    const clientName = $(clickedRow).children().get(1).innerHTML;
                    const firstShowDate = $(clickedRow).children().get(2).innerHTML;
                    const routeName = $(clickedRow).children().get(3).innerHTML;
                    let routeId = clickedRow.closest('tr').prop('id');
                    routeId = routeId.split('_');
                    routeId = routeId[1];
                    localStorage.setItem('weekNumber_' + routeId, weekNumber);
                    localStorage.setItem('clientName_' + routeId, clientName);
                    localStorage.setItem('firstShowDate_' + routeId, firstShowDate);
                    localStorage.setItem('routeName_' + routeId, routeName);
                }*/

                //after clicking on route edit, we append to sessionStorage info about givenRole
                if(e.target.dataset.type == 2) {
                }

            }

            document.addEventListener('click', globalClickHandler);

        });
    </script>
@endsection
