@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Dział HR / Pracownicy działu HR</div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table id="datatable" class="table thead-inverse table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Oddział</th>
            <th>Email</th>
            <th>Nr. Tel.</th>
            <th>Akcja</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

@endsection
@section('script')
<script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
<script>

table = $('#datatable').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "drawCallback": function( settings ) {
    },
    "ajax": {
        'url': "{{ route('api.datatableCadreHR') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    },
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },"columns":[
        {"data": "first_name"},
        {"data": "last_name"},
        {"data": function (data, type, dataToSet) {
            return data.dep_name + ' ' + data.dep_name_type;
          }
        },
        {"data": "username","name":"username"},
        {"data": "phone"},
        {"data": function (data, type, dataToSet) {
              return '<a class="btn btn-default" href="edit_cadre/'+data.id+'" >Edytuj</a>';
        },"orderable": false, "searchable": false }
        ]
});

</script>
@endsection
