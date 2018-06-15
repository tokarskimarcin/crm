@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/rowgroup/1.0.3/css/rowGroup.dataTables.min.css" rel="stylesheet" />


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
                                <th>Oddział</th>
                                <th>Uwagi</th>
                            </tr>
                            </thead>
                        </table>
                    <div class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
        <script src="https://cdn.datatables.net/rowgroup/1.0.3/js/dataTables.rowGroup.min.js"></script>
        <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>

    <script>
       document.addEventListener('DOMContentLoaded', function(mainEvent) {

           table = $('#datatable').DataTable({
               "autoWidth": false,
               "processing": true,
               "serverSide": true,
               order: [[1, 'asc']],
               "drawCallback": function( settings ) {

               },
               "rowCallback": function( row, data, index ) {
                   // $(row).attr('id', "client_" + data.id);
                   // return row;
               },"fnDrawCallback": function(settings) {


               },"ajax": {
                   'url': "{{route('api.campaignsInfo')}}",
                   'type': 'POST',
                   'data': function (d) {

                   },
                   'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
               },
               "language": {
                   "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
               },
               "columns":[
                   {"data":function (data, type, dataToSet) {
                           return data.weekOfYear;
                       },"name":"weekOfYear"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.date;
                       },"name":"date"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.cityName;
                       },"name":"cityName"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.pbxSuccess;
                       },"name":"pbxSuccess"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.pbxSuccess;
                       },"name":"pbxSuccess"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.limits;
                       },"name":"limits"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.loseSuccess;
                       },"name":"loseSuccess"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.clientName;
                       },"name":"clientName"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.departmentName;
                       },"name":"departmentName"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.comment;
                       },"name":"comment"
                   }
               ],
               rowGroup: {
                   dataSrc: 'date',
                   startRender: null,
                   endRender: function (rows, group) {
                       var sumAllSuccess = 0;
                       sumAllSuccess =
                           rows
                           .data()
                           .pluck('pbxSuccess')
                           .reduce( function (a, b) {
                               return a + b*1;
                           }, 0);
                       var sumAllLimit =
                           rows
                               .data()
                               .pluck('limits')
                               .reduce( function (a, b) {
                                   return a + b*1;
                               }, 0);
                       var sumAllLose =
                           rows
                               .data()
                               .pluck('loseSuccess')
                               .reduce( function (a, b) {
                                   return a + b*1;
                               }, 0);

                       return $('<tr/>')
                           .append('<td colspan="4">Podsumowanie Dnia: ' + group + '</td>')
                           .append('<td>' + sumAllSuccess + '</td>')
                           .append('<td>' + sumAllLimit + '</td>')
                           .append('<td>' + sumAllLose + '</td>')
                           .append('<td colspan="3"></td>')

                   },
               },
           });
       });
    </script>
@endsection
