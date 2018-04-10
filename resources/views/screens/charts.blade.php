{{--THIS PAGE SHOWS DIAGRAMS OF AVERAGE VS HOUR --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Document</title>
</head>
<body>
<div id="my_chart">
</div>

<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    var data1 = [];
    var data2 = [];
    var nameOfDepartment = []; //array of departments name
    var i = 0; //iteration
    var stringVariable; //this variable contains string of hour
    var depName; //this variable contains name of given department

    @foreach($department_info as $department_i)
        @foreach($reportData as $reportD)
            @if($department_i->id == $reportD->department_info_id)
            if(data1.length === 0) {
                data1.push(["Godzina", "Średnia", {type: 'string', role: 'annotation'}]);
                depName = "{{$department_i->departments->name}} " + "{{$department_i->department_type->name}}";
                nameOfDepartment.push(depName);
            }
            stringVariable ="{{$reportD->hour}}";
            stringVariable = stringVariable.slice(0,5);
            data1.push([stringVariable, parseFloat({{$reportD->average}}), "{{$reportD->average}}"]);
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

        var options = {
            title: nameOfDepartment[i],
            lineWidth: 8,
            legend: { position: 'bottom' },
            // chartArea: {
            //     left: 30,
            //     top: 30
            // },
            fontSize: 24,
            fontName: "Tahoma",
            series: {
                0: {lineDashStyle: [2, 2, 20, 2, 20,2]}
            },
            colors: ['#1A1567'],
            hAxis: {
                minorGridlines: {count: 5},
                textStyle: {
                    bold: true,
                    fontSize: 27
                }
            },
            vAxis: {
                gridlines: {count: 6},
                maxValue: 6,
                minValue: 0,
                textStyle: {
                    bold: true,
                    fontSize: 32
                }
            },
            titleTextStyle: {
                color: "#C21A01",
                fontSize: 70
            },
            viewWindowMode:'explicit',
            viewWindow:{
                max:6,
                min:0
            },
            annotations: {
                textStyle: {
                    fontSize: 32,
                    color: "black",
                    bold: true
                }
            }

                    // width: 1700
        };

        var chart = new google.visualization.LineChart(document.getElementById('my_chart'));
        chart.draw(data, options);
        i++;
    }

    setInterval(myfunc, 10000);
    function myfunc() {
        drawChart();
        if(i == data2.length) {
            i = 0;
        }
    }
</script>

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
<script>
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
</body>
</html>





