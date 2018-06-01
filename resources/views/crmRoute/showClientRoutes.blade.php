@extends('layouts.main')
@section('style')

@endsection
@section('content')

    <style>
        .check {
            background: #B0BED9 !important;
        }
    </style>

{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Podgląd Tras</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                   Wybierz trasę
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="datatable" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nazwa Klienta</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table id="datatable2" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Miasto</th>
                                    <th>Hotel</th>
                                    <th>Data</th>
                                    <th>Godzina</th>
                                    <th>Podgląd</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function(event) {

            let id = null; //after user click on 1st table row, it assing clientRouteId to this variable
            let rowIterator = null;
            let colorIterator = 0;

            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },
                "rowCallback": function( row, data, index ) {
                    $(row).attr('id', "client_" + data.id);
                    return row;
                },"fnDrawCallback": function(settings) {
                        $('table tbody tr').on('click', function() {
                            test = $(this).closest('table');
                            if($(this).hasClass('check')) {
                                $(this).removeClass('check');
                            }
                            else {
                                test.find('tr.check').removeClass('check');
                                $.each(test.find('.checkbox_info'), function (item, val) {
                                    $(val).prop('checked', false);
                                });
                                $(this).addClass('check');
                                id = $(this).attr('id');
                                indexOfUnderscore = id.lastIndexOf('_');
                                id = id.substr(indexOfUnderscore + 1);
                            }
                            rowIterator = null;
                            colorIterator = 0;
                            table2.ajax.reload();
                        })
                },"ajax": {
                    'url': "{{route('api.getClientRoutes')}}",
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
                            return data.name;
                        },"name":"name"
                    }
                ]
            });


        colorArr = ['red', 'green'];
            table2 = $('#datatable2').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "rowCallback": function( row, data, index ) {
                    $(row).attr('id', "clientRouteInfoId_" + data.id);
                    if(rowIterator == null) {
                        $(row).css( "background-color",colorArr[colorIterator]);
                        rowIterator = data.client_route_id;
                    }
                    else {
                        if(rowIterator == data.client_route_id) {
                            $(row).css( "background-color",colorArr[colorIterator]);
                        }
                        else {
                            $colorIterator++;
                            if($colorIterator == 2) {
                                $colorIterator = 0;
                            }
                            $(row).css( "background-color",colorArr[colorIterator]);
                            rowIterator = data.client_route_id;
                        }
                    }
                    return row;
                },"ajax": {
                    'url': "{{route('api.getClientRouteInfo')}}",
                    'type': 'POST',
                    'data': function (d) {
                        d.id = id;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },
                "columns":[
                    {"data":function (data, type, dataToSet) {
                            return data.cityName;
                        },"name":"cityName"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.hotelName;
                        },"name":"hotelName"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.date;
                        },"name":"date"
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.hour;
                        },"name":"hour"
                    },
                    {"data":function (data, type, dataToSet) {
                            return '<a href="{{URL::to("/specificRoute")}}/' + data.client_route_id + '">Podgląd</a>';
                        },"name":"link"
                    }
                ]
            });
        });
    </script>
@endsection
