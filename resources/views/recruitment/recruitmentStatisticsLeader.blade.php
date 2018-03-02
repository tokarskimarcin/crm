@extends('layouts.main')
@section('content')
<style>
    .myLabel {
        color: #aaa;
        font-size: 20px;
    }
    .myIcon {
        font-size: 550%;
        text-align: center;
    }
    .myUnderLine {
        font-size: 20px;
        color: #aaa;
    }
    .mySmallIcon {
        font-size: 350%;
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Statystyki pracowników HR</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Dane statystyk
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <label for ="ipadress">Data od:</label>
                            <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input  onchange="myFunction()"  id="start_date" class="form-control" name="od" type="text" value="{{date("Y-m-d")}}" readonly >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for ="ipadress">Data do:</label>
                            <div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                <input onchange="myFunction()" id="stop_date" class="form-control" name="do" type="text" value="{{date("Y-m-d")}}"readonly >

                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Status:</label>
                            <select class="form-control" id="attempt_by_status" onchange="myFunction()" >
                                <option value="1">Zakończone</option>
                                <option value="2">Aktywne</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <label>Pracownik HR</label>
                            <select class="form-control" id="hr_user" onchange="myFunction()">
                                <option value="0">Wszyscy</option>
                                @foreach($hr as $item)
                                    <option value="{{$item->id}}">{{$item->last_name . ' ' . $item->first_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Status rozmowy</label>
                            <select class="form-control" id="attempt_status" onchange="myFunction()">
                                <option value="0">Wszystkie</option>
                                @foreach($attemptStatus as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Wynik statusu</label>
                            <select class="form-control" id="attempt_result" onchange="myFunction()">
                                <option value="0">Wszystkie</option>
                                @foreach($attemptResult as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="candidates" class="table table-striped table-bordered thead-inverse" cellspacing="0" width="100%" >
                                <thead>
                                <tr>
                                    <td>Pracownik HR</td>
                                    <td>Kandydat</td>
                                    <td>Data</td>
                                    <td>Status</td>
                                    <td>Wynik Statusu</td>
                                    <td>Komentarz</td>
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
    <script>
        $(document).ready(function () {

        });
        $('.form_date').datetimepicker({
        language: 'pl',
        autoclose: 1,
        minView: 2,
        pickTime: false
        });

        var recruitment_statistics;

        function myFunction() {
            recruitment_statistics.ajax.reload();

        }

        recruitment_statistics = $('#candidates').DataTable({
            "order": [[0, "desc"]],
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            }, "ajax": {
                'url': "{{ route('api.datatableRecruitmentStatisticsLeader') }}",
                'type': 'POST',
                'data': function (d) {
                    d.hr_user = $('#hr_user').val();
                    d.attempt_status = $('#attempt_status').val();
                    d.attempt_result = $('#attempt_result').val();
                    d.start_date = $('#start_date').val();
                    d.stop_date = $('#stop_date').val();
                    d.attempt_by_status = $('#attempt_by_status').val();
                },
                'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            }, "columns": [
                {
                    "data": function (data, type, dataToSet) {
                        return data.cadre_last_name+' '+data.cadre_first_name;
                    },"name":"cadre_last_name"
                },
                {
                    "data": function (data, type, dataToSet) {
                        return data.candidate_last_name+' '+data.candidate_first_name;
                    },"name":"candidate_last_name"
                },
                {"data": "created_at"},
                {"data": "attempt_status_name"},
                {
                    "data": function (data, type, dataToSet) {
                        if(data.ar_name == null){
                            return data.attempt_result_name;
                        }else
                            return data.ar_name;
                    },"name":"attempt_result_name"
                },
                {"data": "comment"},
            ]
        });

    </script>
@endsection