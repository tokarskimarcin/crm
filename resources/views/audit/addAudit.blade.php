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

    <form action="{{URL::to('/handleForm')}}" method="POST" id="auditForm" enctype="multipart/form-data">

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
                            <label class="myLabel">Trener:</label>
                            <select class="form-control" style="font-size:18px;" id="trainer" name="trainer">
                                <option value="0" id="trainerDefaultValue">Wybierz</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="alert alert-info">
                            <p style="text-align:center;font-size:1.3em;">Krok 2: Wybierz trenera z listy.</p>
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
                            <p style="text-align:center;font-size:1.3em;">Krok 3: Wybierz datę audytu a następnie naciśnij przycisk "Generuj raport".</p>
                        </div>
                    </div>
                </div>
                <div class="row fourth-row">
                    <div class="col-md-12">
                        <input class="btn btn-info btn-block" type="button" id="firstButton" value="Generuj raport">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default second-panel">
            <div class="panel-heading titleOfSecondPanel">
                <p>Nazwa drugiego panelu</p>
            </div>
            <div class="panel-body">
                <h4>
                    <div class="alert alert-warning"><sup>*</sup></sup>Kolumny <strong>Ilość</strong> i <strong>Jakość</strong> są obowiązkowe.</p></div>
                    <div class="alert alert-info"><p>Dla otrzymania lepszego wyglądu formularza zaleca się <i>wyłącznie</i> panelu nawigacyjnego naciskając przycisk "OFF" w górnym lewym rogu strony. </p></div>
                </h4>
                @foreach($headers as $h)
                    @if($h->status == 1)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="first">Kryteria</th>
                            <th>Ilość<sup>*</sup></th>
                            <th>Jakość<sup>*</sup></th>
                            <th>Komentarz</th>
                            <th>Zdjęcia</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div class="well well-sm"><p style="text-align:center;font-weight:bold;font-size:1.1em;">{{ucwords($h->name)}}</p></div>
                        @foreach($criterion as $c)
                            @if($c->audit_header_id == $h->id)
                                @if($c->status == 1)
                        <tr>
                            <td class="first">{{ucwords(str_replace('_',' ',$c->name))}}</td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control firstInp" style="font-size:18px;" id="{{$c->name . "_amount"}}" name="{{$c->name . "_amount"}}">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="form-control secondInp" style="font-size:18px;" id="{{$c->name . "_quality"}}" name="{{$c->name . "_quality"}}">
                                        <option value="0">--</option>
                                        <option value="1">Tak</option>
                                        <option value="2">Nie</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" id="{{$c->name . "_comment"}}" name="{{$c->name . "_comment"}}" class="form-control" style="width:100%;">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input name="{{$c->name . "_files[]"}}" id="{{$c->name . "_files[]"}}" type="file" multiple="" />
                                </div>
                            </td>
                        </tr>
                                @endif
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="row last-row">
        <div class="col-md-12">
            <input class="btn btn-success btn-block" type="submit" id="secondButton" value="Zapisz audyt!" style="margin-bottom:1em;">
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
                            for(var i = 0; i < response.length; i++) {
                                var newItem = $('<option class="generatedValues" value="' + response[i].id + '">' + response[i].first_name + ' ' + response[i].last_name + '</option>');
                                $('#trainerDefaultValue').after(newItem);
                            }
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
            function handleChange2() {
                if(inputDepartment.value != '0') {
                    thirdStep.classList.remove('inactivePanel');
                    fourthStep.classList.remove('inactivePanel');
                    return true;
                }
                else {
                    thirdStep.classList.add('inactivePanel');
                    fourthStep.classList.add('inactivePanel');
                    return true;
                }
            }

            /**
             *Function hide first panel(panel with general info) and show 2nd panel(panel with form) and sets heading for 2nd panel
             */
            var title = document.querySelector('.titleOfSecondPanel > p').firstChild;
            function handleFirstButtonClick() {
                secondPanel.classList.remove('inactivePanel');
                secondButton.classList.remove('inactivePanel');
                title.textContent = 'Audyt dla departamentu: ' + inputDepartment.options[inputDepartment.selectedIndex].text + ', trener ' + inputTrainer.options[inputTrainer.selectedIndex].text + ' ' + inputDate.value;
                firstPanel.classList.add('inactivePanel');
            }

            /**
             * Function checks whether all inputs are filled by user(validation), if positive, send form.
             */
            function submitHandler(e) {
                e.preventDefault();
                var everythingIsOk = true; //true = form submits, false = form doesn's submit
                var firstInp = document.getElementsByClassName('firstInp');
                var secondInp = document.getElementsByClassName('secondInp');

                 //Check if every amount input is selected
                for(var i = 0; i < firstInp.length; i++) {
                    if(firstInp[i].value == 0) {
                        everythingIsOk = false;
                        break;
                    }
                }


                 // check if every quality input is selected g
                if(everythingIsOk == true) {
                    for(var j = 0; j < secondInp.length; j++) {
                        if(secondInp[j].value == 0) {
                            everythingIsOk = false;
                            break;
                        }
                    }
                }

                //Validation of required inputs
                if(everythingIsOk != true) {
                    swal('Wypełnij wszystkie pola w kolumnach "Ilość" i "Jakość"');
                }

                if(everythingIsOk == true) {
                    document.getElementById('auditForm').submit();
                }

            }

            /************ End of event listeners functions ************/

            //select every div that should disappear/appear at some point of user experience
           var firstPanel = document.getElementsByClassName('first-panel')[0];
           var secondPanel = document.getElementsByClassName('second-panel')[0];
           var secondStep = document.getElementsByClassName('second-row')[0];
           var thirdStep = document.getElementsByClassName('third-row')[0];
           var fourthStep = document.getElementsByClassName('fourth-row')[0];
           var secondButton = document.getElementById('secondButton');

           //Hiding divs and button at beggining
           secondPanel.classList.add('inactivePanel');
           secondStep.classList.add('inactivePanel');
           thirdStep.classList.add('inactivePanel');
           fourthStep.classList.add('inactivePanel');
           secondButton.classList.add('inactivePanel');

            //Select inputs of first panel(before form appears)
           var inputDepartment = document.getElementById('department_info');
           var inputTrainer = document.getElementById('trainer');
           var inputDate = document.getElementById('date');
           var firstButton = document.getElementById('firstButton');

            //event listeners responsible for showing/hiding first panel divs
            inputDepartment.addEventListener('change', handleChange1);
            inputTrainer.addEventListener('change', handleChange2);
            firstButton.addEventListener('click', handleFirstButtonClick);

            /***********Submit part*************/
            var submitButton = document.getElementById('secondButton');
            submitButton.addEventListener('click', submitHandler);

        });
    </script>


@endsection
