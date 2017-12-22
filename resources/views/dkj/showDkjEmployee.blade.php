@extends('layouts.main')
@section('content')
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/select.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/css/editor.bootstrap.min.css')}}" rel="stylesheet">
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
        .lenght{
            height: 40px;
        }
        .btn.btn-primary[disabled] {
              background-color: #000;
          }


    </style>

    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Podgląd pracowników DKJ</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default"  id="panel1">
                <div class="panel-heading">
                    <a data-toggle="collapse" data-target="#collapseOne">
                        Wybierz Pracownika
                    </a>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div id="start_stop">
                                <div id="collapseOne" class="panel-collapse collapse in">
                                 <div class="panel-body">
                                    <form action="" method="post" action="showDkjEmployee">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <label for="exampleInputPassword1" class="showhidetext">Wybierz Pracownika</label>
                                        <select id="select_form" class="form-control showhidetext" name="user_dkj_id" style="border-radius: 0px;">
                                                @foreach($dkjEmployee as $item)
                                                    @if(isset($employee_id) && $employee_id == $item->id)
                                                    <option selected value={{$item->id}}>{{$item->last_name.' '.$item->first_name}}</option>
                                                    @else
                                                    <option value={{$item->id}}>{{$item->last_name.' '.$item->first_name}}</option>
                                                    @endif;
                                                @endforeach
                                        </select>
                                        <label>Typ rozmowy</label>
                                        <div class="radio">
                                            <label><input type="radio" name="janky_status" value="0" checked='checked' >Wyszystkie</label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="janky_status" value="1" @if (isset($janky_status) &&  $janky_status == 1) checked='checked' @endif>Janki</label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="janky_status" value="2" @if (isset($janky_status) &&  $janky_status == 2) checked='checked' @endif>Janki podważone </label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="janky_status" value="3" @if (isset($janky_status) &&  $janky_status == 3) checked='checked' @endif>Janki usunięte</label>
                                        </div>

                                        <label>Data od:<span style="color:red;">*</span></label>
                                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                            @if(isset($old_start_date))
                                                <input class="form-control" name="start_date" type="text" value="{{$old_start_date}}" readonly >
                                            @else
                                                <input class="form-control" name="start_date" type="text" value="{{date("Y-m-d")}}" readonly >
                                            @endif
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>

                                        <label>Data do:<span style="color:red;">*</span></label>
                                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                            @if(isset($old_stop_date))
                                                <input class="form-control" name="stop_date" type="text" value="{{$old_stop_date}}" readonly >
                                            @else
                                                <input class="form-control" name="stop_date" type="text" value="{{date("Y-m-d")}}" readonly >
                                            @endif
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                        </div>
                                        <br />
                                        <input id="show_employee" class="form-control showhidetext btn btn-primary" value="Wyświetl" style="border-radius: 0px;">
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default"  id="panel2">
                <div class="panel-heading">
                    Raport
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop" class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Imie i Nazwisko</th>
                                                <th>Telefon</th>
                                                <th>Kampania</th>
                                                <th>Komentarz</th>
                                                <th>Janek</th>
                                                <th>Weryfikacja trenera</th>
                                                <th>Oddział</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        {{--@php($lp = 1)--}}
                                        {{--@foreach($employee_info as $item)--}}
                                            {{--<tr>--}}
                                                {{--<td>{{$lp++}}</td>--}}
                                                {{--<td>{{$item->add_date}}</td>--}}
                                                {{--<td>{{$item->dkj_user->first_name.' '.$item->dkj_user->last_name}}</td>--}}
                                                {{--<td>{{$item->phone}}</td>--}}
                                                {{--<td style=" word-wrap: break-word; max-width: 200px" >{{$item->campaign}}</td>--}}
                                                {{--<td style=" word-wrap: break-word; max-width: 100px">{{$item->comment}}</td>--}}
                                                {{--<td>{{ $item->dkj_status == 1 ? "Tak" : "Nie" }}</td>--}}
                                                {{--@if($item->manager_status == null)--}}
                                                    {{--<td>Brak</td>--}}
                                                {{--@else--}}
                                                    {{--<td>{{ $item->manager_status == 0 ? "Tak" : "Nie" }}</td>--}}
                                                {{--@endif--}}
                                                {{--<td>{{ $item->user->department_info->departments->name}} {{ $item->user->dating_type == 1 ? "Wysyłka" : "Badania" }}</td>--}}
                                            {{--</tr>--}}
                                        {{--@endforeach--}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection
@section('script')
            <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
            <script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
            <script src="{{ asset('/js/buttons.bootstrap.min.js')}}"></script>
            <script src="{{ asset('/js/dataTables.select.min.js')}}"></script>
<script>
    $('.form_date').datetimepicker({
        language: 'pl',
        autoclose: 1,
        minView: 2,
        pickTime: false,
    });

    table = $('#datatable').DataTable({
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
        },
        "drawCallback": function (settings) {
        },
        "ajax": {
            'url': "{{ route('api.datatableDkjShowEmployee') }}",
            'type': 'POST',
            'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            'data': function (d) {
                d.start_date = $("input[name='start_date']").val();
                d.stop_date = $("input[name='stop_date']").val();
                d.user_dkj_id =$("select[name='user_dkj_id']").val();
                d.janky_status = $("input[name='janky_status']:checked").val();
            }
        }, "columns": [

            {"data": "add_date"},
            {
                "data": function (data, type, dataToSet) {
                    return data.first_name + " " + data.last_name;
                }, "name": "users.last_name"
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
                    if(data.manager_status == null)
                        return '<b>Brak</b>';
                    else
                        var text_response = (data.manager_status == 0) ? "<b>Tak</b>" : "<b>Nie</b>" ;
                    var comment = (data.comment_manager != null) ? data.comment_manager : "Brak Komentarza" ;
                    return text_response + " " + comment;
                }, "name": "comment_manager"
            },
            {
                "data": function (data, type, dataToSet) {
                    return data.name + " " + data.type_name;
                }, "name": "departments.name"
            },
        ],

    });





    $("#show_employee").click(function () {
        table.ajax.reload();

    });

</script>
@endsection
