@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Blokowanie oddziałów</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Oddział</th>
                        <th>Akcja</th>
                    </tr>
                    <tbody>
                    @foreach($department_info as $department)
                        <tr>
                            <td>{{$department->departments->name . ' ' . $department->department_type->name}}</td>
                            <td>
                              <div class="btn-group">
                                  <button @if($department->blocked == 1) disabled @else id={{$department->id}} @endif class="btn btn-danger block">Zablokuj</button>
                                  <button @if($department->blocked == 0) disabled @else id={{$department->id}} @endif class="btn btn-success unblock">Odblokuj</button>
                              </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </thead>
            </table>
        <div>
    </div>
</div>

@endsection
@section('script')

<script>

$('.block').on('click', function() {
  swal({
      title: '',
      text: "Zablokować oddział?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Tak'
      }).then((result) => {
      if (result.value) {
          var department_info_id = $(this).prop('id');
          $.ajax({
              type: "POST",
              url: '{{ route('api.locker') }}',
              data: {
                "department_info_id":department_info_id,
                "type":1
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
                        text: 'Oddział został zablokowany!',
                        timer: 2000,
                        onOpen: () => {
                          swal.showLoading()
                        }
                      }).then((result) => {
                          location.reload();
                      })

                  }
              }
          });

      }
      })

});

$('.unblock').on('click', function() {
  swal({
      title: '',
      text: "Odblokować oddział?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Tak'
      }).then((result) => {
      if (result.value) {
          var department_info_id = $(this).prop('id');
          $.ajax({
              type: "POST",
              url: '{{ route('api.locker') }}',
              data: {
                "department_info_id":department_info_id,
                "type":0
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
                        text: 'Oddział został odblokowany!',
                        timer: 2000,
                        onOpen: () => {
                          swal.showLoading()
                        }
                      }).then((result) => {
                          location.reload();
                      })

                  }
              }
          });

      }
      })

});

</script>
@endsection
