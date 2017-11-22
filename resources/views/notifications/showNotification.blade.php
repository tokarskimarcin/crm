@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Opis problemu</h1>
    </div>
</div>

<div class="row">
  <div class="col-md-12">

        <div class="panel panel-default">
          <div class="panel-heading">{{$notification->title}}</div>
          <div class="panel-body">
            <p>
              <b>Problem zgłoszony przez:</b>
              {{$notification->user->first_name . ' ' . $notification->user->last_name}}
              <br />
              <hr>
            </p>
            <p>
              <b>Oddział:</b>
              {{$notification->department_info->departments->name . ' ' . $notification->department_info->department_type->name}}
              <br />
              <hr>
            </p>
            <p>
              <b>Treść problemu:</b><br />
                {{$notification->content}}
            </p>
            <hr>
            <p>
              <div class="col-md-3">
              <div class="form-group ">
                <label for="status_change">Zmień status:</label>
                <select class="form-control" name="status_change" id="status_change">
                    <option>Zgłoszono</option>
                    <option>Przyjęto do realizacji</option>
                    <option>Zakończono</option>
                </select>
              </div>
              <div class="form-group">
                  <button class="btn btn-success">Zapisz zmiany</button>
              </div>
            </div>
            </p>
          </div>



        </div>


  </div>
</div>

@endsection
@section('script')
<script>

</script>
@endsection
