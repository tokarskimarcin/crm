@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav">Dodaj link</div>
        </div>
    </div>
</div>

@if (Session::has('message_ok'))
   <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{URL::to('/create_link')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="name">Podaj tytuł:</label>
                <input type="text" class="form-control" placeholder="Tytuł..." id="name" name="name" />
            </div>
            <div class="alert alert-danger" style="display: none" id="name_alert">
                Podaj nazwę linku!
            </div>
            <div class="form-group">
                <label for="link">Podaj link:</label>
                <input type="text" class="form-control" placeholder="Link..." name="link" id="link" />
            </div>
            <div class="alert alert-danger" style="display: none" id="link_alert">
                Podaj link!
            </div>
            <div class="form-group">
                <label for="group_link_id">Wybierz grupę:</label>
                <select class="form-control" name="group_link_id" id="group_link_id">
                    <option>Wybierz</option>
                    @foreach($link_groups as $link)
                        <option value="{{$link->id}}">{{$link->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="alert alert-danger" style="display: none" id="group_link_id_alert">
                Wybierz grupę!
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default btn_submit" value="Dodaj link"/>
            </div>
        </form>
    </div>
</div>


@endsection
@section('script')

<script>

$('.btn_submit').on('click', () => {
    var name = $('#name').val();
    var link = $('#link').val();
    var group_link_id = $('#group_link_id').val();
    var validation = true;

    if (name == '') {
        $('#name_alert').slideDown(1000);
        validation = false;
    } else {
        $('#name_alert').slideUp(1000);
    }

    if (link == '') {
        $('#link_alert').slideDown(1000);
        validation = false;
    } else {
        $('#link_alert').slideUp(1000);
    }

    if (group_link_id == 'Wybierz') {
        $('#group_link_id_alert').slideDown(1000);
        validation = false;
    } else {
        $('#group_link_id_alert').slideUp(1000);
    }
    return validation;
});



</script>
@endsection
