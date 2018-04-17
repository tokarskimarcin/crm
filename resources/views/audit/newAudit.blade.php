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
        /*.second-panel th:nth-of-type(3) {*/
            /*width: 10%;*/
        /*}*/
        .second-panel th:nth-of-type(3) {
            width: 45%;
        }

        .second-panel th:nth-of-type(4) {
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
<form method="post" action="{{URL::to('/handleForm')}}" id="auditForm" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
        <div class="panel panel-default second-panel">
            <div class="panel-heading titleOfSecondPanel">
                <p>Nazwa drugiego panelu</p>
            </div>
            <div class="panel-body">
                <h4>
                    <div class="alert alert-warning"><p><sup>*</sup>Kolumny <strong>Tak/Nie</strong> i <strong>Dlaczego</strong> są obowiązkowe.</p></div>
                    <div class="alert alert-info"><p>Dla otrzymania lepszego wyglądu formularza zaleca się <i>wyłącznie</i> panelu nawigacyjnego naciskając przycisk "OFF" w górnym lewym rogu strony. </p></div>
                    <div class="alert alert-warning"><p>Zdjęcia mogą być <i>tylko</i> w formatach: <strong>.pdf</strong> <strong>.jpg</strong> <strong>.jpeg</strong> <strong>.png</strong></p></div>
                </h4>
                @foreach($headers as $h)
                    {{--*****************************************ZMIENIC JAKOS 1 NA ZMIENNA ************************--}}
                    @if($h->status == $templateType)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="first">Kryteria</th>
                            <th>Tak/Nie<sup>*</sup></th>
                            {{--<th>Jakość<sup>*</sup></th>--}}
                            <th>Dlaczego<sup>*</sup></th>
                            <th>Zdjęcia</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div class="well well-sm"><p style="text-align:center;font-weight:bold;font-size:1.1em;">{{ucwords($h->name)}}</p></div>
                        @foreach($criterion as $c)
                            @if($c->audit_header_id == $h->id)
                                @if($c->status == $templateType)
                        <tr class="tableRow">
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
                            {{--<td>--}}
                                {{--<div class="form-group">--}}
                                    {{--<select class="form-control secondInp" style="font-size:18px;" id="{{$c->name . "_quality"}}" name="{{$c->name . "_quality"}}">--}}
                                        {{--<option value="0">--</option>--}}
                                        {{--<option value="1">Tak</option>--}}
                                        {{--<option value="2">Nie</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</td>--}}
                            <td>
                                <div class="form-group">
                                    <input type="text" id="{{$c->name . "_comment"}}" name="{{$c->name . "_comment"}}" class="form-control thirdInp" style="width:100%;">
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
            <input type="hidden" name="trainer" value="{{$trainerID}}">
            <input type="hidden" name="department_info" value="{{$department_info}}">
            <input type="hidden" name="date" value="{{$date_audit}}">
            <input type="hidden" name="templateType" value="{{$templateType}}">
            <input type="hidden" name="typeOfPerson" value="{{$typeOfPerson}}">
        </div>
    </div>
    </form>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {

            /**
             * sets heading for 2nd panel
             */
            var title = document.querySelector('.titleOfSecondPanel > p').firstChild;
            title.textContent = 'Wypełnij audyt';

            /**
             * Function checks whether all inputs are filled by user(validation), if positive, send form.
             */
            function submitHandler(e) {
                e.preventDefault();
                var everythingIsOk = true; //true = form submits, false = form doesn't submit
                var firstInp = document.getElementsByClassName('firstInp');
                // var secondInp = document.getElementsByClassName('secondInp');
                var thirdInp = document.getElementsByClassName('thirdInp');

                 //Check if every amount input is selected
                for(var i = 0; i < firstInp.length; i++) {
                    if(firstInp[i].value == 0) {
                        everythingIsOk = false;
                        break;
                    }
                }


                 // check if every quality input is selected g
                // if(everythingIsOk == true) {
                //     for(var j = 0; j < secondInp.length; j++) {
                //         if(secondInp[j].value == 0) {
                //             everythingIsOk = false;
                //             break;
                //         }
                //     }
                // }

                if(everythingIsOk == true) {
                    for(var k = 0; k < thirdInp.length; k++) {
                        if(thirdInp[k].value == "" || thirdInp[k].value == null) {
                            everythingIsOk = false;
                            break;
                        }
                    }
                }

                //Validation of required inputs
                if(everythingIsOk != true) {
                    swal('Wypełnij wszystkie pola w kolumnach "Tak/Nie" i "Dlaczego"');
                }

                if(everythingIsOk == true) {

                    var auditScore = 0;
                    var percentAuditScore;
                    var numberOfRows = 0;
                    var allTableRows = document.querySelectorAll('.tableRow');

                    allTableRows.forEach(function(element) {
                        var firstInputInside = element.cells[1].firstElementChild.firstElementChild.value;
                        // var secondInputInside = element.cells[2].firstElementChild.firstElementChild.value;
                        if(firstInputInside == 1) {
                            auditScore += 1;
                        }
                        numberOfRows += 1;
                    });
                    percentAuditScore = 100 * auditScore / numberOfRows;
                    $('.last-row').after('<input type="hidden" name="score" value="' + percentAuditScore + '">');
                    document.getElementById('auditForm').submit();
                }

            }

            /***********Submit part*************/
            var submitButton = document.getElementById('secondButton');
            submitButton.addEventListener('click', submitHandler);

        });
    </script>


@endsection
