{{--/*--}}
{{--*@category: ,--}}
{{--*@info: This view show departments statitstics charts--}}
{{--*@controller: ,--}}
{{--*@methods: , --}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <link rel="stylesheet" href="{{asset('/css/fixedHeader.dataTables.min.css')}}">
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <style>
        .chart{
            overflow: scroll;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">
            Statystyki Telemarketing / Wykresy statystyk oddziałów
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">

        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="chart">
                        @include('screens.charts')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

        //resizeDatatablesOnMenuToggle();
    </script>
@endsection
