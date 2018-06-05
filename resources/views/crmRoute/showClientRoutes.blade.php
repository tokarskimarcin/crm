@extends('layouts.main')
@section('style')

@endsection
@section('content')

    <style>
        .check {
            background: #B0BED9 !important;
        }
    </style>

{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Podgląd Tras</div>
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
                            <div class="form-group">
                                <label for="showOnlyAssigned">Pokaż tylko trasy bez przypisanego hotelu lub godziny</label>
                                <input type="checkbox" style="display:inline-block" id="showOnlyAssigned">
                            </div>
                            <div class="form-group" style="margin-top:1em;">
                                <label for="weekNumber">Wybierz tydzień</label>
                                <select id="weekNumber" class="form-control">
                                    <option value="0">Wybierz</option>
                                </select>
                            </div>
                            <table id="datatable2" class="thead-inverse table table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Numer tygodnia</th>
                                    <th>Nazwa Klienta</th>
                                    <th>Miasto</th>
                                    <th>Hotel</th>
                                    <th>Data</th>
                                    <th>Godzina</th>
                                    <th>Podgląd</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button id="addNewClientRoute" class="btn btn-info">Przejdź do przypisywania tras klientom</button>
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

            //this part is responsible for redirect button
            const addNewClientRouteInput = document.querySelector('#addNewClientRoute');
            addNewClientRouteInput.addEventListener('click',(e) => {
                window.location.href = '{{URL::to('/crmRoute_index')}}';
            });


            const showOnlyAssignedInput = document.querySelector('#showOnlyAssigned');
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
                        $('table tbody tr').on('click', function() {
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



            table2 = $('#datatable2').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "fnDrawCallback": function(settings) {
                    objectArr = [];
                },
                "rowCallback": function( row, data, index ) {
                    $(row).attr('id', "clientRouteInfoId_" + data.id);
                    // if(data.client_route_id != rowIterator) {
                    //     rowIterator = data.client_route_id;
                    //     colorIterator++;
                    // }
                    // if(colorIterator == colorArr.length) {
                    //     colorIterator = 0;
                    // }
                    //
                    // let clientObj = {
                    //     clientRouteId: rowIterator,
                    //     color: colorIterator
                    // };
                    // let flag = false;
                    // objectArr.forEach(obj => {
                    //     if(obj.clientRouteId == rowIterator) {
                    //         $(row).css( "background-color",colorArr[obj.color]);
                    //         flag = true;
                    //         console.log('clientRouteId: ' + obj.clientRouteId + ' color: ' + obj.color);
                    //         console.log(objectArr);
                    //     }
                    // });
                    //
                    // if(flag == false) {
                    //     $(row).css( "background-color",colorArr[colorIterator]);
                    // }
                    // objectArr.push(clientObj);

                    return row;
                },
                "ajax": {
                    'url': "{{route('api.getClientRouteInfo')}}",
                    'type': 'POST',
                    'data': function (d) {
                        d.id = id;
                        d.showAllClients = showAllClients;
                        d.showOnlyAssigned = showOnlyAssigned;
                        d.selectedWeek = selectedWeek;
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
                            return data.cityName;
                        },"name":"cityName"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.hotelName;
                        },"name":"hotelName"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.date;
                        },"name":"date"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.hour;
                        },"name":"hour"
                    },
                    {"data":function (data, type, dataToSet) {
                            return '<a href="{{URL::to("/specificRoute")}}/' + data.client_route_id + '">Podgląd</a>';
                        },"name":"link"
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

            function showOnlyAssignedHandler(e) {
                if(e.target.checked === true) {
                    showOnlyAssigned = true;
                }
                else {
                    showOnlyAssigned = false;
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

            showAllClientsInput.addEventListener('change', showAllClientsInputHandler);
            showOnlyAssignedInput.addEventListener('change', showOnlyAssignedHandler);
            selectedWeekInput.addEventListener('change', selectedWeekHandler);
        });
    </script>
@endsection
