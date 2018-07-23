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
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav "></div>
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

        resizeDatatablesOnMenuToggle();
    </script>
@endsection
