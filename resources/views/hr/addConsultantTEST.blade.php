@extends('layouts.main')
@section('content')


    <style>
        .edit{
            background-color: #FFFFFF;
            border:0;
        }
        .edit:hover{
            color:#a7a7a7;
        }
        .edit:active{
            border:0;
        }
        .edit-name{
            display:none;
        }
        .edit-surname{
            display:none;
        }
        .edit-doc{
            display:none;
        }
        .edit-stu{
            display:none;
        }
        .edit-statuswork{
            display:none;
        }
        .edit-phonework{
            display:none;
        }
        .edit-phonepriv{
            display:none;
        }
        .edit-datework{
            display:none;
        }
        .edit-money{
            display:none;
        }
        .edit-addmoney{
            display:none;
        }
        .td-class {
            width: 170px;
            height:52px;
        }
    </style>

    <!-- Main -->
    <div class="container">
        <div class="row">
            <!-- center left-->
            <div class="col-md-12">
                <hr>
                @if (Session::has('message_edit'))
                    <br />
                    <div class="alert alert-success">{{ Session::get('message_edit') }}</div>
                @endif
                <!-- <div class="alert alert-danger" role="alert">Niestety ta funkcja nie jest jeszcze dostępna.</div> -->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Profil Pracownika</h3>
                    </div>



                    <div class="panel-body">
                        <!-- <div class="col-md-2 col-lg-2 " align="center"> <img alt="User Pic" src="http://saintgeorgelaw.com/wp-content/uploads/2015/01/male-formal-business-hi.png" class="img-circle img-responsive" style="border:2px solid #222;"> </div> -->
                        <form class="form-horizontal" method="post" action="/edit_cadre/{{$user->id}}" id="edit_user"><!-- Formularz edycji kadry -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-md-10">

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
                                        <tr>
                                            <td class="td-class"><b>Nazwisko:</b></td>
                                            <td>
                                                <input type="text" class="form-control" placeholder="Nazwisko" name="last_name"  value="{{$user->last_name}}">
                                            </td>
                                        </tr>
                                        @if($type == 2)
                                            <tr>
                                                <td class="td-class"><b>E-mail:</b></td>
                                                <td>
                                                    <input type="mail" class="form-control" placeholder="Email" name="email"  value="{{$user->email_off}}">
                                                </td>
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
                                        <tr>
                                            <td class="td-class"><b>Login(Godzinówka):</b></td>
                                            <td><input type="text" class="form-control" placeholder="Login" name="username" value="{{$user->username}}"></td>

                                        </tr>
                                        <tr>
                                            <td class="td-class"><b>Hasło:</b></td>
                                            <td>
                                                <input type="text" class="form-control" placeholder="Hasło" name="password"  value="{{base64_decode($user->guid)}}">
                                            </td>

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
                                        <tr>
                                            <td class="td-class"><b>Student:</b></td>
                                            <td>
                                                <select class="form-control" style="font-size:18px;" name="student">
                                                    <option value="1" @if($user->student == 1) selected @endif>Tak</option>
                                                    <option value="0" @if($user->student == 0) selected @endif>Nie</option>
                                                </select>
                                            </td>
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
                                                    <input class="form-control" name="start_date" type="text" value="{{$user->start_work}}" readonly >
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="td-class"><b>Zakończenie Pracy:</b></td>
                                            <td>
                                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                    @if(isset($user->end_work))
                                                        <input class="form-control stop_date" name="stop_date" type="text" value="{{$user->end_work}}" readonly >
                                                    @else
                                                        <input class="form-control stop_date" name="stop_date" type="text" value="{{date('Y-m-d')}}" readonly >
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
                                        @if(isset($penalty_bonuses[1]))
                                            <tr>
                                                <td class="td-class"><b>Suma kar/premii ({{$month[1]}}):</b></td>
                                                <td>
                                                    <input disabled type="number" class="form-control" placeholder="0" name="additional_salary_2nd_month" value="{{$penalty_bonuses[0]->premia - $penalty_bonuses[0]->kara}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="td-class"><b>Suma kar/premii ({{$month[0]}}):</b></td>
                                                <td>
                                                    <input disabled type="number" class="form-control" placeholder="0" name="additional_salary_2nd_month" value="{{$penalty_bonuses[1]->premia - $penalty_bonuses[1]->kara}}">
                                                </td>
                                            </tr>
                                        @elseif(isset($penalty_bonuses[0]))
                                            <tr>
                                                <td class="td-class"><b>Suma kar/premii ({{$month[0]}}):</b></td>
                                                <td>
                                                    <input disabled type="number" class="form-control" placeholder="0" name="additional_salary_2nd_month" value="{{$penalty_bonuses[0]->premia - $penalty_bonuses[0]->kara}}">
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="td-class"><b>Login PBX:</b></td>
                                            <td>
                                                <input type="text" class="form-control" placeholder="Login z programu do dzwonienia" name="login_phone" value="{{$user->login_phone}}">
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="submit" value="Zapisz zmiany" class="btn btn-success" id="edit_button"/>
                            </div>
                        </form>
                        <div class=" col-md-10 col-lg-10 ">
    		                  <table class="table table-user-information">
    		                    <tbody>
    		                    <b style="font-size: 20px; font-family: sans-serif;">Kary i Premie</b>
                              @if(($user->penalty_bonuses->where('status', '!=', 0)->count()) > 0)
          		                      <tr>
          		                        <td><b>Data</b></td>
          		                        <td><b>Kara/Premia</b></td>
          		                        <td><b>Dodał</b></td>
          		                        <td><b>Powód</b></td>
          		                        <td></td>
          		                      </tr>

                              @else
                                  <div class="alert alert-info">Ten użytkownik nie ma jeszcze kar/premii!</div>
                              @endif
                              @foreach($user->penalty_bonuses->where('status', '!=', 0) as $penalty)
                                  <tr name={{$penalty->id}}>
        		                        <td nowrap="nowrap">{{$penalty->event_date}}</td>
                                    @if($penalty->type == 2)
                                        <td nowrap="nowrap"><span style="background-color: #70ff5c; padding: 4px 10px;border-radius: 5px;border:1px solid #33ff36; color:#4b5c44;">Premia: {{$penalty->amount}} zł</span></td>
                                    @else
                                        <td nowrap="nowrap"><span style="background-color: #ff7b7b; padding: 4px 10px;border-radius: 5px;border:1px solid #ff6a6a; color:#7f2222;">Kara: {{$penalty->amount}} zł</span></td>
                                    @endif
        		                        <td nowrap="nowrap"><span style="background-color: #d9edf7; padding: 4px 10px;border-radius: 5px;border:1px solid #bce8f1; color:#31708f;">{{$penalty->manager->first_name . ' ' . $penalty->manager->last_name}}</span></td>
        		                        <td>{{$penalty->comment}}</td>
                                    <td><button class="btn btn-danger btn-sm action delete" id="{{$penalty->id}}">Usuń</button></td>
        		                      </tr>
                              @endforeach
                              @if (Session::has('message'))
                                 <div class="alert alert-success">{{ Session::get('message') }}</div>
                              @endif

    							            <tr>
                                  <form method="POST" action="/view_penalty_bonus_edit" id="pb">
                                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                      <input type="hidden" name="user_id" value="{{$user->id}}">
          		                        <td colspan="2"><select class="form-control" name="penalty_type">
          		                        	<option>Wybierz</option>
          		                        	<option value="1">Kara</option>
          		                        	<option value="2">Premia</option>
          		                        </select></td>
          		                        <td><input type="number" placeholder="0" name="cost" class="form-control"></td>
          		                        <td colspan="2"><input type="text" placeholder="Powód" name="reason" class="form-control"></td>
          		                        <td><input value="Dodaj" type="submit" id="addpbsubmit" class="btn btn-info"></td>
                                  </form>
    		                      </tr>

    		                    </tbody>
    		                  </table>
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

    $("#status_work").on('change', function() {
        $(".stop_date").removeAttr('readonly');
    });

    var validation_user = false;

    $("#edit_button").on('click', function(){

        var first_name = $("input[name='first_name']").val();
        var last_name = $("input[name='last_name']").val();
        var private_phone = $("input[name='private_phone']").val();
        var username = $("input[name='username']").val();
        var password = $("input[name='password']").val();

        $('#edit_user').submit(function(){
            validation_user = true;
            $(this).find(':submit').attr('disabled','disabled');
        });

        if (validation_user == true) {
            $("#addpbsubmit").attr('disabled', true);
        }

        if (first_name == '') {
            alert("Pole imie nie może być puste!");
            return false;
        }

        if (last_name == '') {
            alert("Pole nazwsko nie może być puste!");
            return false;
        }

        if (private_phone == '') {
            alert("Pole telefon prywatny nie może być puste!");
            return false;
        }

        if (username == '') {
            alert("Pole login(godzinówka) nie może być puste!");
            return false;
        }

        if (password == '') {
            alert("Pole hasło nie może być puste!");
            return false;
        }

    });


    $( ".delete" ).click(function() {
        var id = (this.id);

        var conf = confirm("Czy napewno chcesz usunąć karę/premię?");

        if (conf == true) {
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
                    alert("Pomyślnie usunięto karę/premię!");
                }
            });
            $('tr[name=' + this.id + ']').fadeOut(0);
        }
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
            alert("Wybierz rodzaj kary/nagrody!");
            validation = false;
            return false;
        }

        if (cost == '') {
            alert("Podaj kwotę!");
            validation = false;
            return false;
        }

        if (reason == '') {
            alert("Podaj powód!");
            validation = false;
            return false;
        }

    });


    $('#myModal').on('hidden.bs.modal', function () {
        $("#modal_content").empty();
    });



    $(".equipment_data").on('click', function(){
        var id = this.id;

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

        if(equipment_type_id != '') {
            equipment_type_id = $("<tr><td><b>" + "Rodzaj sprzętu" + "</b></td><td>" + equipment_type_id + "</td></tr>");
        }

        if(laptop_processor != '') {
            laptop_processor = $("<tr><td><b>" + "Rodzaj procesora" + "</b></td><td>" + laptop_processor + "</td></tr>");
        }

        if(laptop_ram != '') {
            laptop_ram = $("<tr><td><b>" + "Pamięć RAM" + "</b></td><td>" + laptop_ram + "</td></tr>");
        }

        if(laptop_hard_drive != '') {
            laptop_hard_drive = $("<tr><td><b>" + "Dysk twardy" + "</b></td><td>" + laptop_hard_drive + "</td></tr>");
        }

        if(phone_box != '') {
            phone_box = 'Tak';
            phone_box = $("<tr><td><b>" + "Opakowanie na telefon" + "</b></td><td>" + phone_box + "</td></tr>");
        }

        if(tablet_modem != '') {
            tablet_modem = 'Tak';
            tablet_modem = $("<tr><td><b>" + "Modem" + "</b></td><td>" + tablet_modem + "</td></tr>");
        }

        if(sim_number_phone != '') {
            sim_number_phone = $("<tr><td><b>" + "Numer telefonu" + "</b></td><td>" + sim_number_phone + "</td></tr>");
        }

        if(sim_type != '') {
          if (sim_type == 1) {
              sim_type = 'Prepaid';
          } else {
              sim_type = 'Abonament';
          }
            sim_type = $("<tr><td><b>" + "Rodzaj karty SIM" + "</b></td><td>" + sim_type + "</td></tr>");
        }

        if(sim_pin != '') {
            sim_pin = $("<tr><td><b>" + "Numer PIN" + "</b></td><td>" + sim_pin + "</td></tr>");
        }

        if(sim_puk != '') {
            sim_puk = $("<tr><td><b>" + "Numer PUK" + "</b></td><td>" + sim_puk + "</td></tr>");
        }

        if(sim_net != '') {
            sim_net = $("<tr><td><b>" + "Numer NET" + "</b></td><td>" + sim_net + "</td></tr>");
        }

        if(model != '') {
            model = $("<tr><td><b>" + "Model" + "</b></td><td>" + model + "</td></tr>");
        }

        if(serial_code != '') {
            serial_code = $("<tr><td><b>" + "Numer seryjny" + "</b></td><td>" + serial_code + "</td></tr>");
        }

        if(description != '') {
            description = $("<tr><td><b>" + "Opis" + "</b></td><td>" + description + "</td></tr>");
        }

        if(power_cable != '' || power_cable == 0) {
            power_cable = (power_cable == 0) ? "Nie" : "Tak" ;
            power_cable = $("<tr><td><b>" + "Kabel zasilający" + "</b></td><td>" + power_cable + "</td></tr>");
        }

        if(signal_cable != '') {
            signal_cable = 'Tak'
            signal_cable = $("<tr><td><b>" + "Antena" + "</b></td><td>" + signal_cable + "</td></tr>");
        }

        if(to_user != '') {
            to_user = $("<tr><td><b>" + "Data wydania" + "</b></td><td>" + to_user + "</td></tr>");
        }


        $("#modal_content").append(
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
          );
    });

</script>
@endsection
