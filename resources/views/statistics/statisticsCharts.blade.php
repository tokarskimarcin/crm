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
            width: 80vw;
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
                    </ul>
                </div>
                <div class="col-md-12" >
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                                <div class="chart" id="dep_0">
                                </div>
                            </div>
                            <div class="item">
                                <div class="chart" id="dep_1">
                                </div>
                            </div>
                        </div>

                        <!-- Controls -->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-12" >
                </div>
            </div>
        </div>
    </div>
@endsection
@include('chartsScripts.averageDepartmentsChartScript')
@section('script')
    <script>
        let departmentsAveragesForEveryHour = <?php echo json_encode($departmentsAveragesForEveryHour) ?>;
        let departmentsAveragesForEveryHourChartsData = prepareDataForCharts(departmentsAveragesForEveryHour);
        let iterator = 0;
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(function (){
            drawDepartmentsAveragesForHour(0);
        });

        drawDepartmentsAveragesForHour(1);

        function drawDepartmentsAveragesForHour(iterator){
            let id = departmentsAveragesForEveryHour[iterator].dep_info_id;
            if(departmentsAveragesForEveryHourChartsData[id] !== undefined){
                var data = google.visualization.arrayToDataTable(departmentsAveragesForEveryHourChartsData[id]);
                drawChart(data, id, 'dep_'+iterator,0.5);
            }else{
                console.log('No data');
            }
        }

        $('.navOption').click(function (e) {
            if(!$(e.target).parent().hasClass('active')){
                $('.navOption').removeClass('active');
                $(e.target).parent().addClass('active');
                let statisticsType = $(e.target).parent().attr('id');
                if( statisticsType === 'dailyIntervals'){

                }else if( statisticsType === 'hourlyIntervals'){

                }
            }
        });
    </script>
@endsection
