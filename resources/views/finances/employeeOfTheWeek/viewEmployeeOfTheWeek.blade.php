@php
    /**
     * Created by PhpStorm.
     * User: veronaprogramista
     * Date: 17.09.18
     * Time: 14:19
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
    <link rel="stylesheet" href="{{asset('/assets/css/VCtooltip.css')}}">
    <style>
        .bootstrap-select > .dropdown-menu {
            left: 0 !important;
        }
        .VCtooltip .well:hover {
            background-color: rgba(185,185,185,0.75) !important;
            cursor: help;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">
            Rozliczenia / Pracownicy Tygodnia @if($type == 2)- Kadra @endif
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel z zatwierdzaniem premii dla pracownika tygodnia
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="VCtooltip VCtooltip-rgith" align="right">
                        <div class="well well-sm" style="border-radius: 10%; background-color: #5bc0de; color: white; margin-bottom: 0;">
                            Legenda <span class="glyphicon glyphicon-info-sign"></span>
                        </div>
                        <span class="tooltiptext">
                            <div class="alert alert-info">
                                <div class="legendInfo" id="noUserTypeSelected">Wybierz typ oddziału i typ pracownika by zobaczyć legendę dla wybranej opcji.</div>
                                <div class="legendInfo" id="confirmationTrainer" hidden><strong>[POTWIERDZENIA] Trener tygodnia:</strong> tylko jedno miejsce dla trenera ze wszystkich oddziałów potwierdzeń.</div>
                                <div class="legendInfo" id="confirmationConsultant" hidden><strong>[POTWIERDZENIA] Konsultant tygodnia:</strong> premiowane dwa pierwsze miejsca poszczególnego zespołu wybranego trenera.
                                    Konsultant znajduje się w rankingu, gdy potwierdzał przynajmniej 4 pokazy</div>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row" id="selectorSection">
                <div class="col-md-2" id="departmentTypeSection">
                    <label for="departmentTypeSelect">Typ oddziału</label>
                    <select id="departmentTypeSelect" name="departmentTypeSelect" class="form-control">
                        <option value="0">Wybierz</option>
                        <option value="1">Potwierdzanie</option>
                        <option value="2">Telemarketing</option>
                    </select>
                </div>
            </div>
            {{--<div class="row">
                <div class="col-md-4">
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

                    <div class="col-md-4" @if($accessToAllDepartments != 1) hidden @endif>
                        <label for="departmentSelect">Oddział:</label>
                        <select id="departmentSelect" name="departmentSelect" class="form-control">
                            @foreach($departments_info as $department_info)
                                <option value="{{$department_info->id}}" @if($department_info->id == Auth::user()->department_info_id) selected @endif>{{$department_info->departments->name}} {{$department_info->department_type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                <div class="col-md-4">
                    <label for="userTypeSelect">Stanowisko:</label>
                    <select id="userTypeSelect" name="userTypeSelect" class="form-control">
                        <option value="0">Wybierz</option>
                        @foreach($userTypes as $userType)
                            <option value="{{$userType->id}}">{{$userType->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>--}}
            <div class="row" style="margin-top: 1em">
                <div id="employeeOfTheWeekSection" class="col-md-12">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/moment.js')}}"></script>
    <script>
        let VARIABLES;
        let FUNCTIONS;
        $(document).ready(function () {
            VARIABLES = {
                SUBVIEW:{},
                jQElements: {
                    selectorSection: $('#selectorSection'),
                    departmentTypeSection: $('#departmentTypeSection'),
                    departmentTypeSelect: $('#departmentTypeSelect').selectpicker(),
                    employeeOfTheWeekSection: $('#employeeOfTheWeekSection'),
                    legends: {
                        legendInfo: $('.legendInfo'),
                        noUserTypeSelected: $('#noUserTypeSelected'),
                        confirmationTrainer: $('#confirmationTrainer'),
                        confirmationConsultant: $('#confirmationConsultant')
                    }
                },
                DATA_TABLES: {}
            };

            FUNCTIONS = {
                loadingSwalCall: function(){
                    swal({
                        title: 'Ładowawnie...',
                        text: 'To może chwilę zająć',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        onOpen: () => {
                            swal.showLoading();
                        }
                    });
                },
                createUserTypeSelect: function(userTypes){
                    let userTypeLabel = $('<label>').attr('for','userTypeSelect').append('Typ pracownika tygodnia');
                    let userTypeSelect = $('<select>').addClass('form-control')
                        .attr('id','userTypeSelect')
                        .attr('name','userTypeSelect');
                    let userTypeSection = $('<div>').addClass('col-md-2').attr('id','userTypeSection')
                        .append(userTypeLabel)
                        .append(userTypeSelect)
                        .on('remove',function () {
                            VARIABLES.jQElements.legends.legendInfo.prop('hidden',true);
                            VARIABLES.jQElements.legends.noUserTypeSelected.prop('hidden',false);
                            $('#departmentInfoSection').trigger('remove');
                            if(userTypeSelect.val() === '4'){
                                $('#monthDatetimepickerSection').trigger('remove');
                            }
                            VARIABLES.jQElements.employeeOfTheWeekSection.empty();
                        });
                    if(userTypes.length === 0){
                        userTypeSelect.append($('<option>').append('Brak systemu pracowników tygodnia'));
                    }else{
                        userTypeSelect.append($('<option>').append('Wybierz').prop('selected',true).attr('value', 0));
                        $.each(userTypes,function (index, item) {
                            userTypeSelect.append($('<option>').append(item.name).attr('value', item.id));
                        });
                    }
                    VARIABLES.jQElements.selectorSection.append(userTypeSection);
                    userTypeSelect.selectpicker();
                    FUNCTIONS.EVENT_HANDLERS.userTypeSelectHandler(userTypeSelect);
                },
                createDepartmentInfoSelect: function(departmentInfos){
                    let departmentInfoLabel = $('<label>').attr('for','departmentInfoSelect').append('Oddział');
                    let departmentInfoSelect = $('<select>').addClass('form-control')
                        .attr('id','departmentInfoSelect')
                        .attr('name','departmentInfoSelect');
                    if(departmentInfos.length === 0){
                        departmentInfoSelect.append($('<option>').append('Brak systemu pracowników tygodnia'));
                    }else{
                        departmentInfoSelect.append($('<option>').append('Wybierz').prop('selected',true).attr('value', 0));
                        $.each(departmentInfos,function (index, item) {
                            departmentInfoSelect.append($('<option>').append(item.departments.name).attr('value', item.id));
                        });
                    }
                    let departmentInfoSection = $('<div>').addClass('col-md-2').attr('id','departmentInfoSection')
                        .append(departmentInfoLabel)
                        .append(departmentInfoSelect)
                        .on('remove',function () {
                            $('#monthDatetimepickerSection').trigger('remove');
                            VARIABLES.jQElements.employeeOfTheWeekSection.empty();
                        });
                    VARIABLES.jQElements.selectorSection.append(departmentInfoSection);
                    departmentInfoSelect.selectpicker();
                    FUNCTIONS.EVENT_HANDLERS.departmentInfoSelectHandler(departmentInfoSelect);
                },
                createMonthDatetimepicker: function(){
                    let monthDatetimepickerLabel = $('<label>').attr('for','userTypeSelect').append('Miesiąc');
                    let input = $('<input>').attr('type','text').addClass('form-control').prop('readonly',true);
                    let span = $('<span>').addClass('input-group-addon').append($('<span>').addClass('glyphicon glyphicon-calendar'));
                    let monthDatetimepicker = $('<div>').addClass('input-group date').attr('id','monthDatetimepicker').append(span).append(input);
                    let formGroup = $('<div>').addClass('form-group').append(monthDatetimepicker);
                    let monthDatetimepickerSection = $('<div>').addClass('col-md-3').attr('id','monthDatetimepickerSection')
                        .append(monthDatetimepickerLabel)
                        .append(formGroup).on('remove',function () {
                            $('#trainerSection').trigger('remove');
                            VARIABLES.jQElements.employeeOfTheWeekSection.empty();
                        });
                    VARIABLES.jQElements.selectorSection.append(monthDatetimepickerSection);
                    monthDatetimepicker.datetimepicker({
                        language: 'pl',
                        minView: 3,
                        startView: 3,
                        format: 'yyyy-mm',
                        startDate: moment('2018-09-01').format('YYYY-MM-DD'),
                        endDate: moment().format('YYYY-MM-DD')
                    });
                    FUNCTIONS.EVENT_HANDLERS.monthDatetimepickerHandler(monthDatetimepicker);
                },
                createTrainerSelect: function(trainers){
                    let trainerLabel = $('<label>').attr('for','trainerSelect').append('Zespół trenera');
                    let trainerSelect = $('<select>').addClass('form-control')
                        .attr('id','trainerSelect')
                        .attr('name','trainerSelect');
                    let trainerSection = $('<div>').addClass('col-md-3').attr('id','trainerSection')
                        .append(trainerLabel)
                        .append(trainerSelect)
                        .on('remove',function () {
                            VARIABLES.jQElements.employeeOfTheWeekSection.empty();
                        });
                    if(trainers.length === 0){
                        trainerSelect.append($('<option>').append('Brak informacji o trenerach w oddziale w tym miesiącu'));
                    }else{
                        trainerSelect.append($('<option>').append('Wybierz').prop('selected',true).attr('value', 0));
                        $.each(trainers,function (index, item) {
                            trainerSelect.append($('<option>').append(item.last_name+' '+item.first_name).attr('value', item.id));
                        });
                    }
                    VARIABLES.jQElements.selectorSection.append(trainerSection);
                    trainerSelect.selectpicker();
                    FUNCTIONS.EVENT_HANDLERS.trainerSelectHandler(trainerSelect);
                },
                SUBVIEW:{},
                /* function grups should be before other functions which aren't grouped */
                EVENT_HANDLERS: {
                    userTypeSelectHandler: function(userTypeSelect){
                        userTypeSelect
                            .change(function (e) {
                                let value = $(e.target).val();
                                VARIABLES.jQElements.legends.legendInfo.prop('hidden',true);
                                if(value > 0){
                                    if(value === '1'){
                                        VARIABLES.jQElements.legends.confirmationConsultant.prop('hidden',false);
                                    }
                                    if(value === '4'){
                                        VARIABLES.jQElements.legends.confirmationTrainer.prop('hidden',false);
                                    }
                                }else{
                                    VARIABLES.jQElements.legends.noUserTypeSelected.prop('hidden',false);
                                }
                                let userTypeSelect = $(e.target);
                                $('#departmentInfoSection').trigger('remove');
                                $('#monthDatetimepickerSection').trigger('remove');
                                if(userTypeSelect.val() === '1'){
                                    FUNCTIONS.loadingSwalCall();
                                    FUNCTIONS.AJAXs.getDepartmentInfoAjax(VARIABLES.jQElements.departmentTypeSelect.val())
                                        .then(function (result) {
                                            FUNCTIONS.createDepartmentInfoSelect(result);
                                        });
                                }else if(userTypeSelect.val() === '4'){
                                    FUNCTIONS.createMonthDatetimepicker();
                                }

                            });
                    },
                    departmentInfoSelectHandler: function(departmentInfoSelect){
                        departmentInfoSelect.change( function(e){
                            let value = $(e.target).val();
                            $('#monthDatetimepickerSection').trigger('remove');
                            if(value != 0){
                                FUNCTIONS.createMonthDatetimepicker();
                            }
                        });
                    },
                    monthDatetimepickerHandler: function(monthDatetimepicker){
                        monthDatetimepicker
                            .change( function(e){
                                let value = $(e.target).val();
                                let userTypeSelect = $('#userTypeSelect');
                                $('#trainerSection').trigger('remove');
                                if(userTypeSelect.val() === '1'){
                                    FUNCTIONS.loadingSwalCall();
                                    FUNCTIONS.AJAXs.getTrainersAjax($('#departmentInfoSelect').val(), value).then(function (result) {
                                        FUNCTIONS.createTrainerSelect(result)
                                    });
                                }else if(userTypeSelect.val() === '4'){
                                    FUNCTIONS.loadSubview();
                                }
                            });
                    },
                    trainerSelectHandler: function(trainerSelect){
                        trainerSelect.change( function(e){
                            let value = $(e.target).val();
                            if(value !== '0'){
                                FUNCTIONS.loadSubview();
                            }
                        });
                    },
                    callEvents:function () {
                        (function departmentTypeSelectHandler() {
                            VARIABLES.jQElements.departmentTypeSelect.change(function (e) {
                                let value = $(e.target).val();
                                $('#userTypeSection').trigger('remove');
                                if(value != 0){
                                    FUNCTIONS.loadingSwalCall();
                                    FUNCTIONS.AJAXs.getUserTypesOfDepartmentTypeAjax(value)
                                        .then(function (result) {
                                            FUNCTIONS.createUserTypeSelect(result);
                                        });
                                }
                            });
                        })();
                    }
                },
                AJAXs: {
                    getUserTypesOfDepartmentTypeAjax: function (departmentTypeId) {
                        return $.ajax({
                            type: 'POST',
                            url: '{{route('api.getUserTypesOfDepartmentTypeAjax')}}',
                            data: {
                                departmentTypeId: departmentTypeId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                swal.close();
                                return response;
                            }
                        });
                    },
                    getDepartmentInfoAjax: function (departmentTypeId) {
                        return $.ajax({
                            type: 'POST',
                            url: '{{route('api.getDepartmentInfoAjax')}}',
                            data: {
                                departmentTypeId: departmentTypeId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                swal.close();
                                return response;
                            }
                        });
                    },
                    getTrainersAjax: function (departmentInfoId, month) {
                        return $.ajax({
                            type: 'POST',
                            url: '{{route('api.getTrainersAjax')}}',
                            data: {
                                departmentInfoId: departmentInfoId,
                                month: month
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                swal.close();
                                return response;
                            }
                        });
                    },
                    getEmployeeOfTheWeekSectionSubView: function (data) {
                        return $.ajax({
                            type: 'POST',
                            url: '{{route('api.employeeOfTheWeekSubViewAjax')}}',
                            data: data,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                return response;
                            }
                        });
                    }
                },
                loadSubview: function(){
                    VARIABLES.SUBVIEW = {};
                    FUNCTIONS.SUBVIEW = {};
                    if(parseInt($('#userTypeSelect').val())>0){
                        FUNCTIONS.loadingSwalCall();
                        FUNCTIONS.AJAXs.getEmployeeOfTheWeekSectionSubView({
                            departmentInfoId: $('#departmentInfoSelect').val(),
                            departmentTypeId: $('#departmentTypeSelect').val(),
                            userTypeId: $('#userTypeSelect').val(),
                            selectedMonth: $('#monthDatetimepicker').find('input').val(),
                            trainerId: $('#trainerSelect').val()
                        }).done(function (resolve) {
                            VARIABLES.jQElements.employeeOfTheWeekSection.empty();
                            if(resolve !== 'noView'){
                                VARIABLES.jQElements.employeeOfTheWeekSection.html(resolve);
                            }else{
                                VARIABLES.jQElements.employeeOfTheWeekSection.append($('<h1>').append('Brak danych o premiach'))
                            }
                            swal.close();
                        });
                    }
                }
            };
            FUNCTIONS.EVENT_HANDLERS.callEvents();
            //resizeDatatablesOnMenuToggle();
        });
    </script>
    {{--<script>
        let VARIABLES;
        let FUNCTIONS;
        $(document).ready(function () {
            VARIABLES = {
                SUBVIEW:{},
                jQElements: {
                    employeeOfTheWeekSection: $('#employeeOfTheWeekSection'),
                    departmentSelect: $('#departmentSelect').selectpicker(),
                    userTypeSelect: $('#userTypeSelect').selectpicker(),
                    monthDatetimepicker: $('#monthDatetimepicker').datetimepicker({
                        language: 'pl',
                        minView: 3,
                        startView: 3,
                        format: 'yyyy-mm',
                        startDate: moment('2018-09-01').subtract(2,'months').format('YYYY-MM-DD'),
                        endDate: moment().format('YYYY-MM-DD')
                    }),
                },
                DATA_TABLES: {}
            };

            FUNCTIONS = {
                SUBVIEW:{},
                /* function grups should be before other functions which aren't grouped */
                EVENT_HANDLERS: {
                    callEvents:function () {
                        (function monthDatetimepickerHandler() {
                            VARIABLES.jQElements.monthDatetimepicker.change(function () {
                                FUNCTIONS.loadSubview();
                            });
                        })();
                        (function departmentSelectHandeler() {
                            VARIABLES.jQElements.departmentSelect.change(function (e) {
                                if(parseInt($(e.target).val())>0) {
                                    VARIABLES.jQElements.userTypeSelect.parent().parent().attr('hidden', false);
                                    FUNCTIONS.loadSubview();
                                }else{
                                    VARIABLES.jQElements.employeeOfTheWeekSection.empty();
                                    VARIABLES.jQElements.userTypeSelect.parent().parent().attr('hidden', true);
                                }
                            });
                        })();
                        (function userTypeSelectHandler() {
                            VARIABLES.jQElements.userTypeSelect.change(function (e) {
                                FUNCTIONS.loadSubview();
                            });
                        })();
                    }
                },
                AJAXs: {
                    getEmployeeOfTheWeekSectionSubView: function (data) {
                        return $.ajax({
                            type: 'POST',
                            url: '{{route('api.employeeOfTheWeekSubViewAjax')}}',
                            data: data,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                return response;
                            }
                        });
                    }
                },
                loadSubview: function(){
                    VARIABLES.SUBVIEW = {};
                    FUNCTIONS.SUBVIEW = {};
                    if(parseInt(VARIABLES.jQElements.userTypeSelect.val())>0){
                        swal({
                            title: 'Ładowawnie...',
                            text: 'To może chwilę zająć',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            onOpen: () => {
                                swal.showLoading();
                                FUNCTIONS.AJAXs.getEmployeeOfTheWeekSectionSubView({
                                    departmentInfoId: VARIABLES.jQElements.departmentSelect.val(),
                                    userTypeId: VARIABLES.jQElements.userTypeSelect.val(),
                                    selectedMonth: VARIABLES.jQElements.monthDatetimepicker.find('input').val()
                                }).done(function (resolve) {
                                    VARIABLES.jQElements.employeeOfTheWeekSection.empty();
                                    if(resolve !== 'noView'){
                                        VARIABLES.jQElements.employeeOfTheWeekSection.html(resolve);
                                    }else{
                                        VARIABLES.jQElements.employeeOfTheWeekSection.append($('<h1>').append('Brak danych o premiach'))
                                    }
                                    swal.close();
                                });
                            }
                        });

                    }
                }
            };
            FUNCTIONS.EVENT_HANDLERS.callEvents();
            //resizeDatatablesOnMenuToggle();
        });
    </script>--}}
@endsection
