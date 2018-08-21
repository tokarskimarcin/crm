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

        .colorCell {
            background-color: #bcb7ff !important;
        }

        .selectedCell {
            border-color: blue !important;
            border-style: dashed !important;
            border-width: 1px !important;
        }

        .glyphicon-info-sign:hover{
            color: #5bc0de;
        }

        .myHR{
            border-bottom: 3px;
            background: black;
            margin: 1%;
        }
        .separate{
            margin: 1%;
        }
        .separateBTN{
            margin-top: 1%;
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

                        <div id="test">

                        </div>

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
                            <div class="simulationSection well well-sm" style="display:none">
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
                        @if(Auth::user()->user_type_id != 8)
                        <div class="col-md-4">
                            <button class="btn btn-default simulationClientLimit"  style="width: 100%" data-toggle="modal" data-target="#modalSimulationClient" >Symulacja Klienta(Edycja Limitów)</button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-default simulationNewClient"  style="width: 100%" data-toggle="modal"  data-target="#modalSimulationNewClient" >Symulacja Klienta(Nowy Klient)</button>
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-danger resetSimulationClientLimit"  style="width: 100%" data-toggle="modal" >Resetuj Symulacje</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <button id="removeSimulationButton" class="btn btn-block btn-danger" style="display: none;"><span class="glyphicon glyphicon-remove"></span> Usuń symulację wyników</button>
                        </div>
                    </div>
                    @endif
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

    <div class="modal fade" id="simulationAverages" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Wyliczone średnie zaproszeń na dzień dla poszczególnego oddziału</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div id="modalSimulationClient" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal_title">Sekcja symulatcji<span id="modal_category"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Symalacja limitów klienta
                        </div>
                        <div class="panel-body">
                            <div id="placeToAppendClientLimit"></div>
                            <div class="col-md-12">
                                <button class="btn btn-info separateBTN renderSimulation" style="width: 100%">
                                    <span class="glyphicon glyphicon-cloud"></span> <span>Pokaż symulację</span>
                                </button>
                                <button class="btn btn-success separateBTN saveSimulation" style="width: 100%">
                                    <span class="glyphicon glyphicon-save"></span> <span>Zapisz symulację</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="modalSimulationNewClient" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 90%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal_title">Sekcja symulatcji Nowego Klienta<span></span></h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Symalacja nowego klienta
                        </div>
                        <div class="panel-body">
                            <div id="placeToAppendNewClient"></div>
                            <div class="col-md-12">
                                <button class="btn btn-info separateBTN renderSimulationNewClient" style="width: 100%">
                                    <span class="glyphicon glyphicon-cloud"></span> <span>Pokaż symulację</span>
                                </button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('/js/fixedColumns.dataTables.min.js')}}"></script>
    <script src="{{ asset('/js/polishSelectPicker.js')}}"></script>
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

            class SimulationChangeLimitForClient{
                constructor(arrayOfClinet, dateStart,dateStop,arrayOfLimit,limitForOneHour,saveStatus){
                    this.arrayOfClinet      = arrayOfClinet;
                    this.dateStart          = dateStart;
                    this.dateStop           = dateStop;
                    this.arrayOfLimit       = arrayOfLimit;
                    this.limitForOneHour    = limitForOneHour;
                    this.saveStatus         = saveStatus;
                }
            }

            class SimulationNewClientClient{
                constructor(dayCountEventArray, arrayOfNumberWeekNewClient,arrayOfLimit,year){
                    this.dayCountEventArray                     = dayCountEventArray;
                    this.arrayOfNumberWeekNewClient             = arrayOfNumberWeekNewClient;
                    this.arrayOfLimit                           = arrayOfLimit;
                    this.year                                   = year;
                }
            }


            var simulationEditLimitArray = [];
            var simulationNewClientArray = [];
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
                            if(moment(new Date(day)).format('E') == 7 ){
                                workFreeDaysForDepartments[day][department.name2] = true;

                            }else{
                                workFreeDaysForDepartments[day][department.name2] = false;
                            }
                        })
                    }
                    iterator++;
                }
            }


            /********************Change Limit Simulation*************************/
            function reloadDatePicker() {
                $('.form_date').datetimepicker({
                    language: 'pl',
                    autoclose: 1,
                    minView: 2,
                    pickTime: false,
                });
            }
            function reloadSelectPicker() {
                $('.selectpicker').selectpicker({
                    selectAllText: 'Zaznacz wszystkie',
                    deselectAllText: 'Odznacz wszystkie'
                });
            }
            function getColMDConteiner() {
                let divChoiceClientLimit = document.createElement('div');
                divChoiceClientLimit.classList.add('col-md-4');
                return divChoiceClientLimit;
            }
            function getInputGroup() {
                let divChoiceClientLimitInputGroup = document.createElement('div');
                divChoiceClientLimitInputGroup.classList.add('input-group');
                return divChoiceClientLimitInputGroup;
            }
            function getSpan(spanText) {
                let divChoiceClientLimitSpan = document.createElement('span');
                divChoiceClientLimitSpan.classList.add('input-group-addon');
                divChoiceClientLimitSpan.textContent = spanText;
                return divChoiceClientLimitSpan;
            }
            function getSelectWithPicker(multiSelect = true,className = true) {
                let divChoiceClientLimitSelect = document.createElement('select');
                divChoiceClientLimitSelect.classList.add('form-control');
                divChoiceClientLimitSelect.classList.add('selectpicker');
                divChoiceClientLimitSelect.setAttribute('data-live-search','true');
                divChoiceClientLimitSelect.setAttribute('data-selected-text-format','count');
                divChoiceClientLimitSelect.setAttribute('data-width','100%');
                if(className){
                    divChoiceClientLimitSelect.classList.add('selectedClientToChangeLimit');
                }
                if(multiSelect){
                    divChoiceClientLimitSelect.setAttribute('multiple','multiple');
                }
                return divChoiceClientLimitSelect;
            }
            function getSelectOption(optionText,id) {
                let divChoiceClientLimitSelectOption = document.createElement('option');
                divChoiceClientLimitSelectOption.text = optionText;
                divChoiceClientLimitSelectOption.value = id;
                return divChoiceClientLimitSelectOption;
            }
            function getDateDiv() {
                let divDate = document.createElement('div');
                divDate.classList.add('input-group');
                divDate.classList.add('date');
                divDate.classList.add('form_date');
                divDate.classList.add('col-md-5');
                divDate.setAttribute('data-date-format',"yyyy-mm-dd");
                divDate.setAttribute('data-link-field',"datak");
                divDate.setAttribute('style','width:100%');
                return divDate;
            }
            function getInputDate(className,dateValue) {
                let inputDate = document.createElement('input');
                inputDate.classList.add('form-control');
                inputDate.classList.add(className);
                inputDate.setAttribute('name',className);
                inputDate.setAttribute('type','text');
                inputDate.value = dateValue;
                return inputDate;
            }
            function getGlyphiconDate() {
                let dateSpan = document.createElement('span');
                dateSpan.classList.add('glyphicon');
                dateSpan.classList.add('glyphicon-th');
                let outSideSpan = getSpan('');
                outSideSpan.appendChild(dateSpan);
                return outSideSpan;
            }
            function getGlyphicon(type) {
                let glyphicon = document.createElement('span');
                glyphicon.classList.add('glyphicon', 'glyphicon-'+type);
                return glyphicon
            }
            var polishDayArray = ["Poniedziałek","Wtorek","Środa","Czwartek","Piątek","Sobota","Niedziela","Suma"];
            function TemplateDOMOfNewClientSimulation(placeToAppend) {
                let mainDiv = document.createElement('div');
                mainDiv.classList.add('col-md-12');
                mainDiv.classList.add('newClientSimulation');
                let dayEventCount= document.createElement('div');
                dayEventCount.classList.add('dayEventCount');

                let tableDayEventCount = document.createElement('table');
                tableDayEventCount.classList.add('tableDayEventCount');
                tableDayEventCount.classList.add('table');

                let tableThead = document.createElement('thead');
                let tableTheadTr = document.createElement('tr');
                polishDayArray.forEach(function (value) {
                    let tableTheadTh = document.createElement('th');
                        tableTheadTh.textContent = value;
                    tableTheadTr.appendChild(tableTheadTh);
                });
                tableThead.appendChild(tableTheadTr);
                tableDayEventCount.appendChild(tableThead);

                let tableTbody = document.createElement('tbody');
                let tableTbodyTr = document.createElement('tr');
                polishDayArray.forEach(function (value,index) {
                    let tableTbodyTd = document.createElement('td');
                    let tableTbodyTdInput = document.createElement('input');
                    tableTbodyTdInput.classList.add('form-control');
                    if(polishDayArray.length-1 == index){
                        tableTbodyTdInput.classList.add('dayEventCountSum');
                        tableTbodyTdInput.setAttribute("disabled","true");
                    }else{
                        tableTbodyTdInput.classList.add('dayEventCountNumber'+(index+1));
                        tableTbodyTdInput.classList.add('dayEventCountNumber');
                    }
                    tableTbodyTd.appendChild(tableTbodyTdInput);
                    tableTbodyTr.appendChild(tableTbodyTd);
                });
                tableTbody.appendChild(tableTbodyTr);
                tableDayEventCount.appendChild(tableTbody);
                dayEventCount.appendChild(tableDayEventCount);

                let limitSectionNewClient = document.createElement('div');
                limitSectionNewClient.classList.add('limitSectionNewClient');

                let divlimitNewClientInput = document.createElement('div');
                divlimitNewClientInput.classList.add('col-md-6');
                let divlimitNewClientInputLabel = document.createElement('label');
                divlimitNewClientInputLabel.textContent = "Limitu dla pokazów";
                divlimitNewClientInput.appendChild(divlimitNewClientInputLabel);

                for( let i =0;i<3;i++){
                    let limitInput = document.createElement('div');
                    limitInput.classList.add('input-group','limitNewClientInput');
                    let span = getSpan('Limit #'+(i+1));
                    let inputLimit = getInputDate('NewClientLimit'+(i+1),'');
                    inputLimit.classList.add('NewClientLimit');
                    limitInput.appendChild(span);
                    limitInput.appendChild(inputLimit);
                    divlimitNewClientInput.appendChild(limitInput);
                }
                limitSectionNewClient.appendChild(divlimitNewClientInput);

                let divWeekNumber = document.createElement('div');
                divWeekNumber.classList.add('col-md-6');


                let divLimitWeekLabel = document.createElement('label');
                divLimitWeekLabel.textContent = "Przedział czasowy nowego klienta";
                divWeekNumber.appendChild(divLimitWeekLabel);

                let yearInput = document.createElement('div');
                yearInput.classList.add('input-group','yearNewClientInput');
                let span = getSpan('Rok');
                let selectYear = getSelectWithPicker(false,false);
                selectYear.classList.add('NewClientYear');
                for(let i = -1; i <= 1; i++){
                    let divChoiceClientYearOption = getSelectOption(new Date().getFullYear()+i,new Date().getFullYear()+i);
                    if(new Date().getFullYear() == new Date().getFullYear()+i)
                        divChoiceClientYearOption.setAttribute('selected','selected');
                    selectYear.add(divChoiceClientYearOption);
                }
                yearInput.appendChild(span);
                yearInput.appendChild(selectYear);
                divWeekNumber.appendChild(yearInput);

                //Create colMD Conteiner
                let divChoiceWeekNewClient = getColMDConteiner();
                //Create InputGroupDiv
                let divInputGroup = getInputGroup();
                divChoiceWeekNewClient.appendChild(divInputGroup);
                //Create Span
                let divSpan = getSpan('Wybierz tydzień');
                //Append Span to container
                divInputGroup.appendChild(divSpan);
                //Create Select
                let divChoiceClientWeekSelect = getSelectWithPicker(true,false);
                divChoiceClientWeekSelect.classList.add('NewClientWeek');
                divChoiceClientWeekSelect.title = "Wybierz tydzień...";
                // Select Optio
                for(let i = 1; i < 53 ;i++) {
                    let divChoiceClientWeekOption = getSelectOption(i,i);
                    //Append Select Option
                    divChoiceClientWeekSelect.add(divChoiceClientWeekOption);
                }
                //Append Select to Input Group
                divInputGroup.appendChild(divChoiceClientWeekSelect);
                divWeekNumber.appendChild(divInputGroup);

                limitSectionNewClient.appendChild(divWeekNumber);


                let divLimitConteinerButton = document.createElement('div');
                divLimitConteinerButton.classList.add('col-md-12');

                let divAddLimitButton = document.createElement('button');
                divAddLimitButton.classList.add('btn','btn-default','separate','AddNewClientSimulationBTN');
                divAddLimitButton.addEventListener('click',function (e) {
                    TemplateDOMOfNewClientSimulation(placeToAppend);
                });
                let glyphicon = getGlyphicon('plus');
                divAddLimitButton.appendChild(glyphicon);
                divSpan = getSpan('Dodaj Symulację Kolejnego Klienta');
                divSpan.classList.remove('input-group-addon');
                divAddLimitButton.appendChild(divSpan);
                divLimitConteinerButton.appendChild(divAddLimitButton);


                let divRemoveLimitButton = document.createElement('button');
                divRemoveLimitButton.classList.add('btn','btn-danger','separate','RemoveSimulationNewClientBTN');
                divRemoveLimitButton.addEventListener('click',function (e) {
                    if(document.getElementsByClassName('newClientSimulation').length != 1)
                        e.target.closest('.newClientSimulation').remove();
                    else{
                        swal('Nie ma co usunąć')
                    }
                });
                glyphicon = getGlyphicon('minus');
                divRemoveLimitButton.appendChild(glyphicon);
                divSpan = getSpan('Usuń Symulację');
                divSpan.classList.remove('input-group-addon');
                divRemoveLimitButton.appendChild(divSpan);
                divLimitConteinerButton.appendChild(divRemoveLimitButton);
                limitSectionNewClient.appendChild(divLimitConteinerButton);
                mainDiv.appendChild(dayEventCount);
                mainDiv.appendChild(limitSectionNewClient);
                document.getElementById(placeToAppend).appendChild(mainDiv);
                reloadDatePicker();
                reloadSelectPicker();
            }
            TemplateDOMOfNewClientSimulation('placeToAppendNewClient');
            function TemplateDOMOfClientSimulationEditLimits(placeToAppend) {
                let mainDiv = document.createElement('div');
                mainDiv.classList.add('col-md-12');
                mainDiv.classList.add('changeClientLimitContener');

                let divChangeClientLimit = document.createElement('div');
                divChangeClientLimit.classList.add('changeClientLimit');

                //Create colMD Conteiner
                let divChoiceClientLimit = getColMDConteiner();
                //Create InputGroupDiv
                let divInputGroup = getInputGroup();
                divChoiceClientLimit.appendChild(divInputGroup);
                //Create Span
                let divSpan = getSpan('Wybierz klienta');
                //Append Span to container
                divInputGroup.appendChild(divSpan);

                //Create Select
                let divChoiceClientLimitSelect = getSelectWithPicker();
                divChoiceClientLimitSelect.title = "Wybierz klientów...";

                // Select Option
                let clients =  {!! json_encode($allClients) !!};
                clients.forEach(function (value,key) {
                    let divChoiceClientLimitSelectOption = getSelectOption(value.name,value.id);
                    //Append Select Option
                    divChoiceClientLimitSelect.add(divChoiceClientLimitSelectOption);
                });


                //Append Select to Input Group
                divInputGroup.appendChild(divChoiceClientLimitSelect);

                // END FIRST COL

                // Start Second COL
                let divDateStartClientLimit = getColMDConteiner();
                //Create InputGroupDiv
                divInputGroup = getInputGroup();
                divDateStartClientLimit.appendChild(divInputGroup);
                //Create Span
                divSpan  = getSpan('Data od');
                //Append Span to container
                divInputGroup.appendChild(divSpan);
                //Create Date Div
                let divDate = getDateDiv();
                //Create Input Datepicker
                let inputDate = getInputDate('dateStartLimit',"{{date("Y-m-d")}}");
                divDate.appendChild(inputDate);
                //Append GlyphiconDate()
                let outSideSpan = getGlyphiconDate();
                divDate.appendChild(outSideSpan);
                divInputGroup.appendChild(divDate);

                let divDateStopClientLimit = getColMDConteiner();
                //Create InputGroupDiv
                divInputGroup = getInputGroup();
                divDateStopClientLimit.appendChild(divInputGroup);
                //Create Span
                divSpan  = getSpan('Data do');
                //Append Span to container
                divInputGroup.appendChild(divSpan);
                //Create Date Div
                divDate = getDateDiv();
                //Create Input Datepicker
                inputDate = getInputDate('dateStopLimit',"{{date("Y-m-d")}}");
                divDate.appendChild(inputDate);
                //Append GlyphiconDate()
                outSideSpan = getGlyphiconDate();
                divDate.appendChild(outSideSpan);
                divInputGroup.appendChild(divDate);


                let divLimitPanel = document.createElement('div');
                divLimitPanel.classList.add('limitSection');

                let divLimitForAllEvent = document.createElement('div');
                divLimitForAllEvent.classList.add('col-md-4');
                let divLimitForAllEventLabel = document.createElement('label');
                divLimitForAllEventLabel.textContent = "Limit dla pokazów pełnych (3)";
                divLimitForAllEvent.appendChild(divLimitForAllEventLabel);
                for( let i =0;i<3;i++){
                    let limitInput = document.createElement('div');
                    limitInput.classList.add('input-group','limitInput');
                    let span = getSpan('Limit #'+(i+1));
                    let inputLimit = getInputDate('AllLimit'+(i+1),'');
                    inputLimit.classList.add('AllLimit');
                    limitInput.appendChild(span);
                    limitInput.appendChild(inputLimit);
                    divLimitForAllEvent.appendChild(limitInput);
                }
                divLimitPanel.appendChild(divLimitForAllEvent);

                let divLimitForOneEvent = document.createElement('div');
                divLimitForOneEvent.classList.add('col-md-4');
                let divLimitForOneEventLabel = document.createElement('label');
                divLimitForOneEventLabel.textContent = "Limit dla pokazów godzinowych";
                divLimitForOneEvent.appendChild(divLimitForOneEventLabel);

                let limitInput = document.createElement('div');
                limitInput.classList.add('input-group','limitInput');
                let span = getSpan('Limit #1');
                let inputLimit = getInputDate('OnlyFirstLimit','');
                limitInput.appendChild(span);
                limitInput.appendChild(inputLimit);
                divLimitForOneEvent.appendChild(limitInput);
                divLimitPanel.appendChild(divLimitForOneEvent);

                let divLimitConteinerButton = document.createElement('div');
                divLimitConteinerButton.classList.add('col-md-12');

                let divAddLimitButton = document.createElement('button');
                divAddLimitButton.classList.add('btn','btn-default','separate','AddNewSimulation');
                divAddLimitButton.addEventListener('click',function (e) {
                    TemplateDOMOfClientSimulationEditLimits('placeToAppendClientLimit');
                });
                let glyphicon = getGlyphicon('plus');
                divAddLimitButton.appendChild(glyphicon);
                divSpan = getSpan('Dodaj Kolejną symulacje');
                divSpan.classList.remove('input-group-addon');
                divAddLimitButton.appendChild(divSpan);
                divLimitConteinerButton.appendChild(divAddLimitButton);


                let divRemoveLimitButton = document.createElement('button');
                divRemoveLimitButton.classList.add('btn','btn-danger','separate','RemoveSimulation');
                divRemoveLimitButton.addEventListener('click',function (e) {
                    if(document.getElementsByClassName('changeClientLimitContener').length != 1)
                        e.target.closest('.changeClientLimitContener').remove();
                    else{
                        swal('Nie ma co usunąć')
                    }
                });
                glyphicon = getGlyphicon('minus');
                divRemoveLimitButton.appendChild(glyphicon);
                divSpan = getSpan('Usuń Symulację');
                divSpan.classList.remove('input-group-addon');
                divRemoveLimitButton.appendChild(divSpan);
                divLimitConteinerButton.appendChild(divRemoveLimitButton);

                divLimitPanel.appendChild(divLimitConteinerButton);

                divChangeClientLimit.appendChild(divChoiceClientLimit);
                divChangeClientLimit.appendChild(divDateStartClientLimit);
                divChangeClientLimit.appendChild(divDateStopClientLimit);
                divChangeClientLimit.appendChild(divLimitPanel);
                mainDiv.appendChild(divChangeClientLimit);
                document.getElementById(placeToAppend).appendChild(mainDiv);
                reloadDatePicker();
                reloadSelectPicker();
            }
            function reloadDataTable(modalToHide){
                //Reload Datatable
                $('#datatable_processing').show();
                aheadPlaningTable.dataTable.clear();
                aheadPlaningTable.dataTable.draw();
                aheadPlanningData.getData($('#date_start').val(),$("#date_stop").val()).done(function (response) {
                    aheadPlaningTable.setTableData(response);
                    $('#datatable_processing').hide();
                });
                $(modalToHide).modal('hide');
            }
            $('.renderSimulation, .saveSimulation').on('click',function (e) {
                simulationEditLimitArray     = [];
                let valide          = true;
                let btnType         = $(this).attr("class").split(" ")[3];
                allContentToSave    = $('.changeClientLimitContener');
                allContentToSave.each(function (key,value) {
                    let jObject                 = $(value);
                    let arrayOfClinet           = jObject.find('select').val();
                    let dateStartSimulation     = jObject.find('.dateStartLimit').val();
                    let dateStopSimulation      = jObject.find('.dateStopLimit').val();
                    let arrayOfLimit            = [];
                    let saveStatus              = false;
                    if(btnType == 'renderSimulation'){
                        saveStatus              = false;
                    }else if(btnType == 'saveSimulation'){
                        saveStatus              = true;
                    }
                    arrayOfLimit.push(jObject.find('.AllLimit1').val());
                    arrayOfLimit.push(jObject.find('.AllLimit2').val());
                    arrayOfLimit.push(jObject.find('.AllLimit3').val());
                    let onlyFirstLimit          = jObject.find('.OnlyFirstLimit').val();
                    simulationEditLimitArray.push(new SimulationChangeLimitForClient(arrayOfClinet,dateStartSimulation,dateStopSimulation,arrayOfLimit,onlyFirstLimit,saveStatus));
                    if(arrayOfClinet.length == 0){
                        valide = false;
                        swal("Wybierz klienta do zmiany limitów")
                        return false;
                    }
                    if(dateStartSimulation > dateStopSimulation){
                        valide = false;
                        swal("Data rozpoczęcia symulacji nie może być większa, niż data zakończenia symulacji")
                        return false;
                    }
                });
                if(valide){
                    if(btnType == 'saveSimulation'){
                        swal({
                            title: 'Jesteś Pewny ?',
                            text: "Potwierdzenie spowoduje zapisanie zmian",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Tak, zapisz!'
                        }).then((result) => {
                            if (result.value) {
                                reloadDataTable();
                                swal(
                                    'Zapisano!',
                                    'Limity zostały zmienione.',
                                    'success'
                                )
                            }
                        })
                    }else{
                        reloadDataTable('#modalSimulationClient');
                    }

                }
            });
            $('.renderSimulationNewClient').on('click',function (e) {
                simulationNewClientArray = [];
                let valide          = true;
                allContentToSave    = $('.newClientSimulation');
                allContentToSave.each(function (key,value) {
                    let jObject                     = $(value);
                    let dayCountEventArray          = [];
                    let arrayOfNumberWeekNewClient  = jObject.find('.NewClientWeek select').val();
                    let arrayOfLimit                = [];
                    let year                        = jObject.find('.NewClientYear select').val();
                    for(let i = 1; i <= 3; i++){
                        if(jObject.find('.NewClientLimit'+i).val() != ""){
                            arrayOfLimit.push(jObject.find('.NewClientLimit'+i).val());
                        }
                    }
                    for(let i = 1; i <= 7; i++)
                        dayCountEventArray.push(jObject.find('.dayEventCountNumber'+i).val());
                    if(arrayOfLimit.length < 3){
                        valide = false;
                        swal("Aby dodać nowego klienta musisz ustalić wszystkie limity")
                        return false;
                    }
                    if(arrayOfNumberWeekNewClient.length == 0){
                        valide = false;
                        swal("Wybierz tydzień dla klienta");
                        return false;
                    }
                    if(valide){
                        simulationNewClientArray.push(new SimulationNewClientClient(dayCountEventArray,arrayOfNumberWeekNewClient,arrayOfLimit,year));
                    }
                });
                if(valide){
                    reloadDataTable('#modalSimulationNewClient');
                }
            });
            $('.resetSimulationClientLimit').on('click',function (e) {
                swal({
                    title: 'Jesteś Pewny ?',
                    text: "Potwierdzenie spowoduje resetowanie symulacji",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Tak, resetuj!'
                }).then((result) => {
                    if (result.value) {
                        simulationEditLimitArray = [];
                        simulationNewClientArray = [];
                        document.getElementById('placeToAppendClientLimit').innerHTML = "";
                        document.getElementById('placeToAppendNewClient').innerHTML = "";
                        TemplateDOMOfClientSimulationEditLimits('placeToAppendClientLimit');
                        TemplateDOMOfNewClientSimulation('placeToAppendNewClient');
                        reloadDataTable('#modalSimulationClient');
                        swal(
                            'Wykonano!',
                            'Limity zostały zresetowane.',
                            'success'
                        )
                    }
                });
            });
            //Pass only number
            $('.AllLimit1, .OnlyFirstLimit, .dayEventCountNumber, .NewClientLimit').on("input propertychange", function (e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            TemplateDOMOfClientSimulationEditLimits('placeToAppendClientLimit');
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
                    let deferred = $.Deferred();
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
                            objectClientLimitToSimulate : simulationEditLimitArray,
                            objectNewClientToSimulate :simulationNewClientArray,
                            factors: {
                                isChanged: factorsChanged,
                                saturday: $('#saturdayFactor').val(),
                                sunday: $('#sundayFactor').val()
                            }
                        },
                        success: function (response) {
                            obj.data.aheadPlaning = response.aheadPlanningData;
                            obj.data.departmentsInvitationsAverages = response.departmentsInvitationsAveragesData;
                            deferred.resolve(obj.data.aheadPlaning);
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
                            deferred.reject();
                        }
                    });
                    selectedRowDays = [];
                    $('#removeSimulationButton').hide();
                    return deferred.promise();
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
            });

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
                    let daysBetweenSelectedAndActualDay = Math.abs(Math.round(moment.duration(moment(new Date(selectedDay)).diff(moment(new Date(today)))).asDays()));
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
                            for(let i = 0; i < daysBetweenSelectedAndActualDay - 1; i++){
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

                        //prepareTestingData();

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
                            department.simulatedResult = Math.round(department.simulatedResult);
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
                ['factorsSection'], //sections to show
                false, // flag that identify is ahead planning data after simulation are changed
                /* ---------------- simulation function ----------------------- */
                function (thisObj) {
                    let selectedNewActualDay= getSelectedDay(selectedRowDays[0] < selectedRowDays[1] ? selectedRowDays[0] : selectedRowDays[1]);
                    let selectedDayWithCompletedResult = getSelectedDay(selectedRowDays[0] > selectedRowDays[1] ? selectedRowDays[0] : selectedRowDays[1]);
                    let daysBetweenSelectedAndActualDay = Math.abs(moment.duration(moment(new Date(selectedNewActualDay)).diff(moment(new Date(today)))).asDays());

                    prepareSimulationData();
                    simulation();
                    $('#simulationAverages .modal-body').text('');
                    $('#simulationAverages .modal-body').append(createSimulationAveragesTable());
                    $('#simulationAverages').modal('show');

                    function prepareSimulationData() {
                        $.each(departmentInfo,function (index, department) {
                            aheadPlanningData.data.departmentsInvitationsAverages[department.name2].simulated = {
                                workingDays: 0,
                                saturday: 0
                            };
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

                    }
                    function simulation() {
                        let simulationData = aheadPlanningData.data.getCopyAheadPlaning();
                        $.each(simulationData, function (index, dayInfo) {
                            if (moment(new Date(dayInfo.day)) > moment(new Date(today)) && moment(new Date(dayInfo.day)) <= moment(new Date(selectedDayWithCompletedResult))) {
                                $.each(departmentInfo, function (item, department) {
                                    aheadPlanningData.data.departmentsInvitationsAverages[department.name2].simulated.workingDays += dayInfo[department.name2];
                                });
                            }
                        });

                        $.each(departmentInfo, function (item, department) {
                            let sumOfDays = department.multiplier.workingDays + department.multiplier.saturdays;
                            if(sumOfDays > 0){
                                let sumOfDepartmentLimits = aheadPlanningData.data.departmentsInvitationsAverages[department.name2].simulated.workingDays;
                                let averagePerDay = Math.abs(Math.round(sumOfDepartmentLimits / sumOfDays));
                                if(department.multiplier.saturdays>0){
                                    aheadPlanningData.data.departmentsInvitationsAverages[department.name2].simulated.saturday = Math.round(averagePerDay*parseInt($('#saturdayFactor').val())/100);
                                }else{
                                    aheadPlanningData.data.departmentsInvitationsAverages[department.name2].simulated.saturday = 0;
                                }
                                aheadPlanningData.data.departmentsInvitationsAverages[department.name2].simulated.workingDays =
                                    averagePerDay + Math.round(((averagePerDay*(100-parseInt($('#saturdayFactor').val()))/100)*department.multiplier.saturdays)/department.multiplier.workingDays);
                            }
                        });
                    }
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
                $('#simulation').selectpicker('refresh');
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
                            if($(e.target).parent().children().length - 1 === $(e.target).parent().children().has(':checked').length){
                                $(e.target).parent().find(':checkbox').prop('checked',false);
                            }else{
                                $(e.target).parent().find(':checkbox').prop('checked',true);
                            }
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

            function createSimulationAveragesTable() {
                let tHeadTr = $(document.createElement('tr'));
                tHeadTr.append($(document.createElement('th')).text('Oddział'));
                tHeadTr.append($(document.createElement('th')).text('Pn-Pt'));
                tHeadTr.append($(document.createElement('th')).text('Sobota'));
                let tHead = $(document.createElement('thead')).append(tHeadTr);

                let tBody = $(document.createElement('tbody'));
                $.each(aheadPlanningData.data.departmentsInvitationsAverages,function (department, departmentsInvitationsAverage) {
                    let tr = $(document.createElement('tr'));
                    tr.append($(document.createElement('td')).append(department));
                    tr.append($(document.createElement('td')).append(departmentsInvitationsAverage.simulated.workingDays));
                    tr.append($(document.createElement('td')).append(departmentsInvitationsAverage.simulated.saturday));
                    tBody.append(tr);
                });

                let simulationAveragesTable = $(document.createElement('table')).addClass('table table-striped').css('width','100%').prop('id','workFreeDaysTable');
                simulationAveragesTable.append(tHead).append(tBody);
                let firstSelectedDay = getSelectedDay(selectedRowDays[0] < selectedRowDays[1] ? selectedRowDays[0] : selectedRowDays[1]);
                let secondSelectedDay = getSelectedDay(selectedRowDays[0] > selectedRowDays[1] ? selectedRowDays[0] : selectedRowDays[1]);
                let firstSelectedDayLi =  $(document.createElement('li')).addClass('list-group-item').text(firstSelectedDay+' - ostatni dzień dzwonienia');
                let secondSelectedDayLi =  $(document.createElement('li')).addClass('list-group-item').text(secondSelectedDay+' - dzień wyrobienia limitów');
                let infoUl = $(document.createElement('ul')).addClass('list-group').append(firstSelectedDayLi).append(secondSelectedDayLi);
                let infoColumn = $(document.createElement('div')).addClass('col-md-12').append(infoUl);
                let buttonsRow = $(document.createElement('div')).addClass('row').css({'border-bottom-width':'1px','border-bottom-color':'#c1c1c1','border-bottom-style': 'solid'}).append(infoColumn);
                let tableColumn = $(document.createElement('div')).addClass('col-md-12').append(simulationAveragesTable);//.css({'height':'75vh','overflow':'scroll'});
                let tableRow = $(document.createElement('div')).addClass('row').append(tableColumn);
                return  $(document.createElement('div')).append(buttonsRow).append(tableRow);
            }
        });
    </script>
@endsection
