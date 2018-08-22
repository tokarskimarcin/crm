{{--/*--}}
{{--*@category: CRM,--}}
{{--*@info: This view shows list of available campaigns,--}}
{{--*@controller: CrmRouteController,--}}
{{--*@methods: showHotelsAjax, showHotelsGet--}}
{{--*/--}}

@extends('layouts.main')
@section('style')

@endsection
@section('content')

    <style>
        .heading-container {
            text-align: center;
            font-size: 2em;
            margin-top: 1em;
            margin-bottom: 1em;
            margin-left: .5em;
            margin-right: .5em;
            font-weight: bold;
            box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
            padding-top: 1em;
            padding-bottom: 1em;
        }

        .form-container {
            box-shadow: 0 1px 15px 1px rgba(39,39,39,.1);
            padding-top: 1em;
            padding-bottom: 1em;
            margin: 1em;

        }
    </style>

{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Statystyki miast</div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Podgląd informacji o wykorzystanych miastach
                </div>
                <div class="panel-body">
                        <div class="row">
                            <div class="alert alert-info" style="font-size:2em;">
                                W module statystyki miast znajduję się lista miast użytych w kampaniach, w określonym przedziale czasu. Przycisk podgląd pozwala podejrzeć szczegóły.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" style="margin-left: 1em;">
                                    <label for="date" class="myLabel">Data początkowa:</label>
                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                        <input class="form-control" name="date_start" id="date_start" type="text" value="{{date("Y-m-d")}}">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" style="margin-left: 1em;">
                                    <label for="date_stop" class="myLabel">Data końcowa:</label>
                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                        <input class="form-control" name="date_stop" id="date_stop" type="text" value="{{date("Y-m-d")}}">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>



                    <div class="row" style="padding-left: 2em;padding-right:2em;">
                        <table id="datatable" class="thead-inverse table table-striped row-border" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Wojewodztwo</th>
                                <th>Miasto</th>
                                <th>Ilość</th>
                                <th>Podgląd</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="showRecords" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Info</h4>
                </div>
                <div class="modal2-body">
                    <div class="alert alert-danger">Ładowanie danych.. </div>
                </div>
                <div class="modal-footer">
                    <button id="modal-close-button" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /********** GLOBAL VARIABLES ***********/
            let APP = {
                dates: {
                    now: new Date()
                },
                DOMElements: {
                    dateStart: $("#date_start"),
                    dateStop: $('#date_stop'),
                    modalCloseButton: document.querySelector('#modal-close-button')
                }
            };
            /*******END OF GLOBAL VARIABLES*********/

            /**
             * This function at beggining set date input value to first day of current month
             */
            (function init() {
                let month = ("0" + (APP.dates.now.getMonth() + 1)).slice(-2);
                let firstDayOfThisMonth = APP.dates.now.getFullYear() + "-" + (month) + "-01";
                $(APP.DOMElements.dateStart).val(firstDayOfThisMonth);
            })();

            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false,
            });

            let table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': "{{ route('api.showCitiesStatisticsAjax') }}",
                    'type': 'POST',
                    'data': function (d) {
                        d.startDate = $('#date_start').val();
                        d.stopDate = $("#date_stop").val();
                    },
                    'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },"columns":[
                    {"data":function (data) {
                            return data.voivodeName;
                        },"name":"voivodeName","orderable": true
                    },
                    {"data":function (data) {
                            return data.cityName;
                        },"name":"cityName","orderable": true
                    },
                    {"data":function (data) {
                            return data.ilosc;
                        },"name":"ilosc","orderable": true
                    },
                    {"data":function (data) {
                       return '<button class="btn btn-default btn-block" data-toggle="modal" data-target="#showRecords" data-type="searchIcon" data-cityId="' + data.cityId + '"><span class="glyphicon glyphicon-search" data-cityId="' + data.cityId + '" data-type="searchIcon" style="font-size: 2em;"></span></button>';
                        },"name":"ilosc","orderable": false, searchable: false, width: '10%'
                    }
                ]
            });

            /**
             * This event listener reloads table after changing start or stop date
             */
            $('#date_start, #date_stop').on('change', () => {
               table.ajax.reload();
            });

            /**
             * This function is responsible for click action on whole document.
             * @param e
             */
            function clickHandler(e) {
                if(e.target.dataset.type == "searchIcon") {
                    let cityId = e.target.dataset.cityid;
                    fillModal(cityId);
                }
            }

            /**
             * This method creates on fly table with clientRouteInfo records
             * @param placeToAppend - modal body
             * @param data - mostly clientRouteInfo records
             */
            function createModalTable(placeToAppend, data) {
                console.assert(Array.isArray(data), 'data parameter in createModalTable function ins not Array!');
                const infoTable = document.createElement('table');
                infoTable.classList.add('table', 'table-striped');

                with (document) {
                   [theadElement, tbodyElement, tr1Element, th1Element, th2Element] =
                       [createElement('thead'), createElement('tbody'), createElement('tr'), createElement('th'), createElement('th')];
                }

                th1Element.textContent = 'Miasto';
                tr1Element.appendChild(th1Element);

                th2Element.textContent = 'Data';
                tr1Element.appendChild(th2Element);

                theadElement.appendChild(tr1Element);
                infoTable.appendChild(theadElement);

                for (let i = 0, max = data.length; i < max; i++) {
                    with(document) {
                        [trElement, td1Element, td2Element] = [createElement('tr'), createElement('td'), createElement('td')];
                    }

                    td1Element.textContent = data[i].cityName;
                    td2Element.textContent = data[i].date;
                    trElement.appendChild(td1Element);
                    trElement.appendChild(td2Element);
                    tbodyElement.appendChild(trElement);
                }
                infoTable.appendChild(tbodyElement);
                placeToAppend.appendChild(infoTable);
            }

            /**
             * This method is responsible for modal body
             * @param cityId
             */
            function fillModal(cityId) {
                const url = `{{route('api.getClientRouteInfoRecords')}}`;
                const ourHeaders = new Headers();
                ourHeaders.append('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

                const data = new FormData();
                data.append('cityId', cityId);
                data.append('dateStart', APP.DOMElements.dateStart.val());
                data.append('dateStop', APP.DOMElements.dateStop.val());

                fetch(url, {
                    method: 'post',
                    headers: ourHeaders,
                    credentials: "same-origin",
                    body: data
                }).then(resp => resp.json())
                    .then(resp => {
                        const modalBody = document.querySelector('.modal2-body');
                        modalBody.innerHTML = '';
                        return createModalTable(modalBody, resp);
                    })
                    .then(() => {
                        const modalBody = document.querySelector('.modal2-body');
                        const info = document.createElement('div');
                        info.classList.add('alert', 'alert-info', 'loadedMessage');
                        info.textContent = "Załadowano dane";
                        modalBody.appendChild(info);
                    })
                    .catch(err => console.log('Błąd: ', err))
            }

            /**
             * This function remove info section from modal
             * @param e
             */
            function closeButtonHandler(e) {
                if(document.querySelector('.loadedMessage')) {
                    const info = document.querySelector('.loadedMessage');
                    info.parentNode.removeChild(info);
                }
            }

            document.addEventListener('click', clickHandler);
            APP.DOMElements.modalCloseButton.addEventListener('click', closeButtonHandler);
        });
    </script>
@endsection
