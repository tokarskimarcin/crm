@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/rowgroup/1.0.3/css/rowGroup.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('/assets/css/VCtooltip.css')}}">
    {{--<link rel="stylesheet" href="{{asset('/css/fixedHeader.dataTables.min.css')}}">
--}}
@endsection
@section('content')

    <style>
        .VCtooltip .tooltiptext {
            max-width: 65vw;
        }

        .departmentColors li{
            padding-top: 0.5em;
            padding-bottom: 0.5em;
        }

        .bootstrap-select > .dropdown-menu{
            left: 0 !important;
        }
        textarea.baseDivision {
            resize: none;
        }

        .colorRow {
            /*background-color: #565fff !important;*/
            animation-name: example;
            animation-duration: 1s;
            animation-fill-mode: forwards;
        }

        .notPresentAtWork {
            background-color: indianred !important;
        }

        .page-info {
            font-size: 0.8em;
        }

        @keyframes example {
            from {background-color: white;}
            to {background-color: #565fff ;}
        }
        .VCtooltip .well:hover {
             background-color: rgba(185,185,185,0.75) !important;
             cursor: help;
        }
        .dep1{
            background-color: #a0c1ff !important;
        }
        .dep2{
            background-color: #ffffff !important;
        }
        .dep3{
            background-color: #8888ff !important;
        }
        .dep4{
            background-color: #f9ff6a !important;
        }
        .dep5{
            background-color: #ff92ef !important;
        }
        .dep6{
            background-color: #89ecff !important;
        }
        .dep7{
            background-color: #888 !important;
        }
        .dep8{
            background-color: #ff6a7a !important;
        }

        .dep9{
            background-color: #6dff8c !important;
        }

        .dep10{
            background-color: #55f !important;
        }

    </style>

{{--Header page --}}

   {{-- <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav "> Grafik dla potwierdzeń
        </div>
    </div>--}}


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            {{--<div class="panel-heading">
                Szczegółowe informacje o grafiku
            </div>--}}
            <div class="panel-body">
                <div class="row">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date" class="myLabel">Data początkowa:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control" name="date_start" id="date_start" type="text" value="{{date("Y-m-d")}}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_stop" class="myLabel">Data końcowa:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control" name="date_stop" id="date_stop" type="text" value="{{date("Y-m-d")}}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="departments">Oddział dzwoniący</label>
                                <select id="departments" multiple="multiple" style="width: 100%;">
                                        <option value="dep_-1">Nieprzydzielone</option>
                                    @foreach($departmentInfo as $item)
                                        @if($item->depId == 2)
                                        <option value="dep_{{$item->id}}">{{$item->name2}} {{$item->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="typ">Typ</label>
                                <select id="typ" multiple="multiple" style="width: 100%;">
                                    <option value="2">Wysyłka</option>
                                    <option value="1">Badania</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" align="right">
                            <div class="VCtooltip VCtooltip-left" align="right">
                                <div class="well well-sm" style="border-radius: 10%; background-color: #5bc0de; color: white; margin-bottom: 0;">Legenda <span class="glyphicon glyphicon-info-sign"></span></div>
                                <span class="tooltiptext">
                                    <div class="alert alert-info page-info">
                                        Moduł <strong>Grafik dla potwierdzeń</strong> wyświetla informacje o pokazo-godzinach.<br>
                                        W kolumnie <i>"Potwierdzająca osoba"</i> dostępne są osoby, które wg. grafiku są dostępne dla danej daty potwierdzenia w oddziale osoby wyświetlającej tą zakładkę. <br>
                                        Gdy wiersz jest podświetlony na <span style="background-color: indianred;">czerwono</span>, oznacza to, że osoba potwierdzająca w dniu potwierdzania nie nacisneła przycisku start do godziny 9:00 lub wogóle go nie nacisneła. <br>
                                        Dla otrzymania lepszego wyglądu tabeli zaleca się <i>wyłącznie</i> panelu nawigacyjnego naciskając przycisk <u>"OFF"</u> w górnym lewym rogu strony. <br>
                                        Pokazy <u>anulowane</u> mają cały wiersz w kolorze <span style="background-color: #fdff78;">żółtym</span>.
                                        <br>
                                        Data potwierdzania jest podświetlana na <span style="background-color: #ff9c87">czerowono</span>, jeżeli różnica daty pokazu i daty potwierdzania jest większa niż 1 dzień.
                                        <br>
                                        <strong>Kolory oddziałów potwierdzających:</strong>
                                        <ul class="list-group departmentColors">
                                        @foreach($departmentInfo as $item)
                                                @if($item->depId == 1)
                                                    <li class="list-group-item dep{{$item->departmentId}}" style="color: black">
                                                        {{$item->name2}} {{$item->name}}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="checkbox" >
                            <label>
                                <input id="confirmationDateFilterCheckbox" type="checkbox" style="display: block;"> Filtruj po dacie potwierdzeń
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 buttonSection" style="min-height: 3.5em;">
                        <button class="btn btn-success" style="margin-bottom: 1em;  width: 100%;" id="save" disabled>
                            <span class='glyphicon glyphicon-save'></span> Zapisz zmiany <span class="badge">0</span></button>
                    </div>
                </div>
                <div class="row">
                    <table id="datatable" class="thead-inverse table table-striped table-bordered compact" style="max-width:100%;">
                        <thead>
                        <tr>
                            <th>T</th>
                            <th>Data potw.</th>
                            <th>Miasto</th>
                            <th>Nazwa_klienta</th>
                            <th>G</th>
                            <th>Oddział</th>
                            <th>Potwierdzająca osoba</th>
                            <th>Limit</th>
                            <th>Zgody</th>
                            <th>Frekw.</th>
                            <th>Pary</th>
                            <th>Data</th>
                            <th>cri</th>
                        </tr>
                        </thead>
                    </table>
                </div>

                <div class="row">
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section('script')
        <script src="https://cdn.datatables.net/rowgroup/1.0.3/js/dataTables.rowGroup.min.js"></script>
        <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        <script src="{{asset('/js/numeric-comma.js')}}"></script>
        {{--<script src="{{asset('/js/dataTables.fixedHeader.min.js')}}"></script>--}}
        <script src="{{ asset('/js/moment.js')}}"></script>
    <script>
       document.addEventListener('DOMContentLoaded', function() {
           /********** GLOBAL VARIABLES ***********/

           let APP = {
               arrays: {
                   selectedDepartments: ["0"], //this array collect selected by user departments
                   selectedTypes: ['0'], //array of selected by user types
                   changeArr: [] //This array collect changed rows
               },
               JSONS: {
                   userData: @json($userData),
                   workHourData: @json($workHours)
               },
               DOMElements: {
                   from: document.querySelector('#date_start'),
                   to: document.querySelector('#date_stop'),
                   saveButton: document.querySelector('#save'),
                   badge: document.querySelector('.badge')
               },
               globalVariables: {
                   numberOfChanges: 0,
                   dataTableData: null,
                   datatableHeight: '75vh', //this variable defines height of table
                   loggedUserDepartment: {{Auth::user()->department_info_id}},
                   loggedUserType: {{Auth::user()->user_type_id}}
               }
           };

           /********* END OF GLOBAL VARIABLES*********/

           // let testArr = [
           //     {
           //         name: "Paweł",
           //         surname: "Chmielewski",
           //         userId: '6009',
           //         date: [
           //             "2018-08-20",
           //             "2018-08-21",
           //             "2018-08-22",
           //             "2018-08-23",
           //             "2018-08-24",
           //             "2018-08-25",
           //         ]
           //     },
           //     {
           //         name: "Sebastian",
           //         surname: "Cytawa",
           //         userId: '20',
           //         date: [
           //             "2018-08-20",
           //             "2018-08-21",
           //             "2018-08-22",
           //             "2018-08-23",
           //             "2018-08-24",
           //             "2018-08-25",
           //         ]
           //     },
           //     {
           //         name: "Adam",
           //         surname: "Bogacewicz",
           //         userId: '5000',
           //         date: [
           //             "2018-08-21",
           //             "2018-08-22",
           //             "2018-08-23",
           //             "2018-08-24",
           //             "2018-08-25",
           //         ]
           //     }
           // ]

           $('#menu-toggle').change(()=>{
               table.columns.adjust().draw();
           });


           /**
            * This function shows notification.
            */
           function notify(htmltext$string, typestring = 'info', delaymilisecondsnumber = 5000) {
               $.notify({
                   // options
                   message: htmltext$string
               },{
                   // settings
                   type: typestring,
                   delay: delaymilisecondsnumber,
                   animate: {
                       enter: 'animated fadeInRight',
                       exit: 'animated fadeOutRight'
                   }
               });
           }

           /**
            * This function append "Wybierz" option to given select
            */
           function appendBasicOption(placeToAppend) {
               console.assert(placeToAppend.tagName == "SELECT", "placeToAppend in appendBasicOption is not SELECT element");
               let basicOptionElement = document.createElement('option');
               basicOptionElement.value = 0;
               basicOptionElement.textContent = "Wybierz";
               placeToAppend.appendChild(basicOptionElement);
           }

           /**
            * This function append option with person data to given select
            */
           function addPersonToConfirmationList(person, placeToAppend) {
               console.assert(placeToAppend.tagName == "SELECT", "placeToAppend in addPersonToConfirmationList is not SELECT element");

               let optionElement = document.createElement('option');
               optionElement.value = person.userId;
               optionElement.textContent = `${person.name} ${person.surname}`;
               $(optionElement).addClass('dep'+person.department_id);
               placeToAppend.appendChild(optionElement);
           }

           /**
            * This method returns selected option from select element
            */
           function getSelectedOption(selectElement) {
               console.assert(selectElement.tagName == 'SELECT', 'selectElement in getSelectedOption is not SELECT element');
               return selectElement.options[selectElement.selectedIndex].value;
           }

           /**
            * This method sets given value in given list
            */
           function setOldValues(selectElement, givenValue) {
               console.assert(selectElement.tagName == 'SELECT', 'selectElement in setOldValues is not SELECT element');
               let elementExistInList = false;

               for(let i = 0, max = selectElement.length; i < max; i++) {
                   if(selectElement[i].value == givenValue) {
                       selectElement[i].selected = true;
                       elementExistInList = true;
                   }
               }

               if(!elementExistInList) {
                   $(selectElement).val('0');
               }
           }

           //This object contructor has all info about changed row.
           function ChangeObject(confirmingPerson, frequency, pairs, date, id) {
               this.confirmingPerson = confirmingPerson;
               this.frequency = frequency;
               this.pairs = pairs;
               this.date = date;
               this.id = id;
           }

           /**
            * This method checks whether obiect with given id is in given array of objects
            * return truefalse
            */
           function existInArr(id, array) {
               console.assert(Array.isArray(array), 'array parameter is not Array in existInArr function');
               let exist = false;
               array.forEach(item => {
                   if(item.hasOwnProperty('id')) {
                       if(item['id'] == id) {
                           exist = true;
                       }
                   }
               });

               return exist;
           }

           /**
            * This method set proper values for row inputs.
            */
           function setProperRowValues(row, item) {
               let confirmDateInput = row.querySelector('.confirm-date');
               let confirmingPeopleSelect = row.querySelector('.confirming');
               let frequencyElement = row.querySelector('.frequency');
               let pairElement = row.querySelector('.pairs');

               confirmDateInput.value = item.date;
               frequencyElement.value = item.frequency;
               pairElement.value = item.pairs;

               for(let i = 0, max = confirmingPeopleSelect.length; i < max; i++) {
                   if(confirmingPeopleSelect[i].value == item.confirmingPerson) {
                       setOldValues(confirmingPeopleSelect, item.confirmingPerson);
                   }
               }
           }

           let table = $('#datatable').DataTable({
               autoWidth: true,
               processing: true,
               serverSide: true,
               scrollY: APP.globalVariables.datatableHeight,
               "lengthMenu": [[10, 25, 50, 100, 150, 200], [10, 25, 50, 100, 150, 200]],
               "iDisplayLength": 100,
               "drawCallback": function( settings ) {

               },
               "rowCallback": function( row, data, index ) {
                   let frequencyCell = row.cells['9'];
                   let frequencyInput = frequencyCell.firstChild;
                   if(frequencyInput.value != null && frequencyInput.value != '') {
                       if(frequencyInput.value < 16) {
                           frequencyCell.style.backgroundColor = 'red';
                       }
                       else if(frequencyInput.value < 20) {
                           frequencyCell.style.backgroundColor = 'yellow';
                       }
                       else {
                           frequencyCell.style.backgroundColor = 'green';
                       }
                   }

                   row.setAttribute('data-id', data.id); //clientRouteInfo Id

                   const confirmDateInput = row.querySelector('.confirm-date');
                   const confirmDate = confirmDateInput.value;
                   if(moment(data.date).diff(moment(data.confirmDate),'days') > 1){
                       $(confirmDateInput).css('background-color', '#ff9c87')
                   }
                   let confirmingPeopleSelect = row.querySelector('.confirming');
                   let alreadyIn;
                    APP.JSONS.userData.forEach(person => { //looping over all data about people
                        alreadyIn = false;
                        //Confirming user is deremined and saved in database
                        if(data.confirmingUser == person.userId) {
                            alreadyIn = true;
                        }
                        if(person.hasOwnProperty('date')) {
                            person.date.forEach(day => {
                                if(day == confirmDate && !alreadyIn) { //current person is available this day
                                    if(APP.globalVariables.loggedUserDepartment == person.depId) {
                                        alreadyIn = true;
                                        addPersonToConfirmationList(person, confirmingPeopleSelect);
                                    }
                                }
                            });
                        }
                   });

                   setOldValues(confirmingPeopleSelect, data.confirmingUser);

                   //part responsible for highlighting row if user is not at work at confirm date after 8:59
                   if(APP.JSONS.workHourData.hasOwnProperty(`${data.confirmingUser}`)) {
                       APP.JSONS.workHourData[data.confirmingUser].forEach(item => {
                           if(item.date == confirmDate) {
                               if(item.presentAtTime == 0) {
                                   row.classList.add('notPresentAtWork');
                               }
                           }
                       });
                   }

                    //reassigning classes to rows after changing page.
                 APP.arrays.changeArr.forEach(item => {
                       if(item.hasOwnProperty('id')) {
                           if (item['id'] == data.id) {
                               row.classList.add('colorRow');
                               setProperRowValues(row, item);
                           }
                       }

                   });
                   if(data.canceled == 1) {
                       $(row).css("background-color", '#fdff78');
                   }
               },
               "fnDrawCallback": function(settings) {
                   //$('.selectpicker').selectpicker('refresh');
                   $('select.confirming').change(function (e) {
                       let select = $(e.target);
                       select.removeClass().addClass('confirming form-control');
                       let className = select.find('option[value="'+select.val()+'"]').attr('class');
                       select.addClass(className);
                   });
                   $('#datatable tbody tr').on('change', function(e) {
                        const changedElement = e.target;
                        const elementRow = this;
                        const id = elementRow.dataset.id;

                        let confDate = elementRow.querySelector('.confirm-date');
                        let confDateObject = new Date(confDate.value);
                        let showDateObject = new Date(APP.globalVariables.dataTableData.date);
                        let confirmingPeopleSelect = elementRow.querySelector('.confirming');
                        const frequencyElement = elementRow.querySelector('.frequency');
                        const frequencyValue = frequencyElement.value;
                        const pairElement = elementRow.querySelector('.pairs');
                        const pairValue = pairElement.value;
                        let actualConfirmingPerson = getSelectedOption(confirmingPeopleSelect);

                        if(changedElement.matches('.confirm-date')) { //confirm date has been changed.
                            const newConfirmDate = e.target.value;
                            confirmingPeopleSelect.innerHTML = ''; //clearing list of current people
                            appendBasicOption(confirmingPeopleSelect);

                            APP.JSONS.userData.forEach(person => { //looping over all data about people
                               if(person.hasOwnProperty('date')) {
                                   person.date.forEach(day => {
                                       if(day == newConfirmDate) { //current person is available this day
                                           if(person.depId == APP.globalVariables.loggedUserDepartment) {
                                               addPersonToConfirmationList(person, confirmingPeopleSelect);
                                           }
                                       }
                                   });
                               }
                            });

                            //If actual confirming person exist in new list, he will be selected.
                            for(let i = 0, max = confirmingPeopleSelect.length; i < max; i++) {
                                if(confirmingPeopleSelect[i].value == actualConfirmingPerson) {
                                    setOldValues(confirmingPeopleSelect, actualConfirmingPerson);
                                }
                            }

                        }

                       //if only data has changed
                       if(confirmingPeopleSelect.options[confirmingPeopleSelect.selectedIndex].value != 0 && (showDateObject > confDateObject)) {
                           let exist = existInArr(id, APP.arrays.changeArr);
                           let newConfirmingPerson = getSelectedOption(confirmingPeopleSelect);
                           let changedRow = new ChangeObject(newConfirmingPerson, frequencyValue, pairValue, confDate.value, id);

                           //remove existing item
                           if (exist) {
                               for (let j = 0, max = APP.arrays.changeArr.length; j < max; j++) {
                                   if (APP.arrays.changeArr[j].hasOwnProperty('id')) {
                                       if (APP.arrays.changeArr[j].id == id) {
                                           APP.arrays.changeArr.splice(j, 1);
                                           max--;
                                       }
                                   }
                               }
                           }
                           else {
                                APP.globalVariables.numberOfChanges++;
                                APP.DOMElements.badge.textContent = APP.globalVariables.numberOfChanges;
                           }
                           APP.arrays.changeArr.push(changedRow);

                           elementRow.classList.add('colorRow');
                       }
                       else { //check whether confirming person exist in array. If yes, delete him
                           if(showDateObject < confDateObject) {
                               notify("Data potwierdzania musi być mniejsza niż data pokazu");
                           }
                           else if(confirmingPeopleSelect.options[confirmingPeopleSelect.selectedIndex].value == 0) {
                               notify("Wybierz osobę z listy", 'info', 2000);
                           }

                           for(let j = 0, max = APP.arrays.changeArr.length; j < max; j++) {
                               if(APP.arrays.changeArr[j].hasOwnProperty('id')) {
                                   if(APP.arrays.changeArr[j]['id'] == id) {
                                       APP.arrays.changeArr.splice(j,1);
                                       max--;

                                       APP.globalVariables.numberOfChanges--;
                                       APP.DOMElements.badge.textContent = APP.globalVariables.numberOfChanges;
                                       elementRow.classList.remove('colorRow');
                                   }
                               }
                           }
                       }
                       // console.log(APP.arrays.changeArr);
                      APP.DOMElements.saveButton.disabled = APP.arrays.changeArr.length > 0 ? false : true;
                   });

               },"ajax": {
                   'url': "{{route('api.engraverForConfirmingDatatable')}}",
                   'type': 'POST',
                   'data': function (d) {
                        d.from = APP.DOMElements.from.value;
                        d.to = APP.DOMElements.to.value;
                        d.departments = APP.arrays.selectedDepartments;
                        d.typ = APP.arrays.selectedTypes;
                        d.confirmationDateFilter = $('#confirmationDateFilterCheckbox').prop('checked');
                   },
                   'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
               },
               "language": {
                   "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
               },
               "columns":[
                   {"data":function (data, type, dataToSet) {
                           APP.globalVariables.dataTableData = data;
                           return data.weekOfYear;
                       },"name":"weekOfYear", "width": "1%", "searchable": false
                   },
                   {"data":function(data, type, dataToSet) {
                           if(data.confirmDate != null) {

                               /*let confirmDateInput = $('<input>').attr('type', 'date').css({'width':'100%'}).addClass('form-control confirm-date').val(data.confirmDate);
                               //console.log(moment(data.date).diff(moment(data.confirmDate),'days'), confirmDateInput);
                               return confirmDateInput.prop('outerHTML');*/

                               return `<input type="date" style="width: 100%;" class="form-control confirm-date" value="${data.confirmDate}">`;
                           }
                           else {
                               const showDate = new Date(data.date);
                               const dayBeforeShowDate = new Date(showDate.setDate(showDate.getDate() - 1));
                               const day = ("0" + dayBeforeShowDate.getDate()).slice(-2);
                               const month = ("0" + (dayBeforeShowDate.getMonth() + 1)).slice(-2);
                               const year = dayBeforeShowDate.getFullYear();
                               const fullDate =  year + "-" + month + "-" + day;
                               return `<input type="date" style="width: 100%;" class="form-control confirm-date" value="${fullDate}">`;
                           }

                       }, "name": "dataPotwierdzenia", "orderable": false, "searchable": false
                   },
                   {"data":function (data, type, dataToSet) {
                           if(data.nrPBX != null) {
                               return data.cityName +" ("+data.nrPBX+")";
                           }
                           else {
                               return data.cityName;
                           }
                       },"name":"cityName", "orderable": false
                   },
                   {"data":function (data, type, dataToSet) {
                           let clientNameVariable = '';
                           if(data.typ == '2') {
                               clientNameVariable = data.clientName + ' (W)'
                           }
                           else {
                               clientNameVariable = data.clientName + ' (B)'
                           }
                           return clientNameVariable;
                       },"name":"clientName"
                   },

                   {"data":function (data, type, dataToSet) {
                            if(data.hour) {
                                return data.hour.slice(0,-3);
                            }
                            else {
                                return '';
                            }
                       },"name":"hour"
                   },
                   {"data":function (data, type, dataToSet) {
                           //let fullDepartmentName = data.departmentName == null ? null : data.departmentName + ' ' + data.departmentName2;
                           return data.departmentName;
                       },"name":"departmentName", "searchable": "false", "orderable": false
                   },
                   {"data":function(data, type, dataToSet) {
                       let customSelect =  $('<select>').addClass('confirming form-control').css({'width':'100%'});
                       customSelect.append($('<option>').val(0).append('Wybierz'));
                                if(data.confirmingUser) {
                                    let option = $('<option>').prop('selected', true).val(data.confirmingUser).append(data.first_name+' '+data.last_name)
                                        .addClass('dep'+data.confirm_id_dep);

                                    customSelect.append(option);
                                    customSelect.attr('data-confirm-id-dep', data.confirm_id_dep).addClass('dep'+data.confirm_id_dep);
                               }
                                    return customSelect.prop('outerHTML');
                        }, "name": "potwierdzający", "width": "20%", "orderable": false, "searchable": false
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.limits;
                       },"name":"limits", "orderable": false, "searchable": false
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.actual_success;
                       },"name":"actual_success", "orderable": false, "searchable": false
                   },
                   {"data":function(data, type, dataToSet) {
                           return `<input class="frequency form-control" type="number" min="0" step="1" style="width: 5em;" value="${data.frequency}">`;
                       }, "name": "Frekw.", "orderable": false, "searchable": false
                   },
                   {"data":function(data, type, dataToSet) {
                           return `<input class="pairs form-control" type="number" min="0" step="1" style="width: 5em;" value="${data.pairs}">`;
                       }, "name": "pairs", "orderable": false, "searchable": false
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.date;
                       },"name":"date", "searchable": false
                   },
                   {"data":function(data, type, dataToSet) {
                           return data.client_route_id;
                       }, "name": "client_route_id", "orderable": true, "searchable": false, "visible": false
                   }
               ],
               order: [[11, 'asc'], [3, 'asc'], [12, 'desc'], [4, 'asc']],
               rowGroup: {
                   dataSrc: 'date',
                   startRender: null,
                   endRender: function (rows, group) {
                       let sumAllSuccess = 0;
                       sumAllSuccess =
                           rows
                           .data()
                           .pluck('actual_success')
                           .reduce( function (a, b) {
                               return a + b*1;
                           }, 0);
                       let sumAllCampaings = rows.data().count();
                       let sumAllLimit =
                           rows
                               .data()
                               .pluck('limits')
                               .reduce( function (a, b) {
                                   return a + b*1;
                               }, 0);

                       return $('<tr/>')
                           .append('<td colspan="2">Podsumowanie Dnia: ' + group + '</td>')
                           .append('<td></td>')
                           .append('<td>' + sumAllCampaings + '</td>')
                           .append('<td></td>')
                           .append('<td></td>')
                           .append('<td></td>')
                           .append('<td>' + sumAllLimit + '</td>')
                           .append('<td>' + sumAllSuccess + '</td>')
                           .append('<td colspan="3"></td>')
                   },
               },
           });

           /*********************EVENT LISTENERS FUNCTIONS****************************/
           /*Functions from this section moslty update arrays which are going to be send by ajax for datatable.
           /**********************************************************/

           /**
            * This event listener change elements of array selectedDepartments while user selects a department
            */
           $("#departments").on('select2:select', function() {
               let departments = $('#departments').val();
               if(departments.length > 0) {
                   let helpArray = [];
                   departments.forEach(item => {
                       let tempArray = [];
                      tempArray = item.split('_');
                      helpArray.push(tempArray[1]);
                   });
                   APP.arrays.selectedDepartments = helpArray;
               }
               else {
                   APP.arrays.selectedDepartments = ["0"];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selectedDepartments while user unselects a department
            */
           $("#departments").on('select2:unselect', function() {
              if($('#departments').val().length != 0) {
                  let departments = $('#departments').val();
                  let helpArray = [];
                  departments.forEach(item => {
                      let tempArray = [];
                      tempArray = item.split('_');
                      helpArray.push(tempArray[1]);
                  });
                  APP.arrays.selectedDepartments = helpArray;
              }
              else {
                  APP.arrays.selectedDepartments = ["0"];
              }

              table.ajax.reload();
           });

           /**
            * This event listener change elements of array selectedTypes while user selects any type
            */
           $('#typ').on('select2:select', function() {
               let types = $('#typ').val();
               if(types.length > 0) {
                   APP.arrays.selectedTypes = types;
               }
               else {
                   APP.arrays.selectedTypes = ['0'];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selectedTypes while user unselects any type
            */
           $('#typ').on('select2:unselect', function() {
               if($('#typ').val().length != 0) {
                   APP.arrays.selectedTypes = $('#typ').val();
               }
               else {
                   APP.arrays.selectedTypes = ['0'];
               }
               table.ajax.reload();
           });

           /**
            * This event listener reloads table after changing start or stop date
            */
           $('#date_start, #date_stop').on('change', () => {
               table.ajax.reload();

           });

           $('#confirmationDateFilterCheckbox').change(function () {
              table.ajax.reload();
           });

           function saveHandler(e) {
               const saveBtn = e.target;
               if(saveBtn.disabled == false) { //user clicked on active save button, it mean there are rows to change
                   const dataJSON = JSON.stringify(APP.arrays.changeArr);

                   const ourHeaders = new Headers();
                   ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                   let data = new FormData();
                   data.append('data', dataJSON);

                   fetch('{{route('api.engraverForConfirmingUpdate')}}', {
                       method: 'post',
                       headers: ourHeaders,
                       credentials: "same-origin",
                       body: data
                   })
                       .then(resp => resp.text())
                       .then(resp => {
                           notify(resp, 'success', 4000);
                           APP.arrays.changeArr = [];
                           APP.globalVariables.numberOfChanges = 0;
                           APP.DOMElements.badge.textContent = APP.globalVariables.numberOfChanges;
                           APP.DOMElements.saveButton.disabled = true;
                           let allHighlightedRows = document.querySelectorAll('.colorRow');
                           allHighlightedRows.forEach(item => {
                               item.classList.remove('colorRow');
                           })
                       })

               }
           }

           /***************************END OF EVENT LISTENERS FUNCTIONS********************/

           APP.DOMElements.saveButton.addEventListener('click', saveHandler);

           /*Activation select2 framework and datetimepicker*/
           (function initial() {
               $('#departments').select2();
               $('#typ').select2();
               $('.form_date').datetimepicker({
                   language:  'pl',
                   autoclose: 1,
                   minView : 2,
                   pickTime: false,
               });
           })();

       });
    </script>
@endsection
