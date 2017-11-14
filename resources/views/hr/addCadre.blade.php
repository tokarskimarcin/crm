@extends('layouts.main')
@section('content')


{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Panel pracownika(Kadra)</h1>
        </div>
    </div>

    @if(isset($saved))
        <div class="alert alert-success">
            <strong>Sukces!</strong> Dodano użytkownika: {{$saved['first_name'] . ' ' . $saved['last_name']}}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    Status Pracy
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">
                                    <form class="form-horizontal" method="post" action="add_consultant">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="col-md-4">
                                                <label for="">Imię:<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" name="first_name" placeholder="Imię">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Dokument:<span style="color:red;">*</span></label>
                                                <select class="form-control" style="font-size:18px;" name="documents" >
                                                    <option>Wybierz</option>
                                                    <option value="1">Tak</option>
                                                    <option value="0">Nie</option>
                                                    </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Data Rozpoczęcia Pracy:<span style="color:red;">*</span></label>
                                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                    <input class="form-control" name="start_date" type="text" value="{{date("Y-m-d")}}" readonly >
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Nazwisko:<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" placeholder="Nazwisko" name="last_name"  value="">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Agencja:<span style="color:red;">*</span></label>
                                                <select class="form-control" style="font-size:18px;" name="agency_id" >
                                                    <option>Wybierz</option>
                                                    @foreach($agencies as $agency)
                                                        <option value="{{$agency->id}}">{{$agency->name}}</option>
                                                    @endforeach
                                                    </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Stawka na godzinę:<span style="color:red;">*</span></label>
                                                <select class="form-control" style="font-size:18px;" name="rate" >
                                                    <option>Wybierz</option>
                                                    <option>Nie dotyczy</option>
                                                    @for ($i = 7.00; $i <=14; $i+=0.5)
                                                        <option value="{{number_format ($i,2)}}">{{number_format ($i,2)}}</option>
                                                    @endfor
                                                  </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Login:<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" placeholder="Login" name="username" value="">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Student:<span style="color:red;">*</span></label>
                                                <select class="form-control" style="font-size:18px;" name="student">
                                                    <option>Wybierz</option>
                                                    <option value="1">Tak</option>
                                                    <option value="0">Nie</option>
                                                   </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Hasło:<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" placeholder="Hasło" name="password"  value="">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Całość na konto:<span style="color:red;">*</span></label>
                                                <select class="form-control" style="font-size:18px;" name="salary_to_account">
                                                    <option>Wybierz</option>
                                                    <option value="1">Tak</option>
                                                    <option value="0">Nie</option>
                                                    </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Numer Telefonu:<span style="color:red;">*</span></label>
                                                <input type="number" pattern="[0-9]*" class="form-control" placeholder="format: 000000000" name="phone" value="">
                                            </div>

                                        <div class="col-md-4">
                                            <label>Oddział:<span style="color:red;">*</span></label>
                                            <select class="form-control" style="font-size:18px;" name="department_info">
                                                <option>Wybierz</option>
                                                @foreach($department_info as $item)
                                                    <option value={{$item->id}}>{{$item->department_name.' '.$item->department_type_name}}</option>
                                                @endforeach
                                            </select>
                                            </div>

                                        <div class="col-md-4">
                                            <label>Uprawnienia:<span style="color:red;">*</span></label>
                                            <select class="form-control" style="font-size:18px;" name="user_type">
                                                <option>Wybierz</option>
                                                @foreach($user_types as $item)
                                                    <option value={{$item->id}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                            <div class="col-md-8">
                                                <label>Login z programu do dzwonienia:<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" placeholder="Login z programu do dzwonienia" name="login_phone" value="">
                                            </div>
                                            <div class="col-md-12">
                                                <label>Opis:<span style="color:red;">*</span></label>
                                                <textarea class="form-control" placeholder="Opis dodawany do pracownika np. z jakiego osłoszenia o pracę, od kogo z polecenia itp." name="description"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1" style="padding: 5px 0;"><span style="color:red;">*</span> - wymagane pola.</label>

                                                <input type="submit" class="btn btn-primary" style="font-size:18px; width:100%;" value="Dodaj Pracownika">
                                            </div>
                                        </form>
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
    $(document).ready(function() {

        $('.form_date').datetimepicker({
            language:  'pl',
            autoclose: 1,
            minView : 2,
            pickTime: false,
        });

        $("#add_consultant").click(function () {

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

            var department_info =$("select[name='department_info']").val();
            var user_type =$("select[name='user_type']").val();


            if (department_info == '') {
                alert("Pole oddział nie może być puste!");
                return false;
            }
            if (user_type == '') {
                alert("Pole uprawnienia nie może być puste!");
                return false;
            }
            if (first_name == '') {
                alert("Pole Imię nie może być puste!");
                return false;
            }
            if (last_name == '') {
                alert("Pole Nazwisko nie może być puste!");
                return false;
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
            if (login_phone == '') {
                alert("Musisz wprowadzić login pracownika z programu do dzwonienia!");
                return false;
            }

        });
    });

</script>
@endsection
