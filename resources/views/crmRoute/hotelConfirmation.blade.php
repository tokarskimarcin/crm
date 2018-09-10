@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ asset('/css//buttons.dataTables.min.css')}}" rel="stylesheet">
@endsection
@section('content')

    <style>
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
        .accepted{
            background-color: #ebffd7 !important;
        }
        .toAccept{
            background-color: #fff7b9 !important;
        }
        .cancel{
            background-color: #ffebe6  !important;
        }


    </style>
    <div class="tu"></div>

    {{--Header page --}}
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Potwierdzanie hoteli</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Statusy Hoteli
                </div>
                <div class="panel-body">
                    <div class="alert alert-info page-info">
                        Moduł <span style="font-weight: bold;">Potwierdzanie hoteli</span>
                        wyświetla informacje o statusie potwierdzenia hotelu na daną kapmanię.
                        W tabeli znajdują się hotele, których pokaz jest dzień później niż wybrana data.
                        <br>
                        Status <span style="font-weight: bold;">Oczekuje na akceptacje</span>
                        oznacza iż hotel nie został jeszcze potwierdzony.
                        <br>
                        Status <span style="font-weight: bold;">Zaakceptopwano</span>
                        oznacz, iż hotel został potwierdzony i zaakceptowany. Aby hotel został zaakceptowany, uprzednio musi posiadać przypisany hotel do kampanii.
                        <br>
                        Status <span style="font-weight: bold;">Anulowany</span>
                        oznacza, iż hotel został anulowany.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Data:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control listen_to" id="date_start" name="date_start" type="text" value="{{date('Y-m-d')}}" >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>
                        {{--<div class="col-md-3">--}}
                            {{--<div class="form-group">--}}
                                {{--<label class="myLabel">Zakres do:</label>--}}
                                {{--<div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">--}}
                                    {{--<input class="form-control listen_to" id="date_stop" name="date_stop" type="text" value="{{date('Y-m-d')}}" >--}}
                                    {{--<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="confirmStatus">Klient</label>
                                <select name="clientInfo" id="clientInfo" class="form-control listen_to">
                                        <option value="0">Wszyscy</option>
                                    @foreach($allClients as $item)
                                        <option value='{{$item->id}}'>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="confirmStatus">Status hotelu</label>
                                <select name="confirmStatus" id="confirmStatus" class="form-control listen_to">
                                    <option value="-1">Wszystkie</option>
                                    <option value="0">Oczekuje na akceptacje</option>
                                    <option value="1">Zaakceptowano</option>
                                    <option value="2">Anulowano</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div>
                                <table id="datatable" class="thead-inverse table row-border table-striped "
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Nazwa</th>
                                        <th>Kontakt</th>
                                        <th>Klient</th>
                                        <th>Miasto</th>
                                        <th>Trasa</th>
                                        <th>Data pokazu</th>
                                        <th>Status</th>
                                        <th>Status</th>
                                        <th>Info</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="0" id="cityID"/>

    <!-- Modal -->
    <div id="hotelInfo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Informacje o hotelu</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel-name">Nazwa hotelu</label>
                                <input class="form-control" id="hotel-name" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel-voivode">Województwo</label>
                                <input class="form-control" id="hotel-voivode" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hotel-city">Miasto</label>
                                <input class="form-control" id="hotel-city" type="text" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hotel-street">Ulica</label>
                                <input class="form-control" id="hotel-street" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hotel-paid">Forma płatności</label>
                                <input class="form-control" id="hotel-paid" type="text" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="hotel-comment">Uwagi</label>
                                    <textarea class="form-control" id="hotel-comment" cols="15" rows="2" disabled></textarea>
                                    {{--<input class="form-control" id="hotel-comment" type="text" disabled>--}}
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6" >
                            <label for="telephones">Telefony</label>
                            <ul class="list-group" id="telephones">

                            </ul>
                        </div>
                        <div class="col-md-6" >
                            <label for="emails">E-maile</label>
                            <ul class="list-group" id="emails">

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('/js/jszip.min.js')}}"></script>
    <script src="{{ asset('/js/buttons.html5.min.js')}}"></script>
    <script>
        let voivodes = @json($voivodes);

        $('.form_date').datetimepicker({
            language: 'pl',
            autoclose: 1,
            minView: 2,
            pickTime: false
        });

    class SelectOption{
        constructor(id,name,cssValue){
            this.id = id;
            this.name = name;
            this.cssValue = cssValue;
        }
    }
    class SelectedValue{
        constructor(id,name){
            this.id = id;
            this.selectID = name;
        }
    }
    var selectedValueArray = new Array();
    var optionArray = [
        new SelectOption(0,'Oczekuje na akceptacje',"#fff7b9 !important"),
        new SelectOption(1,'Zaakceptowano',"#ebffd7 !important"),
        new SelectOption(2,'Anulowano',"#ffebe6  !important")];

        $(document).ready(function () {

            $('#date_start,#clientInfo,#confirmStatus').on('change',function () {
                table.ajax.reload();
            });
           var table = $('#datatable').DataTable({
               autoWidth: true,
               serverSide: true,
               processing: true,
               paging: false,
               dom: 'Bfrtip',
               order: [[ 2, "desc" ]],
               buttons: [{
                   extend: 'excelHtml5',
                   exportOptions: {
                       columns: [0,1,2,3,4,5,6]
                   }
               },
               ],
               ajax: {
                   url: "{{ route('api.getConfirmHotelInfo') }}",
                   type: "POST",
                   headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                   data: function (d){
                       d.dataStart = $('#date_start').val();
                       d.dataStop = $('#date_stop').val();
                       d.confirmStatus = $('#confirmStatus').val();
                       d.clientInfo = $('#clientInfo').val();
                   }
               },
               language: {
                   "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
               },
               createdRow: function(row, data, dataIndex ){
                   let inarray = false;
                   selectedValueArray.forEach(function (item) {
                       if(item.id == data.campainID){
                           item.selectID = data.confirmStatus;
                           inarray = true;
                       }
                   });
                   if(!inarray){
                       selectedValueArray.push(new SelectedValue(data.campainID,data.confirmStatus));
                   }

                   if(data.confirmStatus == 0)
                       $(row).addClass('toAccept');
                   if(data.confirmStatus == 1)
                       $(row).addClass('accepted');
                   if(data.confirmStatus == 2)
                       $(row).addClass('cancel');
                    $(row).attr('id',data.campainID);
               },
               fnDrawCallback: function(settings){
                   $('.statusConfirm').on('change',function (e) {
                       let closestTR =  $(this).closest('tr');
                       let campaignID = closestTR.attr('id');
                      let confirmStatus = $(this).val();
                           swal({
                               title: "Czy na pewno?",
                               type: "warning",
                               text: "Czy chcesz zmienić status hotelu ?",
                               showCancelButton: true,
                               confirmButtonClass: "btn-danger",
                               confirmButtonText: "Tak, zmień!",

                           }).then((result) => {
                               if (result.value) {
                                   $.ajax({
                                       type: 'POST',
                                       url: '{{ route('api.changeConfirmStatus') }}',
                                       data:{
                                           campaignID : campaignID,
                                           confirmStatus: confirmStatus
                                       },
                                       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                       success: function (response) {
                                           if(response == 200){
                                               closestTR.attr('style',"background-color:"+optionArray[confirmStatus].cssValue);
                                               closestTR.find('.hiddenColumn').text(optionArray[confirmStatus].name);
                                               table.ajax.reload();
                                               swal({
                                                   type: 'success',
                                                   title: 'Status został zmieniony',
                                                   showConfirmButton: false,
                                                   timer: 1500
                                               })
                                           }
                                           else{
                                               swal('Wystąpił Błąd')
                                           }
                                       }
                                   });
                               }else{
                                   selectedValueArray.forEach(function (item) {
                                      if(item.id == campaignID)
                                          closestTR.find('.statusConfirm').val(item.selectID);
                                   });
                               }
                           });
                   });
               },
               "columnDefs": [
                   { "visible": false, "targets": 6 }
               ],
               columns: [
                   {"data": "hotelName"},
                   {"data": "contact"},
                   {"data": "clientName"},
                   {"data": "cityName"},
                   {"data": "route_name"},
                   {"data": "eventDate"},
                   {
                       "data": function (data, type, dataToSet) {
                           return optionArray[data.confirmStatus].name;
                       },'className':'hiddenColumn'
                   },
                   {
                       "data": function (data, type, dataToSet) {
                           let select = document.createElement('select');
                           select.name = 'statusConfirm';
                           select.className = 'statusConfirm form-control';
                           let optionsStr = "";
                           let hotelID = data.hotelID;
                           let distabledAccept = false;
                           if(hotelID == null) distabledAccept = true;
                           optionArray.forEach(function (item) {
                                if(data.confirmStatus == item.id){
                                    optionsStr += '<option value ='+item.id+' selected>'+item.name+'</option>';
                                }
                                else{
                                    if(distabledAccept && item.id == 1)
                                        optionsStr += '<option value ='+item.id+' disabled>'+item.name+'</option>';
                                    else
                                        optionsStr += '<option value ='+item.id+'>'+item.name+'</option>';
                                }
                           });
                           select.innerHTML = optionsStr;
                           return select.outerHTML;
                       }, "orderable": false, "searchable": false,
                   },
                   {
                       "data": function (data, type, dataToSet) {
                           // console.log(data);
                           let spanButton = $(document.createElement('span')).addClass('glyphicon glyphicon-search');
                           let previewButton = $(document.createElement('button')).addClass('button-preview-hotel btn btn-default btn-block').attr('data-id', data.hotelID).append(spanButton);
                           return previewButton.prop('outerHTML');
                       },'name': 'info'
                   }
               ]
           });

            /**
             * This function fill inputs in modal with info about hotel
             * @param data
             */
           function fillHotelModal(data) {
               console.assert(Array.isArray(data), 'Parameter data is not array in fillHotelModal function');
               console.log(data);
               const generalHotelInfo = data[0];
               const contactHotelInfo = data[1];
               let hotelNameInput = document.querySelector('#hotel-name');
               let hotelVoivodeInput = document.querySelector('#hotel-voivode');
               let hotelCityInput = document.querySelector('#hotel-city');
               let hotelStreetInput = document.querySelector('#hotel-street');
               let hotelPaymentInput = document.querySelector('#hotel-paid');
               let hotelCommentInput = document.querySelector('#hotel-comment');

               let telephonesBox = document.querySelector('#telephones');
               let emailsBox = document.querySelector('#emails');

               if(generalHotelInfo.hasOwnProperty('hotel_name')) {
                   hotelNameInput.value = generalHotelInfo.hotel_name;
               }
               else {
                   hotelNameInput.value = 'Brak danych';
               }

               if(generalHotelInfo.hasOwnProperty('voivode_name')) {
                   hotelVoivodeInput.value = generalHotelInfo.voivode_name;
               }
               else {
                   hotelVoivodeInput.value = 'Brak danych';
               }

               if(generalHotelInfo.hasOwnProperty('city_name')) {
                   hotelCityInput.value = generalHotelInfo.city_name;
               }
               else {
                   hotelCityInput.value = 'Brak danych';
               }

               if(generalHotelInfo.hasOwnProperty('street')) {
                   hotelStreetInput.value = generalHotelInfo.street;
               }
               else {
                   hotelStreetInput.value = 'Brak danych';
               }

               if(generalHotelInfo.hasOwnProperty('payment_method_id')) {
                   if(generalHotelInfo.payment_method_id == 1) {
                       hotelPaymentInput.value = 'Gotówka';
                   }
                   else {
                       hotelPaymentInput.value = 'Przelew';
                   }

               }
               else {
                   hotelPaymentInput.value = "Brak danych";
               }

               if(generalHotelInfo.hasOwnProperty('comment')) {
                   hotelCommentInput.value = generalHotelInfo.comment;
               }
               else {
                   hotelCommentInput.value = 'Brak danych';
               }

               //**********Contact Part************

               contactHotelInfo.forEach(hotelInfo => {
                   let listElement = document.createElement('li');
                   listElement.classList.add('list-group-item');
                   let interiorElement = document.createElement('a');
                   interiorElement.textContent = hotelInfo.contact;

                   if(hotelInfo.type == 'mail') {
                       interiorElement.setAttribute('href', 'mailto:' + hotelInfo.contact);
                       if(hotelInfo.suggested == 1) {
                           interiorElement.textContent += ' - sugerowany';
                       }
                       listElement.appendChild(interiorElement);
                       emailsBox.appendChild(listElement);
                   }
                   else if(hotelInfo.type == 'phone') {
                       interiorElement.setAttribute('href', 'tel:' + hotelInfo.contact);
                       if(hotelInfo.suggested == 1) {
                           interiorElement.textContent += ' - sugerowany';
                       }
                       listElement.appendChild(interiorElement);
                       telephonesBox.appendChild(listElement);
                   }
               });
           }

           function globalClickHandler(e) {
               if(e.target.matches('.button-preview-hotel')) {

                   let clickedButton = e.target;
                   let hotelId = clickedButton.dataset.id;

                   const ourHeaders = new Headers();
                   ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                   let data = new FormData();
                   data.append('hotelId', hotelId);

                   swal({
                       title: 'Ładowanie...',
                       text: 'To może chwilę zająć',
                       showConfirmButton: false,
                       allowOutsideClick: false,
                       allowEscapeKey: false,
                       allowEnterKey: false,
                       onOpen: () => {
                           swal.showLoading();
                           fetch('{{route('api.hotelConfirmationHotelInfoAjax')}}', {
                               method: 'post',
                               headers: ourHeaders,
                               credentials: "same-origin",
                               body: data
                           })
                               .then(resp => resp.json())
                               .then(resp => {
                                   console.log(resp);
                                   return fillHotelModal(resp);
                               })
                               .then(resp => {
                                   swal.close();
                                   $("#hotelInfo").modal("show");
                               })
                               .catch(err => {console.log(err)})
                       }
                   });
               }
           }

           document.addEventListener('click', globalClickHandler);

        });

    </script>
@endsection
