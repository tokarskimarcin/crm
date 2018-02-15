@extends('layouts.main')
@section('content')

@if(Session::has('candidate_data'))
@php
    $candidate = Session::get('candidate_data');
    $candidate_first_name = $candidate->first_name;
    $candidate_last_name = $candidate->last_name;
    $candidate_phone = $candidate->phone;
    $candidate_comment = $candidate->comment;
    Session::forget('candidate_data');
@endphp

@endif
{{--Header page --}}
<div class="col-md-12">
    <hr>
    @if (Session::has('message_ok'))
        <br />
        <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
    @endif
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Profil Pracownika</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="post" action="add_consultant" id="consultant_add">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-md-12">

                    <div class=" col-md-6 col-lg-6 ">
                        <table class="table table-user-information">
                            <tbody>
                            <b style="font-size: 20px; font-family: sans-serif;">Dane Osobowe</b>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Imię:</b></td>
                                <td>
                                    <input type="text" class="form-control" name="first_name" placeholder="Imię" value="@isset($candidate_first_name){{$candidate_first_name}}@endif">
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_name">
                                <td colspan="1">Podaj imie!</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Nazwisko:</b></td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Nazwisko" name="last_name"  value="@isset($candidate_last_name){{$candidate_last_name}}@endif">
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_last_name">
                                <td colspan="1">Podaj nazwisko!</td>
                                <td></td>
                            </tr>
                            @if($type == 2)
                            <tr>
                                <td class="td-class"><b>E-mail:</b></td>
                                <td>
                                    <input class="form-control" type="mail" class="form-control" placeholder="Email" name="email"  value="">
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_email">
                                <td colspan="1">Podaj adres email!</td>
                                <td></td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_email_checked">
                                <td colspan="1">Adres email jest zajęty!</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Telefon służbowy:</b></td>
                                <td>
                                    <input type="number" pattern="[0-9]*" class="form-control" placeholder="format: 000000000" name="phone" value="">
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Telefon prywatny:</b></td>
                                <td>
                                    <input type="number" pattern="[0-9]*" class="form-control" placeholder="format: 000000000" name="private_phone" value=@isset($candidate_phone) {{$candidate_phone}} @endif>
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_user_phone">
                                <td colspan="1">Podaj telefon pracownika!</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Login(Godzinówka):</b></td>
                                <td><input type="text" class="form-control" placeholder="Login" name="username" value=""></td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_user_name">
                                <td colspan="1">Podaj login!</td>
                                <td></td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_user_name_checked">
                                <td colspan="1">Użytkownik o podanej nazwie już istnieje!</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Hasło:</b></td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Hasło" name="password"  value="">
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_pass">
                                <td colspan="1">Podaj hasło!</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Dokumenty:</b></td>
                                <td>
                                    <select class="form-control" style="font-size:18px;" name="documents" >
                                        <option>Wybierz</option>
                                        <option value="1">Tak</option>
                                        <option value="0">Nie</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_documents">
                                <td colspan="1">Wybierz jedną z opcji!</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Student:</b></td>
                                <td>
                                    <select class="form-control" style="font-size:18px;" name="student">
                                        <option>Wybierz</option>
                                        <option value="1">Tak</option>
                                        <option value="0">Nie</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_student">
                                <td colspan="1">Wybierz status studenta!</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Agencja:</b></td>
                                <td>
                                    <select class="form-control" style="font-size:18px;" name="agency_id" >
                                        <option>Wybierz</option>
                                        @foreach($agencies as $agency)
                                            <option value="{{$agency->id}}">{{$agency->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_agency">
                                <td colspan="1">Wybierz agencję!</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class=" col-md-6 col-lg-6 ">
                        <table class="table table-user-information">
                            <tbody>
                            <b style="font-size: 20px; font-family: sans-serif;">Informacje cd</b>
                            <br>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Data rozpoczęcia pracy</b></td>
                                <td>
                                  <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                      <input class="form-control" name="start_date" type="text" value="{{date("Y-m-d")}}" readonly >
                                      <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Stawka na godzine:</b></td>
                                <td>
                                    <select class="form-control" style="font-size:18px;" name="rate" >
                                        <option>Nie dotyczy</option>
                                        @for ($i = 7.00; $i <=14; $i+=0.5)
                                            <option value="{{number_format ($i,2)}}">{{number_format ($i,2)}}</option>
                                        @endfor
                                    </select>
                                </td>
                            </tr>
                            @if($type == 2)
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Wynagrodzenie:</b></td>
                                <td>
                                    <input type="number" class="form-control" placeholder="0" name="salary" value="">
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Dodatek służbowy:</b></td>
                                <td>
                                    <input type="number" class="form-control" placeholder="0" name="additional_salary" value="">
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td style="width: 170px;height:52px;"><b>CNK:</b></td>
                                <td>
                                    <select class="form-control" style="font-size:18px;" name="salary_to_account">
                                        <option>Wybierz</option>
                                        <option value="1">Tak</option>
                                        <option value="0">Nie</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_ck">
                                <td colspan="1">Wybierz wartość CNK!</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Numer kolejki PBX:</b></td>
                                <td>
                                    <input type="number" class="form-control" placeholder="Login z programu do dzwonienia" name="login_phone" value="">
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_pbx">
                                <td colspan="1">Podaj unikalny numer kolejki PBX!</td>
                                <td></td>
                            </tr>
                            @if($type == 2)
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Uprawnienia</b></td>
                                <td>
                                  <select class="form-control" style="font-size:18px;" name="user_type">
                                      <option>Wybierz</option>
                                      @foreach($user_types as $item)
                                          <option value={{$item->id}}>{{$item->name}}</option>
                                      @endforeach
                                  </select>
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_user_type">
                                <td colspan="1">Wybierz uprawnienia!</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Oddział:</b></td>
                                <td style="width: 170px;height:52px;">
                                  <select class="form-control" style="font-size:18px;" name="department_info">
                                      <option>Wybierz</option>
                                      @foreach($department_info as $item)
                                          @if($item->id != 13)
                                          <option value={{$item->id}}>{{$item->departments->name.' '.$item->department_type->name}}</option>
                                          @endif
                                      @endforeach
                                  </select>
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_department">
                                <td colspan="1">Wybierz oddział!</td>
                                <td></td>
                            </tr>
                            @endif
                            @if($type == 1)
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Typ:</b></td>
                                <td style="width: 170px;height:52px;">
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
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_dating_type">
                                <td colspan="1">Wybierz typ użytkownika!</td>
                                <td></td>
                            </tr>

                            @endif
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Opis</b></td>
                                <td>
                                    <textarea class="form-control" name="description" placeholder="Opis dodawany do pracownika np. z jakiego ogłoszenia o pracę">@if(isset($candidate_comment)){{$candidate_comment}}@endif</textarea>
                                </td>
                            </tr>
                            <tr class="alert alert-danger" style="display: none" id="alert_desc">
                                <td colspan="1">Podaj dowolny opis!</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="submit" value="Dodaj pracownika" class="btn btn-success" id="add_consultant"/>
                </div>
            </form>
            </div>

</div>
</div>

@endsection
@section('script')

<script>

   

    $(document).ready(function() {

        $('.form_date').datetimepicker({
            language:  'pl',
            autoclose: 1,
            minView : 2,
            pickTime: false,
        });

        var validation_user = false;

        $("#add_consultant").click(function () {
            //tutaj var dating_type
            var first_name = $("input[name='first_name']").val();
            var last_name = $("input[name='last_name']").val();
            var password =$("input[name='password']").val();
            var username =$("input[name='username']").val();
            var private_phone =$("input[name='private_phone']").val();
            var login_phone =$("input[name='login_phone']").val();
            var description =$("textarea[name='description']").val();
            var documents =$("select[name='documents']").val();
            var student =$("select[name='student']").val();
            var salary_to_account =$("select[name='salary_to_account']").val();
            var rate =$("select[name='rate']").val();
            var agency =$("select[name='agency_id']").val();
            var start_date =$("input[name='start_date']").val();
            var email =$("input[name='email']").val();
            var user_type =$("select[name='user_type']").val();
            var dating_type =$("select[name='dating_type']").val();
            var department_info =$("select[name='department_info']").val();

            $('#consultant_add').submit(function(){
                validation_user = true;
                $(this).find(':submit').attr('disabled','disabled');
            });

            if (validation_user == true) {
                $("#add_consultant").attr('disabled', true);
            }

            var validationCheck = true;

            if (username.trim().length == 0) {
                $('#alert_user_name').fadeIn(1000);
                validationCheck = false;
            }else
            {
                var check = 0;
                $.ajax({
                    type: "POST",
                    async: false,
                    url: '{{ route('api.uniqueUsername') }}',
                    data: {"username":username},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response == 1)
                            check = 1;
                    }
                });
                if(check == 1) {
                    $('#alert_user_name_checked').fadeIn(1000);
                    validationCheck = false;
                    return false;
                } else {
                    $('#alert_user_name').fadeOut(1000);
                    $('#alert_user_name_checked').fadeOut(1000);
                    validationCheck = true;
                }
            }

            if (email != null) {
                if (email.trim().length == 0) {
                    $('#alert_email').fadeIn(1000);
                    validationCheck = false;
                }else
                {
                    var check = 0;
                    if(email.length > 0) {
                        $.ajax({
                            type: "POST",
                            async: false,
                            url: '{{ route('api.uniqueEmail') }}',
                            data: {"email":email},
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if(response == '1')
                                    check = 1;
                            }
                        });
                        if(check == 1) {
                            $('#alert_email_checked').fadeIn(1000)
                            validationCheck = false;
                        } else {
                            $('#alert_email').fadeOut(1000);
                            $('#alert_email_checked').fadeOut(1000);
                            validationCheck = true;
                        }
                    }
                }
            }

            //Sprawdzenie czy numer kolejki pbx jest unikalny
            if (login_phone != null) {
                if (login_phone.trim().length == 0) {
                    $('#alert_pbx').fadeIn(1000);
                    validationCheck = false;
                }else
                {
                    var check = 0;
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
                                swal('Podany numer kolejki PBX jest już w bazie!');
                                validationCheck = false;
                            } else if(response != 1 && response != 0) {
                                swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!');
                            }
                        }
                    });
                }
            }

            if (first_name.trim().length == 0) {
                $('#alert_name').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_name').fadeOut(1000);
            }
            if (last_name.trim().length == 0) {
                $('#alert_last_name').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_last_name').fadeOut(1000);
            }
            if (password.trim().length == 0) {
                $('#alert_pass').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_pass').fadeOut(1000);
            }
            if (documents == 'Wybierz') {
                $('#alert_documents').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_documents').fadeOut(1000);
            }
            if (agency == 'Wybierz') {
                $('#alert_agency').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_agency').fadeOut(1000);
            }
            if (student == 'Wybierz') {
                $('#alert_student').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_student').fadeOut(1000);
            }
            if (salary_to_account == 'Wybierz') {
                $('#alert_ck').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_ck').fadeOut(1000);
            }
            if (rate == 'Wybierz') {
                swal("Musisz wybrać Stawkę!")
                validationCheck = false;
            }
            if (login_phone.trim().length == 0) {
                $('#alert_pbx').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_pbx').fadeOut();
            }
            if (private_phone.trim().length == 0 || isNaN(private_phone)) {
                $('#alert_user_phone').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_user_phone').fadeOut(1000);
            }
            if (description.trim().length == 0) {
                $('#alert_desc').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_desc').fadeOut(1000);
            }
            if (login_phone.trim().length == 0) {
                $('#alert_pbx').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_pbx').fadeOut(1000);
            }
            if (user_type == 'Wybierz') {
                $('#alert_user_type').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_user_type').fadeOut(1000);
            }
            if (department_info == 'Wybierz') {
                $('#alert_department').fadeIn(1000);
                validationCheck = false;
            } else {
                $('#alert_department').fadeOut(1000);
            }
            if (dating_type == 'Wybierz') {
                $('#alert_dating_type').fadeIn(1000);
                validationCheck = false;
            }else {
                $('#alert_dating_type').fadeOut(1000);
            }

            return validationCheck;
        });
    });

</script>
@endsection
