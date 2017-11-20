@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Edytuj sprzęt firmowy</h1>
    </div>
</div>

@if(isset($message_ok))
    <div class="alert alert-success">{{$message_ok}}</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form method="POST" action="/edit_equipment/{{$equipment->id}}">
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
                                <option value="2" @if($equipment->power_cable == 0) selected @endif>Nie</option>
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
                        @if($equipment->equipment_type_id == 2)
                            <div class="form-group">
                                <label for="sim_number_phone">Numer telefonu</label>
                                <input name="sim_number_phone" type="text" value="{{$equipment->sim_number_phone}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="sim_type">Rodzaj karty SIM</label>
                                <input name="sim_type" type="text" value="{{$equipment->sim_type}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="sim_pin">Kod PIN</label>
                                <input name="sim_pin" type="text" value="{{$equipment->sim_pin}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="sim_puk">Kod PUK</label>
                                <input name="sim_puk" type="text" value="{{$equipment->sim_puk}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="sim_net">Kod NET</label>
                                <input name="sim_net" type="text" value="{{$equipment->sim_net}}" class="form-control" />
                            <div>
                            <div class="form-group">
                                <label for="signal_cable">Antena</label>
                                <input name="signal_cable" type="text" value="{{$equipment->signal_cable}}" class="form-control" />
                            <div>
                        @endif
                        <div class="form-group">
                            <label for="description">Opis</label>
                            <input name="description" type="text" value="{{$equipment->description}}" class="form-control" />
                        <div>
                        <br />
                        <div class="form-group">
                            <input type="submit" value="Zapisz zmiany" class="btn btn-success" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




@endsection
@section('script')


@endsection
