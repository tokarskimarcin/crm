{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view shows list of available campaigns,--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: showHotelsAjax, showHotelsGet--}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    {{--<link rel="stylesheet" href="{{asset('/css/ScrollTabla.css')}}">--}}
@endsection
@section('content')

    <style>
        #parent {
            height: 500px;
        }

        #fixTable {
            width: 1800px !important;
        }

    </style>

{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Statystyki miast</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="heading-container">
                                Statystyki miast
                            </div>
                        </div>
                    </div>

                    <div id="parent">
                        <table id="fixTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th colspan="2">Tydzień/ zakres dat</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2">Data</td>
                                    @foreach($days as $item)
                                    <td>{{$item}}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td colspan="2" class="holdDoor">Dzień</td>
                                    @foreach($days as $item)
                                        <td>{{$item}}</td>
                                    @endforeach
                                </tr>
                                    @foreach($clients['Wysyłka'] as $item)
                                        <tr>
                                            <td>Kamery</td>
                                            <td>{{$item->name}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Kamery</td>
                                        <td>SUMA DZIEŃ WYSYŁKA</td>
                                    </tr>
                                    <tr>
                                        <td>Kamery</td>
                                        <td>SUMA TYDZIEŃ WYSYŁKA</td>
                                    </tr>

                                @foreach($clients['Badania'] as $item)
                                    <tr>
                                        <td>Badania</td>
                                        <td>{{$item->name}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Badania</td>
                                    <td>SUMA DZIEŃ BADANIA</td>
                                </tr>
                                <tr>
                                    <td>Badania</td>
                                    <td>SUMA TYDZIEŃ BADANIA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}





@endsection

@section('script')
    <script src="{{asset('/js/tableHeadFixer.js')}}"></script>

    <script>
        $(document).ready(function() {
            $("#fixTable").tableHeadFixer({"head" : false, "left" : 2});
        });
    </script>
@endsection
