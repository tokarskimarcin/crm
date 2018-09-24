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
    {{--<link rel="stylesheet" href="{{asset('/css/fixedHeader.dataTables.min.css')}}">
--}}
@endsection
@section('content')

    <style>
        textarea.baseDivision {
            resize: none;

        }

        .colorRow {
            /*background-color: #565fff !important;*/
            animation-name: example;
            animation-duration: 1s;
            animation-fill-mode: forwards;
        }

        .page-info {
            font-size: 1.3em;
        }

        @keyframes example {
            from {background-color: white;}
            to {background-color: #565fff ;}
        }
    </style>

{{--Header page --}}

    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav "> Szczegółowe informacje o kampaniach
        </div>
    </div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Szczegółowe informacje o kampaniach
            </div>
            <div class="panel-body">
                <div class="alert alert-info page-info">
                    Moduł <span style="font-weight: bold;">Szczegółowe informacje o kampaniach</span> wyświetla informacje o poszczególnych pokazo-godzinach,
                    które mogą być zbiorczo edytowane. Tabela z rekordami może być filtrowana dostępnymi polami wielokrotnego wyboru.
                    Aby edytować rekordy, należy je zaznaczyć i nacisnąć przycisk <span style="font-weight: bold;">Edytuj rekord(y)</span>.
                    Jeśli liczba <i>zaproszeń live</i> przekracza zakładane <i>limity</i>, w kolumnie <i>straty</i> wyswietli się 0. <br>
                    Pokazy anulowane mają cały wiersz w kolorze <span style="background-color: #ffc0bd;">czerwonym</span>.
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="year">Rok</label>
                            <select id="year" multiple="multiple" style="width: 100%;">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="weeks">Tygodnie</label>
                            <select id="weeks" multiple="multiple" style="width: 100%;">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="departments">Oddział</label>
                            <select id="departments" multiple="multiple" style="width: 100%;">
                                    <option value="dep_-1">Nieprzydzielone</option>
                                @foreach($departmentInfo as $item)
                                    <option value="dep_{{$item->id}}">{{$item->name2}} {{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="typ">Typ</label>
                            <select id="typ" multiple="multiple" style="width: 100%;">
                                <option value="2">Wysyłka</option>
                                <option value="1">Badania</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 buttonSection" style="min-height: 3.5em;">
                        <button class="btn btn-info" data-toggle="modal" data-target="#editModal" style="margin-bottom: 1em;  width: 100%;" id="editOneRecord" disabled>
                            <span class='glyphicon glyphicon-edit'></span> Edytuj rekordy</button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-default" id="clearButton" style="width:100%;">
                            <span class='glyphicon glyphicon-unchecked'></span> Wyczyść zaznaczenia <span class="badge">0</span></button>
                    </div>

                </div>
                <div class="row">
                    <table id="datatable" class="thead-inverse table table-striped table-bordered compact" style="max-width:100%;">
                        <thead>
                        <tr>
                            <th>Tydz</th>
                            <th>Data</th>
                            <th>Godz</th>
                            <th>Kampania</th>
                            <th>Podział bazy</th>
                            <th>Sprawdzenie</th>
                            <th>Zaproszenia <br>Live</th>
                            <th>Limit</th>
                            <th>Straty</th>
                            <th>Nazwa_klienta</th>
                            <th>Oddział</th>
                            <th>Uwagi</th>
                            <th>Nr kampanii (PBX)</th>
                            <th>Nazwa_Hotelu</th>
                            <th>Adres_Hotelu</th>
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
        {{--<script src="{{asset('/js/dataTables.fixedHeader.min.js')}}"></script>--}}
    <script>
       document.addEventListener('DOMContentLoaded', function() {
           /********** GLOBAL VARIABLES ***********/

           let APP = {
               arrays: {
                   selectedYears: ["0"], //this array collect selected by user years
                   selectedWeeks: ["0"], //this array collect selected by user weeks
                   selectedDepartments: ["0"], //this array collect selected by user departments
                   clientRouteInfoIdArr: [], //array of client_route_info ids
                   selectedTypes: ['0'], //array of selected by user types
                   arrayOfTableRows: [] //array of modal table rows
               },
               DOMElements: {
                   clearButton: document.querySelector('#clearButton'),
                   editButton: document.querySelector('#editOneRecord')
               },
               globalVariables: {
                   datatableHeight: '75vh' //this variable defines height of table
               }
           };

           /*******END OF GLOBAL VARIABLES*********/

           $('#menu-toggle').change(()=>{
               table.columns.adjust().draw();
           });


           /**
            * This function shows notification.
            */
           function notify(htmltext$string, type$string = 'info', delay$miliseconds$number = 5000) {
               $.notify({
                   // options
                   message: htmltext$string
               },{
                   // settings
                   type: type$string,
                   delay: delay$miliseconds$number,
                   animate: {
                       enter: 'animated fadeInRight',
                       exit: 'animated fadeOutRight'
                   }
               });
           }

           /**
            * This function color selected row and add id value to array.
            */
           function colorRowAndAddIdToArray(id, row) {
               let flag = false;
               let iterator = 0; //in this variable we will store position of id in array, that has been found.
              APP.arrays.clientRouteInfoIdArr.forEach(stringId => {
                   if (id === stringId) {
                       flag = true; //true - this row is already checked
                   }
                   if (!flag) {
                       iterator++;
                   }
               });

               if (flag) {
                   APP.arrays.clientRouteInfoIdArr.splice(iterator, 1);
                   row.removeClass('colorRow');

                   //this part removes object with given id form arrayOfTableRows
                   iterator = 0;
                   APP.arrays.arrayOfTableRows.forEach(clientId => {
                       if(id == clientId.id) {
                           APP.arrays.arrayOfTableRows.splice(iterator, 1);
                       }
                       iterator++;
                   });

               }
               else {
                   APP.arrays.clientRouteInfoIdArr.push(id);
                   row.addClass('colorRow');
               }
               $('#clearButton').find('.badge').text(APP.arrays.clientRouteInfoIdArr.length);
           }

           /**
            * This function append modify button with proper name and remove it if necessary
            */
           function showModifyButton() {
               if (APP.arrays.clientRouteInfoIdArr.length >0) {
                   APP.DOMElements.editButton.disabled = false;
                   addModalBodyContext();
               }
               else {
                   APP.DOMElements.editButton.disabled = true;
               }
           }

           /**********************************************************/
           /*****************MODAL FUNCTIONS**************************/
           /**********************************************************/

           /**
            * This function fill modal body and attach event listener to submit button.
            */
           function addModalBodyContext() {
               let modalBody = document.querySelector('.edit-modal-body');
               modalBody.innerHTML = ''; //clear modal body

               appendModalAlert(modalBody);
               createModalTable(modalBody); //table part of modal
               appendNrPBXInput(modalBody);
               appendBaseDivisionInput(modalBody);
               appendVerificationSelect(modalBody);
               // appendInvitationInput(modalBody);
               appendLiveInvitationsInput(modalBody);
               appendLimitInput(modalBody);
               appendDepartmentSelect(modalBody);
               appendCommentInput(modalBody);

               let submitButton = document.createElement('button');
               submitButton.id = 'submitEdition';
               submitButton.classList.add('btn', 'btn-success');
               submitButton.style.marginTop = '1em';
               submitButton.style.width = "100%";
               $(submitButton).append($("<span class='glyphicon glyphicon-save'></span>"));
               $(submitButton).append(" Zapisz");

               modalBody.appendChild(submitButton);

               /*Event Listener Part*/
               submitButton.addEventListener('click', function() {
                   const nrPBXInput = document.querySelector('#changeNrPBX');
                   const baseDivisionInput = document.querySelector('#changeBaseDivision');
                   const limitInput = document.querySelector('#changeLimits');
                   const commentInput = document.querySelector('#changeComments');
                   const verificationInput = document.querySelector('#changeVerification');
                   // const invitationInput = document.querySelector('#invitations');
                   const departmentSelect = document.querySelector('#modalDepartment');
                   const liveInput = document.querySelector('#liveInvitation');

                   const nrPBXValue = nrPBXInput.value;
                   const baseDivisionValue = baseDivisionInput.value;
                   const limitValue = limitInput.value;
                   const commentValue = commentInput.value;
                   const verificationValue = verificationInput.options[verificationInput.selectedIndex].value;
                   // const invitationValue = invitationInput.value;
                   const departmentValue = departmentSelect.options[departmentSelect.selectedIndex].value;
                   const liveInvitationValue = liveInput.value;

                   const url = `{{route('api.updateClientRouteInfoRecords')}}`;
                   const header = new Headers();
                   header.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                   const data = new FormData();
                   const JSONClientRouteInfoIdArr = JSON.stringify(APP.arrays.clientRouteInfoIdArr);
                   data.append('ids', JSONClientRouteInfoIdArr);

                   if(nrPBXValue != ''){
                       data.append('nrPBX', nrPBXValue);
                   }
                   if(baseDivisionValue != ''){
                       data.append('baseDivision', baseDivisionValue);
                   }
                   if(limitValue != '') {
                       data.append('limit', limitValue);
                   }
                   if(commentValue != '') {
                       data.append('comment', commentValue);
                   }
                   if(verificationValue != -1) {
                       data.append('verification', verificationValue);
                   }
                   // if(invitationValue != '') {
                   //     data.append('invitation', invitationValue);
                   // }
                   if(departmentValue != '0') {
                       data.append('department', departmentValue);
                   }
                   if(liveInvitationValue > 0) {
                       data.append('liveInvitation', liveInvitationValue);
                   }

                   fetch(url, {
                       method: "POST",
                       headers: header,
                       body: data,
                       credentials: "same-origin"
                   })
                       .then(response => response.text())
                       .then(response => {
                           notify("Rekordy zostały zmienione!", "info");
                           table.ajax.reload();
                       })
                       .catch(error => console.error("Błąd :", error))

                   $('#editModal').modal('toggle');
               });
           }

           /**
            * This function create one row of modal table and place it in rows array.
            */
           function createModalTableRow() {
               APP.arrays.clientRouteInfoIdArr.forEach(item => {
                   let addFlag = true;
                   let idItem = item;
                   APP.arrays.arrayOfTableRows.forEach(clientId => {
                       if(item == clientId.id) {
                           addFlag = false;
                       }
                   });

                   if(addFlag == true) {
                       let givenRow = document.querySelector('[data-id="' + idItem + '"]');
                       console.log(givenRow);
                       let givenRowData = givenRow.cells[1].textContent;
                       let givenRowKampania = givenRow.cells[3].textContent;
                       let givenRowProjekt = givenRow.cells[9].textContent;
                       let hotelName = givenRow.cells[12].textContent;
                       let hotelAdress = givenRow.cells[13].textContent;
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

                       let td4 = document.createElement('td');
                       td4.textContent = hotelName;
                       tr.appendChild(td4);

                       let td5 = document.createElement('td');
                       td5.textContent = hotelAdress;
                       tr.appendChild(td5);

                       let rowObject = {
                           id: idItem,
                           row: tr
                       };

                       APP.arrays.arrayOfTableRows.push(rowObject);
                   }
               });
           }

           /**
            * This function create on-fly table with basic info about selected rows by user.
            */
           function createModalTable(placeToAppend) {
               createModalTableRow();

               let infoTable = document.createElement('table');
               infoTable.classList.add('table', 'table-stripped');
               let theadElement = document.createElement('thead');
               let tbodyElement = document.createElement('tbody');
               let tr1 = document.createElement('tr');
               let th1 = document.createElement('th');
               let th2 = document.createElement('th');
               let th3 = document.createElement('th');
               let th4 = document.createElement('th');
               let th5 = document.createElement('th');

               th1.textContent = "Data";
               tr1.appendChild(th1);

               th2.textContent = "Kampania";
               tr1.appendChild(th2);

               th3.textContent = "Projekt";
               tr1.appendChild(th3);

               th4.textContent = "Nazwa hotelu";
               tr1.appendChild(th4);

               th5.textContent = "Adres";
               tr1.appendChild(th5);

               theadElement.appendChild(tr1);

               infoTable.appendChild(theadElement);
               APP.arrays.clientRouteInfoIdArr.forEach(item => {
                   APP.arrays.arrayOfTableRows.forEach(tableRow => {
                       if(item == tableRow.id){
                           tbodyElement.appendChild(tableRow.row);
                       }
                   })

               });

               infoTable.appendChild(tbodyElement);
               placeToAppend.appendChild(infoTable);
           }

           /**
            * This function append to modal Live Invitation input
            */
           function appendLiveInvitationsInput(placeToAppend) {
               let label = document.createElement('label');
               label.setAttribute('for', 'liveInvitation');
               label.textContent = 'Zaproszenia Live';
               placeToAppend.appendChild(label);

               let liveInput = document.createElement('input');
               liveInput.id = 'liveInvitation';
               liveInput.setAttribute('type', 'text');
               //liveInput.setAttribute('step', '1');
               //liveInput.setAttribute('min', '0');
               liveInput.setAttribute('placeholder', 'Aktualna liczba wydzwonionych zaproszeń np: 20');
               liveInput.classList.add('form-control');
               $(liveInput).on('input',function (e) {
                   if(!$.isNumeric($(e.target).val())){
                       $(e.target).val('');
                   }else if($(e.target).val()<0){
                       $(e.target).val(0);
                   }
               });
               placeToAppend.appendChild(liveInput);
           }

           /**
            * This function append to modal verification input
            */
           function appendVerificationSelect(placeToAppend) {
               let label3 = document.createElement('label');
               label3.setAttribute('for', 'changeVerification');
               label3.textContent = "Sprawdzenie";
               placeToAppend.appendChild(label3);

               let verificationSelect = document.createElement('select');
               verificationSelect.classList.add('form-control');
               verificationSelect.id = 'changeVerification';

               let option1 = document.createElement('option');
               option1.value = '-1';
               option1.textContent = "Wybierz";

               let option2 = document.createElement('option');
               option2.value = '0';
               option2.textContent = "Nie";

               let option3 = document.createElement('option');
               option3.value = '1';
               option3.textContent = "Tak";
               verificationSelect.appendChild(option1);
               verificationSelect.appendChild(option2);
               verificationSelect.appendChild(option3);
               placeToAppend.appendChild(verificationSelect);
           }
           /**
            * This function append to modal nr pbx input
            */
           function appendNrPBXInput(placeToAppend){
               let label = document.createElement('label');
               label.setAttribute('for', 'changeNrPBX');
               label.textContent = 'Numer kampanii (PBX)';
               placeToAppend.appendChild(label);

               let NrPBXInput = document.createElement('input');
               NrPBXInput.id = 'changeNrPBX';
               NrPBXInput.setAttribute('type', 'text');
               //NrPBXInput.setAttribute('step', '1');
               //NrPBXInput.setAttribute('min', '0');
               NrPBXInput.setAttribute('placeholder', 'Numer kampanii z PBX');
               NrPBXInput.classList.add('form-control');
               $(NrPBXInput).on('input',function (e) {
                   if(!$.isNumeric($(e.target).val())){
                       $(e.target).val('');
                   }else if($(e.target).val()<0){
                       $(e.target).val(0);
                   }
               });
               placeToAppend.appendChild(NrPBXInput);
           }
           /**
            * This function append to modal division base input
            */
           function appendBaseDivisionInput(placeToAppend){
               let label = document.createElement('label');
               label.setAttribute('for', 'changeBaseDivision');
               label.textContent = 'Podział bazy';
               placeToAppend.appendChild(label);

               let baseDivisionInput = document.createElement('input');
               baseDivisionInput.id = 'changeBaseDivision';
               baseDivisionInput.setAttribute('type', 'text');
               baseDivisionInput.classList.add('form-control');
               placeToAppend.appendChild(baseDivisionInput);
           }
           /**
            * This function append to modal limit input
            */
           function appendLimitInput(placeToAppend) {
               let label = document.createElement('label');
               label.setAttribute('for', 'changeLimits');
               label.textContent = 'Limit';
               placeToAppend.appendChild(label);

               let limitInput = document.createElement('input');
               limitInput.id = 'changeLimits';
               limitInput.setAttribute('type', 'text');
               limitInput.setAttribute('placeholder', 'Limit dla danej godziny z kampanii');
               //limitInput.setAttribute('step', '1');
               //limitInput.setAttribute('min', '0');
               limitInput.classList.add('form-control');
               $(limitInput).on('input',function (e) {
                   if(!$.isNumeric($(e.target).val())){
                       $(e.target).val('');
                   }else if($(e.target).val()<0){
                       $(e.target).val(0);
                   }
               });
               placeToAppend.appendChild(limitInput);
           }

           /**
            * This function append to modal comment input
            */
           function appendCommentInput(placeToAppend) {
               let label2 = document.createElement('label');
               label2.setAttribute('for', 'changeComments');
               label2.textContent = 'Uwagi';
               placeToAppend.appendChild(label2);

               let commentInput = document.createElement('input');
               commentInput.id = 'changeComments';
               commentInput.setAttribute('type', 'text');
               commentInput.setAttribute('placeholder', 'Tutaj można umieścić krótki komentarz');
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
               label.textContent = 'Zaproszenia Live';
               placeToAppend.appendChild(label);

               let invitationInput = document.createElement('input');
               invitationInput.id = 'invitations';
               invitationInput.classList.add('form-control');
               invitationInput.setAttribute('type', 'number');
               invitationInput.setAttribute('min', '0');
               invitationInput.setAttribute('step', '1');
               placeToAppend.appendChild(invitationInput);
           }

           /**
            * This function append to modal department select
            */
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

           /**********************************************************/
           /****************END OF MODAL FUNCTIONS********************/
           /**********************************************************/

           let table = $('#datatable').DataTable({
               autoWidth: true,
               processing: true,
               serverSide: true,
               ordering: false,
               scrollY: APP.globalVariables.datatableHeight,
               "drawCallback": function( settings ) {

               },
               "rowCallback": function( row, data, index ) {
                   if(data.comment+'' != 'null' && data.comment !== ''){
                       $(row).css('background-color', '#fffc8b');
                   }

                   if(data.nrPBX == null){
                       for(let i =6; i <=8; i++){
                           $($(row).children()[i]).css('background-color', '#ff7878');
                       }
                   }else if(data.loseSuccess >= 0){
                       for(let i =6; i <=8; i++){
                           $($(row).children()[i]).css('background-color', '#78ff80');
                       }
                   }
                   row.setAttribute('data-id', data.id);
                   APP.arrays.clientRouteInfoIdArr.forEach(specificId => { //when someone change table page, we have to reassign classes to rows.
                       if (specificId == data.id) {
                           row.classList.add('colorRow');
                       }
                   });
                   $(row).find('.commentInput').val(data.comment);
                   $(row).find('.comment button').click(function (e) {
                       let campaignId = $(e.target).attr('id');
                       swal({
                           title: 'Czy na pewno?',
                           text: 'Czy chcesz usunąc uwagę?',
                           type:'warning',
                           showCancelButton: true
                       }).then((result) => {
                           if(result.value){
                               $.ajax({
                                   type: 'POST',
                                   url: '{{route('api.removeCampaignComment')}}',
                                   headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                   data: {
                                       campaignId: campaignId
                                   },
                                   success: function (response) {
                                       console.log(response);
                                       if(response == 'success')
                                           table.ajax.reload();
                                   }
                               });
                           }
                       });

                   });
                   if(data.canceled == 1) {
                       $(row).css("background-color", '#ffc0bd');
                   }
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
                        d.years = APP.arrays.selectedYears;
                        d.weeks = APP.arrays.selectedWeeks;
                        d.departments = APP.arrays.selectedDepartments;
                        d.typ = APP.arrays.selectedTypes;
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
                            if(data.hour) {
                                return data.hour.slice(0,-3);
                            }
                            else {
                                return '';
                            }

                       },"name":"hour"
                   },
                   {"data":function (data, type, dataToSet) {
                       if(data.nrPBX != null) {
                           return data.cityName +" ("+data.nrPBX+")";
                       }
                       else {
                           return data.cityName;
                       }
                       },"name":"cityName"
                   },
                   {"data": function (data, type, dataToSet) {
                           if(data.baseDivision != null) {
                               return '<textarea class="form-control baseDivision" cols="5" rows="1" readonly>'+data.baseDivision+'</textarea>';
                           }
                           else {
                               return "";
                           }
                               /*return data.baseDivision;
                           else
                               return "";*/
                   },"name":"baseDivision","orderable" : false
                   },
                   {"data":function (data, type, dataToSet) {
                       let verificationInfo;
                       if(data.verification == '1') {
                           verificationInfo = 'TAK';
                           // return '<select class="form-control" style="width:100%;" data-id="' + data.id + '" data-type="noAction"><option value="0" data-type="noAction" selected>Nie</option><option value="1" data-type="noAction">Tak</option></select>';
                       }
                       else {
                           verificationInfo = 'NIE'; // return '<select class="form-control" style="width:100%;" data-id="' + data.id + '" data-type="noAction"><option value="0" data-type="noAction">Nie</option><option data-type="noAction" value="1" selected>Tak</option></select>';
                       }
                           return verificationInfo;
                       },"name":"verification","orderable" : false
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.actual_success;
                       },"name":"actual_success","orderable" : false
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.limits;
                       },"name":"limits","orderable" : false
                   },
                   {"data":function (data, type, dataToSet) {
                       if(data.loseSuccess <= 0) {
                           return data.loseSuccess;
                       }
                       else {
                           return 0;
                       }
                       },"name":"loseSuccess","orderable" : false
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
                            //let fullDepartmentName = data.departmentName == null ? null : data.departmentName + ' ' + data.departmentName2;
                           return data.departmentName;
                       },"name":"departmentName", "searchable": "false","orderable" : false
                   },
                   {"data":function (data, type, dataToSet) {
                            if(data.comment == '' || data.comment == null){
                                return '';
                            }else{
                                let commentInput = $(document.createElement('input')).attr('type','text').addClass('commentInput form-control')
                                    .prop('readonly',true)
                                    .css('width','10em');

                                let removeCommentButtonSpan =  $(document.createElement('span')).addClass('glyphicon glyphicon-remove');//.css({'position':'absolute'});
                                let removeCommentButton =  $(document.createElement('button')).attr('id',data.id).addClass('btn btn-default').append(removeCommentButtonSpan);

                                let inputGroupSpan =  $(document.createElement('span')).addClass('input-group-btn').append(removeCommentButton);

                                let inputGroup = $(document.createElement('div')).addClass('input-group').append(commentInput).append(inputGroupSpan);
                                let commentDivRow = $(document.createElement('div')).addClass('row').addClass('comment');
                                let commentColumn = $(document.createElement('div')).addClass('col-md-12');
                                commentColumn.append(inputGroup);
                                commentDivRow.append(commentColumn);
                                return commentDivRow.prop('outerHTML');
                            }
                       },"name":"comment","orderable" : false
                   },
                   {"data":"nrPBX", "visible":false},
                   {"data":function (data, type, dataToSet) {
                           return data.hotelName;
                       },"name":"hotelName"
                   },
                   {"data":function (data, type, dataToSet) {
                           return data.hotelAdress;
                       },"name":"hotelAdress"
                   }
               ],
               order: [[1, 'asc'], [9, 'asc'],[3, 'desc'], [2, 'asc']],
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
                       let sumAllLose =
                           rows
                               .data()
                               .pluck('loseSuccess')
                               .reduce( function (a, b) {
                                   return a + b*1;
                               }, 0);
                       if(sumAllLose > 0 ) sumAllLose = 0;

                       return $('<tr/>')
                           .append('<td colspan="2">Podsumowanie Dnia: ' + group + '</td>')
                           .append('<td></td>')
                           .append('<td>' + sumAllCampaings + '</td>')
                           .append('<td></td>')
                           .append('<td></td>')
                           .append('<td>' + sumAllSuccess + '</td>')
                           .append('<td>' + sumAllLimit + '</td>')
                           .append('<td>' + sumAllLose + '</td>')
                           .append('<td colspan="5"></td>')
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
                       APP.arrays.selectedYears = [j];
                   }
                   yearSelect.appendChild(opt);
               }

               for(let i = 1; i <= maxWeekInYear + 1; i++) {
                   const opt = document.createElement('option');
                   opt.value = i;
                   opt.textContent = i;
                   if(i == currentWeek) {
                       opt.setAttribute('selected', 'selected');
                       APP.arrays.selectedWeeks = [i];
                   }
                   weekSelect.appendChild(opt);
               }
           })();

           /*********************EVENT LISTENERS FUNCTIONS****************************/
           /*Functions from this section moslty update arrays which are going to be send by ajax for datatable.
           /**********************************************************/

           /**
            * This event listener change elements of array selected Years while user selects another year
            */
           $('#year').on('select2:select', function () {
               let yearArr = $('#year').val();
               if(yearArr.length > 0) { //no values, removed by user
                   APP.arrays.selectedYears = yearArr;
               }
               else {
                   APP.arrays.selectedYears = ["0"];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selected Years while user unselects some year
            */
           $('#year').on('select2:unselect', function() {
               if($('#year').val().length != 0) {
                   APP.arrays.selectedYears = $('#year').val();
               }
               else {
                   APP.arrays.selectedYears = ["0"];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selecteWeeks while user selects another week
            */
           $('#weeks').on('select2:select', function() {
               let weeksArr = $('#weeks').val();
               console.log('weeksArr', weeksArr);
               if(weeksArr.length > 0) {
                   APP.arrays.selectedWeeks = weeksArr;
               }
               else {
                   APP.arrays.selectedWeeks = ["0"];
               }
               table.ajax.reload();
           });

           /**
            * This event listener change elements of array selectedWeeks while user unselects any week.
            */
           $("#weeks").on('select2:unselect', function() {
               if($('#weeks').val().length != 0) {
                   APP.arrays.selectedWeeks = $('#weeks').val();
               }
               else {
                   APP.arrays.selectedWeeks = ['0'];
               }
               table.ajax.reload();
           });

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

           /***************************END OF EVENT LISTENERS FUNCTIONS********************/

           /*Activation select2 framework*/
           (function initial() {
               $('#weeks').select2();
               $('#year').select2();
               $('#departments').select2();
               $('#typ').select2();
           })();

           /**
            * This function clear all row selections and disable edit button
            */
           function clearAllSelections(e) {
               if(APP.arrays.arrayOfTableRows.length > 0) {
                   if(document.querySelectorAll('.colorRow')) {
                       const coloredRows = document.querySelectorAll('.colorRow');
                       coloredRows.forEach(colorRow => {
                           colorRow.classList.remove('colorRow');
                       });
                       APP.DOMElements.editButton.disabled = true;

                       notify("<strong>Wszystkie zaznaczenia zostały usuniete</strong>", 'success', 4000);
                   }
               }
               APP.arrays.clientRouteInfoIdArr = [];
               APP.arrays.arrayOfTableRows = [];
               $(e.target).find('.badge').text(APP.arrays.clientRouteInfoIdArr.length);
           }

            APP.DOMElements.clearButton.addEventListener('click', function (e){
               clearAllSelections(e);
           });
       });
    </script>
@endsection
