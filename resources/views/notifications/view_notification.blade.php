@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Szczegóły</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Tytuł</b>
            </div>
            <div class="panel-body">
                {{$notification->title}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Typ problemu:</b>
            </div>
            <div class="panel-body">
                {{$notification->notification_type->name}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Oddział:</b>
            </div>
            <div class="panel-body">
                {{$notification->department_info->departments->name . ' ' . $notification->department_info->department_type->name}}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Status:</b>
            </div>
            <div class="panel-body">
              @if($notification->status == 1)
                  Zgłoszono
              @elseif($notification->status == 2)
                  Przyjęto do realizacji
              @elseif($notification->status == 3)
                  Zakończono
              @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Treść problemu:</b>
            </div>
            <div class="panel-body">
                {{$notification->content}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <b>Pracownik IT:</b>
            </div>
            <div class="panel-body">
                @if(isset($notification->displayed_by) && $it_user != null)
                    {{$it_user->first_name . ' ' . $it_user->last_name}}
                @else
                    Brak
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h1>Komentarze:</h1>
        @foreach($notification->comments as $comment)
            <div class="panel panel-info">
                <div class="panel-heading">
                    <b>Dodał: {{$comment->user->first_name . ' ' . $comment->user->last_name}} | {{$comment->created_at}}</b>
                </div>
                <div class="panel-body">
                    {{$comment->content}}
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
@section('script')
<script>

</script>
@endsection
