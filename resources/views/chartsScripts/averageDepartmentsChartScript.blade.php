<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
    let departmentsNames = [];

    function prepareDataForCharts(departmentsAverages) {
        let departmentsChartsData = [];
        $.each(departmentsAverages, function (index, item) {
            let depReportChartData = [];
            depReportChartData.push(['Czas','Średnia', {type: 'string', role: 'annotation'}, "Low", "Max"]);
            if([2,8,14].indexOf(item.dep_info_id)>=0){
                $.each(item.depAverages, function (index, depAverages) {
                    depReportChartData.push([depAverages.time, parseFloat(depAverages.average), depAverages.average.toString(), 2, 3]);
                });
            }else{
                $.each(item.depAverages, function (index, depAverages) {
                    depReportChartData.push([depAverages.time, parseFloat(depAverages.average), depAverages.average.toString(), 2.5, 3.5]);
                });
            }
            departmentsChartsData[item.dep_info_id] = depReportChartData;
            departmentsNames[item.dep_info_id] = item.departmentName;
        });
        return departmentsChartsData;
    }

    function drawChart(data, id, srcElementToDraw, size = 1) {
        var options = {
            title: departmentsNames[id],
            lineWidth: 8*size,
            legend: { position: 'bottom' },
            // chartArea: {
            //     left: 30,
            //     top: 30
            // },
            fontSize: 24*size,
            fontName: "Tahoma",
            series: {
                0: {lineDashStyle: [2, 2, 20, 2, 20,2], color: 'red', lineWidth: 11*size},
                1: {lineWidth: 6*size, visibleInLegend: false, },
                2: {lineWidth: 6*size, visibleInLegend: false}
            },
            colors: ['#1A1567'],
            hAxis: {
                minorGridlines: {count: 5},
                textStyle: {
                    bold: true,
                    fontSize: 29*size
                }
            },
            vAxis: {
                gridlines: {count: 7},
                maxValue: 6,
                minValue: 0,
                textStyle: {
                    bold: true,
                    fontSize: 34*size
                }
            },
            titleTextStyle: {
                color: "#C21A01",
                fontSize: 70*size
            },
            viewWindowMode:'explicit',
            viewWindow:{
                max:6,
                min:0
            },
            annotations: {
                textStyle: {
                    fontSize: 34*size,
                    color: "black",
                    bold: true
                }
            }
        };
        var chart = new google.visualization.LineChart(document.getElementById(srcElementToDraw));
        chart.draw(data, options);
    }
</script>