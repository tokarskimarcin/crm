@extends('layouts.main')
@section('style')
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">

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
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Godziny Kadra / Lista Obecności Kadry</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default"  id="panel1">
                <div class="panel-heading">
                    <a data-toggle="collapse" data-target="#collapseOne">
                        Zakres wyszukiwania:
                    </a>
                </div>
                <div class="panel-body">
                    <div class="alert alert-info">
                        <ul class="list-group">
                            <li class="list-group-item">Osoby podświetlone na <span style="background-color: #83e05c;">zielono</span> nacisneły "rozpoczynam pracę" w czasie przewidzianym w grafiku.</li>
                            <li class="list-group-item">Osoby podświetlone na <span style="background-color: #ffd932;">pomarańczowo</span> nacisneły "rozpoczynam pracę" po czasie przewidzianym w grafiku.</li>
                            <li class="list-group-item">Osoby podświetlone na <span style="background-color: #ff514e;">czerwono</span> nie nacisneły "rozpoczynam pracę" w czasie przewidzianym w grafiku.</li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
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

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="checkbox" id="onlyEngraved" style="display: inline-block; margin-right: 0.5em;">
                                <label for="onlyEngraved">Pokaż tylko zagrafikowanych</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <input type="checkbox" id="leaders" style="display: inline-block; margin-right: 0.5em;">
                                <label for="leaders">Pokaż tylko liderów zmiany</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default"  id="panel2">
                <div class="panel-heading">
                    Lista Obecności
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-md-12 table-responsive">
                                <table id="datatable"  class="table thead-inverse table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Imie</th>
                                        <th>Nazwisko</th>
                                        <th>Start</th>
                                        <th>Zarejestrowane</th>
                                        <th>wg Grafiku</th>
                                        <th>Rola</th>
                                        <th>Suma</th>
                                        <th>Grafik</th>
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
@endsection

@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>

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

            let userTypes = @json($userTypes);
            let engravedCheckboxElement = document.querySelector('#onlyEngraved');
            let leadersCheckboxElement = document.querySelector('#leaders');

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
                "rowCallback": function( row, data, index ) {
                    if(data.leader == 1) {
                        $(row).css('font-weight', 'bold');
                    }

                    if(data.onTime == 2){ // na czas w pracy
                        $(row).css('background-color', '#83e05c');
                    }
                    else if(data.onTime == 3){ // spóźniony do pracy
                        $(row).css('background-color', 'rgb(255, 217, 50)');
                    }else if(data.onTime == 1){
                        $(row).css('background-color', '#ff514e');
                    }
                },
                "ajax": {
                    'url': "{{ route('api.checkList') }}",
                    'type': 'POST',
                    'data': function ( d ) {
                        d.start_date = $('#start_date').val();
                        d.dep_info =$("select[name='department_id_info']").val();
                        d.engraved = engravedCheckboxElement.checked;
                        d.leaders = leadersCheckboxElement.checked;
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
                    },"name": "click_start"},

                    {"data": function (data, type, dataToSet) {
                        if(data.register_start == null)
                            data.register_start = "Brak infromacji";
                        if(data.register_stop == null)
                            data.register_stop = "Brak infromacji";
                        return data.register_start + "</br><span class='fa fa-arrow-circle-o-down fa-fw'></span> </br> " + data.register_stop;
                    },"name": "register_start"},
                    {"data": function (data, type, dataToSet) {
                            if(data.scheduleToDayStart == null)
                                data.scheduleToDayStart = "Brak infromacji";
                            if(data.scheduleToDayStop == null)
                                data.scheduleToDayStop = "Brak infromacji";
                            data.freeDay == 1 ?  data.scheduleToDayStart = data.scheduleToDayStop = "Wolne" : 0;
                            return data.scheduleToDayStart + "</br><span class='fa fa-arrow-circle-o-down fa-fw'></span> </br> " + data.scheduleToDayStop;
                        },"name": "scheduleToDayStart"},
                    {"data": function (data, type, dataToSet) {
                            let name = "Brak danych";
                            userTypes.forEach(function(item) {
                                if(item.id == data.user_type_id) {
                                    name = item.name;
                                }
                            });
                            return name;
                    },"name": "user_type_id", "searchable": false},
                    {"data":function (data, type, dataToSet) {
                        if(data.time == null)
                            data.time = "Brak infromacji";
                        return data.time;
                    }, "name": "time","searchable": false },
                    {"data":function (data, type, dataToSet) {
                            let hasEngraver = null;
                            if(data.hasShedule != null) {
                                hasEngraver = 'Tak';
                            }
                            else {
                                hasEngraver = 'Nie';
                            }
                            return hasEngraver;
                    }, "name": "grafik","searchable": false,"visible": false },
                ],
            });

            function checkboxChangeHandler(e) {
                table.ajax.reload();
            }

            engravedCheckboxElement.addEventListener('change', checkboxChangeHandler);
            leadersCheckboxElement.addEventListener('change', checkboxChangeHandler);

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
                    swal("Brak wszystkich godzin w modyfikacji")
                    validate = 0;
                }else if(modify_start >= modify_stop)
                {
                    swal("Godziny są ustawione niepoprawnie")
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
                            swal("Brak zarejestrowanych godzin")
                        }else
                            table.ajax.reload();
                    }
                });
            }
        });
    </script>

@endsection
