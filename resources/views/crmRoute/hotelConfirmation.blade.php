@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
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
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Zakres od:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control listen_to" id="date_start" name="date_start" type="text" value="{{date('Y-m-d')}}" >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="myLabel">Zakres do:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                    <input class="form-control listen_to" id="date_stop" name="date_stop" type="text" value="{{date('Y-m-d')}}" >
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="confirmStatus">Status hotelu</label>
                                <select name="confirmStatus" id="confirmStatus" class="form-control listen_to">
                                    <option value="0">Wszystkie</option>
                                    <option value="1">Oczekujące na Akceptacje</option>
                                    <option value="2">Zaakceptowane</option>
                                    <option value="3">Anulowane</option>
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
                                        <th>Trasa</th>
                                        <th>Data pokazu</th>
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
    <script>

    class SelectOption{
        constructor(id,name,cssValue){
            this.id = id;
            this.name = name;
            this.cssValue = cssValue;
        }
    }
    var optionArray = [
        new SelectOption(0,'Oczekuje na akceptacje',"#fff7b9 !important"),
        new SelectOption(1,'Zaakceptowanio',"#ebffd7 !important"),
        new SelectOption(2,'Anulowano',"#ffebe6  !important")];

        $(document).ready(function () {
           var table = $('#datatable').DataTable({
               autoWidth: true,
               serverSide: true,
               processing: true,
               ajax: {
                   url: "{{ route('api.getConfirmHotelInfo') }}",
                   type: "POST",
                   headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                   data: function (d){
                       d.dataStart = 0;
                       d.dataStop = 0;
                       d.confirmStatus = 0;
                   }
               },
               language: {
                   "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
               },
               createdRow: function(row, data, dataIndex ){
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
                               }
                           });
                   });
               },
               columns: [
                   {"data": "hotelName"},
                   {"data": "contact"},
                   {"data": "clientName"},
                   {"data": "cityName"},
                   {"data": "eventDate"},
                   {
                       "data": function (data, type, dataToSet) {
                           let select = document.createElement('select');
                           select.name = 'statusConfirm';
                           select.className = 'statusConfirm form-control';
                           let optionsStr = "";
                           optionArray.forEach(function (item) {
                                if(data.confirmStatus == item.id){
                                    optionsStr += '<option value ='+item.id+' selected>'+item.name+'</option>';
                                }
                                else{
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
