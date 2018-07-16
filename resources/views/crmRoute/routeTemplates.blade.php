
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

        function Box() {
            this.addNewShowButtonFlag = false; //indices whether add newShowButton
            this.addRemoveShowButtonFlag = false; //indices whether add removeShowButton
            this.addCheckboxFlag = false; //indices whether add refreshShowButton
            this.DOMBox = null; //here is stored DOM representation of Box
            this.addNewShowButton = function() {
                this.addNewShowButtonFlag = true;
            };
            this.addRemoveShowButton = function() {
                this.addRemoveShowButtonFlag = true;
            };
            this.addDistanceCheckbox = function() {
                this.addCheckboxFlag = true;
            };
            this.createDOMBox = function(distance = Infinity, selectedCity = null) { //Creation of DOM form
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

                let basicCityOption = document.createElement('option');
                basicCityOption.value = '0';
                basicCityOption.textContent = 'Wybierz';


                let formBodyColLeftColumn = document.createElement('div');
                formBodyColLeftColumn.classList.add('col-md-6');

                let formBodyLeftColumnGroup = document.createElement('div');
                formBodyLeftColumnGroup.classList.add('form-group');

                let firstSelectLabel = document.createElement('label');
                firstSelectLabel.textContent = 'Województwo';

                let firstSelect = document.createElement('select');
                firstSelect.classList.add('voivodeSelect');
                firstSelect.classList.add('form-control');

                let basicVoivodeOption = document.createElement('option');
                basicVoivodeOption.value = '0';
                basicVoivodeOption.textContent = 'Wybierz';

                firstSelect.appendChild(basicVoivodeOption);

                if(distance === Infinity) { //every voivodeship and every city
                    @foreach($voivodes as $voivode)
                        var singleVoivode = document.createElement('option');
                        singleVoivode.value = {{$voivode->id}};
                        singleVoivode.textContent = '{{$voivode->name}}';
                        firstSelect.appendChild(singleVoivode);
                    @endforeach()

                    firstSelect.addEventListener('change', e => {
                        let voivodeId = e.target.value;
                        console.log(voivodeId);
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
                                console.log(response);
                                let placeToAppend = secondSelect;
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
                }
                else if(distance === 100 || distance === 30) {
                    console.log(selectedCity);
                    $.ajax({
                        type: "POST",
                        url: '{{ route('api.getVoivodeshipRoundWithoutGracePeriod') }}',
                        data: {
                            'limit': distance,
                            "cityId": selectedCity
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            let allVoivodes = response['voievodeInfo'];
                            let allCitiesGroupedByVoivodes = response['cityInfo'];

                            allVoivodes.forEach(voivode => {
                                let voivodeOption = document.createElement('option');
                                voivodeOption.value = voivode.id;
                                voivodeOption.textContent = voivode.name;
                                firstSelect.appendChild(voivodeOption);
                            });

                            //After selecting voivode, this event listener appends cities from given range into city select
                            firstSelect.addEventListener('change', e => {
                                secondSelect.innerHTML = ''; //cleaning previous insertions
                                let basicCityOption = document.createElement('option');
                                basicCityOption.value = '0';
                                basicCityOption.textContent = 'Wybierz';

                                secondSelect.appendChild(basicCityOption);

                               let voivodeId = e.target.value;
                               for(Id in allCitiesGroupedByVoivodes) {
                                   if(voivodeId == Id) {
                                       allCitiesGroupedByVoivodes[Id].forEach(city => {
                                           let cityOption = document.createElement('option');
                                           cityOption.value = city.city_id;
                                           cityOption.textContent = city.city_name;
                                           secondSelect.appendChild(cityOption);
                                       });
                                   }
                               }
                            });
                        }
                    });
                }

                formBodyLeftColumnGroup.appendChild(firstSelectLabel);
                formBodyLeftColumnGroup.appendChild(firstSelect);
                formBodyColLeftColumn.appendChild(formBodyLeftColumnGroup);
                formBodyRow.appendChild(formBodyColLeftColumn);


                secondSelect.appendChild(basicCityOption);
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
            console.log(firstDayContainer);
            panelBody.insertAdjacentElement("afterbegin", firstDayContainer);

            let firstForm = new Box();
            firstForm.addRemoveShowButton();
            firstForm.addDistanceCheckbox();
            firstForm.addNewShowButton();
            firstForm.createDOMBox();
            let firstFormDOM = firstForm.getForm();

            firstDayContainer.appendChild(firstFormDOM);
        })();

        function globalClickHandler(e) {
            if(e.target.matches('.addNewShowButton')) { //user clicks on "add new show" button
                e.preventDefault();
                const newShowButton = e.target;
                const dayContainer = newShowButton.closest('.singleDayContainer');
                const thisShowContainer = newShowButton.closest('.singleShowContainer');
                const selectedCity = thisShowContainer.querySelector('.citySelect');
                const selectedCityId = selectedCity.options[selectedCity.selectedIndex].value;

                let newForm = new Box();
                newForm.addRemoveShowButton();
                newForm.addDistanceCheckbox();
                newForm.addNewShowButton();
                newForm.createDOMBox(30, selectedCityId);
                newFormDomElement = newForm.getForm();

                dayContainer.appendChild(newFormDomElement);
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

                lastDayContainer.insertAdjacentElement("afterend", firstDayContainer);

                const allCitiesSelect = document.getElementsByClassName('citySelect');
                const selectedCity = allCitiesSelect[allCitiesSelect.length - 1];
                const selectedCityId = selectedCity.options[selectedCity.selectedIndex].value;

                let firstForm = new Box();
                firstForm.addRemoveShowButton();
                firstForm.addDistanceCheckbox();
                firstForm.addNewShowButton();
                firstForm.createDOMBox(100, selectedCityId);
                let firstFormDOM = firstForm.getForm();

                firstDayContainer.appendChild(firstFormDOM);
            }
        };

        document.addEventListener('click', globalClickHandler);

        });

    </script>
@endsection
