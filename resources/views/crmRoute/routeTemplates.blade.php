
@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <style>
        .singleDayContainer, .summaryButtonContainer {
            background-color: white;
            padding: 2em;
            box-shadow: 0 1px 15px 1px gray;
            border: 0;
            border-radius: .1875rem;
            margin: 1em;
        }

        .singleShowContainer {
            background-color: white;
            padding: 2em;
            box-shadow: 0 1px 15px 1px rgba(39, 39, 39, .1);
            border: 0;
            border-radius: .1875rem;
            margin: 1em;
            position: relative;
        }

        .singleShowHeader {
            text-align: center;
            font-size: 2em;
            font-weight: bold;
            padding-bottom: .5em;
        }

        .remove-button-container{
            position: absolute;
            top: 1em;
            right: 1em;
        }

        .glyphicon-remove {
            font-size: 2em;
            transition: all 0.8s ease-in-out;
            color: red;
        }

        .glyphicon-remove:hover {
            transform-origin: center;
            transform: scale(1.2) rotate(180deg);
            cursor: pointer;
        }
    </style>


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
@if(Session::has('adnotation'))
    <div class="alert alert-success">
        {{\Illuminate\Support\Facades\Session::get('adnotation')}}
    </div>
@endif
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                   Utwórz nową trasę
                </div>
                <div class="panel-body">
                    <div class="summaryButtonContainer">
                        <div class="row">
                            <div class="col-md-12">
                                <button id="addNewDay" class="btn btn-default" style="width: 100%; margin-bottom: 1em;"><span class="glyphicon glyphicon-plus"></span> Dodaj nowy dzień</button>
                            </div>
                            <div class="col-md-12">
                                <button id="save" class="btn btn-success" style="width: 100%;"><span class="glyphicon glyphicon-save"></span> Zapisz</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function() {

            //GLOBAL VARIABLES
                let panelBody = document.querySelector('.panel-body');
                let submitPlace = document.querySelector('.summaryButtonContainer');
            //END GLOBAL VARIABLES

            /**
             * This method append first day container and first show container
             */
            (function pageOpen() {
                let firstDay = new DayBox();
                firstDay.createDOMDayBox();
                let firstDayContainer = firstDay.getBox();
                panelBody.insertAdjacentElement("afterbegin", firstDayContainer);

                let firstForm = new ShowBox();
                firstForm.addRemoveShowButton();
                firstForm.addDistanceCheckbox();
                firstForm.addNewShowButton();
                firstForm.createDOMBox();
                let firstFormDOM = firstForm.getForm();

                firstDayContainer.appendChild(firstFormDOM);F
            })();

            /**
             * This method is used in shows appended between another ones
             */
            function showInTheMiddleAjax(previousCityDistance, previousCityId, nextCityDistance, nextCityId, citySelect, voivodeSelect, oldValuesArray = null) {
                //console.assert(citySelect.matches('.citySelect'), 'citySelect in showInTheMiddleAjax method is not city select');
                //console.assert(voivodeSelect.matches('.voivodeSelect'), 'voivodeSelect in showInTheMiddleAjax method is not voivode select');
                //console.assert((!isNaN(parseInt(nextCityId))) && (nextCityId != 0), 'nextCityId in showInTheMiddleAjax is not number!');
                //console.assert((!isNaN(parseInt(previousCityId))) && (previousCityId != 0), 'previousCityId in showInTheMiddleAjax is not number!');
                //console.assert((!isNaN(parseInt(previousCityDistance))) || (previousCityDistance == 'infinity'), 'previousCityId in showInTheMiddleAjax is not correct value!');
                //console.assert((!isNaN(parseInt(nextCityDistance))) || (nextCityDistance == 'infinity'), 'nextCityDistance in showInTheMiddleAjax is not correct value!');
                let firstResponse = null;
                let secondResponse = null;
                let intersectionArray = null;

                swal({
                    title: 'Ładowawnie...',
                    text: 'To może chwilę zająć',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    onOpen: () => {
                        swal.showLoading();
                        $.ajax({
                            type: "POST",
                            async: false,
                            url: '{{ route('api.getVoivodeshipRoundWithoutGracePeriod') }}',
                            data: {
                                'limit': previousCityDistance,
                                "cityId": previousCityId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                firstResponse = response;
                                //console.assert(typeof(firstResponse) === "object", "firstResponse in showInTheMiddleAjax is not object!");
                                $.ajax({
                                    type: "POST",
                                    async: false,
                                    url: '{{ route('api.getVoivodeshipRoundWithoutGracePeriod') }}',
                                    data: {
                                        'limit': nextCityDistance,
                                        "cityId": nextCityId
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (response2) {
                                        secondResponse = response2;
                                        voivodeSelect.innerHTML = '';
                                        citySelect.innerHTML = '';
                                        //console.assert(typeof(secondResponse) === "object", "secondResponse in showInTheMiddleAjax is not object!");
                                        intersectionArray = getIntersection(firstResponse, secondResponse);

                                        let voivodeSet = intersectionArray[0];
                                        let citySet = intersectionArray[1];
                                        appendBasicOption(voivodeSelect);

                                        voivodeSet.forEach(voivode => {
                                            appendVoivodeOptions(voivodeSelect, voivode);
                                        });

                                        if(oldValuesArray) { //this is optional
                                            //console.assert(Array.isArray(oldValuesArray), "oldVoivodeArr in showInExtreme method is not array!");
                                            appendBasicOption(citySelect);
                                            voivodeSet.forEach(voivode => {
                                                if(voivode.id == oldValuesArray[1]) {
                                                    citySet.forEach(voivodeCity => {
                                                        //console.assert(Array.isArray(voivodeCity), "voivodeCity in showInTheMiddleAjax method is not array!");
                                                        voivodeCity.forEach(city => {
                                                            if(city.id === voivode.id) {
                                                                appendCityOptions(citySelect, city);
                                                            }
                                                        });
                                                    });
                                                }

                                            });
                                            setOldValues(oldValuesArray[0], oldValuesArray[1], oldValuesArray[2], oldValuesArray[3]);
                                        }

                                        citySelect.setAttribute('data-distance', nextCityDistance);
                                        $(voivodeSelect).on('change', function() {
                                            citySelect.innerHTML = ''; //cleaning previous insertions
                                            appendBasicOption(citySelect);

                                            voivodeSet.forEach(voivode => {
                                                citySet.forEach(voivodeCity => {
                                                    //console.assert(Array.isArray(voivodeCity), "voivodeCity in showInTheMiddleAjax method is not array!");
                                                    voivodeCity.forEach(city => {
                                                        if(city.id === voivode.id) {
                                                            appendCityOptions(citySelect, city);
                                                        }
                                                    });
                                                });
                                            });
                                        });
                                    }
                                });

                            }
                        }).done((response) => {
                            swal.close();
                        });
                    }
                });

            }

            /**
             * This method is used in shows appended as first or last ones
             */
            function showInExtreme(limit, nextCityId, citySelect, voivodeSelect, oldVoivodeArr = null) {
                //console.assert(citySelect.matches('.citySelect'), 'citySelect in showInExtreme method is not city select');
                //console.assert(voivodeSelect.matches('.voivodeSelect'), 'voivodeSelect in showInExtreme method is not voivode select');
                //console.assert(!isNaN(parseInt(limit)), 'limit in showInExtreme is not number!');
                //console.assert((!isNaN(parseInt(nextCityId))) && (nextCityId != 0), 'nextCityId in showInExtreme is not number!');

                swal({
                    title: 'Ładowawnie...',
                    text: 'To może chwilę zająć',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    onOpen: () => {
                        swal.showLoading();
                        $.ajax({
                            type: "POST",
                            async: false,
                            url: '{{ route('api.getVoivodeshipRoundWithoutGracePeriod') }}',
                            data: {
                                'limit': limit,
                                "cityId": nextCityId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                let allVoivodes = response['voievodeInfo'];
                                //console.assert(Array.isArray(allVoivodes), "allVoivodes in showInExtreme method is not array!");
                                let allCitiesGroupedByVoivodes = response['cityInfo'];
                                //console.assert(typeof(allCitiesGroupedByVoivodes) === "object", "allCitiesGroupedByVoivodes in showInExtreme method is not object!");
                                allVoivodes.forEach(voivode => {
                                    appendVoivodeOptions(voivodeSelect, voivode)
                                });
                                citySelect.setAttribute('data-distance', limit); //applaying old value
                                if(oldVoivodeArr) { //this is optional
                                    appendBasicOption(citySelect);
                                    //console.assert(Array.isArray(oldVoivodeArr), "oldVoivodeArr in showInExtreme method is not array!");
                                    for(let Id in allCitiesGroupedByVoivodes) {
                                        if(oldVoivodeArr[1] == Id) {
                                            allCitiesGroupedByVoivodes[Id].forEach(city => {
                                                appendCityOptions(citySelect, city);
                                            });
                                        }
                                    }
                                    setOldValues(oldVoivodeArr[0], oldVoivodeArr[1], oldVoivodeArr[2], oldVoivodeArr[3]);
                                }

                                //After selecting voivode, this event listener appends cities from given range into city select
                                $(voivodeSelect).on('change', function(e) {
                                    citySelect.innerHTML = ''; //cleaning previous insertions
                                    appendBasicOption(citySelect);

                                    let voivodeId = e.target.value;
                                    for(let Id in allCitiesGroupedByVoivodes) {
                                        if(voivodeId == Id) {
                                            //console.assert(Array.isArray(allCitiesGroupedByVoivodes[Id]), "allCitiesGroupedByVoivodes in showInExtreme method is not array!");
                                            allCitiesGroupedByVoivodes[Id].forEach(city => {
                                                appendCityOptions(citySelect, city);
                                            });
                                        }
                                    }
                                });
                            }
                        }).done((response) => {
                            swal.close();
                        });
                    }
                });
            }

            /**
             * This method is used in shows without distance limit
             */
            function showWithoutDistanceAjax(voivodeId, citySelect) {
                //console.assert(!isNaN(parseInt(voivodeId)) && voivodeId != 0, 'voivodeId in showWithoutDistanceAjax is not number!');
                //console.assert(citySelect.matches('.citySelect'), 'citySelect in showWithoutDistanceAjax method is not city select');

                swal({
                    title: 'Ładowawnie...',
                    text: 'To może chwilę zająć',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    onOpen: () => {
                        swal.showLoading();
                        $.ajax({
                            type: "POST",
                            async: false,
                            url: '{{ route('api.allCitiesInGivenVoivodeAjax') }}',
                            data: {
                                "id": voivodeId
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                //console.assert(Array.isArray(response), "response from ajax in showWithoutDistanceAjax method is not array!");
                                let placeToAppend = citySelect;
                                placeToAppend.innerHTML = '';
                                appendBasicOption(placeToAppend);
                                for(let i = 0; i < response.length; i++) {
                                    let responseOption = document.createElement('option');
                                    responseOption.value = response[i].id;
                                    responseOption.textContent = response[i].name;
                                    placeToAppend.appendChild(responseOption);
                                }
                            }
                        }).done((response) => {
                            swal.close();
                        });
                    }
                });
            }

            function limitSelectsWhenBetweenSameDayContainer(grandNextShowContainer, thisSingleShowContainer, nextShowContainer, changeDistanceArr = null) {
                //console.assert(grandNextShowContainer.matches('.singleShowContainer'), 'grandNextShowContainer in limitSelectsWhenBetweenSameDayContainer is not single day container');
                //console.assert(thisSingleShowContainer.matches('.singleShowContainer'), 'thisSingleShowContainer in limitSelectsWhenBetweenSameDayContainer is not single day container');
                //console.assert(nextShowContainer.matches('.singleShowContainer'), 'nextShowContainer in limitSelectsWhenBetweenSameDayContainer is not single day container');
                const grandNextShowContainerCitySelect = grandNextShowContainer.querySelector('.citySelect');
                const grandNextShowContainerCityDistance = grandNextShowContainerCitySelect.dataset.distance;
                let grandNextShowContainerCityId = getSelectedValue(grandNextShowContainerCitySelect);

                const thisSingleShowContainerCitySelect = thisSingleShowContainer.querySelector('.citySelect');
                const thisSingleShowContainerCitySelectCityDistance = thisSingleShowContainerCitySelect.dataset.distance;
                let thisSingleShowContainerCityId = getSelectedValue(thisSingleShowContainerCitySelect);

                const nextShowContainerCitySelect = nextShowContainer.querySelector('.citySelect');
                let nextShowContainerCityid = getSelectedValue(nextShowContainerCitySelect);

                let nextShowContainerVoivodeSelect = nextShowContainer.querySelector('.voivodeSelect');
                let nextShowContainerVoivodeId = getSelectedValue(nextShowContainerVoivodeSelect);

                if((grandNextShowContainerCitySelect.length == 0 || grandNextShowContainerCityId == 0) ||
                    (thisSingleShowContainerCitySelect.length == 0 || thisSingleShowContainerCityId == 0) ||
                    (nextShowContainerCitySelect.length == 0  || nextShowContainerCityid == 0) ||
                    (nextShowContainerVoivodeSelect.length == 0 || nextShowContainerVoivodeId == 0) ||
                    (!thisSingleShowContainerCityId) || (!nextShowContainerCityid) || (!nextShowContainerVoivodeId) || (!grandNextShowContainerCityId)) {
                    notify("Wybierz miasta i województwa we wszystkich listach 5");
                    return false;
                }

                let oldValuesArray = [nextShowContainerVoivodeSelect, nextShowContainerVoivodeId, nextShowContainerCitySelect, nextShowContainerCityid];

                $(nextShowContainerVoivodeSelect).off();

                // nextShowContainerVoivodeSelect = nextShowContainer.querySelector('.voivodeSelect');
                nextShowContainerVoivodeSelect.innerHTML = '';
                nextShowContainerCitySelect.innerHTML = '';
                //console.log(grandNextShowContainerCitySelect);
                //console.log(thisSingleShowContainerCitySelect);

                if(changeDistanceArr) {
                    let helpArr = [];
                    if(changeDistanceArr[0] != 'undefined') {
                        helpArr.push(changeDistanceArr[0]);
                    }
                    else {
                        helpArr.push(grandNextShowContainerCityDistance);
                    }
                    if(changeDistanceArr[1] != 'undefined') {
                        helpArr.push(changeDistanceArr[1]);
                    }
                    else {
                        helpArr.push(thisSingleShowContainerCitySelectCityDistance);
                    }
                    //console.log(helpArr);
                    showInTheMiddleAjax(helpArr[0],grandNextShowContainerCityId,helpArr[1],thisSingleShowContainerCityId,nextShowContainerCitySelect,nextShowContainerVoivodeSelect, oldValuesArray);
                }
                else {
                    showInTheMiddleAjax(grandNextShowContainerCityDistance,grandNextShowContainerCityId,thisSingleShowContainerCitySelectCityDistance,thisSingleShowContainerCityId,nextShowContainerCitySelect,nextShowContainerVoivodeSelect, oldValuesArray);
                }
            }

            /**
             * This method handle refresh distance case when prev show is in the same day container and there is not previous container && case when next show is in the same day container and there is no next container
             */
            function limitSelectsWhenExtreme(previousShowContainer, nextShowContainerRelatedToPreviousShowContainer, limit) {
                let prevShowContainerVoivodeSelect = previousShowContainer.querySelector('.voivodeSelect');
                let prevShowVoivodeId = getSelectedValue(prevShowContainerVoivodeSelect);

                let prevShowContainerCitySelect = previousShowContainer.querySelector('.citySelect');
                let prevShowCityId = getSelectedValue(prevShowContainerCitySelect);

                let nextShowContainerRelatedToPreviousShowContainerCitySelect = nextShowContainerRelatedToPreviousShowContainer.querySelector('.citySelect');
                let nextShowContainerRelatedToPreviousShowContainerCityId = getSelectedValue(nextShowContainerRelatedToPreviousShowContainerCitySelect);

                let oldValuesArray = [prevShowContainerVoivodeSelect, prevShowVoivodeId, prevShowContainerCitySelect, prevShowCityId];

                if((prevShowContainerVoivodeSelect.length == 0 || prevShowVoivodeId == 0) ||
                    (prevShowContainerCitySelect.length == 0 || prevShowCityId == 0) ||
                    (nextShowContainerRelatedToPreviousShowContainerCitySelect.length == 0 || nextShowContainerRelatedToPreviousShowContainerCityId == 0) ||
                    (!prevShowCityId) || (!prevShowVoivodeId) || (!nextShowContainerRelatedToPreviousShowContainerCityId)) {
                    notify("Wybierz miasta i województwa we wszystkich listach 9");
                    return false;
                }

                $(prevShowContainerVoivodeSelect).off();

                prevShowContainerVoivodeSelect.innerHTML = '';
                appendBasicOption(prevShowContainerVoivodeSelect);
                prevShowContainerCitySelect.innerHTML = '';
                //console.log('limit: ', limit);

                showInExtreme(limit, nextShowContainerRelatedToPreviousShowContainerCityId, prevShowContainerCitySelect, prevShowContainerVoivodeSelect, oldValuesArray);
            }

            //This method is used when appending all voivodes and all cities
            function allCitiesAndAllVoivodes(nextShowContainer, defaults = null) {
                //all cities and all voivodes.
                let nextContVoivodeSelect = nextShowContainer.querySelector('.voivodeSelect');
                nextContVoivodeSelect.innerHTML = '';
                appendBasicOption(nextContVoivodeSelect);
                let nextContCitySelect = nextShowContainer.querySelector('.citySelect');
                $(nextContVoivodeSelect).off(); //remove all previous event listeners
                        @foreach($voivodes as $voivode)
                var singleVoivode = document.createElement('option');
                singleVoivode.value = {{$voivode->id}};
                singleVoivode.textContent = '{{$voivode->name}}';
                nextContVoivodeSelect.appendChild(singleVoivode);
                @endforeach()
                $(nextContVoivodeSelect).on('change', function(e) {
                    nextContCitySelect.setAttribute('data-distance', 'infinity');
                    let voivodeId = e.target.value;
                    showWithoutDistanceAjax(voivodeId, nextContCitySelect);
                });
                if(defaults) {
                    showWithoutDistanceAjax(defaults.voivode, nextContCitySelect);
                }
            }

            /**
             * This method appends basic option to voivode select
             */
            function appendBasicOption(element) {
                //console.assert(element.tagName === "SELECT", 'Element in appendBasicOption is not select element');
                let basicVoivodeOption = document.createElement('option');
                basicVoivodeOption.value = '0';
                basicVoivodeOption.textContent = 'Wybierz';
                element.appendChild(basicVoivodeOption);
            }

            /**
             * This method adjust day container numbers;
             */
            function adjustDayNumbers() {
                let allDayNotations = document.getElementsByClassName('day-info');
                for(let i = 0, max = allDayNotations.length; i < max; i++) {
                    allDayNotations[i].textContent = 'Dzień ' + (i+1);
                }
            }

            /**
             * This method appends options with voivode data
             */
            function appendVoivodeOptions(element, data) {
                //console.assert(element.matches('.voivodeSelect'), 'Element in appendVoivodeOptions method is not voivode select');
                let voivodeOption = document.createElement('option');
                voivodeOption.value = data.id;
                voivodeOption.textContent = data.name;
                element.appendChild(voivodeOption);
            }

            /**
             * This method appends options with city data
             */
            function appendCityOptions(element,data) {
                //console.assert(element.matches('.citySelect'), 'Element in appendCityOptions method is not city select');
                let cityOpt = document.createElement('option');
                cityOpt.value = data.city_id;
                cityOpt.textContent = data.city_name;
                element.appendChild(cityOpt);
            }

            /**
             * This function return intersection of 2 given sets of cities
             */
            function getIntersection(firstResponse, secondResponse) {
                let intersectionVoivodes = [];
                let intersectionCities = [];
                const firstVoivodeInfo = firstResponse['voievodeInfo'];
                const secondVoivodeInfo = secondResponse['voievodeInfo'];
                const firstCityInfo = firstResponse['cityInfo'];
                const secondCityInfo = secondResponse['cityInfo'];
                //console.assert(Array.isArray(firstVoivodeInfo), "firstVoivodeInfo in getIntersection method is not array!");
                //console.assert(Array.isArray(secondVoivodeInfo), "secondVoivodeInfo in getIntersection method is not array!");
                //console.assert(typeof(firstCityInfo) === "object", "firstCityInfo in getIntersection method is not object!");
                //console.assert(typeof(secondCityInfo) === "object", "secondCityInfo in getIntersection method is not object!");

                //linear looking for same voivodes
                firstVoivodeInfo.forEach(voivode => {
                   secondVoivodeInfo.forEach(voivode2 => {
                       if(voivode2.id === voivode.id) {
                           intersectionVoivodes.push(voivode);
                       }
                   })
                });

                intersectionVoivodes.forEach(voivode => {
                    let voivodeCityArr = [];
                    if(firstCityInfo[voivode.id] && secondCityInfo[voivode.id]) {
                        let firstCitySet = firstCityInfo[voivode.id];
                        let secondCitySet = secondCityInfo[voivode.id];
                        firstCitySet.forEach(city => {
                           secondCitySet.forEach(city2 => {
                              if(city.city_id === city2.city_id) {
                                  voivodeCityArr.push(city);
                              }
                           });
                        });
                    }
                    if(voivodeCityArr.length != 0) {
                        intersectionCities.push(voivodeCityArr);
                    }
                });

                let intersectionArray = [];
                intersectionArray.push(intersectionVoivodes);
                intersectionArray.push(intersectionCities);

                //console.assert(intersectionArray.length === 2, 'Problem with intersectionArray in getIntersection method');
                return intersectionArray;
            }

            /**
             * @param thisContainer
             * @param className
             * This function return array of prev and next containers if exist.
             */
            function checkingExistenceOfPrevAndNextContainers(thisContainer, className) {
                const allContainers = document.getElementsByClassName(className);
                let nextCont = null;
                let prevCont = null;
                let finalArray = [];
                //checking whether there is next and previous show container
                for(let i = 0; i < allContainers.length; i++) {
                    if(allContainers[i] == thisContainer) {
                        if(allContainers[i+1]) { //exist next container
                            nextCont = allContainers[i+1];
                        }
                        if(allContainers[i-1]) {
                            prevCont = allContainers[i-1];
                        }
                    }
                }
                finalArray.push(prevCont);
                finalArray.push(nextCont);
                return finalArray;
            }

            /**
             * This method check if in given contaiers there is checkbox checked or not
             * @param arrayOfContainers
             * @returns {Array} [undefined/true/false, undefined/true/false] - (undefined - no container given in arrayOfContainers, false - not checked, true - checked)
             */
            function checkboxFilter(arrayOfContainers) {
                //console.assert(Array.isArray(arrayOfContainers), "arrayOfContainers in checkboxFilter method is not array!");
                let prevCont = arrayOfContainers[0];
                let nextCont = arrayOfContainers[1];
                let isCheckedPrev = undefined;
                let isCheckedNext = undefined;
                let checkArr = [];
                if(prevCont) {
                    let checkboxPrevElement = prevCont.querySelector('.distance-checkbox');
                    isCheckedPrev = checkboxPrevElement.checked;
                }
                if(nextCont) {
                    let checkboxNextElement = nextCont.querySelector('.distance-checkbox');
                    isCheckedNext = checkboxNextElement.checked;
                }
                checkArr.push(isCheckedPrev);
                checkArr.push(isCheckedNext);

                return checkArr;
            }

            /**
             * This method validate all single day forms
             */
            function validateAllForms(element) {
                // //console.assert(element.matches('.singleShowContainer'), 'element in validateAllForms is not single show container');
                let flag = true;
                element.forEach(day => {
                    let validation = validateForm(day);
                    if(validation === false) {
                        flag = false;
                    }
                });

                return flag;
            }

            /**
             * This method returns selected by user from list item's value or null.
             */
            function getSelectedValue(element) {
                //console.assert(element.tagName === 'SELECT', 'Argument of getSelectedValue is not select element');
                if(element.options[element.selectedIndex]) {
                    return element.options[element.selectedIndex].value;
                }
                else {
                    return null;
                }
            }

            /**
             * This method sets old values for inputs.
             * @param voivodeSelect
             * @param voivodeId
             * @param citySelect
             * @param cityId
             */
            function setOldValues(voivodeSelect, voivodeId, citySelect, cityId) {
                //console.assert(voivodeSelect.matches('.voivodeSelect'), 'voivodeSelect in setOldValues method is not voivode select');
                //console.assert((!isNaN(parseInt(voivodeId))) && (voivodeId != 0), 'voivodeId in setOldValues is not number!');
                //console.assert(citySelect.matches('.citySelect'), 'citySelect in setOldValues method is not city select');
                //console.assert((!isNaN(parseInt(cityId))) && (cityId != 0), 'cityId in setOldValues is not number!');
                let voivodeFlag = true;
                let cityFlag = true;
                for(let i = 0; i < voivodeSelect.length; i++) {
                    if(voivodeSelect[i].value == voivodeId) {
                        voivodeSelect[i].selected = true;
                        voivodeFlag = false;
                    }
                }
                for(let j = 0; j < citySelect.length; j++) {
                    if(citySelect[j].value == cityId) {
                        citySelect[j].selected = true;
                        cityFlag = false;
                    }
                }
                if(voivodeFlag) {
                    $(voivodeSelect).val('0');
                    // //console.log('zmienilo na wartosc domyslna voivode');
                }
                if(cityFlag) {
                    $(citySelect).val('0');
                    // //console.log('zmienilo na wartosc domyslna city');
                }
            }

            /**
             * This function shows notification.
             */
            function notify(htmltext$string, type$string = 'info', delay$miliseconds$number = 5000) {
                $.notify({
                    // options
                    message: htmltext$string
                },{
                    // settings
                    type: type$string,
                    delay: delay$miliseconds$number,
                    animate: {
                        enter: 'animated fadeInRight',
                        exit: 'animated fadeOutRight'
                    }
                });
            }

            /**
             * This method validate form false - bad, true - good
             */
            function validateForm(element) {
                //console.assert(element.matches('.singleShowContainer'), 'element in validateForm is not singleShowContainer');
                let citySelect = element.querySelector('.citySelect');
                let cityValue = getSelectedValue(citySelect);
                return !(cityValue == 0);
            }

            /**
             * This constructor defines new show object
             * API:
             * let variable = new ShowBox(); - we create new show object,
             * variable.addNewShowButton() - indices that we want addNewShowButton(optional),
             * variable.addRemoveShowButton() - indices that we want removeShowButton(optional),
             * variable.addCheckboxFlag() - indices that we want checkbox(optional),
             * -----------------------------------------------------------------------
             * variable.createDOMBox() - we create new DOM element with no distance limit,
             * variable.createDOMBox(X, cityId) - we create new DOM element with distance limit of X(for example 30) relative to city(cityId),
             * variable.createDOMBox(x, cityId, true, previousShowContainer, nextShowContainer) -
             * we create new DOM element with distance limit between previousShowContainer's limit and
             * nextShowContainer's limit.
             * let DOMElement = variable.getForm(); - we obtain we obtain DOM representation of ShowBox
             * ------------------------------------------------------------------------
             */
            function ShowBox() {
                this.addNewShowButtonFlag = false; //indices whether add newShowButton
                this.addRemoveShowButtonFlag = false; //indices whether add removeShowButton
                this.addCheckboxFlag = false; //indices whether add refreshShowButton
                this.DOMBox = null; //here is stored DOM representation of ShowBox
                this.addNewShowButton = function() {
                    this.addNewShowButtonFlag = true;
                };
                this.addRemoveShowButton = function() {
                    this.addRemoveShowButtonFlag = true;
                };
                this.addDistanceCheckbox = function() {
                    this.addCheckboxFlag = true;
                };
                this.createDOMBox = function(distance = Infinity, selectedCity = null, intersetion = false, previousBox = null, nextBox = null) { //Creation of DOM form
                    let formBox = document.createElement('div'); //creation of main form container
                    formBox.classList.add('singleShowContainer');

                    /*REMOVE BUTTON PART*/
                    if(this.addRemoveShowButtonFlag) { //adding remove button.
                        //console.assert(this.addRemoveShowButtonFlag === true, 'addRemoveShowButtonFlag error');
                        let removeButtonContainer = document.createElement('div');
                        removeButtonContainer.classList.add('remove-button-container');
                        let removeButton = document.createElement('span');
                        removeButton.classList.add('glyphicon');
                        removeButton.classList.add('glyphicon-remove');
                        removeButton.classList.add('remove-button');
                        removeButtonContainer.appendChild(removeButton);
                        formBox.appendChild(removeButtonContainer);
                    }
                    /*END REMOVE BUTTON PART*/

                    /*HEADER PART*/
                    let headerRow = document.createElement('div');
                    headerRow.classList.add('row');

                    let headerCol = document.createElement('div');
                    headerCol.classList.add('col-md-12');

                    let header = document.createElement('div'); // creation of form title
                    header.classList.add('singleShowHeader');
                    header.textContent = 'Kampania';

                    headerCol.appendChild(header);
                    headerRow.appendChild(headerCol);
                    formBox.appendChild(headerRow);
                    /*END HEADER PART*/

                    /* CHECKBOX PART */
                    if(this.addCheckboxFlag) { //adding checkbox
                        //console.assert(this.addCheckboxFlag === true, 'addCheckboxFlag error');
                        let afterHeaderRow = document.createElement('div');
                        afterHeaderRow.classList.add('row');

                        let afterHeaderCol = document.createElement('div');
                        afterHeaderCol.classList.add('col-md-12');

                        let checkboxLabel = document.createElement('label');
                        checkboxLabel.textContent = 'Zdejmij ograniczenie';
                        checkboxLabel.style.display = 'inline-block';

                        let distanceCheckbox = document.createElement('input');
                        distanceCheckbox.setAttribute('type', 'checkbox');
                        distanceCheckbox.classList.add('distance-checkbox');
                        distanceCheckbox.style.display = 'inline-block';
                        distanceCheckbox.style.marginRight = '1em';

                        afterHeaderCol.appendChild(distanceCheckbox);
                        afterHeaderCol.appendChild(checkboxLabel);
                        afterHeaderRow.appendChild(afterHeaderCol);
                        formBox.appendChild(afterHeaderRow);
                    }
                    /*END CHECKBOX PART */

                    /*BODY PART*/
                    let formBodyRow = document.createElement('div');
                    formBodyRow.classList.add('row');

                    let formBodyColRightColumn = document.createElement('div');
                    formBodyColRightColumn.classList.add('col-md-6');

                    let formBodyRightColumnGroup = document.createElement('div');
                    formBodyRightColumnGroup.classList.add('form-group');

                    let secondSelectLabel = document.createElement('label');
                    secondSelectLabel.textContent = 'Miasto';

                    let secondSelect = document.createElement('select');
                    secondSelect.classList.add('citySelect');
                    secondSelect.classList.add('form-control');

                    let formBodyColLeftColumn = document.createElement('div');
                    formBodyColLeftColumn.classList.add('col-md-6');

                    let formBodyLeftColumnGroup = document.createElement('div');
                    formBodyLeftColumnGroup.classList.add('form-group');

                    let firstSelectLabel = document.createElement('label');
                    firstSelectLabel.textContent = 'Województwo';

                    let firstSelect = document.createElement('select');
                    firstSelect.classList.add('voivodeSelect');
                    firstSelect.classList.add('form-control');

                    appendBasicOption(firstSelect);

                    if(distance === Infinity && intersetion === false) { //every voivodeship and every city
                        @foreach($voivodes as $voivode)
                            var singleVoivode = document.createElement('option');
                            singleVoivode.value = {{$voivode->id}};
                            singleVoivode.textContent = '{{$voivode->name}}';
                            firstSelect.appendChild(singleVoivode);
                        @endforeach()

                        $(firstSelect).on('change', function(e) {
                            secondSelect.setAttribute('data-distance', 'infinity');
                            let voivodeId = e.target.value;
                            showWithoutDistanceAjax(voivodeId, secondSelect);
                        });
                    }
                    else if((distance === 100 || distance === 30) && intersetion === false) { // adding show in the end
                        showInExtreme(distance, selectedCity, secondSelect, firstSelect);
                    }
                    else if((distance === 100 || distance === 30) && intersetion === true) { // adding show between some shows
                        const previousCitySelect = previousBox.querySelector('.citySelect');
                        const previousCityDistance = previousCitySelect.dataset.distance;
                        const previousCityId = getSelectedValue(previousCitySelect);
                        const nextCitySelect = nextBox.querySelector('.citySelect');
                        const nextCityDistance = nextCitySelect.dataset.distance;
                        const nextCityId = getSelectedValue(nextCitySelect);

                        showInTheMiddleAjax(previousCityDistance,previousCityId,nextCityDistance,nextCityId,secondSelect,firstSelect);
                    }

                    formBodyLeftColumnGroup.appendChild(firstSelectLabel);
                    formBodyLeftColumnGroup.appendChild(firstSelect);
                    formBodyColLeftColumn.appendChild(formBodyLeftColumnGroup);
                    formBodyRow.appendChild(formBodyColLeftColumn);

                    appendBasicOption(secondSelect);
                    formBodyRightColumnGroup.appendChild(secondSelectLabel);
                    formBodyRightColumnGroup.appendChild(secondSelect);
                    formBodyColRightColumn.appendChild(formBodyRightColumnGroup);
                    formBodyRow.appendChild(formBodyColRightColumn);

                    formBox.appendChild(formBodyRow);
                    /*END BODY PART*/

                    /* ADD NEW SHOW BUTTON */
                        if(this.addNewShowButtonFlag) {
                            //console.assert(this.addNewShowButtonFlag === true, 'addNewShowButtonFlag error');
                            let buttonRow = document.createElement('div');
                            buttonRow.classList.add('row');

                            let buttonCol = document.createElement('div');
                            buttonCol.classList.add('col-md-12');

                            let addNewShowSpan = document.createElement('span');
                            $(addNewShowSpan).addClass('glyphicon glyphicon-collapse-down');

                            let addNewShowButton = document.createElement('button');
                            addNewShowButton.classList.add('btn');
                            addNewShowButton.classList.add('btn-info');
                            addNewShowButton.classList.add('addNewShowButton');
                            addNewShowButton.style.width = "100%";
                            addNewShowButton.appendChild(addNewShowSpan);
                            $(addNewShowButton).append(' Dodaj nowy pokaz');

                            buttonCol.appendChild(addNewShowButton);
                            buttonRow.appendChild(buttonCol);
                            formBox.appendChild(buttonRow);
                        }
                    /* END NEW SHOW BUTTON */

                    this.DOMBox = formBox;
                };
                this.getForm = function() {
                    return this.DOMBox;
                }
            }

            /**
             * This constructor defines day container object.
             * API:
             * let variable = new DayBox();  - we obtain new day element.
             * variable.createDOMDayBox(); - we create DOM representation of DayBox;
             * let DOMElement = variable.getBox(); - we obtain DOM representation of DayBox
             */
            function DayBox() {
                    this.dayBoxDOM = null;
                    this.createDOMDayBox = function() {
                        const allDayContainers = document.getElementsByClassName('singleDayContainer');
                        const numberOfAllDayContainers = allDayContainers.length;

                        let mainContainer = document.createElement('div');
                        mainContainer.classList.add('singleDayContainer');

                        let dayInfoContainer = document.createElement('div');
                        dayInfoContainer.classList.add('day-info');
                        dayInfoContainer.textContent = "Dzień: " + (numberOfAllDayContainers + 1);

                        mainContainer.appendChild(dayInfoContainer);
                        this.dayBoxDOM = mainContainer;
                    };
                    this.getBox = function() {
                        return this.dayBoxDOM;
                    }
            }

            /****************************************EVENT LISTENERS FUNCTIONS******************************************/

            /**
             * Global click handler
             */
            function globalClickHandler(e) {
                if(e.target.matches('.addNewShowButton')) { //user clicks on "add new show" button
                    e.preventDefault();
                    const newShowButton = e.target;
                    const thisShowContainer = newShowButton.closest('.singleShowContainer');
                    const allSingleShowContainers = document.getElementsByClassName('singleShowContainer');
                    const isChecked = thisShowContainer.querySelector('.distance-checkbox').checked;

                    const selectedCity = thisShowContainer.querySelector('.citySelect');
                    const selectedCityId = getSelectedValue(selectedCity);

                    let validation = validateForm(thisShowContainer);

                    if(validation) {
                        let newForm = new ShowBox();
                        newForm.addRemoveShowButton();
                        newForm.addDistanceCheckbox();
                        newForm.addNewShowButton();

                        let lastOneFlag = true;
                        let nextShowContainer = null;
                        //we are checking whether there is more single show containers
                        for(let i = 0; i < allSingleShowContainers.length; i++) {
                            if(allSingleShowContainers[i] === thisShowContainer) {
                                if(allSingleShowContainers[i+1]) {
                                    lastOneFlag = false;
                                    nextShowContainer = allSingleShowContainers[i+1];
                                }

                            }
                        }

                        if(isChecked) { //when clicked singleDayContainer has checkbox checked
                            newForm.createDOMBox();
                            let newFormDomElement = newForm.getForm();
                            thisShowContainer.insertAdjacentElement('afterend',newFormDomElement).scrollIntoView({behavior: "smooth"});
                        }
                        else {
                            //we are checking whether cliecked singleDayContainer is last one, or between others.
                            if(lastOneFlag === true) {
                                newForm.createDOMBox(30, selectedCityId);
                                let newFormDomElement = newForm.getForm();
                                thisShowContainer.insertAdjacentElement('afterend',newFormDomElement).scrollIntoView({behavior: "smooth"});
                            }
                            else { //container is not last one
                                const apreviousCitySelect = thisShowContainer.querySelector('.citySelect');
                                const anextCitySelect = nextShowContainer.querySelector('.citySelect');
                                //we are checking if user selected any city in upper and lower show container
                                if(anextCitySelect.options[anextCitySelect.selectedIndex].value != 0 && apreviousCitySelect.options[apreviousCitySelect.selectedIndex].value != 0) {
                                    apreviousCitySelect.dataset.distance = 30;
                                    newForm.createDOMBox(30, selectedCityId, true, thisShowContainer, nextShowContainer);
                                    let newFormDomElement = newForm.getForm();
                                    thisShowContainer.insertAdjacentElement('afterend',newFormDomElement).scrollIntoView({behavior: "smooth"});
                                }
                                else {
                                    notify('Wybierz miasta w pokazach powyżej i poniżej');
                                }
                            }
                        }


                    }
                    else { //validation failed
                        notify('Wybierz miasto');
                    }
                }
                else if(e.target.matches('.remove-button')) { // user clicks on "remove show" button
                    e.preventDefault();
                    const removeShowButton = e.target;
                    const showContainer = removeShowButton.closest('.singleShowContainer');
                    const dayContainer = removeShowButton.closest('.singleDayContainer');

                    let prevDayFlag = undefined; //true - another day, false - same day
                    let nextDayFlag = undefined;
                    let grandPrevDayFlag = undefined; //true - another day, false - same day
                    let grandNextDayFlag = undefined;
                    let nextShowFlag = undefined; //true - another day, false - same day
                    let prevShowFlag = undefined;

                    const showExistenceArray = checkingExistenceOfPrevAndNextContainers(showContainer, 'singleShowContainer');
                    let siblingsCheckboxArr = checkboxFilter(showExistenceArray);

                    if(showExistenceArray[0]) { //case when previous container exist
                        let prevShowContainer = showExistenceArray[0];
                        let dayContOfPrevShowContainer = prevShowContainer.closest('.singleDayContainer');
                        prevDayFlag = dayContainer == dayContOfPrevShowContainer ? false : true; //checking if next show is in the same day container

                        let prevShowExistenceArr = checkingExistenceOfPrevAndNextContainers(prevShowContainer, 'singleShowContainer');
                        let grandPrevCont = null;
                        if(prevShowExistenceArr[0]) {
                            grandPrevCont = prevShowExistenceArr[0];
                        }

                        if(grandPrevCont) { // grandprev container exist
                            let dayContOfGrandPrevShowContainer = grandPrevCont.closest('.singleDayContainer');
                            grandPrevDayFlag = dayContOfPrevShowContainer == dayContOfGrandPrevShowContainer ? false : true; //checking if next show is in the same day container
                        }

                        let nextShowContainer = null;

                        if(showExistenceArray[1]) {
                            nextShowContainer = showExistenceArray[1];
                        }

                        if(nextShowContainer) {
                            let dayContOfNextShowContainer = nextShowContainer.closest('.singleDayContainer');
                            nextShowFlag = dayContainer == dayContOfNextShowContainer ? false : true; //checking if next show is in the same day container
                        }

                        //main part
                        if(!siblingsCheckboxArr[0]) {
                            if(prevDayFlag) { //prev container is in previous day
                                if(grandPrevCont) { //grandprev container exist
                                    if(grandPrevDayFlag) { //grandprev is in grand previous day
                                        if(nextShowContainer) { //next container exist
                                            if(nextShowFlag) { //next container is in another day
                                                //console.log('prev exist & prev day, grandprev exist & grandprev is in grandprevday, nextshowcontaierExist & in another day');
                                                let changeDistanceArr = [100, 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevCont, nextShowContainer, prevShowContainer, changeDistanceArr);
                                            }
                                            else { //next container is in the same day
                                                //console.log('prev exist & prev day, grandprev exist & grandprev is in grandprevday, nextshowcontaierExist & in same day');
                                                let changeDistanceArr = [100, 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevCont, nextShowContainer, prevShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //next container doesn't exist
                                            //console.log('prev exist & prev day, grandprev exist & grandprev is in grandprevday, nextshowcontaier doesnt exist');
                                            limitSelectsWhenExtreme(prevShowContainer, grandPrevCont, 100);
                                        }

                                    }
                                    else { //grandprev is in previous day(same as previousShowContainer)
                                        if(nextShowContainer) { //next container exist
                                            if(nextShowFlag) { //next container is in another day
                                                //console.log('prev exist & prev day, grandprev exist & grandprev is same day as prev, nextshowcontaier Exist & in another day');
                                                let changeDistanceArr = ['undefined', 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevCont, nextShowContainer, prevShowContainer, changeDistanceArr);

                                            }
                                            else { //next container is in the same day
                                                //console.log('prev exist & prev day, grandprev exist & grandprev is same day as prev, nextshowcontaier Exist & in same day');
                                                let changeDistanceArr = ['undefined', 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevCont, nextShowContainer, prevShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //next container doesn't exist
                                            //console.log('prev exist & prev day, grandprev exist & grandprev is same day as prev, nextshowcontaier doesnt Exist');
                                            limitSelectsWhenExtreme(prevShowContainer, grandPrevCont, 30);
                                        }
                                    }
                                }
                                else { //grandprev doesn't exist
                                    if(nextShowContainer) { //next container exist
                                        if(nextShowFlag) { //next container is in another day
                                            //console.log('prev exist & prev day, grandprev doesnt exist, nextshowcontaier Exist & in another day');
                                            //nic nie robie, ponieważ akcja ma miejsce w tym przypadku w przypadku dla nastepnego.
                                        }
                                        else { //next container is in the same day
                                            //console.log('prev exist & prev day, grandprev doesnt exist, nextshowcontaier Exist & in same day');
                                            //nic nie robie, ponieważ akcja ma miejsce w tym przypadku w przypadku dla nastepnego.
                                        }
                                    }
                                    else { //next container doesn't exist
                                        //console.log('prev exist & prev day, grandprev doesnt exist, nextshowcontaier doesnt Exist');
                                        allCitiesAndAllVoivodes(prevShowContainer);
                                    }
                                }
                            }
                            else { //prev container is in the same day
                                if(grandPrevCont) { // grandprev container exist
                                    if(grandPrevDayFlag) { //grandprev is in grand previous day
                                        if(nextShowContainer) { //next container exist
                                            if(nextShowFlag) { //next container is in another day
                                                //console.log('prev exist & same day, grandprev exist & grandprev is in grandprevday, nextshowcontaier exist & in another day');
                                                let changeDistanceArr = [100, 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevCont, nextShowContainer, prevShowContainer, changeDistanceArr);
                                            }
                                            else { //next container is in the same day
                                                //console.log('prev exist & same day, grandprev exist & grandprev is in grandprevday, nextshowcontaier exist & in same day');
                                                let changeDistanceArr = [100, 'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevCont, nextShowContainer, prevShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //next container doesn't exist
                                            //console.log('prev exist & same day, grandprev exist & grandprev is in grandprevday, nextshowcontaier doesnt exist');
                                            limitSelectsWhenExtreme(prevShowContainer, grandPrevCont, 100);
                                        }
                                    }
                                    else { //grandprev is in previous day(same as previousShowContainer)(all containers are in same day container case)
                                        if(nextShowContainer) { //next container exist
                                            if(nextShowFlag) { //next container is in another day
                                                //console.log('prev exist & same day, grandprev exist & grandprev is prev day, nextshowcontaier Exist & in another day');
                                                let changeDistanceArr = ['undefined', 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevCont, nextShowContainer, prevShowContainer, changeDistanceArr);
                                            }
                                            else { //next container is in the same day
                                                //console.log('prev exist & same day, grandprev exist & grandprev is prev day, nextshowcontaier Exist & in same day');
                                                let changeDistanceArr = ['undefined', 'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevCont, nextShowContainer, prevShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //next container doesn't exist
                                            //console.log('prev exist & same day, grandprev exist & grandprev is prev day, nextshowcontaier doesnt exist');
                                            limitSelectsWhenExtreme(prevShowContainer, grandPrevCont, 30);
                                        }
                                    }
                                }
                                else { //grandprev container doesn't exist
                                    if(nextShowContainer) { //next container exist
                                        if(nextShowFlag) { //next container is in another day
                                            //console.log('grandprev doesnt exist, next exist and another day');
                                            ////nic nie robie, ponieważ akcja ma miejsce w tym przypadku w przypadku dla nastepnego.
                                        }
                                        else { //next container is in the same day
                                            //console.log('grandprev doesnt exist, next exist and same day');
                                            //nic nie robie, ponieważ akcja ma miejsce w tym przypadku w przypadku dla nastepnego.
                                        }
                                    }
                                    else { //next container doesn't exist
                                        //console.log('grandprev doesnt exist, next doesnt exist');
                                        let prevShowVoivodeSelect = prevShowContainer.querySelector('.voivodeSelect');
                                        let prevVoivode = getSelectedValue(prevShowVoivodeSelect);
                                        let prevShowCitySelect = prevShowContainer.querySelector('.citySelect');
                                        let prevCity = getSelectedValue(prevShowCitySelect);
                                        allCitiesAndAllVoivodes(prevShowContainer);
                                        setOldValues(prevShowVoivodeSelect, prevVoivode, prevShowCitySelect, prevCity);
                                    }
                                }
                            }
                        }
                    }

                    if(showExistenceArray[1]) { //case when next container exist
                        let nextShowContainer = showExistenceArray[1];
                        let dayContOfNextShowContainer = nextShowContainer.closest('.singleDayContainer');
                        nextDayFlag = dayContainer == dayContOfNextShowContainer ? false : true; //checking if next show is in the same day container

                        let nextShowExistenceArr = checkingExistenceOfPrevAndNextContainers(nextShowContainer, 'singleShowContainer');
                        let grandNextCont = null;
                        if(nextShowExistenceArr[1]) {
                            grandNextCont = nextShowExistenceArr[1];
                        }

                        if(grandNextCont) { // grandprev container exist
                            let dayContOfGrandNextShowContainer = grandNextCont.closest('.singleDayContainer');
                            grandNextDayFlag = dayContOfNextShowContainer == dayContOfGrandNextShowContainer ? false : true; //checking if next show is in the same day container
                        }

                        let prevShowContainer = null;

                        if(showExistenceArray[0]) {
                            prevShowContainer = showExistenceArray[0];
                        }

                        if(prevShowContainer) {
                            let dayContOfPrevShowContainer = prevShowContainer.closest('.singleDayContainer');
                            prevShowFlag = dayContainer == dayContOfPrevShowContainer ? false : true; //checking if next show is in the same day container
                        }

                        //main part
                        if(!siblingsCheckboxArr[1]) { //checkbox is not selected
                            if(nextDayFlag) { //prev container is in previous day
                                if(grandNextCont) { //grandprev container exist
                                    if(grandNextDayFlag) { //grandprev is in grand previous day
                                        if(prevShowContainer) { //next container exist
                                            if(prevShowFlag) { //next container is in another day
                                                //console.log('next exist & next day, grandnext exist & grandnext is in grandnextday, prevshowcontaierExist & in another day');
                                                let changeDistanceArr = [100, 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextCont, prevShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                            else { //prev container is in the same day
                                                //console.log('next exist & next day, grandnext exist & grandnext in grandnextday, prevshowcontaierExist & in same day');
                                                let changeDistanceArr = [100, 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextCont, prevShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //prev container doesn't exist
                                            //console.log('next exist & next day, grandnext exist & grandnext is in grandnextday, prevshowcontaier doesnt exist');
                                            limitSelectsWhenExtreme(nextShowContainer, grandNextCont, 100);
                                        }

                                    }
                                    else { //grandnext is in next day(same as nextShowContainer)
                                        if(prevShowContainer) { //prev container exist
                                            if(prevShowFlag) { //prev container is in another day
                                                //console.log('next exist & next day, grandnext exist & grandnext is same day as next, prevshowcontaier Exist & in another day');
                                                let changeDistanceArr = ['undefined', 100]; //[dalszy, blizszy]
                                                limitSelectsWhenBetweenSameDayContainer(grandNextCont, prevShowContainer, nextShowContainer, changeDistanceArr);

                                            }
                                            else { //prev container is in the same day
                                                //console.log('next exist & next day, grandnext exist & grandnext is same day as next, prevshowcontaier Exist & in same day');
                                                let changeDistanceArr = ['undefined', 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextCont, prevShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //prev container doesn't exist
                                            //console.log('next exist & next day, grandnext exist & grandnext is same day as prev, prevshowcontaier doesnt Exist');
                                            limitSelectsWhenExtreme(nextShowContainer, grandNextCont, 30);
                                        }
                                    }
                                }
                                else { //grandNext doesn't exist
                                    if(prevShowContainer) { //prev container exist
                                        if(prevShowFlag) { //prev container is in another day
                                            //console.log('next exist & next day, grandnext doesnt exist, prevshowcontaier Exist & in another day');
                                            limitSelectsWhenExtreme(nextShowContainer, prevShowContainer, 100);
                                        }
                                        else { //prev container is in the same day
                                            //console.log('next exist & next day, grandnext doesnt exist, prevshowcontaier Exist & in same day');
                                            limitSelectsWhenExtreme(nextShowContainer, prevShowContainer, 100);
                                        }
                                    }
                                    else { //prev container doesn't exist
                                        //console.log('next exist & next day, grandnext doesnt exist, prevshowcontaier doesnt Exist');
                                        allCitiesAndAllVoivodes(nextShowContainer);
                                    }
                                }
                            }
                            else { //next container is in the same day
                                if(grandNextCont) { // grandnext container exist
                                    if(grandNextDayFlag) { //grandnext is in grand next day
                                        if(prevShowContainer) { //prev container exist
                                            if(prevShowFlag) { //prev container is in another day
                                                //console.log('next exist & same day, grandnext exist & grandnext is in grandnextday, prevshowcontaier exist & in another day');
                                                let changeDistanceArr = [100, 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextCont, prevShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                            else { //prev container is in the same day
                                                //console.log('next exist & same day, grandnext exist & grandnext is in grandnextday, prevshowcontaier exist & in same day');
                                                let changeDistanceArr = [100, 'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextCont, prevShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //prev container doesn't exist
                                            //console.log('next exist & same day, grandnext exist & grandnext is in grandnextday, prevshowcontaier doesnt exist');
                                            limitSelectsWhenExtreme(nextShowContainer, grandNextCont, 100);
                                        }
                                    }
                                    else { //grandnext is in next day(same as nextShowContainer)(all containers are in same day container case)
                                        if(prevShowContainer) { //prev container exist
                                            if(prevShowFlag) { //prev container is in another day
                                                //console.log('next exist & same day, grandnext exist & grandnext is same day, prevshowcontaier Exist & in another day');
                                                let changeDistanceArr = ['undefined', 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextCont, prevShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                            else { //prev container is in the same day
                                                //console.log('next exist & same day, grandnext exist & grandnext is next day, prevshowcontaier Exist & in same day');
                                                let changeDistanceArr = ['undefined', 'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextCont, prevShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //prev container doesn't exist
                                            //console.log('next exist & same day, grandnext exist & grandnext is same day, prevshowcontaier doesnt exist');
                                            limitSelectsWhenExtreme(nextShowContainer, grandNextCont, 30);
                                        }
                                    }
                                }
                                else { //grandnext container doesn't exist
                                    if(prevShowContainer) { //prev container exist
                                        if(prevShowFlag) { //prev container is in another day
                                            //console.log('grandnext doesnt exist, next in same day, prev exist and another day');
                                            limitSelectsWhenExtreme(nextShowContainer, prevShowContainer, 100);
                                        }
                                        else { //next container is in the same day
                                            //console.log('grandnext doesnt exist, prev exist and same day');
                                            limitSelectsWhenExtreme(nextShowContainer, prevShowContainer, 30);
                                        }
                                    }
                                    else { //prev container doesn't exist
                                        // allCitiesAndAllVoivodes(nextShowContainer);
                                        let nextShowVoivodeSelect = nextShowContainer.querySelector('.voivodeSelect');
                                        let nextVoivode = getSelectedValue(nextShowVoivodeSelect);
                                        let nextShowCitySelect = nextShowContainer.querySelector('.citySelect');
                                        let nextCity = getSelectedValue(nextShowCitySelect);
                                        allCitiesAndAllVoivodes(nextShowContainer);
                                        setOldValues(nextShowVoivodeSelect, nextVoivode, nextShowCitySelect, nextCity);
                                    }
                                }
                            }
                        }
                    }

                    const allRemoveButtons = dayContainer.getElementsByClassName('remove-button');
                    //console.assert(allRemoveButtons, "Brak przycisków usuń");
                    if(allRemoveButtons.length > 1) { //delete only show box
                        showContainer.parentNode.removeChild(showContainer);
                    }
                    else if(allRemoveButtons.length === 1) { //delete day box
                        const allDayContainers = document.getElementsByClassName('singleDayContainer');
                        if(allDayContainers.length > 1) {
                            dayContainer.parentNode.removeChild(dayContainer);
                            adjustDayNumbers();
                        }
                        else {
                            notify('Nie można usunąć pierwszego dnia!');
                        }

                    }
                }
                else if(e.target.matches('#addNewDay')) { // user clicks on 'add new day' button
                    let firstDay = new DayBox();
                    firstDay.createDOMDayBox();
                    let firstDayContainer = firstDay.getBox();
                    const allDayContainers = document.getElementsByClassName('singleDayContainer');
                    const lastDayContainer = allDayContainers[allDayContainers.length - 1];
                    const allSingleShowContainers = document.getElementsByClassName('singleShowContainer');
                    const allSingleShowContainersInsideLastDayContainer = lastDayContainer.querySelectorAll('.singleShowContainer');
                    const lastShowContainerInsideLastDay = allSingleShowContainersInsideLastDayContainer[allSingleShowContainersInsideLastDayContainer.length - 1];
                    const isChecked = lastShowContainerInsideLastDay.querySelector('.distance-checkbox').checked;

                    let validate = validateForm(allSingleShowContainers[allSingleShowContainers.length - 1]);

                    if(validate) {
                        let firstForm = new ShowBox();
                        firstForm.addRemoveShowButton();
                        firstForm.addDistanceCheckbox();
                        firstForm.addNewShowButton();
                        lastDayContainer.insertAdjacentElement("afterend", firstDayContainer).scrollIntoView({behavior: "smooth"});
                        if(isChecked) { // case when last single show container has checked checkbox;
                            firstForm.createDOMBox();
                        }
                        else {

                            const allCitiesSelect = document.getElementsByClassName('citySelect');
                            const selectedCity = allCitiesSelect[allCitiesSelect.length - 1];
                            const selectedCityId = getSelectedValue(selectedCity);
                            firstForm.createDOMBox(100, selectedCityId);

                        }
                        let firstFormDOM = firstForm.getForm();
                        firstDayContainer.appendChild(firstFormDOM).scrollIntoView({behavior: "smooth"});
                    }
                    else {
                        notify('Uzupełnij miasto');
                    }

                }
                else if(e.target.matches('#save')) {
                    const allSingleShowContainers = document.querySelectorAll('.singleShowContainer');
                    const allSingleDayContainers = document.getElementsByClassName('singleDayContainer');
                    let finalArray = [];

                    let isOk = validateAllForms(allSingleShowContainers);

                    if(isOk) {
                        for(let i = 0; i < allSingleDayContainers.length; i++) {
                            let singleShowContainersInsideGivenDay = allSingleDayContainers[i].querySelectorAll('.singleShowContainer');
                            let dayNumber = i+1;

                            singleShowContainersInsideGivenDay.forEach(show => {
                                let voivodeSelect = show.querySelector('.voivodeSelect');
                                let voivodeId = getSelectedValue(voivodeSelect);

                                let citySelect = show.querySelector('.citySelect');
                                let cityId = getSelectedValue(citySelect);

                                let checkboxElement = show.querySelector('.distance-checkbox');
                                let checkboxVal = checkboxElement.checked ? 1 : 0;

                                let info = {
                                day: dayNumber,
                                voivode: voivodeId,
                                city: cityId,
                                checkbox: checkboxVal
                                }
                                finalArray.push(info);
                            });
                        }
                        let JSONData = JSON.stringify(finalArray);
                        let finalForm = document.createElement('form');
                        finalForm.setAttribute('method', 'post');
                        finalForm.setAttribute('action', "{{URL::to('/addNewRouteTemplate')}}");
                        finalForm.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="alldata" value=' + JSONData + '>';
                        submitPlace.appendChild(finalForm);
                        finalForm.submit();
                    }
                    else {
                        notify('Wybierz miasta we wszystkich polach');
                    }
                }
            }

                /**
                 * This event listener is responsible for change event on document
                 * @param e
                 */
            function globalChangeHandler(e) {
                if(e.target.matches('.distance-checkbox')) {
                    let isChecked = e.target.checked;
                    let previousSingleShowContainer = null;
                    let nextSingleShowContainer = null;
                    const thisSingleShowContainer = e.target.closest('.singleShowContainer');

                    let voivodeSelect = thisSingleShowContainer.querySelector('.voivodeSelect');
                    voivodeSelect.innerHTML = ''; //clear select
                    let citySelect = thisSingleShowContainer.querySelector('.citySelect');
                    citySelect.innerHTML = ''; //clear select

                    // //this part remove all event listeners from this node
                    // var old_element = voivodeSelect;
                    // var new_element = old_element.cloneNode(true);
                    // old_element.parentNode.replaceChild(new_element, old_element);
                    // //end remove all event listeners
                    $(voivodeSelect).off();

                    voivodeSelect = thisSingleShowContainer.querySelector('.voivodeSelect');

                    appendBasicOption(citySelect);
                    appendBasicOption(voivodeSelect);

                    if(isChecked) { // activate no distance limit option
                        let existenceArr = checkingExistenceOfPrevAndNextContainers(thisSingleShowContainer, 'singleShowContainer');

                        citySelect.setAttribute('data-previousdistance', citySelect.dataset.distance);
                        @foreach($voivodes as $voivode)
                            var singleVoivode = document.createElement('option');
                            singleVoivode.value = {{$voivode->id}};
                            singleVoivode.textContent = '{{$voivode->name}}';
                            voivodeSelect.appendChild(singleVoivode);
                        @endforeach()
                            citySelect.setAttribute('data-distance', 'infinity');
                            voivodeSelect.addEventListener('change', e => {
                                let voivodeId = e.target.value;
                                showWithoutDistanceAjax(voivodeId, citySelect);
                            });

                        if(existenceArr[0]) {
                            let prevVoivodeSelect = existenceArr[0].querySelector('.voivodeSelect');
                            let prevVoivodeId = getSelectedValue(prevVoivodeSelect);
                            let prevCitySelect = existenceArr[0].querySelector('.citySelect');
                            let prevCityId = getSelectedValue(prevCitySelect);
                            prevVoivodeSelect.innerHTML = '';
                            prevCitySelect.innerHTML = '';
                            let defaults = {voivode: prevVoivodeId};
                            allCitiesAndAllVoivodes(existenceArr[0], defaults);
                            setOldValues(prevVoivodeSelect, prevVoivodeId, prevCitySelect, prevCityId);
                        }
                        if(existenceArr[1]) {
                            let nextVoivodeSelect = existenceArr[1].querySelector('.voivodeSelect');
                            let nextVoivodeId = getSelectedValue(nextVoivodeSelect);
                            let nextCitySelect = existenceArr[1].querySelector('.citySelect');
                            let nextCityId = getSelectedValue(nextCitySelect);
                            nextVoivodeSelect.innerHTML = '';
                            nextCitySelect.innerHTML = '';
                            let defaults = {voivode: nextVoivodeId};
                            allCitiesAndAllVoivodes(existenceArr[1], defaults);
                            setOldValues(nextVoivodeSelect, nextVoivodeId, nextCitySelect, nextCityId);
                        }

                    }
                    else { //deactivate no distance limit option
                        const allSingleShowContainers = document.getElementsByClassName('singleShowContainer');
                        for(let i = 0; i < allSingleShowContainers.length; i++) {
                            if(thisSingleShowContainer == allSingleShowContainers[i]) {
                                if(allSingleShowContainers[i-1]) {
                                    previousSingleShowContainer = allSingleShowContainers[i-1];
                                }
                                if(allSingleShowContainers[i+1]) {
                                    nextSingleShowContainer = allSingleShowContainers[i+1];
                                }
                            }
                        }

                        if(previousSingleShowContainer === null && nextSingleShowContainer === null) { //there is only one show
                            @foreach($voivodes as $voivode)
                            var singleVoivode = document.createElement('option');
                            singleVoivode.value = {{$voivode->id}};
                            singleVoivode.textContent = '{{$voivode->name}}';
                            voivodeSelect.appendChild(singleVoivode); //password_date
                            @endforeach()

                            voivodeSelect.addEventListener('change', e => {
                                citySelect.setAttribute('data-distance', 'infinity');
                                let voivodeId = e.target.value;
                                showWithoutDistanceAjax(voivodeId, citySelect);
                            });
                        }
                        else if(previousSingleShowContainer !== null && nextSingleShowContainer === null) { //case when show is last one dziala
                            const previousCitySelect = previousSingleShowContainer.querySelector('.citySelect');
                            const previousCityId = getSelectedValue(previousCitySelect);
                            showInExtreme(citySelect.dataset.previousdistance, previousCityId, citySelect, voivodeSelect);
                        }
                        else if(previousSingleShowContainer === null && nextSingleShowContainer !== null) { //case when show is first one
                            const nextCitySelect = nextSingleShowContainer.querySelector('.citySelect');
                            const nextCityId = getSelectedValue(nextCitySelect);

                            showInExtreme(30, nextCityId, citySelect, voivodeSelect);
                        }
                        else if(previousSingleShowContainer !== null && nextSingleShowContainer !== null) { //case when show is in the middle
                            const previousCitySelect = previousSingleShowContainer.querySelector('.citySelect');
                            const previousCityDistance = previousCitySelect.dataset.distance;
                            const previousCityId = getSelectedValue(previousCitySelect);

                            const nextCitySelect = nextSingleShowContainer.querySelector('.citySelect');
                            const nextCityDistance = nextCitySelect.dataset.distance;
                            const nextCityId = getSelectedValue(nextCitySelect);

                            showInTheMiddleAjax(previousCityDistance, previousCityId, nextCityDistance, nextCityId, citySelect, voivodeSelect);
                        }

                    }
                }
                else if(e.target.matches('.citySelect')) { // user changes city
                    const thisSingleShowContainer = e.target.closest('.singleShowContainer');
                    const thisDayContainer = e.target.closest('.singleDayContainer');
                    const thisContainerCheckbox = thisSingleShowContainer.querySelector('.distance-checkbox');
                    const isCheckedThisContainer = thisContainerCheckbox.checked;

                    let previousShowContainer = undefined;
                    let nextShowContainer = undefined;

                    let nextDayFlag = null; //indices that next show container is in the same day container (false - same day, true - another day)
                    let prevDayFlag = null; //indices that prev show container is in the same day container (false - same day, true - another day)
                    let grandPrevDayFlag = null; //indices that grandPrev show container is in the same day container (false - same day, true - another day)
                    let grandNextDayFlag = null; //indices that grandNext show container is in the same day container (false - same day, true - another day)

                    let siblingShowContainersArr = [];
                    let siblingCheckboxArr = [];

                    siblingShowContainersArr = checkingExistenceOfPrevAndNextContainers(thisSingleShowContainer, 'singleShowContainer');
                    siblingCheckboxArr = checkboxFilter(siblingShowContainersArr);

                    previousShowContainer = siblingShowContainersArr[0] === null ? null : siblingShowContainersArr[0];
                    nextShowContainer = siblingShowContainersArr[1] === null ? null : siblingShowContainersArr[1];

                    if(!isCheckedThisContainer) { // if this container doesn't have checkbox checked
                        if(nextShowContainer) { //case when next show exist.
                            if(siblingCheckboxArr[1] === false) { //next show container doesn't have checked distance checkbox
                                let dayContainerOfNextShowContainer = nextShowContainer.closest('.singleDayContainer');
                                nextDayFlag = dayContainerOfNextShowContainer == thisDayContainer ? false : true; //checking if next show is in the same day container
                                let grandNextShowContainer = undefined; // previous show container of previous show container
                                let prevShowContainerRelatedToNextShowContainer = thisSingleShowContainer;
                                let siblingsOfNextShowContainerArr = checkingExistenceOfPrevAndNextContainers(nextShowContainer, 'singleShowContainer');
                                grandNextShowContainer = siblingsOfNextShowContainerArr[1] === null ? null : siblingsOfNextShowContainerArr[1];
                                let nextSiblingCheckboxArr = checkboxFilter(siblingsOfNextShowContainerArr);

                                if(nextDayFlag) { //case when next show is in another day container
                                    if(grandNextShowContainer) { // there is prev container and next container (related to next show container)
                                        let dayContainerOfGrandNextShowContainer = grandNextShowContainer.closest('.singleDayContainer');
                                        grandNextDayFlag = dayContainerOfGrandNextShowContainer == dayContainerOfNextShowContainer ? false : true; //checking if grandnext show is in the same day container as next show
                                        if(grandNextDayFlag) { //case when grand show is another day
                                            if(!nextSiblingCheckboxArr[1]) { //grand is not checked
                                                let changeDistanceArr = [100,'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextShowContainer, thisSingleShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                            else { //grand is checked
                                                let changeDistanceArr = ['infinity','undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextShowContainer, thisSingleShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //grand is same day
                                            if(!nextSiblingCheckboxArr[1]) { //grand is not checked
                                                let changeDistanceArr = [30,'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextShowContainer, thisSingleShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                            else { //grand is checked
                                                let changeDistanceArr = ['infinity','undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextShowContainer, thisSingleShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                        }
                                    }
                                    else { // there is no next container (related to prev show container)

                                        let changeDistanceArr = [100,100];
                                        limitSelectsWhenBetweenSameDayContainer(nextShowContainer, thisSingleShowContainer, nextShowContainer, changeDistanceArr);
                                        // limitSelectsWhenExtreme(nextShowContainer, prevShowContainerRelatedToNextShowContainer, 100);
                                    }
                                }
                                else { //case when next show is in the same day container
                                    if(grandNextShowContainer) { // there is prev container and next container (related to next show container)
                                        let dayContainerOfGrandNextShowContainer = grandNextShowContainer.closest('.singleDayContainer');
                                        grandNextDayFlag = dayContainerOfGrandNextShowContainer == dayContainerOfNextShowContainer ? false : true; //checking if grandnext show is in the same day container as next show
                                        if(grandNextDayFlag) { //grandnext show is in another day container related to next show
                                            if(!nextSiblingCheckboxArr[1]) { //grand is not checked
                                                let changeDistanceArr = [100, 'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextShowContainer, thisSingleShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                            else {
                                                let changeDistanceArr = ['infinity', 'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextShowContainer, thisSingleShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //grandnext show is in the same day container as next show
                                            if(!nextSiblingCheckboxArr[1]) { //grand is not checked
                                                limitSelectsWhenBetweenSameDayContainer(grandNextShowContainer, thisSingleShowContainer, nextShowContainer);
                                            }
                                            else {
                                                let changeDistanceArr = ['infinity', 'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandNextShowContainer, thisSingleShowContainer, nextShowContainer, changeDistanceArr);
                                            }
                                        }
                                    }
                                    else { // there is no next container (related to next show container)
                                        let changeDistanceArr = [30,30];
                                        limitSelectsWhenBetweenSameDayContainer(nextShowContainer, thisSingleShowContainer, nextShowContainer, changeDistanceArr);
                                        // limitSelectsWhenExtreme(nextShowContainer, prevShowContainerRelatedToNextShowContainer, 30);
                                    }
                                }
                            }
                        }

                        //case when prev show exist.
                        if(previousShowContainer) {
                            if(siblingCheckboxArr[0] === false) { //prev show container doesn't have checked distance checkbox
                                let dayContainerOfPreviousShowContainer = previousShowContainer.closest('.singleDayContainer');
                                prevDayFlag = dayContainerOfPreviousShowContainer == thisDayContainer ? false : true; //checking if prev show is in the same day container
                                let nextShowContainerRelatedToPreviousShowContainer = thisSingleShowContainer;
                                let siblingsOfPreviousShowContainerArr = checkingExistenceOfPrevAndNextContainers(previousShowContainer, 'singleShowContainer');
                                let grandPrevShowContainer = undefined; // previous show container of previous show container
                                let prevSiblingCheckboxArr = checkboxFilter(siblingsOfPreviousShowContainerArr);
                                grandPrevShowContainer = siblingsOfPreviousShowContainerArr[0] === null ? null : siblingsOfPreviousShowContainerArr[0];

                                if(prevDayFlag) { //case when prev show is in another day container
                                    if(grandPrevShowContainer) { // there is previous container and next container (related to prev show container)
                                        let dayContainerOfGrandPrev = grandPrevShowContainer.closest('.singleDayContainer');
                                        grandNextDayFlag = dayContainerOfGrandPrev === dayContainerOfPreviousShowContainer ? false : true;
                                        let changeDistanceArr = [];
                                        if(grandNextDayFlag) {// case when grand prev show is another day related to prev show
                                            if(!prevSiblingCheckboxArr[0]) { //grand is not checked
                                                changeDistanceArr = [100, 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevShowContainer, thisSingleShowContainer, previousShowContainer, changeDistanceArr);
                                            }
                                            else {
                                                changeDistanceArr = ['infinity', 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevShowContainer, thisSingleShowContainer, previousShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //case when grand prev show is in same day container as prev show
                                            if(!prevSiblingCheckboxArr[0]) { //grand is not checked
                                                changeDistanceArr = [30, 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevShowContainer, thisSingleShowContainer, previousShowContainer, changeDistanceArr);
                                            }
                                            else {
                                                changeDistanceArr = ['infinity', 100];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevShowContainer, thisSingleShowContainer, previousShowContainer, changeDistanceArr);
                                            }
                                        }
                                    }
                                    else { // there is no previous container (related to prev show container)
                                        let changeDistanceArr = [100, 100];
                                        limitSelectsWhenBetweenSameDayContainer(previousShowContainer, thisSingleShowContainer, previousShowContainer, changeDistanceArr);

                                        // limitSelectsWhenExtreme(previousShowContainer, nextShowContainerRelatedToPreviousShowContainer, 100);
                                    }

                                }
                                else { //case when prev show is in the same day container
                                    if(grandPrevShowContainer) { // there is previous container and next container (related to prev show container)
                                        let dayContainerOfGrandPrevShowContainer = grandPrevShowContainer.closest('.singleDayContainer');
                                        grandPrevDayFlag = dayContainerOfGrandPrevShowContainer == dayContainerOfPreviousShowContainer ? false : true; //checking if grandprev show is in the same day container as prev show
                                        if(grandPrevDayFlag) { //grandprev show is in another day container related to prev show
                                            if(!prevSiblingCheckboxArr[0]) { //grand is not checked
                                                let changeDistanceArr = [100, 'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevShowContainer, thisSingleShowContainer, previousShowContainer, changeDistanceArr);
                                            }
                                            else {
                                                let changeDistanceArr = ['infinity', 'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevShowContainer, thisSingleShowContainer, previousShowContainer, changeDistanceArr);
                                            }
                                        }
                                        else { //grandprev show is in the same day container as prev show
                                            if(!prevSiblingCheckboxArr[0]) { //grand is not checked
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevShowContainer, thisSingleShowContainer, previousShowContainer);
                                            }
                                            else {
                                                let changeDistanceArr = ['infinity', 'undefined'];
                                                limitSelectsWhenBetweenSameDayContainer(grandPrevShowContainer, thisSingleShowContainer, previousShowContainer, changeDistanceArr);
                                            }
                                        }
                                    }
                                    else { // there is no previous container (related to prev show container)
                                        let changeDistanceArr = [30, 30];
                                        limitSelectsWhenBetweenSameDayContainer(previousShowContainer, thisSingleShowContainer, previousShowContainer, changeDistanceArr);

                                        // limitSelectsWhenExtreme(previousShowContainer, nextShowContainerRelatedToPreviousShowContainer, 30);
                                    }
                                }
                            }
                        }
                    }


                }
            }

            document.addEventListener('click', globalClickHandler);
            document.addEventListener('change', globalChangeHandler);

        });

    </script>
@endsection
