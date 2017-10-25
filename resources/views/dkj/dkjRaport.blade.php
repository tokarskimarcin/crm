@extends('layouts.main')
@section('content')

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
            <h1 class="page-header">Raport DKJ</h1>
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
                                            <option>Wybierz</option>
                                            <option>-------Wysyłka-------</option>
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
                                                @endif
                                            @endforeach

                                            <option>-------Badania-------</option>
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
                                <table id="datatable">
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
                                                <th>Akcja</th>
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

<script>
    $(document).ready(function() {

        $('.form_date').datetimepicker({
            language:  'pl',
            autoclose: 1,
            minView : 2,
            pickTime: false,
        });

        table = $('#datatable').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "drawCallback": function( settings ) {
            },
            "ajax": {
                'url': "{{ route('api.datatableDkjRaport') }}",
                'type': 'POST',
                'data': function ( d ) {
                    d.start_date = $("input[name='start_date']").val();
                    d.stop_date = $("input[name='stop_date']").val();
                    d.department_id_info = $("select[name='department_id_info']").val();
                },
                'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },"columns":[
                {"data": "add_date"},
                {"data":function (data, type, dataToSet) {
                    return data.dkj_user_first_name + " " + data.dkj_user_last_name;
                },"name": " dkj_user.last_name"},
                {"data":function (data, type, dataToSet) {
                    return data.user_first_name + " " + data.user_last_name;
                },"name": "user.last_name"},
                {"data": "phone"},
                {"data": "campaign"},
                {"data": "comment"},
                {"data": "dkj_status"},
                {"data":function (data, type, dataToSet) {
                    return data.manager_status + " " + data.comment_manager;
                },"name": " dkj.comment_manager"},


            ]
        });

        });
</script>
@endsection
