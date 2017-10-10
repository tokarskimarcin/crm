@extends('layouts.main')
@section('content')


    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Akceptacja Godzin</h1>
        </div>
    </div>


    <table id="datatable">
        <thead>
        <tr>
            <th>id</th>
            <th>click_start</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="row">
        <div class="col-lg-12">
        </div>
    </div>


@endsection

@section('script')

    <script>
        $(document).ready( function () {
            $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': "{{ route('api.acceptHour') }}",
                    'type': 'POST',
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },
                "columns":[
                    {"data": "id"},
                    {"data": "click_start"}
                ]
            })
        })


    </script>















    </script>
@endsection
