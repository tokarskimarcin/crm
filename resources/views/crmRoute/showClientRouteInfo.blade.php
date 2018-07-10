{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view shows info list of client routes,--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: , --}}
{{--*/--}}
@extends('layouts.main')
@section('style')
@endsection

@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">Baza miejscowości/hotele</div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel z informacjami
        </div>
        <div class="panel-body">
            <table id="datatable" class="table row-border table-striped">
                <thead>
                <tr>
                    <th>Klient</th>
                    <th>Tydzień</th>
                    <th>Data</th>
                    <th>Miasto</th>
                    <th>Hotel</th>
                    <th>Os. rezerwująca</th>
                    <th>Cena za salę</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        let table = $('#datatable').DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            scrollY: '45vh',
            ajax: {
                url: "{{route('api.datatableClientRouteInfoAjax')}}",
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },
            columns:[
                {data: 'clientName'},
                {data: 'weekOfYear'},
                {data: 'date'},
                {data: 'cityName'},
                {data: 'hotelName'},
                {data: 'userReservation'},
                {data: 'hotelPrice'}
            ]
        });

        $('#menu-toggle').change(()=>{
            table.columns.adjust().draw();
        });
    </script>
@endsection