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

    <style>
        .colorRow {
            background: #c500002e !important;
        }
    </style>

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

                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">Kod pocztowy</label>
                                <select name="zipCode" id="zipCode" class="form-control" multiple="multiple">
                                    <option value="0">Wybierz</option>
                                    @foreach($zipCode as $item)
                                        <option>{{$item->zip_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                        <div class="col-mg-12">
                            <table id="datatable" class="thead-inverse table row-border table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Nazwa</th>
                                    <th>Wojewodztwo</th>
                                    <th>Miasto</th>
                                    <th>Ulica</th>
                                    <th>Kod Pocztowy</th>
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="street">Ulica</label>
                                                <input type="text" name="street" id="street" class="form-control" placeholder="Nazwa Ulicy" value="">
                                            </div>
                                        </div>
                                        {{--<div class="col-md-4">
                                            <div class="form-group">
                                                <label for="price">Cena za salę</label>
                                                <input type="number" step="0.01" min="0" name="price" id="price" class="form-control" placeholder="Cena w złotówkach np. 125,99" value="">
                                            </div>
                                        </div>--}}
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="myLabel">Kod pocztowy:</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <input type="text" id="zipCode1" class="form-control zipCode" placeholder="_" aria-describedby="basic-addon1" style="text-align: center; padding: 1px">
                                                            <span class="input-group-addon" id="basic-addon1" style="padding: 0px"></span>
                                                            <input type="text" id="zipCode2" class="form-control zipCode" placeholder="_" aria-describedby="basic-addon1" style="text-align: center; padding: 1px">
                                                            <span class="input-group-addon" id="basic-addon1" style="padding: 3px;">-</span>
                                                            <input type="text" id="zipCode3" class="form-control zipCode" placeholder="_" aria-describedby="basic-addon1" style="text-align: center; padding: 1px">
                                                            <span class="input-group-addon" id="basic-addon1"style="padding: 0px"></span>
                                                            <input type="text" id="zipCode4" class="form-control zipCode" placeholder="_" aria-describedby="basic-addon1" style="text-align: center; padding: 1px">
                                                            <span class="input-group-addon" id="basic-addon1"style="padding: 0px"></span>
                                                            <input type="text" id="zipCode5" class="form-control zipCode" placeholder="_" aria-describedby="basic-addon1" style="text-align: center; padding: 1px">
                                                            {{--
                                                            <input type="text" id="zipCode1" class="form-control col-md-4" placeholder="- -" aria-describedby="basic-addon1" style="text-align: center; letter-spacing: 8px">
                                                            <span class="input-group-addon" id="basic-addon1">-</span>
                                                            <input type="text" id="zipCode2" class="form-control col-md-7" placeholder="- - -" aria-describedby="basic-addon1" style="text-align: center; letter-spacing: 8px">--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="comment">Komentarz</label>
                                                <input type="text" name="comment" id="comment" class="form-control" placeholder="Tutaj wprowadź krótki komentarz max 255 znaków" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-default form-control"id="saveHotel">
                                                <span class=’glyphicon glyphicon-plus’></span> Dodaj Hotel
                                            </button>
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
        var hotelStatus = 1;
        var city = 0;/*
        let zipCode2 = document.getElementById('zipCode2');
        let zipCode1 = document.getElementById('zipCode1');*/

        function clearContent(container) {
            container.innerHTML = '';
        }
        // czyszczenie modalu
        function clearModal() {
            cityId = 0;
            addNewHotelFlag = false;
            editHotelFlag = false;
            $('#HotelModal .modal-title').first().text('Dodaj Hotel');

            $('#HotelModal #saveHotel').first().text('');
            $('#HotelModal #saveHotel').first().prop('class','btn btn-default form-control');
            $('#HotelModal #saveHotel').first().append($('<span class="glyphicon glyphicon-plus"></span>'));
            $('#HotelModal #saveHotel').first().append('Dodaj Hotel');
            $("#name").val("");
            //$('#price').val("");
            $('#street').val("");
            $('#voivodeAdd').val(0);
            $('#cityAdd').val(0);
            $('#comment').val("");
            $('#hotelId').val(0);
            $('.zipCode').val("");
            /*
            $('#zipCode2').val("");*/
        }

        document.addEventListener('DOMContentLoaded', function(event) {
            $('#NewHotelModal').on('click',function () {
                hotelStatus = 1;
                clearModal();
                addNewHotelFlag = true;
            });

            $('#HotelModal').on('hidden.bs.modal', function () {
                hotelStatus = 1;
                table.ajax.reload();
            });
            let voivodeeId = [];
            let cityId = [];
            let zipCode = [];
            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },
                "rowCallback": function (row, data, index) {
                    if (data.status == 0) {
                        $(row).addClass('colorRow');
                    }
                    return row;
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


                                $('#HotelModal #saveHotel').first().text('');
                                $('#HotelModal #saveHotel').first().prop('class','btn btn-success form-control');
                                $('#HotelModal #saveHotel').first().append($('<span class="glyphicon glyphicon-save"></span>'));
                                $('#HotelModal #saveHotel').first().append(' Zapisz Hotel');
                               $("#name").val(response.name);
                               //$('#price').val(response.price);
                                $('#street').val(response.street);
                                hotelStatus = response.status;
                               $('#voivodeAdd').val(response.voivode_id);
                               $('#comment').val(response.comment);

                                zipCode = String(response.zip_code);
                                if(zipCode != "null") {
                                    length = zipCode.length;
                                    for(i = 0; i < 5-length; i++){
                                        zipCode = "0".concat(zipCode);
                                    }
                                    zipCodeInputs = $('.zipCode');
                                    for(i = 0; i < 5; i++)
                                        $(zipCodeInputs.get(i)).val(zipCode.slice(i,i+1));
                                    /*$('#zipCode1').val(zipCode.slice(0,2));
                                    $('#zipCode2').val(zipCode.slice(2,5));*/
                                }
                                else {
                                    $('.zipCode').val('');
                                }

                                city = response.city_id;
                               $('#voivodeAdd').trigger( "change" );
                               $('#HotelModal').modal('show');
                            }
                        });
                    });

                    /**
                     * Zmiana statusu hotelu
                     */
                    $('.button-status-hotel').on('click',function () {
                        let thisButton = $(this);
                        let hotelId = thisButton.data('id');
                        hotelStatus = thisButton.data('status');
                        let nameOfAction = "";
                        if(hotelStatus == 0)
                            nameOfAction = "Tak, wyłącz Hotel";
                        else
                            nameOfAction = "Tak, włącz Hotel";
                        swal({
                            title: 'Jesteś pewien?',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: nameOfAction
                        }).then((result) => {
                            if (result.value) {
                                thisButton.prop('disabled',true);
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('api.changeStatusHotel') }}", // do zamiany
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: {
                                        'hotelId'   : hotelId
                                    },
                                    success: function (response) {
                                        $.notify({
                                            icon: 'glyphicon glyphicon-ok',
                                            message: 'Status hotelu został zmieniony'
                                        }, {
                                            type: "info"
                                        });
                                        const thisRow = thisButton[0].parentElement.parentElement;
                                        if(hotelStatus == 0){

                                            thisButton.removeClass('btn-danger');
                                            thisButton.addClass('btn-success');
                                            thisButton.data('status', 1);
                                            thisButton.text('Włącz');
                                            thisButton.prepend("<span class='glyphicon glyphicon-off'></span> ");

                                            thisRow.classList.add('colorRow');
                                        }else {
                                            thisButton.removeClass('btn-success');
                                            thisButton.addClass('btn-danger');
                                            thisButton.data('status', 0);
                                            thisButton.text('Wyłącz');
                                            thisButton.prepend("<span class='glyphicon glyphicon-off'></span> ");
                                            thisRow.classList.remove('colorRow');
                                        }
                                        thisButton.prop('disabled', false);
                                    }
                                });
                            }})
                    });

                },
                "ajax": {
                    'url': "{{ route('api.showHotelsAjax') }}",
                    'type': 'POST',
                    'data': function (d) {
                        d.voivode = voivodeeId;
                        d.zipCode = zipCode;
                        d.city = cityId;
                        d.status = [1,0]
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
                            return data.street;
                        },"name":"street","orderable": true
                    },
                    {"data": function(data,type,dataToSet){
                        return data.zip_code;
                        },name:"zip_code"
                    },
                    {"data":function (data, type, dataToSet) {
                            let returnButton = "<button class='button-edit-hotel btn btn-info btn-block'  data-id=" + data.id + "><span class='glyphicon glyphicon-edit'></span> Edycja</button>";
                            if (data.status == 1)
                                returnButton += "<button class='button-status-hotel btn btn-danger btn-block' data-id=" + data.id + " data-status=0 ><span class='glyphicon glyphicon-off'></span> Wyłącz</button>";
                            else
                                returnButton += "<button class='button-status-hotel btn btn-success btn-block' data-id=" + data.id + " data-status=1 ><span class='glyphicon glyphicon-off'></span> Włącz</button>";
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
                            if(response[i].id == city){
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
                //var price = $('#price').val();
                var street = $('#street').val();
                var voivode = $('#voivodeAdd').val();
                var city = $('#cityAdd').val();
                var comment = $('#comment').val();
                var validate = true;
                let hotelId = $('#hotelId').val();/*
                let zipCode1 = $('#zipCode1').val();
                let zipCode2 = $('#zipCode2').val();*/
                let zipCode ='';
                $('.zipCode').each(function( key, item ) {
                    zipCode += item.value;
                });
                //let zipCode = zipCode1 + zipCode2;
                if (zipCode.trim().length < 5) {
                    validate = false;
                    swal("Podaj kod pocztowy")
                }

                if (name.trim().length == 0) {
                    swal('Wprowadź nazwę hotelu!')
                    validate = false;
                }
                if (street.trim().length == 0) {
                    swal('Wprowadź ulicę!')
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
               /* if (price == 0) {
                    swal('Wybierz cene za salę')
                    validate = false;
                }*/
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
                            //'price': price,
                            'street': street,
                            'city': city,
                            'zipCode': zipCode,
                            'hotelId': hotelId,
                            'comment' : comment,
                            'hotelStatus' : hotelStatus,
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

            $('.zipCode').on('input', function(e){
                console.log('input');
                $thisZipCode = $(e.target);
                $zipCodes = $('.zipCode');
                $stringValue = String($thisZipCode.val()).replace('-', '');
                $value = parseInt($stringValue);
                if (isNaN($value)) {
                        $thisZipCode.val('');
                } else {
                        if ($zipCodes.index($thisZipCode) < 4) {
                            if (String($value).length === 1) {

                                $thisZipCode.val($value);
                                //focus and select next zipCode input
                                $index = $zipCodes.index($thisZipCode) + 1;
                                if ($index !== $zipCodes.length) {
                                    $($zipCodes.get($index)).focus();
                                }
                            } else {
                                count = 0;
                                for (; count < String($value).length; count++) {
                                    $($zipCodes.get(count)).val(String($value).charAt(count));
                                }
                                if (count < $zipCodes.length) {
                                    $($zipCodes.get(count)).focus();
                                    $($zipCodes.get(count)).select();
                                } else
                                    $thisZipCode.blur();
                            }
                        } else {
                            if (String($value).length === 1) {
                                $thisZipCode.val($value);
                            } else
                                $thisZipCode.val(String($value).charAt(0));
                        }
                }
            }).focus(function(e){
                $(e.target).select();
            }).keyup(function (e) {
                if(e.keyCode == 8 && $(e.target).val().length === 0){
                    $zipCodes = $('.zipCode');
                    $index = $zipCodes.index($(e.target)) - 1;
                    if ($index >= 0) {
                        $($zipCodes.get($index)).focus();
                        $($zipCodes.get($index)).select();
                    }
                }
            });



            /**
             * This function validate first zip code input
             * @param e
             */
            function zipCode1Handler(e) {
                let typedByUser = e.target.value;
                let lastDigit = typedByUser.substr(typedByUser.length - 1, 1);
                let wordUntilLastDigit = typedByUser.substr(0, typedByUser.length - 1);
                let isANumber = !isNaN(lastDigit);
                console.assert(isANumber === false || isANumber === true, "Variable isANumber is not boolean");

                //check wether typed symbol is number, if false, cut value to previous state
                if(isANumber === false) {
                    e.target.value = wordUntilLastDigit;
                }

                //check wether length = 2 and is only digit
                if(typedByUser.length == 2 && isANumber === true) {
                    zipCode2.focus();
                }
                else if(typedByUser.length > 2) { //if value is > 2, if true cut to only 2
                    e.target.value = wordUntilLastDigit;
                }
            }

            /**
             * This function validate second zip code input
             * @param e
             */
            function zipCode2Handler(e) {
                let typedByUser = e.target.value;
                let lastDigit = typedByUser.substr(typedByUser.length - 1, 1);
                let wordUntilLastDigit = typedByUser.substr(0, typedByUser.length - 1);
                let isANumber = !isNaN(lastDigit);
                console.assert(isANumber === false || isANumber === true, "Variable isANumber is not boolean");

                //check wether typed symbol is number, if false, cut value to previous state
                if(isANumber === false) {
                    e.target.value = wordUntilLastDigit;
                }

                //check wether length = 3 and is only digit
                if(typedByUser.length == 3 && isANumber === true) {
                    zipCode2.blur();
                }
                else if(typedByUser.length > 3) { //if value is > 3, if true cut to only 3
                    e.target.value = wordUntilLastDigit;
                }
            }

            /*
            zipCode1.addEventListener('input', zipCode1Handler);
            zipCode2.addEventListener('input', zipCode2Handler);*/

            $('#voivode').select2();
            $('#city').select2();
            $('#zipCode').select2();

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

            $('#zipCode').on('select2:select select2:unselect', function (e) {
                zipCode = $('#zipCode').val();
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
