@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edytuj sprzęt firmowy</h1>
    </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <a class="btn btn-info" href="{{URL::to('/show_equipment/')}}">Powrót</a>
  </div>
</div>
<br />
@if (Session::has('message_ok'))
    <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form method="POST" action="{{URL::to('/edit_equipment/')}}/{{$equipment->id}}" id="add_equipment">
                  <input type="hidden" name="equipment_type" value="{{$equipment->equipment_type_id}}">
                  <input type="hidden" name="user_set" value="{{$equipment->user_id}}">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="model">Typ</label>
                            <input disabled id="model" type="text" value="{{$equipment->equipment_type->name}}" class="form-control" />
                        <div>
                        <div class="form-group">
                          <label for="user_id">Pracownik:</label>
                            <select class="form-control" name="user_id">
                                <option value="-1">Wybierz</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" @if($user->id == $equipment->id_user) selected @endif >{{$user->first_name . ' ' . $user->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="department_info_id">Oddział:</label>
                            <select class="form-control" name="department_info_id">
                                <option value="-1">Wybierz</option>
                                @foreach($department_info as $department)
                                    <option value="{{$department->id}}" @if($department->id == $equipment->department_info_id) selected @endif >{{$department->departments->name . ' ' . $department->department_type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="model">Model</label>
                            <input name="model" id="model" type="text" value="{{$equipment->model}}" class="form-control" />
                        <div>
                        <div class="form-group">
                            <label for="serial_code">Numer seryjny</label>
                            <input name="serial_code" type="text" value="{{$equipment->serial_code}}" class="form-control" />
                        <div>
                        <div class="form-group">
                            <label for="power_cable">Kabel zasilający</label>
                            <select name="power_cable" class="form-control">
                                <option value="1" @if($equipment->power_cable == 1) selected @endif>Tak</option>
                                <option value="0" @if($equipment->power_cable == 0) selected @endif>Nie</option>
                            </select>
                        <div>
                        @if($equipment->equipment_type_id == 1)
                            <div class="form-group">
                                <label for="laptop_processor">Procesor</label>
                                <input name="laptop_processor" type="text" value="{{$equipment->laptop_processor}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="laptop_ram">Pamięć RAM</label>
                                <input name="laptop_ram" type="text" value="{{$equipment->laptop_ram}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="laptop_hard_drive">Dysk twardy</label>
                                <input name="laptop_hard_drive" type="text" value="{{$equipment->laptop_hard_drive}}" class="form-control" />
                            <div>
                        @endif
                        @if($equipment->equipment_type_id == 4)
                            <div class="form-group">
                                <label for="sim_number_phone">Numer telefonu</label>
                                <input name="sim_number_phone" type="text" value="{{$equipment->sim_number_phone}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="sim_type">Rodzaj karty SIM</label>
                                <select name="sim_type" class="form-control">
                                    <option value="1" @if($equipment->sim_type == 1) selected @endif>Abonament</option>
                                    <option value="2" @if($equipment->sim_type == 2) selected @endif>Prepaid</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sim_pin">Kod PIN</label>
                                <input name="sim_pin" type="text" value="{{$equipment->sim_pin}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="sim_puk">Kod PUK</label>
                                <input name="sim_puk" type="text" value="{{$equipment->sim_puk}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="sim_net">Internet</label>
                                <select name="sim_net" class="form-control">
                                    <option value="1" @if($equipment->sim_net == 1) selected @endif>Tak</option>
                                    <option value="0" @if($equipment->sim_net == 0) selected @endif>Nie</option>
                                </select>
                            </div>
                        @endif
                        @if($equipment->equipment_type_id == 5)
                        <div class="form-group">
                            <label for="signal_cable">Kabel sygnałowy</label>
                            <select name="signal_cable" class="form-control">
                                <option value="1" @if($equipment->signal_cable == 1) selected @endif>Tak</option>
                                <option value="0" @if($equipment->signal_cable == 0) selected @endif>Nie</option>
                            </select>
                        </div>
                        @endif
                        @if($equipment->equipment_type_id == 3)
                            <div class="form-group">
                                <label for="tablet_modem">Modem 3G</label>
                                <select name="tablet_modem" class="form-control">
                                    <option value="1" @if($equipment->tablet_modem == 1) selected @endif>Tak</option>
                                    <option value="0" @if($equipment->tablet_modem == 0) selected @endif>Nie</option>
                                </select>
                            </div>
                        @endif
                        @if($equipment->equipment_type_id == 2 || $equipment->equipment_type_id == 3)
                            <div class="form-group">
                                <label for="imei">Numer IMEI</label>
                                <input name="imei" type="text" value="{{$equipment->imei}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="phone_box">Pudełko</label>
                                <select name="phone_box" class="form-control">
                                    <option value="1" @if($equipment->phone_box == 1) selected @endif>Tak</option>
                                    <option value="0" @if($equipment->phone_box == 0) selected @endif>Nie</option>
                                </select>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="description">Opis</label>
                            <input name="description" type="text" value="{{$equipment->description}}" class="form-control" />
                        <div>
                        <br />
                        <div class="form-group">
                            <input type="submit" value="Zapisz zmiany" id="save" class="btn btn-success pull-left" />
                            <button class="btn btn-danger pull-right" id="delete">Usuń sprzęt</button>
                            <input type="hidden" name="status_delete" value="" id="status_delete"/>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>




@endsection
@section('script')

<script>

    $("#delete").on('click', () => {

        var conf = confirm('Napewno chcesz usunąć ten sprzęt?');
        if (conf) {
            $('#status_delete').val(1);
        } else {
          return false;
        }

    });


    var send = false;

    $("#save").on('click', function() {
        var model = $("input[name='model']").val();
        var serial_code = $("input[name='serial_code']").val();
        var description = $("input[name='description']").val();
        var laptop_processor = $("input[name='laptop_processor']").val();
        var laptop_ram = $("input[name='laptop_ram']").val();
        var laptop_hard_drive = $("input[name='laptop_hard_drive']").val();
        var power_cable = $("select[name='power_cable']").val();
        var phone_box = $("select[name='phone_box']").val();
        var tablet_modem = $("select[name='tablet_modem']").val();
        var signal_cable = $("select[name='signal_cable']").val();
        var sim_type = $("select[name='sim_type']").val();
        var sim_net = $("select[name='sim_net']").val();
        var sim_pin = $("input[name='sim_pin']").val();
        var sim_puk = $("input[name='sim_puk']").val();
        var imei = $("input[name='imei']").val();

        $('#add_equipment').submit(function(){
            send = true;
            $(this).find(':submit').attr('disabled','disabled');
        });

        if (send == true) {
            $('#add_button').attr('disabled', 'disabled');
        }

        if (model == '') {
            alert("Podaj nazwę modelu!");
            return false;
        }

        if (serial_code == '') {
            alert("Podaj numer seryjny!");
            return false;
        }

        if (description == '') {
            alert("Dodaj opis!");
            return false;
        }

        if (imei == '') {
            alert("Podaj nazwę IMEI!");
            return false;
        }

        if (laptop_ram == '') {
            alert("Podaj ilość pamięci RAM!");
            return false;
        }

        if (laptop_processor == '') {
            alert("Podaj nazwę procesora!");
            return false;
        }

        if (laptop_hard_drive == '') {
            alert("Podaj dane dysku twardego!");
            return false;
        }

        if (power_cable == 'Wybierz') {
            alert("Zaznacz czy sprzęt posiada kabel zasilający!");
            return false;
        }

        if (phone_box == 'Wybierz') {
            alert("Zaznacz czy telefon posiada opakowanie!");
            return false;
        }

        if (tablet_modem == 'Wybierz') {
            alert("Zaznacz czy tablet posiada modem 3G!");
            return false;
        }

        if (sim_net == 'Wybierz') {
            alert("Zaznacz czy telefon posiada dostęp do internetu!");
            return false;
        }

        if (sim_type == 'Wybierz') {
            alert("Zaznacz typ umowy!");
            return false;
        }

        if (sim_pin == '') {
            alert("Podaj numer PIN!");
            return false;
        }

        if (sim_puk == '') {
            alert("Podaj numer PUK!");
            return false;
        }

        if (signal_cable == 'Wybierz') {
            alert("Zaznacz czy monitor posiada kabel sygnałowy!");
            return false;
        }


    });

</script>


@endsection
