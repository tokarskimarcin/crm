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
                    <div class="client-container cities-container">
                        @foreach($info as $item)
                        @if ($loop->first)
                        {{--<div class="form-group">--}}
                            {{--<label>Miasto</label>--}}
                            {{--<select class="form-control">--}}
                                {{--<option value="{{$item->city_id}}">{{$item->cityName}}</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                                <input type="hidden" value="{{$item->client_route_id}}" class="idOfClientRoute">
                            <h2 class="voivode_info" data-identificator="{{$item->voivode_id}}">Województwo: {{$item->voivodeName}}</h2>
                            <h2 class="city_info" data-identificator="{{$item->city_id}}">Miasto: {{$item->cityName}}</h2>
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
                            <input type="time" class="form-control time-input">
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

            const voivodeeId = null;
            const cityId = null;
            let numberOfLoops = '{{$iterator}}';
            var tableArray = new Array();
            var lp = 1;


            newTable = $('.datatable');
            newTable.each(function() {

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
                        if(lp == 2){
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
                })
                )

            });

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
                    if(this.voivodeId != undefined && this.voivodeId != '' && this.cityId != undefined && this.timeArr != '' && this.timeArr.length > 0 && this.clientRouteId != '' && this.clientRouteId != undefined && this.hotelId != null && this.hotelId != '') {
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
                let formContainer = document.createElement('div');
                let input = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="form_data" value="' + cityInfoArray + '">';

                formContainer.innerHTML = '<form method="post" action="{{URL::to("/specificRoute")}}" id="formularz">' + input + '</form>';
                const placeToAppendForm = document.querySelector('.placeToAppendForm');
                placeToAppendForm.appendChild(formContainer);
                var formularz = $('#formularz');
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
                        console.log(response);
                    }
                });


            }

            const submitButton = document.querySelector('#submit-button');
            submitButton.addEventListener('click', submitHandler);

        })


    </script>
@endsection
