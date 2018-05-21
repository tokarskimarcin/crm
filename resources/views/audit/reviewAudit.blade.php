@extends('layouts.main')
@section('content')
    {{--************************************************--}}
    {{--THIS PAGE SHOWS FILLED AUDIT WHICH CAN BE EDITED--}}
    {{--************************************************--}}
    <style>
        th:nth-of-type(1) {
            width: 25%;
        }
        th:nth-of-type(2) {
            width: 10%;
        }
        /*th:nth-of-type(3) {*/
            /*width: 10%;*/
        /*}*/
        th:nth-of-type(3) {
            width: 50%;
        }

        th:nth-of-type(5) {
            width: 5%;
        }

        sup {
            color:red;
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
                <p>Audyt dla departamentu {{$infoAboutAudit['0']->department}} wypełniony przez {{$infoAboutAudit['0']->user_name}}, osoba wybrana {{$infoAboutAudit['0']->trainer}} w {{$infoAboutAudit['0']->date_audit}}</p>
            </div>
            <div class="panel-body">
                <h4>
                    <div class="alert alert-warning"><p><sup>*</sup>Kolumny <strong>Tak/Nie</strong> i <strong>Dlaczego</strong> są obowiązkowe.</p></div>
                    <div class="alert alert-info"><p>Dla otrzymania lepszego wyglądu formularza zaleca się <i>wyłącznie</i> panelu nawigacyjnego naciskając przycisk "OFF" w górnym lewym rogu strony. </p></div>
                    <div class="alert alert-warning"><p>Zdjęcia mogą być <i>tylko</i> w formatach: <strong>.pdf</strong> <strong>.jpg</strong> <strong>.jpeg</strong> <strong>.png</strong>. Pliki dźwiękowe mogą być <i>tylko</i> w formatach: <strong>.wav</strong> <strong>.mp3</strong></p></div>
                </h4>
                @foreach($headers as $h)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="first">Kryteria</th>
                                <th>Tak/Nie<sup>*</sup></th>
                                <th>Komentarz<sup>*</sup></th>
                                <th></th>
                                <th>Zdjęcia/Pliki audio</th>
                                <th>Zdjęcia</th>
                                <th></th>
                                <th>Pliki audio</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <div class="well well-sm"><p style="text-align:center;font-weight:bold;font-size:1.1em;">{{ucwords($h->name)}}</p></div>
                            @foreach($criterion as $c)
                                @if($c->audit_header_id == $h->id)
                                    <tr class="tableRow">
                                        <td class="first">{{ucwords(str_replace('_',' ',$c->name))}}</td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control firstInp" style="font-size:18px;" id="{{$c->id . "_amount"}}" name="{{$c->id . "_amount"}}">
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
                                        {{--<td>--}}
                                            {{--<div class="form-group">--}}
                                                {{--<select class="form-control secondInp" style="font-size:18px;" id="{{$c->name . "_quality"}}" name="{{$c->name . "_quality"}}">--}}
                                                    {{--@foreach($audit_info as $a)--}}
                                                        {{--@if($c->id == $a->audit_criterion_id)--}}
                                                    {{--<option value="0" @if($a->quality == '0') selected @endif>--</option>--}}
                                                    {{--<option value="1" @if($a->quality == '1') selected @endif>Tak</option>--}}
                                                    {{--<option value="2" @if($a->quality == '2') selected @endif>Nie</option>--}}
                                                        {{--@endif--}}
                                                    {{--@endforeach--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                        {{--</td>--}}
                                        <td>
                                            <div class="form-group">
                                                @foreach($audit_info as $a)
                                                    @if($c->id == $a->audit_criterion_id)
                                                        @if(isset($a->comment))
                                                        <input type="text" id="{{$c->id . "_comment"}}" name="{{$c->id . "_comment"}}" class="form-control thirdInp" style="width:100%;" value="{{$a->comment}}">
                                                        @else
                                                        <input type="text" id="{{$c->id . "_comment"}}" name="{{$c->id . "_comment"}}" class="form-control thirdInp" style="width:100%;" value="">
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td> <a data-toggle="modal" data-info="{{$c->id . "_comment"}}" class="modal_trigger" href="#myModal"><span class="glyphicon glyphicon-search"></span></a></td>
                                        <td>
                                            <div class="form-group">
                                                <input name="{{$c->id . "_files[]"}}" id="{{$c->id . "_files[]"}}" type="file" multiple="" />
                                            </div>
                                        </td>
                                        <?php
                                            $i = 1;
                                        ?>
                                        <style>
                                            .inactive {
                                                display: none;
                                            }

                                            .gl-rem:hover {
                                                cursor: pointer;
                                                color: red;
                                            }
                                        </style>
                                        <td>
                                            <div class="form-group">
                                                @foreach($audit_files as $f)
                                                    @if($c->id == $f->criterion_id)
                                                        <a href="/api/getAuditScan/{{$f->name}}" download id="zdjecie_{{$f->id}}">Zdjęcie{{$i}}</a>
                                                        <?php
                                                        $i++;
                                                        ?>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($audit_files as $f)
                                                @if($c->id == $f->criterion_id)
                                                    <span class="glyphicon glyphicon-remove gl-rem" id="{{$f->id}}" onclick='removePhoto(this)'></span>
                                                    <?php
                                                    $i++;
                                                    ?>
                                                @endif
                                            @endforeach

                                        </td>
                                        <?php
                                        $i = 1;
                                        ?>
                                        <td>
                                            @foreach($audit_audios as $audio)
                                                @if($c->id == $audio->criterion_id)
                                                    <a href="/api/getAuditScan/{{$audio->name}}" download id="audio_{{$audio->id}}">Audio{{$i}}</a>
                                                    <?php
                                                    $i++;
                                                    ?>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($audit_audios as $audio)
                                                @if($c->id == $audio->criterion_id)
                                                    <span class="glyphicon glyphicon-remove gl-rem" id="{{$audio->id}}" onclick='removeAudio(this)'></span>
                                                    <?php
                                                    $i++;
                                                    ?>
                                                @endif
                                            @endforeach
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
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success final-alert">Wynik audytu to: </div>
            </div>
        </div>
    <div class="row last-row">
        <div class="col-md-12">
            <input type="button" class="btn btn-info btn-block" id="back_button" value="Powrót">
            <input class="btn btn-success btn-block" type="submit" id="secondButton" value="Zapisz zmiany!" style="margin-bottom:1em;">
        </div>
    </div>
    </form>
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Komentarz</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@section('script')
   <script>
       function removePhoto(e) {
           swal({
               title: 'Jesteś pewien?',
               text: "Po potwierdzeniu, brak możliwości cofnięcia zmian!",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Usuń zdjęcie!'
           }).then((result) => {
               if (result.value) {
               $.ajax({ //generate list of trainers from given location
                   type: "POST",
                   url: '{{ route('api.delete_picture') }}',
                   data: {
                       "id_picture": e.id
                   },
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                   success: function(response) {
                       if(response == 1){
                           document.getElementById('zdjecie_'+e.id).classList.add('inactive');
                           e.classList.add('inactive');
                           swal('Zdjęcie usunięto')
                       }
                       else
                           swal('Problem z usunięciem zdjęcia')
                   }
               });
           }
       });
       }

       function removeAudio(e) {
           swal({
               title: 'Jesteś pewien?',
               text: "Po potwierdzeniu, brak możliwości cofnięcia zmian!",
               type: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Usuń audio!'
           }).then((result) => {
               if (result.value) {
               $.ajax({ //generate list of trainers from given location
                   type: "POST",
                   url: '{{ route('api.delete_picture') }}',
                   data: {
                       "id_picture": e.id
                   },
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                   success: function(response) {
                       if(response == 1){
                           document.getElementById('audio_'+e.id).classList.add('inactive');
                           e.classList.add('inactive');
                           swal('Plik audio został usunięty')
                       }
                       else
                           swal('Problem z usunięciem zdjęcia')
                   }
               });
           }
       });
       }


    $(document).ready(function() {

        var submitButton = document.getElementById('secondButton');
        submitButton.addEventListener('click', submitHandler);

        /**
         * Event Listener function responsible for submiting form.
         */
        function submitHandler(e) {
            e.preventDefault();
            var everythingIsOk = true; //true = form submits, false = form doesn't submit
            var firstInp = document.getElementsByClassName('firstInp');
            // var secondInp = document.getElementsByClassName('secondInp');
            var thirdInp = document.getElementsByClassName('thirdInp');

            /**
             * Check if every "amount" input is selected
             */
            for(var i = 0; i < firstInp.length; i++) {
                if(firstInp[i].value == 0) {
                    everythingIsOk = false;
                    break;
                }
            }

            /**
             * check if every "quality" input is selected
             */
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
                    if(thirdInp[k].value == null || thirdInp[k].value == '') {
                        everythingIsOk = false;
                        break;
                    }
                }
            }

            //Validation of required inputs
            if(everythingIsOk != true) {
                swal('Wypełnij wszystkie pola w kolumnach "Tak/Nie" i "Komentarz"');
            }

            if(everythingIsOk == true) {

                var auditScore = 0;
                var numberOfRows = 0;
                var percentAuditScore;
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

        //THIS PART HIDES ALL HEADERS WHICH ARE AVAILABLE AT THE MOMENT BUT WERE NOT AVAILABLE WHEN AUDIT WAS ADDED
        var allTables = document.getElementsByClassName('table');
        for(var i = 0; i < allTables.length; i++) {
            if(allTables[i].lastElementChild.childElementCount === 0) {
                allTables[i].style.display = 'none';
                allTables[i].nextSibling.parentNode.firstElementChild.style.display='none';
            }
        }

        var auditScore = 0;
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

        $('.final-alert').append('<strong>' + auditScore + '</strong>' + '/' + numberOfRows + ' (' + (Math.round((100 * auditScore)/numberOfRows *100) / 100)+ '%)');

        let modalTriggers = Array.from(document.getElementsByClassName('modal_trigger'));
        modalTriggers.forEach(function(trigger) {
           trigger.addEventListener('click', function(e) {
               document.getElementsByClassName('modal-body')[0].textContent = document.getElementById(e.target.parentNode.dataset.info).value;
               console.log(e.target.parentNode.dataset.info);
           });
        });

        //THIS PART IS RESPONSIBLE FOR REDIRECTING BACK USER AFTER CLICKING ON BUTTON
        let back_button = document.getElementById('back_button');
        back_button.addEventListener('click', function(e) {
            window.location.href = '{{URL::to("/showAudits")}}';
        });




    });
    </script>
@endsection
