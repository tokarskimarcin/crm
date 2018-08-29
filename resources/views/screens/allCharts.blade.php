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

@include('chartsScripts.averageDepartmentsChartScript')
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script type="text/javascript">
    let deferred = $.Deferred();
    let formData = new FormData();
    let departmentsAveragesForEveryHour = <?php echo json_encode($departmentsAveragesForEveryHour) ?>;
    let departmentsAveragesForEveryHourChartsData = prepareDataForCharts(departmentsAveragesForEveryHour);

    for( let i = 0; i < departmentsAveragesForEveryHour.length; i++){
        let newDepChartDiv = $(document.createElement('div')).attr('id', 'dep_'+i).addClass('chart');
        $('#my_charts').append(newDepChartDiv);
    }

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function (){
        for( let i = 0; i < departmentsAveragesForEveryHour.length; i++){
            let id = departmentsAveragesForEveryHour[i].dep_info_id;
            if(departmentsAveragesForEveryHourChartsData[id] !== undefined){
                var data = google.visualization.arrayToDataTable(departmentsAveragesForEveryHourChartsData[id]);
                drawChart(data, id, 'dep_'+i);
            }
        }
        deferred.resolve('Charts loaded');
    });

    $(document).ready(function () {
        deferred.promise().then(function (resolve) {
            console.log(resolve);
            html2canvas(document.querySelector('#my_charts'),{logging:false}).then(canvas => {
                saveImage(canvas,1);
                uploadFilesAjax();
            });
        },
        function (reject) {

        });
    });

    function saveImage(canvas, size){/*
        canvas.width = Math.floor(canvas.width*size);
        canvas.height = Math.floor(canvas.height*size);*/
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





