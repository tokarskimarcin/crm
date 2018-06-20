{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view shows user detailed info about show hours with possibility of edition,--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: showRoutesDetailedUpdateAjax, campaignsInfo, showRoutesDetailedGet--}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/rowgroup/1.0.3/css/rowGroup.dataTables.min.css" rel="stylesheet" />

@endsection
@section('content')

    <style>
        .colorRow {
            /*background-color: #565fff !important;*/
            animation-name: example;
            animation-duration: 1s;
            animation-fill-mode: forwards;
        }

        @keyframes example {
            from {backgroud-color: white;}
            to {background-color: #565fff;}
        }
    </style>

{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav "> Szczegółowe informacje o kampaniach
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="year">Rok</label>
                                <select id="year" class="form-control" multiple="multiple">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="weeks">Tygodnie</label>
                                <select id="weeks" class="form-control" multiple="multiple">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="departments">Oddział</label>
                                <select id="departments" class="form-control" multiple="multiple">
                                    @foreach($departmentInfo as $item)
                                        <option value="dep_{{$item->id}}">{{$item->name2}} {{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="typ" style="display: block;">Typ</label>
                                <select id="typ" multiple="multiple">
                                    <option value="1">Wysyłka</option>
                                    <option value="2">Badania</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 buttonSection" style="min-height: 3.5em;">

                        </div>
                    </div>
                        <table id="datatable" class="thead-inverse table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Tydzien</th>
                                <th>Data</th>
                                <th>Kampania</th>
                                <th>SMS</th>
                                <th>Zaproszenia Live</th>
                                <th>Limit</th>
                                <th>Straty</th>
                                <th>Projekt</th>
                                <th>Oddział</th>
                                <th>Uwagi</th>
                            </tr>
                            </thead>
                        </table>
                    <div class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edytuj</h4>
                </div>
                <div class="modal-body edit-modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij okno</button>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
        <script src="https://cdn.datatables.net/rowgroup/1.0.3/js/dataTables.rowGroup.min.js"></script>
        <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
       document.addEventListener('DOMContentLoaded', function(mainEvent) {
           /********** GLOBAL VARIABLES ***********/
           let selectedYears = ["0"]; //this array collect selected by user years
           let selectedWeeks = ["0"]; //this array collect selected by user weeks
           let selectedDepartments = ["0"]; //this array collect selected by user departments
           let clientRouteInfoIdArr = []; //array of client_route_info ids
           let selectedTypes = ['0']; //array of selected by user types
           /*******END OF GLOBAL VARIABLES*********/

           /**
            * This function color selected row and add id value to array.
            */
           function colorRowAndAddIdToArray(id, row) {
               let flag = false;
               let idArr = [];
               let iterator = 0; //in this variable we will store position of id in array, that has been found.
               clientRouteInfoIdArr.forEach(stringId => {
                   if (id === stringId) {
                       flag = true; //true - this row is already checked
                   }
                   if (!flag) {
                       iterator++;
                   }
               });

               if (flag) {
                   clientRouteInfoIdArr.splice(iterator, 1);
                   row.removeClass('colorRow');
               }
               else {
                   clientRouteInfoIdArr.push(id);
                   row.addClass('colorRow');
               }
           }

           /**
            * This function append modify button with proper name and remove it if necessary
            */
           function showModifyButton() {
               if (clientRouteInfoIdArr.length == 1) {
                   if (document.querySelector('#editMultipleRecords')) { //if "edytuj rekordy" button exists, remove it
                       const previousButton = document.querySelector('#editMultipleRecords');
                       previousButton.parentNode.removeChild(previousButton);
                   }
                   const buttonSection = document.querySelector('.buttonSection');
                   let editButton = document.createElement('button');
                   editButton.id = 'editOneRecord';
                   editButton.classList.add('btn', 'btn-info');
                   editButton.textContent = "Edytuj rekord";
                   editButton.setAttribute('data-toggle', 'modal');
                   editButton.setAttribute('data-target', '#editModal');
                   editButton.style.marginBottom = '1em';
                   buttonSection.appendChild(editButton);
                   addModalBodyContext();
               }
               else if (clientRouteInfoIdArr.length > 1) {
                   if (document.querySelector('#editOneRecord')) { //inf "edytuj rekord" button exists, remove it
                       const previousButton = document.querySelector('#editOneRecord');
                       previousButton.parentNode.removeChild(previousButton);
                   }
                   if (!document.querySelector('#editMultipleRecords')) {
                       const buttonSection = document.querySelector('.buttonSection');
                       let editButton = document.createElement('button');
                       editButton.id = 'editMultipleRecords';
                       editButton.classList.add('btn', 'btn-info');
                       editButton.textContent = "Edytuj rekordy";
                       editButton.setAttribute('data-toggle', 'modal');
                       editButton.setAttribute('data-target', '#editModal');
                       editButton.style.marginBottom = '1em';
                       buttonSection.appendChild(editButton);
                   }

                   addModalBodyContext();
               }
               else if (clientRouteInfoIdArr.length == 0) { //remove button if no row is selected
                   const buttonToRemove = document.querySelector('#editOneRecord');
                   buttonToRemove.parentNode.removeChild(buttonToRemove);
               }
           }

           /*****************MODAL FUNCTIONS**********************/

           /**
            * This function fill modal body and attach event listener to submit button.
            */
           function addModalBodyContext() {
               let modalBody = document.querySelector('.edit-modal-body');
               modalBody.innerHTML = ''; //clear modal body

               appendModalAlert(modalBody);
               createModalTable(modalBody); //table part of modal
               appendLimitInput(modalBody);
               appendCommentInput(modalBody);
               appendSmsSelect(modalBody);
               appendInvitationInput(modalBody);
               appendDepartmentSelect(modalBody);

               let submitButton = document.createElement('button');
               submitButton.id = 'submitEdition';
               submitButton.classList.add('btn', 'btn-success');
               submitButton.style.marginTop = '1em';
               // submitButton.style.marginBottom = '1em';
               submitButton.style.marginLeft = '90%';
               submitButton.textContent = 'Zapisz';
               modalBody.appendChild(submitButton);

               /*Event Listener Part*/
               submitButton.addEventListener('click', function(e) {
                   const limitInput = document.querySelector('#changeLimits');
                   const commentInput = document.querySelector('#changeComments');
                   const smsInput = document.querySelector('#changeSms');
                   const invitationInput = document.querySelector('#invitations');
                   const departmentSelect = document.querySelector('#modalDepartment');

                   const limitValue = limitInput.value;
                   const commentValue = commentInput.value;
                   const smsValue = smsInput.options[smsInput.selectedIndex].value;
                   const invitationValue = invitationInput.value;
                   const departmentValue = departmentSelect.options[departmentSelect.selectedIndex].value;

                   const url = `{{route('api.updateClientRouteInfoRecords')}}`;
                   const header = new Headers();
                   header.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                   const data = new FormData();
                   const JSONClientRouteInfoIdArr = JSON.stringify(clientRouteInfoIdArr);
                   data.append('ids', JSONClientRouteInfoIdArr);

                   if(limitValue != '') {
                       data.append('limit', limitValue);
                   }
                   if(commentValue != '') {
                       data.append('comment', commentValue);
                   }
                   if(smsValue != -1) {
                       data.append('sms', smsValue);
                   }
                   if(invitationValue != '') {
                       data.append('invitation', invitationValue);
                   }
                   if(departmentValue != '0') {
                       data.append('department', departmentValue);
                   }

                   fetch(url, {
                       method: "POST",
                       headers: header,
                       body: data,
                       credentials: "same-origin"
                   })
                       .then(response => response.text())
                       .then(response => {
                           $.notify({
                               // options
                               message: 'Rekordy zostały zmienione!'
                           },{
                               // settings
                               type: 'info',
                               z_index: '100000'
                           });
                           table.ajax.reload();

                       })
                       .catch(error => console.log("Błąd :", error))
               });
           }

           /**
            * This function create on-fly table with basic info about selected rows by user.
            */
           function createModalTable(placeToAppend) {
               let infoTable = document.createElement('table');
               infoTable.classList.add('table', 'table-stripped');
               let theadElement = document.createElement('thead');
               let tbodyElement = document.createElement('tbody');
               let tr1 = document.createElement('tr');
               let th1 = document.createElement('th');
               let th2 = document.createElement('th');
               let th3 = document.createElement('th');

               th1.textContent = "Data";
               tr1.appendChild(th1);

               th2.textContent = "Kampania";
               tr1.appendChild(th2);

               th3.textContent = "Projekt";
               tr1.appendChild(th3);

               theadElement.appendChild(tr1);

               infoTable.appendChild(theadElement);
               clientRouteInfoIdArr.forEach(item => {
                   let givenRow = document.querySelector('[data-id="' + item + '"]');
                   givenRowData = givenRow.cells[1].textContent;
                   givenRowKampania = givenRow.cells[2].textContent;
                   givenRowProjekt = givenRow.cells[7].textContent;
                   let tr = document.createElement('tr');
                   let td1 = document.createElement('td');
                   td1.textContent = givenRowData;
                   tr.appendChild(td1);
                   let td2 = document.createElement('td');
                   td2.textContent = givenRowKampania;
                   tr.appendChild(td2);
                   let td3 = document.createElement('td');
                   td3.textContent = givenRowProjekt;
                   tr.appendChild(td3);
                   tbodyElement.appendChild(tr);
               });

               infoTable.appendChild(tbodyElement);
               placeToAppend.appendChild(infoTable);
           }

           /**
            * This function append to modal sms input
            */
           function appendSmsSelect(placeToAppend) {
               let label3 = document.createElement('label');
               label3.setAttribute('for', 'changeSms');
               label3.textContent = "Czy sms został ustalony?";
               placeToAppend.appendChild(label3);

               let smsSelect = document.createElement('select');
               smsSelect.classList.add('form-control');
               smsSelect.id = 'changeSms';

               let option1 = document.createElement('option');
               option1.value = '-1';
               option1.textContent = "Wybierz";

               let option2 = document.createElement('option');
               option2.value = '0';
               option2.textContent = "Nie";

               let option3 = document.createElement('option');
               option3.value = '1';
               option3.textContent = "Tak";
               smsSelect.appendChild(option1);
               smsSelect.appendChild(option2);
               smsSelect.appendChild(option3);
               placeToAppend.appendChild(smsSelect);
           }

           /**
            * This function append to modal limit input
            */
           function appendLimitInput(placeToAppend) {
               let label = document.createElement('label');
               label.setAttribute('for', 'changeLimits');
               label.textContent = 'Podaj wartość limitu';
               placeToAppend.appendChild(label);

               let limitInput = document.createElement('input');
               limitInput.id = 'changeLimits';
               limitInput.setAttribute('type', 'number');
               limitInput.setAttribute('step', '1');
               limitInput.setAttribute('min', '0');
               limitInput.classList.add('form-control');
               placeToAppend.appendChild(limitInput);
           }

           /**
            * This function append to modal comment input
            */
           function appendCommentInput(placeToAppend) {
               let label2 = document.createElement('label');
               label2.setAttribute('for', 'changeComments');
               label2.textContent = 'Treść komentarza';
               placeToAppend.appendChild(label2);

               let commentInput = document.createElement('input');
               commentInput.id = 'changeComments';
               commentInput.setAttribute('type', 'text');
               commentInput.classList.add('form-control');
               placeToAppend.appendChild(commentInput);
           }

           /**
            * This function append to modal alert info
            */
           function appendModalAlert(placeToAppend) {
               let alertElement = document.createElement('div');
               alertElement.classList.add('alert', 'alert-danger');
               alertElement.textContent = "Jeśli nie chcesz zmieniać wartości danego pola, pozostaw puste miejsce w okienku.";
               placeToAppend.appendChild(alertElement);
           }

           /**
            * This function append to modal invitation input
            */
           function appendInvitationInput(placeToAppend) {
               let label = document.createElement('label');
               label.setAttribute('for', 'invitations');
               label.textContent = 'Zaproszenia';
               placeToAppend.appendChild(label);

               let invitationInput = document.createElement('input');
               invitationInput.id = 'invitations';
               invitationInput.classList.add('form-control');
               invitationInput.setAttribute('type', 'number');
               invitationInput.setAttribute('min', '0');
               invitationInput.setAttribute('step', '1');
               placeToAppend.appendChild(invitationInput);
           }

           function appendDepartmentSelect(placeToAppend) {
               let label = document.createElement('label');
               label.setAttribute('for', 'modalDepartment');
               label.textContent = 'Oddział';
               placeToAppend.appendChild(label);

               let departmentSelect = document.createElement('select');
               departmentSelect.id = 'modalDepartment';
               departmentSelect.classList.add('form-control');

               let basicOption = document.createElement('option');
               basicOption.value = '0';
               basicOption.textContent = 'Wybierz';
               departmentSelect.appendChild(basicOption);

               @foreach($departmentInfo as $department)
                    var option = document.createElement('option');
                    option.value = `{{$department->id}}`;
                    option.textContent = `{{$department->name2}} {{$department->name}}`;
                    departmentSelect.appendChild(option);
               @endforeach

               placeToAppend.appendChild(departmentSelect);
           }

           /****************END OF MODAL FUNCTIONS********************/

           table = $('#datatable').DataTable({
               "autoWidth": false,
               "processing": true,
               "serverSide": true,
               order: [[1, 'asc']],
               "drawCallback": function( settings ) {

               },
               "rowCallback": function( row, data, index ) {
                    row.setAttribute('data-id', data.id);
                    clientRouteInfoIdArr.forEach(specificId => { //when someone change table page, we have to reassign classes to rows.
                        if(specificId == data.id) {
                            row.classList.add('colorRow');
                        }
                    });
               },
               "fnDrawCallback": function(settings) {
                   $('#datatable tbody tr').on('click', function(e) {
                        if(e.target.dataset.type != "noAction") {
                            const givenRow = $(this);
                            const clientRouteInfoId = givenRow.attr('data-id');
                            colorRowAndAddIdToArray(clientRouteInfoId, givenRow);
                            showModifyButton();
                        }
                   });

               },"ajax": {
                   'url': "{{route('api.campaignsInfo')}}",
                   'type': 'POST',
                   'data': function (d) {
                        d.years = selectedYears;
                        d.weeks = selectedWeeks;
                        d.departments = selectedDepartments;
                        d.typ = selectedTypes;
                   },
                   'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
               },
               "language": {
                   "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
               },
               "columns":[
                   {"data":function (data, type, dataToSet) {
                           return data.weekOfYear;
                       },"name":"weekOfYear"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.date;
                       },"name":"date"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.cityName;
                       },"name":"cityName"
                   },
                   {"data":function (data, type, dataToSet) {
                       let smsInfo;
                       if(data.sms == '1') {
                           smsInfo = 'TAK';
                           // return '<select class="form-control" style="width:100%;" data-id="' + data.id + '" data-type="noAction"><option value="0" data-type="noAction" selected>Nie</option><option value="1" data-type="noAction">Tak</option></select>';
                       }
                       else {
                           smsInfo = 'NIE'; // return '<select class="form-control" style="width:100%;" data-id="' + data.id + '" data-type="noAction"><option value="0" data-type="noAction">Nie</option><option data-type="noAction" value="1" selected>Tak</option></select>';
                       }
                           return smsInfo;
                       },"name":"sms"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.pbxSuccess;
                       },"name":"pbxSuccess"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.limits;
                       },"name":"limits"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.loseSuccess;
                       },"name":"loseSuccess"
                   },
                   {"data":function (data, type, dataToSet) {
                       let clientNameVariable = '';
                       if(data.typ == '2') {
                           clientNameVariable = data.clientName + ' (B)'
                       }
                       else {
                           clientNameVariable = data.clientName + ' (W)'
                       }
                           return clientNameVariable;
                       },"name":"clientName"
                   },
                   {"data":function (data, type, dataToSet) {
                            let fullDepartmentName = data.departmentName == null ? null : data.departmentName + ' ' + data.departmentName2;
                           return fullDepartmentName;
                       },"name":"departmentName", "searchable": "false"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.comment;
                       },"name":"comment"
                   }
               ],
               rowGroup: {
                   dataSrc: 'date',
                   startRender: null,
                   endRender: function (rows, group) {
                       var sumAllSuccess = 0;
                       sumAllSuccess =
                           rows
                           .data()
                           .pluck('pbxSuccess')
                           .reduce( function (a, b) {
                               return a + b*1;
                           }, 0);
                       var sumAllCampaings = rows.data().count();
                       var sumAllLimit =
                           rows
                               .data()
                               .pluck('limits')
                               .reduce( function (a, b) {
                                   return a + b*1;
                               }, 0);
                       var sumAllLose =
                           rows
                               .data()
                               .pluck('loseSuccess')
                               .reduce( function (a, b) {
                                   return a + b*1;
                               }, 0);

                       return $('<tr/>')
                           .append('<td colspan="2">Podsumowanie Dnia: ' + group + '</td>')
                           .append('<td>' + sumAllCampaings + '</td>')
                           .append('<td>' + sumAllSuccess + '</td>')
                           .append('<td> 0 </td>')
                           .append('<td>' + sumAllLimit + '</td>')
                           .append('<td>' + sumAllLose + '</td>')
                           .append('<td colspan="4"></td>')
                   },
               },
           });

           /**
            * This function appends week numbers to week select element and years to year select element
            * IIFE function, execute after page is loaded automaticaly
            */
           (function appendWeeksAndYears() {
               const maxWeekInYear = {{$lastWeek}}; //number of last week in current year
               const weekSelect = document.querySelector('#weeks');
               const yearSelect = document.querySelector('#year');
               const baseYear = '2017';
               const currentYear = {{$currentYear}};
               const currentWeek = {{$currentWeek}};

               for(let j = baseYear; j <= currentYear + 1; j++) {
                   const opt = document.createElement('option');
                   opt.value = j;
                   opt.textContent = j;
                   if(j == currentYear) {
                       opt.setAttribute('selected', 'selected');
                       selectedYears = [j];
                   }
                   yearSelect.appendChild(opt);
               }

               for(let i = 1; i <= maxWeekInYear + 1; i++) {
                   const opt = document.createElement('option');
                   opt.value = i;
                   opt.textContent = i;
                   if(i == currentWeek) {
                       opt.setAttribute('selected', 'selected');
                       selectedWeeks = [i];
                   }
                   weekSelect.appendChild(opt);
               }
           })();

           /*********************EVENT LISTENERS FUNCTIONS****************************/

           /**
            * This event listener change elements of array selected Years while user selects another year
            */
           $('#year').on('select2:select', function (e) {
               let yearArr = $('#year').val();
               console.log(yearArr);
               if(yearArr.length > 0) { //no values, removed by user
                   selectedYears = yearArr;
               }
               else {
                   selectedYears = ["0"];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selected Years while user unselects some year
            */
           $('#year').on('select2:unselect', function(e) {
               if($('#year').val() != null) {
                   let yearArr = $('#year').val();
                   selectedYears = yearArr;
               }
               else {
                   selectedYears = ["0"];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selecteWeeks while user selects another week
            */
           $('#weeks').on('select2:select', function(e) {
               let weeksArr = $('#weeks').val();
               if(weeksArr.length > 0) {
                   selectedWeeks = weeksArr;
               }
               else {
                   selectedWeeks = ["0"];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selectedWeeks while user unselects any week.
            */
           $("#weeks").on('select2:unselect', function(e) {
               if($('#weeks').val() != null) {
                   let weeksArr = $('#weeks').val();
                   selectedWeeks = weeksArr;
               }
               else {
                   selectedWeeks = ['0'];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selectedDepartments while user selects a department
            */
           $("#departments").on('select2:select', function(e) {
               let departments = $('#departments').val();
               if(departments.length > 0) {
                   let helpArray = [];
                   departments.forEach(item => {
                       let tempArray = [];
                      tempArray = item.split('_');
                      helpArray.push(tempArray[1]);
                   });
                   selectedDepartments = helpArray;
               }
               else {
                   selectedDepartments = ["0"];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selectedDepartments while user unselects a department
            */
           $("#departments").on('select2:unselect', function(e) {
              if($('#departments').val() != null) {
                  let departments = $('#departments').val();
                  let helpArray = [];
                  departments.forEach(item => {
                      let tempArray = [];
                      tempArray = item.split('_');
                      helpArray.push(tempArray[1]);
                  });
                  selectedDepartments = helpArray;
              }
              else {
                  selectedDepartments = ["0"];
              }

              table.ajax.reload();
           });

           /**
            * This event listener change elements of array selectedTypes while user selects any type
            */
           $('#typ').on('select2:select', function(e) {
               let types = $('#typ').val();
               if(types.length > 0) {
                   selectedTypes = types;
               }
               else {
                   selectedTypes = ['0'];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selectedTypes while user unselects any type
            */
           $('#typ').on('select2:unselect', function(e) {
               if($('#typ').val() != null) {
                   let types = $('#typ').val();
                   selectedTypes = types;
               }
               else {
                   selectedTypes = ['0'];
               }
               table.ajax.reload();
           });

           /***************************END OF EVENT LISTENERS FUNCTIONS********************/

           /*Activation select2 framework*/
           (function initial() {
               $('#weeks').select2();
               $('#year').select2();
               $('#departments').select2();
               $('#typ').select2();
           })();

       });
    </script>
@endsection
