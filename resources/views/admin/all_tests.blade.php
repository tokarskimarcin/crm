@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Podgląd wszystkich testów</div>
        </div>
    </div>
</div>

<div class="table-responsive">
        <table id="datatable" class="table table-striped table-bordered thead-inverse" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th style="width: 10%">Data:</th>
                    <th style="width: 30%">Tytuł:</th>
                    <th style="width: 10%">Stan</th>
                    <th style="width: 10%">Osoba testująca</th>
                    <th style="width: 5%">Szczegóły</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>


@endsection


@section('script')
<script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.select.min.js')}}"></script>

<script>
table = $('#datatable').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "drawCallback": function( settings ) {
    },
    "ajax": {
        'url': "{{ route('api.datatableAllTests') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    },
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },"columns":[
        {"data": "created_at"},
        {"data": "name"},
        {"data": function (data, type, dataToSet) {
            var myType = data.status;
            if (myType == 1) {
                return 'Wykreowany';
            } else if (myType == 2) {
                return 'Aktywowany';
            } else if (myType == 3) {
                return 'Zakończony';
            } else if (myType == 4) {
                return 'Oceniono';
            }
        },"orderable": true, "searchable": false, "name": "status"},
        {"data": function (data, type, dataToSet) {
            var myName = data.first_name + " " + data.last_name;
            return myName;
        },"orderable": false, "searchable": true, "name": "last_name"},
        {"data": function (data, type, dataToSet) {
            return '<a class="btn btn-default" href="{{ URL::to('show_test_for_admin') }}/' + data.id + '">Szczegóły</a>';
        },"orderable": false, "searchable": false },
    ]
});


</script>
@endsection
