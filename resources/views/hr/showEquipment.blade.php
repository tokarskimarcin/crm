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
                <div class="panel-heading">
                    Status Pracy
                </div>
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
                                                <td>Serial</td>
                                                <td>Procesor</td>
                                                <td>Ram</td>
                                                <td>Dysk</td>
                                                <td>Opis</td>
                                                <td>Oddział</td>
                                                <td>Pracownik</td>
                                                <td>Akcja</td>
                                            </tr>
                                        @endif
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
