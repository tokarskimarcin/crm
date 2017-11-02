@extends('layouts.main')
@section('content')
    <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.2.3/css/select.bootstrap.min.css" rel="stylesheet">
    <link href="https://editor.datatables.net/extensions/Editor/css/editor.bootstrap.min.css" rel="stylesheet">
    <style>
        .panel-heading a:after {
            font-family:'Glyphicons Halflings';
            content:"\e114";
            float: right;
            color: grey;
        }
        .panel-heading a.collapsed:after {
            content:"\e080";
        }


    </style>

    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Janki Weryfikacja</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default"  id="panel1">
                <div class="panel-heading">
                    <a data-toggle="collapse" data-target="#collapseOne">
                        Wybierz Oddział
                    </a>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div id="start_stop">
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <form action="" method="post" action="dkjRaport">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <label for="exampleInputPassword1" class="showhidetext">Wybierz Oddział</label>
                                            <select class="form-control showhidetext" name="department_id_info" style="border-radius: 0px;">
                                                <option value="0">Wybierz</option>
                                                <option value="0">-------Wysyłka-------</option>
                                                @foreach($departments as $department)
                                                    @if($department->type == 'Wysyłka')
                                                        @if(isset($select_department_id_info))
                                                            @if($select_department_id_info == $department->id)
                                                                <option selected value={{$department->id}}>{{$department->department_name.' '.$department->department_type_name}}</option>
                                                            @else
                                                                <option value={{$department->id}}>{{$department->department_name.' '.$department->department_type_name}}</option>
                                                            @endif
                                                        @else
                                                            <option value={{$department->id}}>{{$department->department_name.' '.$department->department_type_name}}</option>
                                                        @endif
                                                    @else


                                                        @if($department->type == 'Badania/Wysyłka')
                                                            @if(isset($select_department_id_info))
                                                                @if($select_department_id_info == $department->id)
                                                                    <option selected value={{$department->id}}>{{$department->department_name.' '.$department->department_type_name.' Wysyłka'}}</option>
                                                                @else
                                                                    <option value={{$department->id}}>{{$department->department_name.' '.$department->department_type_name.' Wysyłka'}}</option>
                                                                @endif
                                                            @else
                                                                <option value={{$department->id}}>{{$department->department_name.' '.$department->department_type_name.' Wysyłka'}}</option>
                                                            @endif
                                                        @endif


                                                    @endif
                                                @endforeach

                                                <option value="0">-------Badania-------</option>
                                                @foreach($departments as $department)
                                                    @if($department->type == 'Badania')
                                                        @if(isset($select_department_id_info))
                                                            @if($select_department_id_info == $department->id)
                                                                <option selected value={{$department->id}}>{{$department->department_name.' '.$department->department_type_name}}</option>
                                                            @else
                                                                <option value={{$department->id}}>{{$department->department_name.' '.$department->department_type_name}}</option>
                                                            @endif
                                                        @else
                                                            <option value={{$department->id}}>{{$department->department_name.' '.$department->department_type_name}}</option>
                                                        @endif
                                                    @else


                                                        @if($department->type == 'Badania/Wysyłka')
                                                            @if(isset($select_department_id_info))
                                                                @if($select_department_id_info == $department->id*(-1))
                                                                    <option selected value={{$department->id*(-1)}}>{{$department->department_name.' '.$department->department_type_name.' Badania'}}</option>
                                                                @else
                                                                    <option value={{$department->id*(-1)}}>{{$department->department_name.' '.$department->department_type_name.' Badania'}}</option>
                                                                @endif
                                                            @else
                                                                <option value={{$department->id*(-1)}}>{{$department->department_name.' '.$department->department_type_name.' Badania'}}</option>
                                                            @endif
                                                        @endif


                                                    @endif
                                                @endforeach

                                            </select>

                                            <label>Data od:<span style="color:red;">*</span></label>
                                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                @if(isset($select_start_date))
                                                    <input class="form-control" name="start_date" type="text" value="{{$select_start_date}}" readonly >
                                                @else
                                                    <input class="form-control" name="start_date" type="text" value="{{date("Y-m-d")}}" readonly >
                                                @endif
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                            </div>

                                            <label>Data do:<span style="color:red;">*</span></label>
                                            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                @if(isset($select_stop_date))
                                                    <input class="form-control" name="stop_date" type="text" value="{{$select_stop_date}}" readonly >
                                                @else
                                                    <input class="form-control" name="stop_date" type="text" value="{{date("Y-m-d")}}" readonly >
                                                @endif
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                            </div>

                                            <input type="submit" class="form-control showhidetext btn btn-primary" value="Wyświetl" style="
						border-radius: 0px;" name="showjanki">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($show_raport))
                <div class="panel panel-default"  id="panel2">
                    <div class="panel-heading">
                        Raport
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="start_stop">
                                    <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Dodał</th>
                                            <th>Imie Nazwisko</th>
                                            <th>Telefon</th>
                                            <th>Kampania</th>
                                            <th>Komentarz</th>
                                            <th>Jank</th>
                                            <th>Weryfikacja trenera</th>
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
            @endif
        </div>

        @endsection
        @section('script')
            <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.bootstrap.min.js"></script>
            <script src="https://cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>
            <script src="{{ asset('/js/dataTables.editor.min.js')}}"></script>
            <script src="{{ asset('/js/editor.bootstrap.min.js')}}"></script>
            <script>
                var editor;
                var tablica = (1,2,3,4);

                $(document).ready(function() {

                    var test= new Array({"label" : "a", "value" : "a"});
                    function getStateList(){
                        test.splice(0,1);
                        $.ajax({
                            type: "POST",
                            url: "{{ route('api.getUser') }}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            async: false,
                            dataType: 'json',
                            data: {'department_info' : $("select[name='department_id_info']").val()},
                            success: function (json) {
                                console.log(json);
                                if(json!=0)
                                {
                                    for(var a=0;a<json.length;a++){
                                        obj= { "label" : json[a]['first_name']+" "+json[a]['last_name'], "value" : json[a]['id']};
                                        test.push(obj);
                                    }
                                }

                            }
                        });
                        return test;
                    }


                    $('.form_date').datetimepicker({
                        language: 'pl',
                        autoclose: 1,
                        minView: 2,
                        pickTime: false,
                    });




                    editor = new $.fn.dataTable.Editor({
                        ajax: {
                            url: "{{ route('api.dkjRaportSave')}}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        },
                        table: "#datatable",
                        idSrc:  'id',
                        fields: [
                            {
                                label: "Użytkownik:",
                                name: "id_user",
                                type:  "select",
                                "ipOpts": getStateList()
                            },{
                                label: "Telefon:",
                                name: "phone"
                            }, {
                                label: "Kampania:",
                                name: "campaign"
                            }, {
                                label: "Komentarz:",
                                name: "comment"
                            }
                            ,{
                                label: "Janek:",
                                name:  "dkj_status",
                                type:  "select",
                                options: [
                                    { label: "Nie", value: "0" },
                                    { label: "Tak", value: "1" }
                                ]
                            },{
                                type:  "hidden",
                                name: "department_info_id",
                                def: Math.abs($("select[name='department_id_info']").val())
                            }
                        ]
                    });

                    table = $('#datatable').DataTable({
                        "autoWidth": false,
                        "processing": true,
                        "serverSide": true,
                        "drawCallback": function (settings) {
                        },
                        "ajax": {
                            'url': "{{ route('api.datatableDkjRaport') }}",
                            'type': 'POST',
                            'data': function (d) {
                                d.start_date = $("input[name='start_date']").val();
                                d.stop_date = $("input[name='stop_date']").val();
                                d.department_id_info = $("select[name='department_id_info']").val()
                                d.type_verification = 1;
                            },
                            'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                        }, "columns": [
                            {"data": "add_date"},
                            {
                                "data": function (data, type, dataToSet) {
                                    return data.dkj_user_first_name + " " + data.dkj_user_last_name;
                                }, "name": " dkj_user.last_name"
                            },
                            {
                                "data": function (data, type, dataToSet) {
                                    return data.user_first_name + " " + data.user_last_name;
                                }, "name": "user.last_name"
                            },
                            {"data": "phone"},
                            {"data": "campaign"},
                            {"data": "comment"},
                            {"data": function (data, type, dataToSet) {
                                if(data.dkj_status == 0)
                                    return 'Nie';
                                else return 'Tak'
                            }, "name": "dkj_status"},
                            {
                                "data": function (data, type, dataToSet) {
                                    return data.manager_status + " " + data.comment_manager;
                                }, "name": " dkj.comment_manager"
                            }
                        ],
                        select: true
                    });
                    // Display the buttons
                    new $.fn.dataTable.Buttons( table, [
                        { extend: "create", editor: editor },
                        { extend: "edit",   editor: editor },
                        { extend: "remove", editor: editor }
                    ] );

                    table.buttons().container()
                        .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
                });

            </script>
@endsection
