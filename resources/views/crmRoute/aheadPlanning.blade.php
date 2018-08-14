{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view allows user to edit given hotel (DB table: "hotels"),--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: hotelGet, hotelPost--}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ asset('/css/fixedColumns.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('/css/fixedHeader.dataTables.min.css')}}" rel="stylesheet">


@endsection
@section('content')

    <style>

        .dataTable td{
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer */
            -webkit-user-select: none; /* Chrome, Safari, and Opera */
            -webkit-touch-callout: none; /* Disable Android and iOS callouts*/
        }

        #float {
            position: fixed;
            top: 3em;
            right: 2em;
            z-index: 100;
        }

        .heading-container {
            text-align: center;
            font-size: 2em;
            margin: 1em;
            font-weight: bold;
            box-shadow: 0 1px 15px 1px rgba(39, 39, 39, .1);
            padding-top: 1em;
            padding-bottom: 1em;
        }

        .form-container {
            box-shadow: 0 1px 15px 1px rgba(39, 39, 39, .1);
            padding-top: 1em;
            padding-bottom: 1em;
            margin: 1em;
        }

        .colorCell {
            background-color: #bcb7ff !important;
        }

        .selectedCell {
            border-color: blue !important;
            border-style: dashed !important;
            border-width: 1px !important;
        }

        .selectedRowDay{
            background: #bcb7ff !important;
        }
        .alert-info {
            font-size: 1.2em;
        }
        .thisDay{
            background: #fffc8b !important;
        }
        .dropdown-menu {
            left: 0px;
        }

        .warningResult{
            background: #ff7878 !important;
        }

        .glyphicon-info-sign:hover{
            color: #5bc0de;
        }
    </style>

    {{--Header page --}}
            <div class="page-header">
                <div class="alert gray-nav ">Planowanie Wyprzedzenia</div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Planowanie wyprzedzenia
                </div>
                <div class="alert alert-info">
                    Moduł planowanie wyprzedzenia zawiera tabelę pokazującą różnicę pomiędzy <i>zaproszeniami live</i> a ustalonymi <i>limitami</i> z zakładki <strong>informacje o kampaniach</strong> dla poszczególnych oddziałów dla określonych dni.
                    Kolumny można sumować w następujący sposób: Po pierwsze należy zaznaczyć pierwszą komórkę z sumy, przytrzymać lewy shift a następnie kliknąć ostatnią komórkę sumy.
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date" class="myLabel">Data początkowa:</label>
                                <div class="input-group date form_date col-md-5" data-date=""
                                     data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control" name="date_start" id="date_start" type="text"
                                           value="{{date("Y-m-d")}}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_stop" class="myLabel">Data końcowa:</label>
                                <div class="input-group date form_date col-md-5" data-date=""
                                     data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control" name="date_stop" id="date_stop" type="text"
                                           value="{{date("Y-m-d")}}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 buttonSection">
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-4">
                            <button class="btn btn-block btn-default" id="resultsSimulationButton">Symulacje wyników <span class="glyphicon glyphicon-chevron-down"></span></button>
                            <div class="simulationSection well well-sm">
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <select id="simulation" class="selectpicker form-control show-tick" title="Wybierz symulację wyników">
                                        </select>
                                    </div>
                                </div>
                                <div class="row factorsSection" style="margin-top:1em; display:none">
                                    <div class="col-md-6">
                                        <label>Mnożnik sobót <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                                                                   title="W przypadku, gdy średnie wyniki sobót wynoszą 0 to, te wyniki wyliczane są ze średnich dziennych pomnożonych o określony MNOŻNIK SOBÓT"></span></label>
                                        <div class="input-group">
                                            <input id="saturdayFactor" class="form-control" type="text" value="95" style="text-align: right;">
                                            <span class="input-group-addon" id="basic-addon1">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Mnożnik niedziel <span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
                                                                      title="W przypadku, gdy średnie wyniki niedziel wynoszą 0 to, te wyniki wyliczane są ze średnich sobót pomnożonych o określony MNOŻNIK NIEDZIEL"></span></label>
                                        <div class="input-group">
                                            <input id="sundayFactor" class="form-control" type="text" value="80" style="text-align: right;">
                                            <span class="input-group-addon" id="basic-addon1">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:1em;">
                                    <div class="col-md-6">
                                        <button class="btn btn-block btn-default" id="workFreeDaysButton" data-toggle="modal" data-target="#workFreeDaysModal"><span class="glyphicon glyphicon-calendar"></span> Dni wolne</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button id="simulationButton" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-blackboard"></span> Symuluj</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <button id="removeSimulationButton" class="btn btn-block btn-danger" style="display: none;"><span class="glyphicon glyphicon-remove"></span> Usuń symulację wyników</button>
                        </div>
                    </div>
                    <table id="datatable" class="table table-striped row-border" style="width:100%;">
                        <thead>
                        <tr>
                            <th>Tydzien</th>
                            <th>Dzień</th>
                            <th>Data</th>
                            @foreach($departmentInfo as $item)
                                <th>{{$item->name2.' '.$item->name}}</th>
                            @endforeach
                            <th>Suma</th>
                            <th>Podział</th>
                            {{--@foreach($departmentInfo as $item)--}}
                            {{--<th>CEL {{$item->name2.' '.$item->name}}</th>--}}
                            {{--@endforeach--}}
                        </tr>
                        </thead>
                        <tbody>
                        {{--@foreach($departmentInfo as $item)--}}
                        {{--<tr>--}}
                        {{--<td>1</td>--}}
                        {{--<td>2</td>--}}
                        {{--<td>3</td>--}}
                        {{--@foreach($departmentInfo as $item)--}}
                        {{--<td>1123123123123123</td>--}}
                        {{--@endforeach--}}
                        {{--<td>5</td>--}}
                        {{--<td>6</td>--}}
                        {{--@foreach($departmentInfo as $item)--}}
                        {{--<td>1123123123123123</td>--}}
                        {{--@endforeach--}}
                        {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--@foreach($departmentInfo as $item)--}}
                        {{--<tr>--}}
                        {{--<td>1</td>--}}
                        {{--<td>2</td>--}}
                        {{--<td>3</td>--}}
                        {{--@foreach($departmentInfo as $item)--}}
                        {{--<td>1123123123123123</td>--}}
                        {{--@endforeach--}}
                        {{--<td>5</td>--}}
                        {{--<td>6</td>--}}
                        {{--@foreach($departmentInfo as $item)--}}
                        {{--<td>1123123123123123</td>--}}
                        {{--@endforeach--}}
                        {{--</tr>--}}
                        {{--@endforeach--}}
                        </tbody>
                    </table>
                    <div class="row">
                    </div>
                </div>
            </div>
    <div class="modal fade" id="workFreeDaysModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Wyznaczanie dni wolnych dla poszczególnego oddziału</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button id="saveWorkFreeDays" type="button" class="btn btn-success" data-dismiss="modal">Zapisz</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('/js/fixedColumns.dataTables.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/moment.js')}}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            (function activateDatepicker() {
                $('.form_date').datetimepicker({
                    language: 'pl',
                    autoclose: 1,
                    minView: 2,
                    pickTime: false,
                });
            })();


            /********** GLOBAL VARIABLES ***********/

            let departmentInfo = <?php echo json_encode($departmentInfo->toArray()) ?>;

            let elementsToSum = {
                firstElement: {trId: null, tdId: null},
                lastElement: {trId: null, tdId: null}
            };

            let sumOfSelectedCells = 0;

            let factorsChanged = false;
            let selectedRowDays = [];

            let workFreeDaysForDepartments = {};

            const warningResult = {
                lowAheadDay: 2,
                highAheadDay: 15
            };
            const firstDayOfThisMonth = moment().format('YYYY-MM')+'-01';
            const today = moment().format('YYYY-MM-DD');
            const startDate = moment().add(-1,'w').format('YYYY-MM-DD');
            const stopDate = moment().add(3,'w').format('YYYY-MM-DD');
            /*******END OF GLOBAL VARIABLES*********/

            $('#date_start').val(startDate);
            $('#date_stop').val(stopDate);

            function fillWorkFreeDaysForDepartments() {
                let iterator = 1;
                while(moment(new Date(today)).add(iterator,'d') <= moment(new Date($('#date_stop').val()))){
                    let day = moment(new Date(today)).add(iterator,'d').format('YYYY-MM-DD');
                    if(!workFreeDaysForDepartments.hasOwnProperty(day)){
                        workFreeDaysForDepartments[day] = {};
                        $.each(departmentInfo,function (index, department) {
                            workFreeDaysForDepartments[day][department.name2] = false;
                        })
                    }
                    iterator++;
                }
            }

            /*********************DataTable FUNCTUONS****************************/
            let aheadPlanningData = {
                limitSimulation: null,
                newClientSimulation: null,
                data: {
                    aheadPlaning: null,
                    departmentsInvitationsAverages: null,
                    getCopyAheadPlaning: function () {
                        if(this.aheadPlaning === null){
                            return null;
                        }else{
                            let aheadPlaningCopy = [];
                            $.each(this.aheadPlaning,function (index, item) {
                                aheadPlaningCopy.push(Object.assign({},item));
                            });
                            return aheadPlaningCopy;
                        }
                    }
                },
                getData: function (startDate, stopDate) {
                    let deffered = $.Deferred();
                    let obj = this;
                    $.ajax({
                        url: "{{ route('api.getaHeadPlanningInfo') }}",
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        data: {
                            startDate: startDate,
                            stopDate: stopDate,
                            limitSimulation: obj.limitSimulation,
                            newClientSimulation: obj.newClientSimulation,
                            departmentsInvitationsAverages: obj.data.departmentsInvitationsAverages,
                            factors: {
                                isChanged: factorsChanged,
                                saturday: $('#saturdayFactor').val(),
                                sunday: $('#sundayFactor').val()
                            }
                        },
                        success: function (response) {
                            obj.data.aheadPlaning = response.aheadPlanningData;
                            obj.data.departmentsInvitationsAverages = response.departmentsInvitationsAveragesData;
                            deffered.resolve(obj.data.aheadPlaning);
                            fillWorkFreeDaysForDepartments();
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
                            deffered.reject();
                        }
                    });
                    selectedRowDays = [];
                    $('#removeSimulationButton').hide();
                    return deffered.promise();
                }
            };

            $('#datatable_processing').show();
            aheadPlanningData.getData($('#date_start').val(),$("#date_stop").val()).done(function (response) {
                aheadPlaningTable.setTableData(response);
                $('#datatable_processing').hide();
            });

            let aheadPlaningTable = {
                dataTable:  $('#datatable').DataTable({
                    //serverSide: true,
                    scrollY: '60vh',
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    processing: true,
                    fixedColumns: {
                        leftColumns: 3
                    },
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                    },
                    fnRowCallback:  function( nRow, aData, iDisplayIndex, iDisplayIndexFull ){
                        if(aData.day === today){
                            for(let i = 0 ; i< 3; i++){
                                $($(nRow).children()[i]).addClass('thisDay');
                            }
                        }

                        let simulatedActualDay = today;
                        if(selectedRowDays.hasOwnProperty(0)){
                            simulatedActualDay = moment(new Date(
                                $('#date_start').val())).add(selectedRowDays[0],'d').format('YYYY-MM-DD');
                        }
                        if(moment.duration(moment(new Date(aData.day)).diff(moment(new Date(simulatedActualDay)))).asDays() <= warningResult.lowAheadDay){
                            for(let i = 3 ; i< $(nRow).children().length - 1; i++){
                                if(parseInt($($(nRow).children()[i]).text()) < 0){
                                    $($(nRow).children()[i]).addClass('warningResult');
                                }
                            }
                        }

                        if(moment.duration(moment(new Date(aData.day)).diff(moment(new Date(simulatedActualDay)))).asDays() >= warningResult.highAheadDay){
                            for(let i = 3 ; i< $(nRow).children().length - 1; i++){
                                if(parseInt($($(nRow).children()[i]).text()) === 0){
                                    $($(nRow).children()[i]).addClass('warningResult');
                                }
                            }
                        }
                    },
                    fnDrawCallback: function () {
                        elementsToSum.firstElement.tdId = null;
                        elementsToSum.firstElement.trId = null;
                        elementsToSum.lastElement.tdId = null;
                        elementsToSum.lastElement.trId = null;
                        const allTd = document.querySelectorAll('td');
                        allTd.forEach(cell => {
                            if(cell.textContent == '0') {
                                cell.style.background = "#b9f4b7";
                            }
                        })
                    }, order: [[2,'asc']]
                    ,"columns": [
                        {"data": "numberOfWeek", orderable: false},
                        {"data": "dayName", orderable: false},
                        {"data": "day", orderable: false},
                            @foreach($departmentInfo as $item)
                        {
                            "data": `{{$item->name2}}`, "searchable": false, orderable: false
                        },
                            @endforeach
                        {
                            "data": "totalScore", orderable: false
                        },
                        {
                            "data": function (data, type, dataToSet) {
                                return data.allSet
                            }, "name": "allSet", orderable: false
                        }
                    ]
                }),

                setTableData: function (data){
                    let table = this.dataTable;
                    table.clear();
                    if($.isArray(data)) {
                        $.each(data, function (index, row) {
                            table.row.add(row).draw();
                        });
                    }
                }
            };


            /*********************EVENT LISTENERS FUNCTIONS****************************/


            $('#date_start, #date_stop').on('change', function () {
                $('#datatable_processing').show();
                aheadPlaningTable.dataTable.clear();
                aheadPlaningTable.dataTable.draw();
                aheadPlanningData.getData($('#date_start').val(),$("#date_stop").val()).done(function (response) {
                    aheadPlaningTable.setTableData(response);
                    $('#datatable_processing').hide();
                });
                //table.ajax.reload();
            });

            /**
             * This event listener finds row and column of clicked 'td' element and colors selected cells
             */
            $('#datatable').click((e) => {
                addOrRemoveClickedElement(e);

                /*if (elementsToSum.firstElement.tdId !== null
                    && (elementsToSum.firstElement.trId !== elementsToSum.lastElement.trId
                        || elementsToSum.firstElement.tdId !== elementsToSum.lastElement.tdId)) {
                    $('#sumButton').removeAttr('disabled');
                } else if (!$('#sumButton').prop('disabled'))
                    $('#sumButton').prop('disabled', true);*/
            });

            /*********************END EVENT LISTENERS FUNCTIONS****************************/

            /**
             * This function saves clicked cell positions (tr and td id's).
             * First cell is saved after click, second is saved after click + shift (if first is saved)
             */
            function addOrRemoveClickedElement(e) {
                let clickedElement = $(e.target);
                let trElement = clickedElement.parent();
                let tableElement = trElement.parent();
                let clickedElementTdIndex = trElement.children().index(clickedElement);
                let clickedElementTrIndex = tableElement.children().index(trElement);
                if (clickedElement.is('td') && clickedElementTdIndex >= 3 && clickedElementTdIndex < trElement.children().length - 1){
                    selectedRowDays = [];
                    if (e.shiftKey) {
                        if (elementsToSum.firstElement.tdId !== null) {
                            elementsToSum.lastElement.tdId = elementsToSum.lastElement.tdId; //clickedElementTdIndex;
                            elementsToSum.lastElement.trId = clickedElementTrIndex;
                            colorCellsRectangle(elementsToSum);
                            $.notify({
                                title: $($('#datatable tr').first().children().get(elementsToSum.firstElement.tdId)).text() + ': ',
                                message: '<strong>' + sumOfSelectedCells + '</strong>'
                            }, {
                                type: 'info',
                                mouse_over: 'pause',
                                placement: {
                                    from: "bottom",
                                    align: "right"
                                },
                            });
                        }
                    } else {
                        $('.selectedCell').removeClass('selectedCell');
                        clickedElement.addClass('selectedCell');
                        elementsToSum.firstElement.tdId = clickedElementTdIndex;
                        elementsToSum.firstElement.trId = clickedElementTrIndex;
                        elementsToSum.lastElement.tdId = clickedElementTdIndex;
                        elementsToSum.lastElement.trId = clickedElementTrIndex;
                        if (clickedElement.is('.colorCell')) {
                            $('.selectedCell').removeClass('selectedCell');
                            $('.colorCell').removeClass('colorCell');
                            elementsToSum.firstElement.tdId = null;
                            elementsToSum.firstElement.trId = null;
                            elementsToSum.lastElement.tdId = null;
                            elementsToSum.lastElement.trId = null;
                        } else
                            colorCellsRectangle(elementsToSum);
                    }
                }else if(clickedElement.is('td') && clickedElementTdIndex < 3){
                    $('.selectedCell').removeClass('selectedCell');
                    $('.colorCell').removeClass('colorCell');
                    let selectedSimulationsIndex = $('#simulation').val();
                    if(simulations[selectedSimulationsIndex]){
                        if(new Date() < new Date(getSelectedDay(clickedElementTrIndex))){
                            if(e.ctrlKey){
                                if($.inArray(clickedElementTrIndex, selectedRowDays) >= 0){
                                    selectedRowDays.splice(selectedRowDays.indexOf(clickedElementTrIndex),1);
                                }else if(selectedRowDays.length < simulations[selectedSimulationsIndex].availableSelectedDays){
                                    selectedRowDays.push(clickedElementTrIndex);
                                }
                            }else{
                                if($.inArray(clickedElementTrIndex, selectedRowDays) >= 0){
                                    selectedRowDays.splice(selectedRowDays.indexOf(clickedElementTrIndex),1);
                                }else {
                                    selectedRowDays = [];
                                    selectedRowDays.push(clickedElementTrIndex);
                                }
                            }
                        }else if(!e.ctrlKey){
                            selectedRowDays = [];
                        }

                    }else{
                        $.notify({
                            message: 'Wybierz typ symulacji wyników przed zaznaczeniem dni',

                        },{
                            type: 'info',
                            placement: {
                                from: "bottom",
                                align: "right"
                            }
                        });
                    }
                }

                colorSelectedRowDays();

            }

            function colorSelectedRowDays() {
                let colorClassSelectedRowDay = 'selectedRowDay';
                $('.'+colorClassSelectedRowDay).removeClass(colorClassSelectedRowDay);
                let tableTr = $('.DTFC_LeftBodyWrapper .table.dataTable tbody').children();
                $.each(selectedRowDays, function (index, item) {
                    let tds = $(tableTr[item]).children();
                    for(i = 0; i < 3; i++){
                        $(tds[i]).addClass(colorClassSelectedRowDay);
                    }
                });
            }

            function getSelectedDay(elementTrIndex) {
                let dayColumn = 2;
                let tableTr = $('.DTFC_LeftBodyWrapper .table.dataTable tbody').children();
                return $($(tableTr[elementTrIndex]).children()[dayColumn]).text();
            }
            /**
             * This function add class 'colorCell' to cells in array of cells appointed by two corner cells.
             * Elements is a object that has positions of first and last cell (tr and td id's)
             */
            function colorCellsRectangle(elements) {
                $('.colorCell').removeClass('colorCell');
                trElements = $('#datatable tr');

                //selecting left top and right bottom cells
                firstElementTrId = elements.firstElement.trId;
                firstElementTdId = elements.firstElement.tdId;
                lastElementTrId = elements.lastElement.trId;
                lastElementTdId = elements.lastElement.tdId;

                //if selected cells are not left top and right bottom cells, switch values properly
                //firstElement - left top corner, lastElement - right bottom corner
                if (firstElementTrId > lastElementTrId) {               //if first element is below last element
                    firstElementTrId = elementsToSum.lastElement.trId;
                    lastElementTrId = elementsToSum.firstElement.trId;
                    if (firstElementTdId > lastElementTdId) {           //if first element is on right side of last element
                        firstElementTdId = elementsToSum.lastElement.tdId;
                        lastElementTdId = elementsToSum.firstElement.tdId;
                    }
                } else if (firstElementTdId > lastElementTdId) {               //if first element is on right side of last element
                    firstElementTdId = elementsToSum.lastElement.tdId;
                    lastElementTdId = elementsToSum.firstElement.tdId;
                }

                sumOfSelectedCells = 0;
                //add class colorCell to all cell beetween first and last element
                for (var i = firstElementTrId; i <= lastElementTrId; i++) {
                    tdElements = $(trElements.get(i + 1)).children();
                    for (var j = firstElementTdId; j <= lastElementTdId; j++) {
                        $(tdElements.get(j)).addClass('colorCell');
                        sumOfSelectedCells += parseInt($(tdElements.get(j)).text());
                    }
                }

                //rightTopCell = $($(trElements.get(firstElementTrId + 1)).children().get(lastElementTdId));

            }

            //  simulations template
            function Simulation( name, availableSelectedDays, sectionsToShow, isChangingAheadPlanningData, simulateCallback, validateCallback) {
                return {
                    name: name,
                    availableSelectedDays: availableSelectedDays,
                    sectionsToShow: sectionsToShow,
                    isChangingAheadPlanningData: isChangingAheadPlanningData,
                    simulate: function () {
                        simulateCallback(this);
                    },
                    validate: function () {
                        return validateCallback(this);
                    }
                };
            }

            /*  ---------------------- creating available simulations ---------------------- */
            let simulations = [];

            simulations.push(Simulation(
                'Przewidywanie wyprzedzenia na wybrany dzień',
                1, // available days to select
                ['factorsSection'], //sections to show
                true, // flag that identify is ahead planning data after simulation are changed
                /* ---------------- simulation function ----------------------- */
                function (thisObj){
                    let selectedDay = getSelectedDay(selectedRowDays[0]);
                    let daysBetweenSelectedAndActualDay = Math.abs(moment.duration(moment(new Date(selectedDay)).diff(moment(new Date(today)))).asDays());
                    let workingHoursLeft = Math.abs(Math.round(moment.duration((moment().diff(moment().hour(16).minute(0)))).asHours()));
                    let simulatedData = null;

                    prepareSimulationData();
                    if(factorsChanged){
                        aheadPlanningData.getData($('#date_start').val(),$("#date_stop").val()).done(function () {
                            simulatedData = simulation();
                        });
                    }else{
                        simulatedData = simulation();
                    }

                    aheadPlaningTable.setTableData(simulatedData);
                    colorSelectedRowDays();

                    function prepareSimulationData() {
                        $.each(departmentInfo,function (index, department) {
                            department.multiplier = {
                                workingDays: 0,
                                saturdays: 0,
                                sundays: 0
                            };

                            // counting working days, saturdays and sundays for every department
                            for(let i = 0; i < daysBetweenSelectedAndActualDay; i++){
                                let dayOfWeek = moment(new Date(today)).add(i+1,'d').format('E');
                                let departmentFreeDay = workFreeDaysForDepartments[moment(new Date(today)).add(i+1,'d').format('YYYY-MM-DD')][department.name2];
                                if(!departmentFreeDay){
                                    if(dayOfWeek < 6){
                                        department.multiplier.workingDays += 1;
                                    }else if(dayOfWeek == 6){
                                        department.multiplier.saturdays += 1;
                                    }else{
                                        department.multiplier.sundays += 1;
                                    }
                                }
                            }
                        });

                        ///////////////////////////////////////////////////////////////
                        prepareTestingData();/// do usuniecia
                        ///////////////////////////////////////////////////////////////

                        //counting simulated result for every department
                        $.each(departmentInfo,function (index, department) {
                            department.simulatedResult = 0;
                            department.simulatedResult += department.multiplier.workingDays*aheadPlanningData.data.departmentsInvitationsAverages[department.name2].workingDays;
                            department.simulatedResult += department.multiplier.saturdays*aheadPlanningData.data.departmentsInvitationsAverages[department.name2].saturday;
                            department.simulatedResult += department.multiplier.sundays*aheadPlanningData.data.departmentsInvitationsAverages[department.name2].sunday;
                            let thisDayOfWeek = moment().format('E');
                            let thisDayMultiplier = workingHoursLeft/8;
                            let thisDaySimulatedResult = 0;
                            if(thisDayOfWeek < 6){
                                thisDaySimulatedResult = aheadPlanningData.data.departmentsInvitationsAverages[department.name2].workingDays*thisDayMultiplier;
                            }else if(thisDayOfWeek == 6){
                                thisDaySimulatedResult = aheadPlanningData.data.departmentsInvitationsAverages[department.name2].saturday*thisDayMultiplier;
                            }else{
                                thisDaySimulatedResult = aheadPlanningData.data.departmentsInvitationsAverages[department.name2].sunday*thisDayMultiplier;
                            }
                            department.simulatedResult += Math.round(thisDaySimulatedResult);
                        });
                    }

                    function simulation() {
                        let simulationData = aheadPlanningData.data.getCopyAheadPlaning();
                        $.each(simulationData, function (index, dayInfo) {
                            let depResultsSum = 0;
                            if(moment(new Date(dayInfo.day)) >= moment(new Date(today))){
                                $.each(departmentInfo,function (index, department) {
                                    if(department.simulatedResult >0){
                                        dayInfo[department.name2] += department.simulatedResult;
                                        if(dayInfo[department.name2] > 0){
                                            department.simulatedResult = dayInfo[department.name2];
                                            dayInfo[department.name2] = 0;
                                        }else{
                                            department.simulatedResult = 0;
                                        }
                                    }
                                    depResultsSum += dayInfo[department.name2];
                                });
                                dayInfo.totalScore = depResultsSum;
                            }
                        });
                        return simulationData;
                    }

                    function prepareTestingData() {
                        $.each(departmentInfo, function (index, department) {

                            let departmenAverages = aheadPlanningData.data.departmentsInvitationsAverages[department.name2];
                            departmenAverages.workingDays = Math.floor(Math.random()*400)+1200;
                            departmenAverages.saturday = departmenAverages.workingDays*95/100;
                            departmenAverages.sunday = departmenAverages.saturday*80/100;
                            aheadPlanningData.data.departmentsInvitationsAverages[department.name2] = departmenAverages;
                        });
                    }
                },
                /* ---------------- validation function ----------------------- */
                function (thisObj) {
                    if(selectedRowDays.length !== thisObj.availableSelectedDays){
                        swal('Wybierz wymaganą liczbę dni','Do przeprowadzenia symulacji wymagane jest wybranie dnia przewidywania wyprzedzenia','warning');
                        return false;
                    }else{
                        return true;
                    }
                }
            ));
            simulations.push(Simulation(
                'Wyliczenie średniej zaproszeń dla oddziałów do dnia',
                2, // available days to select
                [], //sections to show
                false, // flag that identify is ahead planning data after simulation are changed
                /* ---------------- simulation function ----------------------- */
                function (thisObj) {
                },
                /* ---------------- validation function ----------------------- */
                function (thisObj) {
                    if(selectedRowDays.length !== thisObj.availableSelectedDays){
                        swal('Wybierz wymaganą liczbę dni','Do przeprowadzenia symulacji wymagane jest wybranie wszystkich dni: dnia 1# - ostatni dzień dzwonienia; dnia 2# - dzień wyrobienia limitów','warning');
                        return false;
                    }else{
                        return true;
                    }
                }
            ));
            /*  ---------------------- end creating available simulations ---------------------- */

            // fill simulation select with created simulation objects
            $.each(simulations,function(index, item){
                $('#simulation').append($('<option>', {
                    value: index,
                    text: item.name
                }));
            });

            // hide simulation section
            $('.simulationSection').hide();

            // resultsSimulationButton toggle simulation section
            $('#resultsSimulationButton').click(function () {
               $('.simulationSection').slideToggle('slow', function () {

                   if($('.simulationSection').is(':hidden')){
                       $('#resultsSimulationButton span').removeClass().addClass('glyphicon glyphicon-chevron-down');
                   }else{
                       $('#resultsSimulationButton span').removeClass().addClass('glyphicon glyphicon-chevron-up');
                   }
               });
            });

            $('#simulation').change(function (e){
                //clear selected days
                selectedRowDays = [];
                colorSelectedRowDays();

                if($('#removeSimulationButton').is(':visible'))
                    $('#removeSimulationButton').click();
                // sections assigned to simulations are hidden or shown depending on selected simulation
                let simulationIndex = $(e.target).val();
                $.each(simulations,function (index, item) {
                        $.each(item.sectionsToShow,function (index, sectionToShow) {
                            $('.'+sectionToShow).hide();
                        });
                });
                $.each(simulations[simulationIndex].sectionsToShow,function (index, sectionToShow) {
                    $('.'+sectionToShow).show();
                });
            });

            $('#workFreeDaysButton').click(function () {
                let modalBody = $('#workFreeDaysModal .modal-body');
                modalBody.text('');
                let workFreeDaysTable = createWorkFreeDaysTable();
                modalBody.append(workFreeDaysTable);
            });
            $('#saveWorkFreeDays').click(function (e) {
                let workFreeDayCheckboxes = $('#workFreeDaysModal').find(':checkbox');
                $.each(workFreeDayCheckboxes, function (index, checkbox) {
                    workFreeDaysForDepartments[$(checkbox).data('date')][$(checkbox).data('name')] = checkbox.checked;
                });
                $.notify({
                    message: 'Dni wolne zapisane'
                }, {
                    type: 'success',
                    placement: {
                        from: "bottom",
                        align: "right"
                    }
                })
            });

            $('#simulationButton').click(function () {
                let simulationIndex = $('#simulation').val();
                if(simulations[simulationIndex]){
                    if(simulations[simulationIndex].validate()){
                        let height = $(window).scrollTop();
                        simulations[simulationIndex].simulate();
                        if(simulations[simulationIndex].isChangingAheadPlanningData){
                            $('#removeSimulationButton').show();
                        }
                        $(window).scrollTop(height);
                    }
                }else{
                    swal('Wybierz symulację');
                }
            });

            $('#removeSimulationButton').click(function (e) {
                let height = $(window).scrollTop();
                selectedRowDays = [];
                aheadPlaningTable.setTableData(aheadPlanningData.data.aheadPlaning);
                $(e.target).hide();
                $(window).scrollTop(height);
            });

            $('#saturdayFactor').change(function (e) {
                factorsChangeHandler(e,95);
            });
            $('#sundayFactor').change(function (e) {
                factorsChangeHandler(e,80);
            });

            function factorsChangeHandler(e, value){
                factorsChanged = $.isNumeric($(e.target).val());
                if(!factorsChanged){
                    $(e.target).val(value);
                }
            }

            function createWorkFreeDaysTable(){
                let tHeadTr = $(document.createElement('tr'));
                tHeadTr.append($(document.createElement('th')).text('Data'));
                $.each(departmentInfo,function (index, department) {
                    tHeadTr.append($(document.createElement('th')).text(department.name2));
                });
                let tHead = $(document.createElement('thead')).append(tHeadTr);

                let tBody = $(document.createElement('tbody'));
                $.each(workFreeDaysForDepartments,function (date, dayInfo) {
                    let tr = $(document.createElement('tr'));
                    if(moment(new Date(date))>moment(new Date(today))){
                        tr.append($(document.createElement('td')).css('cursor','pointer').append(date).click(function (e) {
                            $(e.target).parent().find(':checkbox').prop('checked',true)
                        }));
                        $.each(departmentInfo,function (index, department) {
                            tr.append($(document.createElement('td')).css({'text-align':'center'})
                                .append($(document.createElement('input')).attr('data-name',department.name2).attr('data-date',date)
                                    .prop('checked',dayInfo[department.name2])
                                    .prop('type','checkbox').css('display','inline-block')));
                        });
                        tBody.append(tr);
                    }
                });

                let workFreeDaysTable = $(document.createElement('table')).addClass('table table-striped').css('width','100%').prop('id','workFreeDaysTable');
                workFreeDaysTable.append(tHead).append(tBody);

                let uncheckButtonSpan = $(document.createElement('span')).addClass('glyphicon glyphicon-unchecked');
                let uncheckButton = $(document.createElement('button')).addClass('btn btn-default btn-block').append(uncheckButtonSpan).append(' Odznacz wszystko').click(function () {
                    $('#workFreeDaysModal').find(':checkbox').prop('checked',false);
                });
                let uncheckButtonColumn = $(document.createElement('div')).addClass('col-md-4').append(uncheckButton);
                let buttonsRow = $(document.createElement('div')).addClass('row').css({'padding-bottom':'1em','border-bottom-width':'1px','border-bottom-color':'#c1c1c1','border-bottom-style': 'solid'}).append(uncheckButtonColumn);
                let tableColumn = $(document.createElement('div')).addClass('col-md-12').append(workFreeDaysTable).css({'height':'75vh','overflow':'scroll'});
                let tableRow = $(document.createElement('div')).addClass('row').append(tableColumn);
                return  $(document.createElement('div')).append(buttonsRow).append(tableRow);
            }
        });
    </script>
@endsection
