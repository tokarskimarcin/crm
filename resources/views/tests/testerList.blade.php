@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="well gray-nav">Testy / Lista testerów</div>
        </div>
    </div>
</div>

@if (Session::has('message_ok'))
    <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <form action="{{ URL::to('tester_list') }}" method="POST" id="tester_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="col-md-4">
                <div class="well well-back">
                <div class="form-group">
                    <label>Wybierz pracownika:</label>
                    <select class="form-control" name="user_id" id="user_id">
                        <option value="0">Wybierz</option>
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->last_name . ' ' . $user->first_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-info" id="add_new_tester">
                        <span class="glyphicon glyphicon-plus"></span> Przydziel uprawnienia
                    </button>
                </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row" style="margin-top: 50px">
    <div class="col-md-5">
        <div class="well well-back">
        <div class="table-responsive">
            <table class="table table-striped thead-inverse">
                <thead>
                    <tr>
                        <th style="width: 10%">Lp.</th>
                        <th>Imie i nazwisko</th>
                        <th style="width: 20%">Usuń z listy</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 0)
                    @foreach($testers as $item)
                        @php($i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$item->last_name . ' ' . $item->first_name}}</td>
                            <td>
                                <button class="btn btn-danger remove_user" data-user_id="{{$item->id}}">
                                    <span class="glyphicon glyphicon-remove"></span> Usuń
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($i == 0)
                <div class="alert alert-info">
                    <b>Brak testerów!</b>
                </div>
            @endif
        </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>

$(document).ready(() => {

    $('#add_new_tester').click((e) => {
        e.preventDefault();
        var user_id = $('#user_id').val();
    
        if (user_id == 0) {
            swal('Wybierz użytkownika!')
            return;
        }

        $('#tester_form').submit();
    });
   
    $('.remove_user').click(function(e) {
        var user_id = $(this).data('user_id');

        $.ajax({
            type:"POST",
            async: false,
            url: '{{ route('api.deleteTester') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                "user_id":user_id
            },
            success: function(response) {
                location.reload();
            },
            error: function(response) {
                swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!')
            }
        });
    });
});

</script>
@endsection
