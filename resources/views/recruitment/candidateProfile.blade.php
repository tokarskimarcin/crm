@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        color: #aaa;
        font-size: 20px;
    }
    .modal {
        text-align: center;
        padding: 0!important;
      }
      
      .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px; /* Adjusts for spacing */
      }
      
      .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
      }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Profil kandydata</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Profil kandydata
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="row">
                            <div class="col-md-12">
                                <span style="font-size: 150px; color: #aaa" class="glyphicon glyphicon-user"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <b class="myLabel">
                                        <span id="name_surname">{{$candidate->first_name . ' ' . $candidate->last_name}}</span>
                                    </b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <b class="myLabel">Status rekrutacji:</b>
                                    <p class="myLabel">
                                        <span id="user_status">Do zrobienia</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Imie:</label>
                                        <input type="text" class="form-control" id="candidate_name" placeholder="Imie" value="{{$candidate->first_name}}"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Nazwisko:</label>
                                        <input type="text" class="form-control" id="candidate_surname" placeholder="Nazwisko" value="{{$candidate->last_name}}"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Numer telefonu:</label>
                                        <input type="text" class="form-control" id="candidate_phone" placeholder="000000000" value="{{$candidate->phone}}"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Oddział:</label>
                                        <select class="form-control" id="candidate_department">
                                            @foreach($department_info as $item)
                                                <option @if($item->id == $candidate->department_info_id) selected @endif value="{{$item->id}}">{{$item->departments->name . ' ' . $item->department_type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Źródło:</label>
                                        <select class="form-control" id="candidate_source">
                                            @foreach($sources as $item)
                                                <option @if($item->id == $candidate->candidate_source_id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel">Status rekrutacji:</label>
                                        <input type="text" class="form-control status_input" value="To do"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel"></label>
                                        <button class="btn btn-info" style="width: 100%" id="edit_submit">  
                                            <span class="glyphicon glyphicon-envelope"></span> Zapisz zmiany
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Opis:</label>
                                    <textarea rows="5" style="height: 100%" class="form-control" id="candidate_desc" placeholder="Opis pracownika">{{$candidate->comment}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($candidate->recruitment_attempt->where('status', '=', 0)->count() == 0)
    <div class="form-group">
        <button class="btn btn-success" style="width: 100%" data-toggle="modal" data-target="#newRecruitment">
            <span class="glyphicon glyphicon-ok"></span> Przeprowadź rekrutację
        </button>
    </div>
@endif

@php
    $i = 0;
@endphp
@foreach($candidate->recruitment_attempt as $item)
@php
    $i++;
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Rektutacja nr {{$i}} @if($item->status == 1) (Zakończona) @endif
            </div>
            <div class="panel-body">
        
                <ul class="nav nav-tabs" style="margin-bottom: 25px">
                    @php
                        $y = 0;
                    @endphp
                    @foreach($item->recruitment_story as $story)
                    @php
                        $y++;
                    @endphp
                        <li @if($y == 1) class="active" @endif><a data-toggle="tab" href="#story{{$story->id}}">Etap {{$y}}</a></li>
                    @endforeach
                </ul>
                
                <div class="tab-content">
                    @php
                        $y = 0;
                    @endphp
                    @foreach($item->recruitment_story as $story)
                    @php
                        $y++;
                    @endphp
                        <div id="story{{$story->id}}" class="tab-pane fade in @if($y == 1) active @endif">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{$story->attemptLevel->name}}
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="myLabel">Rekruter:</label>
                                                <input type="text" class="form-control" readonly value="{{$story->cadre->first_name . ' ' . $story->cadre->last_name}}"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="myLabel">Data:</label>
                                                <input type="text" class="form-control" value="{{$story->updated_at}}" readonly/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="myLabel">Etap rekrutacji:</label>
                                                <input type="text" class="form-control" readonly value="{{$story->attemptLevel->name}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button data-toggle="modal" data-target="#nextLevel" class="btn btn-success" style="width: 100%" @if($item->status == 1) disabled @endif>  
                                                        <span class="glyphicon glyphicon-ok"></span> Następny etap
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button data-toggle="modal" data-target="#add_training" class="btn btn-warning" style="width: 100%" @if($item->status == 1) disabled @endif>  
                                                        <span class="glyphicon glyphicon-envelope"></span> Zapisz na szkolenie
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button data-toggle="modal" data-target="#stopRecruitment" class="btn btn-danger" style="width: 100%" @if($item->status == 1) disabled @endif>  
                                                        <span class="glyphicon glyphicon-remove"></span> Zakończ rekrutację
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="myLabel">Komentarz:</label>
                                                <textarea rows="4" class="form-control" placeholder="Komentarz">{{$story->comment}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach


<div id="newRecruitment" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dodaj etap rekrutacji</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="myLabel">Wybierz etap rekrutacji:</label>
                            <select class="form-control" id="new_recruitment_status">
                                @foreach($status as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="myLabel">Komentarz:</label>
                            <textarea class="form-control" rows="5" placeholder="Dodaj komentarz..." id="new_recruitment_comment"></textarea>
                        </div>
                        <div class="form-group">
                            <button style="width: 100%" class="btn btn-success" id="new_recruitment_submit">
                                <span class="glyphicon glyphicon-ok"></span> Rozpocznij rekrutację
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknj</button>
            </div>
        </div>
    </div>
</div>

<div id="stopRecruitment" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Zakończ rekrutację</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="myLabel">Etap rekrutacji:</label>
                            <input class="form-control" id="stop_recruitment_status" value="Zakończenie rekrutacji" readonly>
                        </div>
                        <div class="form-group">
                            <label class="myLabel">Powód:</label>
                            <textarea class="form-control" rows="5" placeholder="Dodaj komentarz..." id="stop_recruitment_comment"></textarea>
                        </div>
                        <div class="form-group">
                            <button style="width: 100%" class="btn btn-danger" id="stop_recruitment_submit">
                                <span class="glyphicon glyphicon-ok"></span> Zakończ rekrutację
                            </button>
                        </div>
                        <div class="form-group">
                            <button style="width: 100%" class="btn btn-success" id="stop_recruitment_add">
                                <span class="glyphicon glyphicon-ok"></span> Zakończ i dodaj jako konsultanta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknj</button>
            </div>
        </div>
    </div>
</div>

<div id="nextLevel" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dodaj etap rekrutacji</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="myLabel">Wybierz etap rekrutacji:</label>
                            <select class="form-control" id="add_level_status">
                                @foreach($status as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="myLabel">Komentarz:</label>
                            <textarea class="form-control" rows="5" placeholder="Dodaj komentarz..." id="add_level_comment"></textarea>
                        </div>
                        <div class="form-group">
                            <button style="width: 100%" class="btn btn-success" id="add_level_submit">
                                <span class="glyphicon glyphicon-ok"></span> Dodaj etap
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknj</button>
            </div>
        </div>
    </div>
</div>

<div id="add_training" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dodaj etap rekrutacji</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="myLabel">Etap rekrutacji:</label>
                            <input class="form-control" value="Zapis na szkolenie" readonly>
                        </div>
                        <div class="form-group">
                            <label class="myLabel">Komentarz:</label>
                            <textarea class="form-control" rows="5" placeholder="Dodaj komentarz..." id="add_training_comment"></textarea>
                        </div>
                        <div class="form-group">
                            <button style="width: 100%" class="btn btn-warning" id="add_training_submit">
                                <span class="glyphicon glyphicon-ok"></span> Zapisz na szkolenie
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknj</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="candidate_id" value="{{$candidate->id}}" />
@endsection
@section('script')
<script>
$(document).ready(() => {
    $('#edit_submit').click(() => {
        var candidate_id = $('#candidate_id').val();
        var candidate_name = $('#candidate_name').val();
        var candidate_surname = $('#candidate_surname').val();
        var candidate_phone = $('#candidate_phone').val();
        var candidate_department = $('#candidate_department').val();
        var candidate_source = $('#candidate_source').val();
        var candidate_desc = $('#candidate_desc').val();

        if (candidate_name == '') {
            swal('Podaj imie kandydata!')
            return false;
        }

        if (candidate_surname == '') {
            swal('Podaj nazwisko kandydata!')
            return false;
        }

        if (candidate_phone == '') {
            swal('Podaj telefon kandydata!')
            return false;
        } else if (isNaN(candidate_phone) || (candidate_phone.length < 8)) {
            swal('Podaj prawidłowy numer telefonu!')
            return false;
        }

        if (candidate_department == 'Wybierz') {
            swal('Wybierz oddział!')
            return false;
        }

        if (candidate_source == 'Wybierz') {
            swal('Wybierz żródło!')
            return false;
        }

        if (candidate_desc == '') {
            swal('Dodaj opis kandydata!')
            return false;
        }

        //Edycja danych kandydata
        $.ajax({
            type: "POST",
            url: '{{ route('api.editCandidate') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_id": candidate_id,
                "candidate_name": candidate_name,
                "candidate_surname": candidate_surname,
                "candidate_phone": candidate_phone,
                "candidate_department": candidate_department,
                "candidate_source": candidate_source,
                "candidate_desc": candidate_desc
            },
            success: function (response) {
                if (response == 1) {
                    swal('Zmiany zapisano!')
                    var newName = candidate_name + " " + candidate_surname;
                    $('#name_surname').html(newName);
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
    });

    $('#new_recruitment_submit').click(() => {
        var candidate_id = $('#candidate_id').val();
        var new_recruitment_status = $('#new_recruitment_status').val();
        var new_recruitment_comment = $('#new_recruitment_comment').val();

        if (new_recruitment_comment == '') {
            swal('Dodaj komentarz!')
            return false;
        }

        $.ajax({
            type: "POST",
            url: '{{ route('api.startNewRecruitment') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_id": candidate_id,
                "new_recruitment_status": new_recruitment_status,
                "new_recruitment_comment": new_recruitment_comment
            },
            success: function (response) {
                if (response == 1) {
                    swal('Rekrutacja została rozpoczęta!')
                    location.reload();
                } else if (response == 2) {
                    swal('Pracownik ma już rozpoczętą rekrutację!')
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
    });

    function stopRecruitment(stopType) {
        var candidate_id = $('#candidate_id').val();
        var stop_recruitment_status = $('#stop_recruitment_status').val();
        var stop_recruitment_comment = $('#stop_recruitment_comment').val();

        if (stop_recruitment_comment == '') {
            swal('Dodaj komentarz!')
            return false;
        }

        $.ajax({
            type: "POST",
            url: '{{ route('api.stopRecruitment') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_id": candidate_id,
                "stop_recruitment_status": 10,
                "stop_recruitment_comment": stop_recruitment_comment,
                "stopType": stopType
            },
            success: function (response) {
                if (response == 1) {
                    swal('Rekrutacja została zakończona!')
                    location.reload();
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
    }

    $('#stop_recruitment_submit').click(() => {
        var stopType = 0; // tutaj jezeli nie dodajemy jako konsultanta
        stopRecruitment(stopType);
    });

    $('#stop_recruitment_add').click(() => {
        var stopType = 1; // tutaj jezeli dodajemy jako konsultanta
        stopRecruitment(stopType);
    });

    $('#add_level_submit').click(() => {
        var candidate_id = $('#candidate_id').val();
        var add_level_status = $('#add_level_status').val();
        var add_level_comment = $('#add_level_comment').val();

        if (stop_recruitment_comment == '') {
            swal('Dodaj komentarz!')
            return false;
        }

        $.ajax({
            type: "POST",
            url: '{{ route('api.addRecruitmentLevel') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_id": candidate_id,
                "add_level_status": add_level_status,
                "add_level_comment": add_level_comment
            },
            success: function (response) {
                if (response == 1) {
                    swal('Etap został dodany!')
                    location.reload();
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
    });

    $('#add_training_submit').click(() => {
        var candidate_id = $('#candidate_id').val();
        var add_training_comment = $('#add_training_comment').val();

        if (stop_recruitment_comment == '') {
            swal('Dodaj komentarz!')
            return false;
        }

        $.ajax({
            type: "POST",
            url: '{{ route('api.addToTraining') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_id": candidate_id,
                "add_training_comment": add_training_comment,
                "add_level_status": 9
            },
            success: function (response) {
                if (response == 1) {
                    window.location.href = "{{ URL::to('/add_group_training') }}";
                } else {
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
    });
});
</script>
@endsection
