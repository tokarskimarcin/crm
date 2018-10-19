@extends('layouts.main')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Pomoc / Ocena pracowników IT</div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        Panel z ocenami pracowników IT
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <label>Od:
                <div class='input-group date' id='startDatetimepicker' >
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                    <input type='text' class="form-control" name="date_start" value="{{date('Y-m-')}}01" readonly/>
                </div>
                </label>
            </div>
            <div class="col-md-3">
                <label>Do:
                <div class='input-group date' id='stopDatetimepicker' >
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                    <input type='text' class="form-control" name="date_stop" value="{{date('Y-m-').date('t')}}" readonly/>
                </div>
                </label>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="iTCadreNotificationsRatingsStatisticsTable" class="table table-bordered table-hover thead-inverse">
                        <thead>
                            <tr>
                                <th>Pracownik</th>
                                <th>Śr. ocen</th>
                                <th>Śr. czas realizacji</th>
                                <th>Śr. czas reakcji</th>
                            </tr>
                        </thead>
                        <tbody >
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
    <script src="{{ asset('/js/moment.js')}}"></script>
<script>
    let VARIABLES = {
        jQElements:{
            iTCadreNotificationsRatingsStatisticsTable: $('#iTCadreNotificationsRatingsStatisticsTable'),
            startDatetimepicker: $('#startDatetimepicker').datetimepicker({
                language: 'pl',
                minView: 2,
                startView: 2,
                format: 'yyyy-mm-dd',
                endDate: moment().format('YYYY-MM-DD')
            }),
            stopDatetimepicker: $('#stopDatetimepicker').datetimepicker({
                language: 'pl',
                minView: 2,
                startView: 2,
                format: 'yyyy-mm-dd',
                endDate: moment().endOf('month').format('YYYY-MM-DD')
            })
        },
        DATA_TABLES: {}
    };
    let FUNCTIONS = {
        /* function grups should be before other functions which aren't grouped */
        EVENT_HANDLERS: {
            callEvents(){
                VARIABLES.jQElements.startDatetimepicker.change(function () {
                    FUNCTIONS.AJAXs.iTCadreNotificationsRatingsStatisticsAjax().then(function (response) {
                        FUNCTIONS.generateTable(response);
                        $(".clickable-row").click(function() {
                            window.location = $(this).data("href");
                        });
                    });
                });
                VARIABLES.jQElements.stopDatetimepicker.change(function () {
                    FUNCTIONS.AJAXs.iTCadreNotificationsRatingsStatisticsAjax().then(function (response) {
                        FUNCTIONS.generateTable(response);
                        $(".clickable-row").click(function() {
                            window.location = $(this).data("href");
                        });
                    });
                });
            }
        },
        AJAXs: {
            iTCadreNotificationsRatingsStatisticsAjax(){
                return $.ajax({
                    url: '{{route('api.iTCadreNotificationsRatingsStatisticsAjax')}}',
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: {
                        startDate: VARIABLES.jQElements.startDatetimepicker.find('input').val(),
                        stopDate: VARIABLES.jQElements.stopDatetimepicker.find('input').val()
                    },
                    success(response) {
                        return response;
                    },
                    error(jqXHR, textStatus, thrownError) {
                        console.log(jqXHR);
                        console.log('textStatus: ' + textStatus);
                        console.log('thrownError: ' + thrownError);
                        swal({
                            type: 'error',
                            title: 'Błąd ' + jqXHR.status,
                            text: 'Wystąpił błąd: ' + thrownError + ' "' + jqXHR.responseJSON.message + '"',
                        });
                    }
                });
            }
        },
        secondsToDaysHoursMinutesSeconds(averageRealizationTime){
            let minutes = 60;
            let hours = minutes*60;
            let days = hours*24;
            let averageRealizationTimeString = '';
            let diff = Math.floor(averageRealizationTime/days);
            averageRealizationTimeString += diff+'D ';
            averageRealizationTime -= diff*days;
            diff = Math.floor(averageRealizationTime/hours);
            averageRealizationTimeString += (diff < 10 ? '0'+diff : diff)+':';
            averageRealizationTime -= diff*hours;
            diff = Math.floor(averageRealizationTime/minutes);
            averageRealizationTimeString += (diff < 10 ? '0'+diff : diff)+':';
            averageRealizationTime -= diff*minutes;
            averageRealizationTimeString += (averageRealizationTime < 10 ? '0'+averageRealizationTime : averageRealizationTime);
            return averageRealizationTimeString;
        },
        generateTable(response){
            let tbody = VARIABLES.jQElements.iTCadreNotificationsRatingsStatisticsTable.find('tbody');

            tbody.empty();

            let averageRating = 0;
            let notificationsWithRatingCount = 0;
            let averageRealizationTime = 0;
            let averageReactionTime = 0;
            $.each(response, function (index, programmer) {
                averageRating += programmer.averageRating;
                if(programmer.averageRating != null){
                    notificationsWithRatingCount++;
                }
                averageRealizationTime += parseInt(programmer.averageRealizationTime);
                averageReactionTime += parseInt(programmer.averageReactionTime);

                let programmerTr = $('<tr>')
                    .append($('<td>').append(programmer.displayedBy))
                    .append($('<td>').append(programmer.averageRating == null ? '-' : (Math.round(programmer.averageRating*10000)/100)+'%'))
                    .append($('<td>').append(FUNCTIONS.secondsToDaysHoursMinutesSeconds(programmer.averageRealizationTime)))
                    .append($('<td>').append(FUNCTIONS.secondsToDaysHoursMinutesSeconds(programmer.averageReactionTime)));
                if(programmer.status_work == 1){
                    programmerTr.addClass('clickable-row').attr('data-href','it_worker/'+programmer.id).css({'cursor':'pointer'});
                }
                tbody.append(programmerTr);
            });
            console.log(averageRealizationTime);
            averageRating = averageRating/notificationsWithRatingCount;
            averageRealizationTime = Math.floor(averageRealizationTime/response.length);
            averageReactionTime = Math.floor(averageReactionTime/response.length);
            tbody.append($('<tr>').css({'font-weight':'bold','background-color':'#afafaf'})
                .append($('<td>').append('Suma'))
                .append($('<td>').append((Math.round(averageRating*10000)/100)+'%'))
                .append($('<td>').append(FUNCTIONS.secondsToDaysHoursMinutesSeconds(averageRealizationTime)))
                .append($('<td>').append(FUNCTIONS.secondsToDaysHoursMinutesSeconds(averageReactionTime)))
            );
        }
    };

    FUNCTIONS.AJAXs.iTCadreNotificationsRatingsStatisticsAjax().then(function (response) {
        FUNCTIONS.generateTable(response);
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });
    FUNCTIONS.EVENT_HANDLERS.callEvents();
</script>
@endsection
