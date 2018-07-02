@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')

    <style>
        .heading-container {
            text-align: center;
            font-size: 2em;
            margin: 1em;
            font-weight: bold;
            box-shadow: 0 1px 15px 1px rgba(39, 39, 39, .1);
            padding-top: 1em;
            padding-bottom: 1em;
        }

        .form-container {
            box-shadow: 0 1px 15px 1px rgba(39, 39, 39, .1);
            padding-top: 1em;
            padding-bottom: 1em;
            margin: 1em;
        }
    </style>

    {{--Header page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Panel zarządzania miastami</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Miasta
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button data-toggle="modal" class="btn btn-default cityToModal" id="NewCityModal"
                                    data-target="#ModalCity" data-id="1" title="Nowe Miasto"
                                    style="margin-bottom: 14px">
                                <span class="glyphicon glyphicon-plus"></span> <span>Dodaj Miasto</span>
                            </button>
                            <div>
                                <table id="datatable" class="thead-inverse table table-striped table-bordered"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Wojweództwo</th>
                                        <th>Miasto</th>
                                        <th>Kod Pocztowy</th>
                                        <th>Szerokość Geo.</th>
                                        <th>Długość Geo.</th>
                                        <th>Ilość Pokazów</th>
                                        <th>Karencja</th>
                                        <th>Edycja</th>
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
    </div>


    <div id="ModalCity" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal_title">Dodawanie Miasta<span id="modalCity"></span></h4>
                </div>
                <div class="modal-body">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Formularz
                        </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="myLabel">Województwo:</label>
                                            <select class="form-control" id="voiovedshipID">
                                                @foreach($allVoivodeship as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="myLabel">Miasto:</label>
                                            <input class="form-control" id="cityName" name="cityName"
                                                   placeholder="Miasto" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="myLabel">Ilość pokazów:</label>
                                            <input class="form-control" id="eventCount" name="eventCount"
                                                   placeholder="Ilość pokazów" type="number" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="myLabel">Karencja:</label>
                                            <input class="form-control" id="gracePeriod" name="gracePeriod"
                                                   placeholder="Karencja" type="number" min="-1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="myLabel">Kod pocztowy:</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input type="text" id="zipCode1" class="form-control col-md-4" placeholder="- -" aria-describedby="basic-addon1" style="text-align: center; letter-spacing: 8px">
                                                        <span class="input-group-addon" id="basic-addon1">-</span>
                                                        <input type="text" id="zipCode2" class="form-control col-md-7" placeholder="- - -" aria-describedby="basic-addon1" style="text-align: center; letter-spacing: 8px">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="myLabel">Szerokość geograficzna:</label>
                                            <input class="form-control" id="latitude" name="latitude"
                                                   placeholder="Szerokość geograficzna" type="number">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="myLabel">Długość geograficzna:</label>
                                            <input class="form-control" id="longitude" name="longitude"
                                                   placeholder="Długość geograficzna" type="number">
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="visibility: hidden; display:inline;">
                                        <div class="form-group">
                                            <label class="myLabel">Status</label>
                                            <input class="form-control" id="status" name="status" placeholder="Status"
                                                   type="number">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-success form-control" id="saveCityModal"
                                                onclick="saveCity(this)">Dodaj Miasto
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="alert alert-success">--}}
                            {{--<h4>--}}
                            {{--<p>Aktualny wynik wyliczany jest na podstawie ostatnich ~18 RBH danego konsultanta.</p>--}}
                            {{--<p>W przypadku gdy, aktualny wynik jest większy niż 0.5, wymagane jest aby wynik docelowy mieścił się w przedziale od 10% do 30% aktualnego wyniku.</p>--}}
                            {{--<p>Konsultant wyświetli się na liście, po zaakceptowaniu przynajmniej jednej godziny.</p>--}}
                            {{--</h4>--}}
                            {{--</div>--}}

                        </div>
                    </div>
                    <div class="row">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" value="0" id="cityID"/>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>


        //flaga zatwierdzenia zapisu miasta w modalu, unikniecie przeladowania datatable
        var saveCityButtonClicked = false;

        //flaga dodania nowego miasta - po poprawnym wykonaniu ajaxa wyswietli sie komunikat
        var addNewCityFlag = false;
        var editCityFlag = false;

        //Sprawdzenie czy parametr jest liczba 
        function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        // czyszczenie modalu
        function clearModal() {
            $('#voiovedshipID').val("1");
            $('#cityName').val("");

            $('#zipCode1').val("");
            $('#zipCode2').val("");
            $('#latitude').val("");
            $('#longitude').val("");

            $('#eventCount').val("");
            $('#gracePeriod').val("");
            $('#cityID').val(0);
        }

        //Zapisanie miasta
        function saveCity(e) {
            let voiovedshipID = $('#voiovedshipID').val();
            let cityName = $('#cityName').val();
            let eventCount = $('#eventCount').val();
            let gracePeriod = $('#gracePeriod').val();

            let status = $('#status').val();
            let zipCode = $('#zipCode1').val()+$('#zipCode2').val();
            let latitude = $('#latitude').val();
            let longitude = $('#longitude').val();

            let validation = true;

            if (zipCode.trim().length == 0) {
                validation = false;
                swal("Podaj kod pocztowy")
            }
            if (latitude.trim().length == 0) {
                validation = false;
                swal("Podaj szerokość geograficzną")
            }
            if (longitude.trim().length == 0) {
                validation = false;
                swal("Podaj dlugość geograficzną")
            }
            if (cityName.trim().length == 0) {
                validation = false;
                swal("Podaj nazwę miasta")
            }
            if (!isNumber(gracePeriod)) {
                validation = false;
                swal("Podaj prawdiłową liczbę karencji")
            }
            if (!isNumber(eventCount)) {
                validation = false;
                swal("Podaj prawdiłową liczbę godzin pokazu")
            }
            if (validation) {
                $('#saveCityModal').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "{{route('api.saveNewCity')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'voiovedshipID': voiovedshipID,
                        'cityName': cityName,
                        'eventCount': eventCount,
                        'gracePeriod': gracePeriod,
                        'latitude': latitude,
                        'longitude': longitude,
                        'zipCode': zipCode,
                        'cityID': $('#cityID').val(),
                        'status': status
                    },
                    success: function (response) {
                        $('#ModalCity').modal('hide');
                        if (addNewCityFlag) {
                            $.notify({
                                icon: 'glyphicon glyphicon-ok',
                                message: 'Dodano nowe miasto <strong>' + cityName + '</strong>'
                            }, {
                                type: "success"
                            });
                            addNewCityFlag = false;
                        }
                        if (editCityFlag) {
                            $.notify({
                                icon: 'glyphicon glyphicon-ok',
                                message: 'Edytowano miasto <strong>' + cityName + '</strong>'
                            }, {
                                type: "success"
                            });
                            editCityFlag = false;
                        }
                        $('#saveCityModal').prop('disabled', false);
                    }
                })
            }
        }

        $('#zipCode1').keyup((e)=>{
            checkValue = parseInt(e.target.value);
            e.target.value = isNaN(checkValue) ? "" : e.target.value;
            if(e.target.value>2)
                e.target.value = e.target.value.slice(0,2);
            checkValue = e.target.value;
            if(checkValue.length === 2){
                $('#zipCode2').focus();
            }
        }).focus((e)=>{
            $(e.target).select();
        });

        $('#zipCode2').keyup((e)=>{
            checkValue = parseInt(e.target.value);
            e.target.value = isNaN(checkValue) ? "" : e.target.value;
            if(e.target.value>3)
                e.target.value = e.target.value.slice(0,3);

        }).focus((e)=>{
            $(e.target).select();
        });

        $(document).ready(function () {

            $('#ModalCity').on('hidden.bs.modal', function () {
                $('#cityID').val("0");
                clearModal();
                if (saveCityButtonClicked) {
                    table.ajax.reload();
                    saveCityButtonClicked = false;
                }
            });

            $('#saveCityModal').click((e) => {
                saveCityButtonClicked = true;
                if ($(e.target).text() === 'Dodaj Miasto') {
                    addNewCityFlag = true;
                } else if ($(e.target).text() === 'Edytuj Miasto') {
                    editCityFlag = true;
                }
            });

            $('#NewCityModal').click(() => {
                $('#ModalCity .modal-title').first().text('Dodawanie Miasta');
                $('#ModalCity #saveCityModal').first().text('Dodaj Miasto');
            });

            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function (settings) {
                },
                "ajax": {
                    'url': "{{ route('api.getCity') }}",
                    'type': 'POST',
                    'data': function (d) {
                        // d.date_start = $('#date_start').val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                }, "rowCallback": function (row, data, index) {
                    if (data.status == 1) {
                        $(row).css('background', '#c500002e')
                    }
                    $(row).attr('id', data.id);
                    return row;
                }, "fnDrawCallback": function (settings) {
                    /**
                     * Zmiana statusu miasta
                     */
                    $('.button-status-city').on('click', function () {
                        let cityId = $(this).data('id');
                        let cityStatus = $(this).data('status');
                        let nameOfAction = "";
                        if (cityStatus == 0)
                            nameOfAction = "Tak, wyłącz Miasto";
                        else
                            nameOfAction = "Tak, włącz Miasto";
                        swal({
                            title: 'Jesteś pewien?',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: nameOfAction
                        }).then((result) => {
                            if (result.value) {

                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('api.changeStatusCity') }}", // do zamiany
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: {
                                        'cityId': cityId
                                    },
                                    success: function (response) {
                                        if (cityStatus == 0) {
                                            $.notify({
                                                icon: 'glyphicon glyphicon-ok',
                                                message: 'Miasto wyłączone'
                                            }, {
                                                type: "success"
                                            });
                                        } else if (cityStatus == 1) {
                                            $.notify({
                                                icon: 'glyphicon glyphicon-ok',
                                                message: 'Miasto włączone'
                                            }, {
                                                type: "success"
                                            });
                                        }
                                        table.ajax.reload();
                                    }
                                });
                            }
                        })
                    });

                    /**
                     * Edycja coachingu
                     */
                    $('.button-edit-city').on('click', function () {
                        cityId = $(this).data('id');
                        $.ajax({
                            type: "POST",
                            url: "{{ route('api.findCity') }}", // do zamiany
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'cityId': cityId
                            },
                            success: function (response) {
                                clearModal();
                                $('#ModalCity .modal-title').first().text('Edycja Miasta');
                                $('#ModalCity #saveCityModal').first().text('Edytuj Miasto');
                                $('#voiovedshipID').val(response.voivodeship_id);
                                $('#cityName').val(response.name);
                                $('#eventCount').val(response.max_hour);
                                $('#gracePeriod').val(response.grace_period);
                                zipCode = String(response.zip_code);
                                length = zipCode.length;
                                for(i = 0; i < 5-length; i++){
                                    zipCode = "0".concat(zipCode);
                                }
                                $('#zipCode1').val(zipCode.slice(0,2));
                                $('#zipCode2').val(zipCode.slice(2,5));
                                $('#latitude').val(response.latitude);
                                $('#longitude').val(response.longitude);
                                $('#cityID').val(response.id);
                                $('#status').val(response.status);
                                $('#ModalCity').modal('show');
                            }
                        });
                    });
                }, "columns": [
                    {"data": "vojName"},
                    {"data": "name"},
                    {"data": function(data,type,dataToSet){
                            zipCode = String(data.zip_code);
                            length = zipCode.length;
                            for(i = 0; i < 5-length; i++){
                                zipCode = "0".concat(zipCode);
                            }
                            return zipCode.slice(0,2)+'-'+
                                zipCode.slice(2,5);
                        },name:"zip_code"},
                    {"data": "latitude"},
                    {"data": "longitude"},
                    {"data": "max_hour"},
                    {
                        "data": function (data, type, dataToSet) {
                            if (data.grace_period < 0)
                                return 'Nielimitowane';
                            else
                                return data.grace_period;
                        }, name: "grace_period"
                    },
                    {
                        "data": function (data, type, dataToSet) {
                            let returnButton = "<button class='button-edit-city btn btn-warning btn-block'  data-id=" + data.id + ">Edycja</button>";
                            if (data.status == 0)
                                returnButton += "<button class='button-status-city btn btn-danger btn-block' data-id=" + data.id + " data-status=0 >Wyłącz</button>";
                            else
                                returnButton += "<button class='button-status-city btn btn-success btn-block' data-id=" + data.id + " data-status=1 >Włącz</button>";
                            return returnButton;
                        }, "orderable": false, "searchable": false
                    }
                ]
            });
        })
    </script>
@endsection
