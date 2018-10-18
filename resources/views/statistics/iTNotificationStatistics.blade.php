@php
    /**
     * Created by PhpStorm.
     * User: veronaprogramista
     * Date: 18.10.18
     * Time: 09:38
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
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">Statystyki zgłoszeń pracowników IT</div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">

        </div>
        <div class="panel-body">

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
