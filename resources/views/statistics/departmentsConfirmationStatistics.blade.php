@php
    /**
     *Created by PhpStorm.
     *User: veronaprogramista
     *Date: 07.09.18
     *Time: 15:21
    */
@endphp

{{--/*--}}
{{--*@category: Statistics,--}}
{{--*@info: This template view is for copy purpose--}}
{{--*@controller: DepartmentsConfirmationStatisticsController,--}}
{{--*@methods: departmentsConfirmationGet, departmentsConfirmationStatisticsAjax, --}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <link rel="stylesheet" href="{{asset('/assets/css/VCtooltip.css')}}">
    <style>
        .DTFC_LeftHeadWrapper thead tr, .DTFC_LeftBodyWrapper thead tr{
            background: white;

        }

        /*.DTFC_LeftBodyLiner{
            overflow: visible !important;
        }*/
        /*.DTFC_LeftBodyWrapper{
            top: -1px !important;
        }

        .dataTables_scrollBody{
            left: 1px;
        }*/
        /*#departmentsConfirmationDatatable thead{
            display: none;
        }*//*

        .DTFC_LeftBodyLiner tbody{
            height: 100% !importanwt;
        }*/

        .green{
            background: #d9ead3 !important;
        }
        .strongGreen{
            background: #93c47d !important;
        }

        .yellow{
            background: #fff2cc !important;
        }
        .strongYellow{
            background: #ffd966 !important;
        }
        .red{
            background: #f4cccc !important;
        }
        .strongRed{
            background: #e06666 !important;
        }
        .peach{
            background: #ffe599 !important;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">
            Statystki oddziały potwierdznia
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel ze statystykami dla poszczególnych oddziałów w wybranym miesiącu
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="checkbox">
                        <label>

                            <input type="checkbox" style="display: block;"> Grupowanie po trenerach
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="departmentsConfirmationDatatable" class="table row-border table-striped table-hover compact"  style="width:100%;">
                        <thead>
                        <tr>
                            <th colspan="4"></th>
                            <th colspan="2" class="strongGreen" style="text-align: center">Pokazy udane</th>
                            <th colspan="2" class="strongYellow" style="text-align: center">Pokazy neutralne</th>
                            <th colspan="4" class="strongRed" style="text-align: center">Pokazy nieudane</th>
                            <th colspan="8"></th>
                        </tr>
                        <tr>
                            <th class="peach">Lp.</th>
                            <th class="yellow">Imię i nazwisko</th>
                            <th class="yellow">Liczba pokazów</th>
                            <th class="yellow">Prowizja</th>
                            <th class="strongGreen">Liczba</th>
                            <th class="strongGreen">%</th>
                            <th class="strongYellow">Liczba</th>
                            <th class="strongYellow">%</th>
                            <th class="strongRed">Liczba</th>
                            <th class="strongRed">%</th>
                            <th class="strongRed"><12</th>
                            <th class="strongRed">%</th>
                            <th class="yellow">Śr. liczba osób</th>
                            <th class="yellow">Śr. liczba par</th>
                            <th class="yellow">Liczba rekordów</th>
                            <th class="yellow">Czas na rekord</th>
                            <th class="yellow">% zgód</th>
                            <th class="yellow">% niepewnych</th>
                            <th class="yellow">% odmów</th>
                            <th class="yellow">Tydzień</th>
                            <th class="yellow">Trener</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            let groupColumns = [19,20];
            let VARIABLES  = {
                lpCounter: 0,
                DATA_TABLES:{
                    departmentsConfirmation: {
                        data: {
                            departmentsConfirmationStatistics: null
                        },
                        table: $('#departmentsConfirmationDatatable'),
                        dataTable: $('#departmentsConfirmationDatatable').DataTable({
                            scrollX: true,
                            scrollY: '70vh',
                            scrollCollapse: true,
                            processing: true,
                            paging: false,
                            language: {
                                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                            },
                            columnDefs:[
                                { "visible": false, "targets": groupColumns }
                            ],
                            columns:[
                                {data: function () {
                                        VARIABLES.lpCounter++;
                                        return VARIABLES.lpCounter;
                                    }},
                                {data: 'name'},
                                {data: 'shows'},
                                {data: 'provision'},
                                {data: 'successful'},
                                {data: function (data) {
                                        return data.successfulPct+'%';
                                    }, name: 'successfulPct'},
                                {data: 'neutral'},
                                {data: function (data) {
                                        return data.neutralPct+'%';
                                    },name : 'neutralPct'},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: function () {
                                        return 1;
                                    }},
                                {data: 'dateGroup'},
                                {data: 'trainer'}
                            ],
                            fnDrawCallback: function () {
                                FUNCTIONS.setColumnClass(0, 'peach', VARIABLES.DATA_TABLES.departmentsConfirmation.table);
                                FUNCTIONS.setColumnClass([4,5],'green', VARIABLES.DATA_TABLES.departmentsConfirmation.table);
                                FUNCTIONS.setColumnClass([6,7],'yellow', VARIABLES.DATA_TABLES.departmentsConfirmation.table);
                                FUNCTIONS.setColumnClass([8,11],'red', VARIABLES.DATA_TABLES.departmentsConfirmation.table);
                                FUNCTIONS.insertGroupRows(groupColumns[0], this, 19, {background:'#444444', color:'white', 'font-weight':'bold'});
                                FUNCTIONS.insertGroupRows(groupColumns[1], this, 19, {background:'#ffe599', 'font-weight':'bold'});

                                FUNCTIONS.EVENT_HANDLERS.scrollBodyHandler();
                            }
                        }),
                        getData: function () {
                            return FUNCTIONS.AJAXs.departmentsConfirmationStatisticsAjax();
                        },
                        setTableData: function (data){
                            let dataTable = this.dataTable;
                            let table = this.table;
                            let conditionLpCounterZeroing = null;
                            dataTable.clear();
                            $.each(data,function (dateGroup, trainersData) {
                                $.each(trainersData, function (trainer, data) {
                                    if($.isArray(data)) {
                                        $.each(data, function (index, row) {
                                            if(conditionLpCounterZeroing != row.trainer){
                                                VARIABLES.lpCounter = 0;
                                                conditionLpCounterZeroing = row.trainer;
                                            }
                                            dataTable.row.add(row);
                                        });
                                    }
                                });
                            });
                            dataTable.draw();

                        },
                        ajaxReload: function () {
                            FUNCTIONS.ajaxReload(this);
                        }
                    }
                }
            };
            let FUNCTIONS = {
                /* functions groups should be before other functions which aren't grouped*/
                EVENT_HANDLERS: {
                    scrollBodyHandler: function(){
                        $('.dataTables_scrollBody').scroll(function (e) {
                            if($(e.target).scrollTop() < $('#departmentsConfirmationDatatable').find('thead').height()){
                                $(e.target).scrollTop($('#departmentsConfirmationDatatable').find('thead').height());
                            }
                        });
                    }
                },
                AJAXs:{
                    departmentsConfirmationStatisticsAjax: function(dataToSend) {
                        return $.ajax({
                            url: "{{ route('api.departmentsConfirmationStatisticsAjax') }}",
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                            },
                            success: function (response) {
                                VARIABLES.DATA_TABLES.departmentsConfirmation.data.departmentsConfirmationStatistics = response;
                                return response;
                            },
                            error: function (jqXHR, textStatus, thrownError) {
                                console.log(jqXHR);
                                console.log('textStatus: ' + textStatus);
                                console.log('hrownError: ' + thrownError);
                                swal({
                                    type: 'error',
                                    title: 'Błąd ' + jqXHR.status,
                                    text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                                });
                            }
                        });
                    }
                },
                insertGroupRows: function(column, dataTable, colspan, cssOptionsTr = null){
                    let api = dataTable.api();
                    let rows = api.rows({page: 'current'}).nodes();
                    let last = null;
                    api.column(column, {page: 'current'}).data().each(function (group, i) {
                        if (last !== group) {
                            let elementToInsert = $('<tr>').addClass('group_'+column).append($('<td>').attr('colspan',colspan).text(group));
                            if(cssOptionsTr != null){
                                elementToInsert.css(cssOptionsTr);
                            }
                            $($(rows).eq(i)[0]).before(elementToInsert);
                            last = group;
                        }
                    });
                },
                ajaxReload: function(dataTable){
                    let processing = $('#'+dataTable.table.attr('id')+'_processing');
                    processing.show();
                    dataTable.getData().done(function (response) {
                        dataTable.setTableData(response);
                        processing.hide();
                    });
                },
                setTableData: function(data, dataTable){
                    dataTable.clear();
                    if($.isArray(data)) {
                        $.each(data, function (index, row) {
                            dataTable.row.add(row);
                        });
                        dataTable.draw();
                    }
                },
                setColumnClass: function (column, className, table) {
                    table.find('tbody').children().each(function (index, tr) {
                        $(tr).children().each(function (index, td) {
                            if(Array.isArray(column)){
                                if(index >= column[0] && index <= column[1] ){
                                    $(td).addClass(className);
                                }
                            }else{
                                if(index == column){
                                    $(td).addClass(className);
                                }
                            }

                        });
                    });
                }
            };
            VARIABLES.DATA_TABLES.departmentsConfirmation.ajaxReload();
            resizeDatatablesOnMenuToggle([VARIABLES.DATA_TABLES.departmentsConfirmation.dataTable]);
        });
    </script>
@endsection
