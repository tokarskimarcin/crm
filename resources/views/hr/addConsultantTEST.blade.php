@extends('layouts.main')
@section('style')
@section('content')

<!-- Main -->

<div class="row">
    <!-- center left-->
        <hr>
        @if (Session::has('message_edit'))
            <br />
            <div class="alert alert-success">{{ Session::get('message_edit') }}</div>
        @endif
        <div class="panel panel-info" style="width: 100%">
            <div class="panel-heading">
                <h3 class="panel-title">Profil Pracownika</h3>
            </div>

            <div class="panel-body">
                <form class="form-horizontal" method="post" action="{{URL::to('/edit_cadre/')}}/{{$user->id}}" id="edit_user">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-12">

                        <div class=" col-md-6 col-lg-6 ">
                            <table class="table table-user-information">
                                <tbody>
                                <b style="font-size: 20px; font-family: sans-serif;">Dane Osobowe</b>
                                <tr>
                                    <td class="td-class"><b>Imię:</b></td>
                                    <td>
                                        <input type="text" class="form-control" name="first_name" placeholder="Imię" value="{{$user->first_name}}">
                                    </td>
                                </tr>
                                <tr class="alert alert-danger" style="display: none" id="alert_name">
                                    <td colspan="1">Podaj imie!</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="td-class"><b>Nazwisko:</b></td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Nazwisko" name="last_name"  value="{{$user->last_name}}">
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
                                            <input readonly type="mail" class="form-control" placeholder="Email" name="email"  value="{{$user->email_off}}">
                                        </td>
                                    </tr>
                                    <tr class="alert alert-danger" style="display: none" id="alert_email">
                                        <td colspan="1">Podaj adres email!</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="td-class"><b>Telefon służbowy:</b></td>
                                        <td>
                                            <input type="number" pattern="[0-9]*" class="form-control" placeholder="format: 000000000" name="phone" value="{{$user->phone}}">
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="td-class"><b>Telefon prywatny:</b></td>
                                    <td>
                                        <input type="number" pattern="[0-9]*" class="form-control" placeholder="format: 000000000" name="private_phone" value="{{$user->private_phone}}">
                                    </td>
                                </tr>
                                <tr class="alert alert-danger" style="display: none" id="alert_user_phone">
                                    <td colspan="1">Podaj telefon pracownika!</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="td-class"><b>Login(Godzinówka):</b></td>
                                    <td><input readonly type="text" class="form-control" placeholder="Login" name="username" value="{{$user->username}}"></td>
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
                                    <td class="td-class"><b>Hasło:</b></td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Hasło" name="password"  value="{{base64_decode($user->guid)}}">
                                    </td>
                                </tr>
                                <tr class="alert alert-danger" style="display: none" id="alert_pass">
                                    <td colspan="1">Podaj hasło!</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="td-class"><b>Dokumenty:</b></td>
                                    <td>
                                        <select class="form-control" style="font-size:18px;" name="documents" >
                                            <option value="1" @if($user->documents == 1) selected @endif>Tak</option>
                                            <option value="0" @if($user->documents == 0) selected @endif>Nie</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="alert alert-danger" style="display: none" id="alert_documents">
                                    <td colspan="1">Wybierz jedną z opcji!</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="td-class"><b>Student:</b></td>
                                    <td>
                                        <select class="form-control" style="font-size:18px;" name="student">
                                            <option value="1" @if($user->student == 1) selected @endif>Tak</option>
                                            <option value="0" @if($user->student == 0) selected @endif>Nie</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="alert alert-danger" style="display: none" id="alert_student">
                                    <td colspan="1">Wybierz status studenta!</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="td-class"><b>Agencja:</b></td>
                                    <td>
                                        <select class="form-control" style="font-size:18px;" name="agency_id" >
                                            @foreach($agencies as $agency)
                                                <option value="{{$agency->id}}" @if($user->agency_id == $agency->id) selected @endif>{{$agency->name}}</option>
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
                                <tr>
                                    <td class="td-class"><b>Rozpoczęcie Pracy:</b></td>
                                    <td>
                                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                            <input class="form-control" id="start_date" name="start_date" type="text" value="{{$user->start_work}}" readonly >
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-class"><b>Zakończenie Pracy:</b></td>
                                    <td>
                                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                            @if(isset($user->end_work))
                                                <input class="form-control stop_date" id="stop_date" name="stop_date" type="text" value="{{$user->end_work}}" readonly >
                                            @else
                                                <input class="form-control stop_date" id="stop_date" name="stop_date" type="text" value="0000-00-00" readonly >
                                            @endif

                                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-class"><b>Aktualnie zatrudniony</b></td>
                                    <td>
                                        <select class="form-control"  style="font-size:18px;" name="status_work" id="status_work">
                                            <option @if($user->status_work == 1) selected @endif value="1">Tak</option>
                                            <option @if($user->status_work == 0) selected @endif value="0">Nie</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="td-class"><b>Stawka na godzine:</b></td>
                                    <td>
                                        <select class="form-control" style="font-size:18px;" name="rate" >
                                            <option>Nie dotyczy</option>
                                            @for ($i = 7.00; $i <=14; $i+=0.5)
                                                <option value="{{number_format ($i,2)}}" @if($user->rate == $i) selected @endif>{{number_format ($i,2)}}</option>
                                            @endfor
                                        </select>
                                    </td>
                                </tr>
                                @if($type == 2)
                                    <tr>
                                        <td class="td-class"><b>Wynagrodzenie:</b></td>
                                        <td>
                                            @if(isset($user->salary))
                                                <input type="number" class="form-control" placeholder="0" name="salary" value="{{$user->salary}}">
                                            @else
                                                <input type="number" class="form-control" placeholder="0" name="salary" value="0">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-class"><b>Dodatek slużbowy:</b></td>
                                        <td>
                                            <input type="number" class="form-control" placeholder="0" name="additional_salary" value="{{$user->additional_salary}}">
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="td-class"><b>Całość na konto:</b></td>
                                    <td>
                                        <select class="form-control" style="font-size:18px;" name="salary_to_account">
                                            <option value="1" @if($user->salary_to_account == 1) selected @endif>Tak</option>
                                            <option value="0" @if($user->salary_to_account == 0) selected @endif>Nie</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="alert alert-danger" style="display: none" id="alert_ck">
                                    <td colspan="1">Wybierz wartość CNK!</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="td-class"><b>Uprawnienia:</b></td>
                                    <td>
                                        <select class="form-control" style="font-size:18px;" name="user_type" >
                                            @foreach($userTypes as $user_type)
                                                <option value="{{$user_type->id}}" @if($user_type->id == $user->user_type_id) selected @endif>{{$user_type->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr class="alert alert-danger" style="display: none" id="alert_user_type">
                                    <td colspan="1">Wybierz uprawnienia!</td>
                                    <td></td>
                                </tr>
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
                                                <option value="0" @if($user->dating_type == 0) selected @endif>Badania</option>
                                                <option value="1" @if($user->dating_type == 1) selected @endif>Wysyłka</option>
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr class="alert alert-danger" style="display: none" id="alert_dating_type">
                                    <td colspan="1">Wybierz typ użytkownika!</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="td-class"><b>Numer kolejki PBX:</b></td>
                                    <td>
                                        <input type="number" class="form-control" placeholder="Login z programu do dzwonienia" name="login_phone" value="{{$user->login_phone}}">
                                    </td>
                                </tr>
                                <tr class="alert alert-danger" style="display: none" id="alert_pbx">
                                    <td colspan="1">Podaj numer kolejki PBX!</td>
                                    <td></td>
                                </tr>
                                @endif
                                @if(isset($penalty_bonuses[1]))
                                    <tr>
                                        <td class="td-class"><b>Suma kar/premii ({{$month[0]}}):</b></td>
                                        <td>
                                            <input disabled type="number" class="form-control" placeholder="0" name="additional_salary_2nd_month" value="{{$penalty_bonuses[0][0]->premia - $penalty_bonuses[0][0]->kara}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-class"><b>Suma kar/premii ({{$month[1]}}):</b></td>
                                        <td>
                                            <input disabled type="number" class="form-control" placeholder="0" name="additional_salary_2nd_month" value="{{$penalty_bonuses[1][0]->premia - $penalty_bonuses[1][0]->kara}}">
                                        </td>
                                    </tr>
                                @elseif(isset($penalty_bonuses[0]))
                                    <tr>
                                        <td class="td-class"><b>Suma kar/premii ({{$month[1]}}):</b></td>
                                        <td>
                                            <input disabled type="number" class="form-control" placeholder="0" name="additional_salary_2nd_month" value="{{$penalty_bonuses[0][0]->premia - $penalty_bonuses[0][0]->kara}}">
                                        </td>
                                    </tr>
                                @endif
                                @if($type == 2)
                                    <tr>
                                        <td class="td-class"><b>Oddział:</b></td>
                                        <td>
                                            <select class="form-control" style="font-size:18px;" name="department_info_id" id="department_info_id">
                                                @foreach($department_info as $dep)
                                                    @if($dep->id != 13)
                                                        <option @if($dep->id == $user->main_department_id) selected @endif value="{{$dep->id}}">{{$dep->departments->name . ' ' . $dep->department_type->name}}</option>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="submit" value="Zapisz zmiany" class="btn btn-success" id="edit_button"/>
                    </div>
                </form>
            </div>
        </div>

    <div class="panel panel-info" style="width: 100%">
        <div class="panel-heading">
            <h3 class="panel-title">Kary i Premie</h3>
        </div>

        <div class="panel-body">
            <div class=" col-md-12 col-lg-12 ">
                @include('hr.userPenaltyBonus')
            </div>
        </div>
    </div>

    <div class="panel panel-info" style="width: 100%">
        <div class="panel-heading">
            <h3 class="panel-title">Posiadany Sprzęt</h3>
        </div>

        <div class="panel-body">
            <div class=" col-md-12 col-lg-12 ">
                @include('hr.userEquipment')
            </div>
        </div>
    </div>
</div>

<!--/container-->
<!-- /Main -->
<div class="modal">

</div>

@endsection
@section('script')

<script>
        var checkEndWorkStatus = Number({{$user->status_work}});
        var checkEndWorkDate = '{{$user->end_work}}';

    $('.form_date').datetimepicker({
        language: 'pl',
        autoclose: 1,
        minView: 2,
        pickTime: false,
    });

    $("#status_work").on('change', function() {
        $(".stop_date").removeAttr('readonly');
    });

    var validation_user = false;

    $('#status_work').change(function() {
        //Sprawdzenie czy zmieniany jest status pracy na "niepracujący"
        var status_work = $("#status_work").val();
        if (checkEndWorkStatus == 1 && status_work == 0) {
            swal('Wybierz datę zakończenia pracy');
            //wiem ze tutaj cos trzeeba dodac
        }
    });

    $("#edit_button").on('click', function(){
        var first_name = $("input[name='first_name']").val();
        var last_name = $("input[name='last_name']").val();
        var private_phone = $("input[name='private_phone']").val();
        var username = $("input[name='username']").val();
        var password = $("input[name='password']").val();
        var login_phone = $("input[name='login_phone']").val();
        var start_date = $('#start_date').val();
        var stop_date = $('#stop_date').val();

        $('#edit_user').submit(function(){
            validation_user = true;
            $(this).find(':submit').attr('disabled','disabled');
        });

        if (validation_user == true) {
            $("#addpbsubmit").attr('disabled', true);
        }

        var validationCheck = true;


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

        if (private_phone.trim().length == 0 || isNaN(private_phone)) {
            $('#alert_user_phone').fadeIn(1000);
            validationCheck = false;
        } else {
            $('#alert_user_phone').fadeOut(1000);
        }

        if (username.trim().length == 0) {
            $('#alert_user_name').fadeIn(1000);
            validationCheck = false;
        }else {
            $('#alert_user_name').fadeOut(1000);
        }

        if (password.trim().length == 0) {
            $('#alert_pass').fadeIn(1000);
            validationCheck = false;
        } else {
            $('#alert_pass').fadeOut(1000);
        }
        if (login_phone.trim().length == 0) {
            $('#alert_pbx').fadeIn(1000);
            validationCheck = false;
        } else {
            $('#alert_pbx').fadeOut();
        }

        //Porównanie czy data zakończenia jest wyższa niż data rozpoczęcia
        var d1 = Date.parse(stop_date);
        var d2 = Date.parse(start_date);

        if (d1 < d2) {
            swal('Data zakończenia pracy nie może być niższa niż data jej rozpoczęcia!');
            validationCheck = false;
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

        return validationCheck;
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

</script>
@endsection
