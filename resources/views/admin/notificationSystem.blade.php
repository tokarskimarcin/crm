@php
    /**
     * Created by PhpStorm.
     * User: veronaprogramista
     * Date: 09.10.18
     * Time: 14:29
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
        .leftPanel{
            border-right: 1px #b9b9b9 solid;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">Modyfikacja systemu zgłoszeń</div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel z systemami oceniania
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-6 leftPanel">
1
                </div>
                <div class="col-md-6 rightPanel">
1
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            let VARIABLES = {
                DATA_TABLES: {}
            };

            let FUNCTIONS = {
                /* function grups should be before other functions which aren't grouped */
                EVENT_HANDLERS: {},
                AJAXs: {}
            };
            resizeDatatablesOnMenuToggle();
        });
    </script>
@endsection
