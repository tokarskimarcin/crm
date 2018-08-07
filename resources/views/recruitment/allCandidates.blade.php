@extends('layouts.main')
@section('content')
    <style>
        .myLabel {
            color: #aaa;
            font-size: 20px;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="well gray-nav">Rekrutacja / Wszyscy kandydaci</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">Od</span>
                <input id="from_date" type="date" class="form-control" aria-describedby="basic-addon1">
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon2">Do</span>
                <input id="to_date" type="date" class="form-control" aria-describedby="basic-addon2">
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 1em">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="candidates" class="table table-striped table-bordered thead-inverse" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <td>Imię i nazwisko</td>
                        <td>Telefon</td>
                        <td>Data dodania</td>
                        <td>Dodany przez</td>
                        <td>W procesie u</td>
                        <td>Status</td>
                        <td>Profil</td>
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
        var now = new Date();

        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);

        var today = now.getFullYear() + "-" + (month) + "-" + (day);
        var firstDayOfThisMonth = now.getFullYear() + "-" + (month) + "-01";

        let fromDate = $('#from_date');
        let toDate = $('#to_date');
        fromDate.val(firstDayOfThisMonth);
        toDate.val(today);

        table = $('#candidates').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "drawCallback": function (settings) {
            },
            "ajax": {
                'url': "{{ route('api.datatableShowCandidates') }}",
                'type': 'POST',
                'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function (d) {
                    d.fromDate = fromDate.val();
                    d.toDate = toDate.val();
                }
            },
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            }, "columns": [
                {
                    "data": function (data, type, dataToSet) {
                        var myName = data.first_name + " " + data.last_name;
                        return myName;
                    }, "orderable": true, "searchable": true, "name": "last_name"
                },
                {"data": "phone"},
                {"data": "created_at"},
                {
                    "data": function (data, type, dataToSet) {
                        var myName = data.cadre_name + " " + data.cadre_surname;
                        return myName;
                    }, "orderable": true, "searchable": true, "name": "cadre_surname"
                },
                {"data": "last_edit_user_name"},
                {"data": "attempt_name"},
                {
                    "data": function (data, type, dataToSet) {
                        return "<a href='{{ URL::to('/candidateProfile') }}/" + data.id + "' class='btn btn-info'><span class='glyphicon glyphicon-pencil'></span> Szczegóły</a>";
                    }, "orderable": false, "searchable": false
                },
            ]
        });

        fromDate.change(() => {
            if (new Date(fromDate.val()) > new Date(toDate.val()))
                toDate.val(fromDate.val());
            table.ajax.reload();
        });

        toDate.change(() => {
            if (new Date(fromDate.val()) > new Date(toDate.val()))
                fromDate.val(toDate.val());
            table.ajax.reload();
        });
    </script>
@endsection