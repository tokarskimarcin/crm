{{--/*--}}
{{--*@category: ,--}}
{{--*@info: This view show departments statitstics charts--}}
{{--*@controller: ,--}}
{{--*@methods: , --}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        .chart{
            /*overflow: scroll;*/
            height: 80vh;
            margin: auto;
            /*width: 80vw;*/
            font-size: xx-large;
            color: grey;
            text-align: center;
        }
        .navOption{
            cursor: pointer;
        }
        .bootstrap-select > .dropdown-menu {
            left: 0px !important;
        }

    </style>
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">
            Statystyki Telemarketing / Wykresy statystyk oddziałów
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel z wykresami
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="navOption active" id="dailyIntervals"><a >Statystyki dzienne</a></li>
                        <li role="presentation" class="navOption" id="hourlyIntervals"><a >Statystyki godzinowe</a></li>
                        <li role="presentation" id="departments">
                            <select class="form-control selectpicker" id="departmentsSelect">
                            </select>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="dailyIntervals">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date" class="myLabel">Data początkowa:</label>
                            <div class="input-group date form_date col-md-5" data-date=""
                                 data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" name="date_start" id="date_start" type="text"
                                       value="{{date("Y-m-d")}}">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date" class="myLabel">Data końcowa:</label>
                            <div class="input-group date form_date col-md-5" data-date=""
                                 data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" name="date_stop" id="date_stop" type="text"
                                       value="{{date("Y-m-d")}}">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hourlyIntervals" hidden>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date" class="myLabel">Data:</label>
                            <div class="input-group date form_date col-md-5" data-date=""
                                 data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input class="form-control" name="date" id="date" type="text"
                                       value="{{date("Y-m-d")}}">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" >
                    <div class="chart" id="chart">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('chartsScripts.averageDepartmentsChartScript')
@section('script')
    <script src="{{ asset('/js/moment.js')}}"></script>
    <script>
        let departmentsAveragesForEveryHour = null;
        let departmentsAveragesForEveryHourChartsData = null;
        let departmentsAveragesForEveryDay = null;
        let departmentsAveragesForEveryDayChartsData = null;
        let statisticsType = $('.navOption.active').attr('id');

        $(document).ready(function () {
            (function activateDatepicker() {
                $('.form_date').datetimepicker({
                    language: 'pl',
                    autoclose: 1,
                    minView: 2,
                    pickTime: false,
                    endDate: new Date(),
                    todayHighlight: true
                });
            })();

            getDepartmentsAveragesForEveryDayAjax($('#date_start').val(), $('#date_stop').val()).then( function (){
                return getDepartmentsAveragesForEveryHourAjax($('#date').val());
            }).then(function (resolve) {
                for(let i = 0 ; i < departmentsAveragesForEveryHour.length; i++){
                    //let divItemChart = $(document.createElement('div')).addClass('chart').attr('id','depChart_'+i);

                    let departmentValue = $(document.createElement('option')).val(i).text(departmentsAveragesForEveryHour[i].departmentName);
                    if(i !== 0){
                        //divItemChart.attr('hidden', true);
                    }else{
                        departmentValue.attr('selected', true);
                    }
                    //$('#charts').append(divItemChart);
                    $('#departmentsSelect').append(departmentValue);

                }
                $('#departmentsSelect').selectpicker('refresh');


                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(function (){
                    drawProperTypeChart($('#departmentsSelect').val());
                });
            });
        });


        function drawDepartmentsAveragesForEveryHourCharts(iterator){
            if(iterator !== null ){
                let depData = departmentsAveragesForEveryHour[iterator];
                if(depData !== undefined){
                    let id = depData.dep_info_id;
                    if(departmentsAveragesForEveryHourChartsData[id].length>1){
                        var data = google.visualization.arrayToDataTable(departmentsAveragesForEveryHourChartsData[id]);
                        drawChart(data, id, 'chart',0.6);
                    }else{
                        $('#chart').text('Brak danych');
                    }
                }else{
                    $('#chart').text('Brak danych');
                    console.log('Nie ma danych pod wybranym indeksem: ' + iterator);
                }
            }
        }

        function drawDepartmentsAveragesForEveryDayCharts(iterator){
            if(iterator !== null ){
                let depData = departmentsAveragesForEveryDay[iterator];
                if(depData !== undefined){
                    let id = depData.dep_info_id;
                    if(departmentsAveragesForEveryDayChartsData[id].length>1){
                        var data = google.visualization.arrayToDataTable(departmentsAveragesForEveryDayChartsData[id]);
                        drawChart(data, id, 'chart',0.6);
                    }else{
                        $('#chart').text('Brak danych');
                    }
                }else{
                    $('#chart').text('Brak danych');
                    console.log('Nie ma danych pod wybranym indeksem: ' + iterator);
                }
            }
        }

        $('.navOption').click(function (e) {
            if(!$(e.target).parent().hasClass('active')){
                $.each($('.navOption'), function (index, item) {
                    $('.'+item.id).hide();
                });
                $('.navOption').removeClass('active');
                $(e.target).parent().addClass('active');
                statisticsType = $(e.target).parent().attr('id');
                $('.'+statisticsType).show();
                drawProperTypeChart($('#departmentsSelect').val());
            }
        });

        $('#departmentsSelect').change(function (e){
            drawProperTypeChart($(e.target).val());
        });

        $('#date').change(function (e) {
            getDepartmentsAveragesForEveryHourAjax($(e.target).val()).then(function (resolve) {
                drawProperTypeChart($('#departmentsSelect').val());
            });
        });

        $('#date_start, #date_stop').change(function (e) {
            let dateStart = $('#date_start');
            let dateStop = $('#date_stop');
            if(moment(dateStart.val())> moment(dateStop.val())){
                if($(e.target).attr('id') === dateStart.attr('id')){
                    dateStop.val(dateStart.val());
                }else{
                    dateStart.val(dateStop.val());
                }
            }
            getDepartmentsAveragesForEveryDayAjax($('#date_start').val(), $('#date_stop').val()).then(function (resolve) {
                drawProperTypeChart($('#departmentsSelect').val());
            });
        });

        function drawProperTypeChart(value){
            if( statisticsType === 'dailyIntervals'){
                drawDepartmentsAveragesForEveryDayCharts(parseInt(value));
            }else if( statisticsType === 'hourlyIntervals') {
                drawDepartmentsAveragesForEveryHourCharts(parseInt(value));
            }
        }
        function getDepartmentsAveragesForEveryHourAjax(date) {
            return $.ajax({
                type: "POST",
                url: "{{route('api.getDepartmentsAveragesForEveryHourAjax')}}",
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: {
                    date: date
                },
                success: function (response) {
                    if(response !== false){
                        departmentsAveragesForEveryHour = response;
                        departmentsAveragesForEveryHourChartsData = prepareDataForCharts(departmentsAveragesForEveryHour);
                    }
                },
                error: function (jqXHR, textStatus, thrownError) {
                    console.log(jqXHR);
                    console.log('textStatus: ' + textStatus);
                    console.log('hrownError: ' + thrownError);
                    swal({
                        type: 'error',
                        title: 'Błąd ' + jqXHR.status,
                        text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                    });
                }
            });
        }
        function getDepartmentsAveragesForEveryDayAjax(dateStart, dateStop) {
            return $.ajax({
                type: "POST",
                url: "{{route('api.getDepartmentsAveragesForEveryDayAjax')}}",
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: {
                    date_start: dateStart,
                    date_stop: dateStop
                },
                success: function (response) {
                    if(response !== false){
                        departmentsAveragesForEveryDay = response;
                        departmentsAveragesForEveryDayChartsData = prepareDataForCharts(departmentsAveragesForEveryDay);
                    }
                },
                error: function (jqXHR, textStatus, thrownError) {
                    console.log(jqXHR);
                    console.log('textStatus: ' + textStatus);
                    console.log('hrownError: ' + thrownError);
                    swal({
                        type: 'error',
                        title: 'Błąd ' + jqXHR.status,
                        text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                    });
                }
            });
        }
        setInterval(function(){
            if($('#date').val() === moment().format('YYYY-MM-DD')  || $('#date_stop').val() === moment().format('YYYY-MM-DD') && moment().minute() === 5) {
                getDepartmentsAveragesForEveryDayAjax($('#date_start').val(), $('#date_stop').val()).then(function (){
                    return getDepartmentsAveragesForEveryHourAjax($('#date').val());
                }).then(function () {
                    drawProperTypeChart($('#departmentsSelect').val());
                });
            }
        }, 1000*60);
    </script>
@endsection
