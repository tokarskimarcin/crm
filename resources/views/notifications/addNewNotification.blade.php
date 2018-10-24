@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
         <div class="page-header">
             <div class="alert gray-nav ">Pomoc / Zgłoś problem</div>
         </div>
    </div>
</div>

@if (Session::has('message_ok'))
   <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Zgłoś problem
            </div>
            <div class="panel-body">
                <form method="POST" action="add_notification" id="form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="title">Tytuł</label>
                        <input type="text" placeholder="Wpisz tytuł problemu" class="form-control" name="title" id="title"/>
                    </div>
                    <div class="alert alert-danger" style="display: none" id="alert_title">
                        Podaj tytuł problemu!
                    </div>
                    <div class="form-group">
                        <label for="content">Szczegółowy opis</label>
                        <textarea rows="7" type="text" placeholder="Dodaj opis" class="form-control" name="content" id="content" style="resize: vertical"/></textarea>
                    </div>
                    <div class="alert alert-danger" style="display: none" id="alert_description">
                        Podaj szczegółowy opis!
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="stickerNr">Numer naklejki urządzenia (np. KA41)</label>
                        <input type="text" placeholder="Wpisz numer naklejki urządzenia" class="form-control" name="stickerNr" id="stickerNr"/>
                        <div class="checkbox">
                            <label>
                                <input id="involveCheckbox" type="checkbox" style="display: block"> Nie dotyczy
                            </label>
                        </div>
                    </div>
                    <div class="alert alert-danger" style="display: none" id="alert_stickerNr">
                        Podaj numer naklejki urządzenia!
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="notification_type_id">Wybierz typ problemu</label>
                        <select name="notification_type_id" class="form-control" id="notification_type_id">
                            <option>Wybierz</option>
                            @foreach($notification_types as $notification_type)
                                <option value="{{$notification_type->id}}">{{$notification_type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-danger" style="display: none" id="alert_type">
                        Wybierz typ problemu!
                    </div>
                    <div class="form-group">
                        <label for="notification_about_id">Wybierz czego dotyczy problem</label>
                        <select name="notification_about_id" class="form-control" id="notification_about_id">
                            <option>Wybierz</option>
                            @foreach($notification_abouts as $notification_about)
                                <option value="{{$notification_about->id}}">{{$notification_about->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-danger" style="display: none" id="alert_about">
                        Wybierz czego dotyczy problem!
                    </div>
                    <div class="form-group">
                    <label for="department_info_id">Wybierz oddział</label>
                        <select name="department_info_id" class="form-control" id="department_info_id">
                            <option>Wybierz</option>
                            @foreach($department_info as $department)
                                @if($department->id != 13)
                                <option value="{{$department->id}}">{{$department->departments->name . ' ' . $department->department_type->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-danger" style="display: none" id="alert_department">
                        Wybierz oddział!
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

    let stickerNr = $('#stickerNr').on('input',function () {

    });
    let involveCheckbox = $('#involveCheckbox').click(function (e) {
            swal({
                title:'Czy jesteś pewny?',
                text: 'Czy Twój problem '+(e.target.checked ? 'nie ' : '')+'dotyczy urządzenia z naklejką?',
                type:'warning',
                confirmButtonText: 'Tak!',
                showCancelButton: true,

            }).then((result) => {
                if (result.value) {
                    e.target.checked ? stickerNr.prop('disabled', true) : stickerNr.prop('disabled', false);
                }else{
                    e.target.checked = !e.target.checked;
                }
            });

    });

    $("#add_notification").on('click', function(e) {
        e.preventDefault();

        var title = $("#title").val();
        var content = $("#content").val();
        var stickerNumber = stickerNr.val();
        var involved = involveCheckbox.prop('checked');
        var notification_type_id = $("#notification_type_id").val();
        var notification_about_id = $("#notification_about_id").val();
        var department_info_id = $("#department_info_id").val();

        var form_ok = true;
        if (title == '') {
            $('#alert_title').slideDown(1000);
            form_ok = false;
        } else {
            $('#alert_title').slideUp(1000);
        }

        if(!involved && stickerNumber == ''){
            $('#alert_stickerNr').slideDown(1000);
            form_ok = false;
        }else{
            $('#alert_stickerNr').slideUp(1000);
        }

        if (content == '') {
            $('#alert_description').slideDown(1000);
            form_ok = false;
        } else {
            $('#alert_description').slideUp(1000);
        }

        if (notification_type_id == 'Wybierz') {
            $('#alert_type').slideDown(1000);
            form_ok = false;
        } else {
            $('#alert_type').slideUp(1000);
        }

        if (notification_about_id == 'Wybierz') {
            $('#alert_about').slideDown(1000);
            form_ok = false;
        } else {
            $('#alert_about').slideUp(1000);
        }

        if (department_info_id == 'Wybierz') {
            $('#alert_department').slideDown(1000);
            form_ok = false;
        } else {
            $('#alert_department').slideUp(1000);
        }


        if (form_ok == true) {
            $(this).find(':submit').attr('disabled','disabled');
            $('#form').submit();
        }

        if (form_ok == false) {
            return false;
        }
    });

</script>
@endsection
