@extends('layouts.main')
@section('content')


{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Sprzęt Firmowy</h1>
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
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="panel-body">


                                    @foreach($equipments_types as $equipments_type)
                                        @if($equipments_type->name == "Laptop")
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Model</td>
                                                    <td>Numer seryjny</td>
                                                    <td>Procesor</td>
                                                    <td>Ram</td>
                                                    <td>Dysk</td>
                                                    <td>Opis</td>
                                                    <td>Oddział</td>
                                                    <td>Pracownik</td>
                                                    <td>Akcja</td>
                                                </tr>
                                            @foreach($equipments->where('equipment_type_id',$equipments_type->id) as $equipment)
                                                    <td>{{$equipment->model}}</td>
                                                    <td>{{$equipment->serial_code}}</td>
                                                    <td>{{$equipment->laptop_processor}}</td>
                                                    <td>{{$equipment->laptop_ram}}</td>
                                                    <td>{{$equipment->laptop_hard_drive}}</td>
                                                    <td>{{$equipment->description}}</td>
                                                    <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                                    @if($equipment->user != null)
                                                        <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                                    @else
                                                        <td>Brak</td>
                                                    @endif
                                            @endforeach
                                            </table>
                                        @elseif($equipments_type->name == "Tablet")
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Model</td>
                                                    <td>Numer seryjny</td>
                                                    <td>Imei</td>
                                                    <td>Modem 3G</td>
                                                    <td>Opis</td>
                                                    <td>Oddział</td>
                                                    <td>Pracownik</td>
                                                    <td>Akcja</td>
                                                </tr>
                                                @foreach($equipments->where('equipment_type_id',$equipments_type->id) as $equipment)
                                                    <td>{{$equipment->model}}</td>
                                                    <td>{{$equipment->serial_code}}</td>
                                                    <td>{{$equipment->imei}}</td>
                                                    <td>{{$equipment->tablet_modem}}</td>
                                                    <td>{{$equipment->description}}</td>
                                                    <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                                    @if($equipment->user != null)
                                                        <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                                    @else
                                                        <td>Brak</td>
                                                    @endif
                                                @endforeach
                                                </table>
                                        @elseif($equipments_type->name == "Telefon")
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td>Model</td>
                                                        <td>Numer IMEI</td>
                                                        <td>Ładowarka</td>
                                                        <td>Pudełko</td>
                                                        <td>Opis</td>
                                                        <td>Oddział</td>
                                                        <td>Pracownik</td>
                                                        <td>Akcja</td>
                                                    </tr>
                                                    @foreach($equipments->where('equipment_type_id',$equipments_type->id) as $equipment)
                                                        <td>{{$equipment->model}}</td>
                                                        <td>{{$equipment->imei}}</td>
                                                        <td>{{$equipment->power_cable}}</td>
                                                        <td>{{$equipment->phone_box}}</td>
                                                        <td>{{$equipment->description}}</td>
                                                        <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                                        @if($equipment->user != null)
                                                            <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                                        @else
                                                            <td>Brak</td>
                                                        @endif
                                                    @endforeach
                                                </table>
                                        @elseif($equipments_type->name == "Karta SIM")
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Typ</td>
                                                    <td>Numer Telefonu</td>
                                                    <td>ID Karty</td>
                                                    <td>PIN</td>
                                                    <td>PUK</td>
                                                    <td>Internet</td>
                                                    <td>Opis</td>
                                                    <td>Oddział</td>
                                                    <td>Pracownik</td>
                                                    <td>Akcja</td>
                                                </tr>
                                                @foreach($equipments->where('equipment_type_id',$equipments_type->id) as $equipment)
                                                    <td>{{$equipment->sim_type}}</td>
                                                    <td>{{$equipment->sim_number_phone}}</td>
                                                    <td>{{$equipment->sim_id}}</td>
                                                    <td>{{$equipment->sim_pin}}</td>
                                                    <td>{{$equipment->sim_puk}}</td>
                                                    <td>{{$equipment->sim_net}}</td>
                                                    <td>{{$equipment->description}}</td>
                                                    <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                                    @if($equipment->user != null)
                                                        <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                                    @else
                                                        <td>Brak</td>
                                                    @endif
                                                @endforeach
                                            </table>

                                        @elseif($equipments_type->name == "Monitor")
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Model</td>
                                                    <td>Numer seryjny</td>
                                                    <td>Kabel Sygnałowy</td>
                                                    <td>Kabel zasilający</td>
                                                    <td>Opis</td>
                                                    <td>Oddział</td>
                                                    <td>Pracownik</td>
                                                    <td>Akcja</td>
                                                </tr>
                                                @foreach($equipments->where('equipment_type_id',$equipments_type->id) as $equipment)
                                                    <td>{{$equipment->model}}</td>
                                                    <td>{{$equipment->serial_code}}</td>
                                                    <td>{{$equipment->power_cable}}</td>
                                                    <td>{{$equipment->signal_cable}}</td>
                                                    <td>{{$equipment->description}}</td>
                                                    <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                                    @if($equipment->user != null)
                                                        <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                                    @else
                                                        <td>Brak</td>
                                                    @endif
                                                @endforeach
                                            </table>

                                        @elseif($equipments_type->name == "Drukarka")
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Model</td>
                                                    <td>Numer seryjny</td>
                                                    <td>Opis</td>
                                                    <td>Oddział</td>
                                                    <td>Pracownik</td>
                                                    <td>Akcja</td>
                                                </tr>
                                                @foreach($equipments->where('equipment_type_id',$equipments_type->id) as $equipment)
                                                    <td>{{$equipment->model}}</td>
                                                    <td>{{$equipment->serial_code}}</td>
                                                    <td>{{$equipment->description}}</td>
                                                    <td>{{$equipment->department_info->departments->name.' '.$equipment->department_info->department_type->name}}</td>
                                                    @if($equipment->user != null)
                                                        <td>{{$equipment->user->first_name.' '.$equipment->user->last_name}}</td>
                                                    @else
                                                        <td>Brak</td>
                                                    @endif
                                                @endforeach
                                            </table>

                                            @endif
                                    @endforeach
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


    });

</script>
@endsection
