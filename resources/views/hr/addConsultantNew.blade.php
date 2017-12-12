@extends('layouts.main')
@section('content')


{{--Header page --}}
<div class="col-md-12">
    <hr>
    @if (Session::has('message_ok'))
        <br />
        <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
    @endif
    <!-- <div class="alert alert-danger" role="alert">Niestety ta funkcja nie jest jeszcze dostępna.</div> -->
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Profil Pracownika</h3>
        </div>



        <div class="panel-body">
            <!-- <div class="col-md-2 col-lg-2 " align="center"> <img alt="User Pic" src="http://saintgeorgelaw.com/wp-content/uploads/2015/01/male-formal-business-hi.png" class="img-circle img-responsive" style="border:2px solid #222;"> </div> -->
            <form class="form-horizontal" method="post" action="add_consultant" id="consultant_add"><!-- Formularz edycji kadry -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-md-10">

                    <div class=" col-md-6 col-lg-6 ">
                        <table class="table table-user-information">
                            <tbody>
                            <b style="font-size: 20px; font-family: sans-serif;">Dane Osobowe</b>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Imię:</b></td>
                                <td>
                                    <input type="text" class="form-control" name="first_name" placeholder="Imię" value="">
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Nazwisko:</b></td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Nazwisko" name="last_name"  value="">
                                </td>
                            </tr>
                            @if($type == 2)
                            <tr>
                                <td class="td-class"><b>E-mail:</b></td>
                                <td>
                                    <div class="input-group">
                                        <input type="mail" class="form-control" placeholder="Email" name="email"  value="">
                                        <span class="input-group-addon" style="padding: 0px"><small><b>@veronaconsulting.pl</b></small></span>
                                    </div>
                                </td>
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
                                    <input type="number" pattern="[0-9]*" class="form-control" placeholder="format: 000000000" name="private_phone" value="">
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Login(Godzinówka):</b></td>
                                <td><input type="text" class="form-control" placeholder="Login" name="username" value=""></td>

                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Hasło:</b></td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Hasło" name="password"  value="">
                                </td>

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
                                <td style="width: 170px;height:52px;"><b>Dodatel służbowy:</b></td>
                                <td>
                                    <input type="number" class="form-control" placeholder="0" name="additional_salary" value="">
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Całość na konto:</b></td>
                                <td>
                                    <select class="form-control" style="font-size:18px;" name="salary_to_account">
                                        <option>Wybierz</option>
                                        <option value="1">Tak</option>
                                        <option value="0">Nie</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Login PBX:</b></td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Login z programu do dzwonienia" name="login_phone" value="">
                                </td>
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
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Oddział:</b></td>
                                <td style="width: 170px;height:52px;">
                                  <select class="form-control" style="font-size:18px;" name="department_info">
                                      <option>Wybierz</option>
                                      @foreach($department_info as $item)
                                          <option value={{$item->id}}>{{$item->departments->name.' '.$item->department_type->name}}</option>
                                      @endforeach
                                  </select>
                                </td>
                            </tr>
                            @endif
                            @if($type == 1)
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Typ:</b></td>
                                <td style="width: 170px;height:52px;">
                                  <select class="form-control" style="font-size:18px;" name="dating_type" id="dating_type">
                                      <option>Wybierz</option>
                                      <option value="0">Badania</option>
                                      <option value="1">Wysyłka</option>
                                  </select>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td style="width: 170px;height:52px;"><b>Opis</b></td>
                                <td>
                                    <textarea class="form-control" name="description" placeholder="Opis dodawany do pracownika np. z jakiego ogłoszenia o pracę"></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="submit" value="Zapisz zmiany" class="btn btn-success" id="add_consultant"/>
                </div>
            </form>
            </div>

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
            var phone =$("input[name='phone']").val();
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


            if (username == '') {
                alert("Pole Login nie może być puste!");
                return false;
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
                        if(response == '1')
                            check = 1;
                    }
                });
                if(check == 1) {
                    alert("Użytkownik o podanej nazwie już istnieje");
                    return false;
                }
            }

            if (email == '') {
                alert("Pole e-mail nie może być puste!");
                return false;
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
                        alert("Adres email jest zajęty!");
                        return false;
                    }
                }

            }

            //tutaj if() dating type

            if (first_name == '') {
                alert("Pole Imię nie może być puste!");
                return false;
            }
            if (last_name == '') {
                alert("Pole Nazwisko nie może być puste!");
                return false;
            }
            if (password== '') {
                alert("Pole Hasło nie może być puste!");
                return false;
            }
            if (documents == 'Wybierz') {
                alert("Musisz wybrać Dokument!");
                return false;
            }
            if (agency == 'Wybierz') {
                alert("Musisz wybrać Agencję!");
                return false;
            }
            if (student == 'Wybierz') {
                alert("Musisz wybrać status Studenta!");
                return false;
            }
            if (salary_to_account == 'Wybierz') {
                alert("Musisz wybrać wartość CK!");
                return false;
            }
            if (rate == 'Wybierz') {
                alert("Musisz wybrać Stawkę!");
                return false;
            }
            if (login_phone == '') {
                alert("Musisz wpisać login do programu dzwoniącego");
                return false;
            }
            if (phone == '') {
                alert("Wprowadź telefon pracownika! Jeśli nie posiadasz telefonu wprowadź 0.");
                return false;
            }
            if (description == '') {
                alert("Musisz wprowadzić dowolny opis!");
                return false;
            }
            if (username == '') {
                alert("Musisz wprowadzić login pracownika z programu do dzwonienia!");
                return false;
            }
            if (user_type == 'Wybierz') {
                alert("Musisz wybrać uprawnienia!");
                return false;
            }
            if (department_info == 'Wybierz') {
                alert("Musisz wybrać oddział!");
                return false;
            }
            if (dating_type == 'Wybierz') {
                alert("Musisz wybrać typ użytkownika!");
                return false;
            }

        });
    });

</script>
@endsection
