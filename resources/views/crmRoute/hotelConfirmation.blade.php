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
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('/js/jszip.min.js')}}"></script>
    <script src="{{ asset('/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('/js/modules/hotelInfo.blade.js')}}"></script>
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


            let tu = document.querySelector('.tu');
            console.log(tu);
            let myHotelInfo = new CreateHotelInfo('tu', 1);
            myHotelInfo.createDOMContent();
            let myEl = myHotelInfo.getDOMElement();
            tu.appendChild(myEl);



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
               ]
           });
        });

    </script>
@endsection
