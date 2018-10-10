@php
    /**
     * Created by PhpStorm.
     * User: veronaprogramista
     * Date: 09.10.18
     * Time: 14:29
     */
@endphp

{{--/*--}}
{{--*@category: ,--}}
{{--*@info: This template view is for copy purpose--}}
{{--*@controller: ,--}}
{{--*@methods: , --}}
{{--*/--}}

@extends('layouts.main')
@section('style')
    <link rel="stylesheet" href="{{asset('/css/fixedHeader.dataTables.min.css')}}">
    <link href="{{ asset('/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/assets/css/VCtooltip.css')}}">
    <style>
        .leftPanel{
            border-right: 1px #b9b9b9 solid;
        }
        .leftPanel{
            border-right: 1px #b9b9b9 solid;
        }
        .bootstrap-select > .dropdown-menu {
            left: 0 !important;
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">Modyfikacja systemu zgłoszeń</div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel do modyfikacji
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-7 leftPanel">
                    <button id="newRatingCriterion" class="btn btn-block btn-default"><span class="glyphicon glyphicon-plus"></span> Dodaj nowe kryterium oceniania</button>
                    <hr>
                    <table id="ratingCriterionDatatable" class="table display" style="width: 100%;">
                        <thead>
                        <tr>
                            <th>
                                Lp.
                            </th>
                            <th>
                                Kryterium oceniania
                            </th>
                            <th>
                                System ocen
                            </th>
                            <th>
                                Akcja
                            </th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="col-md-5 rightPanel">
                    <button id="newRatingSystem" class="btn btn-block btn-default"><span class="glyphicon glyphicon-plus"></span> Dodaj nowy system ocen</button>
                    <hr>
                    <table id="ratingSystemDatatable" class="table display" style="width: 100%;">
                        <thead>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                System ocen
                            </th>
                            <th>
                                Opis
                            </th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            let VARIABLES = {
                jQElements:{
                    newRatingCriterionButton: $('#newRatingCriterion'),
                    newRatingSystemButton: $('#newRatingSystem'),
                    myModal: $('#myModal')
                },
                DATA_TABLES: {
                    ratingCriterion: {
                        data: {
                            criterion: []
                        },
                        table: $('#ratingCriterionDatatable'),
                        dataTable: $('#ratingCriterionDatatable').DataTable({
                            scrollX: true,
                            scrollY: '40vh',
                            paging: false,
                            processing: true,
                            language: {
                                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                            },
                            ordering: false,
                            columns:[
                                {data: 'lp'},
                                {data: 'criterion'},
                                {data: function (data) {
                                        return $('<textarea>').css({'width':'20em','height':'3em', 'resize':'none'})
                                            .append(data.rating_system.rating_start+' - '+data.rating_system.rating_stop
                                                +' : '+data.rating_system.description).prop('outerHTML')
                                    }},
                                {data: function (data) {
                                    let actionButton = $('<button>')
                                        .addClass('onoff btn btn-block').append($('<span>')
                                            .addClass('glyphicon glyphicon-off'));
                                    if(data.status == 0){
                                        actionButton.addClass('btn-success');
                                    }else if(data.status == 1){
                                        actionButton.addClass('btn-danger');
                                    }

                                    return actionButton.prop('outerHTML');
                                    }, name: 'action'}
                            ],
                            fnDrawCallback: function () {
                            },
                            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                                if(aData.status == 0){
                                    $(nRow).css({'background': '#ffa8a3'});
                                }
                                $(nRow).find('.onoff').click(function (e) {
                                    let onoffText = 'wyłączyć';
                                    if(aData.status == 0){
                                        onoffText = 'włączyć';
                                    }
                                    swal({
                                        showCancelButton: true,
                                        title: 'Czy na pewno?',
                                        text: 'Czy na pewno chcesz '+onoffText+' kryterium?',
                                        type: 'warning'}).then(function (result) {
                                        if(result.value){
                                            FUNCTIONS.AJAXs.ratingCriterionStatusChangeAjax(aData.id, aData.status == 1 ? 0 : 1);
                                        }
                                    })
                                });
                            }
                        }),
                        getData: function () {
                            return FUNCTIONS.AJAXs.ratingCriterionDataAjax().then(function (response) {
                                return response;
                            });
                        },
                        setTableData: function (data){
                            FUNCTIONS.setTableData(data, this.dataTable);

                        },
                        ajaxReload: function () {
                            FUNCTIONS.ajaxReload(this);
                        }
                    },
                    ratingSystem: {
                        data: {
                            systems: []
                        },
                        table: $('#ratingSystemDatatable'),
                        dataTable: $('#ratingSystemDatatable').DataTable({
                            scrollX: true,
                            scrollY: '40vh',
                            paging: false,
                            processing: true,
                            language: {
                                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                            },
                            ordering: false,
                            columns:[
                                {data: 'id'},
                                {data: function (data) {
                                        return data.rating_start+' - '+data.rating_stop;
                                    }, name:'rating_system'},
                                {data: function (data) {/*
                                    let descriptionButton = $('<button>')
                                        .addClass('btn btn-block btn-default')
                                        .append($('<span>')
                                            .addClass('glyphicon glyphicon-search'));
                                    return descriptionButton.prop('outerHTML');*/
                                        return $('<textarea>').css({'width':'20em','height':'3em', 'resize':'none'}).append(data.description).prop('outerHTML');
                                    }, name: 'descriptionButton'}
                            ],
                            fnDrawCallback: function () {
                            },
                            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                                /*$(nRow).find('button').click(function (e) {
                                    FUNCTIONS.prepareModal();
                                    VARIABLES.jQElements.myModal.find('.modal-header').append($('<h2>').append('Opis'));
                                    VARIABLES.jQElements.myModal.find('.modal-body').append(aData.description);
                                    VARIABLES.jQElements.myModal.modal('show');
                                });*/
                            }
                        }),
                        getData: function () {
                            return FUNCTIONS.AJAXs.ratingSystemDataAjax().then(function (response) {
                                return response;
                            });
                        },
                        setTableData: function (data){
                            FUNCTIONS.setTableData(data, this.dataTable);

                        },
                        ajaxReload: function () {
                            FUNCTIONS.ajaxReload(this);
                        }
                    }}
            };

            let FUNCTIONS = {
                /* function grups should be before other functions which aren't grouped */
                EVENT_HANDLERS: {
                    handleTextInputOnlyNumbers: function(e){
                        if(!isNaN(parseInt($(e.target).val()))){
                            $(e.target).val(parseInt($(e.target).val()));
                        }else{
                            $(e.target).val('');
                        }
                    },
                    callEvents: function () {
                        (function newRatingCriterionButtonHandler() {
                                VARIABLES.jQElements.newRatingCriterionButton.click(function () {
                                    FUNCTIONS.prepareModal();
                                    VARIABLES.jQElements.myModal.modal('show');
                                    VARIABLES.jQElements.myModal.find('.modal-header')
                                        .append($('<h2>')
                                            .addClass('modal-title')
                                            .append('Formularz nowego kryterium oceniania'));
                                    let criterionTextArea = $('<textarea>').css({'width':'100%','resize':'vertical','min-height':'10vh','max-height':'30vh'});
                                    let selectRatingSystems = $('<select>').addClass('form-control selectpicker').attr('data-live-search',true);
                                    selectRatingSystems.append($('<option>').val(0).text('Wybierz')).prop('selected',true);
                                    $.each(VARIABLES.DATA_TABLES.ratingSystem.data.systems, function(index, system){
                                        selectRatingSystems.append($('<option>').val(system.id).text(system.rating_start+' - '+system.rating_stop+' : '+system.description.substring(0,80))
                                            .attr('data-subtext','ID: '+system.id));
                                    });
                                    VARIABLES.jQElements.myModal.find('.modal-body')
                                        .append($('<label>')
                                            .append('Kryterium'))
                                        .append(criterionTextArea)
                                        .append($('<hr>'))
                                        .append($('<label>')
                                            .append('System oceny'))
                                        .append(selectRatingSystems);
                                    selectRatingSystems.selectpicker('refresh');
                                    VARIABLES.jQElements.myModal.find('.modal-footer')
                                        .append($('<button>')
                                            .addClass('btn btn-success')
                                            .append($('<span>')
                                                .addClass('glyphicon glyphicon-save'))
                                            .append(' Zapisz')
                                            .click(function (e) {
                                                let validation = true;
                                                if(parseInt(selectRatingSystems.selectpicker('val')) <= 0){
                                                    validation = false;
                                                }
                                                if(!criterionTextArea.val()){
                                                    validation = false;
                                                }
                                                if(validation){
                                                    FUNCTIONS.AJAXs.newRatingCriterionDataAjax(criterionTextArea.val(), selectRatingSystems.selectpicker('val'))
                                                }else{
                                                    swal('Wypełnij wszystkie pola');
                                                }
                                            }));

                                });
                            }
                        )();
                        (function newRatingSystemButtonHandler() {
                                VARIABLES.jQElements.newRatingSystemButton.click(function () {
                                    FUNCTIONS.prepareModal();
                                    VARIABLES.jQElements.myModal.modal('show');
                                    VARIABLES.jQElements.myModal.find('.modal-header')
                                        .append($('<h2>')
                                            .addClass('modal-title')
                                            .append('Formularz nowego systemu oceniania'));

                                    let ratingStartInput = $('<input>').attr('id','ratingStartInput').attr('placeholder','Początek punktacji').attr('type','text').addClass('form-control');
                                    let ratingStopInput = $('<input>').attr('id','ratingStopInput').attr('placeholder','Koniec punktacji').attr('type','text').addClass('form-control');

                                    ratingStartInput.on('input', function (e) {
                                        FUNCTIONS.EVENT_HANDLERS.handleTextInputOnlyNumbers(e);
                                        if(!isNaN(parseInt($(e.target).val())) && !isNaN(parseInt(ratingStopInput.val()))){
                                            if(parseInt($(e.target).val()) > parseInt(ratingStopInput.val())){
                                                ratingStopInput.val($(e.target).val())
                                            }
                                        }
                                    });

                                    ratingStopInput.on('input', function (e) {
                                        FUNCTIONS.EVENT_HANDLERS.handleTextInputOnlyNumbers(e);
                                        if(!isNaN(parseInt($(e.target).val())) && !isNaN(parseInt(ratingStartInput.val()))){
                                            if(parseInt($(e.target).val()) < parseInt(ratingStartInput.val())){
                                                ratingStartInput.val($(e.target).val())
                                            }
                                        }
                                    });

                                    let descriptionTextArea = $('<textarea>').css({'width':'100%','resize':'vertical','min-height':'10vh','max-height':'30vh'});
                                    let ratingGroupInput = $('<div>').addClass('input-group')
                                        .append(ratingStartInput)
                                        .append($('<span>')
                                            .addClass('input-group-addon')
                                            .append('-'))
                                        .append(ratingStopInput);
                                    VARIABLES.jQElements.myModal.find('.modal-body')
                                        .append(ratingGroupInput)
                                        .append($('<hr>'))
                                        .append($('<label>')
                                            .append('Opis nowego systemu'))
                                        .append(descriptionTextArea);
                                    VARIABLES.jQElements.myModal.find('.modal-footer')
                                        .append($('<button>')
                                            .addClass('btn btn-success')
                                            .append($('<span>')
                                                .addClass('glyphicon glyphicon-save'))
                                            .append(' Zapisz')
                                            .click(function (e) {
                                                let validation = true;
                                                if(isNaN(parseInt(ratingStartInput.val()))){
                                                    validation = false;
                                                }
                                                if(isNaN(parseInt(ratingStopInput.val()))){
                                                    validation = false;
                                                }
                                                if(!descriptionTextArea.val()){
                                                    validation = false;
                                                }
                                                if(validation){
                                                    FUNCTIONS.AJAXs.newRatingSystemDataAjax(ratingStartInput.val(), ratingStopInput.val(), descriptionTextArea.val())
                                                }else{
                                                    swal('Wypełnij wszystkie pola');
                                                }
                                            }));
                                });
                            }
                        )();
                    }
                },
                AJAXs: {
                    ratingCriterionDataAjax: function() {
                        return $.ajax({
                            url: "{{ route('api.ratingCriterionDataAjax') }}",
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                            },
                            success: function (response) {
                                VARIABLES.DATA_TABLES.ratingCriterion.data.criterion = response;
                                return response;
                            },
                            error: function (jqXHR, textStatus, thrownError) {
                                console.log(jqXHR);
                                console.log('textStatus: ' + textStatus);
                                console.log('thrownError: ' + thrownError);
                                swal({
                                    type: 'error',
                                    title: 'Błąd ' + jqXHR.status,
                                    text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                                });
                            }
                        });
                    },
                    ratingSystemDataAjax: function() {
                        return $.ajax({
                            url: "{{ route('api.ratingSystemDataAjax') }}",
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                            },
                            success: function (response) {
                                VARIABLES.DATA_TABLES.ratingSystem.data.systems = response;
                                return response;
                            },
                            error: function (jqXHR, textStatus, thrownError) {
                                console.log(jqXHR);
                                console.log('textStatus: ' + textStatus);
                                console.log('thrownError: ' + thrownError);
                                swal({
                                    type: 'error',
                                    title: 'Błąd ' + jqXHR.status,
                                    text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                                });
                            }
                        });
                    },
                    newRatingCriterionDataAjax: function(criterion, ratingSystemId) {
                        return $.ajax({
                            url: "{{ route('api.newRatingCriterionDataAjax') }}",
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                                criterion: criterion,
                                ratingSystemId: ratingSystemId
                            },
                            success: function (response) {
                                VARIABLES.jQElements.myModal.modal('hide');
                                if(response === 'success'){
                                    $.notify({
                                        // options
                                        message: 'Dodano nowe kryterium oceniania'
                                    },{
                                        // settings
                                        type: 'success'
                                    });
                                    VARIABLES.DATA_TABLES.ratingCriterion.ajaxReload();
                                }else{
                                    $.notify({
                                        // options
                                        message: 'Coś poszło nie tak'
                                    },{
                                        // settings
                                        type: 'danger'
                                    });
                                }
                            },
                            error: function (jqXHR, textStatus, thrownError) {
                                console.log(jqXHR);
                                console.log('textStatus: ' + textStatus);
                                console.log('thrownError: ' + thrownError);
                                swal({
                                    type: 'error',
                                    title: 'Błąd ' + jqXHR.status,
                                    text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                                });
                            }
                        });
                    },
                    ratingCriterionStatusChangeAjax: function(ratingCriterionId, status) {
                        return $.ajax({
                            url: "{{ route('api.ratingCriterionStatusChangeAjax') }}",
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                                ratingCriterionId: ratingCriterionId,
                                status: status
                            },
                            success: function (response) {
                                if(response === 'success'){
                                    $.notify({
                                        // options
                                        message: 'Zmieniono status kryterium oceniania'
                                    },{
                                        // settings
                                        type: 'success'
                                    });
                                    VARIABLES.DATA_TABLES.ratingCriterion.ajaxReload();
                                }else{
                                    $.notify({
                                        // options
                                        message: 'Coś poszło nie tak'
                                    },{
                                        // settings
                                        type: 'danger'
                                    });
                                }
                            },
                            error: function (jqXHR, textStatus, thrownError) {
                                console.log(jqXHR);
                                console.log('textStatus: ' + textStatus);
                                console.log('thrownError: ' + thrownError);
                                swal({
                                    type: 'error',
                                    title: 'Błąd ' + jqXHR.status,
                                    text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                                });
                            }
                        });
                    },
                    newRatingSystemDataAjax: function(ratingStart, ratingStop, description) {
                        return $.ajax({
                            url: "{{ route('api.newRatingSystemDataAjax') }}",
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            data: {
                                ratingStart: ratingStart,
                                ratingStop: ratingStop,
                                description: description
                            },
                            success: function (response) {
                                VARIABLES.jQElements.myModal.modal('hide');
                                if(response === 'success'){
                                    $.notify({
                                        // options
                                        message: 'Dodano nowy system oceniania'
                                    },{
                                        // settings
                                        type: 'success'
                                    });
                                    VARIABLES.DATA_TABLES.ratingSystem.ajaxReload();
                                }else{
                                    $.notify({
                                        // options
                                        message: 'Coś poszło nie tak'
                                    },{
                                        // settings
                                        type: 'danger'
                                    });
                                }
                            },
                            error: function (jqXHR, textStatus, thrownError) {
                                console.log(jqXHR);
                                console.log('textStatus: ' + textStatus);
                                console.log('thrownError: ' + thrownError);
                                swal({
                                    type: 'error',
                                    title: 'Błąd ' + jqXHR.status,
                                    text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                                });
                            }
                        });
                    }
                },
                ajaxReload: function(dataTable){
                    let processing = $('#'+dataTable.table.attr('id')+'_processing');
                    dataTable.getData().done(function (response) {
                        dataTable.setTableData(response);
                        processing.hide();
                    });
                },
                setTableData: function(data, dataTable){
                    let lp = 1;
                    dataTable.clear();
                    if($.isArray(data)) {
                        $.each(data, function (index, row) {
                            row.lp = lp;
                            dataTable.row.add(row);
                            lp++;
                        });
                        dataTable.draw();
                    }
                },
                prepareModal: function (){
                    FUNCTIONS.clearModal();
                    FUNCTIONS.fillModalDefaultContent();
                },
                clearModal: function () {
                    VARIABLES.jQElements.myModal.find('.modal-header').empty();
                    VARIABLES.jQElements.myModal.find('.modal-body').empty();
                    VARIABLES.jQElements.myModal.find('.modal-footer').empty();
                },
                fillModalDefaultContent: function(){
                    VARIABLES.jQElements.myModal.find('.modal-header').append($('<button>')
                        .attr('type','button')
                        .addClass('close')
                        .attr('data-dismiss','modal')
                        .append($('<span>')
                            .attr('aria-hidden', true)
                            .append('&times;')));
                    VARIABLES.jQElements.myModal.find('.modal-footer').append($('<button>')
                        .attr('type','button')
                        .addClass('btn btn-default')
                        .attr('data-dismiss','modal')
                        .append($('<span>')
                            .addClass('glyphicon glyphicon-remove'))
                        .append(' Zamknij'));
                }
            };
            VARIABLES.DATA_TABLES.ratingCriterion.ajaxReload();
            VARIABLES.DATA_TABLES.ratingSystem.ajaxReload();
            FUNCTIONS.EVENT_HANDLERS.callEvents();
            resizeDatatablesOnMenuToggle([VARIABLES.DATA_TABLES.ratingCriterion.dataTable,VARIABLES.DATA_TABLES.ratingSystem.dataTable]);
        });
    </script>
@endsection
