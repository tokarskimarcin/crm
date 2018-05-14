@extends('layouts.main')
@section('content')
    {{--*******************************************--}}
    {{--THIS PAGE DISPLAYS TABLE WITH FILLED AUDITS--}}
    {{--*******************************************--}}

    {{--<style>--}}
        {{--td:nth-of-type(5)::after {--}}
            {{--content: '%';--}}
        {{--}--}}
    {{--</style>--}}


    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <div class="alert gray-nav">Tabela wykonanych audytów</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Lista wykonanych audytów
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-info">Filtrując tabelę po oddziałach można wybrać bezpośrednio oddział lub dyrektora, jeżeli zostanie wybrany dyrektor, zostaną wyfiltrowane rekordy z oddziałami mu podległymi</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Zakres od:</label>
                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                        <input class="form-control listen_to" id="date_start" name="date_start" type="text" value="{{date('Y-m-01')}}" >
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                    </div>
                                </div>
                                <script>

                                    let date_st = document.getElementById('date_start');
                                    let storageVal1 = sessionStorage.getItem('date_start');
                                    if(sessionStorage.getItem('date_start') != undefined || sessionStorage.getItem('date_start') != null) {
                                        date_st.value = storageVal1;
                                    }
                                </script>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="myLabel">Zakres do:</label>
                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                        <input class="form-control listen_to" id="date_stop" name="date_stop" type="text" value="{{date('Y-m-d')}}" >
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                    </div>
                                </div>
                                <script>
                                    let date_sto = document.getElementById('date_stop');
                                    let storageVal2 = sessionStorage.getItem('date_stop');
                                    if(sessionStorage.getItem('date_stop') != undefined || sessionStorage.getItem('date_stop') != null) {
                                        date_sto.value = storageVal2;
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="row row_to_insert">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department">Oddział</label>
                                    <select name="department" id="department" class="form-control listen_to">
                                        <option value="0">Wybierz</option>
                                        <optgroup label="departamenty">
                                        @foreach($departments as $department)
                                        <option value="{{$department->id}}" data-type="1" class="all_departments">{{$department->departments->name}} {{$department->department_type->name}}</option>
                                        @endforeach
                                        </optgroup>
                                        <optgroup label="dyrektorzy">
                                            @foreach($directors as $director)
                                                <option value="{{$director->id * 100}}" class="all_departments" data-type="2">{{$director->first_name}} {{$director->last_name}}</option>
                                            @endforeach
                                        </optgroup>
                                        <script>
                                            let deps = document.getElementsByClassName('all_departments');
                                            let storageVal3 = sessionStorage.getItem('departmentValue');
                                            // if(storageVal3 > 100) {
                                            //     storageVal3 /= 100;
                                            // }
                                            for(var i = 0; i < deps.length; i++) {
                                                if(deps[i].value == storageVal3) {
                                                    deps[i].selected = true;
                                                }
                                            }
                                            // sessionStorage.removeItem('departmentValue');
                                        </script>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Typ</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="0">Wybierz</option>
                                        <option value="1">Trener</option>
                                        <option value="2">Hr-owiec</option>
                                        <option value="3">Oddział</option>
                                    </select>
                                </div>
                                <script>
                                    let type = document.getElementById('type');
                                    let typeValue = sessionStorage.getItem('type');
                                    if(sessionStorage.getItem('type') != undefined || sessionStorage.getItem('type') != null) {
                                        type.selectedIndex = typeValue;
                                    }
                                </script>
                            </div>

                        </div>
                        <div class="alert alert-info insertDiv"></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="start_stop">
                                    <div class="panel-body table-responsive">
                                        @if(Session::has('adnotation'))
                                                    <div class="alert alert-success">{{Session::get('adnotation') }}</div>
                                            @php
                                                Session::forget('adnotation');
                                            @endphp
                                        @endif
                                        <table id="datatable" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th class="search-input-text" data-column="1">Wypełniającey</th>
                                                <th>Oceniany</th>
                                                <th>Typ</th>
                                                <th>Department</th>
                                                <th>Data</th>
                                                <th class="score">Wynik</th>
                                                <th>Podgląd/Edycja</th>
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
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(document).ready( function () {
            var selectedDepartment = document.getElementById('department');
            var selectedStartDate = document.getElementById('date_start');
            var selectedStopDate = document.getElementById('date_stop');
            var selectedType = document.getElementById('type');
            var departmentValue = null;
            var directorId = null;
            var type = null;

            //ajax reponsible for receiving and displaying data through datatable
            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {
                },
                "ajax": {
                    'url': "{{ route('api.auditTable') }}",
                    'type': 'POST',
                    'data': function (d) {
                        d.date_start = $('#date_start').val();
                        d.date_stop =  $('#date_stop').val();
                        d.department = departmentValue;
                        d.director = directorId;
                        d.type = type;
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"columns":[
                    {"data":function (data, type, dataToSet) {
                            return data.user_first_name+' '+data.user_last_name;
                        },"name":"users.last_name", "orderable": false
                    },
                    {"data":function (data, type, dataToSet) {
                            if(data.user_type != 3) {
                                return data.trainer_first_name+' '+data.trainer_last_name;
                            }
                            else {
                                return data.department_name + ' ' + data.department_type;
                            }
                        },"name":"trainer.last_name","orderable": false
                    },
                    {"data":function (data, type, dataToSet) {
                        if(data.user_type == 1) {
                            return "Trener";
                        }
                        else if(data.user_type == 2) {
                            return "Hr";
                        }
                        else {
                            return 'Oddział';
                        }
                        },"name":"users.last_name","orderable": false
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.department_name+' '+data.department_type;
                        },"name":"departments.name","orderable": false
                    },
                    {"data": function (data, type, dataToSet) {
                        return data.date_audit;
                        },"orderable": false},
                    // {"data": "date_audit"},
                    {"data":function (data, type, dataToSet) {
                        if(data.score != null && data.score != 'null' )
                            return data.score + ' ' + '%';
                        else return "0 %"
                        },"name":"score","orderable": false
                    },
                    {"data":function (data, type, dataToSet) {
                            return '<a href="{{URL::to("audit")}}/' + data.audit_id + '" class="links">Link</a>';
                        },"orderable": false, "searchable": false
                    }
                ]
            });

            $('.search-input-text').on( 'change', function () {   // for text boxes
                var i =$(this).attr('data-column');  // getting column index
                var v = $(this).text();  // getting search input value
                table.columns(i).search(v).draw();
            } );

            /**
             * This event listener filter table by selected date
             */
            $('#date_start, #date_stop, #department, #type').on('change',function(e) {
                if(selectedDepartment.options[selectedDepartment.selectedIndex].dataset.type == 1) {
                    departmentValue = selectedDepartment.options[selectedDepartment.selectedIndex].value;
                }
                else {
                    departmentValue = 0;
                }
                if(selectedDepartment.options[selectedDepartment.selectedIndex].dataset.type == 2) {
                    directorId = selectedDepartment.options[selectedDepartment.selectedIndex].value;
                }
                else {
                    directorId = 0;
                }

                if(selectedType.options[selectedType.selectedIndex].value != 0) {
                    type = selectedType.options[selectedType.selectedIndex].value;
                }

                table.ajax.reload();
                document.getElementsByClassName('insertDiv')[0].textContent = '';
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.scores') }}',
                    data: {
                        "departmentValue": selectedDepartment.options[selectedDepartment.selectedIndex].value,
                        "date_start": document.getElementById('date_start').value,
                        "date_stop": document.getElementById('date_stop').value
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let average = response[0].number_of_records > 0 ? Math.round(response[0].total_score / response[0].number_of_records *100) / 100 : null;
                        if(average != null) {
                            $('.insertDiv').append('<p>Średnia ze wszystkich audytów(bez podziału na typ):  ' + average + '%</p>');
                        }
                    },
                    error: function(jqxhr, status, exception) {
                        console.log('Exception:', exception);
                    }
                });

                departmentValue = null;
                directorId = null;
                type = null;
            });

            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false
            });
            {{--let inputs_to_listen = Array.from(document.getElementsByClassName('listen_to'));--}}

            {{--inputs_to_listen.forEach(function(input) {--}}
                {{--input.addEventListener('change', function(e) {--}}
                    {{--document.getElementsByClassName('insertDiv')[0].textContent = '';--}}
                    {{--$.ajax({--}}
                        {{--type: "POST",--}}
                        {{--url: '{{ route('api.scores') }}',--}}
                        {{--data: {--}}
                            {{--"departmentValue": selectedDepartment.options[selectedDepartment.selectedIndex].value,--}}
                            {{--"date_start": document.getElementById('date_start').value,--}}
                            {{--"date_stop": document.getElementById('date_stop').value--}}
                        {{--},--}}
                        {{--headers: {--}}
                            {{--'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
                        {{--},--}}
                        {{--success: function(response) {--}}
                            {{--let average = response[0].number_of_records > 0 ? Math.round(response[0].total_score / response[0].number_of_records *100) / 100 : null;--}}
                            {{--if(average != null) {--}}
                                {{--$('.insertDiv').append('<p>Średnia z audytów:  ' + average + '%</p>');--}}
                            {{--}--}}
                        {{--},--}}
                        {{--error: function(jqxhr, status, exception) {--}}
                            {{--console.log('Exception:', exception);--}}
                        {{--}--}}
                    {{--});--}}
                {{--});--}}
            {{--});--}}

            $( document ).ajaxComplete(function() {
                let links = Array.from(document.getElementsByClassName('links'));
                links.forEach(function(link) {
                    link.addEventListener('click', function(event) {
                        sessionStorage.setItem('date_start', document.getElementById('date_start').value);
                        sessionStorage.setItem('date_stop', document.getElementById('date_stop').value);
                        sessionStorage.setItem('departmentValue', selectedDepartment.options[selectedDepartment.selectedIndex].value);
                        sessionStorage.setItem('type', document.getElementById('type').value);
                    });
                });
            });


            //session part - setting variable values for ajax and removing session
            let storageVal2 = sessionStorage.getItem('date_stop');
            let storageVal1 = sessionStorage.getItem('date_start');
            let storageVal3 = sessionStorage.getItem('departmentValue');
            let storageVal4 = sessionStorage.getItem('type');
            if(storageVal3 != null) {
                if(selectedDepartment.options[selectedDepartment.selectedIndex].value < 100) {
                    departmentValue = selectedDepartment.options[selectedDepartment.selectedIndex].value;
                }
                else {
                    directorId = selectedDepartment.options[selectedDepartment.selectedIndex].value;
                }
                sessionStorage.removeItem('departmentValue');
                table.ajax.reload();
            }
            if(storageVal4 != null) {
                type = selectedType.options[selectedType.selectedIndex].value;
                sessionStorage.removeItem('type');
                table.ajax.reload();
            }

            if(storageVal2 != null) {
                table.ajax.reload();
                sessionStorage.removeItem('date_stop');
            }

            if(storageVal1 != null) {
                table.ajax.reload();
                sessionStorage.removeItem('date_start');
            }

     });
    </script>
@endsection
