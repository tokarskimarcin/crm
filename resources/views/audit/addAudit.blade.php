@extends('layouts.main')
@section('content')
    {{--*************************************--}}
    {{--THIS PAGE ALLOWS USER ADD NEW AUDIT--}}
    {{--*************************************--}}


    <style>
        .container-fluid {
            padding: 5px;
        }

        .second-panel th:nth-of-type(1) {
            width: 25%;
        }
        .second-panel th:nth-of-type(2) {
            width: 10%;
        }
        .second-panel th:nth-of-type(3) {
            width: 10%;
        }
        .second-panel th:nth-of-type(4) {
            width: 45%;
        }

        .second-panel th:nth-of-type(5) {
            width: 5%;
        }

        .panel-default > .panel-heading {
            background: #83BFC6;
        }

        .gray-nav {
            background: #02779E;
        }

        sup {
            color: red;
        }

        .inactivePanel {
            display: none;
        }

        .activePanel {
            display: block;
        }
    </style>

    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <div class="alert gray-nav">Audyt</div>
            </div>
        </div>
    </div>

    <form action="{{URL::to('/addAudit')}}" method="POST" id="auditForm" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="panel panel-default panel-primary first-panel">
            <div class="panel-heading">
                <p>Informacje ogólne</p>
            </div>
            <div class="panel-body">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row first-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Oddział:</label>
                            <select class="form-control" style="font-size:18px;" id="department_info" name="department_info">
                                <option value="0">Wybierz</option>
                                @if(isset($dept))
                                    @foreach($dept as $d)
                                        <option value="{{$d->id}}">{{$d->departments->name}} {{$d->department_type->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="alert alert-info">
                            <p style="text-align:center;font-size:1.3em;">Krok 1: Wybierz departament.</p>
                        </div>
                    </div>
                </div>
                <div class="row second-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Osoba:</label>
                            <select class="form-control" style="font-size:18px;" id="trainer" name="trainer">
                                <optgroup label="Trenerzy" data-nr="1">
                                    <option value="0" id="trainerDefaultValue">Wybierz</option>
                                </optgroup>
                                <optgroup label="HRowcy" id="hrGroup" data-nr="2">
                                </optgroup>
                                <optgroup label="Zbiorczy dla oddziału" id="collective" data-nr="3">
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="alert alert-info">
                            <p style="text-align:center;font-size:1.3em;">Krok 2: Wybierz osobę z listy.</p>
                        </div>
                    </div>
                </div>
                <div class="row rowBetweenSecondAndThird">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="template">Rodzaj formularza</label>
                            <select name="template" id="template" class="form-control">
                                <option value="0" id="templateDefaultValue">Wybierz</option>
                                @foreach($templates as $template)
                                    @if($template->id != '0' && $template->isActive == 1)
                                        <option value="{{$template->id}}">{{$template->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="alert alert-info">
                            <p style="text-align:center;font-size:1.3em;">Krok 3: Wybierz rodzaj formularza</p>
                        </div>
                    </div>
                </div>
                <div class="row third-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="myLabel">Data:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" name="date" id="date" type="text" value="{{date("Y-m-d")}}">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="alert alert-info">
                            <p style="text-align:center;font-size:1.3em;">Krok 4: Wybierz datę audytu a następnie naciśnij przycisk "Generuj raport".</p>
                        </div>
                    </div>
                </div>
                <div class="row fourth-row">
                    <div class="col-md-12">
                        <input class="btn btn-info btn-block" type="submit" id="firstButton" value="Generuj raport">
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    </div>

@endsection
@section('script')
    <script>
        $('.form_date').datetimepicker({
            language:  'pl',
            autoclose: 1,
            minView : 2,
            pickTime: false,
        });

        $(document).ready(function() {

            var typeOfPerson; // 1 - trainer, 2 - hr, 3 - collective

            /*********************EVENT LISTENERS FUNCTIONS********************/

            /**
             * Function Show/Hide (2nd and next) steps and get list of trainers
             */
            function handleChange1() {
                if(inputDepartment.value != '0') { //activate 2nd step
                    secondStep.classList.remove('inactivePanel');
                    $('.generatedValues').remove(); //clear option list
                    $.ajax({ //generate list of trainers from given location
                        type: "POST",
                        url: '{{ route('api.ajax') }}',
                        data: {
                            "wybranaOpcja": inputDepartment.value
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            for(var i = 0; i < response.trainers.length; i++) {
                                var newItem = $('<option class="generatedValues" value="' + response.trainers[i].id + '">' + response.trainers[i].first_name + ' ' + response.trainers[i].last_name + '</option>');
                                $('#trainerDefaultValue').after(newItem);
                            }
                            for(var j = 0; j <response.hr.length; j++) {
                                var newItem2 = $('<option class="generatedValues" value="' + response.hr[j].id + '">' + response.hr[j].first_name + ' ' + response.hr[j].last_name + '</option>');
                                $('#hrGroup').append(newItem2);
                            }
                                var newItem3 = $('<option class="generatedValues" value="' + response.collective.id + '">' + inputDepartment.options[inputDepartment.selectedIndex].textContent + '</option>');
                                $('#collective').append(newItem3);
                        }
                    });
                    return true;
                }
                else { //hide all previous divs and set value of 2nd div to default
                    secondStep.classList.add('inactivePanel');
                    $('#trainer').val('0'); //set value of trainer input back to 0
                    thirdStep.classList.add('inactivePanel');
                    fourthStep.classList.add('inactivePanel');
                    return true;
                }
            }

            /**
             *Function Show/Hide 3rd and 4th step.
             */
            function handleChange2(e) {
                if(inputDepartment.value != '0') {
                    stepBetween.classList.remove('inactivePanel');
                    var selectedOptionArray = Array.from(e.target.selectedOptions);
                    typeOfPerson = selectedOptionArray[0].parentElement.dataset.nr;
                    // thirdStep.classList.remove('inactivePanel');
                    // fourthStep.classList.remove('inactivePanel');
                    return true;
                }
                else {
                    stepBetween.classList.add('inactivePanel');
                    // thirdStep.classList.add('inactivePanel');
                    // fourthStep.classList.add('inactivePanel');
                    return true;
                }
            }

            function handleChangeTemplate() {
                if(inputTemplate.value != '0') {
                    thirdStep.classList.remove('inactivePanel');
                    fourthStep.classList.remove('inactivePanel');
                }
                else {
                    thirdStep.classList.add('inactivePanel');
                    fourthStep.classList.add('inactivePanel');
                }
            }

            function handleButtonClick(e) {
                e.preventDefault();
                placeInForm = $('.fourth-row');
                hiddenInput = $('<input type="hidden" value="' + typeOfPerson + '" name="typeOfPerson">');
                placeInForm.after(hiddenInput);
                document.getElementById('auditForm').submit();
            }

            /************ End of event listeners functions ************/

            //select every div that should disappear/appear at some point of user experience
           var firstPanel = document.getElementsByClassName('first-panel')[0];
           var secondStep = document.getElementsByClassName('second-row')[0];
           var stepBetween = document.getElementsByClassName('rowBetweenSecondAndThird')[0];
           var thirdStep = document.getElementsByClassName('third-row')[0];
           var fourthStep = document.getElementsByClassName('fourth-row')[0];


           //Hiding divs and button at beggining
           secondStep.classList.add('inactivePanel');
           stepBetween.classList.add('inactivePanel');
           thirdStep.classList.add('inactivePanel');
           fourthStep.classList.add('inactivePanel');

            //Select inputs of first panel(before form appears)
           var inputDepartment = document.getElementById('department_info');
           var inputTemplate = document.getElementById('template');
           var inputTrainer = document.getElementById('trainer');
           var inputDate = document.getElementById('date');
           var firstButton = document.getElementById('firstButton');

            //event listeners responsible for showing/hiding first panel divs
            inputDepartment.addEventListener('change', handleChange1);
            inputTrainer.addEventListener('change', handleChange2);
            inputTemplate.addEventListener('change', handleChangeTemplate);
            firstButton.addEventListener('click', handleButtonClick);

        });
    </script>


@endsection
