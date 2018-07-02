{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view shows list of available hotels (DB table: "hotels"),--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: showHotelsAjax, showHotelsGet--}}
{{--*/--}}


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
                        <div class="col-md-12">
                            <button data-toggle="modal" class="btn btn-default hotelToModal" id="NewHotelModal"
                                    data-target="#HotelModal" data-id="1" title="Nowy Hotel"
                                    style="margin-bottom: 14px">
                                <span class="glyphicon glyphicon-plus"></span> <span>Dodaj Hotel</span>
                            </button>
                        </div>

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
                    </div>

                        <div class="col-mg-12">
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
            </div>
        </div>
    </div>



<div id="HotelModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_title">Dodaj Hotel<span id="modalHotel"></span></h4>
            </div>
            <div class="modal-body">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Formularz
                    </div>
                    <div class="panel-body">
                        <div class="form-container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Nazwa Hotelu</label>
                                                <input type="text" id="name" class="form-control" name="name" placeholder="Tutaj wprowadź nazwę hotelu" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="voivode">Województwo</label>
                                                <select name="voivodeAdd" id="voivodeAdd" class="form-control" data-element="voivode" required>
                                                    <option value="0">Wybierz</option>
                                                    @foreach($voivodes as $voivode)
                                                        <option value ="{{$voivode->id}}">{{$voivode->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="city">Miasto</label>
                                                <select name="cityAdd" id="cityAdd" class="form-control" required>
                                                    <option value="0">Wybierz</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">Cena za salę</label>
                                                <input type="number" step="0.01" min="0" name="price" id="price" class="form-control" placeholder="Cena w złotówkach np. 125,99" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="comment">Komentarz</label>
                                                <input type="text" name="comment" id="comment" class="form-control" placeholder="Tutaj wprowadź krótki komentarz max 255 znaków" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="button-container">
                                                <input type="submit" id="saveHotel" class="btn btn-success" value="Zapisz zmiany" style="width:100%;font-size:1.1em;font-weight:bold;margin-bottom:1em;margin-top:1em;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
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
<input type="hidden" value="0" id="hotelId"/>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>

        //flaga dodania nowego hotelu - po poprawnym wykonaniu ajaxa wyswietli sie komunikat
        var addNewHotelFlag = false;
        var editHotelFlag = false;
        var cityId = 0;
        function clearContent(container) {
            container.innerHTML = '';
        }
        // czyszczenie modalu
        function clearModal() {
            cityId = 0;
            addNewHotelFlag = false;
            editHotelFlag = false;
            $('#HotelModal .modal-title').first().text('Dodaj Hotel');
            $('#HotelModal #saveHotel').first().text('Zapisz');
            $("#name").val("");
            $('#price').val("");
            $('#voivodeAdd').val(0);
            $('#cityAdd').val(0);
            $('#comment').val("");
            $('#hotelId').val(0);
        }

        document.addEventListener('DOMContentLoaded', function(event) {
            $('#NewHotelModal').on('click',function () {
                clearModal();
                addNewHotelFlag = true;
            });

            $('#HotelModal').on('hidden.bs.modal', function () {
                    console.log(123);
                    table.ajax.reload();
            });
            let voivodeeId = [];
            let cityId = [];
            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },"fnDrawCallback": function (settings) {
                    /**
                     * Edycja Hotelu
                     */
                    $('.button-edit-hotel').on('click', function () {
                        let hotel_id = $(this).data('id');
                        $.ajax({
                            type: "POST",
                            url: "{{ route('api.findHotel') }}", // do zamiany
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'hotelId': $(this).data('id')
                            },
                            success: function (response) {
                               clearModal();
                               $('#hotelId').val(hotel_id);
                               editHotelFlag = true;
                               $('#HotelModal .modal-title').first().text('Edycja Hotelu');
                               $('#HotelModal #saveHotel').first().text('Edytuj Hotel');
                               $("#name").val(response.name);
                               $('#price').val(response.price);
                               $('#voivodeAdd').val(response.voivode_id);
                               $('#comment').val(response.comment);
                                cityId = response.city_id;
                               $('#voivodeAdd').trigger( "change" );
                               $('#HotelModal').modal('show');
                            }
                        });
                    });
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
                            var returnButton = "<button class='button-edit-hotel btn btn-warning btn-block'  data-id=" + data.id + ">Edycja</button>";
                            if (data.status == 0)
                                returnButton += "<button class='button-status-hotel btn btn-danger btn-block' data-id=" + data.id + " data-status=0 >Wyłącz</button>";
                            else
                                returnButton += "<button class='button-status-hotel btn btn-success btn-block' data-id=" + data.id + " data-status=1 >Włącz</button>";
                            return returnButton;
                        },"orderable": false, "searchable": false
                    }
                ]
            });

            $('#voivodeAdd').on('change',function (e) {
                $.ajax({
                    method: "POST",
                    url: "{{ route('api.getCitiesNames') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "id": $(this).val()
                    },
                    success: function(response, status) {
                        let cityInput = document.getElementById('cityAdd');
                        clearContent(cityInput);
                        let zeroValueOption = document.createElement('option');
                        zeroValueOption.value = 0;
                        zeroValueOption.textContent = "Wybierz";
                        cityInput.appendChild(zeroValueOption);

                        for(var i = 0; i < response.length; i++) {
                            let optionC = document.createElement('option');
                            optionC.value = response[i].id;
                            optionC.textContent = response[i].name;
                            if(response[i].id == cityId){
                                optionC.selected = true;
                            }
                            cityInput.appendChild(optionC);
                        }

                    },
                    error: function(err, status, info) {
                        console.log(err);
                        console.log(status);
                        console.log(info);
                    }
                })
            });

            //Walidacja Zapisu
            $('#saveHotel').on('click', function() {
                var name = $("#name").val();
                var price = $('#price').val();
                var voivode = $('#voivodeAdd').val();
                var city = $('#cityAdd').val();
                var comment = $('#comment').val();
                var hotslStatus = 0;
                var validate = true;
                let hotelId = $('#hotelId').val();
                console.log(hotelId);
                if (name.trim().length == 0) {
                    swal('Wprowadź nazwę hotelu!')
                    validate = false;
                }
                if (voivode == 0) {
                    swal('Wybierz województwo!')
                    validate = false;
                }
                if (city == 0 || city=='') {
                    swal('Wybierz miasto!')
                    validate = false;
                }
                if (price == 0) {
                    swal('Wybierz cene za salę')
                    validate = false;
                }
                if(validate) {
                    $('#saveHotel').prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "{{route('api.saveNewHotel')}}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'voivode': voivode,
                            'name': name,
                            'price': price,
                            'city': city,
                            'hotelId': hotelId,
                            'comment' : comment,
                            'hotslStatus' : hotslStatus,
                        },
                        success: function (response) {
                            $('#HotelModal').modal('hide');
                            if (addNewHotelFlag) {
                                $.notify({
                                    icon: 'glyphicon glyphicon-ok',
                                    message: 'Dodano nowy hotel <strong>' + name + '</strong>'
                                }, {
                                    type: "success"
                                });
                                addNewHotelFlag = false;
                            }
                            if (editHotelFlag) {
                                $.notify({
                                    icon: 'glyphicon glyphicon-ok',
                                    message: 'Edytowano hotel <strong>' + name + '</strong>'
                                }, {
                                    type: "success"
                                });
                                editHotelFlag = false;
                            }
                            $('#saveHotel').prop('disabled', false);
                        }
                    })
                }

            });



            $('#voivode').select2();
            $('#city').select2();


            $('#voivode').on('select2:select select2:unselect', function (e) {
                let voivodeInp = document.querySelector('#voivode');
                voivodeeId = $('#voivode').val();
                cityId = [];
                clearSelection('city');
                table.ajax.reload();
            });

            $('#city').on('select2:select select2:unselect', function (e) {
                let cityInp = document.querySelector('#city');
                cityId = $('#city').val();
                voivodeeId = [];
                clearSelection('voivode');
                table.ajax.reload();
            });

            function clearSelection(element) {
                if(element == 'city') {
                    $('#city').val(null).trigger('change');
                }
                else {
                    $('#voivode').val(null).trigger('change');
                }

            }
        });
    </script>
@endsection
