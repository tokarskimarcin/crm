@extends('layouts.main')
@section('content')


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            @if($type == 1)
              Aktualnie zgłoszone problemy
            @elseif($type == 2)
              Problemy przyjęte do realizacji
            @elseif($type == 3)
              Rozwiązane problemy
            @endif
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
          <li @if($type == 1) class="active" @endif><a href="/show_all_notifications/1">Aktualnie zgłoszone</a></li>
          <li @if($type == 2) class="active" @endif><a href="/show_all_notifications/2">W trakcie realizacji</a></li>
          <li @if($type == 3) class="active" @endif><a href="/show_all_notifications/3">Zrealizowane</a></li>
        </ul>
        <br />
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID zgłoszenia</th>
                            <th>Tytuł</th>
                            <th>Data dodania</th>
                            <th>Oddział</th>
                            <th>Użytkownik</th>
                            <th>Akcja</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($notifications as $notification)
                          <tr>
                              <td>{{$notification->id}}</td>
                              <td>{{$notification->title}}</td>
                              <td>{{$notification->created_at}}</td>
                              <td>{{$notification->department_info->departments->name . ' ' . $notification->department_info->department_type->name}}</td>
                              <td>{{$notification->user->first_name . ' ' . $notification->user->last_name}}</td>
                              <td><a class="btn btn-info" href="/show_notification/{{$notification->id}}">Zobacz zgłoszenie</a></td>
                          </tr>
                      @endforeach
                    </tbody>
                </table>
                {{$notifications->links()}}
            </div>
        </div>



    </div>
</div>











@endsection
@section('script')
<script>

</script>
@endsection
