@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')

    @php
    $i = 1;
    $iterator = 0;
    @endphp

    <style>
        .client-wrapper {
            display:flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .client-container {
            background-color: white;
            padding: 2em;
            box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
            border: 0;
            border-radius: .1875rem;
            margin: 1em;
            margin-bottom: 3em;

            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 90%;
            max-width: 90%;

            line-height: 2em;

        }

        header {
            text-align: center;
            font-size: 2em;
            font-weight: bold;
        }
        .check{
            background: #B0BED9 !important;
        }

    </style>


    <div class="row">

    </div>
    <div class="client-wrapper">
        <div class="client-container">
            <header>Przypisywanie szczegółowych informacji do tras klienta @if(isset($clientName))<i>{{$clientName}}</i>@endif</header>
        </div>
    </div>

    <div class="client-wrapper">
        <div class="client-container">
            <section>
                @foreach($clientRouteInfo as $info)
                    @php
                        $i = 1;
                    @endphp
                    <div class="client-container">
                        @foreach($info as $item)
                        @if ($loop->first)
                        {{--<div class="form-group">--}}
                            {{--<label>Miasto</label>--}}
                            {{--<select class="form-control">--}}
                                {{--<option value="{{$item->city_id}}">{{$item->cityName}}</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                            <h2 class="voivode_info">Województwo: {{$item->voivodeName}}</h2>
                            <h2 class="city_info">Miasto: {{$item->cityName}}</h2>
                        {{--<div class="form-group">--}}
                            {{--<label>Województwo</label>--}}
                            {{--<select class="form-control">--}}
                                {{--<option value="{{$item->voivode_id}}">{{$item->voivodeName}}</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        <label>Wybierz hotel:</label>
                        <table id="datatable_@php echo $iterator @endphp" class="thead-inverse table table-striped table-bordered datatable" data-typ="datatable" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Nazwa</th>
                                <th>Wojewodztwo</th>
                                <th>Miasto</th>
                                <th>Akcja</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                            @endif
                        <div class="form-group">
                            <label>Godzina pokazu nr. @php echo $i; @endphp</label>
                            <input type="time" class="form-control" name="hour">
                        </div>
                        @php
                            $i++;
                        @endphp

                        @endforeach
                    </div>
                    @php
                    $iterator++;
                    @endphp
                @endforeach
            </section>
        </div>
    </div>





@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded',function(event) {

            const voivodeeId = null;
            const cityId = null;
            let numberOfLoops = {{$iterator}};


            newTable = $('.datatable');
            newTable.each(function() {

                table = $(this).DataTable({
                    "autoWidth": true,
                    "processing": true,
                    "serverSide": true,
                    "drawCallback": function( settings ) {
                    },
                    "rowCallback": function( row, data, index ) {
                        $(row).attr('id', "hotelId_" + data.id);
                        return row;
                    },"fnDrawCallback": function(settings) {
                           $('table tbody tr').on('click', function() {

                               if($(this).hasClass('check')) {
                                   $(this).removeClass('check');
                                   $(this).find('.checkbox_info').prop('checked',false);
                               }
                               else {
                                   table.$('tr.check').removeClass('check');
                                   $.each($('.datatable').find('.checkbox_info'), function (item, val) {
                                       $(val).prop('checked', false);
                                   });
                                   $(this).addClass('check');
                                   $(this).find('.checkbox_info').prop('checked', true);
                               }
                           })
                    }, "ajax": {
                        'url': "{{ route('api.showHotelsAjax') }}",
                        'type': 'POST',
                        'data': function (d) {
                            d.voivode = voivodeeId;
                            d.city = cityId;
                        },
                        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    },
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                    },"columns":[
                        {"data":function (data, type, dataToSet) {
                                return data.name;
                            },"name":"name","orderable": false
                        },
                        {"data": function(data, type, dataToSet) {
                                return data.voivodeName;
                            },"name":"voivodeName", "orderable": false
                        },
                        {"data": function(data, type, dataToSet) {
                                return data.cityName;
                            },"name":"cityName", "orderable": false
                        },
                        {"data":function (data, type, dataToSet) {
                                return '<input class="checkbox_info" type="checkbox" value="' + data.id + '" style="display:inline-block;">';
                            },"orderable": false, "searchable": false
                        }
                    ]
                });
            });

            {{--table = $('.datatable').DataTable({--}}
                {{--"autoWidth": true,--}}
                {{--"processing": true,--}}
                {{--"serverSide": true,--}}
                {{--"drawCallback": function( settings ) {--}}
                {{--},--}}
                {{--"rowCallback": function( row, data, index ) {--}}
                    {{--$(row).attr('id', "hotelId_" + data.id);--}}
                    {{--return row;--}}
                {{--},"fnDrawCallback": function(settings) {--}}
                    {{--$('.datatable').on( 'select.dt', function ( e, dt, type, indexes ) {--}}
                        {{--if ( type === 'row' ) {--}}
                            {{--var data = $('.datatable').rows( indexes ).data().pluck( 'id' );--}}
                            {{--console.log('hej');--}}

                            {{--// do something with the ID of the selected items--}}
                        {{--}--}}
                    {{--} );--}}
                {{--}, "ajax": {--}}
                    {{--'url': "{{ route('api.showHotelsAjax') }}",--}}
                    {{--'type': 'POST',--}}
                    {{--'data': function (d) {--}}
                        {{--d.voivode = voivodeeId;--}}
                        {{--d.city = cityId;--}}
                    {{--},--}}
                    {{--'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}--}}
                {{--},--}}
                {{--"language": {--}}
                    {{--"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"--}}
                {{--},"columns":[--}}
                    {{--{"data":function (data, type, dataToSet) {--}}
                            {{--return data.name;--}}
                        {{--},"name":"name","orderable": false--}}
                    {{--},--}}
                    {{--{"data": function(data, type, dataToSet) {--}}
                            {{--return data.voivodeName;--}}
                        {{--},"name":"voivodeName", "orderable": false--}}
                    {{--},--}}
                    {{--{"data": function(data, type, dataToSet) {--}}
                            {{--return data.cityName;--}}
                        {{--},"name":"cityName", "orderable": false--}}
                    {{--},--}}
                    {{--{"data":function (data, type, dataToSet) {--}}
                            {{--return '<input class="checkbox_info" type="checkbox" value="' + data.id + '" style="display:inline-block;">';--}}
                        {{--},"orderable": false, "searchable": false--}}
                    {{--}--}}
                {{--]--}}
            {{--});--}}


            // $('.datatable').on( 'draw.dt', function () {
            //     $('[data-typ="datatable"] tbody tr').on('click', function() {
            //
            //         if($(this).hasClass('check')) {
            //             $(this).removeClass('check');
            //             $(this).find('.checkbox_info').prop('checked',false);
            //         }
            //         else {
            //             table.$('tr.check').removeClass('check');
            //             $.each($('.datatable').find('.checkbox_info'), function (item, val) {
            //                 $(val).prop('checked', false);
            //             });
            //             $(this).addClass('check');
            //             $(this).find('.checkbox_info').prop('checked', true);
            //         }
            //     })
            // } );

        })


    </script>
@endsection
