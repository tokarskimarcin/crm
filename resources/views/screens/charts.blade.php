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
        font-size: xx-large;
        color: grey;
        text-align: center;
    }
</style>
<body>
<div id="my_chart">
</div>
</body>

@include('chartsScripts.averageDepartmentsChartScript')
<script type="text/javascript">
    let departmentsAveragesForEveryHour = <?php echo json_encode($departmentsAveragesForEveryHour) ?>;
    let departmentsAveragesForEveryHourChartsData = prepareDataForCharts(departmentsAveragesForEveryHour);
    let iterator = 0;
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function (){
        let id = departmentsAveragesForEveryHour[iterator].dep_info_id;
        if(id !== -1){ //id of all deps averages data
            if(departmentsAveragesForEveryHourChartsData[id] !== undefined){
                if(departmentsAveragesForEveryHourChartsData[id].length>1) {
                    var data = google.visualization.arrayToDataTable(departmentsAveragesForEveryHourChartsData[id]);
                    drawChart(data, id, 'my_chart');
                }else{
                    $('#my_chart').text(departmentsAveragesForEveryHour[iterator].departmentName+' Brak danych');
                }
                iterator++;
            }else{
                console.log('No data');
            }
        }else{
            iterator++;
        }
    });

    setInterval(myfunc, 10000);
    function myfunc() {
        let id = departmentsAveragesForEveryHour[iterator].dep_info_id;
        if(id !== -1) { //id of all deps averages data
            if (departmentsAveragesForEveryHourChartsData[id].length > 1) {
                var data = google.visualization.arrayToDataTable(departmentsAveragesForEveryHourChartsData[id]);
                drawChart(data, id, 'my_chart');
            } else {
                $('#my_chart').text(departmentsAveragesForEveryHour[iterator].departmentName + ' Brak danych');
            }
        }else{
            iterator++;
        }
        iterator++;
        if(iterator === departmentsAveragesForEveryHour.length) {
            iterator = 0;
        }
    }

    var today = new Date();
    var today2 = new Date();
    setInterval(function(){
        today = new Date();
        if(today.getMinutes() == '5' && (today.getSeconds() == '1' || today.getSeconds() == '2')){
            window.location.reload(1);
        }
    }, 1000);
    var actualHour = today2.getHours();
    setInterval(function(){
        today2 = new Date();
        if(actualHour - today2.getHours() != 0) {
            window.location.reload(1);
            actualHour = today2.getHours()
        }
    }, 1000);
</script>
</html>





