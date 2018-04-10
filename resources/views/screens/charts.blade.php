



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Document</title>
</head>
<body>
<div id="my_chart">

</div>
<div>

</div>

<button id='btn' value="CLICK">CLICKS</button>

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    var data1 = [];
    var data2 = [];
    var nameOfDepartment = [];
    var i = 0;
    var stringVariable = [];
    var depName;

    <?php
    $i = 0;
    ?>
    @foreach($department_info as $department_i)
        @foreach($reportData as $reportD)
            @if($department_i->id == $reportD->department_info_id)
            if(data1.length === 0) {
                data1.push(["Hour", "Average"]);
                depName = "{{$department_i->departments->name}} " + "{{$department_i->department_type->name}}";
                nameOfDepartment.push(depName);
            }
            stringVariable ="{{$reportD->hour}}";
            data1.push([stringVariable, parseFloat({{$reportD->average}})]);
            @endif
         @endforeach
                    if(data1.length != 1) {
                    data2.push(data1);
                    data1 = [];
            }
            data1 = [];
    @endforeach

    function drawChart() {
        var data = google.visualization.arrayToDataTable(data2[i]);
        i++;
        var options = {
            title: nameOfDepartment[i],
            lineWidth: 8,
            legend: { position: 'bottom' },
            width: 1600,
            height: 700
        };

        var chart = new google.visualization.LineChart(document.getElementById('my_chart'));

        chart.draw(data, options);
    }


    setInterval(myfunc, 4000);
    function myfunc() {
        drawChart();
        if(i == data2.length) {
            i = 0;
        }
    }
</script>


</body>
</html>





