
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
                                <button id="addNewDay" class="btn btn-default" style="width: 100%; margin-bottom: 1em;">Dodaj nowy dzień</button>
                            </div>
                            <div class="col-md-12">
                                <button id="save" class="btn btn-success" style="width: 100%;">Zapisz</button>
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

        document.addEventListener('DOMContentLoaded', function(event) {

            //GLOBAL VARIABLES
                let panelBody = document.querySelector('.panel-body');
            //END GLOBAL VARIABLES

            /**
             * This method is used in shows appended between another ones
             */
            function showInTheMiddleAjax(previousCityDistance, previousCityId, nextCityDistance, nextCityId, citySelect, voivodeSelect) {
                console.assert(citySelect.matches('.citySelect'), 'citySelect in showInTheMiddleAjax method is not city select');
                console.assert(voivodeSelect.matches('.voivodeSelect'), 'voivodeSelect in showInTheMiddleAjax method is not voivode select');
                console.assert((!isNaN(parseInt(nextCityId))) && (nextCityId != 0), 'nextCityId in showInTheMiddleAjax is not number!');
                console.assert((!isNaN(parseInt(previousCityId))) && (previousCityId != 0), 'previousCityId in showInTheMiddleAjax is not number!');
                console.assert((!isNaN(parseInt(previousCityDistance))) || (previousCityDistance == 'infinity'), 'previousCityId in showInTheMiddleAjax is not correct value!');
                console.assert((!isNaN(parseInt(nextCityDistance))) || (nextCityDistance == 'infinity'), 'nextCityDistance in showInTheMiddleAjax is not correct value!');
                let firstResponse = null;
                let secondResponse = null;
                let intersectionArray = null;

                $.ajax({
                    type: "POST",
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
                        console.assert(typeof(firstResponse) === "object", "firstResponse in showInTheMiddleAjax is not object!");
                        $.ajax({
                            type: "POST",
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
                                console.assert(typeof(secondResponse) === "object", "secondResponse in showInTheMiddleAjax is not object!");
                                intersectionArray = getIntersection(firstResponse, secondResponse);

                                let voivodeSet = intersectionArray[0];
                                let citySet = intersectionArray[1];

                                voivodeSet.forEach(voivode => {
                                    appendVoivodeOptions(voivodeSelect, voivode);
                                });

                                citySelect.setAttribute('data-distance', 30);
                                //After selecting voivode, this event listener appends cities from given range into city select
                                voivodeSelect.addEventListener('change', e => {

                                    citySelect.innerHTML = ''; //cleaning previous insertions
                                    appendBasicOption(citySelect);

                                    voivodeSet.forEach(voivode => {
                                        citySet.forEach(voivodeCity => {
                                            console.assert(Array.isArray(voivodeCity), "voivodeCity in showInTheMiddleAjax method is not array!");
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
                });
            }

            /**
             * This method is used in shows appended as first or last ones
             */
            function showInExtreme(limit, nextCityId, citySelect, voivodeSelect) {
                console.assert(citySelect.matches('.citySelect'), 'citySelect in showInExtreme method is not city select');
                console.assert(voivodeSelect.matches('.voivodeSelect'), 'voivodeSelect in showInExtreme method is not voivode select');
                console.assert(!isNaN(parseInt(limit)), 'limit in showInExtreme is not number!');
                console.assert((!isNaN(parseInt(nextCityId))) && (nextCityId != 0), 'nextCityId in showInExtreme is not number!');
                $.ajax({
                    type: "POST",
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
                        console.assert(Array.isArray(allVoivodes), "allVoivodes in showInExtreme method is not array!");
                        let allCitiesGroupedByVoivodes = response['cityInfo'];
                        console.assert(typeof(allCitiesGroupedByVoivodes) === "object", "allCitiesGroupedByVoivodes in showInExtreme method is not object!");

                        allVoivodes.forEach(voivode => {
                            appendVoivodeOptions(voivodeSelect, voivode)
                        });
                        citySelect.setAttribute('data-distance', limit); //applaying old value

                        //After selecting voivode, this event listener appends cities from given range into city select
                        voivodeSelect.addEventListener('change', e => {
                            citySelect.innerHTML = ''; //cleaning previous insertions
                            appendBasicOption(citySelect);

                            let voivodeId = e.target.value;
                            for(Id in allCitiesGroupedByVoivodes) {
                                if(voivodeId == Id) {
                                    allCitiesGroupedByVoivodes[Id].forEach(city => {
                                        appendCityOptions(citySelect, city);
                                    });
                                }
                            }
                        });
                    }
                });
            }

            /**
             * This method is used in shows without distance limit
             */
            function showWithoutDistanceAjax(voivodeId, citySelect) {
                console.assert(!isNaN(parseInt(voivodeId)) && voivodeId != 0, 'voivodeId in showWithoutDistanceAjax is not number!');
                console.assert(citySelect.matches('.citySelect'), 'citySelect in showWithoutDistanceAjax method is not city select');
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.allCitiesInGivenVoivodeAjax') }}',
                    data: {
                        "id": voivodeId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.assert(Array.isArray(response), "response from ajax in showWithoutDistanceAjax method is not array!");
                        let placeToAppend = citySelect;
                        placeToAppend.innerHTML = '';
                        appendBasicOption(placeToAppend);
                        for(var i = 0; i < response.length; i++) {
                            let responseOption = document.createElement('option');
                            responseOption.value = response[i].id;
                            responseOption.textContent = response[i].name;
                            placeToAppend.appendChild(responseOption);
                        }

                    }
                });
            }

            /**
             * This method appends basic option to voivode select
             */
            function appendBasicOption(element) {
                console.assert(element.tagName === "SELECT", 'Element in appendBasicOption is not select element');
                let basicVoivodeOption = document.createElement('option');
                basicVoivodeOption.value = '0';
                basicVoivodeOption.textContent = 'Wybierz';
                element.appendChild(basicVoivodeOption);
            }

            /**
             * This method appends options with voivode data
             */
            function appendVoivodeOptions(element, data) {
                console.assert(element.matches('.voivodeSelect'), 'Element in appendVoivodeOptions method is not voivode select');
                let voivodeOption = document.createElement('option');
                voivodeOption.value = data.id;
                voivodeOption.textContent = data.name;
                element.appendChild(voivodeOption);
            }

            /**
             * This method appends options with city data
             */
            function appendCityOptions(element,data) {
                console.assert(element.matches('.citySelect'), 'Element in appendCityOptions method is not city select');
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
                console.assert(Array.isArray(firstVoivodeInfo), "firstVoivodeInfo in getIntersection method is not array!");
                console.assert(Array.isArray(secondVoivodeInfo), "secondVoivodeInfo in getIntersection method is not array!");
                console.assert(typeof(firstCityInfo) === "object", "firstCityInfo in getIntersection method is not object!");
                console.assert(typeof(secondCityInfo) === "object", "secondCityInfo in getIntersection method is not object!");

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

                console.assert(intersectionArray.length === 2, 'Problem with intersectionArray in getIntersection method');
                return intersectionArray;
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
             * This method validate form
             */
            function validateForm(element) {
                console.assert(element.matches('.singleShowContainer'), 'element in validateForm is not singleShowContainer');
                let citySelect = element.querySelector('.citySelect');
                let cityValue = citySelect.options[citySelect.selectedIndex].value;
                if(cityValue == 0) {
                    return false;
                }
                else {
                    return true;
                }
            }

            /**
             * This constructor defines new show object
             * API:
             * let variable = new showBox(); - we create new show object,
             * variable.addNewShowButton() - indices that we want addNewShowButton(optional),
             * variable.addRemoveShowButton() - indices that we want removeShowButton(optional),
             * variable.addCheckboxFlag() - indices that we want checkbox(optional),
             * -----------------------------------------------------------------------
             * variable.createDOMBox() - we create new DOM element with no distance limit,
             * variable.createDOMBox(X, cityId) - we create new DOM element with distance limit of X(for example 30) relative to city(cityId),
             * variable.createDOMBox(x, cityId, true, previousShowContainer, nextShowContainer) -
             * we create new DOM element with distance limit between previousShowContainer's limit and
             * nextShowContainer's limit.
             * let DOMElement = variable.getForm(); - we obtain we obtain DOM representation of showBox
             * ------------------------------------------------------------------------
             */
            function showBox() {
                this.addNewShowButtonFlag = false; //indices whether add newShowButton
                this.addRemoveShowButtonFlag = false; //indices whether add removeShowButton
                this.addCheckboxFlag = false; //indices whether add refreshShowButton
                this.DOMBox = null; //here is stored DOM representation of showBox
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
                        console.assert(this.addRemoveShowButtonFlag === true, 'addRemoveShowButtonFlag error');
                        let removeButtonContainer = document.createElement('div');
                        removeButtonContainer.classList.add('remove-button-container');
                        let removeButton = document.createElement('span');
                        removeButton.classList.add('glyphicon');
                        removeButton.classList.add('glyphicon-remove');
                        removeButton.classList.add('remove-button');
                        removeButtonContainer.appendChild(removeButton);
                        formBox.appendChild(removeButtonContainer);
                    };
                    /*END REMOVE BUTTON PART*/

                    /*HEADER PART*/
                    let headerRow = document.createElement('div');
                    headerRow.classList.add('row');

                    let headerCol = document.createElement('div');
                    headerCol.classList.add('col-md-12');

                    let header = document.createElement('div'); // creation of form title
                    header.classList.add('singleShowHeader');
                    header.textContent = 'Pokaz';

                    headerCol.appendChild(header);
                    headerRow.appendChild(headerCol);
                    formBox.appendChild(headerRow);
                    /*END HEADER PART*/

                    /* CHECKBOX PART */
                    if(this.addCheckboxFlag) { //adding checkbox
                        console.assert(this.addCheckboxFlag === true, 'addCheckboxFlag error');
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
                    };
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

                    // appendBasicOption(secondSelect);


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

                        firstSelect.addEventListener('change', e => {
                            secondSelect.setAttribute('data-distance', 'infinity');
                            let voivodeId = e.target.value;
                            showWithoutDistanceAjax(voivodeId, secondSelect);
                        });
                    }
                    else if((distance === 100 || distance === 30) && intersetion === false) {
                        showInExtreme(distance, selectedCity, secondSelect, firstSelect);
                    }
                    else if((distance === 100 || distance === 30) && intersetion === true) { // adding show between some shows
                        const previousCitySelect = previousBox.querySelector('.citySelect');
                        const previousCityDistance = previousCitySelect.dataset.distance;
                        const previousCityId = previousCitySelect.options[previousCitySelect.selectedIndex].value;
                        const nextCitySelect = nextBox.querySelector('.citySelect');
                        const nextCityDistance = nextCitySelect.dataset.distance;
                        const nextCityId = nextCitySelect.options[nextCitySelect.selectedIndex].value;

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
                            console.assert(this.addNewShowButtonFlag === true, 'addNewShowButtonFlag error');
                            let buttonRow = document.createElement('div');
                            buttonRow.classList.add('row');

                            let buttonCol = document.createElement('div');
                            buttonCol.classList.add('col-md-12');

                            let addNewShowButton = document.createElement('button');
                            addNewShowButton.classList.add('btn');
                            addNewShowButton.classList.add('btn-info');
                            addNewShowButton.classList.add('addNewShowButton');
                            addNewShowButton.style.width = "100%";
                            addNewShowButton.textContent = 'Dodaj nowy pokaz';

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

            /**
             * This method append first day container and first show container
             */
            (function pageOpen() {
                let firstDay = new DayBox();
                firstDay.createDOMDayBox();
                let firstDayContainer = firstDay.getBox();
                panelBody.insertAdjacentElement("afterbegin", firstDayContainer);

                let firstForm = new showBox();
                firstForm.addRemoveShowButton();
                firstForm.addDistanceCheckbox();
                firstForm.addNewShowButton();
                firstForm.createDOMBox();
                let firstFormDOM = firstForm.getForm();

                firstDayContainer.appendChild(firstFormDOM);
            })();

            /**
             * Global click handler
             */
            function globalClickHandler(e) {
                if(e.target.matches('.addNewShowButton')) { //user clicks on "add new show" button
                    e.preventDefault();
                    const newShowButton = e.target;
                    const dayContainer = newShowButton.closest('.singleDayContainer');
                    const thisShowContainer = newShowButton.closest('.singleShowContainer');
                    const allSingleShowContainers = document.getElementsByClassName('singleShowContainer');
                    const lastSingleShowCOntainer = allSingleShowContainers[allSingleShowContainers.length - 1];

                    const selectedCity = thisShowContainer.querySelector('.citySelect');
                    const selectedCityId = selectedCity.options[selectedCity.selectedIndex].value;

                    var validation = validateForm(thisShowContainer);

                    if(validation) {
                        let newForm = new showBox();
                        newForm.addRemoveShowButton();
                        newForm.addDistanceCheckbox();
                        newForm.addNewShowButton();

                        lastOneFlag = true;
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

                        //we are checking whether cliecked singleDayContainer is last one, or between others.
                        if(lastOneFlag === true) {
                            newForm.createDOMBox(30, selectedCityId);
                            newFormDomElement = newForm.getForm();
                            thisShowContainer.insertAdjacentElement('afterend',newFormDomElement);
                        }
                        else { //container is not last one
                            const apreviousCitySelect = thisShowContainer.querySelector('.citySelect');
                            const anextCitySelect = nextShowContainer.querySelector('.citySelect');
                            //we are checking if user selected any city in upper and lower show container
                            if(anextCitySelect.options[anextCitySelect.selectedIndex].value != 0 && apreviousCitySelect.options[apreviousCitySelect.selectedIndex].value != 0) {
                                const previousShowContainer = thisShowContainer; // relative to newForm, this one is previousShowContainer
                                newForm.createDOMBox(30, selectedCityId, true, previousShowContainer, nextShowContainer);
                                newFormDomElement = newForm.getForm();
                                thisShowContainer.insertAdjacentElement('afterend',newFormDomElement);
                            }
                            else {
                                notify('Wybierz miasta w pokazach powyżej i poniżej');
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
                    const allRemoveButtons = dayContainer.getElementsByClassName('remove-button');
                    console.assert(allRemoveButtons, "Brak przycisków usuń");
                    if(allRemoveButtons.length > 1) { //delete only show box
                        showContainer.parentNode.removeChild(showContainer);
                    }
                    else if(allRemoveButtons.length === 1) { //delete day box
                        const allDayContainers = document.getElementsByClassName('singleDayContainer');
                        if(allDayContainers.length > 1) {
                            dayContainer.parentNode.removeChild(dayContainer);
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

                    let validate = validateForm(allSingleShowContainers[allSingleShowContainers.length - 1]);

                    if(validate) {
                        lastDayContainer.insertAdjacentElement("afterend", firstDayContainer);

                        const allCitiesSelect = document.getElementsByClassName('citySelect');
                        const selectedCity = allCitiesSelect[allCitiesSelect.length - 1];
                        const selectedCityId = selectedCity.options[selectedCity.selectedIndex].value;

                        let firstForm = new showBox();
                        firstForm.addRemoveShowButton();
                        firstForm.addDistanceCheckbox();
                        firstForm.addNewShowButton();
                        firstForm.createDOMBox(100, selectedCityId);
                        let firstFormDOM = firstForm.getForm();

                        firstDayContainer.appendChild(firstFormDOM);
                    }
                    else {
                        notify('Uzupełnij miasto');
                    }

                }
            };

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
                    let cityDistance = citySelect.dataset.distance;
                    citySelect.innerHTML = ''; //clear select

                    //this part remove all event listeners from this node
                    var old_element = voivodeSelect;
                    var new_element = old_element.cloneNode(true);
                    old_element.parentNode.replaceChild(new_element, old_element);
                    //end remove all event listeners

                    voivodeSelect = thisSingleShowContainer.querySelector('.voivodeSelect');

                    appendBasicOption(citySelect);
                    appendBasicOption(voivodeSelect);

                    if(isChecked) { // activate no distance limit option
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
                            voivodeSelect.appendChild(singleVoivode);
                            @endforeach()

                            voivodeSelect.addEventListener('change', e => {
                                citySelect.setAttribute('data-distance', 'infinity');
                                let voivodeId = e.target.value;
                                showWithoutDistanceAjax(voivodeId, citySelect);
                            });
                        }
                        else if(previousSingleShowContainer !== null && nextSingleShowContainer === null) { //case when show is last one
                            const previousCitySelect = previousSingleShowContainer.querySelector('.citySelect');
                            const previousCityId = previousCitySelect.options[previousCitySelect.selectedIndex].value;
                            showInExtreme(citySelect.dataset.previousdistance, previousCityId, citySelect, voivodeSelect);
                        }
                        else if(previousSingleShowContainer === null && nextSingleShowContainer !== null) { //case when show is first one
                            const nextCitySelect = nextSingleShowContainer.querySelector('.citySelect');
                            const nextCityId = nextCitySelect.options[nextCitySelect.selectedIndex].value;

                            showInExtreme(30, nextCityId, citySelect, voivodeSelect);
                        }
                        else if(previousSingleShowContainer !== null && nextSingleShowContainer !== null) { //case when show is in the middle
                            const previousCitySelect = previousSingleShowContainer.querySelector('.citySelect');
                            const previousCityDistance = previousCitySelect.dataset.distance;
                            const previousCityId = previousCitySelect.options[previousCitySelect.selectedIndex].value;

                            const nextCitySelect = nextSingleShowContainer.querySelector('.citySelect');
                            const nextCityDistance = nextCitySelect.dataset.distance;
                            const nextCityId = nextCitySelect.options[nextCitySelect.selectedIndex].value;

                            showInTheMiddleAjax(previousCityDistance, previousCityId, nextCityDistance, nextCityId, citySelect, voivodeSelect);
                        }

                    }
                }
            }

            document.addEventListener('click', globalClickHandler);
            document.addEventListener('change', globalChangeHandler);

        });

    </script>
@endsection
