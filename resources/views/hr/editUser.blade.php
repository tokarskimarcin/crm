@extends('layouts.main')
@section('content')

<style>
    .myLabel {
        font-size: 20px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Dział HR / Profil pracownika / {{$user->first_name . ' ' . $user->last_name}}</div>
        </div>
    </div>
</div>

@if (Session::has('message_edit'))
    <div class="alert alert-success">{{ Session::get('message_edit') }}</div>
@endif

@if(Session::has('candidate_data'))
    @php
        $candidate = Session::get('candidate_data');
        $candidate_id = $candidate->id;
        Session::forget('candidate_data');
    @endphp
@endif
<div class="row">
    <div class="col-md-12">
        <form method="post" action="{{URL::to('/edit_cadre/')}}/{{$user->id}}" id="consultant_add"  enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Profil pracownika
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Imie:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Imie" value="{{$user->first_name}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Nazwisko:</label>
                                <input type="text" class="form-control" id="last_name" placeholder="Nazwisko" name="last_name"  value="{{$user->last_name}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Login godzinówka:</label>
                                <input readonly type="text" class="form-control" id="username" placeholder="Login" name="username"  value="{{$user->username}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="myLabel">Telefon prywatny:</label>
                                <input type="number" pattern="[0-9]*" class="form-control" id="private_phone" placeholder="format: 000000000" name="private_phone" value="{{$user->private_phone}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="myLabel">Hasło:</label>
                                <input type="text" class="form-control" placeholder="Hasło" id="password" name="password"  value="{{base64_decode($user->guid)}}">
                            </div>
                        </div>
                    </div>
                    @if($type == 1)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="myLabel" for="recomended">Polecony przez</label>
                                <select class="form-control" style="font-size:18px;" name="recomended" id="recomended">
                                    @foreach($recomendingPeople as $rp)
                                      <option>{{$rp->first_name . " " . $rp->last_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label class="myLabel" for="responsible_for">Prowadzący</label>
                              <select class="form-control" style="font-size:18px;" name="responsible_for" id="responsible_for">
                                <option value="c">a</option>
                                <option value="d">b</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Dokumenty:</label>
                                <select class="form-control" style="font-size:18px;" id="documents" name="documents" >
                                    <option value="1" @if($user->documents == 1) selected @endif>Tak</option>
                                    <option value="0" @if($user->documents == 0) selected @endif>Nie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Student:</label>
                                <select class="form-control" style="font-size:18px;" id="student" name="student">
                                    <option value="1" @if($user->student == 1) selected @endif>Tak</option>
                                    <option value="0" @if($user->student == 0) selected @endif>Nie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Agencja:</label>
                                <select class="form-control" style="font-size:18px;" id="agency_id" name="agency_id" >
                                    <option>Wybierz</option>
                                        @foreach($agencies as $agency)
                                            <option value="{{$agency->id}}" @if($user->agency_id == $agency->id) selected @endif>{{$agency->name}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">CNK:</label>
                                <select class="form-control" style="font-size:18px;" id="salary_to_account" name="salary_to_account">
                                    <option value="1" @if($user->salary_to_account == 1) selected @endif>Tak</option>
                                    <option value="0" @if($user->salary_to_account == 0) selected @endif>Nie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Data rozpoczęcia pracy:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control" id="start_date" name="start_date" type="text" value="{{$user->start_work}}" readonly >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Data zakończenia pracy:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    @if(isset($user->end_work))
                                        <input class="form-control stop_date" id="stop_date" name="stop_date" type="text" value="{{$user->end_work}}" readonly >
                                    @else
                                        <input class="form-control stop_date" id="stop_date" name="stop_date" type="text" value="0000-00-00" readonly >
                                    @endif

                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="myLabel">Stawka na godzine:</label>
                                <select class="form-control" style="font-size:18px;" id="rate" name="rate" >
                                    <option>Nie dotyczy</option>
                                    @for ($i = 7.00; $i <=14; $i+=0.5)
                                        <option value="{{number_format ($i,2)}}" @if($user->rate == $i) selected @endif>{{number_format ($i,2)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="myLabel">Aktualnie zatrudniony:</label>
                                <select class="form-control"  style="font-size:18px;" name="status_work" id="status_work">
                                    <option @if($user->status_work == 1) selected @endif value="1">Tak</option>
                                    <option @if($user->status_work == 0) selected @endif value="0">Nie</option>
                                </select>
                            </div>
                        </div>
                    </div>
                        @if($type == 2)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Adres email:</label>
                                    <input class="form-control" type="mail" class="form-control" placeholder="Email" id="email" name="email"  value="{{$user->email_off}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Telefon służbowy:</label>
                                    <input type="number" pattern="[0-9]*" class="form-control" placeholder="format: 000000000" id="phone" name="phone" value="{{$user->phone}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Wynagrodzenie:</label>
                                    @if(isset($user->salary))
                                        <input type="number" class="form-control" placeholder="0" name="salary" value="{{$user->salary}}">
                                    @else
                                        <input type="number" class="form-control" placeholder="0" name="salary" value="0">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Dodatek służbowy:</label>
                                    <input type="number" class="form-control" placeholder="0" name="additional_salary" value="{{$user->additional_salary}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Uprawnienia:</label>
                                    <select class="form-control" style="font-size:18px;" id="user_type" name="user_type">
                                        @foreach($userTypes as $user_type)
                                            <option value="{{$user_type->id}}" @if($user_type->id == $user->user_type_id) selected @endif>{{$user_type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Oddział:</label>
                                    <select class="form-control" style="font-size:18px;" id="department_info" name="department_info_id">
                                        <option>Wybierz</option>
                                        @foreach($department_info as $dep)
                                            @if($dep->id != 13)
                                                <option @if($dep->id == $user->main_department_id) selected @endif value="{{$dep->id}}">{{$dep->departments->name . ' ' . $dep->department_type->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($type == 1)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="myLabel">Typ:</label>
                                    <select class="form-control" style="font-size:18px;" name="dating_type" id="dating_type">
                                        @if(Auth::user()->department_info->type == 'Badania')
                                            <option value="0">Badania</option>
                                        @elseif(Auth::user()->department_info->type == 'Wysyłka')
                                            <option value="1">Wysyłka</option>
                                        @else
                                            <option value="0" @if($user->dating_type == 0) selected @endif>Badania</option>
                                            <option value="1" @if($user->dating_type == 1) selected @endif>Wysyłka</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                    <div class="row">
                        @if($type == 1)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Numer PBX:</label>
                                <input type="number" class="form-control" placeholder="Login z programu do dzwonienia" id="login_phone" name="login_phone" value="{{$user->login_phone}}">
                            </div>
                        </div>
                        @endif
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="myLabel">Opis:</label>
                                <textarea class="form-control" name="description" id="description" placeholder="Opis dodawany do pracownika np. z jakiego ogłoszenia o pracę">{{$user->description}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(isset($penalty_bonuses[1]))
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Suma kar/premii ({{$month[0]}}):</label>
                                    <input disabled type="number" class="form-control" placeholder="0" name="additional_salary_2nd_month" value="{{$penalty_bonuses[0][0]->premia - $penalty_bonuses[0][0]->kara}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Suma kar/premii ({{$month[1]}}):</label>
                                    <input disabled type="number" class="form-control" placeholder="0" name="additional_salary_2nd_month" value="{{$penalty_bonuses[1][0]->premia - $penalty_bonuses[1][0]->kara}}">
                                </div>
                            </div>
                        @elseif(isset($penalty_bonuses[0]))
                            <div class="col-md-12">
                                <div class="form-group"><label class="myLabel">Suma kar/premii ({{$month[1]}}):</label></div>
                                <input disabled type="number" class="form-control" placeholder="0" name="additional_salary_2nd_month" value="{{$penalty_bonuses[0][0]->premia - $penalty_bonuses[0][0]->kara}}">
                            </div>
                        @endif
                    </div>
                    @if($user->medicalPackages->where('deleted', '=', 0)->count() == 0)
                        @include('hr.addMedicalPackage')
                    @else
                        @include('hr.editMedicalPackage')
                    @endif
                    <div class="col-md-12">
                        <div class="form-group">
                            <button class="btn btn-success text-center" style="width: 100%" id="add_submit">
                                <span class="glyphicon glyphicon-ok"></span> Zapisz zmiany
                            </button>
                        </div>
                    </div>
                </div>
                <!-- End panel-body  -->
            </div>
            <!-- End panel panel-default -->
            <input type="hidden" name="candidate_id" value="@if(isset($candidate_id)) {{$candidate_id}} @endif">
        </form>
    </div>
    <!-- End col-md-12 -->
</div>
<!-- End row -->

<div class="panel panel-info" style="width: 100%">
    <div class="panel-heading">
        <h3 class="panel-title">Kary i Premie</h3>
    </div>

    <div class="panel-body">
        @include('hr.userPenaltyBonus')
    </div>
</div>

<div class="panel panel-info" style="width: 100%">
    <div class="panel-heading">
        <h3 class="panel-title">Posiadany Sprzęt</h3>
    </div>

    <div class="panel-body">
        @include('hr.userEquipment')
    </div>
</div>

@endsection
@section('script')

<script>

    /** Do edycji pakietu medycznego **/
    var showEditMedical = false;
    var medicalStatus = Number('{{$user->medicalPackages->where("deleted", '=', 0)->count()}}');
    var medicalScanIsSet = (medicalStatus == 0) ? false : true;

    $('#edit_medical_package').click(function(e) {
        e.preventDefault();

        if (showEditMedical == true) {
            $('#edit_medical_data').fadeOut(0);
            $('#span_edit_medical').removeClass('glyphicon-minus').addClass('glyphicon-plus');
            $('#edit_span_message').html('Edytuj pakiet medyczny');
            $('#medical_package_active').val(0);
            showEditMedical = false;
        } else {
            $('#edit_medical_data').fadeIn(0);
            $('#span_edit_medical').removeClass('glyphicon-plus').addClass('glyphicon-minus');
            $('#edit_span_message').html('Cofnij edycję pakietu');
            $('#medical_package_active').val(1);
            showEditMedical = true;
        }
    });

    //Usuwanie całości pakietu medycznego
    $('#delete_all_packages').click(function (e) {
        e.preventDefault();
        var medical_stop = $('#medical_stop').val();
        var user_id = Number('{{$user->id}}');
        swal({
            title: '',
            text: "Usunąć pakiet medyczny?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.deleteMedicalPackage') }}',
                    data: {
                        "medical_stop":medical_stop,
                        "user_id":user_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        if (response == 1) {
                            swal('Usunięto pakiet medyczny!');
                            window.location.reload();
                        } else {
                            swal('Ups! Coś poszło nie tak, skontatkuj się z admnistratorem!');
                        }
                    }, error: function(response) {
                        swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!');
                    }
                });
            }
        })
    });

    /** Koniec edycji pakietu medycznego **/

    /******** Do pakietu medycznego *********/
    //Suma członków w pakiecie
    var totalMemberSum = 0;

    function totalMemberCounter(diff, forceValue = null) {
        if (forceValue === null) {
            totalMemberSum += diff;
        } else {
            totalMemberSum = forceValue;
        }

        $('#totalMemberSum').val(totalMemberSum);
    }

    //Usunięcie danego członka
    function deleteMember(id) {
        $('#member' + id).remove();
        totalMemberCounter(-1);
    }
    //Usunięcie starego członka
    function deleteOldMember(id) {
        $('#oldmember' + id).remove();
        totalMemberCounter(-1);
    }
    /************* Koniec pakietu medycznego *************/

$(document).ready(function() {

    var user_id = Number({{$user->id}});

    //Zabokowanie przesyłania formularza po naciścnięciu entera (rozwijał się przycisk z pakietem medycznym)
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    var checkEndWorkStatus = Number({{$user->status_work}});
    var checkEndWorkDate = '{{$user->end_work}}';

    $('#status_work').change(function() {
        //Sprawdzenie czy zmieniany jest status pracy na "niepracujący"
        var status_work = $("#status_work").val();
        if (checkEndWorkStatus == 1 && status_work == 0) {
            swal('Wybierz datę zakończenia pracy');
            $('#stop_date').val('{{date("Y-m-d")}}');
        }
    });

    var medicalPackageShow = false;

    // Obsługa pokazania/ukrycia panelu pakietu medycznego
    $('#add_medical_package').click(function(e) {
        e.preventDefault();

        if (medicalPackageShow == true) {
            $('#add_medical_package_div').slideUp();
            $('#span_medical').removeClass('glyphicon-minus').addClass('glyphicon-plus');
            $('#medical_package_active').val(0);
            medicalPackageShow = false;
        } else {
            $('#add_medical_package_div').slideDown();
            $('#span_medical').removeClass('glyphicon-plus').addClass('glyphicon-minus');
            $('#medical_package_active').val(1);
            medicalPackageShow = true;
        }
    });



    $('#add_submit').click((e) => {

        //Pobranie danych z inputów
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var username = $('#username').val();
        var private_phone = $('#private_phone').val();
        var password = $('#password').val();
        var documents = $('#documents').val();
        var student = $('#student').val();
        var agency_id = $('#agency_id').val();
        var salary_to_account = $('#salary_to_account').val();
        var start_date = $('#start_date').val();
        var stop_date = $('#stop_date').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var user_type = $('#user_type').val();
        var department_info = $('#department_info').val();
        var login_phone = $('#login_phone').val();
        var description = $('#description').val();

        if (first_name.trim().length == 0) {
            swal('Podaj imie!');
            return false;
        }

        if (last_name.trim().length == 0) {
            swal('Podaj nazwisko!');
            return false;
        }

        if (username.trim().length == 0) {
            swal('Podaj login do godzinówki!');
            return false;
        }

        if (private_phone.trim().length == 0) {
            swal('Podaj numer telefonu!');
            return false;
        }

        if (password.trim().length == 0) {
            swal('Podaj hasło!');
            return false;
        }

        if (documents == 'Wybierz') {
            swal('Wybierz dokument!');
            return false;
        }

        if (student == 'Wybierz') {
            swal('Wybierz status studenta!');
            return false;
        }

        if (agency_id == 'Wybierz') {
            swal('Wybierz agencję!');
            return false;
        }

        if (salary_to_account == 'Wybierz') {
            swal('Wybierz wartość CNK!');
            return false;
        }

        if (login_phone != null && login_phone.trim().length == 0) {
            swal('Podaj numer kolejki PBX!');
            return false;
        }

        if (description.trim().length == 0) {
            swal('Podaj opis pracownika!');
            return false;
        }

        if (phone != null && phone.trim().length == 0) {
            swal('Podaj telefon służbowy!');
            return false;
        }

        if (email != null && email.trim().length == 0) {
            swal('Podaj adres email!');
            return false;
        }

        if (user_type != null && user_type == 'Wybierz') {
            swal('Wybierz uprawnienia!');
            return false;
        }

        if (department_info != null && department_info == 'Wybierz') {
            swal('Wybierz oddział!');
            return false;
        }

        //
        var ajaxCheck = true;

        if (email != null) {
            $.ajax({
                type: "POST",
                async: false,
                url: '{{ route('api.uniquerEmailEdit') }}',
                data: {
                    "email":email,
                    "user_id": user_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    if (response  == 1) {
                        swal('Ten email jest już zajęty!');
                        ajaxCheck = false;
                    }
                }
            });
        }

        if (ajaxCheck == false) {
            return false;
        }

        //Porównanie czy data zakończenia jest wyższa niż data rozpoczęcia
        var d1 = Date.parse(stop_date);
        var d2 = Date.parse(start_date);

        if (d1 < d2) {
            swal('Data zakończenia pracy nie może być niższa niż data jej rozpoczęcia!');
            return false;
        }

        //** Dodanie procesu walidacji dla pakietu medycznego **/
        @include('hr.medicalPackageValidation')

        $('#add_submit').attr('disabled', true);
        $('#consultant_add').submit();
    });

    $('.form_date').datetimepicker({
        language:  'pl',
        autoclose: 1,
        minView : 2,
        pickTime: false,
    });
    $('.medical_date').datetimepicker({
        language:  'pl',
        autoclose: 1,
        minView : 1,
        pickTime: false,
    });

    $( ".delete" ).click(function() {
        var id = (this.id);

        swal({
            title: '',
            text: "Czy napewno chcesz usunąć karę/premię?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tak'
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.deletePenaltyBonus') }}',
                    data: {
                        "id": id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response == 1) {
                            swal("Pomyślnie usunięto karę/premię!")
                            $('tr[name=' + id + ']').fadeOut(0);
                        } else {
                            swal('Ups! Coś poszło nie tak. Skontaktuj się z administratorem!')
                            return;
                        }
                    }
                });
            }
        })
    });

    var validation = false;
    $("#addpbsubmit").click(function () {

        var penalty_type = $("select[name='penalty_type']").val();
        var cost = $("input[name='cost']").val();
        var reason = $("input[name='reason']").val();

        $('#pb').submit(function(){
            validation = true;
            $(this).find(':submit').attr('disabled','disabled');
        });

        if (validation == true) {
            $("#addpbsubmit").attr('disabled', true);
        }

        if (penalty_type == "Wybierz") {
            $('#alert_select').slideDown(1000);
            validation = false;
            return false;
        } else {
            $('#alert_select').slideUp(1000);
        }

        if (cost.trim().length == 0) {
            $('#alert_value').slideDown(1000);
            validation = false;
            return false;
        } else {
            $('#alert_value').slideUp(1000);
        }

        if (cost <= 0) {
            $('#alert_value_plus').slideDown(1000);
            validation = false;
            return false;
        } else {
            $('#alert_value_plus').slideUp(1000);
        }

        if (reason.trim().length == 0) {
            $('#alert_reason').slideDown(1000);
            validation = false;
            return false;
        } else {
            $('#alert_select').slideUp(1000);
        }

    });


    $('#myModal').on('hidden.bs.modal', function () {
        $("#modal_content").empty();
    });

    $(".equipment_data").on('click', function(){
        var id = this.id;

        var equipment_id_database= $(this).data('equipment_id_database');
        var equipment_type_id= $(this).data('equipment_type_id');
        var laptop_processor= $(this).data('laptop_processor');
        var laptop_ram= $(this).data('laptop_ram');
        var laptop_hard_drive= $(this).data('laptop_hard_drive');
        var phone_box= $(this).data('phone_box');
        var tablet_modem= $(this).data('tablet_modem');
        var sim_number_phone= $(this).data('sim_number_phone');
        var sim_type= $(this).data('sim_type');
        var sim_pin= $(this).data('sim_pin');
        var sim_puk= $(this).data('sim_puk');
        var sim_net= $(this).data('sim_net');
        var model= $(this).data('model');
        var serial_code= $(this).data('serial_code');
        var description= $(this).data('description');
        var power_cable= $(this).data('power_cable');
        var signal_cable= $(this).data('signal_cable');
        var to_user= $(this).data('to_user');

        var equipmentArray = [
          equipment_type_id,
          laptop_processor,
          laptop_ram,
          laptop_hard_drive,
          phone_box,
          tablet_modem,
          sim_number_phone,
          sim_type,
          sim_pin,
          sim_puk,
          sim_net,
          model,
          serial_code,
          description,
          power_cable,
          signal_cable,
          to_user
        ];

        var newElements = [];
      console.log(typeof(equipment_id_database));

        if(equipment_type_id != '') {
            equipment_type_id = $("<tr><td><b>" + "Rodzaj sprzętu" + "</b></td><td>" + equipment_type_id + "</td></tr>");
            newElements.push(equipment_type_id);
        }

        if(model != '' || !isNaN(model)) {
            model = $("<tr><td><b>" + "Model" + "</b></td><td>" + model + "</td></tr>");
            newElements.push(model);
        }

        if(serial_code != '' || !isNaN(serial_code)) {
            serial_code = $("<tr><td><b>" + "Numer seryjny" + "</b></td><td>" + serial_code + "</td></tr>");
            newElements.push(serial_code);
        }

        if(description != '' || !isNaN(description)) {
            description = $("<tr><td><b>" + "Opis" + "</b></td><td>" + description + "</td></tr>");
            newElements.push(description);
        }


        //dla laptopów
        if (equipment_id_database == 1) {
            if(laptop_processor != '' || !isNaN(laptop_processor)) {
                laptop_processor = $("<tr><td><b>" + "Rodzaj procesora" + "</b></td><td>" + laptop_processor + "</td></tr>");
                newElements.push(laptop_processor);
            }

            if(laptop_ram != '' || !isNaN(laptop_ram)) {

                laptop_ram = $("<tr><td><b>" + "Pamięć RAM" + "</b></td><td>" + laptop_ram + "</td></tr>");
                newElements.push(laptop_ram);
            }

            if(laptop_hard_drive != '' || !isNaN(laptop_hard_drive)) {
                laptop_hard_drive = $("<tr><td><b>" + "Dysk twardy" + "</b></td><td>" + laptop_hard_drive + "</td></tr>");
                newElements.push(laptop_hard_drive);
            }
        }

        //dla telefonów/tabletow

        if (equipment_id_database == 3) {
            if(phone_box != '' || !isNaN(phone_box)) {
                phone_box = 'Tak';
                phone_box = $("<tr><td><b>" + "Opakowanie na telefon" + "</b></td><td>" + phone_box + "</td></tr>");
                newElements.push(phone_box);
            }
        }

        //dla SIM
        if (equipment_id_database == 4) {

            if(tablet_modem != '' || !isNaN(tablet_modem)) {
                tablet_modem = 'Tak';
                tablet_modem = $("<tr><td><b>" + "Modem" + "</b></td><td>" + tablet_modem + "</td></tr>");
                newElements.push(tablet_modem);
            }

            if(sim_number_phone != '' || !isNaN(sim_number_phone)) {
                sim_number_phone = $("<tr><td><b>" + "Numer telefonu" + "</b></td><td>" + sim_number_phone + "</td></tr>");
                newElements.push(sim_number_phone);
            }

            if(sim_type != '') {
                sim_type = (sim_type == 1) ? 'Prepaid' : 'Abonament' ;
                sim_type = $("<tr><td><b>" + "Rodzaj karty SIM" + "</b></td><td>" + sim_type + "</td></tr>");
                newElements.push(sim_type);
            }

            if(sim_pin != '' || !isNaN(sim_pin)) {
                sim_pin = $("<tr><td><b>" + "Numer PIN" + "</b></td><td>" + sim_pin + "</td></tr>");
                newElements.push(sim_pin);
            }

            if(sim_puk != '' || !isNaN(sim_puk)) {
                sim_puk = $("<tr><td><b>" + "Numer PUK" + "</b></td><td>" + sim_puk + "</td></tr>");
                newElements.push(sim_puk);
            }

            if(sim_net != '' || !isNaN(sim_net)) {
                sim_net = (sim_type == "1") ? 'Tak' : 'Nie' ;
                sim_net = $("<tr><td><b>" + "Internet" + "</b></td><td>" + sim_net + "</td></tr>");
                newElements.push(sim_net);
            }
        }

        if (equipment_id_database != 4) {
            if(power_cable != '' || !isNaN(power_cable)) {
                power_cable = (power_cable == 0) ? "Nie" : "Tak" ;
                power_cable = $("<tr><td><b>" + "Kabel zasilający" + "</b></td><td>" + power_cable + "</td></tr>");
                newElements.push(power_cable);
            }
        }


        //dla monitorow
        if (equipment_id_database == 5) {
            if(signal_cable != '' || !isNaN(signal_cable)) {
                signal_cable = (signal_cable == 0) ? 'Nie' : 'Tak' ;
                signal_cable = $("<tr><td><b>" + "Antena" + "</b></td><td>" + signal_cable + "</td></tr>");
                newElements.push(signal_cable);
            }
        }

        if(to_user != '') {
            to_user = $("<tr><td><b>" + "Data wydania" + "</b></td><td>" + to_user + "</td></tr>");
            newElements.push(to_user);
        }

        $.each(newElements, function(key, value) {
            $("#modal_content").append(
                  value
              );
        });
    });

});

</script>

    @include('hr.medicalPackageAddTemplates')
@endsection
