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
            Rozliczenia / Pracownicy Tygodnia - Kadra
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel z zatwierdzaniem premii za pracownika tygodnia
        </div>
        <div class="panel-body">
            <div class="row">
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
                <div class="col-md-4">
                    <label for="userTypeSelect">Stanowisko:</label>
                    <select id="userTypeSelect" name="userTypeSelect" class="form-control">
                        <option value="0">Wybierz</option>
                        @foreach($userTypes as $userType)
                            <option value="{{$userType->id}}">{{$userType->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div id="employeeOfTheWeekSection" class="col-md-12"
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
                jQElements: {
                    employeeOfTheWeekSection: $('#employeeOfTheWeekSection'),
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

            let FUNCTIONS = {
                /* function grups should be before other functions which aren't grouped */
                EVENT_HANDLERS: {
                    callEvents:function () {
                        (function userTypeSelectHandler() {
                            VARIABLES.jQElements.userTypeSelect.change(function (e) {
                                VARIABLES.jQElements.employeeOfTheWeekSection.empty();
                                if(parseInt($(e.target).val())>0){
                                    FUNCTIONS.AJAXs.getEmployeeOfTheWeekSectionSubView({
                                        view: $(e.target).val(),
                                        selectedMonth: VARIABLES.jQElements.monthDatetimepicker.find('input').val()
                                    }).done(function (resolve) {
                                        VARIABLES.jQElements.employeeOfTheWeekSection.html(resolve);
                                    });
                                }
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
            };
            FUNCTIONS.EVENT_HANDLERS.callEvents();
            //resizeDatatablesOnMenuToggle();
        });
    </script>
@endsection
