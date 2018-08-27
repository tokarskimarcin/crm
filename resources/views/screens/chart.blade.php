{{--********************************************--}}
{{--THIS PAGE SHOWS DIAGRAMS OF AVERAGE VS HOUR --}}
{{--********************************************--}}

        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Document</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    #my_chart {
        width: 100vw;
        height: 100vh;
    }
</style>
<body>
<div id="my_chart">
</div>


</body>
@include('chartsScripts.averageDepartmentsChartScript')
<script>
    let departmentsAveragesForEveryHour = <?php echo json_encode($departmentsAveragesForEveryHour) ?>;
    let departmentsAveragesForEveryHourChartsData = prepareDataForCharts(departmentsAveragesForEveryHour);
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function (){
        let id = parseInt({{ $dep_info_id }});
        if(departmentsAveragesForEveryHourChartsData[id] !== undefined){
            var data = google.visualization.arrayToDataTable(departmentsAveragesForEveryHourChartsData[id]);
            drawChart(data, id, 'my_chart');
        }
    });

</script>
</html>





