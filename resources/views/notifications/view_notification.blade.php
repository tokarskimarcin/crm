@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Pomoc / Szczegóły problemu</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Pracownik IT:
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
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Typ problemu:
            </div>
            <div class="panel-body">
                {{$notification->notification_type->name}}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Status:
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
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Tytuł
            </div>
            <div class="panel-body">
                {{$notification->title}}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Treść
            </div>
            <div class="panel-body">
                {{$notification->content}}
            </div>
        </div>
    </div>
</div>

@if($notification->comments->count() > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Komentarze
                </div>
                <div class="panel-body">
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
        </div>
    </div>
@endif

@endsection
@section('script')
<script>

</script>
@endsection
