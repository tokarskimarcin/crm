{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view is responsible for adding new route to DataBase (DB table: "routes, routes_info"),--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: addNewRouteGet, addNewRoutePost--}}
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
            <div class="alert gray-nav ">Tworzenie Tras</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                   Utwórz nową trasę
                </div>
                <div class="panel-body">
                        @include('crmRoute.routes')
                    <div class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $('.form_date').datetimepicker({
            language:  'pl',
            autoclose: 1,
            minView : 2,
            pickTime: false
        });

        $(document).ready(function() {

            Element.prototype.appendAfter = function (element) {
                element.parentNode.insertBefore(this, element.nextSibling);
            },false;

            Element.prototype.appendBefore = function (element) {
                element.parentNode.insertBefore(this, element);
            },false;

            let iterator = 1; //This variable is helpful for adding id's
            let mainContainer = document.querySelector('.routes-wrapper'); //zaznaczamy główny container

            /**
             *Ta funkcja tworzy nowy show - tu jest napisany kod html całego formularza
             */
            function createNewShow(voivodes) {
                let numberOfShow = iterator;
                newElement = document.createElement('div');
                newElement.className = 'routes-container';
                let stringAppend = '        <div class="row">\n' +
                    '<div class="button_section button_section_gl_nr' + numberOfShow + '">' +
                    '<span class="glyphicon glyphicon-remove" data-remove="show"></span>' +
                    '</div>' +
                    '        <header>Pokaz </header>\n' +
                    '<div class=colmd-12 style="text-align: center">' +
                        '<span class="glyphicon glyphicon-refresh" data-refresh="refresh" style="font-size: 30px"></span>' +
                    '</div>' +
                    '\n' +
                    '            <div class="col-md-6">\n' +
                    '                <div class="form-group">\n' +
                    '                    <label>Województwo</label>\n' +
                    '                    <select class="form-control voivodeship" data-type="voivode">\n' +
                    '                        <option value="0">Wybierz</option>\n';
                                                for(let i = 0; i < voivodes.length ; i++){
                                                    stringAppend += '<option value ='+voivodes[i]['id']+'>'+voivodes[i]['name']+'</option>';
                                                }

                stringAppend +='     </select>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '\n' +
                    '            <div class="col-md-6">\n' +
                    '                <div class="form-group">\n' +
                    '                    <label for="city' + numberOfShow + '">Miasto</label>\n' +
                    '                    <select class="form-control city">\n' +
                    '                        <option value="0">Wybierz</option>\n' +
                    '                    </select>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '\n' +
                    '<div class="form-group hour_div">' +
                    '</div>' +
                    '            <div class="col-lg-12 button_section">\n' +
                    '                <input type="button" class="btn btn-success" id="save_route" value="Zapisz!" style="width:100%;margin-bottom:1em;font-size:1.1em;font-weight:bold;">\n' +
                    '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_show" value="Dodaj nowy pokaz" style="width:100%;margin-bottom:1em;font-size:1.1em;font-weight:bold;">' +
                    '            </div>\n' +
                    '        </div>';

                newElement.innerHTML =  stringAppend;
                return newElement;
            }


            /**
             * Ta funkcja dodaje nowy pokaz.
             */
            function addNewShow(ajaxResponse,type) {
                removeButtonsFromLastShow();
                if(type == 0){
                    var voievodes = @json($voivodes);
                    var newShow = createNewShow(voievodes); //otrzymujemy nowy formularz z pokazem.
                 }
                else{
                    var newShow = createNewShow(ajaxResponse); //otrzymujemy nowy formularz z pokazem.
                }
                mainContainer.appendChild(newShow);

                iterator++;

                $('.form_date').datetimepicker({
                    language:  'pl',
                    autoclose: 1,
                    minView : 2,
                    pickTime: false
                });
            }

            /*
            This function is responsible for removing buttons from last show container
             */
            function removeButtonsFromLastShow() {
                let buttonSection = document.getElementsByClassName('button_section')[document.getElementsByClassName('button_section').length - 1];
                if(buttonSection != null) {
                    buttonSection.parentNode.removeChild(buttonSection);
                }
            }

            /*
            This function removes "X" button from first show container
             */
            function removeGlyInFirstShow() {
                let firstShow = document.getElementsByClassName('routes-container')[0];
                let removeGlyphicon = firstShow.getElementsByClassName('glyphicon-remove')[0];
                let removeGlyphiconRefresh = firstShow.getElementsByClassName('glyphicon-refresh')[0];
                removeGlyphicon.parentNode.removeChild(removeGlyphicon);
                removeGlyphiconRefresh.parentNode.removeChild(removeGlyphiconRefresh);
            }

            /*
            This function is responsible for removing given show container
             */
            function removeGivenShow(container) {
                let allShows = document.getElementsByClassName('routes-container');
                let lastShowContainer = allShows[allShows.length - 1];
                if(container == lastShowContainer) {
                    addButtonsToPreviousContainer(container);
                    container.parentNode.removeChild(container);
                }
                else {
                    container.parentNode.removeChild(container);
                }
            }

            /*
            This function is responsible for attaching buttons to previous container
             */
            function addButtonsToPreviousContainer(container) {
                let previousContainer = container.previousElementSibling;
                let placeInPreviousContainer = previousContainer.getElementsByClassName('hour_div')[0];
                let buttonsElement = document.createElement('div');
                buttonsElement.classList.add('col-lg-12');
                buttonsElement.classList.add('button_section');
                buttonsElement.innerHTML = '                <input type="button" class="btn btn-success" id="save_route" value="Zapisz!" style="width:100%;margin-bottom:1em;">\n' +
                    '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_show" value="Dodaj nowy pokaz" style="width:100%;margin-bottom:1em;">';
                buttonsElement.appendAfter(placeInPreviousContainer);
            }

            /*
            This function creates form with input's data and sending it to server.
             */
            function saveRoute() {
                let voivodeElements = Array.from(document.getElementsByClassName('voivodeship'));
                let cityElements = Array.from(document.getElementsByClassName('city'));

                let voivodeArr = [];
                let cityArr = [];
                voivodeElements.forEach(function(element) {
                   voivodeArr.push(element.options[element.selectedIndex].value);
                });

                cityElements.forEach(function(element) {
                   cityArr.push(element.options[element.selectedIndex].value);
                });

                everythingIsGood = formValidation(voivodeArr, cityArr);

                if(everythingIsGood == true) {
                    let formContainer = document.createElement('div');
                    formContainer.innerHTML = '<form method="post" action="{{URL::to('/addNewRoute')}}" id="user_form"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" value="' + voivodeArr + '" name="voivode"><input type="hidden" value="' + cityArr + '" name="city"></form>';
                    let place = document.getElementsByClassName('routes-wrapper')[0];
                    place.appendChild(formContainer);
                    let userForm = document.getElementById('user_form');
                    userForm.submit();
                }
                else {
                    clearArrays(voivodeArr, cityArr);
                    swal('W każdym polu wartości muszą zostać wybrane!');
                }
            }

            /**
             * This function clear given arrays;
             */
            function clearArrays() {
                let args = arguments;
                for(var i = 0; i < args.length; i++) {
                    args[i] = [];
                }
            }
            /**
             * This function validate form
             */
            function formValidation() {
                let args = arguments;
                let flag = true;
                for(var i = 0; i < args.length; i++) {
                    args[i].forEach(function(value) {
                        if(value == '0') {
                            flag = false;
                        }
                    });
                }
                return flag;
            }

            /*
            This eventListener is responsible for clicking on add new show button, remove-glyphicon, and save_route
             */
            function buttonHandler(e) {
                if(e.target.id == 'add_new_show') { // click on add new show button
                    //Get last child of voievoidship
                    let AllVoievoidship = document.getElementsByClassName('voivodeship');
                    let countAllVoievoidship = AllVoievoidship.length;
                    //Get last child of City
                    let AllCitySelect = document.getElementsByClassName('city');
                    let countAllCitySelect = AllCitySelect.length;
                    let cityId = 0;
                    let voievodeshipId = 0;


                    // Walidacja wybrania
                    if(countAllVoievoidship != 0 && countAllCitySelect != 0){
                        voievodeshipId  = AllVoievoidship[countAllVoievoidship-1].value;
                        cityId = AllCitySelect[countAllCitySelect-1].value;
                    }
                    else{
                        voievodeshipId = AllVoievoidship[countAllVoievoidship].value;
                        cityId = AllCitySelect[countAllCitySelect].value;
                    }
                    $.ajax({
                            type: "POST",
                            url: '{{ route('api.getVoivodeshipRound') }}',
                            data: {
                                "voievodeshipId" : voievodeshipId,
                                "cityId"         : cityId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                addNewShow(response['voievodeInfo'],1);
                                $('.city').select2();
                                $('.voivodeship').select2(); //attach select2 look
                                $('.voivodeship').off('select2:select'); //remove previous event listeners
                                $('.voivodeship').on('select2:select', function (e) {
                                    let container = e.target.parentElement.parentElement.parentElement.parentElement;
                                    let headerId = e.params.data.id;
                                    let placeToAppend = container.getElementsByClassName('city')[0];
                                    placeToAppend.innerHTML = '';
                                    let basicOption = document.createElement('option');
                                    basicOption.value = '0';
                                    basicOption.textContent = 'Wybierz';
                                    placeToAppend.appendChild(basicOption);
                                    var cityInfo = response['cityInfo'][headerId];
                                    if(typeof cityInfo !== 'undefined'){
                                        //zmiana ręczna województwa
                                        for(var i = 0; i < cityInfo.length; i++) {
                                            if(cityInfo[i].id == headerId){
                                                console.log(cityInfo);
                                                let responseOption = document.createElement('option');
                                                responseOption.value = cityInfo[i].city_id;
                                                responseOption.textContent = cityInfo[i].city_name;
                                                placeToAppend.appendChild(responseOption);
                                            }
                                        }
                                    }else{
                                        $.ajax({
                                            type: "POST",
                                            url: '{{ route('api.getCitiesNames') }}',
                                            data: {
                                                "id": headerId
                                            },
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            success: function(response) {
                                                let placeToAppend = container.getElementsByClassName('city')[0];
                                                placeToAppend.innerHTML = '';
                                                let basicOption = document.createElement('option');
                                                basicOption.value = '0';
                                                basicOption.textContent = 'Wybierz';
                                                placeToAppend.appendChild(basicOption);
                                                for(var i = 0; i < response.length; i++) {
                                                    let responseOption = document.createElement('option');
                                                    responseOption.value = response[i].id;
                                                    responseOption.textContent = response[i].name;
                                                    placeToAppend.appendChild(responseOption);
                                                }

                                            }
                                        });
                                    }
                                });
                            }
                        });
                }
                else if(e.target.dataset.remove == 'show') { // click on X glyphicon
                    let showContainer = e.target.parentElement.parentElement.parentElement;
                    removeGivenShow(showContainer);
                }else if(e.target.dataset.refresh == 'refresh') { // click on refresh glyphicon
                    //get contener with select (actual and previous)
                    var actualContener = e.target.parentNode.parentNode;
                    var previousContener = e.target.parentNode.parentNode.parentNode.previousElementSibling;
                    var actualSelectCity = actualContener.getElementsByClassName('city')[0];
                    var actualSelectVoievode = actualContener.getElementsByClassName('voivodeship')[0];
                    var previousSelectCityVal = previousContener.getElementsByClassName('city')[0].value;
                    var previousSelectVoievodeVal = previousContener.getElementsByClassName('voivodeship')[0].value;

                    $.ajax({
                    type: "POST",
                    url: '{{ route('api.getVoivodeshipRound') }}',
                    data: {
                    "voievodeshipId": previousSelectVoievodeVal,
                    "cityId": previousSelectCityVal
                    },
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                        success: function (response) {
                            var stringAppend = '<option value=0>Wybierz</option>';
                            for (let i = 0; i < response['voievodeInfo'].length; i++) {
                                    stringAppend += '<option value =' + response['voievodeInfo'][i]['id'] + '>' + response['voievodeInfo'][i]['name'] + '</option>';
                            }
                            actualSelectVoievode.innerHTML = stringAppend;
                            stringAppend = '<option value=0>Wybierz</option>';
                            actualSelectCity.innerHTML = stringAppend;
                        }
                    });
                }
                else if(e.target.id == 'save_route') {
                    saveRoute();
                }

            }


            /***********************************************/

            mainContainer.addEventListener('click', buttonHandler);


            addNewShow(0,0);
            removeGlyInFirstShow();

            $('.city').select2();
            $('.voivodeship').select2();

            $('.voivodeship').on('select2:select', function (e) {
                let container = e.target.parentElement.parentElement.parentElement.parentElement;
                let headerId = e.params.data.id;
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.getCitiesNames') }}',
                    data: {
                        "id": headerId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let placeToAppend = container.getElementsByClassName('city')[0];
                        placeToAppend.innerHTML = '';
                        let basicOption = document.createElement('option');
                        basicOption.value = '0';
                        basicOption.textContent = 'Wybierz';
                        placeToAppend.appendChild(basicOption);
                        for(var i = 0; i < response.length; i++) {
                            let responseOption = document.createElement('option');
                            responseOption.value = response[i].id;
                            responseOption.textContent = response[i].name;
                            placeToAppend.appendChild(responseOption);
                        }
                    }
                });
            });
        });
    </script>
@endsection
