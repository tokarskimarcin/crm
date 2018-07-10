{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view shows info list of client routes,--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: , --}}
{{--*/--}}
@extends('layouts.main')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">Baza miejscowości/hotele</div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel z informacjami
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="year">Rok</label>
                <select id="year" multiple="multiple" style="width: 100%;">
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="weeks">Tygodnie</label>
                <select id="weeks" multiple="multiple" style="width: 100%;">
                </select>
            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <script>
        let selectedYears = ["0"]; //this array collect selected by user years
        let selectedWeeks = ["0"]; //this array collect selected by user weeks
        /*Activation select2 framework*/
        (function initial() {
            $('#weeks').select2();
            $('#year').select2();
        })();

        let table = $('#datatable').DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            scrollY: '45vh',
            ajax: {
                url: "{{route('api.datatableClientRouteInfoAjax')}}",
                type: 'POST',
                data: function (d) {
                    d.years = selectedYears;
                    d.weeks = selectedWeeks;
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
         * This event listener change elements of array selected Years while user selects another year
         */
        $('#year').on('select2:select', function (e) {
            let yearArr = $('#year').val();
            if(yearArr.length > 0) { //no values, removed by user
                selectedYears = yearArr;
            }
            else {
                selectedYears = ["0"];
            }
            table.ajax.reload();
        });

        /**
         * This event listener change elements of array selected Years while user unselects some year
         */
        $('#year').on('select2:unselect', function(e) {
            if($('#year').val() != null) {
                let yearArr = $('#year').val();
                selectedYears = yearArr;
            }
            else {
                selectedYears = ["0"];
            }
            table.ajax.reload();
        });

        /**
         * This event listener change elements of array selecteWeeks while user selects another week
         */
        $('#weeks').on('select2:select', function(e) {
            let weeksArr = $('#weeks').val();
            if(weeksArr.length > 0) {
                selectedWeeks = weeksArr;
            }
            else {
                selectedWeeks = ["0"];
            }
            table.ajax.reload();
        });

        /**
         * This event listener change elements of array selectedWeeks while user unselects any week.
         */
        $("#weeks").on('select2:unselect', function(e) {
            if($('#weeks').val() != null) {
                let weeksArr = $('#weeks').val();
                selectedWeeks = weeksArr;
            }
            else {
                selectedWeeks = ['0'];
            }
            table.ajax.reload();
        });

        /**
         * This function appends week numbers to week select element and years to year select element
         * IIFE function, execute after page is loaded automaticaly
         */
        (function appendWeeksAndYears() {
            const maxWeekInYear = {{$lastWeek}}; //number of last week in current year
            const weekSelect = document.querySelector('#weeks');
            const yearSelect = document.querySelector('#year');
            const baseYear = '2017';
            const currentYear = {{$currentYear}};
            const currentWeek = {{$currentWeek}};
            for(let j = baseYear; j <= currentYear + 1; j++) {
                const opt = document.createElement('option');
                opt.value = j;
                opt.textContent = j;
                if(j == currentYear) {
                    opt.setAttribute('selected', 'selected');
                    selectedYears = [j];
                }
                yearSelect.appendChild(opt);
            }

            for(let i = 1; i <= maxWeekInYear + 1; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = i;
                if(i == currentWeek) {
                    opt.setAttribute('selected', 'selected');
                    selectedWeeks = [i];
                }
                weekSelect.appendChild(opt);
            }
        })();
    </script>
@endsection