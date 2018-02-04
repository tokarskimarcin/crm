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
                                <span class="pull-right" style="font-size: 50px; color: #aaa">{{$today_interviews}}</span>
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
                                <span class="pull-right" style="font-size: 50px; color: #aaa">7</span>
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
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <h1 style="color: #3b3d3c">Rozmowy kwalifikacyjne</h1>
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
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3 style="color: #3b3d3c">Nadchodzące szkolenia</h3>
                <div class="table-responsive">
                    <table class="table table-striped thead-inverse">
                        <thead>
                            <th>Data szkolenia</th>
                            <th>Szczegóły</th>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
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

</script>
@endsection
