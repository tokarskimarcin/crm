{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view shows info list of client routes,--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: , --}}
{{--*/--}}
@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <style>
        #fullscreen {
            margin-top: 1.75em;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">Baza miejscowości/hotele</div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel z informacjami
        </div>
        <div class="alert alert-info">
            Moduł <strong>Baza miejscowości/hotele</strong> prezentuje informacje o miastach i hotelach przypisanych do tras dla poszczególnych klientów.
            Lista <strong>klienci </strong> jest wielokrotnego wyboru. Tryb pełnoekranowy, można go opuścić naciskająć przycisk "ESC" na klawiaturze.
        </div>

            <div class="row">

                <div class="col-md-3">
                    <div class="form-group" style="margin-left: 1em;">
                        <label for="date" class="myLabel">Data początkowa:</label>
                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                            <input class="form-control" name="date_start" id="date_start" type="text" value="{{date("Y-m-d")}}">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group"style="margin-left: 1em;">
                        <label for="date_stop" class="myLabel">Data końcowa:</label>
                        <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                            <input class="form-control" name="date_stop" id="date_stop" type="text" value="{{date("Y-m-d")}}">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <label for="clients">Klienci</label>
                    <select class="selectpicker form-control" id="clients" name="link_privilages[]" title="Brak wybranych użytkowników" multiple data-actions-box="true">
                        @if(isset($clients))
                            @foreach($clients as $client)
                                <option value="{{$client->id}}">{{$client->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <button id="fullscreen" class="btn btn-info"><span class="glyphicon glyphicon-fullscreen"></span> Tryb pełnoekranowy</button>
                </div>
            </div>


            <div class="panel-body">

                <table id="datatable" class="thead-inverse table row-border table-striped">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <script>
        let datatableHeight = '45vh'; //this variable defines height of table
        let fullscreen = document.getElementById('fullscreen'); // fullscreen button
        const legendBar = document.querySelector('.alert-info');

        const now = new Date();
        const day = ("0" + now.getDate()).slice(-2);
        const month = ("0" + (now.getMonth() + 1)).slice(-2);
        const today = now.getFullYear() + "-" + (month) + "-" + (day);
        const firstDayOfThisMonth = now.getFullYear() + "-" + (month) + "-01";

        /*Activation selectpicker and datetimepicker framework*/
        (function initial() {
            $('.selectpicker').selectpicker({
                selectAllText: 'Zaznacz wszystkie',
                deselectAllText: 'Odznacz wszystkie'
            });

            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false,
            });

            $('#date_start').val(firstDayOfThisMonth);
        })();

        let table = $('#datatable').DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            scrollY: datatableHeight,
            ajax: {
                url: "{{route('api.datatableClientRouteInfoAjax')}}",
                type: 'POST',
                data: function (d) {
                    d.dateStop = $('#date_stop').val();
                    d.dateStart = $('#date_start').val();
                    d.clients = $('#clients').val();
                },
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

        /**
         * This event listener reloads table after changing start or stop date
         */
        $('#date_start, #date_stop, #clients').on('change', function(e) {
            table.ajax.reload();
        });

        /**
         * This event listener function allow fullscreen with proper table height.
         */
        function fullScreenHandler(e) {
            const elem = document.querySelector('.panel-default');

            if(elem.mozRequestFullScreen) {
                datatableHeight = '65vh';
                $('div.dataTables_scrollBody').css('height',datatableHeight);
                legendBar.style.display = 'none';
                elem.mozRequestFullScreen();
            }
            if(elem.webkitRequestFullscreen) {
                datatableHeight = '65vh';
                $('div.dataTables_scrollBody').css('height',datatableHeight);
                legendBar.style.display = 'none';
                elem.webkitRequestFullscreen();
            }

            if(elem.msRequestFullscreen) {
                datatableHeight = '65vh';
                $('div.dataTables_scrollBody').css('height',datatableHeight);
                legendBar.style.display = 'none';
                elem.msRequestFullscreen();
            }

        }

        /**
         * This event listeners adjust height of table after closing full screen mode.
         */
        document.addEventListener("mozfullscreenchange", function( ev ) {
            if ( document.mozFullScreenElement === null ) {
                datatableHeight = '45vh';
                $('div.dataTables_scrollBody').css('height',datatableHeight);
                legendBar.style.display = 'block';
            }
        });

        document.addEventListener("webkitfullscreenchange", function( ev ) {
            if ( document.webkitFullscreenElement === null ) {
                datatableHeight = '45vh';
                $('div.dataTables_scrollBody').css('height',datatableHeight);
                legendBar.style.display = 'block';
            }
        });

        document.addEventListener("MSFullscreenChange", function( ev ) {
            if ( document.msFullscreenElement === null ) {
                datatableHeight = '45vh';
                $('div.dataTables_scrollBody').css('height',datatableHeight);
                legendBar.style.display = 'block';
            }
        });

        fullscreen.addEventListener('click', fullScreenHandler);
    </script>
@endsection