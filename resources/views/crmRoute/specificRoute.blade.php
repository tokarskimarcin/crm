@extends('layouts.main')
@section('style')
    <link rel="stylesheet" href="{{asset('/css/fixedHeader.dataTables.min.css')}}">
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

        .client-container, .client-info-container {
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
            max-width: 100%;

            line-height: 2em;

        }

        header {
            text-align: center;
            font-size: 2em;
            font-weight: bold;
        }

        .route-info-bar {
            font-size: 1.3em;
            font-weight: bold;
        }

        .check{
            background: #B0BED9 !important;
        }

        .invisible {
            display:none;
        }

    </style>

    <div class="client-wrapper">
        @if(isset($pageInfo) AND !empty($pageInfo))
        <div class="client-container">
            <header>Przypisywanie szczegółowych informacji do tras klienta {{$pageInfo->clientName}}</header>
        </div>
        <div class="client-info-container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="route-info-bar">Tydzień: {{$pageInfo->week}}</div>
                </div>
                <div class="col-lg-4">
                    <div class="route-info-bar">Data pierwszego pokazu: {{$pageInfo->date}} </div>
                </div>
                <div class="col-lg-6">
                    <div class="route-info-bar">Nazwa trasy: {{$pageInfo->routeName}}</div>
                </div>
            </div>
        </div>
            @endif
    </div>
    @php
        $lp = 1;
    @endphp
    <div class="client-wrapper">
        <div class="client-container">
            @foreach($routeInfo as $campaign)
                @php
                    $i = 1;
                @endphp
                <div class="client-container campaign-container">
                        <input type="hidden" value="{{$campaign[0]->id}}" class="campaignDirstClientRouteInfoId">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="campaign_lp" >Kampania #{{$lp++}}</h3>
                        </div>
                    </div>
                    <div class="row" style="text-align: center">
                        <div class="col-md-6">
                            <h4 class="voivode_info" >Województwo: {{$campaign[0]->voivode_name}}</h4>
                            <h2 class="city_info" >Miasto: {{$campaign[0]->city_name}}</h2>
                        </div>
                        <div class="col-md-6">
                            <h2 class="date_info" >{{$campaign[0]->date}}</h2>
                        </div>
                    </div>
                    <label>Wybierz hotel:</label>
                    <table id="datatable" class="thead-inverse table table-striped row-border datatable hover" data-typ="datatable" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nazwa</th>
                            <th>Wojewodztwo</th>
                            <th>Miasto</th>
                            <th>Ulica</th>
                            <th>Kod Pocztowy</th>
                            <th>Sugerowany Tel</th>
                            <th>Wybierz</th>
                            <th>Podgląd</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-lg-6">
                            <label for="user_reservation" style="margin-right: 10px;">Osoba Rezerwująca: </label>
                            <input id="user_reservation" name="user_reservation" type="text" class="form-control price-input" @if(!empty($campaign[0]->user_reservation)) value="{{$campaign[0]->user_reservation}}" @else value="Brak"@endif>

                        </div>
                        <div class="col-lg-2">
                            <label for="hotel-price" style="margin-right: 10px;">Cena za hotel: </label>
                            <input id="hotel-price" name="hotel-price" type="text" class="form-control hotel-price-input" @if(!empty($campaign[0]->hotel_price)) value="{{$campaign[0]->hotel_price}}" @else value="0"@endif>
                        </div>
                        <div class="col-lg-4">
                            <label for="comment" style="margin-right: 10px;">Uwaga do raportu : </label>
                            <input id="comment" name="comment" type="text" class="form-control comment-input" @if(!empty($campaign[0]->comment_for_report)) value="{{$campaign[0]->comment_for_report}}" @else value="Brak" @endif required>
                        </div>
                    </div>

                    @foreach($campaign as $showHour)
                        <div class="form-group">
                            <label>Godzina pokazu nr. @php echo $i; @endphp</label>
                            <input type="time" class="form-control time-input" data-clientrouteinfoid="{{$showHour->id}}" @if(isset($showHour->hour)) value="{{$showHour->hour}}" @endif>
                        </div>
                        {{--@if(!$loop->last)
                            <div style="width: 100%; height:2px; border-top:3px dashed black; margin-top:1em; margin-bottom:1em;"></div>
                        @endif--}}
                        @php
                            $i++;
                        @endphp

                    @endforeach
                </div>
            @endforeach

        </div>

        <div class="client-container placeToAppendForm">
            <button class="btn btn-primary" style="margin-top:1em;margin-bottom:1em;font-size:1.1em;font-weight:bold;" id="redirect"><span class='glyphicon glyphicon-repeat'></span> Powrót</button>
            <button id="submit-button" class="btn btn-success" type="button" style="font-weight:bold;"><span class='glyphicon glyphicon-save'></span> Zapisz</button>
        </div>
    </div>


    <!-- Modal -->
    <div id="hotelInfo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Informacje o hotelu</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel-name">Nazwa hotelu</label>
                                <input class="form-control" id="hotel-name" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel-voivode">Województwo</label>
                                <input class="form-control" id="hotel-voivode" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel-city">Miasto</label>
                                <input class="form-control" id="hotel-city" type="text" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hotel-street">Ulica</label>
                                <input class="form-control" id="hotel-street" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel-paid">Forma płatności</label>
                                <input class="form-control" id="hotel-paid" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hotel-parking">Parking</label>
                                <input class="form-control" id="hotel-parking" type="text" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="hotel-comment">Uwagi</label>
                                    <textarea class="form-control" id="hotel-comment" cols="15" rows="2" disabled></textarea>
                                    {{--<input class="form-control" id="hotel-comment" type="text" disabled>--}}
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6" >
                            <label for="telephones">Telefony</label>
                            <ul class="list-group" id="telephones">

                            </ul>
                        </div>
                        <div class="col-md-6" >
                            <label for="emails">E-maile</label>
                            <ul class="list-group" id="emails">

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        let hotelInfoArr =[];
        let campaignInfoArray = [];

        $(document).ready(function (){
            @foreach($routeInfo as $campaign)
                @if(!empty($campaign[0]->hotel_id))
                    var hotelObj = {
                            campaign_first_client_route_info_id: '{{$campaign[0]->id}}',
                            hotel_id: '{{$campaign[0]->hotel_id}}',
                            city_name: '{{$campaign[0]->city_name}}',
                            voivode_name: '{{$campaign[0]->voivode_name}}',
                            hotel_page: '{{$campaign[0]->hotel_page}}'
                        };
                    hotelInfoArr.push(hotelObj);
                @else
                    var hotelObj = {
                            campaign_first_client_route_info_id: '{{$campaign[0]->id}}',
                            hotel_id: null,
                            city_name: '{{$campaign[0]->city_name}}',
                            voivode_name: '{{$campaign[0]->voivode_name}}',
                            hotel_page: '{{$campaign[0]->hotel_page}}'
                        };
                    hotelInfoArr.push(hotelObj);
                @endif
            @endforeach

            let newTable = $('.datatable');
            newTable.each(function(key, value) {
                let searchName = '';
                if(hotelInfoArr[key].hotel_id === null){
                    searchName = hotelInfoArr[key].city_name +' '+ hotelInfoArr[key].voivode_name;
                }
                $(this).DataTable({
                        "autoWidth": true,
                        "processing": true,
                        "serverSide": true,
                        "oSearch": {"sSearch": searchName},
                        displayStart: hotelInfoArr[key].hotel_page*10,
                        "drawCallback": function( settings ) {
                        },
                        "rowCallback": function( row, data, index ) {
                            $(row).attr('id', "hotelId_" + data.id);
                            $(row).data('hotel_id', data.id);
                            if(data.id == hotelInfoArr[key].hotel_id) {
                                $(row).find('.checkbox_info').prop('checked', true);
                            }
                            return row;
                        },"fnDrawCallback": function(settings) {
                                $(this).find('tr').off('click');

                                $(this).find('tr').on('click', function(e) {
                                    if(!e.target.matches('.button-preview-hotel')) { //not highlighting row when click on loop glyphicon
                                        let datatables = $('.datatable');
                                        let test = $(this).closest('table');
                                        if($(this).hasClass('check')) {
                                            $(this).removeClass('check');
                                            $(this).find('.checkbox_info').prop('checked',false);
                                            hotelInfoArr[key].hotel_id = null;
                                        }
                                        else {
                                            test.find('tr.check').removeClass('check');
                                            $.each(test.find('.checkbox_info'), function (item, val) {
                                                $(val).prop('checked', false);
                                            });
                                            $(this).addClass('check');
                                            $(this).find('.checkbox_info').prop('checked', true);
                                            hotelInfoArr[key].hotel_id = $(this).data('hotel_id');
                                        }
                                    }
                                });
                            checkedInput = $(this).closest('table').find('input[type="checkbox"]:checked');
                            closestTr = checkedInput.closest('tr');
                            closestTr.addClass('check');
                        }, "ajax": {
                            'url': "{{ route('api.showHotelsAjax') }}",
                            'type': 'POST',
                            'data': function (d) {
                                d.status = [1]
                            },
                            'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                        },
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                        },"columns":[
                            {"data":
                                    'id',"orderable": false, visible: false
                            },
                            {"data":function (data, type, dataToSet) {
                                console.log(data);
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
                            {"data": function(data, type, dataToSet) {
                                    return data.street;
                                },"name":"street", "orderable": false
                            },
                            {"data": function(data, type, dataToSet) {
                                    return data.zip_code;
                                },"name":"zip_code", "orderable": false, "width": "10%"
                            },
                            {"data": function(data, type, dataToSet) {
                                let tel = null;
                                if(data.contact != null) {
                                    tel = data.contact;
                                }
                                else {
                                    tel = 'Brak';
                                }
                                    return tel;
                                },"name":"telephone", "orderable": false, "width": "10%"
                            },
                            {"data":function (data, type, dataToSet) {
                                    /* var cityId = cityElementOfGivenContainer;
                                     let newarray = new Array();
                                     if(helpFlag == 0) {
                                         for(var i = 0; i<hotelInfoArr.length;i++){
                                             if(hotelInfoArr[i].city_id == cityId){
                                                 if(data.id == hotelInfoArr[i].hotel_id) {
                                                     for(var j = 0; j<hotelInfoArr.length;j++){
                                                         if(i != j){
                                                             newarray.push(hotelInfoArr[j]);
                                                         }
                                                     }
                                                     hotelInfoArr = newarray;
                                                     helpFlag++;
                                                     return '<input class="checkbox_info" type="checkbox" value="' + data.id + '" style="display:inline-block;" checked>';
                                                 }

                                             }
                                         }
                                     }*/
                                    return '<input class="checkbox_info btn-block" type="checkbox" value="' + data.id + '" style="display:inline-block;">';
                                },"orderable": false, "searchable": false, width:'10%'
                            },
                        {
                            "data": function (data, type, dataToSet) {
                                // console.log(data);
                                let spanButton = $(document.createElement('span')).addClass('glyphicon glyphicon-search');
                                let previewButton = $(document.createElement('button')).addClass('button-preview-hotel btn btn-default btn-block').attr('data-id', data.id).append(spanButton);
                                return previewButton.prop('outerHTML');
                            },'name': 'info'
                        }
                        ]
                    })
            });

            /**
             * This function fill inputs in modal with info about hotel
             * @param data
             */
            function fillHotelModal(data) {
                console.assert(Array.isArray(data), 'Parameter data is not array in fillHotelModal function');
                console.log(data);
                const generalHotelInfo = data[0];
                const contactHotelInfo = data[1];
                let hotelNameInput = document.querySelector('#hotel-name');
                let hotelVoivodeInput = document.querySelector('#hotel-voivode');
                let hotelCityInput = document.querySelector('#hotel-city');
                let hotelStreetInput = document.querySelector('#hotel-street');
                let hotelPaymentInput = document.querySelector('#hotel-paid');
                let hotelCommentInput = document.querySelector('#hotel-comment');
                let hotelParkingInput = document.querySelector('#hotel-parking');

                let telephonesBox = document.querySelector('#telephones');
                let emailsBox = document.querySelector('#emails');

                if(generalHotelInfo.hasOwnProperty('hotel_name')) {
                    hotelNameInput.value = generalHotelInfo.hotel_name;
                }
                else {
                    hotelNameInput.value = 'Brak danych';
                }

                if(generalHotelInfo.hasOwnProperty('voivode_name')) {
                    hotelVoivodeInput.value = generalHotelInfo.voivode_name;
                }
                else {
                    hotelVoivodeInput.value = 'Brak danych';
                }

                if(generalHotelInfo.hasOwnProperty('city_name')) {
                    hotelCityInput.value = generalHotelInfo.city_name;
                }
                else {
                    hotelCityInput.value = 'Brak danych';
                }

                if(generalHotelInfo.hasOwnProperty('street')) {
                    hotelStreetInput.value = generalHotelInfo.street;
                }
                else {
                    hotelStreetInput.value = 'Brak danych';
                }

                if(generalHotelInfo.hasOwnProperty('payment_method_id')) {
                    if(generalHotelInfo.payment_method_id == 1) {
                        hotelPaymentInput.value = 'Gotówka';
                    }
                    else {
                        hotelPaymentInput.value = 'Przelew';
                    }

                }
                else {
                    hotelPaymentInput.value = "Brak danych";
                }

                if(generalHotelInfo.hasOwnProperty('comment')) {
                    hotelCommentInput.value = generalHotelInfo.comment;
                }
                else {
                    hotelCommentInput.value = 'Brak danych';
                }

                if(generalHotelInfo.hasOwnProperty('parking')) {
                    hotelParkingInput.value = generalHotelInfo.parking == 1 ? 'Tak' : 'Nie';
                }
                else {
                    hotelParkingInput.value = 'Brak danych';
                }

                //**********Contact Part************

                contactHotelInfo.forEach(hotelInfo => {
                    let listElement = document.createElement('li');
                    listElement.classList.add('list-group-item');
                    let interiorElement = document.createElement('a');
                    interiorElement.textContent = hotelInfo.contact;

                    if(hotelInfo.type == 'mail') {
                        interiorElement.setAttribute('href', 'mailto:' + hotelInfo.contact);
                        if(hotelInfo.suggested == 1) {
                            interiorElement.textContent += ' - sugerowany';
                        }
                        listElement.appendChild(interiorElement);
                        emailsBox.appendChild(listElement);
                    }
                    else if(hotelInfo.type == 'phone') {
                        interiorElement.setAttribute('href', 'tel:' + hotelInfo.contact);
                        if(hotelInfo.suggested == 1) {
                            interiorElement.textContent += ' - sugerowany';
                        }
                        listElement.appendChild(interiorElement);
                        telephonesBox.appendChild(listElement);
                    }

                });
            }

            //this function collect data from forms and validate it.
            function submitHandler(e) {
                campaignInfoArray = [];
                let isValid = true;
                const campaignContainers = document.querySelectorAll('.campaign-container'); // node list of containers(logic: every city data)

                campaignContainers.forEach(campaignContainer => {
                    const timeHotelArr = createTimeHotelArrayOfObjects(campaignContainer);

                    const userReservationInput = campaignContainer.querySelector('#user_reservation');
                    const userReservationVal = userReservationInput.value;
                    const hotelPriceInput = campaignContainer.querySelector('#hotel-price');
                    const hotelPriceVal = hotelPriceInput.value;
                    const commentInput = campaignContainer.querySelector('.comment-input');
                    const commentVal = commentInput.value;

                    const campaignObject = new CampaignObject(campaignContainer.querySelectorAll('.campaignDirstClientRouteInfoId')[0].value, timeHotelArr, userReservationVal, hotelPriceVal, commentVal);

                    if(isValid === false) {
                        swal('Wypełnij wszystkie pola');
                    }
                    else {
                        campaignObject.pushJSON();
                    }

                });
                if(isValid === true) {
                    $(e.target).prop('disabled',true);
                    submitForm(e);
                }
            }

            function submitForm(e) {
                let JSONData = JSON.stringify(campaignInfoArray);
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.updateClientRouteInfoHotelsAndHours') }}',
                    data: {
                        "JSONData": JSONData
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response==='success'){
                            sessionStorage.setItem('addnotation', 'Zmiany zostały zapisane!');
                            window.location.href = `{{URL::to('/showClientRoutes')}}`;
                        }else{
                            console.log(response);
                            swal('Błąd!','Wewnętrzny błąd serwera. Spróbuj ponownie później','error');
                            $(e.target).prop('disabled',false);
                        }
                    }
                });
            }

            function createTimeHotelArrayOfObjects(campaignContainer) {
                let hotelId = null;
                const campaignFirstClientRouteInfoId = campaignContainer.querySelectorAll('.campaignDirstClientRouteInfoId')[0].value;
                hotelInfoArr.forEach(function (campaignHotelInfo, index) {
                    if(campaignHotelInfo.campaign_first_client_route_info_id === campaignFirstClientRouteInfoId && campaignHotelInfo.hotel_id !== '0'){
                        hotelId = campaignHotelInfo.hotel_id;
                    }
                });
                const allTimes = campaignContainer.querySelectorAll('.time-input');
                let timeHotelArr = [];
                for(let i = 0; i < allTimes.length; i++) {
                    const time = allTimes[i].value;
                    const timeHotelObject = {
                        clientRouteInfoId: $(allTimes[i]).data('clientrouteinfoid'),
                        hotelId: hotelId,
                        time: time
                    };
                    timeHotelArr.push(timeHotelObject);
                }
                return timeHotelArr;

            }

            function redirectHandler(e) {
                location.href="{{URL::to('/showClientRoutes')}}";
            }

            const redirectButton = document.querySelector('#redirect');
            redirectButton.addEventListener('click', redirectHandler);

            const submitButton = document.querySelector('#submit-button');
            submitButton.addEventListener('click', submitHandler);

            //This object will store every info about given campaign.
            function CampaignObject(campaignFirstClientRouteInfoId, timeHotelArr, userReservation, hotelPrice, commentVal) {
                this.campaignFirstClientRouteInfoId = campaignFirstClientRouteInfoId;
                this.timeHotelArr = timeHotelArr;
                this.userReservation = userReservation;
                this.hotelPrice = hotelPrice;
                this.commentVal = commentVal;
                this.pushJSON = function() {
                    let obj = {
                        campaignFirstClientRouteInfoId: this.campaignFirstClientRouteInfoId,
                        timeHotelArr: this.timeHotelArr,
                        userReservation: this.userReservation,
                        hotelPrice: this.hotelPrice,
                        comment: this.commentVal
                    };
                    campaignInfoArray.push(obj);

                }
            }

            function globalClickHandler(e) {
                if(e.target.matches('.button-preview-hotel')) {

                    let clickedButton = e.target;
                    let hotelId = clickedButton.dataset.id;

                    const ourHeaders = new Headers();
                    ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                    let data = new FormData();
                    data.append('hotelId', hotelId);

                    swal({
                        title: 'Ładowanie...',
                        text: 'To może chwilę zająć',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        onOpen: () => {
                            swal.showLoading();
                            fetch('{{route('api.hotelConfirmationHotelInfoAjax')}}', {
                                method: 'post',
                                headers: ourHeaders,
                                credentials: "same-origin",
                                body: data
                            })
                                .then(resp => resp.json())
                                .then(resp => {
                                    console.log(resp);
                                    return fillHotelModal(resp);
                                })
                                .then(resp => {
                                    swal.close();
                                    $("#hotelInfo").modal("show");
                                })
                                .catch(err => {console.log(err)})
                        }
                    });
                }
            }

            document.addEventListener('click', globalClickHandler);
        });
    </script>
@endsection
