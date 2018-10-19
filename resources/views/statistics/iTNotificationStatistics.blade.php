@php
    /**
     * Created by PhpStorm.
     * User: veronaprogramista
     * Date: 18.10.18
     * Time: 09:38
     */
@endphp

{{--/*--}}
{{--*@category: ,--}}
{{--*@info: This template view is for copy purpose--}}
{{--*@controller: ,--}}
{{--*@methods: , --}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <link rel="stylesheet" href="{{asset('/css/fixedHeader.dataTables.min.css')}}">
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/assets/css/VCtooltip.css')}}">
    <style>
        .bootstrap-select > .dropdown-menu{
            left: 0 !important;
        }
        .VCtooltip .well:hover {
            background-color: rgba(185,185,185,0.75) !important;
            cursor: help;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">Statystyki zgłoszeń pracowników IT</div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel statystyk zgłoszeń pracowników IT
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="programmerSelect">Programista</label>
                    <select id="programmerSelect" class="selectpicker form-control">
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Miesiąc:
                    <div class="form-group">
                        <div class='input-group date' id='monthDatetimepicker'>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input type='text' class="form-control" value="{{date('Y-m')}}" readonly/>
                        </div>
                    </div>
                    </label>
                </div>

                <div class="col-md-4"></div>
                <div class="col-md-2">
                    <div class="VCtooltip VCtooltip-left">
                        <div class="well well-sm" style="border-radius: 10%; background-color: #5bc0de; color: white; margin-bottom: 0;">Legenda <span class="glyphicon glyphicon-info-sign"></span></div>
                        <span class="tooltiptext">
                            <div class="alert alert-info">
                                Zmiana daty skutkuje zaktualizowaniem listy programistów, którzy w danym okresie mieli jakiekolwiek statystyki zgłoszeń.<br>
                                <strong>Not CNT</strong> (not count) oznacza, że średnia ocen nie wlicza się w średnią tygodniową, ponieważ nie było w danym dniu ocenionych zgłoszeń.
                            </div>
                        </span>
                    </div>
                </div>
            </div>
            <hr>
            <div id="statisticsSection">

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('/js/moment.js')}}"></script>
    <script>
        $(document).ready(function () {
            let VARIABLES = {
                data: null,
                jQElements:{
                    programmerSelect: $('#programmerSelect'),
                    monthDatetimepicker: $('#monthDatetimepicker').datetimepicker({
                        language: 'pl',
                        minView: 3,
                        startView: 3,
                        format: 'yyyy-mm',
                        startDate: moment('2017-01-01').format('YYYY-MM-DD'),
                        endDate: moment().format('YYYY-MM-DD')
                    }),
                    statisticsSection: $('#statisticsSection')
                },
            };

            let FUNCTIONS = {
                /* function grups should be before other functions which aren't grouped */
                EVENT_HANDLERS: {
                    programmerSelectHandler: {
                        change(){
                            VARIABLES.jQElements.programmerSelect.on('changed.bs.select', function (e) {
                                FUNCTIONS.generateTables(VARIABLES.data, parseInt($(e.target).val()));
                            });
                        }
                    },
                    monthDatetimepickerHandler: {
                        change() {
                            VARIABLES.jQElements.monthDatetimepicker.change(function () {
                                FUNCTIONS.AJAXs.iTNotificationsStatisticsAjax().then(function (response) {
                                    FUNCTIONS.fillProgrammerSelect(response.programmers);
                                    FUNCTIONS.generateTables(response, parseInt(VARIABLES.jQElements.programmerSelect.val()));
                                });
                            });
                        }
                    },
                    callEvents(){
                        FUNCTIONS.EVENT_HANDLERS.programmerSelectHandler.change();
                        FUNCTIONS.EVENT_HANDLERS.monthDatetimepickerHandler.change();
                    }
                },
                AJAXs: {
                    iTNotificationsStatisticsAjax() {
                        return $.ajax({
                            url: '{{route('api.iTNotificationsStatisticsAjax')}}',
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                                selectedMonth: VARIABLES.jQElements.monthDatetimepicker.find('input').val()
                            },
                            success(response) {
                                VARIABLES.data = response;
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
                        })
                    }
                },
                onlyUnique(value, index, self) {
                    return self.indexOf(value) === index;
                },
                fillProgrammerSelect(data){
                    VARIABLES.jQElements.programmerSelect.empty();
                    $.each(data, function (index, item) {
                        VARIABLES.jQElements.programmerSelect.append($('<option>').append(item.last_name+' '+item.first_name).val(item.id));
                    });
                    VARIABLES.jQElements.programmerSelect.selectpicker('refresh');
                },
                generateTables(data, selectedProgrammerId){
                    VARIABLES.jQElements.statisticsSection.empty();
                    let programmerITRealizedNotificationStatistics = null;
                    let programmerITUnrealizedNotificationStatistics = null;
                    if( data.iTRealizedNotificationStatistics.hasOwnProperty(selectedProgrammerId)){
                        programmerITRealizedNotificationStatistics = data.iTRealizedNotificationStatistics[selectedProgrammerId];
                    }
                    if( data.iTUnrealizedNotificationStatistics.hasOwnProperty(selectedProgrammerId)){
                        programmerITUnrealizedNotificationStatistics = data.iTUnrealizedNotificationStatistics[selectedProgrammerId];
                    }

                    $.each(VARIABLES.data.monthIntoCompanyWeeksDivision,function (index, week) {
                        let weekTable = $('<table>').addClass('table table-responsive thead-inverse table-striped table-condensed')
                            .append($('<thead>')
                                .append($('<tr>').css({'background-color':'black','color':'#efd88f'})
                                    .append($('<th>').attr('colspan',5).css({'padding':'1em'})
                                        .append($('<font>').attr('size',5).attr('face','Calibri')
                                            .append('Tydzień '+moment(week.firstDay).format('YYYY.MM.DD')+' - '+moment(week.lastDay).format('YYYY.MM.DD'))
                                        )
                                    )
                                )
                                .append($('<tr>').css({'background-color':'black','color':'#efd88f'})
                                    .append($('<th>').append('Dzień'))
                                    .append($('<th>').append('Lb. zgłoszeń'))
                                    .append($('<th>').append('Śr. ocen'))
                                    .append($('<th>').append('Lb. ocenionych'))
                                    .append($('<th>').append('Lb. w trakcie realizacji'))
                                )
                            );
                        let weekTableBody = $('<tbody>');
                        let notificationsSumCount = 0;
                        let weekAverageRating = 0;
                        let notificationsRatedSumCount = 0;
                        let daysDifference = moment(week.lastDay).diff(moment(week.firstDay), 'days');
                        let daysWithNotificationsRated = 0;
                        let programmerITUnrealizedNotificationWeek = [];

                        for(let i = 0; i <= daysDifference; i++){
                            let dayTr = $('<tr>').css({'text-align':'center'})
                                .append($('<td>').css({'font-weight':'bold'})
                                    .append(moment(week.firstDay).add(i,'d').format('YYYY-MM-DD'))
                                );
                            let notificationsCountTd = $('<td>').append('0');
                            let averageRatingTd = $('<td>').append('Not CNT');
                            let notificationsRatedCountTd = $('<td>').append('0');
                            let notificationsInProgressCountTd = $('<td>').append('0');
                            let notificationsInProgressCount = 0;
                            let currentDate = moment(week.firstDay).add(i,'d').format('YYYY-MM-DD');


                            if(programmerITRealizedNotificationStatistics !== null){
                                let dayStatistics = programmerITRealizedNotificationStatistics.find( x => x.date_stop === currentDate);
                                if(typeof dayStatistics !== 'undefined'){
                                    notificationsCountTd.empty().append(dayStatistics.notificationsCount);
                                    notificationsRatedCountTd.empty().append(dayStatistics.notificationsRatedCount);
                                    if(dayStatistics.notificationsRatedCount !== 0){
                                        daysWithNotificationsRated++;
                                        averageRatingTd.empty().append((Math.round(parseFloat(dayStatistics.average_rating)*10000)/100)+'%');
                                    }

                                    notificationsSumCount += dayStatistics.notificationsCount;
                                    weekAverageRating += dayStatistics.average_rating;
                                    notificationsRatedSumCount += dayStatistics.notificationsRatedCount;
                                }
                            }
                            if(programmerITUnrealizedNotificationStatistics !== null){
                                $.each(programmerITUnrealizedNotificationStatistics, function (index, item) {
                                    if(item.date_stop === null){
                                        if(moment(item.date_start).unix() <= moment(currentDate).unix() ){
                                            notificationsInProgressCount++;
                                            programmerITUnrealizedNotificationWeek.push(index);
                                        }
                                    }else{
                                        if(moment(item.date_start).unix() <= moment(currentDate).unix()
                                        && moment(item.date_stop).unix() > moment(currentDate).unix()){
                                            notificationsInProgressCount++;
                                            programmerITUnrealizedNotificationWeek.push(index);
                                        }
                                    }
                                });
                                notificationsInProgressCountTd.empty().append(notificationsInProgressCount);
                            }
                            dayTr.append(notificationsCountTd).append(averageRatingTd).append(notificationsRatedCountTd).append(notificationsInProgressCountTd);
                            weekTableBody.append(dayTr);
                        }

                        let weekSumTr = $('<tr>').css({'text-align':'center','font-weight':'bold', 'background-color':'#afafaf'})
                            .append($('<td>').append('TYDZIEŃ'))
                            .append($('<td>').append(notificationsSumCount))
                            .append($('<td>').append((Math.round(weekAverageRating*10000/daysWithNotificationsRated)/100)+'%'))
                            .append($('<td>').append(notificationsRatedSumCount))
                            .append($('<td>').append(programmerITUnrealizedNotificationWeek.filter(FUNCTIONS.onlyUnique).length));

                        weekTableBody.append(weekSumTr);
                        weekTable.append(weekTableBody);
                        VARIABLES.jQElements.statisticsSection.append(weekTable);
                    });
                }
            };

            FUNCTIONS.AJAXs.iTNotificationsStatisticsAjax().then(function (response) {
                FUNCTIONS.fillProgrammerSelect(response.programmers);
                FUNCTIONS.generateTables(response, parseInt(VARIABLES.jQElements.programmerSelect.val()));
            });
            FUNCTIONS.EVENT_HANDLERS.callEvents();
            resizeDatatablesOnMenuToggle();
        });
    </script>
@endsection
