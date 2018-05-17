@extends('layouts.main')
@section('style')

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
                    Tworzenie Tras
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

            let iterator = 1;
            let mainContainer = document.querySelector('.routes-wrapper'); //zaznaczamy główny container

            /**
             *Ta funkcja tworzy nowy show - tu jest napisany kod html całego formularza
             */
            function createNewShow() {
                let numberOfShow = iterator;
                newElement = document.createElement('div');
                newElement.className = 'routes-container';
                newElement.innerHTML = '        <div class="row">\n' +
                    '<div class="button_section button_section_gl_nr' + numberOfShow + '">' +
                    '<span class="glyphicon glyphicon-remove" data-remove="show"></span>' +
                    '</div>' +
                    '        <header>Pokaz </header>\n' +
                    '\n' +
                    '            <div class="col-md-4">\n' +
                    '                <div class="form-group">\n' +
                    '                    <label for="woj' + numberOfShow + '">Województwo</label>\n' +
                    '                    <select name="woj' + numberOfShow + '" id="woj' + numberOfShow + '" class="form-control">\n' +
                    '                        <option value="0">Wybierz</option>\n' +
                    '                        <option value="1">Lubelskie</option>\n' +
                    '                        <option value="2">Mazowieckie</option>\n' +
                    '                    </select>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '\n' +
                    '            <div class="col-md-4">\n' +
                    '                <div class="form-group">\n' +
                    '                    <label for="city' + numberOfShow + '">Miasto</label>\n' +
                    '                    <select name="city' + numberOfShow + '" id="city' + numberOfShow + '" class="form-control">\n' +
                        @foreach($departments as $department)
                            '<option value ="{{$department->id}}">{{$department->departments->name}}</option>' +
                        @endforeach
                            '                    </select>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '\n' +
                    '            <div class="col-md-4">\n' +
                    '                <div class="form-group">\n' +
                    '                    <label for="karencja' + numberOfShow + '">Karencja</label>\n' +
                    '                    <select name="karencja' + numberOfShow + '" id="karencja' + numberOfShow + '" class="form-control">\n' +
                    '                        <option>Wybierz</option>\n' +
                    '                        <option value="0">0</option>\n' +
                    '                        <option value="1">1</option>\n' +
                    '                        <option value="2">2</option>\n' +
                    '                        <option value="3">3</option>\n' +
                    '                    </select>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '\n' +
                    // '            <div class="form-group">\n' +
                    // '                <label for="date">Data:</label>\n' +
                    // '                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">\n' +
                    // '                    <input class="form-control form-all-dates" name="start_date' + numberOfShow + '" type="text">\n' +
                    // '                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>\n' +
                    // '                </div>\n' +
                    // '            </div>\n' +
                    '<div class="form-group hour_div">' +

                    '</div>' +
                    '\n' +
                    // '            <div class="col-lg-12 new_hour_section">\n' +
                    // '<input type="button" class="btn btn-primary" data-hour="true" value="Dodaj godzinę pokazu" style="width:30%;margin-bottom:1em;">' +
                    // '</div>' +
                    '            <div class="col-lg-12 button_section">\n' +
                    '                <input type="button" class="btn btn-success" id="save_route" value="Zapisz!" style="width:100%;margin-bottom:1em;">\n' +
                    '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_show" value="Dodaj nową trasę" style="width:100%;margin-bottom:1em;">' +
                    '            </div>\n' +
                    '        </div>';
                return newElement;
            }


            /**
             * Ta funkcja dodaje nowy pokaz.
             */
            function addNewShow() {
                removeButtonsFromLastShow();
                let newShow = createNewShow(); //otrzymujemy nowy formularz z pokazem.
                mainContainer.appendChild(newShow);

                iterator++;

                $('.form_date').datetimepicker({
                    language:  'pl',
                    autoclose: 1,
                    minView : 2,
                    pickTime: false
                });
            }


            function removeButtonsFromLastShow() {
                let buttonSection = document.getElementsByClassName('button_section')[document.getElementsByClassName('button_section').length - 1];
                if(buttonSection != null) {
                    buttonSection.parentNode.removeChild(buttonSection);
                }
            }

            function removeGlyInFirstShow() {
                let firstShow = document.getElementsByClassName('routes-container')[0];
                let removeGlyphicon = firstShow.getElementsByClassName('glyphicon-remove')[0];
                removeGlyphicon.parentNode.removeChild(removeGlyphicon);
            }

            function removeGivenHour(container) {
                container.parentNode.removeChild(container);
            }

            function insertHourInput(container) {
                let hourInputContainer = document.createElement('div');
                hourInputContainer.innerHTML = '<label class="remove_hour_section">Godzina pokazu  <span class="glyphicon glyphicon-minus" data-remove="hour" style="color:red"></span></label><input type="time" class="form-control" name="show_hour">';
                container.appendChild(hourInputContainer);
            }

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

            function addButtonsToPreviousContainer(container) {
                let previousContainer = container.previousElementSibling;
                let placeInPreviousContainer = previousContainer.getElementsByClassName('hour_div')[0];
                let buttonsElement = document.createElement('div');
                buttonsElement.classList.add('col-lg-12');
                buttonsElement.classList.add('button_section');
                buttonsElement.innerHTML = '                <input type="button" class="btn btn-success" id="save_route" value="Zapisz!" style="width:100%;margin-bottom:1em;">\n' +
                    '<input type="button" class="btn btn-info btn_add_new_route" id="add_new_show" value="Dodaj nową trasę" style="width:100%;margin-bottom:1em;">';
                buttonsElement.appendAfter(placeInPreviousContainer);
            }


            function buttonHandler(e) {
                if(e.target.id == 'add_new_show') { // click on add new show button
                    addNewShow();
                }
                else if(e.target.dataset.remove == 'show') { // click on X glyphicon
                    let showContainer = e.target.parentElement.parentElement.parentElement;
                    removeGivenShow(showContainer);
                }
                else if(e.target.dataset.hour == 'true') { // click on add hour button
                    let hourContainer = e.target.parentElement;
                    insertHourInput(hourContainer);
                }
                else if(e.target.dataset.remove == 'hour') { // click on - glyphicon(hour)
                    let givenHourInputContainer = e.target.parentElement.parentElement;
                    removeGivenHour(givenHourInputContainer);
                }

            }
            /***********************************************/

            mainContainer.addEventListener('click', buttonHandler);

            addNewShow();
            removeGlyInFirstShow();

        });
    </script>
@endsection
