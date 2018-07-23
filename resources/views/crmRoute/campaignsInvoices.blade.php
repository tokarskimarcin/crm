{{--/*--}}
{{--*@category: CRM --}}
{{--*@info: This view shows invoices of selected or all campaigns--}}
{{--*@controller: CrmRouteController --}}
{{--*@methods: getCampaignsInvoices, getCampaignsInvoicesDatatableAjax, uploadCampaignInvoiceAjax, getHotelContacts --}}
{{--*/--}}

@extends('layouts.main')
@section('style')
@endsection
@section('content')
    <div class="page-header">
        <div class="alert gray-nav ">Lista faktur @if($routeId != 0 ) @if($client != null)
                - {{$client->route_name}}@endif @endif</div>

    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Faktury poszczególnych kampanii
        </div>
        <div class="panel-body">
            @if($routeId == 0)
                <div class="row">
                    <div class="col-md-5">
                        <label class="">Klilent</label>
                        <select id="clientSelect" class="form-control">
                            <option value="0">Wybierz</option>
                            @if(isset($clients) and $clients !== null)
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="">Status</label>
                        <select id="invoiceStatusSelect" class="form-control">
                            <option value="0">Wybierz</option>
                            @if(isset($invoiceStatuses) and $invoiceStatuses !== null)
                                @foreach($invoiceStatuses as $invoiceStatus)
                                    <option value="{{$invoiceStatus->id}}">{{$invoiceStatus->name_pl}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="">Od</label>
                        <input id="firstDate" type="date" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="">Do</label>
                        <input id="lastDate" type="date" class="form-control">
                    </div>
                </div>
            @endif
            <div class="row" style="margin-top: 1em">
                <div class="col-md-12">
                    <table id="invoicesDatatable" class="thead-inverse table table-striped row-border">
                        <thead>
                        <tr>
                            <th>Klient</th>
                            <th>Trasa</th>
                            <th>Hotel</th>
                            <th>Data pokazu</th>
                            <th>Faktura</th>
                            <th>Status</th>
                            <th>Kara</th>
                            <th>Data wysłania faktury</th>
                            <th>Data opłacenia faktury</th>
                            <th>Akcja</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(() => {
            let clientSelect = $('#clientSelect');
            let invoiceStatusSelect = $('#invoiceStatusSelect');
            let firstDate = $('#firstDate');
            let lastDate = $('#lastDate');
            @if(isset($routeId))
                @if($routeId == 0)
                    firstDate.val('{{$firstDate}}');
                    lastDate.val('{{$lastDate}}');
                @endif
            @endif

            let invoiceDatatable = $('#invoicesDatatable').DataTable({
                autoWidth: true,
                processing: true,
                serverSide: true,
                scrollCollapse: true,
                scrollY: '45vh',
                scrollX: true,
                ajax: {
                    url: "{{route('api.getCampaignsInvoicesDatatableAjax')}}",
                    type: 'POST',
                    data: function (data) {
                        data.routeId = "{{$routeId}}";
                        data.clientId = clientSelect.val();
                        data.invoiceStatusId = invoiceStatusSelect.val();
                        data.firstDate = firstDate.val();
                        data.lastDate = lastDate.val();
                    },
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                },
                language: {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
                },
                rowCallback: function( row, data ) {
                    if(data.penalty < 0){
                        $(row).css('background','#c500002e');
                    }
                },
                columns: [
                    {
                        data: function (data, type, val) {
                            return data.client_name
                        }, name: 'client_name'
                    },
                    {
                        data: function (data, type, val) {
                            return data.route_name
                        }, name: 'route_name'
                    },
                    {
                        data: function (data, type, val) {
                            return data.hotel_name
                        }, name: 'hotel_name'
                    },
                    {
                        data: function (data, type, val) {
                            return data.date
                        }, name: 'date'
                    },
                    {
                        data: function (data, type, val) {
                            let invoiceSpan = $(document.createElement('span')).addClass('glyphicon glyphicon-search');
                            let invoiceButton = $(document.createElement('button')).addClass('btn btn-default btn-block').append(invoiceSpan);
                            if (data.invoice_path === null || data.invoice_path === '') {
                                invoiceButton.prop('disabled', true);
                            }
                            return invoiceButton.prop('outerHTML');
                        }, name: 'invoice'
                    },
                    {
                        data: function (data, type, val) {
                            let divStatus = $(document.createElement('div')).text(data.name_pl);
                            let color = '';
                            if (data.invoice_status_id == 1) {
                                color = '#d9534f';
                                divStatus.css('color', 'white');
                            }
                            if (data.invoice_status_id == 2) {
                                color = '#f0ad4e';
                                divStatus.css('color', 'white');
                            }
                            if (data.invoice_status_id == 3) {
                                color = '#5bc0de';
                                divStatus.css('color', 'white');
                            }
                            if (data.invoice_status_id == 4) {
                                color = '#5bc0de';
                                divStatus.css('color', 'white');
                            }
                            divStatus.css('background-color', color);
                            divStatus.css('padding', '10px 3px');
                            divStatus.css('border-radius', '10px');
                            divStatus.css('font-weight', 'bold');

                            return divStatus.prop('outerHTML');
                        }, name: 'invoice_status_id'
                    },
                    {
                        data: function (data, type, val) {
                            let divPenalty = $(document.createElement('div')).text(data.penalty + ' zł');
                            if(data.penalty < 0 ){
                                divPenalty.css('color','red');
                                divPenalty.css('font-weight', 'bold');
                            }
                            return divPenalty.prop('outerHTML');
                        }, name: 'penalty'
                    },
                    {
                        data: function (data, type, val) {
                            return data.invoice_send_date
                        }, name: 'invoice_send_date'
                    },
                    {
                        data: function (data, type, val) {
                            return data.invoice_payment_date
                        }, name: 'invoice_payment_date'
                    },
                    {
                        data: function (data, type, val) {

                            let actionSpan = $(document.createElement('span'));
                            let actionButton = $(document.createElement('button'))
                                .attr('data-campaign_id', data.id)
                                .attr('data-hotel_id', data.hotel_id)
                                .prop('type', 'button').addClass('btn btn-block');//.attr('data-toggle', 'modal').attr('data-target', '#myModal');
                            if (data.invoice_status_id == 1) {
                                actionButton.addClass('btn-danger');
                                actionButton.attr('id', 'uploadInvoice');
                                actionButton.text(' Wrzuć fakturę');
                                actionSpan.addClass('glyphicon glyphicon-export');
                            }
                            if (data.invoice_status_id == 2) {
                                actionButton.addClass('btn-warning');
                                actionButton.attr('id', 'sendInvoice');
                                actionButton.text(' Wyślij fakturę');
                                actionSpan.addClass('glyphicon glyphicon-envelope');
                            }
                            if (data.invoice_status_id == 3) {
                                actionButton.addClass('btn-success');
                                actionButton.attr('id', 'confirmPayment');
                                actionSpan.addClass('glyphicon glyphicon-unchecked');
                                actionButton.text(' Potwierdź zapłatę');
                            }
                            if (data.invoice_status_id == 4) {
                                actionButton.addClass('btn-default');
                                actionButton.attr('id', 'noAction');
                                actionSpan.addClass('glyphicon glyphicon-check');
                                actionButton.text('Opłacone');
                                actionButton.prop('disabled', true);
                            }
                            return actionButton.prepend(actionSpan).prop('outerHTML');
                        }, orderable: false, searchable: false, name: 'action'
                    }
                ],
                    fnDrawCallback: function () {

                    //handle uploadInvoiceButton
                        $('#uploadInvoice').click(function (e) {
                            let modalContent = $('#' + modalIdString + ' .modal-content');
                            clearModalContent(modalContent);

                            //--------- create modal content -------------//
                            let inputLabel = $(document.createElement('label')).text('Wybierz fakturę');
                            let input = $(document.createElement('input')).addClass('form-control').prop('type', 'file').attr('name', 'campaign_invoice').attr('id', 'invoiceInput').css('padding-bottom', '3em');
                            let inputColumn = $(document.createElement('div')).addClass('col-md-12').append(inputLabel).append(input);
                            let uploadSpan = $(document.createElement('span')).addClass('glyphicon glyphicon-export');
                            let uploadButton = $(document.createElement('button')).addClass('btn btn-block btn-info')
                                .click(function () {
                                    handleUploadInvoiceButtonClick($(e.target).data('campaign_id'));
                                })
                                .prop('type', 'button').text(' Wrzuć fakturę').prepend(uploadSpan);
                            let uploadColumn = $(document.createElement('div')).addClass('col-md-12').append(uploadButton);
                            let row1 = $(document.createElement('div')).addClass('row').append(inputColumn);
                            let row2 = $(document.createElement('div')).addClass('row').append(uploadColumn).css('margin-top', '1em');
                            let modalTitle = $(document.createElement('h4')).addClass('modal-titlel').text('Wrzucanie faktury na serwer');
                            let modalHeader = $(document.createElement('div')).addClass('modal-header').append(modalTitle);
                            let modalBody = $(document.createElement('div')).addClass('modal-body').append(row1).append(row2);
                            modalContent.prepend(modalBody).prepend( modalHeader);
                            //--------- end creating modal content -------------//

                            //handle file input
                            input.change(function (e) {
                                //on change check file extensions if is valid
                                let allowedExtensions = <?php echo $validCampaignInvoiceExtensions ?>;
                                if (allowedExtensions.indexOf(getFileExtension($(e.target).prop('files')[0].name)) === -1) {
                                    $(e.target).val('');
                                    swal({
                                        title: 'Zły format pliku',
                                        text: 'Dostępne formaty: ' + allowedExtensions.toString(),
                                        type: 'warning'
                                    });
                                }
                            });

                            myModal.modal('show');
                        });

                        //handle sendInvoiceButton
                        $('#sendInvoice').click(function (e) {
                            $.ajax({

                            }).done((response)=>{

                            });
                            let modalContent = $('#' + modalIdString + ' .modal-content');
                            clearModalContent(modalContent);

                            //--------- create modal content -------------//
                            let sendSpan = $(document.createElement('span')).addClass('glyphicon glyphicon-envelope');
                            let sendButton = $(document.createElement('button')).addClass('btn btn-block btn-info')
                                .click(function () {
                                    handleSendInvoiceButtonClick(e);
                                })
                                .prop('type', 'button').text(' Wyślij fakturę').prepend(sendSpan);
                            let sendColumn = $(document.createElement('div')).addClass('col-md-12').append(sendButton);
                            let row1 = $(document.createElement('div')).addClass('row').append();
                            let row2 = $(document.createElement('div')).addClass('row').append(sendColumn).css('margin-top', '1em');
                            let modalTitle = $(document.createElement('h4')).addClass('modal-titlel').text('Wysyłanie faktury mailem do hotelu');
                            let modalHeader = $(document.createElement('div')).addClass('modal-header').append(modalTitle);
                            let modalBody = $(document.createElement('div')).addClass('modal-body').append(row1).append(row2);
                            modalContent.prepend(modalBody).prepend(modalHeader);
                            //--------- end creating modal content -------------//

                            myModal.modal('show');
                        });
                    }
            });

            clientSelect.change(function () {
                invoiceDatatable.ajax.reload();
            });
            invoiceStatusSelect.change(function () {
                invoiceDatatable.ajax.reload();
            });
            firstDate.change(function () {
                invoiceDatatable.ajax.reload();
            });
            lastDate.change(function () {
                invoiceDatatable.ajax.reload();
            });

            //method gets value of file input to upload file to server
            function handleUploadInvoiceButtonClick(campaignId) {
                let invoiceInput = $('#invoiceInput').val();
                let valid = true;
                if (invoiceInput === '' || invoiceInput === null) {
                    swal('Wybierz fakturę z dysku');
                    valid = false;
                }
                if (valid) {
                    swal({
                        title: 'Czy na pewno?',
                        text: 'Czy chcesz wysłać wybraną fakturę na serwer?',
                        confirmButtonText: 'Tak, wyślij!',
                        showCancelButton: true,
                        type: 'warning'
                    }).then((result) => {
                        if (result.value) {
                            myModal.modal('hide');
                            let invoiceFileInput = $('#invoiceInput');
                            let formData = new FormData();
                            let uploadFiles = false;

                            //if file input has chosen files
                            if (invoiceFileInput.prop("files").length !== 0) {
                                let fileNames = [];
                                formData.append(invoiceFileInput.prop('name'), invoiceFileInput.prop("files")[0]);
                                fileNames.push(invoiceFileInput.prop('name'));
                                uploadFiles = true;

                                console.log(campaignId);
                                formData.append('fileNames', JSON.stringify(fileNames));
                                formData.append('campaignId', campaignId.toString());
                            }
                            if (uploadFiles)
                                uploadFilesAjax(formData);
                        }
                    });
                }
            }

            function handleSendInvoiceButtonClick(e) {
                myModal.modal('hide');
            }

            function handleConfirmPaymentButtonClick(e) {
            }

            let myModal = createModal(modalIdString);
            $('.panel.panel-default').append(myModal);

            resizeDatatablesOnMenuToggle([invoiceDatatable]);
        });

        let modalIdString = 'myModal';

        function createModal(modalIdString) {
            let modalHeader = $(document.createElement('div')).addClass('modal-header');
            let modaBody = $(document.createElement('div')).addClass('modal-body');
            let modalContent = $(document.createElement('div')).addClass('modal-content');
            clearModalContent(modalContent);
            modalContent.prepend(modaBody).prepend(modalHeader);
            let modalDialog = $(document.createElement('div')).addClass('modal-dialog').append(modalContent);
            return $(document.createElement('div')).addClass('modal fade').attr('id', modalIdString).attr('role', 'dialog').append(modalDialog);
        }

        function clearModalContent(modalContent) {
            let modalCloseButton = $(document.createElement('button')).addClass('btn btn-default').prop('type', 'button').attr('data-dismiss', 'modal').text('Zamknij');
            let modalFooter = $(document.createElement('div')).addClass('modal-footer').append(modalCloseButton);
            modalContent.text('').append(modalFooter);
        }
        function replaceModalContent(modalIdString, modalHeader, modalBody) {
            modalContent.prepend(modalBody).prepend(modalHeader);
        }

        function getFileExtension(fname) {
            return fname.slice((fname.lastIndexOf(".") - 1 >>> 0) + 2);
        }

        function uploadFilesAjax(formData) {
            swal({
                title: 'Wysyłanie pliku...',
                text: 'To może chwilę potrwać',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                onOpen: () => {
                    swal.showLoading();
                    $.ajax({
                        type: "POST",
                        url: "{{route('api.uploadCampaignInvoiceAjax')}}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: false,
                        processData: false,
                        data: formData
                    }).done(function (response) {
                        swal.close();
                        if (response === 'success') {
                            $.notify({
                                icon: 'glyphicon glyphicon-ok',
                                message: 'Wysłano fakturę na serwer'
                            }, {
                                type: "success"
                            });
                        } else if (response === 'fail') {
                            $.notify({
                                icon: 'glyphicon glyphicon-remove',
                                message: 'Nie udało się wysłać faktury na serwer'
                            }, {
                                type: "danger"
                            });
                        }
                    }).error(function (jqXHR, textStatus, thrownError) {
                        swal.close();
                        console.log(jqXHR);
                        console.log('textStatus: ' + textStatus);
                        console.log('hrownError: ' + thrownError);
                        swal({
                            type: 'error',
                            title: 'Błąd ' + jqXHR.status,
                            text: 'Wystąpił błąd: ' + thrownError + ' "' + jqXHR.responseJSON.message + '"',
                        });
                    });
                }
            });
        }
    </script>
@endsection
