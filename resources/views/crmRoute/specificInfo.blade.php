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

        .invisible {
            display:none;
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

        <div class="client-container addnotation-container">
            <script>
                const addnotation = sessionStorage.getItem('addnotation');
                const addnotationContainer = document.querySelector('.addnotation-container');
                if(addnotation != null) {
                    let infoContainer = document.createElement('div');
                    infoContainer.classList.add('alert');
                    infoContainer.classList.add('alert-info');
                    infoContainer.textContent = addnotation;
                    addnotationContainer.appendChild(infoContainer);
                    sessionStorage.clear();
                }
                else {
                    addnotationContainer.classList.add('invisible');
                }
            </script>
        </div>

        <div class="client-container">
            <section>
                @foreach($clientRouteInfo as $info)
                    @php
                        $i = 1;
                    @endphp
                    <div class="client-container cities-container">
                        @foreach($info as $item)
                        @if ($loop->first)
                                <input type="hidden" value="{{$item->client_route_id}}" class="idOfClientRoute">
                            <h2 class="voivode_info" data-identificator="{{$item->voivode_id}}">Województwo: {{$item->voivodeName}}</h2>
                            <h2 class="city_info" data-identificator="{{$item->city_id}}">Miasto: {{$item->cityName}}</h2>
                        <label>Wybierz hotel:</label>
                        <table id="datatable_@php echo $iterator @endphp" class="thead-inverse table table-striped table-bordered datatable" data-typ="datatable" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Nazwa</th>
                                <th>Wojewodztwo</th>
                                <th>Miasto</th>
                                <th>Wybierz</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                            @endif
                        <div class="form-group">
                            <label>Godzina pokazu nr. @php echo $i; @endphp</label>
                            <input type="time" class="form-control time-input" @if(isset($item->hour)) value="{{$item->hour}}" @endif>
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

        <div class="client-container placeToAppendForm">
            <button id="submit-button" class="btn btn-success" type="button">Zapisz</button>
        </div>
    </div>





@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded',function(event) {

            let hotelIdArr = []; //here we collect id's of each city's hotel;
                    @foreach($clientRouteInfo as $info)
                        @foreach($info as $item)
                            @if($loop->first)
                                @if(isset($item->hotel_id))
                                    var hotelObj = {
                                            hotel_id: {{$item->hotel_id}},
                                            city_id: {{$item->city_id}}
                                        };
                                    hotelIdArr.push(hotelObj);
                                @else
                                    var hotelObj = {
                                            hotel_id: "0",
                                            city_id: {{$item->city_id}}
                                        };
                                    hotelIdArr.push(hotelObj);
                                @endif
                            @endif
                        @endforeach
                    @endforeach
                console.log(hotelIdArr);



            const voivodeeId = null;
            const cityId = null;
            let numberOfLoops = '{{$iterator}}';
            var tableArray = new Array();
            var lp = 1;


            newTable = $('.datatable');
            newTable.each(function() {
                var cityElementOfGivenContainer = $(this).siblings('.city_info').attr('data-identificator');
                console.log(cityElementOfGivenContainer);

                tableArray.push($(this).DataTable({
                    "autoWidth": true,
                    "processing": true,
                    "serverSide": true,
                    "drawCallback": function( settings ) {
                    },
                    "rowCallback": function( row, data, index ) {
                        $(row).attr('id', "hotelId_" + data.id);
                        return row;
                    },"fnDrawCallback": function(settings) {
                        if(lp == hotelIdArr.length){
                            $('table tbody tr').on('click', function() {
                                test = $(this).closest('table');
                                if($(this).hasClass('check')) {
                                    $(this).removeClass('check');
                                    $(this).find('.checkbox_info').prop('checked',false);
                                }
                                else {
                                    test.find('tr.check').removeClass('check');
                                    $.each(test.find('.checkbox_info'), function (item, val) {
                                        $(val).prop('checked', false);
                                    });
                                    $(this).addClass('check');
                                    $(this).find('.checkbox_info').prop('checked', true);
                                }
                            })
                        }
                            lp++;
                        checkedInput = $(this).closest('table').find('input[type="checkbox"]:checked');
                        closestTr = checkedInput.closest('tr');
                        closestTr.addClass('check');


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
                                var cityId = cityElementOfGivenContainer;
                                        for(var i = 0; i<hotelIdArr.length;i++){
                                            if(hotelIdArr[i].city_id == cityId){
                                                if(data.id == hotelIdArr[i].hotel_id) {
                                                    return '<input class="checkbox_info" type="checkbox" value="' + data.id + '" style="display:inline-block;" checked>';
                                                }
                                            }
                                        }
                                       {{--return '<input class="checkbox_info" type="checkbox" value="' + data.id + '" style="display:inline-block;" @foreach($clientRouteInfo as $info) @foreach($info as $item) @if($loop->first) @if(isset($item->hotel_id)) @if($item->hotel_id == data.id) checked @endif @endif @endif @endforeach @endforeach>';--}}
                                       return '<input class="checkbox_info" type="checkbox" value="' + data.id + '" style="display:inline-block;">';
                            },"orderable": false, "searchable": false
                        }
                    ]
                })
                )

            });

            //This object will store every info about given city.
            function CityObject(voivode_id, city_id, time_arr, client_route_id, hotel_id) {
                this.voivodeId = voivode_id;
                this.cityId = city_id;
                this.timeArr = time_arr;
                this.clientRouteId = client_route_id;
                this.hotelId = hotel_id;
                this.showValues = function() {
                    console.log("voivodeId: " + voivode_id);
                    console.log("cityId: " + city_id);
                    console.log("timeArr: " + time_arr);
                    console.log("clientRouteId: " + client_route_id);
                    console.log("hotelId: " + hotel_id);
                };
                this.validate = function() {
                    let isOkFlag = true;
                    if(this.voivodeId != undefined && this.voivodeId != '' && this.voivodeId != null && this.cityId != undefined && this.cityId != null && this.cityId != '' && this.timeArr != '' && this.timeArr.length > 0 && this.clientRouteId != '' && this.clientRouteId != undefined && this.clientRouteId != null) {
                        this.timeArr.forEach(time => {
                           if(time == null || time == '') {
                              isOkFlag = false;
                           }
                        });

                        if(isOkFlag == true) {
                            return true;
                        }
                        else {
                            return false;
                        }
                    }
                    else {
                        return false;
                    }
                };
                this.pushJSON = function() {
                    let obj = {
                        voivodeId: this.voivodeId,
                        cityId: this.cityId,
                        timeArr: this.timeArr,
                        clientRouteId: this.clientRouteId,
                        hotelId: this.hotelId
                    };
                    cityInfoArray.push(obj);

                }
            }

            let cityInfoArray = []; //Here will be all data about given cities filled by user.

            //this function collect data from forms and validate it.
            function submitHandler(e) {
                let isOk = null;
                cityInfoArray = []; //every time user click submit button, we collect data from scratch
                const citiesContainers = document.querySelectorAll('.cities-container'); // node list of containers(logic: every city data)

                citiesContainers.forEach(cityContainer => {
                    const voivodeElement = cityContainer.querySelector('.voivode_info'); //voivode element
                    const cityElement = cityContainer.querySelector('.city_info'); //city element
                    const timeElements = cityContainer.querySelectorAll('.time-input'); //time elements
                    const clientRouteId = cityContainer.querySelector('.idOfClientRoute').value;
                    const hotelId = cityContainer.querySelector('input[type="checkbox"]:checked') == null ? null : cityContainer.querySelector('input[type="checkbox"]:checked').value;

                    let timeArr = []; //in this array we will have all hours from given city

                    const voivodeId = voivodeElement.dataset.identificator;
                    const cityId = cityElement.dataset.identificator;

                    timeElements.forEach(timeElement => {
                        timeArr.push(timeElement.value);
                    });

                    const cityObject = new CityObject(voivodeId, cityId, timeArr, clientRouteId, hotelId);
                    if(isOk != false) {
                        isOk = cityObject.validate();
                    }

                    if(isOk == false) {
                        swal('Wypełnij wszystkie pola');
                    }
                    else {
                        cityObject.pushJSON();
                    }

                });
                if(isOk == true) {
                    submitForm();
                }
            }

            function submitForm() {
                let JSONData = JSON.stringify(cityInfoArray);
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.getJSONRoute') }}',
                    data: {
                        "JSONData": JSONData
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        sessionStorage.setItem('addnotation', 'Zmiany zostały zapisane!');
                        window.location.reload();
                    }
                });
            }

            const submitButton = document.querySelector('#submit-button');
            submitButton.addEventListener('click', submitHandler);

        })


    </script>
@endsection
