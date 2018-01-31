@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        color: #aaa;
        font-size: 20px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Dodaj kandydata</div>
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
                                        <span id="name_surname">Imie Nazwisko</span>
                                    </b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <b class="myLabel">Status rekrutacji:</b>
                                    <p class="myLabel">
                                        <span id="user_status">Brak</span>
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
                                        <input type="text" class="form-control" placeholder="Imie" id="candidate_name"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="myLabel">Nazwisko:</label>
                                        <input type="text" class="form-control" placeholder="Nazwisko" id="candidate_surname"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Numer telefonu:</label>
                                        <input type="text" class="form-control" placeholder="000000000" id="candidate_phone"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Oddział:</label>
                                        <select class="form-control" id="candidate_department">
                                            <option>Wybierz</option>
                                            @foreach($department_info as $item)
                                                <option value="{{$item->id}}">{{$item->departments->name . ' ' . $item->department_type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="myLabel">Źródło:</label>
                                        <select class="form-control" id="candidate_source">
                                            <option>Wybierz</option>
                                            @foreach($sources as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
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
                                        <select class="form-control" id="candidate_level">
                                            @foreach($status as $item)
                                                <option @if($item->id == 1) selected @else disabled @endif value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel"></label>
                                        <button class="btn btn-info" style="width: 100%" id="add_submit">  
                                            <span class="glyphicon glyphicon-ok"></span> Dodaj kandydata
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Opis:</label>
                                    <textarea rows="5" style="height: 100%" class="form-control" placeholder="Opis pracownika" id="candidate_desc"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
<script>

$(document).ready(() => {
    $('#add_submit').click(() => {
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

        $('#add_submit').attr('disabled', true);

        //Dodanie nowego kandydata
        $.ajax({
            type: "POST",
            url: '{{ route('api.addNewCandidate') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_name": candidate_name,
                "candidate_surname": candidate_surname,
                "candidate_phone": candidate_phone,
                "candidate_department": candidate_department,
                "candidate_source": candidate_source,
                "candidate_desc": candidate_desc
            },
            success: function (response) {
                if (response > 0) {
                    swal({
                        title: 'Kandydat został dodany!',
                        type: 'success',
                        timer: 3000
                    }).then((result) => {
                        window.location.href = "{{ URL::to('/candidateProfile/') }}/" + response;
                    })
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
