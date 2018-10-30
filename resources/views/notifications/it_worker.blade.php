@extends('layouts.main')
@section('content')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Pomoc / Profil pracownika IT</div>
        </div>
    </div>
</div>
@php
    $polish_month = array( '', 'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień' );
    $data = $user_results[0];
    $avg_grade = 0;
    $count_grade  = 0;
    $dataPoints = array(
    round($data->user_judge_sum, 2),
    round($data->user_quality, 2),
    round($data->user_contact, 2),
    round($data->user_time, 2)
    );
@endphp
<?php

?>
<div class="row">
  {{--<div class="col-md-6">
    <div id="dual_x_div" style="width: 400px; height: 400px;"></div>
  </div>--}}
  <div class="col-md-12">
      <div class="panel panel-green" style="height: 100%">
          <div class="panel-heading"><h3>{{$user_data->first_name . ' ' . $user_data->last_name}}</h3></div>
          <div class="panel-body" id="userDataPanelBody">
          </div>
      </div>
  </div>
</div>
<hr>
{{--<div class="row">--}}
  {{--<div class="col-md-12">--}}
      {{--<div class="panel panel-default">--}}
          {{--<div class="panel-heading">--}}
              {{--Komentarze--}}
          {{--</div>--}}
          {{--<div class="panel-body">--}}
            {{--<div class="list-group">--}}
              {{--@foreach($comments as $comment)--}}
                  {{--<div class="list-group-item"><p>{{$comment->comment}}</p><small>Dodał: {{$comment->first_name . ' ' . $comment->last_name . ' ' . $comment->add_time}}</small></div>--}}
              {{--@endforeach--}}
              {{--@if($comments->count() == 0)--}}
                  {{--<div class="list-group-item"><p>Brak komentarzy!</p></div>--}}
              {{--@endif--}}
            {{--</div>--}}
          {{--</div>--}}
      {{--</div>--}}
  {{--</div>--}}
{{--</div>--}}


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Historia zgłoszeń
            </div>
            <div class="panel-body">
                <div class="table_of_conntent">
                    <div id="menu1" class="tab-pane fade in active">
                        <div class="table-responsive" style="margin-top: 30px;">
                            <table id="history_of_notification" class="table table-striped table-bordered thead-inverse" cellspacing="0" width="100%" >
                                <thead>
                                <tr>
                                    <td style="width: 15%">Data złoszenia</td>
                                    <td style="width: 15%">Data przyjęcia zgłoszenia</td>
                                    <td style="width: 15%">Data zakończenia zgłoszenia</td>
                                    <td style="width: 10%">Zgłoszone przez</td>
                                    <td style="width: 10%">Ocena końcowa</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($story_of_problem as $item)
                                    <tr>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->data_start}}</td>
                                        @if($item->data_stop == null)
                                            <td>W trakcie wykonywania</td>
                                        @else
                                            <td>{{$item->data_stop}}</td>
                                        @endif
                                        <td>{{$item->first_name.' '.$item->last_name}}</td>
                                        @if($item->nr_id == null)
                                            <td><button class="notificationRatingButton btn btn-block btn-primary" disabled="disabled">Brak</button></td>
                                        @else
                                            <td><button class="notificationRatingButton btn btn-block btn-primary" data-nrid="{{$item->nr_id}}">@if($item->comment !== null)<span class="glyphicon glyphicon-comment"></span> @endif{{($item->average_rating*100).'%'}}</button></td>
                                        @endif
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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

@endsection
@section('script')
    <script src="{{ asset('/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/dataTables.select.min.js')}}"></script>
<script>
/*google.charts.load('current', {'packages':['bar']});
google.charts.setOnLoadCallback(drawStuff);*/

let userDataPanelBody = $('#userDataPanelBody');

$(document).ready( function () {
    var table = $('#history_of_notification').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
        },
        "order": [[ 0, "desc" ]]
    });
});
function drawStuff() {
  var data = google.visualization.arrayToDataTable([
   ['', 'Ocena', { role: 'style' }],
   ['Suma', {{$dataPoints[0]}} + "/6", 'gray'],            // RGB value
   ['Jakość', {{$dataPoints[1]}} + "/6", 'silver'],            // English color name
   ['Kontakt', {{$dataPoints[2]}} + "/6", 'gold'],
   ['Czas wykonania', {{$dataPoints[3]}} + "/6", 'color: gray' ], // CSS-style declaration
]);
  var month = '{{$polish_month[intval (date('m'))]}}';
  var options = {
    width: 500,
    chart: {
      title: "Średnia ocena użytkownika "+month,
      subtitle: 'Ocena w skali od 0 do 6'
    },
    bars: 'horizontal', // Required for Material Bar Charts.
    axes: {
      x: {
        distance: {label: 'parsecs'}, // Bottom x-axis.
        brightness: {side: 'top', label: 'apparent magnitude'} // Top x-axis.
      }
    }
  };

var chart = new google.charts.Bar(document.getElementById('dual_x_div'));
chart.draw(data, options);
}

$('.panel-body').click(function (e) {
    if($(e.target).hasClass('notificationRatingButton')){
        notificationRatingButtonHandler(e);
    }

});


function normalize(score, scoreRange, normalizeRange = [0,1]){
    return ((score-scoreRange[0])/(scoreRange[1] - scoreRange[0]))*(normalizeRange[1]-normalizeRange[0])+normalizeRange[0];
}

function iTCadreNotificationsRatingsStatisticsAjax() {
    return $.ajax({
        url: '{{route('api.iTCadreNotificationsRatingsStatisticsAjax')}}',
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        data: {
            noPeriod: true,
            userId: '{{$user_data->id}}'
        },
        success(response) {
            return response;
        },
        error(jqXHR, textStatus, thrownError) {
            console.log(jqXHR);
            console.log('textStatus: ' + textStatus);
            console.log('thrownError: ' + thrownError);
            swal({
                type: 'error',
                title: 'Błąd ' + jqXHR.status,
                text: 'Wystąpił błąd: ' + thrownError + ' "' + jqXHR.responseJSON.message + '"',
            });
        }
    });
}

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
                .css({'background-color': component.rating === 1 ? 'rgba(255,0,0,0.75)' : component.rating === 2 ? 'rgba(0,0,255,0.50)' : 'rgba(0,175,0,0.75)'}))
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

function secondsToDaysHoursMinutesSeconds(averageRealizationTime){
    let minutes = 60;
    let hours = minutes*60;
    let days = hours*24;
    let averageRealizationTimeString = '';
    let diff = Math.floor(averageRealizationTime/days);
    averageRealizationTimeString += diff+'D ';
    averageRealizationTime -= diff*days;
    diff = Math.floor(averageRealizationTime/hours);
    averageRealizationTimeString += (diff < 10 ? '0'+diff : diff)+':';
    averageRealizationTime -= diff*hours;
    diff = Math.floor(averageRealizationTime/minutes);
    averageRealizationTimeString += (diff < 10 ? '0'+diff : diff)+':';
    averageRealizationTime -= diff*minutes;
    averageRealizationTimeString += (averageRealizationTime < 10 ? '0'+averageRealizationTime : averageRealizationTime);
    return averageRealizationTimeString;
}

iTCadreNotificationsRatingsStatisticsAjax().then(function (response) {
    userDataPanelBody.append($('<ul>').addClass('list-group')
        .append($('<li>').addClass('list-group-item')
            .append($('<strong>').append('Średnia ocen: '))
            .append(response[0].averageRating == null ? '-' : (Math.round(response[0].averageRating*10000)/100)+'%'))
        .append($('<li>').addClass('list-group-item')
            .append($('<strong>').append('Średni czas realizacji: '))
            .append(secondsToDaysHoursMinutesSeconds(response[0].averageRealizationTime)))
        .append($('<li>').addClass('list-group-item')
            .append($('<strong>').append('Średni czas reakcji: '))
            .append(secondsToDaysHoursMinutesSeconds(response[0].averageReactionTime)))
    );
});
</script>
@endsection
