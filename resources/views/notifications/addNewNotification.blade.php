@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Zgłoś problem</h1>
    </div>
</div>

@if (Session::has('message_ok'))
   <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
  <div class="col-md-12">
      <div class="col-md-6">
        <form method="POST" action="/add_notification" id="form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="title">Problem</label>
                <input type="text" placeholder="Wpisz tytuł problemu" class="form-control" name="title" id="title"/>
            </div>
            <div class="form-group">
                <label for="content">Szczegółowy opis</label>
                <textarea rows="7" type="text" placeholder="Dodaj opis problemu" class="form-control" name="content" id="content"/></textarea>
            </div>
            <div class="form-group">
                <label for="notification_type_id">Wybierz typ problemu</label>
                <select name="notification_type_id" class="form-control" id="notification_type_id">
                    <option>Wybierz</option>
                    @foreach($notification_types as $notification_type)
                        <option value="{{$notification_type->id}}">{{$notification_type->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
              <label for="department_info_id">Wybierz oddział</label>
                <select name="department_info_id" class="form-control" id="department_info_id">
                    <option>Wybierz</option>
                    @foreach($department_info as $department)
                        <option value="{{$department->id}}">{{$department->departments->name . ' ' . $department->department_type->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="submit" id="add_notification" class="btn btn-success" value="Wyślij zgłoszenie"/>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>



    $("#add_notification").on('click', function() {
        var title = $("#title").val();
        var content = $("#content").val();
        var notification_type_id = $("#notification_type_id").val();
        var department_info_id = $("#department_info_id").val();

        var form_ok = true;
        if (title == '') {
            alert("Dodaj tytuł problemu!");
            form_ok = false;
        }

        if (content == '') {
            alert("Dodaj treść problemu!");
            form_ok = false;
        }

        if (notification_type_id == 'Wybierz') {
            alert("Wybierz rodzaj problemu!");
            form_ok = false;
        }

        if (department_info_id == 'Wybierz') {
            alert("Wybierz oddział!");
            form_ok = false;
        }

        if (form_ok == true) {
          $('#form').submit(function(){
              $(this).find(':submit').attr('disabled','disabled');
          });
        }

        if (form_ok == false) {
            return false;
        }
    });

</script>
@endsection
