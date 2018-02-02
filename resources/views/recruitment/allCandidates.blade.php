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
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="candidates" class="table table-striped table-bordered thead-inverse" cellspacing="0" width="100%" >
              <thead>
                <tr>
                    <td>Imie i nazwisko</td>
                    <td>Data dodania</td>
                    <td>Dodany przez</td>
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

table = $('#candidates').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "drawCallback": function( settings ) {
    },
    "ajax": {
        'url': "{{ route('api.datatableShowCandidates') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    },
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },"columns":[
        {"data": function (data, type, dataToSet) {
            var myName = data.first_name + " " + data.last_name;
            return myName;
        },"orderable": true, "searchable": true, "name": "last_name"},
        {"data": "created_at"},
        {"data": function (data, type, dataToSet) {
            var myName = data.cadre_name + " " + data.cadre_surname;
            return myName;
        },"orderable": true, "searchable": true, "name": "cadre_surname"},
        {"data": "created_at"},
        {"data": function (data, type, dataToSet) {
            return "<a href='{{ URL::to('/candidateProfile') }}/" + data.id +"' class='btn btn-info'><span class='glyphicon glyphicon-pencil'></span> Szczegóły</a>";
        },"orderable": false, "searchable": false},


        {{--  {"data": "name"},
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
        },"orderable": false, "searchable": false },  --}}
    ]
});

</script>
@endsection