@extends('layouts.main')
@section('content')

<style>
    .myLabel {
        font-size: 20px;
    }
</style>

@if(Session::has('candidate_data'))
    <!-- Te dane pobierane są jeżeli dodajemy pracownika z proflilu kandydata  -->
    @php
        $candidate = Session::get('candidate_data');
        $candidate_id = $candidate->id;
        $candidate_first_name = $candidate->first_name;
        $candidate_last_name = $candidate->last_name;
        $candidate_phone = $candidate->phone;
        $candidate_comment = $candidate->comment;
        Session::forget('candidate_data');
    @endphp
@endif

@php
    $add_type = ($type == 1) ? 'konsultanta' : 'pracownika kadry' ;
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Dział HR / Dodaj {{$add_type}}</div>
        </div>
    </div>
</div>

@if (Session::has('message_ok'))
    <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <form method="post" action="add_consultant" id="consultant_add" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Dodanie nowego pracownika
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Imie:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Imie" value="@isset($candidate_first_name){{$candidate_first_name}}@endif">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Nazwisko:</label>
                                <input type="text" class="form-control" id="last_name" placeholder="Nazwisko" name="last_name"  value="@isset($candidate_last_name){{$candidate_last_name}}@endif">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Login godzinówka:</label>
                                <input type="text" class="form-control" id="username" placeholder="Login" name="username" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="myLabel">Telefon prywatny:</label>
                                <input type="number" pattern="[0-9]*" class="form-control" id="private_phone" placeholder="format: 000000000" name="private_phone" value=@isset($candidate_phone) {{$candidate_phone}} @endif>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="myLabel">Hasło:</label>
                                <input type="text" class="form-control" placeholder="Hasło" id="password" name="password"  value="">
                            </div>
                        </div>

                        @if($type == 1)
                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label class="myLabel" for="recomended">Polecony przez:</label>
                                        <select class="form-control" style="font-size:18px;" name="recommended_by" id="recommended_by">
                                            <option value="0" id="none">Brak</option>
                                            @foreach($recomendingPeople as $rp)
                                                <option value="{{$rp->id}}">{{$rp->first_name . " " . $rp->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" hidden>
                                    <div class="form-group">
                                        <label class="myLabel" for="responsible_for">Prowadzący:</label>
                                        <select class="form-control" style="font-size:18px;" name="coach_id" id="responsible_for">
                                            <option value="0" id="noTrainer">--Wybierz prowadzącego--</option>
                                            @foreach($workingTreners as $wt)
                                                <option value="{{$wt->id}}" @if (isset($user->coach_id) && $user->coach_id == $wt->id) selected @endif>{{$wt->first_name . " " . $wt->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                        @endif

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Dokumenty:</label>
                                <select class="form-control" style="font-size:18px;" id="documents" name="documents" >
                                    <option>Wybierz</option>
                                    <option value="1">Tak</option>
                                    <option value="0">Nie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Student:</label>
                                <select class="form-control" style="font-size:18px;" id="student" name="student">
                                    <option>Wybierz</option>
                                    <option value="1">Tak</option>
                                    <option value="0">Nie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Agencja:</label>
                                <select class="form-control" style="font-size:18px;" id="agency_id" name="agency_id" >
                                    <option>Wybierz</option>
                                    @foreach($agencies as $agency)
                                        <option value="{{$agency->id}}">{{$agency->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">CNK:</label>
                                <select class="form-control" style="font-size:18px;" id="salary_to_account" name="salary_to_account">
                                    <option>Wybierz</option>
                                    <option value="1">Tak</option>
                                    <option value="0">Nie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Data rozpoczęcia pracy:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control" name="start_date" id="start_date" type="text" value="{{date("Y-m-d")}}" readonly >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Stawka na godzine:</label>
                                <select class="form-control" style="font-size:18px;" id="rate" name="rate" >
                                    <option>Nie dotyczy</option>
                                    @for ($i = 7.00; $i <=14; $i+=0.5)
                                        <option value="{{number_format ($i,2)}}">{{number_format ($i,2)}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        @if($type == 2)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Adres email:</label>
                                    <input class="form-control" type="mail" class="form-control" placeholder="Email" id="email" name="email"  value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Telefon służbowy:</label>
                                    <input type="number" pattern="[0-9]*" class="form-control" placeholder="format: 000000000" id="phone" name="phone" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Wynagrodzenie:</label>
                                    <input type="number" class="form-control" placeholder="0" name="salary" id="salary" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Dodatek służbowy:</label>
                                    <input type="number" class="form-control" placeholder="0" id="additional_salary" name="additional_salary" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Uprawnienia:</label>
                                    <select class="form-control" style="font-size:18px;" id="user_type" name="user_type">
                                        <option>Wybierz</option>
                                        @foreach($user_types as $item)
                                            <option value={{$item->id}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="myLabel">Oddział:</label>
                                    <select class="form-control" style="font-size:18px;" id="department_info" name="department_info">
                                        <option>Wybierz</option>
                                        @foreach($department_info as $item)
                                            @if($item->id != 13)
                                            <option value={{$item->id}}>{{$item->departments->name.' '.$item->department_type->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        @if($type == 1)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="myLabel">Typ:</label>
                                    <select class="form-control" style="font-size:18px;" name="dating_type" id="dating_type">
                                        @if(Auth::user()->department_info->type == 'Badania')
                                                <option value="0">Badania</option>
                                        @elseif(Auth::user()->department_info->type == 'Wysyłka')
                                                <option value="1">Wysyłka</option>
                                        @else
                                                <option>Wybierz</option>
                                                <option value="0">Badania</option>
                                                <option value="1">Wysyłka</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        @if($type == 1)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="myLabel">Numer PBX:</label>
                                    <input type="number" class="form-control" placeholder="000" id="login_phone" name="login_phone" value="">
                                </div>
                            </div>
                        @endif
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="myLabel">Opis:</label>
                                <textarea class="form-control" name="description" id="description" placeholder="Opis dodawany do pracownika np. z jakiego ogłoszenia o pracę">@if(isset($candidate_comment)){{$candidate_comment}}@endif</textarea>
                            </div>
                        </div>
                    </div>
                        @include('hr.addMedicalPackage')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="btn btn-success text-center" style="width: 100%" id="add_submit">
                                    <span class="glyphicon glyphicon-plus"></span> Dodaj pracownika
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End panel-body  -->
            </div>
            <!-- End panel panel-default -->
            <input type="hidden" name="candidate_id" value="@if(isset($candidate_id)) {{$candidate_id}} @endif">
        </form>
    </div>
</div>

@endsection
@section('script')

<script>
    /******** Do pakietu medycznego *********/
    //Suma członków w pakiecie
    var totalMemberSum = 0;
    var medicalScanIsSet = false;

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
    /************* Koniec pakietu medycznego *************/

$(document).ready(function() {

    //Zabokowanie przesyłania formularza po naciścnięciu entera (rozwijał się przycisk z pakietem medycznym) 
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    //Sprawdzenie czy pakiet medyczny jest dodawany
    var medicalPackageShow = false;

    // Obsługa dodania/odebrania pakietu medycznego 
    $('#add_medical_package').click(function(e) {
        e.preventDefault();

        if (medicalPackageShow == true) {
            $('#add_medical_package_div').fadeOut(0);
            $('#span_medical').removeClass('glyphicon-minus').addClass('glyphicon-plus');
            $('#span_medical_text').html('Dodaj pakiet medyczny');
            totalMemberCounter(0, 0);
            $('#medical_package_active').val(0);
            medicalPackageShow = false;
            $('#package_variable').val('Wybierz');
        } else {
            $('#add_medical_package_div').fadeIn(0);
            $('#span_medical').removeClass('glyphicon-plus').addClass('glyphicon-minus');
            $('#span_medical_text').html('Cofnij pakiet medyczny');
            $('#medical_package_active').val(1);
            medicalPackageShow = true;
        }
        
    });

    $('#add_submit').click((e) => {
        /************ Walidacja dla danych uzytkownika *****************/
        //Pobranie danych z inputów
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var username = $('#username').val();
        var private_phone = $('#private_phone').val();
        var password = $('#password').val();
        // var trainer = $('#responsible_for').val();
        var documents = $('#documents').val();
        var student = $('#student').val();
        var agency_id = $('#agency_id').val();
        var salary_to_account = $('#salary_to_account').val();
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

        // if (trainer == "0") {
        //     swal('Wybierz prowadzącego');
        //     return false;
        // }

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
        //ajax check
        var ajaxCheck = true;

        $.ajax({
            type: "POST",
            async: false,
            url: '{{ route('api.uniqueUsername') }}',
            data: {"username":username},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response == 1) {
                    ajaxCheck = false;
                    swal('Ten login jest już zajęty!');
                }
            }
        });

        if (email != null) {
            $.ajax({
                type: "POST",
                async: false,
                url: '{{ route('api.uniqueEmail') }}',
                data: {"email":email},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response == 1) {
                        ajaxCheck = false;
                        swal('Taki adres email jest już w bazie danych!');
                    }
                }
            });
        }

        if (login_phone != null) {
            $.ajax({
                type: "POST",
                async: false,
                url: '{{ route('api.uniquePBX') }}',
                data: {"login_phone":login_phone},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response == 1) {
                        ajaxCheck = false;
                        swal('Ten numer kolejki PBX jest już w bazie danych!');
                    }
                }
            });
        }

        if (ajaxCheck == false) {
            return false;
        }

        /*************** Koniec walidacji dla danych użytkownika*****************/

        /** Dodanie procesu walidacji dla pakietu medycznego **/
        @include('hr.medicalPackageValidation')

        $("#add_submit").attr("disabled", true);
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
        minView : 2,
        pickTime: false,
    });

});

</script>

    @include('hr.medicalPackageAddTemplates')
@endsection
