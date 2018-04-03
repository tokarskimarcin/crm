@extends('layouts.main')
@section('content')

    <style>
        th:nth-of-type(1) {
            width: 25%;
        }
        th:nth-of-type(2) {
            width: 10%;
        }
        th:nth-of-type(3) {
            width: 10%;
        }
        th:nth-of-type(4) {
            width: 50%;
        }

        th:nth-of-type(5) {
            width: 5%;
        }

        .panel-default > .panel-heading {
            background: #83BFC6;
        }
    </style>
<div class="container-fluid">
    <form action="{{URL::to('/handleEdit')}}" method="post" enctype="multipart/form-data" id="auditForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="givenID" value="{{$givenId}}">
    <div class="row">
        <div class="panel panel-default second-panel">
            <div class="panel-heading titleOfSecondPanel">
                <p>Audyt dla departamentu {{$infoAboutAudit['0']->department}} wypełniony przez {{$infoAboutAudit['0']->user_name}} dla trenera {{$infoAboutAudit['0']->trainer}} w {{$infoAboutAudit['0']->date_audit}}</p>
            </div>
            <div class="panel-body">
                @foreach($headers as $h)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="first">Kryteria</th>
                                <th>Ilość</th>
                                <th>Jakość</th>
                                <th>Komentarz</th>
                                <th>Zdjęcia</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div class="well well-sm"><p style="text-align:center;">{{ucwords($h->name)}}</p></div>
                            @foreach($criterion as $c)
                                @if($c->audit_header_id == $h->id)
                                    <tr>
                                        <td class="first">{{ucwords(str_replace('_',' ',$c->name))}}</td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control firstInp" style="font-size:18px;" id="{{$c->name . "_amount"}}" name="{{$c->name . "_amount"}}">
                                                    @foreach($audit_info as $a)
                                                        @if($c->id == $a->audit_criterion_id)
                                                    <option value="0" @if($a->amount == '0') selected @endif>--</option>
                                                    <option value="1" @if($a->amount == '1') selected @endif>Tak</option>
                                                    <option value="2" @if($a->amount == '2') selected @endif>Nie</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control secondInp" style="font-size:18px;" id="{{$c->name . "_quality"}}" name="{{$c->name . "_quality"}}">
                                                    @foreach($audit_info as $a)
                                                        @if($c->id == $a->audit_criterion_id)
                                                    <option value="0" @if($a->quality == '0') selected @endif>--</option>
                                                    <option value="1" @if($a->quality == '1') selected @endif>Tak</option>
                                                    <option value="2" @if($a->quality == '2') selected @endif>Nie</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                @foreach($audit_info as $a)
                                                    @if($c->id == $a->audit_criterion_id)
                                                        @if(isset($a->comment))
                                                        <input type="text" id="{{$c->name . "_comment"}}" name="{{$c->name . "_comment"}}" class="form-control" style="width:100%;" value="{{$a->comment}}">
                                                        @else
                                                        <input type="text" id="{{$c->name . "_comment"}}" name="{{$c->name . "_comment"}}" class="form-control" style="width:100%;" value="">
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input name="{{$c->name . "_files[]"}}" id="{{$c->name . "_files[]"}}" type="file" multiple="" />
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
    $(document).ready(function() {

        var submitButton = document.getElementById('secondButton');
        submitButton.addEventListener('click', submitHandler);

        function submitHandler(e) {
            e.preventDefault();
            var everythingIsOk = true; //true = form submits, false = form doesn's submit
            var firstInp = document.getElementsByClassName('firstInp');
            var secondInp = document.getElementsByClassName('secondInp');

            /**
             * Check if every amount input is selected
             */
            for(var i = 0; i < firstInp.length; i++) {
                if(firstInp[i].value == 0) {
                    everythingIsOk = false;
                    break;
                }
            }

            /**
             * check if every quality input is selected
             */
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

    });



    </script>

@endsection
