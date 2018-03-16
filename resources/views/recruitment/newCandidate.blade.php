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
                                <span style="font-size: 150px; color: #aaa" class="glyphicon glyphicon-user" onclick="easterEgg()"></span>
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
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="myLabel"></label>
                                <button class="btn btn-info" style="width: 100%" id="add_submit">
                                    <span class="glyphicon glyphicon-ok"></span> Dodaj kandydata
                                </button>
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
                                        <select class="form-control" id="candidate_department" disabled title="Możesz dodawać pracowników tylko do swojego oddziału.">
                                            <option>Wybierz</option>
                                            @foreach($department_info as $item)
                                                <option @if($item->id == Auth::user()->department_info_id) selected @endif value="{{$item->id}}">{{$item->departments->name . ' ' . $item->department_type->name}}</option>
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
                                        <input type="text" readonly class="form-control" value="Dodanie kandydata" id="candidate_level">
                                    </div>
                                </div>
                                {{--<div class="col-md-12">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="myLabel">Doświadczenie:</label>--}}
                                        {{--<select class="form-control" id="candidate_experience">--}}
                                            {{--<option value="0" selected>Brak</option>--}}
                                            {{--<option value="1">Tak</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Opis:</label>
                                    <textarea rows="5" style="height: 100%" class="form-control" placeholder="Opis pracownika" id="candidate_desc"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="myLabel">Były pracownik:</label>
                                        <select class="form-control" id="ex_candidate">
                                            <option value="0" selected>Nie</option>
                                            <option value="1">Tak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="find_user_table" style="display: none">
                                        <table id="all_user_fired" class="table table-striped thead-inverse">
                                            <thead>
                                            <tr>
                                                <th>Imie i nazwisko</th>
                                                <th class="category_column">Data rozpoczęcia</th>
                                                <th class="category_column">Data zakończenia</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($fired_user as $item)
                                                    <tr id={{$item->id}}>
                                                        <td>{{$item->first_name.' '.$item->last_name}}</td>
                                                        <td>{{$item->start_work}}</td>
                                                        <td>{{$item->end_work}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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
var easterEggCounter = 0;
var ex_id_user = null;
function easterEgg() {
    easterEggCounter++;

    if (easterEggCounter == 3) {
        var size = 100;
        var size2 = 1;
        var side = 1;
        setInterval(function(){ 
            $('input, textarea, select, span').css('width', size);
            $('input, textarea, select, span').css('height', size2);
            size += side;
            size2 += side;

            if (size >= 200 || size <= 0) {
                side *= -1;
            }
        }, 1);
        
    }
}

$(document).ready(() => {
    //Funkcja wyświetlająca tabele z wyborem byłego pracownika
    $('#ex_candidate').val(0);
    $('#ex_candidate').on('change',function () {
        if($(this).val() == 0)
        {
            $('.find_user_table').css("display","none");
            ex_id_user = null;
            fired_user_table.$('tr.selected').removeClass('selected');
        }else if($(this).val() == 1)
        {
            $('.find_user_table').css("display","block");
        }
});
    //tablela z byłymi pracownikami
    var fired_user_table = $('#all_user_fired').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
        }
    });

    // event, po kliknięciu wiersza w tabeli z byłymi pracownikami
    $('#all_user_fired').on('click','tr',function (e) {
        if($(this).hasClass('selected')){
            $(this).removeClass('selected');
            ex_id_user = null;
        }else{
            fired_user_table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            ex_id_user = fired_user_table.row('.selected').data()['DT_RowId'];
        }
    });


    //Funkcja dodajaca nowego kandydata
    $('#add_submit').click(() => {
        var candidate_name = $('#candidate_name').val();
        var candidate_surname = $('#candidate_surname').val();
        var candidate_phone = $('#candidate_phone').val();
        var candidate_department = $('#candidate_department').val();
        var candidate_source = $('#candidate_source').val();
        var candidate_desc = $('#candidate_desc').val();
        var candidate_experience = 0;
        var ex_candidate_id = $('#ex_candidate').val();
        if(ex_candidate_id == 1 && ex_id_user == null){
            swal('Wybierz byłego pracownika z listy')
            return false;
        }

        if (candidate_name == '' || (candidate_name.trim().length == 0)) {
            swal('Podaj imie kandydata!')
            return false;
        }

        if (candidate_surname == '' || (candidate_surname.trim().length == 0)) {
            swal('Podaj nazwisko kandydata!')
            return false;
        }

        if (candidate_phone == '') {
            swal('Podaj telefon kandydata!')
            return false;
        } else if (isNaN(candidate_phone) || (candidate_phone.length < 9)) {
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

        if (candidate_desc == '' || (candidate_desc.trim().length == 0)) {
            swal('Dodaj opis kandydata!')
            return false;
        }
        
        //Sprawdzenie czy istnieje użytkownik o podanym numerze telefonu
        $.ajax({
            type: "POST",
            url: '{{ route('api.uniqueCandidatePhone') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "candidate_phone": candidate_phone
            },
            success: function (response) {
                if (response == 0) {
                    //jezeli istenieje taki numer w bazie kandydatow
                    swal('Numer telefonu istnieje już w bazie!')                   
                } else if (response == 1) {
                    //Jezeli numer jest unikatowy - dodanie kandydata

                    //Zablokowanie przycisku
                    $('#add_submit').prop('disabled', true);

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
                            "candidate_desc": candidate_desc,
                            "candidate_experience": candidate_experience,
                            "ex_id_user": ex_id_user

                        },
                        success: function (response) {
                            if (response > 0) {
                                swal({
                                    title: 'Kandydat został dodany!',
                                    type: 'success',
                                    timer: 3000
                                }).then((result) => {
                                    //Przeniesienie do strony profilowej kandydata
                                    window.location.href = "{{ URL::to('/candidateProfile/') }}/" + response;
                                })
                            } else {
                                //W przypadku niepowodzenia dodania kandydata
                                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                            }
                        }, error: function(response) {
                            //W przypadku niepowodzenia dodania kandydata
                            swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                        }
                    });
                } else {
                    //W przypadku niepowodzenia w sprawdzeniu unikatowego numeru
                    swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
                }
            }, error: function(response) {
                //W przypadku niepowodzenia w sprawdzeniu unikatowego numeru
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        })
    });
});

</script>
@endsection
