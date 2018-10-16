@extends('layouts.main')
@section('content')



    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <div class="alert gray-nav ">Pomoc / Opis problemu</div>
            </div>
        </div>
    </div>

    @if(isset($message))
        <div class="alert alert-success">{{$message}}</div>
    @endif

    @if (Session::has('message_ok'))
        <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{$notification->title}}</div>
                <div class="panel-body">

                        <b>Problem zgłoszony przez:</b>
                        {{$notification->user->first_name . ' ' . $notification->user->last_name}}
                        <br />
                        <hr>


                        <b>Pracownik IT:</b>
                        @if(isset($notification->displayed_by) && $it_user != null)
                            {{$it_user->first_name . ' ' . $it_user->last_name}}
                        @else
                            Brak
                        @endif
                        <br />
                        <hr>


                        <b>Oddział:</b>
                        {{$notification->department_info->departments->name . ' ' . $notification->department_info->department_type->name}}
                        <br />
                        <hr>



                        <b>Treść problemu:</b>
                        {{$notification->content}}
                        <br />
                        <hr>

                        <b>Status:</b>
                        @if($notification->status == 1)
                            Zgłoszono
                        @elseif($notification->status == 2)
                            Przyjęto do realizacji
                        @elseif($notification->status == 3)
                            Zakończono
                        @endif
                        <br />
                        <hr>


                    <p>
                    <div class="row">
                        @if($notification->status == 3)

                            <div class="col-md-4">
                                <form method="get" action="{{URL::to('/rateNotification/'.$notification->id)}}">
                                    <button class="btn btn-default btn-block">Oceń</button>
                                </form>
                            </div>
                        @endif
                        <div class="col-md-8">
                            <form method="POST" action="{{URL::to('/add_comment_notifications/')}}/{{$notification->id}}" id="form_comment">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label for="content">Dodaj komentarz:</label>
                                    <textarea id="content" name="content" style="resize: vertical" placeholder="Tutaj wpisz treść komentarza" class="form-control"></textarea>
                                </div>
                                <div class="alert alert-danger" style="display: none" id="alert_comment">
                                    Podaj treść komentarza!
                                </div>
                                <div class="form-group">
                                    <input id="add_comment" type="submit" class="btn btn-default" value="Dodaj komentarz" />
                                </div>
                            </form>
                        </div>
                    </div>
                    </p>
                    <div class="col-md-12">
                        <hr>
                        @if($notification->comments != null)
                            <h3>Komentarze:</h3>
                            @foreach($notification->comments->sortByDesc('created_at') as $comment)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Dodał: {{$comment->user->first_name . ' ' . $comment->user->last_name}}
                                    </div>
                                    <div class="panel-body">
                                        <small>Data dodania: {{$comment->created_at}}</small>
                                        <p>{{$comment->content}}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>

</script>
@endsection
