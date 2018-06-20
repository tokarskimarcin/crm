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
            margin: 1em;
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
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="heading-container">
                                Statystyki miast
                            </div>
                        </div>
                    </div>
                    <div class="form-container">

                            <div class="form-group" style="margin-left: 1em;">
                                <label for="date" class="myLabel">Data początkowa:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:50%;">
                                    <input class="form-control" name="date_start" id="date_start" type="text" value="{{date("Y-m-d")}}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                            <div class="form-group"style="margin-left: 1em;">
                                <label for="date_stop" class="myLabel">Data końcowa:</label>
                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:50%;">
                                    <input class="form-control" name="date_stop" id="date_stop" type="text" value="{{date("Y-m-d")}}">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                </div>
                            </div>
                        </div>

                    <table id="datatable" class="thead-inverse table table-striped table-bordered" cellspacing="0" width="100%">
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
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button id="modal-close-button" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function(mainEvent) {
            /********** GLOBAL VARIABLES ***********/
            const modalCloseButton = document.querySelector('#modal-close-button');
            /*******END OF GLOBAL VARIABLES*********/

            $('.form_date').datetimepicker({
                language:  'pl',
                autoclose: 1,
                minView : 2,
                pickTime: false,
            });

            table = $('#datatable').DataTable({
                "autoWidth": true,
                "processing": true,
                "serverSide": true,
                "drawCallback": function( settings ) {

                },
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
                    {"data":function (data, type, dataToSet) {
                            return data.voivodeName;
                        },"name":"voivodeName","orderable": true
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.cityName;
                        },"name":"cityName","orderable": true
                    },
                    {"data":function (data, type, dataToSet) {
                            return data.ilosc;
                        },"name":"ilosc","orderable": true
                    },
                    {"data":function (data, type, dataToSet) {
                            return '<a data-toggle="modal"  class="modal_trigger2" href="#showRecords"> <span class="glyphicon glyphicon-search" data-cityId="' + data.cityId + '" data-type="searchIcon"></span></a>';
                        },"name":"ilosc","orderable": true
                    }
                ]
            });

            $('#date_start, #date_stop').on('change', function(e) {
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
                const infoTable = document.createElement('table');
                infoTable.classList.add('table', 'table-striped');

                const theadElement = document.createElement('thead');
                const tbodyElement = document.createElement('tbody');
                const tr1Element = document.createElement('tr');
                const th1Element = document.createElement('th');
                const th2Element = document.createElement('th');

                th1Element.textContent = 'Miasto';
                tr1Element.appendChild(th1Element);

                th2Element.textContent = 'Data';
                tr1Element.appendChild(th2Element);

                theadElement.appendChild(tr1Element);
                infoTable.appendChild(theadElement);

                for (let i = 0; i < data.length; i++) {
                    const trElement = document.createElement('tr');
                    const td1Element = document.createElement('td');
                    const td2Element = document.createElement('td');
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
                const dateStart = $("#date_start").val();
                const dateStop = $('#date_stop').val();

                const data = new FormData();
                data.append('cityId', cityId);
                data.append('dateStart', dateStart);
                data.append('dateStop', dateStop);

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
                    .then(resp => {
                        const modalBody = document.querySelector('.modal2-body');
                        const info = document.createElement('div');
                        info.classList.add('alert', 'alert-info', 'loadedMessage');
                        info.textContent = "Załadowano dane";
                        modalBody.appendChild(info);
                    })
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
            modalCloseButton.addEventListener('click', closeButtonHandler);
        });
    </script>
@endsection
