@php
    /**
     * Created by PhpStorm.
     * User: veronaprogramista
     * Date: 17.10.18
     * Time: 08:49
     */
@endphp

{{--/*--}}
{{--*@category: ,--}}
{{--*@info: This template view is for copy purpose--}}
{{--*@controller: ,--}}
{{--*@methods: , --}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <link rel="stylesheet" href="{{asset('/css/fixedHeader.dataTables.min.css')}}">
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/assets/css/VCtooltip.css')}}">
    <style>
        .VCtooltip .well:hover {
            background-color: rgba(185,185,185,0.75) !important;
            cursor: help;
        }
        .bootstrap-select > .dropdown-menu{
            left: 0 !important;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">Statystyki usuniętych zgłoszeń</div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel ze statystykami usuniętych zgłoszeń
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <label>Od:
                    <div class='input-group date' id='startDatetimepicker' >
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input type='text' class="form-control" name="date_start" value="{{date('Y-m-')}}01" readonly/>
                    </div></label>

                </div>
                <div class="col-md-2">
                    <label>Do:
                    <div class='input-group date' id='stopDatetimepicker' >
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input type='text' class="form-control" name="date_stop" value="{{date('Y-m-').date('t')}}" readonly/>
                    </div></label>
                </div>
                <div class="col-md-3">
                    @if(isset($departments))
                        <label for="departmentsSelect">Oddział:
                        <select class="form-control selectpicker" id="departmentsSelect" name="departmentsSelect">
                            <option value="0">Wszystkie</option>
                            @foreach($departments as $dep)
                                <option value="{{$dep->id}}" >{{$dep->departments->name}} {{$dep->department_type->name}}</option>
                            @endforeach
                        </select>
                        </label>
                    @endif
                </div>
                <div class="col-md-3">
                    <label for="notificationsTypeSelect">Zgłoszenia:
                        <select class="form-control selectpicker" id="notificationsTypeSelect" name="notificationsTypeSelect">
                            <option value="0">Wszystkie</option>
                            <option value="1">Usunięte przez zgłaszającego</option>
                            <option value="2">Usunięte nie przez zgłaszającego</option>
                        </select>
                    </label>
                </div>
                <div class="col-md-2" align="right">
                    <div class="VCtooltip VCtooltip-left" align="right">
                        <div class="well well-sm" style="border-radius: 10%; background-color: #5bc0de; color: white; margin-bottom: 0;">Legenda <span class="glyphicon glyphicon-info-sign"></span></div>
                        <span class="tooltiptext">
                            <div class="alert alert-info">
                                Tabela po <strong>lewej</strong> stronie zawiera listę osób zgłaszających problemy, ich główne oddziały i liczbę ich zgłoszeń, które zostały usunięte.<br>
                                Po naciśnięciu przycisku <button class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button> zostaje zakutalizowana tabela po <strong>prawej</strong> o informacje o usuniętych zgłoszeniach wybranej osoby.<br>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <table id="removedNotificationsCountDatatable" class="datatable table display compact"  style="width:100%;">
                        <thead>
                        <tr>
                            <th>Osoba zgłaszająca</th>
                            <th>Oddział</th>
                            <th>Lb. usuniętych<br> zgłoszeń</th>
                            <th>Pokaż zgłoszenia</th>
                        </tr>
                        </thead>
                        <tbody >

                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table id="removedNotificationsDatatable" class="datatable table display compact"  style="width:100%;">
                        <thead>
                        <tr>
                            <th>Data usunięcia</th>
                            <th>Usunięty przez</th>
                            <th>Zgłoszony przez</th>
                            <th>Tytuł</th>
                        </tr>
                        </thead>
                        <tbody >

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/moment.js')}}"></script>
    <script>
        $(document).ready(function () {
            let VARIABLES = {
                selectedUser: null,
                jQElements: {
                    departmentsSelect: $('#departmentsSelect'),
                    notificationsTypeSelect: $('#notificationsTypeSelect'),
                    startDatetimepicker: $('#startDatetimepicker').datetimepicker({
                        language: 'pl',
                        minView: 2,
                        startView: 2,
                        format: 'yyyy-mm-dd',
                        endDate: moment().format('YYYY-MM-DD')
                    }),
                    stopDatetimepicker: $('#stopDatetimepicker').datetimepicker({
                        language: 'pl',
                        minView: 2,
                        startView: 2,
                        format: 'yyyy-mm-dd',
                        endDate: moment().endOf('month').format('YYYY-MM-DD')
                    })
                },
                DATA_TABLES: {
                    removedNotificationsCount: {
                        data: {
                            removedNotificationsCountStatistics: null
                        },
                        table: $('#removedNotificationsCountDatatable'),
                        dataTable: $('#removedNotificationsCountDatatable').DataTable({
                            scrollX: true,
                            scrollY: '70vh',
                            scrollCollapse: true,
                            processing: true,

                            language: {
                                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                            },
                            order: [[2,'desc']],
                            columnDefs:[
                                {
                                    targets: 2,
                                    className: 'dt-body-center'
                                }
                            ],
                            columns:[
                                {data: 'person', width: "30%"},
                                {data: 'department', width: "30%"},
                                {data: 'removedNotificationsCount', width: "15%"},
                                {data: function (data) {
                                    let showRemovedNotifications = $('<button>')
                                        .addClass('showRemovedNotificationsButton btn btn-block btn-default')
                                        .append($('<span>').addClass('glyphicon glyphicon-search'))
                                        .attr('data-user-id',data.user_id);
                                        return showRemovedNotifications.prop('outerHTML');
                                    }, name:'showNotifications', orderable: false, searchable: false, width: "15%"}
                            ],
                            fnDrawCallback: function () {
                                $('.showRemovedNotificationsButton').click(function (e) {
                                    FUNCTIONS.EVENT_HANDLERS.showRemovedNotificationsButtonHandler.click(e);
                                });
                            },
                            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                            }
                        }),
                        getData: function () {
                            return FUNCTIONS.AJAXs.removedNotificationsCountStatisticsAjax().then(function (response) {
                                return response;
                            });
                        },
                        setTableData: function (data){
                            FUNCTIONS.setTableData(data, this)
                        },
                        ajaxReload: function () {
                            FUNCTIONS.ajaxReload(this);
                        }
                    },
                    removedNotifications: {
                        data: {
                            removedNotifications: null
                        },
                        table: $('#removedNotificationsDatatable'),
                        dataTable: $('#removedNotificationsDatatable').DataTable({
                            scrollX: true,
                            scrollY: '70vh',
                            scrollCollapse: true,
                            processing: true,
                            language: {
                                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                            },
                            order: [[0,'desc']],
                            columns:[
                                {data: 'remove_date', width: "15%"},
                                {data: 'removedBy', width: "30%"},
                                {data: 'notifiedBy', width: "30%"},
                                {data: 'title', width: "25%"}
                            ],
                            fnDrawCallback: function () {
                            },
                            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                            }
                        }),
                        getData: function () {
                            return FUNCTIONS.AJAXs.removedNotificationsAjax().then(function (response) {
                                return response;
                            });
                        },
                        setTableData: function (data){
                            FUNCTIONS.setTableData(data, this)
                        },
                        ajaxReload: function () {
                            FUNCTIONS.ajaxReload(this);
                        }
                    }
                }
            };

            let FUNCTIONS = {
                /* function grups should be before other functions which aren't grouped */
                EVENT_HANDLERS: {
                    showRemovedNotificationsButtonHandler:  {
                        click: function (e) {
                            VARIABLES.selectedUser = $(e.target).data('user-id');
                            VARIABLES.DATA_TABLES.removedNotifications.ajaxReload();
                        }
                    },
                    callEvents: function () {
                        (function startDatetimepickerHandler() {
                            VARIABLES.jQElements.startDatetimepicker.datetimepicker().on('changeDate',function () {
                               FUNCTIONS.reloadTables();
                            });
                        })();

                        (function stopDatetimepickerHandler() {
                            VARIABLES.jQElements.stopDatetimepicker.datetimepicker().on('changeDate',function () {
                                FUNCTIONS.reloadTables();
                            });
                        })();

                        (function departmentsSelectHandler() {
                            VARIABLES.jQElements.departmentsSelect.on('changed.bs.select',function () {
                                FUNCTIONS.reloadTables();
                            });
                        })();
                        (function notificationsTypeSelectHandler() {
                            VARIABLES.jQElements.notificationsTypeSelect.on('changed.bs.select',function () {
                                FUNCTIONS.reloadTables();
                            });
                        })();
                    }
                },
                AJAXs: {
                    removedNotificationsCountStatisticsAjax: function() {
                        return $.ajax({
                            url: "{{ route('api.removedNotificationsCountStatisticsAjax') }}",
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                                dateStart: VARIABLES.jQElements.startDatetimepicker.find('input').val(),
                                dateStop: VARIABLES.jQElements.stopDatetimepicker.find('input').val(),
                                departmentsSelect: VARIABLES.jQElements.departmentsSelect.val(),
                                notificationsTypeSelect: VARIABLES.jQElements.notificationsTypeSelect.val()
                            },
                            success: function (response) {
                                VARIABLES.DATA_TABLES.removedNotificationsCount.data.removedNotificationsCountStatistics = response;
                                return response;
                            },
                            error: function (jqXHR, textStatus, thrownError) {
                                console.log(jqXHR);
                                console.log('textStatus: ' + textStatus);
                                console.log('thrownError: ' + thrownError);
                                swal({
                                    type: 'error',
                                    title: 'Błąd ' + jqXHR.status,
                                    text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                                });
                            }
                        });
                    },
                    removedNotificationsAjax: function() {
                        return $.ajax({
                            url: "{{ route('api.removedNotificationsAjax') }}",
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                                dateStart: VARIABLES.jQElements.startDatetimepicker.find('input').val(),
                                dateStop: VARIABLES.jQElements.stopDatetimepicker.find('input').val(),
                                notificationsTypeSelect: VARIABLES.jQElements.notificationsTypeSelect.val(),
                                selectedUser: VARIABLES.selectedUser,
                            },
                            success: function (response) {
                                VARIABLES.DATA_TABLES.removedNotifications.data.removedNotifications = response;
                                return response;
                            },
                            error: function (jqXHR, textStatus, thrownError) {
                                console.log(jqXHR);
                                console.log('textStatus: ' + textStatus);
                                console.log('thrownError: ' + thrownError);
                                swal({
                                    type: 'error',
                                    title: 'Błąd ' + jqXHR.status,
                                    text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                                });
                            }
                        });
                    }},
                reloadTables(){
                    VARIABLES.DATA_TABLES.removedNotificationsCount.ajaxReload();
                    VARIABLES.DATA_TABLES.removedNotifications.dataTable.clear();
                    VARIABLES.DATA_TABLES.removedNotifications.dataTable.draw();
                },
                @php
                    /*universal function for datatables that reload data in given datatable
                    * @DATA_TABLES dataTable
                    * */
                @endphp
                ajaxReload: function(dataTable){
                    let processing = $('#'+dataTable.table.attr('id')+'_processing');
                    processing.show();
                    dataTable.getData().done(function (response) {
                        dataTable.setTableData(response);
                        processing.hide();
                    });
                },
                @php
                    /*universal function for datatables that sets given data in given datatable
                    * @array data - data for insert
                    * @DATA_TABLES dataTable
                    * */
                @endphp
                setTableData: function(data, dataTable){
                    dataTable.dataTable.clear();
                    if($.isArray(data)) {
                        $.each(data, function (index, row) {
                            dataTable.dataTable.row.add(row);
                        });
                        dataTable.dataTable.draw();
                    }
                },
                @php
                    /*universal function for tables that adds class name to column
                    * @integer, @array column - column(s) range number(s)
                    * @string className - data for insert
                    * @jQuery table
                    * */
                @endphp
                setColumnClass: function (column, className, table) {
                    table.find('tbody').children().each(function (index, tr) {
                        $(tr).children().each(function (index, td) {
                            if(Array.isArray(column)){
                                if(index >= column[0] && index <= column[1] ){
                                    $(td).addClass(className);
                                }
                            }else{
                                if(index === column){
                                    $(td).addClass(className);
                                }
                            }
                        });
                    });
                }
            };
            VARIABLES.DATA_TABLES.removedNotificationsCount.ajaxReload();
            FUNCTIONS.EVENT_HANDLERS.callEvents();
            resizeDatatablesOnMenuToggle([VARIABLES.DATA_TABLES.removedNotificationsCount.dataTable, VARIABLES.DATA_TABLES.removedNotifications.dataTable]);
        });
    </script>
@endsection
