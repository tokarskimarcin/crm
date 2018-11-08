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
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    <style>
        .DTFC_LeftHeadWrapper thead tr, .DTFC_LeftBodyWrapper thead tr{
            background: white;

        }
        #departmentsConfirmationDatatable tbody td, #allDepartmentsConfirmationDatatable tbody td{
            text-align: center;
        }

        .bootstrap-select > .dropdown-menu {
            left: 0 !important;
        }
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
        .gray{
            background: #999999;
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
                <div class="col-md-2">
                    <label>Miesiąc:</label>
                    <div class="form-group">
                        <div class='input-group date' id='monthDatetimepicker'>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input type='text' class="form-control" value="{{date('Y-m')}}" readonly/>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label>Okres:</label>
                    <select class="form-control selectpicker" id="periodSelect">
                        <option value="1" selected>Tygodniowy</option>
                        <option value="3">Miesięczny</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Oddział:</label>
                    <select class="form-control selectpicker" id="departmentsSelect">
                        @foreach($deps as $dep)
                            <option value="{{$dep->id}}" >{{$dep->departments->name}} {{$dep->department_type->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Trener:</label>
                    <select class="form-control selectpicker" id="trainersSelect">
                        <option value="-1" selected>Wszyscy</option>
                        @foreach($trainers as $trainer)
                            @if($trainer->department_info_id == $deps[0]->id)
                                <option value="{{$trainer->id}}" >{{$trainer->first_name}} {{$trainer->last_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="checkbox">
                        <label>
                            <input id="trainersGroupingCheckbox" type="checkbox" style="display: block;"> Grupowanie po trenerach
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="departmentsConfirmationDatatable" class="table cell-border table-striped table-hover compact"  style="width:100%;">
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
                            <th class="yellow">% janki</th>
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
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel ze statystykami dla wszystkich oddziałów w wybranym miesiącu
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <label>Miesiąc:</label>
                    <div class="form-group">
                        <div class='input-group date' id='monthDatetimepickerForDepartments'>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input type='text' class="form-control" value="{{date('Y-m')}}" readonly/>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label>Okres:</label>
                    <select class="form-control selectpicker" id="periodSelectForDepartments">
                        <option value="1" selected>Tygodniowy</option>
                        <option value="3">Miesięczny</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="allDepartmentsConfirmationDatatable" class="table cell-border table-striped table-hover compact"  style="width:100%;">
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
                            <th class="yellow">Oddział</th>
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
                            <th class="yellow">% janki</th>
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
    <script src="{{ asset('/js/moment.js')}}"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function () {
            const columnsNr = {'lp':0,'name':1,'shows':2,'provision':3,'avgTimeOnRecord': 15,'dateGroup':20,'secondGroup':21};
            let hiddenColumns = [columnsNr['dateGroup'], columnsNr['secondGroup']];
            let groupColumns = [columnsNr['dateGroup'], columnsNr['secondGroup']];
            let VARIABLES  = {
                jQElements:{
                    trainersGroupingCheckboxjQ: $('#trainersGroupingCheckbox'),
                    departmentsSelectjQ: $('#departmentsSelect'),
                    trainersSelectjQ: $('#trainersSelect'),
                    periodSelectjQ: $('#periodSelect'),
                    periodSelectForDepartmentsjQ: $('#periodSelectForDepartments'),
                    monthDatetimepicker: $('#monthDatetimepicker').datetimepicker({
                        language: 'pl',
                        minView: 3,
                        startView: 3,
                        format: 'yyyy-mm',
                        startDate: moment('2018-09-01').format('YYYY-MM-DD'),
                        endDate: moment().format('YYYY-MM-DD')
                    }),
                    monthDatetimepickerForDepartments: $('#monthDatetimepickerForDepartments').datetimepicker({
                        language: 'pl',
                        minView: 3,
                        startView: 3,
                        format: 'yyyy-mm',
                        startDate: moment('2018-09-01').format('YYYY-MM-DD'),
                        endDate: moment().format('YYYY-MM-DD')
                    })
                },
                departments: <?php echo json_encode($deps->toArray()) ?>,
                trainers: <?php echo json_encode($trainers->toArray()) ?>,
                departmentsConfirmationStatisticsLpCounter: 0,
                allDepartmentsConfirmationStatisticsLpCounter: 0,
                conditionDepartmentsConfirmationStatisticsLpCounterZeroing: null, //condition for pointing row that start a group,
                dateGroupConditionDepartmentsConfirmationStatisticsLpCounterZeroing: null,
                DATA_TABLES:{
                    departmentsConfirmation: {
                        data: {
                            departmentsConfirmationStatistics: null,
                            departmentsConfirmationStatisticsSums: null
                        },
                        table: $('#departmentsConfirmationDatatable'),
                        dataTable: $('#departmentsConfirmationDatatable').DataTable({
                            scrollX: true,
                            scrollY: '70vh',
                            scrollCollapse: true,
                            processing: true,
                            paging: false,
                            dom: 'Bftipr',
                            buttons: [
                                'csv', 'excel'
                            ],
                            language: {
                                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                            },
                            columnDefs:[
                                { "visible": false, "targets": hiddenColumns }
                            ],
                            ordering: false,
                            columns:[
                                {data: 'lp'},
                                {data: 'confirmingUserName'},
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
                                {data: 'unsuccessful'},
                                {data: function (data) {
                                        return data.unsuccessfulPct+'%';
                                    }},
                                {data: 'unsuccessfulBadly'},
                                {data: function (data) {
                                        return data.unsuccessfulBadlyPct+'%';
                                    }},
                                {data: 'avgFrequency'},
                                {data: 'avgPairs'},
                                {data: 'recordsCount'},
                                {data: 'avgTimeOnRecord'},
                                {data: function (data) {
                                        return data.jankyPct+'%';
                                    }},
                                {data: function (data) {
                                        return data.agreementPct+'%';
                                    }},
                                {data: function (data) {
                                        return data.uncertainPct+'%';
                                    }},
                                {data: function (data) {
                                        return data.refusalPct+'%';
                                    }},
                                {data: 'dateGroup'},
                                {data: 'secondGroup'}
                            ],
                            fnDrawCallback: function () {
                                FUNCTIONS.setColumnClass(0, 'peach', VARIABLES.DATA_TABLES.departmentsConfirmation.table);
                                FUNCTIONS.setColumnClass([4,5],'green', VARIABLES.DATA_TABLES.departmentsConfirmation.table);
                                FUNCTIONS.setColumnClass([6,7],'yellow', VARIABLES.DATA_TABLES.departmentsConfirmation.table);
                                FUNCTIONS.setColumnClass([8,11],'red', VARIABLES.DATA_TABLES.departmentsConfirmation.table);
                                FUNCTIONS.insertGroupRows(groupColumns[0], this, 20, {background:'#444444', color:'white', 'font-weight':'bold'});
                                if(VARIABLES.jQElements.trainersGroupingCheckboxjQ.get(0).checked) {
                                    FUNCTIONS.insertGroupRows(groupColumns[1], this, 20, {
                                        background: '#ffe599',
                                        'font-weight': 'bold'
                                    });
                                }
                                FUNCTIONS.insertSumRowAfterGroup(VARIABLES.jQElements.trainersGroupingCheckboxjQ.get(0).checked, this, VARIABLES.DATA_TABLES.departmentsConfirmation.data.departmentsConfirmationStatisticsSums);

                                //coloring to green date(week) group rows if week didn't end
                                $('#'+VARIABLES.DATA_TABLES.departmentsConfirmation.table.attr('id')).find('.group_'+groupColumns[0]).each(function (index, row) {
                                    let dates = $(row).find('td').text().split(" ");
                                    let lastDay = dates[2].split('.');
                                    if(moment()<moment().set({'year':lastDay[0],'month':lastDay[1]-1,'date':lastDay[2]})){
                                        $(row).css('background','#429137')
                                    }
                                });
                            },
                            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                                let avgTimeOnRecord = aData.avgTimeOnRecord.split(':');
                                let avgTimeOnRecordInSeconds = parseInt(avgTimeOnRecord[0])*3600 + parseInt(avgTimeOnRecord[1])*60 + parseInt(avgTimeOnRecord[2]);
                                let redAvgTimeOnRecord = 120; // 00:02:00
                                let yellowAvgTimeOnRecord = 150; // 00:02:30
                                let color = '#00ff00';
                                if(avgTimeOnRecordInSeconds < redAvgTimeOnRecord){
                                    color = '#ff0000';
                                }else if(avgTimeOnRecordInSeconds < yellowAvgTimeOnRecord){
                                    color = '#ffff00';
                                }
                                $($(nRow).children()[columnsNr['avgTimeOnRecord']]).css({'background-color': color});
                            }
                        }),
                        getData: function () {
                            return FUNCTIONS.AJAXs.departmentsConfirmationStatisticsAjax().then(function (response) {
                                return response.data;
                            });

                        },
                        setTableData: function (data){
                            VARIABLES.departmentsConfirmationStatisticsLpCounter = 0;
                            //data is grouped by weeks and trainers  (/week/: [ trainer1:[], trainer2:[]])
                            let dataTable = this.dataTable;
                            dataTable.clear();
                            $.each(data,function (dateGroup, week) {
                                if(VARIABLES.jQElements.trainersGroupingCheckboxjQ.get(0).checked){
                                    $.each(week, function (trainer, data) {
                                        FUNCTIONS.setTableDataWithDepartmentsConfirmationStatisticsLpCounterByGroup(data, 'secondGroup', dataTable);
                                    });
                                }else{
                                    FUNCTIONS.setTableDataWithDepartmentsConfirmationStatisticsLpCounterByGroup(week, 'dateGroup', dataTable);
                                }
                            });
                            dataTable.draw();

                        },
                        ajaxReload: function () {
                            return FUNCTIONS.ajaxReload(this);
                        }
                    },
                    allDepartmentsConfirmation: {
                        data: {
                            allDepartmentsConfirmationStatistics: null,
                            allDepartmentsConfirmationStatisticsSums: null
                        },
                        table: $('#allDepartmentsConfirmationDatatable'),
                        dataTable: $('#allDepartmentsConfirmationDatatable').DataTable({
                            scrollX: true,
                            scrollY: '70vh',
                            scrollCollapse: true,
                            processing: true,
                            paging: false,
                            dom: 'Bftipr',
                            buttons: [
                                'csv', 'excel'
                            ],
                            language: {
                                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                            },
                            columnDefs:[
                                { "visible": false, "targets": hiddenColumns }
                            ],
                            ordering: false,
                            columns:[
                                {data: 'lp'},
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
                                {data: 'unsuccessful'},
                                {data: function (data) {
                                        return data.unsuccessfulPct+'%';
                                    }},
                                {data: 'unsuccessfulBadly'},
                                {data: function (data) {
                                        return data.unsuccessfulBadlyPct+'%';
                                    }},
                                {data: 'avgFrequency'},
                                {data: 'avgPairs'},
                                {data: 'recordsCount'},
                                {data: 'avgTimeOnRecord'},
                                {data: function (data) {
                                        return data.jankyPct+'%';
                                    }},
                                {data: function (data) {
                                        return data.agreementPct+'%';
                                    }},
                                {data: function (data) {
                                        return data.uncertainPct+'%';
                                    }},
                                {data: function (data) {
                                        return data.refusalPct+'%';
                                    }},
                                {data: 'dateGroup'},
                                {data: 'secondGroup'}
                            ],
                            fnDrawCallback: function () {
                                FUNCTIONS.setColumnClass(0, 'peach', VARIABLES.DATA_TABLES.allDepartmentsConfirmation.table);
                                FUNCTIONS.setColumnClass([4,5],'green', VARIABLES.DATA_TABLES.allDepartmentsConfirmation.table);
                                FUNCTIONS.setColumnClass([6,7],'yellow', VARIABLES.DATA_TABLES.allDepartmentsConfirmation.table);
                                FUNCTIONS.setColumnClass([8,11],'red', VARIABLES.DATA_TABLES.allDepartmentsConfirmation.table);
                                FUNCTIONS.insertGroupRows(groupColumns[0], this, 20, {background:'#444444', color:'white', 'font-weight':'bold'});
                                FUNCTIONS.insertSumRowAfterGroup(false, this, VARIABLES.DATA_TABLES.allDepartmentsConfirmation.data.allDepartmentsConfirmationStatisticsSums);

                                //coloring to green date(week) group rows if week didn't end
                                $('#'+VARIABLES.DATA_TABLES.allDepartmentsConfirmation.table.attr('id')).find('.group_'+groupColumns[0]).each(function (index, row) {
                                    let dates = $(row).find('td').text().split(" ");
                                    let lastDay = dates[2].split('.');
                                    if(moment()<moment().set({'year':lastDay[0],'month':lastDay[1]-1,'date':lastDay[2]})){
                                        $(row).css('background','#429137')
                                    }
                                });
                            }
                        }),
                        getData: function () {
                            return FUNCTIONS.AJAXs.allDepartmentsConfirmationStatisticsAjax().then(function (response) {
                                return response.sums;
                            });

                        },
                        setTableData: function (data){
                            VARIABLES.departmentsConfirmationStatisticsLpCounter = 0;
                            let dataTable = this.dataTable;
                            dataTable.clear();
                            $.each(data, function (weekIndex, week) {
                                $.each(week.secondGrouping, function (secondGroupingIndex, secondGrouping) {
                                    secondGrouping.dateGroup = week.dateGroup;
                                });
                                FUNCTIONS.setTableDataWithDepartmentsConfirmationStatisticsLpCounterByGroup(week.secondGrouping, 'dateGroup', dataTable);
                            });
                            dataTable.draw();

                        },
                        ajaxReload: function () {
                            return FUNCTIONS.ajaxReload(this);
                        }
                    }
                }
            };
            let FUNCTIONS = {
                /* functions groups should be before other functions which aren't grouped*/
                EVENT_HANDLERS: {
                    callEvents: function(){
                        (function trainersGroupingCheckboxHandler() {
                            VARIABLES.jQElements.trainersGroupingCheckboxjQ.change(function (e) {
                                if(e.target.checked){
                                    groupColumns[1] = columnsNr['secondGroup'];
                                }
                                VARIABLES.DATA_TABLES.departmentsConfirmation.ajaxReload();
                            });
                        })();
                        (function departmentsSelectHandler() {
                            VARIABLES.jQElements.departmentsSelectjQ.change(function (e) {
                                VARIABLES.jQElements.trainersSelectjQ.find('option')
                                    .remove();
                                VARIABLES.jQElements.trainersSelectjQ.append($('<option>').val(-1).text('Wszyscy')).prop('selected',true);
                                $.each(VARIABLES.trainers, function(index, trainer){
                                   if(trainer.department_info_id == VARIABLES.jQElements.departmentsSelectjQ.val()){
                                       VARIABLES.jQElements.trainersSelectjQ.append($('<option>').val(trainer.id).text(trainer.first_name+' '+trainer.last_name));
                                   }
                                });
                                VARIABLES.jQElements.trainersSelectjQ.selectpicker('refresh');
                                VARIABLES.DATA_TABLES.departmentsConfirmation.ajaxReload();
                            });
                        })();
                        (function periodSelectHandler() {
                            VARIABLES.jQElements.periodSelectjQ.change(function (e) {
                                VARIABLES.DATA_TABLES.departmentsConfirmation.ajaxReload();
                            });
                        })();
                        (function trainersSelectHandler() {
                            VARIABLES.jQElements.trainersSelectjQ.change(function (e) {
                                VARIABLES.DATA_TABLES.departmentsConfirmation.ajaxReload();
                            });
                        })();
                        (function monthDatetimepickerHandler() {
                            VARIABLES.jQElements.monthDatetimepicker.change(function (e) {
                                VARIABLES.DATA_TABLES.departmentsConfirmation.ajaxReload();
                            });
                        })();
                        (function periodSelectForDepartmentsHandler() {
                            VARIABLES.jQElements.periodSelectForDepartmentsjQ.change(function (e) {
                                VARIABLES.DATA_TABLES.allDepartmentsConfirmation.ajaxReload();
                            });
                        })();
                        (function monthDatetimepickerForDepartmentsHandler() {
                            VARIABLES.jQElements.monthDatetimepickerForDepartments.change(function (e) {
                                VARIABLES.DATA_TABLES.allDepartmentsConfirmation.ajaxReload();
                            });
                        })();
                    }
                },
                AJAXs:{
                    departmentsConfirmationStatisticsAjax: function() {
                        return $.ajax({
                            url: "{{ route('api.departmentsConfirmationStatisticsAjax') }}",
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                                trainersGrouping: VARIABLES.jQElements.trainersGroupingCheckboxjQ.get(0).checked,
                                departmentId: VARIABLES.jQElements.departmentsSelectjQ.val(),
                                selectedMonth: VARIABLES.jQElements.monthDatetimepicker.find('input').val(),
                                trainerId: VARIABLES.jQElements.trainersSelectjQ.val(),
                                period: VARIABLES.jQElements.periodSelectjQ.val()
                            },
                            success: function (response) {
                                VARIABLES.DATA_TABLES.departmentsConfirmation.data.departmentsConfirmationStatistics = response.data;
                                VARIABLES.DATA_TABLES.departmentsConfirmation.data.departmentsConfirmationStatisticsSums = response.sums;
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
                    allDepartmentsConfirmationStatisticsAjax: function() {
                        return $.ajax({
                            url: "{{ route('api.allDepartmentsConfirmationStatisticsAjax') }}",
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                                selectedMonth: VARIABLES.jQElements.monthDatetimepickerForDepartments.find('input').val(),
                                period: VARIABLES.jQElements.periodSelectForDepartmentsjQ.val()
                            },
                            success: function (response) {
                                VARIABLES.DATA_TABLES.allDepartmentsConfirmation.data.allDepartmentsConfirmationStatistics = response.data;

                                //adding `name` of departments to sums of departments
                                $.each(response.sums, function (weekIndex, week) {
                                    $.each(week.secondGrouping,function (departmentIndex, departmentSums) {
                                        $.each(VARIABLES.departments, function (depIndex, dep) {
                                            if(departmentSums.secondGroup == dep.id){
                                                departmentSums.name = dep.departments.name+' '+dep.department_type.name;
                                                return false;
                                            }
                                        });
                                    })
                                });
                                VARIABLES.DATA_TABLES.allDepartmentsConfirmation.data.allDepartmentsConfirmationStatisticsSums = response.sums;
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
                    }
                },
                @php
                /*function inserts rows in datatable
                * @integer column - column number pointing on the data that should be taken as grouping data
                * @DataTable dataTable - datatable in which rows should be inserted
                * @integer colspan - number of how many columns should be span
                * @object cssOptionsTr - param for jQuery .css() method
                * */
                @endphp
                insertGroupRows: function(column, dataTable, colspan, cssOptionsTr = null){
                    let api = dataTable.api();
                    let rows = api.rows({page: 'current'}).nodes();
                    let last = null;
                    let lastDateGroup = null;
                    api.column(column, {page: 'current'}).data().each(function (group, i) {
                        let dateGroup = api.row(i).data().dateGroup;
                        if (last !== group || lastDateGroup !== dateGroup) {
                            let elementToInsert = $('<tr>').addClass('group_'+column).append($('<td>').attr('colspan',colspan).text(group));
                            if(cssOptionsTr != null){
                                elementToInsert.css(cssOptionsTr);
                            }
                            $($(rows).eq(i)[0]).before(elementToInsert);
                            last = group;
                            lastDateGroup = dateGroup;
                        }
                    });
                },

                insertSumRowAfterGroup: function (insideGrouping, dataTable, sumsData, cssOptionsTr = null) {
                    let column = insideGrouping ? groupColumns[1] : groupColumns[0];
                    let api = dataTable.api();
                    let rows = api.rows({page: 'current'}).nodes();
                    api.column(column, {page: 'current'}).data().each(function (group, i) {
                        let dateGroup = api.column(columnsNr['dateGroup']).data()[i]; //variable need to exist only for inside grouping
                        if(group !== api.column(column).data()[i+1] || dateGroup !== api.column(columnsNr['dateGroup']).data()[i+1]){
                            let data = null;
                            $.each(sumsData, function (weekNr, weekSums) {
                               if(weekSums.dateGroup === api.column(groupColumns[0], {page: 'current'}).data()[i]){
                                   data = weekSums;
                                   return false;
                               }
                            });
                            if(insideGrouping){
                                $.each(data.secondGrouping, function (secondGroupingNr, secondGroupingSums) {
                                    if(secondGroupingSums.secondGroup === api.column(groupColumns[1], {page: 'current'}).data()[i]){
                                        data = secondGroupingSums;
                                        return false;
                                    }
                                });
                            }
                            let elementToInsert = $('<tr>').addClass('groupSum_'+column).css('font-weight','bold')
                               .append($('<td>'))
                               .append($('<td>').addClass('gray').text('Suma:'))
                               .append($('<td>').addClass('gray').text(data.shows))
                               .append($('<td>').addClass('gray').text(data.provision))
                               .append($('<td>').addClass('strongGreen').text(data.successful))
                               .append($('<td>').addClass('strongGreen').text(data.successfulPct+'%'))
                               .append($('<td>').addClass('strongYellow').text(data.neutral))
                               .append($('<td>').addClass('strongYellow').text(data.neutralPct+'%'))
                               .append($('<td>').addClass('strongRed').text(data.unsuccessful))
                               .append($('<td>').addClass('strongRed').text(data.unsuccessfulPct+'%'))
                               .append($('<td>').addClass('strongRed').text(data.unsuccessfulBadly))
                               .append($('<td>').addClass('strongRed').text(data.unsuccessfulBadlyPct+'%'))
                               .append($('<td>').addClass('gray').text(data.avgFrequency))
                               .append($('<td>').addClass('gray').text(data.avgPairs))
                               .append($('<td>').addClass('gray').text(data.recordsCount))
                               .append($('<td>').addClass('gray').text(data.avgTimeOnRecord))
                                .append($('<td>').addClass('gray').text(data.jankyPct+'%'))
                               .append($('<td>').addClass('gray').text(data.agreementPct+'%'))
                               .append($('<td>').addClass('gray').text(data.uncertainPct+'%'))
                               .append($('<td>').addClass('gray').text(data.refusalPct+'%'));
                            $($(rows).eq(i)[0]).after(elementToInsert);
                        }
                    });
                },
                @php
                /*universal function for datatables that reload data in given datatable
                * @DataTable dataTable
                * */
                @endphp
                ajaxReload: function(dataTable){
                    let processing = $('#'+dataTable.table.attr('id')+'_processing');
                    processing.show();
                    return dataTable.getData().then(function (response) {
                        dataTable.setTableData(response);
                        processing.hide();
                    });
                },
                @php
                /*universal function for datatables that sets given data in given datatable
                * @array data - data for insert
                * @DataTable dataTable
                * */
                @endphp
                setTableData: function(data, dataTable){
                    dataTable.clear();
                    if($.isArray(data)) {
                        $.each(data, function (index, row) {
                            dataTable.row.add(row);
                        });
                        dataTable.draw();
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
                },
                setTableDataWithDepartmentsConfirmationStatisticsLpCounterByGroup: function(data, groupingData, dataTable){
                    if($.isArray(data)) {
                        $.each(data, function (index, row) {
                            if(VARIABLES.conditionDepartmentsConfirmationStatisticsLpCounterZeroing !== row[groupingData] || VARIABLES.dateGroupConditionDepartmentsConfirmationStatisticsLpCounterZeroing !== row['dateGroup']){  //if groupig data is diffrent than previous value, counter equals 0
                                VARIABLES.departmentsConfirmationStatisticsLpCounter = 0;                                    //counting from beginning
                                VARIABLES.conditionDepartmentsConfirmationStatisticsLpCounterZeroing = row[groupingData];    //setting condition to grouping data of current row
                                VARIABLES.dateGroupConditionDepartmentsConfirmationStatisticsLpCounterZeroing = row['dateGroup'];
                            }
                            VARIABLES.departmentsConfirmationStatisticsLpCounter++;
                            row.lp = VARIABLES.departmentsConfirmationStatisticsLpCounter;   //setting 'lp' row attribute
                            dataTable.row.add(row);         //adding row to datatable
                        });
                    }
                }
            };


            let reloadDatatableDeferred = $.Deferred();
            VARIABLES.DATA_TABLES.departmentsConfirmation.dataTable.on('init.dt', function () {
                VARIABLES.DATA_TABLES.departmentsConfirmation.ajaxReload().then(function () {
                    reloadDatatableDeferred.resolve();
                });
            });

            VARIABLES.DATA_TABLES.allDepartmentsConfirmation.dataTable.on('init.dt', function () {
                reloadDatatableDeferred.done(function () {
                    VARIABLES.DATA_TABLES.allDepartmentsConfirmation.ajaxReload();
                });
            });
            FUNCTIONS.EVENT_HANDLERS.callEvents();
            resizeDatatablesOnMenuToggle([VARIABLES.DATA_TABLES.departmentsConfirmation.dataTable, VARIABLES.DATA_TABLES.allDepartmentsConfirmation.dataTable]);
        });
    </script>
@endsection
