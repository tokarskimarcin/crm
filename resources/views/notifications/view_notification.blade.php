@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Szczegóły</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                Opis problemu
            </div>
            <div class="panel-body">
              <h3>
                <p>
                    <b>Tytuł:</b> {{$notification->title}}
                </p>
                <hr>
                <p>
                    <b>Typ problemu:</b> {{$notification->notification_type->name}}
                </p>
                <hr>
                <p>
                    <b>Oddział:</b> {{$notification->department_info->departments->name . ' ' . $notification->department_info->department_type->name}}
                </p>
                <hr>
                <p>
                    <b>Treść problemu:</b> {{$notification->content}}
                </p>
                <hr>
                <p>
                    <b>Status:</b>
                    @if($notification->status == 1)
                        Zgłoszono
                    @elseif($notification->status == 2)
                        Przyjęto do realizacji
                    @elseif($notification->status == 3)
                        Zakończono
                    @endif
                </p>
                <hr>
                @if(isset($notification->displayed_by) && $it_user != null)
                    <p>
                        <b>Pracownik IT:</b> {{$it_user->first_name . ' ' . $it_user->last_name}}
                    </p>
                    <hr>
                @endif
                <p>
                  <b>Komentarze</b>
                </p>
                @foreach($notification->comments as $comment)
                    <div class="well">
                          {{$comment->content}}
                          <p><small><b>Dodano:</b> {{$comment->created_at}} <b>przez</b> {{$comment->user->first_name . ' ' . $comment->user->last_name}}</small></p>
                    </div>
                @endforeach
              </h3>
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
<script>

</script>
@endsection
