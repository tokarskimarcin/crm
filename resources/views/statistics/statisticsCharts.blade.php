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
            overflow: scroll;
            height: 80vh;
            margin: auto;
            /*width: 80vw;*/
        }
        .navOption{
            cursor: pointer;
        }
        .dropdown-menu {
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
                        <li role="presentation"  id="departments">
                            <select class="form-control selectpicker" id="departmentsSelect">
                            </select>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="dailyIntervals">
                    <div class="col-md-4" >
                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" {{--style="width:100%;"--}}>
                            <input class="form-control" name="start_date" type="text" value="{{date("Y-m-d")}}" readonly >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                    </div>
                    <div class="col-md-4" >
                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" {{--style="width:100%;"--}}>
                            <input class="form-control" name="stop_date" type="text" value="{{date("Y-m-d")}}" readonly >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                    </div>
                </div>
                <div class="hourlyIntervals" hidden>
                    <div class="col-md-4" >
                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" {{--style="width:100%;"--}}>
                            <input class="form-control" name="date" type="text" value="{{date("Y-m-d")}}" readonly >
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
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
    <script>
        /*let departmentsAveragesForEveryHour = <?php /*echo json_encode($departmentsAveragesForEveryHour)*/ ?>;
        let departmentsAveragesForEveryHourChartsData = prepareDataForCharts(departmentsAveragesForEveryHour);*/
        $(document).ready(function () {
            getDepartmentsAveragesForEveryHourAjax().then(function () {
                for(let i = 0 ; i < departmentsAveragesForEveryHour.length; i++){
                    //let divItemChart = $(document.createElement('div')).addClass('chart').attr('id','depChart_'+i);

                    let departmentValue = $(document.createElement('option')).val(i).text(departmentsAveragesForEveryHour[i].departmentName);
                    if(i != 0){
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
                    let statisticsType = $('.navOption active').attr('id');
                    if( statisticsType === 'dailyIntervals'){
                        $('#chart').text('');
                    }else if( statisticsType === 'hourlyIntervals'){
                        drawDepartmentsAveragesForEveryHourCharts($('#departmentsSelect').val());
                    }
                });
            });
        });


        function drawDepartmentsAveragesForEveryHourCharts(iterator){
            let id = departmentsAveragesForEveryHour[iterator].dep_info_id;
            var data = google.visualization.arrayToDataTable(departmentsAveragesForEveryHourChartsData[id]);
            drawChart(data, id, 'chart',0.5);
        }

        $('.navOption').click(function (e) {
            if(!$(e.target).parent().hasClass('active')){
                $('.navOption').removeClass('active');
                $(e.target).parent().addClass('active');
                let statisticsType = $(e.target).parent().attr('id');
                if( statisticsType === 'dailyIntervals'){
                    $('#chart').text('');
                }else if( statisticsType === 'hourlyIntervals'){
                    drawDepartmentsAveragesForEveryHourCharts($('#departmentsSelect').val());
                }
            }
        });

        $('#departmentsSelect').change(function (e){
            drawDepartmentsAveragesForEveryHourCharts($(e.target).val());
        });

        function getDepartmentsAveragesForEveryHourAjax() {
            return $.ajax({
                type: "POST",
                url: "{{route('api.getDepartmentsAveragesForEveryHourAjax')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {

                },
                success: function (response) {

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
    </script>
@endsection
