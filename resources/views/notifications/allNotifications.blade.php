@extends('layouts.main')
@section('content')

<style>
    .table-striped tr td:first-child + td {
        word-break: break-all;
    }
    a{
        cursor: pointer;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Pomoc / <span id="page_title">Nowe zgłoszenia</span></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
      <ul class="nav nav-tabs">
        <li class="menu_item active" id="menu_new_notifications" data-type="1"><a >Zgłoszone</a></li>
        <li class="menu_item" id="menu_in_progress"><a  data-type="2">Przyjęte do realizacji</a></li>
        <li class="menu_item" id="menu_finished" data-type="3"><a >Zakończone</a></li>
      </ul>
        <br />
        <div class="col-md-12">
          <div class="table-responsive" id="div_new_notifications">
              <table id="new_notifications" class="table compact table-striped table-bordered thead-inverse" cellspacing="0" width="100%" >
                <thead>
                  <tr>
                      <td>ID zgłoszenia</td>
                      <td>Tytuł</td>
                      <td>Data dodania</td>
                      <td>Oddział</td>
                      <td>Użytkownik</td>
                      <td>Akcja</td>
                  </tr>
              </thead>
              <tbody>

              </tbody>
              </table>
          </div>
          <div class="table-responsive" style="display: none" id="div_in_progress">
              <table id="in_progress" class="table compact table-striped table-bordered thead-inverse" cellspacing="0" width="100%" >
                <thead>
                  <tr>
                      <td>ID zgłoszenia</td>
                      <td>Tytuł</td>
                      <td>Data dodania</td>
                      <td>Oddział</td>
                      <td>Użytkownik</td>
                      <td>Data przyjęcia</td>
                      <td>Os. przyjmująca</td>
                      <td>Akcja</td>
                  </tr>
              </thead>
              <tbody>

              </tbody>
              </table>
          </div>
          <div class="table-responsive" style="display: none" id="div_finished">
              <table id="finished" class="table compact table-striped table-bordered thead-inverse" cellspacing="0" width="100%" >
                <thead>
                  <tr>
                      <td>ID zgłoszenia</td>
                      <td>Tytuł</td>
                      <td>Data dodania</td>
                      <td>Oddział</td>
                      <td>Użytkownik</td>
                      <td>Data realizacji</td>
                      <td>Os. realizująca</td>
                      <td>Akcja</td>
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
<script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{ asset('/js/dataTables.select.min.js')}}"></script>
<script>
let authUser = <?php echo json_encode(Auth::user()->toArray())?>;

$('.menu_item').on('click', function(){
    var id = this.id;

    function deletePrevious() {
        $("#menu_new_notifications, #menu_in_progress, #menu_finished").removeClass('active');
        $("#div_new_notifications, #div_in_progress, #div_finished").fadeOut(0);
    }

    if(id == "menu_new_notifications") {
        deletePrevious();
        $("#menu_new_notifications").addClass('active');
        $("#div_new_notifications").fadeIn(0);
        $('#page_title').text('Nowe zgłoszenia');
    }

    if(id == "menu_in_progress") {
        deletePrevious();
        $("#menu_in_progress").addClass('active');
        $("#div_in_progress").fadeIn(0);
        $('#page_title').text('Przyjęte zgłoszenia');
    }

    if(id == "menu_finished") {
        deletePrevious();
        $("#menu_finished").addClass('active');
        $("#div_finished").fadeIn(0);
        $('#page_title').text('Zrealizowane zgłoszenia');
    }

});

table = $('#new_notifications').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
        $('.removeNotification').click(function (e) {
            removeNotificationButtonHandler(e);
        });
    },
    "ajax": {
        'url': "{{ route('api.datatableShowNewNotifications') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {
        }
    },
    "order": [[ 2, "desc" ]],
    "columns": [
        {"data": 'notification_id'},
        {"data": 'title'},
        {"data": 'created_at'},
        {"data": function (data, type, dataToSet) {
              return data.dep_name + " " + data.dep_name_type;
        }, "name": "dep_name"},
        {"data": function (data, type, dataToSet) {
              return data.first_name + " " + data.last_name;
        }, "name": "last_name"},
        {"data": function (data, type, dataToSet) {
            let div = $('<div>').append("<a class='btn btn-default btn-block' href={{URL::to('/show_notification/')}}/" + data.notification_id + ">Pokaż</a>");
            if(authUser.user_type_id === 3){
                div.append($('<button>').addClass('removeNotification btn btn-danger btn-block').attr('data-notification-id',data.notification_id).append('Usuń'));
            }
            return div.prop('outerHTML');
        }, "name": "id_user", orderable: false},

    ],

});

table2 = $('#in_progress').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
        $('.removeNotificationInProgress').click(function (e) {
            removeNotificationButtonHandler(e);
        });
    },
    "ajax": {
        'url': "{{ route('api.datatableShowInProgressNotifications') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {
        }
    },
    "order": [[ 5, "desc" ]],
    "columns": [
        {"data": 'notification_id'},
        {"data": 'title'},
        {"data": 'created_at'},
        {"data": function (data, type, dataToSet) {
              return data.dep_name + " " + data.dep_name_type;
        }, "name": "dep_name"},
        {"data": function (data, type, dataToSet) {
              return data.first_name + " " + data.last_name;
        }, "name":  "last_name"},
        {"data": 'data_start'},
        {"data": 'displayedBy'},
        {"data": function (data, type, dataToSet) {
                let div = $('<div>').append("<a class='btn btn-default btn-block' href={{URL::to('/show_notification/')}}/" + data.notification_id + ">Pokaż</a>");
                if(authUser.user_type_id === 3){
                    div.append($('<button>').addClass('removeNotificationInProgress btn btn-danger btn-block').attr('data-notification-id',data.notification_id).append('Usuń'));
                }
                return div.prop('outerHTML');
        }, "name": "id_user", orderable: false},

    ],

});

table3 = $('#finished').DataTable({
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
    },
    "drawCallback": function (settings) {
        $('.removeNotificationExecuted').click(function (e) {
            removeNotificationButtonHandler(e);
        });
    },
    "ajax": {
        'url': "{{ route('api.datatableShowFinishedNotifications') }}",
        'type': 'POST',
        'headers': {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        'data': function (d) {
        }
    },
    "order": [[ 5, "desc" ]],
    "columns": [
        {"data": 'notification_id'},
        {"data": 'title'},
        {"data": 'created_at'},
        {"data": function (data, type, dataToSet) {
              return data.dep_name + " " + data.dep_name_type;
        }, "name": "dep_name"},
        {"data": function (data, type, dataToSet) {
              return data.first_name + " " + data.last_name;
        }, "name":  "last_name"},
        {"data": 'data_stop'},
        {"data": 'displayedBy'},
        {"data": function (data, type, dataToSet) {
                let div = $('<div>').append("<a class='btn btn-default btn-block' href={{URL::to('/show_notification/')}}/" + data.notification_id + ">Pokaż</a>");
                if(authUser.user_type_id === 3){
                    div.append($('<button>').addClass('removeNotificationExecuted btn btn-danger btn-block').attr('data-notification-id',data.notification_id).append('Usuń'));
                }
                return div.prop('outerHTML');
        }, "name": "id_user", orderable: false},

    ],

});

function removeNotificationButtonHandler(e) {
    let notification_id = $(e.target).data('notification-id');
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
                    if (response == 1) {
                        swal(
                            'Zgłoszenie zostało usunięte',
                            'Zgłoszenie zostało usunięte',
                            'success'
                        );
                        table.ajax.reload();
                        table2.ajax.reload();
                        table3.ajax.reload();
                    }
                    else if(response == 0){
                        swal(
                            'Zgłoszenia nie można usunąć, ponieważ jest już w trakcie realizacji oraz jesteś osobą zgłaszającą.',
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
}
</script>
@endsection
