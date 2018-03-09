@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav">Przydziel dostęp spoza zaufanych IP</div>
        </div>
    </div>
</div>

@if (Session::has('message_ok'))
   <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{URL::to('/firewall_privileges')}}" id="firewall_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="user_selected">Dodaj pracownika</label>
                <select name="user_selected" class="form-control" id="user_selected">
                    <option value="0">Wybierz</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->last_name . ' ' . $user->first_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" value="Dodaj użytkownika" id="btn_add_user" />
            </div>
        </form>
    </div>
</div>

@php $i = 0 @endphp

<div class="row">
    <div class="col-md-6">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead style="color: white; background: #7A7A7A;">
                    <th>Użytkownik</th>
                    <th>Data dodania</th>
                    <th>Akcja</th>
                <thead>
                <tbody>
                    @foreach($firewall_privileges as $item)
                        @php $i++;  @endphp
                        <tr id="{{$i}}">
                            <td>{{$item->user->last_name . ' ' . $item->user->first_name}}</td>
                            <td>{{$item->updated_at}}</td>
                            <td><button class="btn btn-danger" data-user_id="{{$item->user_id}}" data-row_id="{{$i}}">Usuń</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('script')

<script>

$('#btn_add_user').click(function(e) {
    e.preventDefault();
    var user_selected = $('#user_selected').val();

    if (user_selected == 0) {
        swal('Wybierz użytkownika!')
        return false;
    } else {
        $('#firewall_form').submit();
    }
});

$('.btn-danger').click(function() {
  swal({
      title: '',
      text: "Usunąć użytkownika?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Tak'
      }).then((result) => {
      if (result.value) {
          var user_id = $(this).data('user_id');
          var row_id = $(this).data('row_id');

          $.ajax({
              type: "POST",
              url: '{{ route('api.firewallDeleteUser') }}',
              data: {
                "user_id": user_id
              },
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                  if (response == 0) {
                    swal(
                      'Błąd!',
                      'Coś poszło nie tak, skontaktuj się z administratorem!',
                      'warning'
                    )
                  } else {
                    swal({
                        title: 'Sukces',
                        text: 'Użytkownik został usunięty z listy!',
                        timer: 2000,
                        onOpen: () => {
                          swal.showLoading()
                        }
                      }).then((result) => {
                          $('#' + row_id).remove(0);
                      })
                  }
              }
          });

      }
      })
});

</script>
@endsection
