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
                    <div class="alert alert-warning"><p>Zdjęcia mogą być <i>tylko</i> w formatach: <strong>.pdf</strong> <strong>.jpg</strong> <strong>.jpeg</strong> <strong>.png</strong>.</p></div>
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
                                <th>Zdjęcia</th>
                                <th>Pliki audio</th>
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
                                                        <input type="text" id="{{$c->name . "_comment"}}" name="{{$c->name . "_comment"}}" class="form-control thirdInp" style="width:100%;" value="{{$a->comment}}">
                                                        @else
                                                        <input type="text" id="{{$c->name . "_comment"}}" name="{{$c->name . "_comment"}}" class="form-control thirdInp" style="width:100%;" value="">
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td> <a data-toggle="modal" data-info="{{$c->name . "_comment"}}" class="modal_trigger" href="#myModal"><span class="glyphicon glyphicon-search"></span></a></td>
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
            </div>
        </div>
    {{--<div class="row last-row">--}}
        {{--<div class="col-md-12">--}}
            {{--<input class="btn btn-success btn-block" type="submit" id="secondButton" value="Zapisz zmiany!" style="margin-bottom:1em;">--}}
        {{--</div>--}}
    {{--</div>--}}
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


    $(document).ready(function() {

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
