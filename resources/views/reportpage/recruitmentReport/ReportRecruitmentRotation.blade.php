@php
    /**
     * Created by PhpStorm.
     * User: veronaprogramista
     * Date: 05.10.18
     * Time: 15:05
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
        .bootstrap-select > .dropdown-menu {
            left: 0 !important;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">Raport rotacji rekrutacji</div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel z raportem rotacji rekrutacji
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ URL::to('/pageReportRecruitmentRotationPost') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
                <div class="col-md-2">
                    <label>Od:</label>
                    <div class='input-group date' id='startDatetimepicker' >
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        <input type='text' class="form-control" name="date_start" value="{{date('Y-m-')}}01" readonly/>
                    </div>
                </div>
                <div class="col-md-2">
                    <label>Do:</label>
                    <div class='input-group date' id='stopDatetimepicker' >
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        <input type='text' class="form-control" name="date_stop" value="{{date('Y-m-').date('t')}}" readonly/>
                    </div>
                </div>
                <div class="col-md-4">
                    @if(0)
                    <label for="departmentsSelect">Oddział:</label>
                    <select class="form-control selectpicker" id="departmentsSelect" name="department">
                        @foreach($departments as $dep)
                            <option value="{{$dep->id}}" >{{$dep->departments->name}} {{$dep->department_type->name}}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
                <div class="col-md-4">
                    <button class="btn btn-info btn-block" id="generateReport">Generuj</button>
                </div>
            </div>
            </form>
            <div class="row" style="margin-top: 1em">
                <div class="col-md-12">
                    @include('mail.recruitmentMail.reportRecruitmentRotation')
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
                jQElements:{
                    generateReportButton: $('#generateReport'),
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
                DATA_TABLES: {}
            };

            let FUNCTIONS = {
                /* function grups should be before other functions which aren't grouped */
                EVENT_HANDLERS: {
                },
                AJAXs: {}
            };
            resizeDatatablesOnMenuToggle();
        });
    </script>
@endsection
