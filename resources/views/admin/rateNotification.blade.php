@extends('layouts.main')
@section('content')
<style type="text/css">
      .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
      }
      .btn-circle.btn-lg {
        width: 50px;
        height: 50px;
        padding: 13px 13px;
        font-size: 18px;
        line-height: 1.33;
        border-radius: 25px;
      }
      .selected-span {
        font-size: 30px;
        margin-left: 10px;
      }
      .btn {
         outline: none !important;
         box-shadow: none !important;
      }

</style>

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Pomoc / Ocena wykonania zgłoszenia</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>ID zgłoszenia {{$notification->id}}</b>
            </div>
            <div class="panel-body">
                <p><b>Tytuł:</b></p>
                <p>{{$notification->title}}</p>
                <hr>
                <p><b>Treść:</b></p>
                <p>{{$notification->content}}</p>
                <hr>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Kryteria oceny</b>
            </div>
            <div class="panel-body">
@if($notificationRating == null)
    @foreach($notificationRatingCriterion as $ratingCriterion)
        <div class="row">
            <div class="col-md-12">
                <div class="ratingCriterion" data-id="{{$ratingCriterion->id}}" data-system-id="{{$ratingCriterion->rating_system->id}}">
                    <h3>{{$ratingCriterion->criterion}}</h3>
                    @if($ratingCriterion->rating_system->id == 3)
                        @include('admin.ratingSystems.NoAverageYes',['radioName'=> 'NoAverageYes'.$ratingCriterion->id])
                    @endif
                    @if($ratingCriterion->rating_system->id == 2)
                        @include('admin.ratingSystems.OneToSix', ['radioName'=> 'OneToSix'.$ratingCriterion->id])
                    @endif
                    @if($ratingCriterion->rating_system->id == 1)
                        @include('admin.ratingSystems.NoYes', ['radioName'=> 'NoYes'.$ratingCriterion->id])
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@else
    @foreach($notificationRating->rating_component as $rating_component)
        @php
            $ratingCriterion = $notificationRatingCriterion->where('id',$rating_component->notification_rating_criterion_id)->first();
        @endphp
            <div class="row">
                <div class="col-md-12">
                    <div class="ratingCriterion" data-id="{{$ratingCriterion->id}}" data-system-id="{{$ratingCriterion->rating_system->id}}">
                        <h3>{{$ratingCriterion->criterion}}</h3>
                        @if($ratingCriterion->rating_system->id == 3)
                            @include('admin.ratingSystems.NoAverageYes',['radioName'=> 'NoAverageYes'.$ratingCriterion->id , 'rating' => $rating_component->rating])
                        @endif
                        @if($ratingCriterion->rating_system->id == 2)
                            @include('admin.ratingSystems.OneToSix', ['radioName'=> 'OneToSix'.$ratingCriterion->id , 'rating' => $rating_component->rating])
                        @endif
                        @if($ratingCriterion->rating_system->id == 1)
                            @include('admin.ratingSystems.NoYes', ['radioName'=> 'NoYes'.$ratingCriterion->id , 'rating' => $rating_component->rating])
                        @endif
                    </div>
                </div>
            </div>
    @endforeach
@endif
        @if($notificationRating == null)
            <div class="row">
                <div class="col-md-12">
                    <label style="width: 100%">
                        Komentarz (opcjonalnie):
                    <textarea id="comment" style="width: 100%; min-height: 5em; max-height: 100%; resize: vertical" ></textarea>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <form id="rateForm" action="{{url('/rateNotificationPost')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="ajaxResponse" name="response" value="">
                        <button id="rateButton" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-list"></span><br>Oceń</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="{{url('/my_notifications')}}">
                        <button class="btn btn-block btn-default"><span class="glyphicon glyphicon-log-out"></span><br>Cofnij</button>
                    </form>
                </div>
            </div>
        @else
        <div class="row">
            <div class="col-md-12">
                <label style="width: 100%">
                    Komentarz:
                <textarea id="comment" style="width: 100%; min-height: 5em; max-height: 100%; resize: vertical" readonly>{{$notificationRating->comment}}</textarea>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="{{url('/my_notifications')}}">
                    <button class="btn btn-block btn-default"><span class="glyphicon glyphicon-log-out"></span><br>Cofnij</button>
                </form>

            </div>
        </div>
        @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $('#rateButton').click(function (e) {
        e.preventDefault();
        let ratingsArray = [];
        let ratingCriterion = $('.ratingCriterion');
        $.each(ratingCriterion, function (criterionIndex, criterion) {
            $.each($(criterion).find('input'), function (inputIndex, input) {
                if($(input).is(':checked')){
                    ratingsArray.push({'criterionId':$(criterion).data('id'),'rating': $(input).val()})
                }
            });
        });
        if(ratingCriterion.length == ratingsArray.length){
            $(e.target).attr('disabled', true);
            $.ajax({
                url: "{{ route('api.rateNotificationAjax') }}",
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: {
                    ratingsArray: ratingsArray,
                    notificationId: '{{$notification->id}}',
                    comment: $('#comment').val()
                },
                success: function (response) {
                    $('#ajaxResponse').val(response);
                    $('#rateForm').submit();
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
        }else {
            swal('Uzupełnij wszystkie odpowiedzi');
        }
    });
</script>
@endsection
