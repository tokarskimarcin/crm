@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Twoje zgłoszenia</h1>
        </div>
    </div>
</div>


    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 10%">Data:</th>
                    <th style="width: 40%">Tytuł:</th>
                    <th style="width: 30%">Zgłoszenie przyjęte przez:</th>
                    <th style="width: 10%">Szczegóły</th>
                    <th style="width: 10%">Oceń</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                    <tr>
                        <td>{{$notification->created_at}}</td>
                        <td>{{$notification->title}}</td>
                        @if($notification->displayed_by != null)
                            <td>{{$notification->first_name . ' ' . $notification->last_name}}</td>
                        @else
                            <td>Oczekiwanie na przyjęcie zgłoszenia</td>
                        @endif
                        <td><a class="btn btn-default" href="{{URL::to('/show_notification/')}}/{{$notification->id}}">Szczegóły</a></td>
                        @if($notification->status == 3)
                            <td><a class="btn btn-default" data-toggle="tooltip" title="Ocenić wykonanie możesz po zakończonej realizacji!" data-placement="left" disabled>Oceń</a></td>
                        @else
                            <td><a class="btn btn-default" href="{{URL::to('/judge_notification/')}}/{{$notification->id}}">Oceń</a></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>



@endsection
@section('script')

<script>


</script>
@endsection
