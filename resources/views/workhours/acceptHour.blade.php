@extends('layouts.main')
@section('content')


    {{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Akceptacja Godzin</h1>
        </div>
    </div>

    <div class="col-lg-3">
        <label for ="ipadress">Zakres wyszukiwania:</label>
        <div class="form-group">
            <label for ="ipadress">Od:</label>
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                <input id="start_date" class="form-control" name="od" type="text" value="{{date("Y-m-d")}}" readonly >

                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
        </div>
        <div class="form-group">
            <label for ="ipadress">Do:</label>
            <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                <input id="stop_date" class="form-control" name="do" type="text" value="{{date("Y-m-d")}}"readonly >

                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
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

        $(function() {
            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false,
            });
        });
        $(document).ready( function () {
            $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': "{{ route('api.acceptHour') }}",
                    'type': 'POST',
                    'data': function ( d ) {
                        d.start_date = $('#start_date').val();
                        d.stop_date = $('#stop_date').val();
                    },
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
