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

        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
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
        let departmentsAveragesForEveryHour = <?php echo json_encode($departmentsAveragesForEveryHour) ?>;
        let departmentsAveragesForEveryHourChartsData = prepareDataForCharts(departmentsAveragesForEveryHour);
        let iterator = 0;
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(function (){
            let id = departmentsAveragesForEveryHour[iterator].dep_info_id;
            if(departmentsAveragesForEveryHourChartsData[id] !== undefined){
                var data = google.visualization.arrayToDataTable(departmentsAveragesForEveryHourChartsData[id]);
                drawChart(data, id, 'chart',0.5);
            }else{
                console.log('No data');
            }
        });

        //resizeDatatablesOnMenuToggle();
    </script>
@endsection
