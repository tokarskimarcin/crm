@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Pomoc / Moje zgłoszenia</div>
        </div>
    </div>
</div>

@if(Session::has('message_ok'))
    <div class="alert alert-success">
        {{Session::get('message_ok')}}
    </div>
@endif
<div class="panel panel-info">
    <div class="panel-heading">
        Moje wysłane zgłoszenia
    </div>
    <div class="panel-body">
        @if(isset($unratedNotifications) and $unratedNotifications > 0)
            <div class="alert alert-warning">
                Masz zakończone zgłoszenia, które nie są ocenione: {{$unratedNotifications}}
            </div>
        @endif
        <div class="table-responsive">
            <table id="datatable" class="table table-striped table-bordered thead-inverse" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="width: 20%;">Data:</th>
                        <th style="width: 40%;">Tytuł:</th>
                        <th style="width: 20%;">Stan realizacji</th>
                        <th style="width: 10%;">Szczegóły</th>
                        <th style="width: 5%;">Ocena</th>
                        <th style="width: 10%;">Akcja</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
    @if($notifications > 0)
        <div class="panel panel-info">
            <div class="panel-heading">
                Moje przyjęte zgłoszenia
            </div>
            <div class="panel-body">
                @if(isset($notRepairedNotifications) and $notRepairedNotifications > 0)
                    <div class="alert alert-warning">
                        Masz zgłoszenia w trakcie realizacji: {{$notRepairedNotifications}}
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="datatable2" class="table table-striped table-bordered thead-inverse" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th style="width: 10%;">Data:</th>
                            <th style="width: 20%;">Zgłoszony przez:</th>
                            <th style="width: 30%;">Tytuł:</th>
                            <th style="width: 20%;">Stan realizacji</th>
                            <th style="width: 10%;">Ocena</th>
                            <th style="width: 10%;">Podgląd</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="modalJudgeResult" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ocena</h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endif

@endsection
@section('script')
<script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
<script>
let table = $('#datatable').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "drawCallback": function( settings ) {
    },
    "ajax": {
        'url': "{{ route('api.datatableMyNotifications') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    },
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    order: [[0,'desc']]
    ,"columns":[
        {"data": "created_at"},
        {"data": "title"},
        {"data": function (data, type, dataToSet) {
            var status = data.status;
            if (status == 1) {
                return 'Zgłoszono';
            } else if (status == 2) {
                return 'Przyjęto do realizacji';
            } else if (status == 3) {
                return 'Zakończono';
            }
          }
        },
        {"data": function (data, type, dataToSet) {
              return '<a class="btn btn-default" href="view_notification/'+data.id+'" >Szczegóły</a>';
        },"orderable": false, "searchable": false },
        {"data": function (data, type, dataToSet) {
            var status = data.status;
            if (status != 3) {
                return '<a class="btn btn-default btn-block" href="#" data-toggle="tooltip" title="Ocenić wykonanie możesz po zakończonej realizacji!" data-placement="left" disabled>Oceń</a>';
            } else {
                if(data.judge_result == null){
                    return '<a class="btn btn-default btn-block" href="judge_notification/'+data.id+'" >Oceń</a>';
                }else{
                    return '<a class="btn btn-info btn-block" href="judge_notification/'+data.id+'" >Ocena</a>';
                }
            }

        },"orderable": false, "searchable": false },
        {"data": function (data, type, dataToSet) {
            var status = data.status;
                return '<a class="btn btn-danger delete_notification" data-id='+data.id+' >Usuń</a>';
        },"orderable": false, "searchable": false }
        ],"fnDrawCallback": function(settings) {

            $('.delete_notification').on('click', function (e) {
                let notification_id = $(this).attr('data-id');
                swal({
                    title: 'Jesteś pewien?',
                    text: "Spowoduje to usunięcie zgłoszenia!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Tak, usuń zgłoszenie'
                }).then((result) => {
                    if(result.value)
                    {
                        $.ajax({
                            type: "POST",
                            url: '{{ route('api.delete_notification') }}',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                'notification_id': notification_id
                            },
                            success: function (response) {
                                console.log(response);
                                if (response == 1) {
                                    swal(
                                        'Zgłoszenie zostało usunięte',
                                        'Zgłoszenie zostało usunięte',
                                        'success'
                                    );
                                    table.ajax.reload();
                                }else if(response == 2){
                                    swal(
                                        'Zgłoszenie może usunąć tylko osoba zgłaszająca.',
                                        'Zgłoszenie może usunąć tylko osoba zgłaszająca.',
                                        'error'
                                    );
                                }
                                else if(response == 0){
                                    swal(
                                        'Zgłoszenia nie można usunąć, ponieważ jest już w trakcie realizacji.',
                                        'Zgłoszenia nie można usunąć.',
                                        'error'
                                    );
                                }else{
                                    swal(
                                        'Problem skontaktuj się z admininstratorem.',
                                        'Problem skontaktuj się z admininstratorem.',
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            });
        },
});



let table2 = $('#datatable2').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "ajax": {
        'url': "{{ route('api.datatableMyHandledNotifications') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    },
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    order: [[0,'desc']]
    ,"columns":[
        {"data": "created_at"},
        {"data": "user_name"},
        {"data": "title"},
        {"data": function (data, type, dataToSet) {
                var status = data.status;
                if (status == 1) {
                    return 'Zgłoszono';
                } else if (status == 2) {
                    return 'Przyjęto do realizacji';
                } else if (status == 3) {
                    return 'Zakończono';
                }
            }
        },
        {"data": function (data, type, dataToSet) {
            let button = $(document.createElement('button')).addClass('judgeResultButton btn btn-block btn-primary');
            if(data.jr_id == null){
                button.append('Brak').attr('disabled',true);
            }else{
                let span = $(document.createElement('span'));
                if(data.comment !== 'Brak komentarza'){
                    span.addClass('glyphicon glyphicon-comment');
                }
                button.append(span).append(' '+data.judge_sum).attr('data-jrid',data.jr_id);
            }
            return button.prop('outerHTML');
            }, name: 'judgeResult',"orderable": false, "searchable": false},
        {"data": function (data, type, dataToSet) {
                return '<a class="btn btn-default  btn-block" href="show_notification/'+data.id+'" ><span class="glyphicon glyphicon-search"></span></a>';
            },"orderable": false, "searchable": false },
    ]
});

$('.panel-body').click(function (e) {
   if($(e.target).hasClass('judgeResultButton')){
       judgeResultButtonHandler(e);
   }

});
function judgeResultButtonHandler(e) {
    swal({
        title: 'Ładowawnie...',
        text: 'To może chwilę zająć',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        onOpen: () => {
            swal.showLoading();
            $.ajax({
                url: "{{ route('api.notificationJudgeResult') }}",
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: {
                    'judgeResultId': $(e.target).data('jrid')
                },
                success: function (response) {
                    if(response){
                        let modalBody = $('#modalJudgeResult .modal-body');
                        modalBody.text('');
                        modalBody.append(createJudgeResultModalBody(response.judgeResult));
                        $('#modalJudgeResult').modal('show');
                    }
                },
                error: function (jqXHR, textStatus, thrownError) {
                    console.log(jqXHR);
                    console.log('textStatus: ' + textStatus);
                    console.log('hrownError: ' + thrownError);
                    swal({
                        type: 'error',
                        title: 'Błąd ' + jqXHR.status,
                        text: 'Wystąpił błąd: ' + thrownError+' "'+jqXHR.responseJSON.message+'"',
                    });
                }
            }).then((response) => {
                swal.close();
            });
        }
    });


}

function createJudgeResultModalBody(judgeResult){
    console.log(judgeResult);

    let thead1 = $(document.createElement('thead'))
        .append($(document.createElement('tr'))
            .append($(document.createElement('th')).append('Kryterium'))
            .append($(document.createElement('th')).append('Ocena')));


    let tbody1 = $(document.createElement('tbody'))
        .append($(document.createElement('tr'))
            .append($(document.createElement('td')).append('Jakość wykonania'))
            .append($(document.createElement('td')).append(judgeResult.judge_quality)))

        .append($(document.createElement('tr'))
            .append($(document.createElement('td')).append('Kontakt z serwisantem'))
            .append($(document.createElement('td')).append(judgeResult.judge_contact)))

        .append($(document.createElement('tr'))
            .append($(document.createElement('td')).append('Czas wykonywania'))
            .append($(document.createElement('td')).append(judgeResult.judge_time)))

        .append($(document.createElement('tr'))
            .append($(document.createElement('th')).append('Ocena ogólna'))
            .append($(document.createElement('th')).append(judgeResult.judge_sum+'/6')));

    let table1 = $(document.createElement('table')).addClass('table table-striped table-bordered thead-inverse')
        .append(thead1).append(tbody1);
    let tablesCol1 = $(document.createElement('div')).addClass('col-md-6').append(table1);

    let thead2 = $(document.createElement('thead'))
        .append($(document.createElement('tr'))
            .append($(document.createElement('th')).append('Kryterium'))
            .append($(document.createElement('th')).append('Ocena')));


    let tbody2 = $(document.createElement('tbody'))
        .append($(document.createElement('tr'))
            .append($(document.createElement('td')).append('Problem naprawiony'))
            .append($(document.createElement('td')).css('background',judgeResult.repaired === 1 ? '#78ff80' : '#ff7878')
                .append(judgeResult.repaired === 1 ? 'TAK' : 'NIE')))

        .append($(document.createElement('tr'))
            .append($(document.createElement('td')).append('Kontakt technika po zakończeniu'))
            .append($(document.createElement('td')).css('background',judgeResult.response_after === 1 ? '#78ff80' : '#ff7878')
                .append(judgeResult.response_after === 1 ? 'TAK' : 'NIE')));

    let table2 = $(document.createElement('table')).addClass('table table-striped table-bordered thead-inverse')
        .append(thead2).append(tbody2);
    let tablesCol2 = $(document.createElement('div')).addClass('col-md-6').append(table2);

    let tablesRow = $(document.createElement('div')).addClass('row').append(tablesCol1).append(tablesCol2);

    let commentCol = $(document.createElement('div')).addClass('col-md-12')
        .append($(document.createElement('label')).append('Komentarz:'))
        .append($(document.createElement('div')).addClass('well well-sm').append(judgeResult.comment));
    let commentRow = $(document.createElement('div')).addClass('row').append(commentCol);
    return  $(document.createElement('div')).append(tablesRow).append($(document.createElement('hr'))).append(commentRow);

}
</script>
@endsection
