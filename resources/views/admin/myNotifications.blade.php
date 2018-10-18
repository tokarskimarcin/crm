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

        <div id="modalNotificationRating" class="modal fade" tabindex="-1" role="dialog">
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
                if(data.notification_rating == null){
                    return '<a class="btn btn-default btn-block" href="rateNotification/'+data.id+'" >Oceń</a>';
                }else{
                    return '<a class="btn btn-info btn-block" href="rateNotification/'+data.id+'" >Ocena</a>';
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
            let button = $(document.createElement('button')).addClass('notificationRatingButton btn btn-block btn-primary');
            if(data.nr_id == null){
                button.append('Brak').attr('disabled',true);
            }else{
                let span = $(document.createElement('span'));
                if(data.comment !== null){
                    span.addClass('glyphicon glyphicon-comment');
                }
                button.append(span).append(' '+parseInt(data.average_rating*100)+'%').attr('data-nrid',data.nr_id);
            }
            return button.prop('outerHTML');
            }, name: 'notificationRating',"orderable": false, "searchable": false},
        {"data": function (data, type, dataToSet) {
                return '<a class="btn btn-default  btn-block" href="show_notification/'+data.id+'" ><span class="glyphicon glyphicon-search"></span></a>';
            },"orderable": false, "searchable": false },
    ]
});

$('.panel-body').click(function (e) {
   if($(e.target).hasClass('notificationRatingButton')){
       notificationRatingButtonHandler(e);
   }

});
function notificationRatingButtonHandler(e) {
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
                url: "{{ route('api.notificationRating') }}",
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: {
                    'notificationRatingId': $(e.target).data('nrid')
                },
                success: function (response) {
                    if(response.hasOwnProperty('notificationRating') && response.hasOwnProperty('notificationRatingComponents') && response.hasOwnProperty('notificationRatingCriterion')){
                        let modalBody = $('#modalNotificationRating .modal-body');
                        modalBody.text('');
                        modalBody.append(createNotificationRatingModalBody(response));
                        $('#modalNotificationRating').modal('show');
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
function normalize(score, scoreRange, normalizeRange = [0,1]){
    return ((score-scoreRange[0])/(scoreRange[1] - scoreRange[0]))*(normalizeRange[1]-normalizeRange[0])+normalizeRange[0];
}

function createNotificationRatingModalBody(response){
    let thead1 = $(document.createElement('thead'))
        .append($(document.createElement('tr'))
            .append($(document.createElement('th')).append('Kryterium'))
            .append($('<th>').append('Wynik'))
            .append($(document.createElement('th')).append('Ocena')));


    let tbody1 = $(document.createElement('tbody'));

    $.each(response.notificationRatingComponents, function (componentIndex, component) {
        let notificationRatingCriterion = response.notificationRatingCriterion.find(x => x.id === component.notification_rating_criterion_id);
        let tr = $('<tr>')
            .append($('<td>').append(notificationRatingCriterion.criterion))
            .append($('<td>').append(Math.round(normalize(component.rating,
                [notificationRatingCriterion.rating_system.rating_start, notificationRatingCriterion.rating_system.rating_stop])*10000)/100).append('%'));
        if (notificationRatingCriterion.rating_system.id === 1) {
            tr.append($('<td>').append(component.rating === 1 ? 'NIE' : 'TAK').css({'background-color': component.rating === 1 ? 'rgba(255,0,0,0.75)' : 'rgba(0,175,0,0.75)'}))
        } else if (notificationRatingCriterion.rating_system.id === 3) {
            tr.append($('<td>').append(component.rating === 1 ? 'NIE' : component.rating === 2 ? 'ŚREDNIO' : 'TAK')
                .css({'background-color': component.rating === 1 ? 'rgba(255,0,0,0.75)' : component.rating === 2 ? 'rgba(0,0,255,0.75)' : 'rgba(0,175,0,0.75)'}))
        } else {
            tr.append($('<td>').append(component.rating));
        }
        tbody1.append(tr);
    });
    tbody1.append( $('<tr>').css({'font-weight':'bold','color':'white','background-color':'#696969'})
        .append($('<td>').append('Suma:'))
        .append($('<td>').append(Math.round(response.notificationRating.average_rating*10000)/100).append('%')));
    let table1 = $(document.createElement('table')).addClass('table table-striped table-bordered thead-inverse')
        .append(thead1).append(tbody1);
    let tablesCol1 = $(document.createElement('div')).addClass('col-md-12').append(table1);


    let tablesRow = $(document.createElement('div')).addClass('row').append(tablesCol1);

    let commentCol = $(document.createElement('div')).addClass('col-md-12')
        .append($(document.createElement('label')).append('Komentarz:'))
        .append($(document.createElement('div')).addClass('well well-sm').append(response.notificationRating.comment));
    let commentRow = $(document.createElement('div')).addClass('row').append(commentCol);

    let append = $(document.createElement('div')).append(tablesRow);
    if(response.notificationRating.comment !== null){
        append.append($(document.createElement('hr'))).append(commentRow);
    }
    return append;

}
</script>
@endsection
