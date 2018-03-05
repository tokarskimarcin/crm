@extends('layouts.main')
@section('content')

<style>
    .myLabel {
        font-size: 20px;
        color: #aaa;
    }
    .myButton {
        width: 100%;
        margin-top: 33px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Rekrutacja / Raport przeprowadzonych szkoleń</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="myLabel">Zakres od:</label>
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                <input onchange="refreash()" class="form-control" id="date_start" name="date_start" type="text" value="{{date('Y-m-d')}}" >
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="myLabel">Zakres do:</label>
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                <input onchange="refreash()" class="form-control" id="date_stop" name="date_stop" type="text" value="{{date('Y-m-d')}}" >
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped thead-inverse" id="table">
                <thead>
                    <tr>
                        <th>Oddział</th>
                        <th>Suma umówionych</th>
                        <th>Suma obecnych</th>
                        <th>Suma nieobecnych</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
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

function refreash() {
    recruitment_statistics.ajax.reload();
}

$(document).ready(function() {

    recruitment_statistics = $('#table').DataTable({
        "order": [[0, "desc"]],
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
        }, "ajax": {
            'url': "{{ route('api.datatableTrainingData') }}",
            'type': 'POST',
            'data': function (d) {
                d.date_start = $('#date_start').val();
                d.date_stop = $('#date_stop').val();
            },
            'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        }, "columns": [
            {"data": function (data, type, dataToSet) {
                let dep = data.dep_name + " " + data.dep_name_type;
                return dep;
            }, "name": "dep_name"},
            {"data": function (data, type, dataToSet) {
                let countUsers = Number(data.sum_choise) + Number(data.sum_absent);
                return countUsers;
            }, "name": "dep_name"},
            {"data": "sum_choise"},
            {"data": "sum_absent"},
        ]
    });
});

</script>
@endsection