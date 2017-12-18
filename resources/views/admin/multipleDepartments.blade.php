@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>Przydzielanie oddziałów</h1>
        </div>
    </div>
</div>

@if(isset($success))
    <div class="alert alert-success">{{$success}}</div>
@endif


<div class="row">
    <div class="col-md-8">
        <form method="POST" action="{{URL::to('/set_multiple_department')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="request_type" value="select_user">
              <div class="form-group">
                  <select id="user_department" name="user_department" class="form-control">
                      <option>Wybierz</option>
                      @foreach($users as $user)
                          <option @if(isset($user_id_post) && $user->id == $user_id_post) selected @endif value="{{$user->id}}">{{$user->last_name . ' ' . $user->first_name}}</option>
                      @endforeach
                  </select>
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-default"  value="Wybierz"/>
              </div>

        </form>
@if(isset($department_info))
<form method="POST" >
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="request_type" value="save_changes">
    <input type="hidden" name="user_department_post" value="{{$user_id_post}}">
    @foreach($department_info as $department)
                <div class="checkbox">
                    <label><input type="checkbox" name="dep{{$department->id}}"
                  @foreach($user_dep as $ud)
                          @if($ud->department_info_id == $department->id)
                            checked
                          @endif
                  @endforeach
                      value="{{$department->id}}">{{$department->departments->name . ' ' . $department->department_type->name}}</label>
                </div>
    @endforeach
    <div class="form-group">
      <input type="submit" class="btn btn-default"  value="Zapisz zmiany"/>
    </div>

</form>
@endif



    </div>
</div>



@endsection
@section('script')

<script>

$(document).ready(function(){
    var user_department = $('#user_department').val();
    if(user_department != 'Wybierz'){
        $('#departmet_to_set').removeAttr('disabled');
    }
});

$('#user_department').on('change', function() {
    var user_department = $('#user_department').val();

    if (user_department != 'Wybierz') {
        $('#departmet_to_set').removeAttr('disabled');
    } else {
        $('#departmet_to_set').removeAttr('disabled', true);
    }
});



</script>
@endsection