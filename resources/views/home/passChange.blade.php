@extends('layouts.main')
@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="page-header">
      <h1>Zmiana hasła</h1>
    </div>
  </div>
</div>


@if (Session::has('message_ok'))
   <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@elseif(Session::has('message_nok'))
  <div class="alert alert-danger">{{ Session::get('message_nok') }}</div>
@endif

<div class="row">
  <div class="col-md-6">
      <form method="POST" action="password_change" id="save_pass">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
              <label for="old_pass">Podaj stare hasło:</label>
              <input name="old_pass" id="old_pass" type="password" class="form-control" placeholder="Stare hasło"/>
          </div>
          <div class="form-group">
              <label for="new_pass">Podaj nowe hasło:</label>
              <input name="new_pass" id="new_pass" type="password" class="form-control" placeholder="Nowe hasło"/>
          </div>
          <div class="form-group">
              <label for="new_pass_confirm">Powtórz nowe hasło:</label>
              <input name="new_pass_confirm" id="new_pass_confirm" type="password" class="form-control" placeholder="Nowe hasło"/>
          </div>
          <div class="form-group">
              <input id="change" type="submit" class="btn btn-success" value="Zmień hasło" />
          </div>
      </form>
  </div>
</div>
<hr>

@endsection

@section('script')

<script>

var send = false;

$("#change").on('click', function() {
    var old_pass = $("#old_pass").val();
    var new_pass = $("#new_pass").val();
    var new_pass_confirm = $("#new_pass_confirm").val();

    $('#save_pass').submit(function(){
        send = true;
        $(this).find(':submit').attr('disabled','disabled');
    });

    if (send == true) {
        $('#change').attr('disabled', 'disabled');
    }

    if (old_pass == '') {
        alert("Podaj stare hasło!");
        return false;
    }

    if (new_pass == '') {
        alert("Podaj nowe hasło!");
        return false;
    }

    if (new_pass_confirm == '') {
        alert("Powtórz nowe hasło!");
        return false;
    }

    if (new_pass != new_pass_confirm) {
        alert("Hasła nie są zgodne!");
        return false;
    }

    if (new_pass == old_pass) {
        alert("Podane hasło nie może być identyczne z poprzednim!");
        return false;
    }
});

</script>
@endsection
