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
        .modifydate{
            margin-bottom: 0px;
            height: 41px;
        }

    </style>
@endsection
@section('content')


    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Lista Obecności Kadry</h1>
        </div>
    </div>

    <div class="col-lg-3">
        <label for ="ipadress">Zakres wyszukiwania:</label>
        <div class="form-group">
            <label for ="ipadress">Dzień:</label>
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                <input  onchange="myFunction()"  id="start_date" class="form-control" name="od" type="text" value="{{date("Y-m-d")}}" readonly >

                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
        </div>
        <div class="form-group">
            <select onchange="myFunction()" class="form-control showhidetext" name="department_id_info" style="border-radius: 0px;">
                <option value="*" selected>Wszystkie oddziały</option>
                @foreach($departments as $department)
                    <option value={{$department->id}}>{{$department->department_name.' '.$department->department_type_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <table id="datatable">
        <thead>
        <tr>
            <th>Data</th>
            <th>Imie</th>
            <th>Nazwisko</th>
            <th>Start</th>
            <th>Zarejestrowane</th>
            <th>Suma</th>
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

            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false,
            });

        $(document).ready( function () {

            table = $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {

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

                },
                "ajax": {
                    'url': "{{ route('api.checkList') }}",
                    'type': 'POST',
                    'data': function ( d ) {
                        d.start_date = $('#start_date').val();
                        d.dep_info =$("select[name='department_id_info']").val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },
                "columns":[
                    {"data": "start_date",searchable: false},
                    {"data":"first_name"},
                    {"data":"last_name"},
                    {"data": function (data, type, dataToSet) {
                        if(data.click_start == null)
                            data.click_start = "Brak infromacji";
                        if(data.click_stop == null)
                            data.click_stop = "Brak infromacji";
                        return data.click_start + "</br><span class='fa fa-arrow-circle-o-down fa-fw'></span> </br> " + data.click_stop;
                    },"name": "work_hours.click_start"},

                    {"data": function (data, type, dataToSet) {
                        if(data.register_start == null)
                            data.register_start = "Brak infromacji";
                        if(data.register_stop == null)
                            data.register_stop = "Brak infromacji";
                        return data.register_start + "</br><span class='fa fa-arrow-circle-o-down fa-fw'></span> </br> " + data.register_stop;
                    },"name": "work_hours.register_start"},
                    {"data":function (data, type, dataToSet) {
                        if(data.time == null)
                            data.time = "Brak infromacji";
                        return data.time;
                    }, "name": "time","searchable": false },

                ],

            });
        });

        $('#datatable tbody').on('click', '.button-save', function () {
            var data = table.row( $(this).parents('tr') ).data();
            var modify_start = $(this).closest("tr").find("input[id='register_start']").val();
            var modify_stop = $(this).closest("tr").find("input[id='register_stop']").val();
            var succes = 0;
            var id = data.id;
            var type_edit = 0;
            var validate = 1;
            if(modify_start !='' || modify_stop !='')
            {
                if(modify_start == '' || modify_stop == '')
                {
                    alert("Brak wszystkich godzin w modyfikacji");
                    validate = 0;
                }else if(modify_start > modify_stop)
                {
                    alert("Godziny są ustawione niepoprawnie");
                    validate = 0;
                }else
                    type_edit = 1;
            }
            if(validate == 1)
            {
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.saveAcceptHour') }}',
                    data: {
                        "id": id,
                        "register_start": modify_start,
                        "register_stop": modify_stop,
                        "type_edit":type_edit,
                        "succes":succes
                    },

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response == '-1')
                        {
                            alert("Brak zarejestrowanych godzin");
                        }else
                            table.ajax.reload();
                    }
                });
            }


        });
    </script>















@endsection
