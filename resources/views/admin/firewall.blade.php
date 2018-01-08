@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Zauafane adresy IP</h1>
        </div>
    </div>
</div>

@if (Session::has('message_ok'))
   <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Adres IP</th>
                        <th>Status</th>
                    </tr>
              </thead>
              <tbody>
                  @foreach($firewall as $item)
                      <tr>
                          <td>{{$item->ip_address}}</td>
                          @php($status = ($item->whitelisted == 1) ? 'Zatwierdzony' : 'Niezatwierdzony')
                          <td>{{$status}}</td>
                      </tr>
                  @endforeach
              </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h1>Dodaj adres IP</h1>
    </div>
    <div class="col-md-4">
        <form method="POST"  action="{{URL::to('/firewall_ip')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="new_ip">Podaj IP</label>
                <input type="text" class="form-control" name="new_ip" id="new_ip" placeholder="127.0.0.1"/>
            </div>
            <div class="form-group">
                <label for="new_ip">Status</label>
                <select class="form-control" name="ip_status" id="ip_status">
                    <option>Wybierz</option>
                    <option value="0">Nie</option>
                    <option value="1">Tak</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default btn-lg" value="Dodaj IP" id="ip_submit"/>
            </div>
        </form>
    </div>
</div>

@endsection
@section('script')

<script>

$('#ip_submit').on('click', () => {
    var new_ip = $('#new_ip').val();
    var ip_status = $('#ip_status').val();

    if (new_ip == '') {
        swal('Podaj adres IP!')
        return false;
    }

    if (ip_status == 'Wybierz') {
        swal('Wybierz status!')
        return false;
    }

});

</script>
@endsection
