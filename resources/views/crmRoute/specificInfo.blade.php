@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')


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
                    <div class="client-container">
                        <div class="form-group">
                            <label>Miasto</label>
                            <select class="form-control">
                                <option value="{{$info->city_id}}">{{$info->cityName}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Województwo</label>
                            <select class="form-control">
                                <option value="{{$info->voivode_id}}">{{$info->voivodeName}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Godzina pokazu</label>
                            <input type="time" class="form-control" name="hour">
                        </div>

                        <label>Wybierz hotel:</label>
                        <table class="thead-inverse table table-striped table-bordered" data-typ="datatable" cellspacing="0" width="100%">
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
                    </div>


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

            table = $('[data-typ="datatable"]').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },
                "rowCallback": function( row, data, index ) {
                    $(row).attr('id', "hotelId_" + data.id);
                    return row;
                },"fnDrawCallback": function(settings) {

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
        })

        $('[data-typ="datatable"]').on( 'draw.dt', function () {
            $('[data-typ="datatable"] tbody tr').on('click', function() {

                if($(this).hasClass('check')) {
                    $(this).removeClass('check');
                    $(this).find('.checkbox_info').prop('checked',false);
                }
                else {
                    table.$('tr.check').removeClass('check');
                    $.each($('[data-typ="datatable"]').find('.checkbox_info'), function (item, val) {
                        $(val).prop('checked', false);
                    });
                    $(this).addClass('check');
                    $(this).find('.checkbox_info').prop('checked', true);
                }
            })
        } );
    </script>
@endsection
