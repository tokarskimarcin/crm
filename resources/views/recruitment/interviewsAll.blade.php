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
    
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Rozmowy kwalifikacyjne</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <span class="glyphicon glyphicon-plus myIcon" style="color: #2cb74f"></span>
                            </div>
                            <div class="col-md-6">
                                <span class="pull-right" style="font-size: 50px; color: #aaa">23</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 myUnderLine">
                                Liczba udanych rekrutacji
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
                                <span class="glyphicon glyphicon-user myIcon" style="color: #2c5eb7"></span>
                            </div>
                            <div class="col-md-6">
                                <span class="pull-right" style="font-size: 50px; color: #aaa">{{($today_interviews != null) ? $today_interviews : 0}}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 myUnderLine">
                                Rozmów dzisiaj
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
                                <span class="glyphicon glyphicon-check myIcon" style="color: #b7512c"></span>
                            </div>
                            <div class="col-md-6">
                                <span class="pull-right" style="font-size: 50px; color: #aaa">{{$active_recruitments}}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 myUnderLine">
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
                                <span class="pull-right" style="font-size: 50px; color: #aaa">{{$total_trainings}}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 myUnderLine">
                                Dodanych szkoleń
                            </div>
                        </div>
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
                <h3 style="color: #3b3d3c">Rozmowy kwalifikacyjne</h3>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Od:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_start" name="date_start" type="text" value="{{date("Y-m-d")}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Do:</label>
                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" id="date_stop" name="date_stop" type="text" value="{{date("Y-m-d")}}" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button class="btn btn-info" style="width: 100%; margin-top: 33px" id="filter_interviews">
                                <span class="glyphicon glyphicon-ok"></span> Filtruj wyniki
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="myLabel">Wyszukaj:</label>
                            <input type="text" class="form-control" placeholder="Wyszukaj..." id="interviews_search"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table=striped thead-inverse">
                                <thead>
                                    <th>Dane kandydata</th>
                                    <th>Komentarz</th>
                                    <th>Data</th>
                                    <th>Godzina</th>
                                </thead>
                                <tbody id="my_interviews">

                                </tbody>
                            </table>
                            <div id="no_interviews" class="alert alert-danger" style="display: none">
                                Brak rozmów kwalifikacyjnych w podanym przedziale!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3 style="color: #3b3d3c">Nadchodzące szkolenia</h3>
                <div class="table-responsive">
                    <table class="table table-striped thead-inverse">
                        <thead>
                            <td style="width: 20%">Data szkolenia</td>
                            <td style="width: 20%">Godzina szkolenia</td>
                            <td style="width: 10%">Osób</td>
                            <td>Opis</th>
                            <td>Szczegóły</th>
                            <td></th>
                        </thead>
                        <tbody>
                            @foreach($incoming_trening as $item)
                                <tr>
                                    <td>{{$item->training_date}}</td>
                                    <td>{{$item->training_hour}}</td>
                                    <td>{{$item->candidates->count()}}</td>
                                    <td>{{$item->comment}}</td>
                                    <td>
                                        <button class="btn btn-info info_interview" data-id="{{$item->id}}" data-toggle="modal" data-target="#myModal">
                                            <span class="glyphicon glyphicon-envelope"></span> Szczegóły
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($incoming_trening->count() == 0)
                        <div class="alert alert-danger">
                            Brak nadchodzących szkoleń!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>Zrekrutowani kandydaci</h3>
                <div class="table-responsive">
                    <table class="table table-striped thead-inverse" id="candidates">
                        <thead>
                            <tr>
                                <th>Imie i nazwisko</th>
                                <th>Data dodania</th>
                                <th>Szczegóły</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Szczegóły szkolenia</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="myLabel">Data:</label>
                        <input type="text" class="form-control" readonly id="modal_training_date" value=""/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="myLabel">Godzina:</label>
                        <input type="text" class="form-control" readonly id="modal_training_hour"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="myLabel">Opis:</label>
                        <textarea class="form-control" readonly id="modal_training_comment" placeholder="Brak komentarza"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Lista osób na szkoleniu
                        </div>
                        <div class="panel-body" id="modal_training_candidates" >

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <a class="btn btn-info" href="{{ URL::to('/add_group_training') }}">
                            <span class="glyphicon glyphicon-pencil"></span> Edycja szkoleń
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

@endsection
@section('script')
<script>

$('.form_date').datetimepicker({
    language: 'pl',
    autoclose: 1,
    minView: 2,
    pickTime: false,
});

var today = '{{date('Y-m-d')}}';

var start_search = today;
var stop_search = today;

function myInterviews(start_search, stop_search) {

    $.ajax({
        type: "POST",
        url: '{{ route('api.myInterviews') }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            "start_search": start_search,
            "stop_search": stop_search
        },
        success: function (response) {
            $('#my_interviews tr').remove();

            var content = '';

            $.each(response, function(key, value) {
                content += `
                    <tr>
                        <td>${value.user_name} ${value.user_surname}</td>
                        <td>Komentarz</td>
                        <td>${value.interview_date.substr(0, 10)}</td>
                        <td>${value.interview_date.substr(11, 20)}</td>
                    </tr>
                `;
            });

            $('#my_interviews').append(content);

            if (response.length == 0) {
                $('#no_interviews').fadeIn(500);
            } else {
                $('#no_interviews').fadeOut(500);
            }
        }, error: function(response) {
            swal('Ups, coś poszło nie tak, skontaktuj się z administratorem!')
        }
    });
} 

$(document).ready(() => {
    myInterviews(start_search, stop_search);

    $("#interviews_search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#my_interviews tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $('#filter_interviews').click(() => {
        start_search = $('#date_start').val();
        stop_search = $('#date_stop').val();
        myInterviews(start_search, stop_search);
    });
});

$(document).ready(() => {
    $('.info_interview').click(function(e) {
        var interview_id = $(this).data('id');
        $('#modal_training_candidates div').remove();

        $.ajax({
            type: "POST",
            url: '{{ route('api.getGroupTrainingInfo') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                "id_training_group": interview_id,
                "cancel_candidate":  1
            },
            success: function (response) {
                console.log();
                var content = '';

                $.each(response.candidate, function(key, value) {
                    content += `
                        <div class="list-group-item">
                            ${value.last_name} ${value.first_name}
                        </div>
                    `;
                });

                var interview_data = response.group_training[0];

                $('#modal_training_candidates').append(content);
                $('#modal_training_date').val(interview_data.training_date);
                $('#modal_training_hour').val(interview_data.training_hour);
                $('#modal_training_comment').val(interview_data.comment);
            }
        });
    });
});

table = $('#candidates').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "drawCallback": function( settings ) {
    },
    "ajax": {
        'url': "{{ route('api.datatableShowCadreCandidates') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    },
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },"columns":[
        {"data": function (data, type, dataToSet) {
            var myName = data.first_name + " " + data.last_name;
            return myName;
        },"orderable": true, "searchable": true, "name": "last_name"},
        {"data": "created_at"},
        {"data": function (data, type, dataToSet) {
            return "<a href='{{ URL::to('/candidateProfile') }}/" + data.id +"' class='btn btn-info'><span class='glyphicon glyphicon-pencil'></span> Szczegóły</a>";
        },"orderable": false, "searchable": false},


         {{-- {"data": "name"},
        {"data": function (data, type, dataToSet) {
            var myType = data.status;
            if (myType == 1) {
                return 'Wykreowany';
            } else if (myType == 2) {
                return 'Aktywowany';
            } else if (myType == 3) {
                return 'Zakończony';
            } else if (myType == 4) {
                return 'Oceniono';
            }
        },"orderable": true, "searchable": false, "name": "status"},
        {"data": function (data, type, dataToSet) {
            var myName = data.first_name + " " + data.last_name;
            return myName;
        },"orderable": false, "searchable": true, "name": "last_name"},
        {"data": function (data, type, dataToSet) {
            return '<a class="btn btn-default" href="{{ URL::to('show_test_for_admin') }}/' + data.id + '">Szczegóły</a>';
        },"orderable": false, "searchable": false },  --}}
    ]
});

</script>
@endsection
