@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        color: #aaa;
        font-size: 20px;
    }
    .myIcon {
        font-size: 550%;
        text-align: center;
    }
    .myUnderLine {
        font-size: 20px;
        color: #aaa;
    }
    .mySmallIcon {
        font-size: 350%;
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Statystyki rekrutacji</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <span class="glyphicon glyphicon-plus myIcon" style="color: #2cb74f"></span>
                    </div>
                    <div class="col-md-6">
                        <span class="pull-right" style="font-size: 50px; color: #aaa">{{$active_recruitments}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 myUnderline" style="font-size: 20px; color: #aaa;">
                        Aktywnych rekrutacji
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <span class="glyphicon glyphicon-education myIcon" style="color: #2cb79d"></span>
                    </div>
                    <div class="col-md-6">
                        <span class="pull-right" style="font-size: 50px; color: #aaa">{{$training_sum}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 myUnderline" style="font-size: 20px; color: #aaa;">
                        Dodanych szkoleń
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <span class="glyphicon glyphicon-zoom-in myIcon" style="color: #f46841"></span>
                    </div>
                    <div class="col-md-6">
                        <span class="pull-right" style="font-size: 50px; color: #aaa">{{($recruiter_sum) ? $recruiter_sum : 0 }}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 myUnderline" style="font-size: 20px; color: #aaa;">
                        Ilość rekruterów
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <span class="glyphicon glyphicon-ok myIcon" style="color: #45f442"></span>
                    </div>
                    <div class="col-md-6">
                        <span class="pull-right" style="font-size: 50px; color: #aaa">{{$recruitment_ok}}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 myUnderline" style="font-size: 20px; color: #aaa;">
                        Ilość zrekrutowanych
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3 style="color: #aaa">Statystyki rekruterów</h3>
                <div class="table-responsive">
                    <table class="table table-striped thead-inverse">
                        <thead>
                            <th>Imie i nazwisko</th>
                            <th>Oddział</th>
                            <th>Suma rekrutacji</th>
                            <th>Aktywnych</th>
                            <th>Pozytywnych</th>
                            <th>Szczegóły</th>
                        </thead>
                        <tbody>
                            @foreach($recruiters as $item)
                                <tr>
                                    <td>{{$item->last_name . ' ' . $item->first_name}}</td>
                                    <td>{{$item->department_info->departments->name . ' ' . $item->department_info->department_type->name}}</td>
                                    <td>{{$item->userCandidates->count()}}</td>
                                    <td>{{$item->userCandidates->whereNotIn('attempt_status_id', [10,11])->count()}}</td>
                                    <td>{{$item->userCandidates->where('attempt_status_id', '=', 10)->count()}}</td>
                                    <td>
                                        <button class="btn btn-info recruiter_click" data-id="{{$item->id}}" data-toggle="modal" data-target="#recruiter_info">
                                            <span class="glyphicon glyphicon-search"></span> Szczegóły
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default panel-body">
            <h3 style="color: #aaa;">Statystyki osób prowadzących szkolenie</h3>
            <div class="table-responsive">
                <table class="table table-striped thead-inverse">
                    <thead>
                        <tr>
                            <td>Imie i nazwisko</td>
                            <td>Oddział</td>
                            <td>Suma szkoleń</td>
                            <td style="width: 15%">Szczegóły</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trainers as $item)
                            <tr>
                                <td>{{$item->first_name . ' ' . $item->last_name}}</td>
                                <td>{{$item->dep_name . ' ' . $item->dep_type_name}}</td>
                                <td>{{$item->trainer_sum}}</td>
                                <td>
                                    <button class="btn btn-info trainer_click" data-id="{{$item->id}}" data-toggle="modal" data-target="#trainer_info">
                                        <span class="glyphicon glyphicon-search"></span> Szczegóły
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="trainer_info" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style="color: #aaa" class="modal-title">Trener: <span id="trainer_id_data"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="panel panel-default panel-body">
                                        <div class="row">
                                            <div class="col-md-6">

                                            </div>
                                            <div class="col-md-6">

                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="myLabel">Szukaj:</label>
                                <input type="text" class="form-control" placeholder="Wyszukaj..." id="trainer_trainings_search"/>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped thead-inverse">
                                    <thead>
                                        <tr>
                                            <th>Data szkolenia</th>
                                            <th>Godzina szkolenia</th>
                                            <th>Ilość osób na szkoleniu</th>
                                            <th>Etap szkolenia</th>
                                        </tr>
                                    </thead>
                                    <tbody id="trainer_trainings">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <a class="btn btn-info">
                                <span></span> Przejdź do działu szkoleń
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>

<div id="recruiter_info" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 style="color: #aaa" class="modal-title">Rekruter: <span id="recruiter_name_data"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-default panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <span class="glyphicon glyphicon-ok mySmallIcon" style="color: #45f442"></span>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="pull-right" style="font-size: 35px; color: #aaa" id="modal_recruitment_sum"></span>
                                    </div>
                                </div>
                            </div>
                            <hr >
                            <div class="row">
                                <div class="col-md-12" style="color: #aaa">
                                    Suma zrekrutowanych pracowników
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <span class="glyphicon glyphicon-envelope mySmallIcon" style="color: #2cb79d"></span>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="pull-right" style="font-size: 35px; color: #aaa" id="modal_all_sum"></span>
                                    </div>
                                </div>
                            </div>
                            <hr >
                            <div class="row">
                                <div class="col-md-12" style="color: #aaa">
                                    Suma rekrutacji
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <span class="glyphicon glyphicon-user mySmallIcon" style="color: #f46841"></span>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="pull-right" style="font-size: 35px; color: #aaa" id="modal_interviews_sum"></span>
                                    </div>
                                </div>
                            </div>
                            <hr >
                            <div class="row">
                                <div class="col-md-12" style="color: #aaa">
                                    Zaplanowanych rozmów rekrutacyjnych
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h4 style="color: #aaa">Etapy zakończenia rekrutacji</h4>
                        <div class="table-responsive">
                            <table class="table table-striped thead-inverse">
                                <thead>
                                    <tr>
                                        <th>Etap</th>
                                        <th>Ilość kandydatów</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody id="recruiter_fails">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 style="color: #aaa">Źródła rekrutacji</h4>
                        <div class="table-responsive">
                            <table class="table table-striped thead-inverse">
                                <thead>
                                    <tr>
                                        <th>Źródło</th>
                                        <th>Ilość kandydatów</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody id="recruiter_sources">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default panel-body">
                            <h4 style="color: #aaa">Kandydaci</h4>
                            <div class="table-responsive">
                                <table id="recruiter_candidates_datatable" class="table table-striped thead-inverse">
                                    <thead>
                                        <tr>
                                            <th>Imie i nazwisko</th>
                                            <th>Data dodania</th>
                                            <th>Status rekrutacji</th>
                                            <th>Profil kandydata</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default panel-body">
                            <h4 style="color: #aaa">Przeprowadzone szkolenia</h4>
                            <div class="table-responsive">
                                <table class="table table-striped thead-inverse">
                                    <thead>
                                        <tr>
                                            <th>Data szkolenia</th>
                                            <th>Ilość osób na szkoleniu</th>
                                            <th>Suma pozytywnych</th>
                                            <th>Suma negatywnych</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modal_training_table">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <a class="btn btn-info" href="{{ URL::to('/add_group_training') }}">
                                    Przejdź do szczegółów szkoleń
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.select.min.js')}}"></script>
<script>

$(document).ready(function() {

    $('#recruiter_info').on('shown.bs.modal', function () {
        
    });

    // Pobranie danch dotyczących trenera
    $('.trainer_click').click(function(e) {
        var id = $(this).data('id');

        $.ajax({
            type: "POST",
            url: '{{ route('api.trainerData') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "id": id
            },
            success: function (response) {
                console.log(response)

                var user = response.user;
                var userTrainings = response.userTrainings;

                $('#trainer_id_data').html(user.first_name + ' ' + user.last_name);

                var content = '';
                $.each(userTrainings, function(key, value) {
                    content += `
                        <tr>
                            <td>${value.training_date}</td>
                            <td>${value.training_hour}</td>
                            <td>${value.candidate_count}</td>
                            <td>Etap ${value.training_stage}</td>
                        </tr>
                    `;
                });

                $('#trainer_trainings').append(content);

            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
    });

    $('.recruiter_click').click(function(e) {
        var id = $(this).data('id');
        

        //Pobranie danych dotyczacych rekrutera
        $.ajax({
            type: "POST",
            url: '{{ route('api.recruiterData') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "id": id
            },
            success: function (response) {
                console.log(response)

                // Podmiana imienia oraz  nazwiska w modalu
                $('#recruiter_name_data').html(response.user.last_name + " " + response.user.first_name);
                // Podmiana ilości zrekrutowanych osob
                $('#modal_recruitment_sum').html(response.recruitment_sum);
                // Podmiana ilości rekrutacji
                $('#modal_all_sum').html(response.all_sum);
                // Podmiania ilości zaplanowanych rozmów
                $('#modal_interviews_sum').html(response.interviews_sum);

                // Wyczyszczenie tabel
                $('#modal_training_table tr').remove();
                $('#recruiter_fails tr').remove();
                $('#recruiter_sources tr').remove();

                // Zdefiniowanie nowych tabel
                var content ='';
                var failContent = '';
                var sourceContent = '';

                // Loop przez wszystkie szkolenia użytkownika
                $.each(response.training_data, function(key, value){
                    content += `
                        <tr>
                            <td>${value.created_at}</td>
                            <td>${value.candidate_sum}</td>
                            <td>${value.candidate_pass}</td>
                            <td>${value.candidate_not_pass}</td>
                        </tr>
                    `;
                });

                // Sprawdzenie czy użytkownik miał jakiekolwiek szkolenia
                content = (content != '') ? content : '<tr class="text-center"><td colspan="4">Użytkownik nie prowadził jeszcze szkoleń.</td></tr>' ;

                //Dodanie szkoleń do tabeli
                $('#modal_training_table').append(content);

                //Loop przez nieudane rekrutacje
                $.each(response.recuitment_by_types, function(key, value){
                    var proc = (Number(Number(value.value) / Number(response.recruitemnt_sum_total)) * 100).toFixed(2) ;
                    failContent += `
                        <tr>
                            <td>${value.name}</td>
                            <td>${value.value}</td>
                            <td>${proc} %</td>
                        </tr>
                    `;
                });

                // Sprawdzenie czy użytkownik miał jakiekolwiek szkolenia
                failContent = (failContent != '') ? failContent : '<tr class="text-center"><td colspan="3">Rekruter nie ma zakończonych negatywnie rekrutacji!.</td></tr>' ;
                
                //Dodanie etapow rekrutacji do tabeli
                $('#recruiter_fails').append(failContent);

                // Loop przez źródła rekrutacji
                $.each(response.recruiter_sources, function(key, value) {
                    var proc = (Number(Number(value.sum) / Number(response.all_sum)) * 100).toFixed(2);
                    sourceContent += `
                        <tr>
                            <td>${value.name}</td>
                            <td>${value.sum}</td>
                            <td>${proc} %</td>
                        </tr>
                    `;
                });

                // Sprawdzenie czy użytkownik miał jakiekolwiek rekrutacje
                sourceContent = (sourceContent != '') ? sourceContent : '<tr class="text-center"><td colspan="4">Użytkownik nie prowadził jeszcze rekrutacji.</td></tr>' ;

                //Dodanie szkoleń do tabeli
                $('#recruiter_sources').append(sourceContent);

                //Dodanie tabeli z kandydatami konkretnego rerkrutera
                table = $('#recruiter_candidates_datatable').DataTable({
                    "autoWidth": false,
                    "processing": true,
                    "bDestroy": true,
                    "serverSide": true,
                    "drawCallback": function( settings ) {
                    },
                    "ajax": {
                        'url': "{{ route('api.datatableShowCadreCandidates') }}",
                        'type': 'POST',
                        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        'data': {'id': id}
                    },
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                    },"columns":[
                        {"data": function (data, type, dataToSet) {
                            var myName = data.first_name + " " + data.last_name;
                            return myName;
                        },"orderable": true, "searchable": true, "name": "last_name"},
                        {"data": "created_at"},
                        {"data": "attempt_name"},
                        {"data": function (data, type, dataToSet) {
                            return "<a href='{{ URL::to('/candidateProfile') }}/" + data.id +"' class='btn btn-info'><span class='glyphicon glyphicon-pencil'></span> Szczegóły</a>";
                        },"orderable": false, "searchable": false},
                    ]
                });

            }, error: function(response) {
                swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
        
    });

    //wyszukiwanie kategegorii
    $("#trainer_trainings_search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#trainer_trainings tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

});

</script>
@endsection