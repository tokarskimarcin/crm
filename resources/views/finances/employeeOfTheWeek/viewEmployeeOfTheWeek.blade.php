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
            <div class="row">
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
                    departmentTypeSection: $('#departmentTypeSection'),
                    departmentTypeSelect: $('#departmentTypeSelect').selectpicker()
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
                    let userTypeLabel = $('<label>').attr('for','userTypeSelect').append('Typ pracownika');
                    let userTypeSelect = $('<select>').addClass('form-control')
                        .attr('id','userTypeSelect')
                        .attr('name','userTypeSelect');
                    let userTypeSection = $('<div>').addClass('col-md-2').attr('id','userTypeSection')
                        .append(userTypeLabel)
                        .append(userTypeSelect)
                        .on('remove',function () {
                            $('#monthDatetimepicker').trigger('remove');
                        });
                    if(userTypes.length === 0){
                        userTypeSelect.append($('<option>').append('Brak systemu pracowników tygodnia'));
                    }else{
                        userTypeSelect.append($('<option>').append('Wybierz').prop('selected',true).attr('value', 0));
                        $.each(userTypes,function (index, item) {
                            userTypeSelect.append($('<option>').append(item.name).attr('value', item.id));
                        });
                    }
                    VARIABLES.jQElements.departmentTypeSection.after(userTypeSection);
                    userTypeSelect.selectpicker();
                },
                SUBVIEW:{},
                /* function grups should be before other functions which aren't grouped */
                EVENT_HANDLERS: {
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
