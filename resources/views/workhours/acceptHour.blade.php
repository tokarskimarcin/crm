@extends('layouts.main')
@section('style')
    <style>
        td{
            text-align: center;
        }

        #register_stop
        {
            float: left;
            width: 75px;
            height: 28px;
            margin-top: 3px;
        }
        #register_start
        {
            width: 75px;}

    </style>
@endsection
@section('content')


    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Akceptacja Godzin</h1>
        </div>
    </div>

    <div class="col-lg-3">
        <label for ="ipadress">Zakres wyszukiwania:</label>
        <div class="form-group">
            <label for ="ipadress">Od:</label>
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                <input  onchange="myFunction()"  id="start_date" class="form-control" name="od" type="text" value="{{date("Y-m-d")}}" readonly >

                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
        </div>
        <div class="form-group">
            <label for ="ipadress">Do:</label>
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                <input onchange="myFunction()" id="stop_date" class="form-control" name="do" type="text" value="{{date("Y-m-d")}}"readonly >

                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
        </div>
    </div>
    <table id="datatable">
        <thead>
        <tr>
            <th>Data</th>
            <th>Osoba</th>
            <th>Start</th>
            <th>Zarejestrowane</th>
            <th>Modyfikacja</th>
            <th>Suma</th>
            <th>Akcja</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="row">
        <div class="col-lg-12">
            </br> <span class="fa fa-user fa-fw"></span> </br>
        </div>
    </div>


@endsection

@section('script')

    <script>
        var table;


        function myFunction() {
            table.ajax.reload();
        }
        $(function() {
            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false,
            });
                $('.form_time').datetimepicker({
                    language:  'pl',
                    weekStart: 1,
                    todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 1,
                    minView: 0,
                    maxView: 1,
                    forceParse: 0
                });
        });

        $(document).ready( function () {
            table = $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': "{{ route('api.acceptHour') }}",
                    'type': 'POST',
                    'data': function ( d ) {
                        d.start_date = $('#start_date').val();
                        d.stop_date = $('#stop_date').val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },
                "columns":[
                    {"data": "date"},

                    {"data":function (data, type, dataToSet) {
                        return data.first_name + " " + data.last_name;
                    },"name": "users.last_name"},

                    {"data": function (data, type, dataToSet) {
                        if(data.click_start == null)
                            data.click_start = "Brak infromacji";
                        if(data.click_stop == null)
                            data.click_stop = "Brak infromacji";
                        return data.click_start + "</br><span class='fa fa-arrow-circle-o-down fa-fw'></span> </br> " + data.click_stop;
                    },"name": "click_start" },

                    {"data": function (data, type, dataToSet) {
                        if(data.register_start == null)
                            data.register_start = "Brak infromacji";
                        if(data.register_stop == null)
                            data.register_stop = "Brak infromacji";
                        return data.register_start + "</br><span class='fa fa-arrow-circle-o-down fa-fw'></span> </br> " + data.register_stop;
                    },"name": "register_start"},

                    {"data":null,"targets": -3,"orderable": false, "searchable": false },

                    {"data": "time", "name": "time","searchable": false },

                    {"data":null,"targets": -1,"orderable": false, "searchable": false }

                ],
                "columnDefs": [ {
                    "targets": -1,
                    "data": null,
                    "defaultContent": "<button id='zapis'>Zapisz</button>"
                },
                    {
                        "targets": -3,
                        "data": null,
                        "defaultContent": "" +
                        "<div class='form-group'>" +
                        "<div class='input-group date form_time col-md-5' data-date='' data-date-format=hh:ii' data-link-field='dtp_input3' data-link-format='hh:ii'>"+
                        "<input id='register_start' class='form-control' size='16' type='text' value='' readonly>"+
                        "<span class='input-group-addon'><span class='glyphicon glyphicon-remove'></span></span>"+
                        "<span class='input-group-addon'><span class='glyphicon glyphicon-time'></span></span>"+
                        "</div>"+
                        "<input type='hidden' id='dtp_input3' value='' /><br/>"+
                        "</div>"
                    }]
            });
            $('#datatable tbody').on( 'click', '#register_start', function () {
                $("#success-alert").alert();
                $('.form_time').datetimepicker({
                    language:  'pl',
                    weekStart: 1,
                    todayBtn:  1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 1,
                    minView: 0,
                    maxView: 1,
                    forceParse: 0
                }).focus();
            });

        });




    </script>















@endsection
