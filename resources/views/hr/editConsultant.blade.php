@extends('layouts.main')
@section('content')


    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Panel pracownika {{$user->first_name.' '.$user->last_name}} </h1>
        </div>
    </div>

    <div class="user_id" id={{$user->username}}>
    </div>
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
                                    <form class="form-horizontal" method="post" action="/edit_consultant/{{$user->id}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="col-md-4">
                                            <label for="">Imię:<span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" name="first_name" placeholder="Imię" value={{$user->first_name}}>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Nazwisko:<span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" placeholder="Nazwisko" name="last_name"  value={{$user->last_name}}>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Dokument:<span style="color:red;">*</span></label>
                                            <select class="form-control" style="font-size:18px;" name="documents" >
                                                <option>Wybierz</option>
                                                @if($user->documents == 1)
                                                    <option selected value="1">Tak</option>
                                                    <option value="0">Nie</option>
                                                @endif
                                                @if($user->documents == 0)
                                                    <option value="1">Tak</option>
                                                    <option selected value="0">Nie</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Data Rozpoczęcia Pracy:<span style="color:red;">*</span></label>
                                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                <input class="form-control" name="start_date" type="text" value={{$user->start_work}} readonly >
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Data Zakończenia Pracy:<span style="color:red;">*</span></label>
                                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                @if($user->end_work == null)
                                                    <input class="form-control" name="end_work" type="text" value={{$user->end_work}}  >
                                                @else
                                                    <input class="form-control" name="end_work" type="text" value={{$user->end_work}} readonly  >
                                                @endif
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Agencja:<span style="color:red;">*</span></label>
                                            <select class="form-control" style="font-size:18px;" name="agency_id" >
                                                <option>Wybierz</option>
                                                @foreach($agencies as $agency)
                                                    @if($user->agency_id == $agency->id)
                                                        <option selected value="{{$agency->id}}">{{$agency->name}}</option>
                                                    @else
                                                        <option value="{{$agency->id}}">{{$agency->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Stawka na godzinę:<span style="color:red;">*</span></label>
                                            <select class="form-control" style="font-size:18px;" name="rate" >
                                                <option>Wybierz</option>
                                                @if($user->rate == 0)
                                                    <option selected>Nie dotyczy</option>
                                                @else
                                                    <option>Nie dotyczy</option>
                                                @endif
                                                @for ($i = 7.00; $i <=14; $i+=0.5)
                                                    @if($user->rate == $i)
                                                        <option selected value="{{number_format ($i,2)}}">{{number_format ($i,2)}}</option>
                                                    @else
                                                        <option value="{{number_format ($i,2)}}">{{number_format ($i,2)}}</option>
                                                    @endif

                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Login:<span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" placeholder="Login" name="username" value={{$user->username}}>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Student:<span style="color:red;">*</span></label>
                                            <select class="form-control" style="font-size:18px;" name="student">
                                                <option>Wybierz</option>
                                                @if($user->student == 1)
                                                    <option selected value="1">Tak</option>
                                                    <option value="0">Nie</option>
                                                @endif
                                                @if($user->student == 0)
                                                    <option selected value="0">Nie</option>
                                                    <option value="1">Tak</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Hasło:<span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" placeholder="Hasło" name="password">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Całość na konto:<span style="color:red;">*</span></label>
                                            <select class="form-control" style="font-size:18px;" name="salary_to_account">
                                                <option>Wybierz</option>
                                                @if($user->salary_to_account == 1)
                                                    <option selected value="1">Tak</option>
                                                    <option value="0">Nie</option>
                                                @endif
                                                @if($user->salary_to_account == 0)
                                                    <option selected value="0">Nie</option>
                                                    <option value="1">Tak</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Numer Telefonu:<span style="color:red;">*</span></label>
                                            <input type="number" pattern="[0-9]*" class="form-control" placeholder="format: 000000000" name="phone" value={{$user->phone}}>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Login z programu do dzwonienia:<span style="color:red;">*</span></label>
                                            <input type="text" class="form-control" placeholder="Login z programu do dzwonienia" name="login_phone" value={{$user->login_phone}}>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Status pracy:<span style="color:red;">*</span></label>
                                            <select class="form-control" style="font-size:18px;" name="status_work">
                                                <option>Wybierz</option>
                                                @if($user->status_work == 1)
                                                    <option selected value="1">Pracuje</option>
                                                    <option value="0">Nie Pracuje</option>
                                                @endif
                                                @if($user->status_work == 0)
                                                    <option selected value="0">Nie Pracuje</option>
                                                    <option value="1">Pracuje</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Opis:<span style="color:red;">*</span></label>
                                            <textarea class="form-control" placeholder="Opis dodawany do pracownika np. z jakiego osłoszenia o pracę, od kogo z polecenia itp." name="description" >{{$user->description}}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="exampleInputPassword1" style="padding: 5px 0;"><span style="color:red;">*</span> - wymagane pola.</label>

                                            <input type="submit" name="edit_user_consultant" id="edit_consultant" class="btn btn-primary" style="font-size:18px; width:100%;" value="Edytuj Pracownika">
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
            var user = document.getElementsByClassName('user_id')[0].id;

            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false,
            });

            $("#edit_consultant").click(function () {

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
                var end_work =$("input[name='end_work']").val();
                var status_work = $("select[name='status_work']").val();
                if(status_work == 0)
                {
                    if(end_work == ''){
                        alert("Wprowadź datę zakończenia pracy");
                        return false;
                    }

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
                }else if(username != user)
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

            });
        });

    </script>
@endsection
