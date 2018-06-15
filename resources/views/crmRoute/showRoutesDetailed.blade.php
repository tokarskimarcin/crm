@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')



{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav "> Szczegółowe informacje o kampaniach
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                </div>
                <div class="panel-body">
                        <table id="datatable" class="thead-inverse table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Tydzien</th>
                                <th>Data</th>
                                <th>Kampania</th>
                                <th>PBX</th>
                                <th>Zaproszenia</th>
                                <th>Limit</th>
                                <th>Straty</th>
                                <th>Projekt</th>
                                <th>Departament</th>
                                <td>Uwagi</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>01.02.2018</td>
                                <td>Lublin</td>
                                <td>50</td>
                                <td>60</td>
                                <td>70</td>
                                <td>-10</td>
                                <td>Damages</td>
                                <td>Lublin Telemarketing</td>
                                <td>Brak</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>01.02.2018</td>
                                <td>Lublin</td>
                                <td>40</td>
                                <td>65</td>
                                <td>70</td>
                                <td>-5</td>
                                <td>Damages</td>
                                <td>Lublin Telemarketing</td>
                                <td>Brak</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>01.02.2018</td>
                                <td>Lublin</td>
                                <td>60</td>
                                <td>70</td>
                                <td>70</td>
                                <td>0</td>
                                <td>Exito</td>
                                <td>Radom Potwierdzenia</td>
                                <td>Brak</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>01.02.2018</td>
                                <td>Lublin</td>
                                <td>45</td>
                                <td>70</td>
                                <td>70</td>
                                <td>0</td>
                                <td>Exito</td>
                                <td>Radom Potwierdzenia</td>
                                <td>Brak</td>
                            </tr>
                            <tr style="font-weight: bold; background: lightslategrey; font-size:1.1em;">
                                <td>1</td>
                                <td>01.02.2018</td>
                                <td></td>
                                <td></td>
                                <td>335</td>
                                <td></td>
                                <td>-15</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>02.02.2018</td>
                                <td>Warszawa/mokotow</td>
                                <td>10</td>
                                <td>35</td>
                                <td>40</td>
                                <td>-5</td>
                                <td>Vitautas</td>
                                <td>Białystok Telemarketing</td>
                                <td>Słabo</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>02.02.2018</td>
                                <td>Warszawa/mokotow</td>
                                <td>15</td>
                                <td>35</td>
                                <td>40</td>
                                <td>-5</td>
                                <td>Vitautas</td>
                                <td>Białystok Telemarketing</td>
                                <td>Brak</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>02.02.2018</td>
                                <td>Warszawa/mokotow</td>
                                <td>20</td>
                                <td>35</td>
                                <td>35</td>
                                <td>0</td>
                                <td>Vitautas</td>
                                <td>Ostrowiec Telemarketing</td>
                                <td>Brak</td>
                            </tr>
                            <tr style="font-weight: bold; background: lightslategrey; font-size:1.1em;">
                                <td>1</td>
                                <td>02.02.2018</td>
                                <td></td>
                                <td></td>
                                <td>105</td>
                                <td></td>
                                <td>-10</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    <div class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
       document.addEventListener('DOMContentLoaded', function(mainEvent) {

           table = $('#datatable').DataTable();

           {{--table = $('#datatable').DataTable({--}}
               {{--"autoWidth": true,--}}
               {{--"processing": true,--}}
               {{--"serverSide": true,--}}
               {{--"drawCallback": function( settings ) {--}}

               {{--},--}}
               {{--"rowCallback": function( row, data, index ) {--}}
                   {{--// $(row).attr('id', "client_" + data.id);--}}
                   {{--// return row;--}}
               {{--},"fnDrawCallback": function(settings) {--}}


               {{--},"ajax": {--}}
                   {{--'url': "{{route('api.getDetailedInfo')}}",--}}
                   {{--'type': 'POST',--}}
                   {{--'data': function (d) {--}}

                   {{--},--}}
                   {{--'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}--}}
               {{--},--}}
               {{--"language": {--}}
                   {{--"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"--}}
               {{--},--}}
               {{--"columns":[--}}
                   {{--{"data":function (data, type, dataToSet) {--}}
                           {{--return data.weekOfYear;--}}
                       {{--},"name":"weekOfYear"--}}
                   {{--},--}}
                   {{--{"data":function (data, type, dataToSet) {--}}
                           {{--return data.date;--}}
                       {{--},"name":"date"--}}
                   {{--},--}}
                   {{--{"data":function (data, type, dataToSet) {--}}
                           {{--return 1;--}}
                       {{--},"name":"name3"--}}
                   {{--},--}}
                   {{--{"data":function (data, type, dataToSet) {--}}
                           {{--return 1;--}}
                       {{--},"name":"name4"--}}
                   {{--},--}}
                   {{--{"data":function (data, type, dataToSet) {--}}
                           {{--return 1;--}}
                       {{--},"name":"name5"--}}
                   {{--},--}}
                   {{--{"data":function (data, type, dataToSet) {--}}
                           {{--return 1;--}}
                       {{--},"name":"name6"--}}
                   {{--},--}}
                   {{--{"data":function (data, type, dataToSet) {--}}
                           {{--return 1;--}}
                       {{--},"name":"name7"--}}
                   {{--},--}}
                   {{--{"data":function (data, type, dataToSet) {--}}
                           {{--return 1;--}}
                       {{--},"name":"name8"--}}
                   {{--},--}}
                   {{--{"data":function (data, type, dataToSet) {--}}
                           {{--return 1;--}}
                       {{--},"name":"name9"--}}
                   {{--}--}}
               {{--]--}}
           {{--});--}}
       });
    </script>
@endsection
