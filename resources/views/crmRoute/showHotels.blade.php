/*
*@category: CRM,
*@info: This view shows list of available hotels (DB table: "hotels"),
*@controller: CrmRouteController,
*@methods: showHotelsAjax, showHotelsGet
*/


@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')



{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Podgląd Hoteli</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                   Wybierz hotel
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="voivode">Województwo</label>
                                <select name="voivode" id="voivode" class="form-control" multiple="multiple">
                                    <option value="0">Wybierz</option>
                                    @foreach($voivodes as $voivode)
                                        <option value="{{$voivode->id}}">{{$voivode->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">Miasto</label>
                                <select name="city" id="city" class="form-control" multiple="multiple">
                                    <option value="0">Wybierz</option>
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}">{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-mg-10">
                            <table id="datatable" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
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
                    </div>
                    <div class="col-md-12">
                        <button id="addNewHotel" class="btn btn-info">Dodaj nowy hotel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function(event) {
            let voivodeeId = [];
            let cityId = [];
            const addNewHotelInput = document.querySelector('#addNewHotel');
            addNewHotelInput.addEventListener('click',(e) => {
                window.location.href = '{{URL::to('/addNewHotel')}}';
            });
            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },
                "ajax": {
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
                        },"name":"name","orderable": true
                    },
                    {"data": function(data, type, dataToSet) {
                            return data.voivodeName;
                        },"name":"voivodeName", "orderable": true
                    },
                    {"data": function(data, type, dataToSet) {
                            return data.cityName;
                        },"name":"cityName", "orderable": true
                    },
                    {"data":function (data, type, dataToSet) {
                            return '<a href="{{URL::to("hotel")}}/' + data.id + '" class="links">Edycja</a>';
                        },"orderable": false, "searchable": false
                    }
                ]
            });

            $('#voivode').select2();
            $('#city').select2();


            $('#voivode').on('select2:select select2:unselect', function (e) {
                let voivodeInp = document.querySelector('#voivode');
                voivodeeId = $('#voivode').val();
                cityId = [];
                table.ajax.reload();
            });

            $('#city').on('select2:select select2:unselect', function (e) {
                let cityInp = document.querySelector('#city');
                cityId = $('#city').val();
                voivodeeId = [];
                table.ajax.reload();
            });
        });
    </script>
@endsection
