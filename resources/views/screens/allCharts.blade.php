{{--********************************************--}}
{{--THIS PAGE SHOWS DIAGRAMS OF AVERAGE VS HOUR --}}
{{--********************************************--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Document</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    #my_charts {
        width: 100vw;
        /*height: 100vh;*/
    }

    .chart {
        height: 100vh;
    }
</style>
<body>
<div id="my_charts">
</div>
</body>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    var data1 = [];
    var data2 = [];
    var nameOfDepartment = []; //array of departments name
    var stringVariable; //this variable contains string of hour
    var depName; //this variable contains name of given department

    var successBefore = null;
    var successAfter;
    var useBefore = null;
    var useAfter;
    var realAverageValue;
    var adnotation;
    let formData = new FormData;
    let deferred = $.Deferred();

    @foreach($department_info as $department_i)
    @if($department_i->id == "2" || $department_i->id == "14" || $department_i->id =="8") //3 departments
    @foreach($reportData as $reportD)
            @if($department_i->id == $reportD->department_info_id)
    if(data1.length === 0) {
        data1.push(["Godzina", "Średnia", {type: 'string', role: 'annotation'}, "Low", "Max"]);
        depName = "{{$department_i->departments->name}} " + "{{$department_i->department_type->name}}";
        nameOfDepartment.push(depName);
    }

    stringVariable ="{{$reportD->hour}}";
    stringVariable = stringVariable.slice(0,5);

    successAfter = parseFloat({{$reportD->success}});
    useAfter = parseFloat({{$reportD->hour_time_use}});
    if(successBefore === null || useBefore === null) { //First iteration, where variables are undefined
        data1.push([stringVariable, parseFloat({{$reportD->average}}), "{{$reportD->average}}", 2, 3]);
    }
    else{
        realAverageValue = Math.round(100 *((successAfter - successBefore) / (useAfter - useBefore)))/100;
        if((successAfter - successBefore) > 0 && (useAfter - useBefore) > 0) {
            //console.log('realAverageValue: ' + realAverageValue);
            adnotation = realAverageValue + '';
            data1.push([stringVariable, realAverageValue, adnotation, 2, 3]);
        }
    }

    successBefore = successAfter;
    useBefore  = useAfter;
    @endif
            @endforeach
    if(data1.length != 1) {
        data2.push(data1);
        data1 = [];
    }
    data1 = [];
    successAfter = 1;
    useAfter = 1;
    successBefore = null;
    useBefore = null;
    adnotation = '';
    @else //rest of departments excluding 3 above

    @foreach($reportData as $reportD)
            @if($department_i->id == $reportD->department_info_id)
    if(data1.length === 0) {
        data1.push(["Godzina", "Średnia", {type: 'string', role: 'annotation'}, "Low", "Max"]);
        depName = "{{$department_i->departments->name}} " + "{{$department_i->department_type->name}}";
        nameOfDepartment.push(depName);
    }

    stringVariable ="{{$reportD->hour}}";
    stringVariable = stringVariable.slice(0,5);

    successAfter = parseFloat({{$reportD->success}});
    useAfter = parseFloat({{$reportD->hour_time_use}});
    if(successBefore === null || useBefore === null) {
        data1.push([stringVariable, parseFloat({{$reportD->average}}), "{{$reportD->average}}", 2.5, 3.5]);
    }
    else{
        realAverageValue = Math.round(100 *((successAfter - successBefore) / (useAfter - useBefore)))/100;
        if((successAfter - successBefore) > 0 && (useAfter - useBefore) > 0) {
            //console.log('realAverageValue: ' + realAverageValue);
            adnotation = realAverageValue + '';
            data1.push([stringVariable, realAverageValue, adnotation, 2.5, 3.5]);
        }
    }

    successBefore = successAfter;
    useBefore  = useAfter;
    @endif
            @endforeach
    if(data1.length != 1) {
        data2.push(data1);
        data1 = [];
    }
    data1 = [];
    successAfter = 1;
    useAfter = 1;
    successBefore = null;
    useBefore = null;
    adnotation = '';
    @endif
    @endforeach


    for( let i = 0; i < data2.length; i++){
        let newDepChartDiv = $(document.createElement('div')).attr('id', 'dep_'+i).addClass('chart');
        $('#my_charts').append(newDepChartDiv);
    }

    google.charts.setOnLoadCallback(function (){
        for( let i = 0; i < data2.length; i++){
            drawChart(i);
        }
        deferred.resolve('Charts loaded');
    });


    $(document).ready(function () {
        deferred.promise().then(function (resolve) {
            console.log(resolve);
            html2canvas(document.querySelector('#my_charts'),{logging:false}).then(canvas => {
                saveImage(canvas);
                uploadFilesAjax();
            });
        },
        function (reject) {

        });
    });
    function drawChart(i) {
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
                0: {lineDashStyle: [2, 2, 20, 2, 20,2], color: 'red', lineWidth: 11},
                1: {lineWidth: 6, visibleInLegend: false},
                2: {lineWidth: 6, visibleInLegend: false}
            },
            colors: ['#1A1567'],
            hAxis: {
                minorGridlines: {count: 5},
                textStyle: {
                    bold: true,
                    fontSize: 29
                }
            },
            vAxis: {
                gridlines: {count: 7},
                maxValue: 6,
                minValue: 0,
                textStyle: {
                    bold: true,
                    fontSize: 34
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
                    fontSize: 34,
                    color: "black",
                    bold: true
                }
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('dep_'+i));
        chart.draw(data, options);
    }


    function saveImage(canvas){
        var dataURL = canvas.toDataURL('image/png');
        var blob = dataURItoBlob(dataURL);
        formData.append("allChartsImage", blob);
    }

    function dataURItoBlob(dataURI) {
        // convert base64/URLEncoded data component to raw binary data held in a string
        var byteString;
        if (dataURI.split(',')[0].indexOf('base64') >= 0)
            byteString = atob(dataURI.split(',')[1]);
        else
            byteString = unescape(dataURI.split(',')[1]);

        // separate out the mime component
        var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

        // write the bytes of the string to a typed array
        var ia = new Uint8Array(byteString.length);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ia], {type:mimeString});
    }

    function uploadFilesAjax(){
        console.log('Uploading screenshot');
        $.ajax({
            type: "POST",
            url: "{{route('api.uploadScreenshotsAjax')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            processData: false,
            data: formData,
            success: function (response) {
                if(response === 'success' ){
                    console.log('Screenshot uploaded');
                }else if(response === 'fail'){
                    console.log('Failed to upload screenshot');
                }
            },
            error: function (jqXHR, textStatus, thrownError) {
                console.log(jqXHR);
                console.log('textStatus: ' + textStatus);
                console.log('hrownError: ' + thrownError);

            }
        });
    }
</script>
</html>





