@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Dodaj sprzęt firmowy ({{$equipments_types->name}})</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form method="POST" action="{{URL::to('/add_equipment/')}}" id="add_equipment">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="equipment_type" value="{{$equipments_types->id}}">
                    <div class="col-md-6">

                        <div class="form-group">
                          <label for="user_id">Pracownik (Opcjonalnie):</label>
                            <select class="form-control" name="user_id">
                                <option value="-1">Wybierz</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->last_name . ' ' . $user->first_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="department_info_id">Oddział (Opcjonalnie):</label>
                            <select class="form-control" name="department_info_id">
                                <option value="-1">Wybierz</option>
                                @foreach($department_info as $department)
                                    <option value="{{$department->id}}">{{$department->departments->name . ' ' . $department->department_type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="model">Model</label>
                            <input name="model" id="model" type="text" class="form-control" placeholder="Model"/>
                        <div>
                        <div class="form-group">
                            <label for="serial_code">Numer seryjny</label>
                            <input name="serial_code" id="serial_code" type="text" class="form-control" placeholder="Numer seryjny" />
                        <div>
                        @if($equipments_types->id == 1)
                            <div class="form-group">
                                <label for="laptop_processor">Procesor:</label>
                                <input name="laptop_processor" id="laptop_processor" type="text" class="form-control" placeholder="Procesor"/>
                            <div>
                            <div class="form-group">
                                <label for="laptop_ram">Pamięć RAM:</label>
                                <input name="laptop_ram" id="laptop_ram" type="text" class="form-control" placeholder="Pamięć RAM"/>
                            <div>
                            <div class="form-group">
                                <label for="laptop_hard_drive">Dysk twardy:</label>
                                <input name="laptop_hard_drive" id="laptop_hard_drive" type="text" class="form-control" placeholder="Dysk twardy" />
                            <div>
                        @endif
                        @if($equipments_types->id == 2)
                            <div class="form-group">
                                <label for="phone_box">Opakowanie na telefon</label>
                                <select name="phone_box" class="form-control">
                                    <option>Wybierz</option>
                                    <option value="1">Tak</option>
                                    <option value="0">Nie</option>
                                </select>
                            <div>
                        @endif
                        @if($equipments_types->id == 3)
                            <div class="form-group">
                                <label for="tablet_modem">Modem 3G</label>
                                <select name="tablet_modem" class="form-control">
                                    <option>Wybierz</option>
                                    <option value="1">Tak</option>
                                    <option value="0">Nie</option>
                                </select>
                            <div>
                        @endif
                        @if($equipments_types->id == 3 || $equipments_types->id == 2 || $equipments_types->id == 4)
                            <div class="form-group">
                                <label for="imei">Numer IMEI</label>
                                <input name="imei" id="imei" type="text" class="form-control" placeholder="Numer IMEI"/>
                            <div>
                        @endif
                        @if($equipments_types->id == 4)
                            <div class="form-group">
                                <label for="sim_number_phone">Numer telefonu</label>
                                <input name="sim_number_phone" id="sim_number_phone" type="text" class="form-control" placeholder="Numer telefonu"/>
                            <div>
                            <div class="form-group">
                                <label for="sim_pin">Numer PIN</label>
                                <input name="sim_pin" id="sim_pin" type="text" class="form-control" placeholder="Numer PIN"/>
                            <div>
                            <div class="form-group">
                                <label for="sim_puk">Numer PUK</label>
                                <input name="sim_puk" id="sim_puk" type="text" class="form-control" placeholder="Numer PUK"/>
                            <div>
                            <div class="form-group">
                                <label for="sim_type">Typ umowy</label>
                                <select name="sim_type" class="form-control">
                                    <option>Wybierz</option>
                                    <option value="1">Abonament</option>
                                    <option value="2">Prepaid</option>
                                </select>
                            <div>
                            <div class="form-group">
                                <label for="sim_net">Internet</label>
                                <select name="sim_net" class="form-control">
                                    <option>Wybierz</option>
                                    <option value="1">Tak</option>
                                    <option value="0">Nie</option>
                                </select>
                            <div>
                        @endif
                        @if($equipments_types->id != 4)
                            <div class="form-group">
                                <label for="power_cable">Kabel zasilający</label>
                                <select name="power_cable" class="form-control">
                                    <option>Wybierz</option>
                                    <option value="1">Tak</option>
                                    <option value="0">Nie</option>
                                </select>
                            <div>
                        @endif
                        @if($equipments_types->id == 5)
                            <div class="form-group">
                                <label for="signal_cable">Kabel sygnałowy</label>
                                <select name="signal_cable" class="form-control">
                                    <option>Wybierz</option>
                                    <option value="1">Tak</option>
                                    <option value="0">Nie</option>
                                </select>
                            <div>
                        @endif
                        <div class="form-group">
                            <label for="description">Opis</label>
                            <input name="description" id="description" type="text" class="form-control" placeholder="Opis"/>
                        <div>
                        <br />
                        <div class="form-group">
                            <input type="submit" value="Dodaj" class="btn btn-success" id="add_button"/>
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

    var send = false;

    $("#add_button").on('click', function() {
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

        var validation_ok = true;

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
