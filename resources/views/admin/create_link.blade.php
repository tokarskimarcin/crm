@extends('layouts.main')
@section('content')
    {{--**************************************************************************************--}}
    {{--THIS PAGE ALLOWS USER ADD/REMOVE NAVBAR GROUPS AND ADD/REMOVE EACH LINK IN GIVEN GROUP--}}
    {{--**************************************************************************************--}}
    <style>
        #removeGroupForm {
            margin-top: 40px;
        }
    </style>

    <div class="container-fluid">
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
        {{--PART RESPONSIBLE FOR ADDING/REMOVING LINKS INSIDE GIVEN GROUP--}}
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
            {{--PART RESPONSIBLE FOR ADDING/REMOVING GROUPS--}}
            <div class="col-md-6">
                <form action="{{URL::to('/addGroup')}}" method="POST" id="addGroupForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="addLinkGroup">Dodaj grupę</label>
                        <input type="text" class="form-control" id="addLinkGroup" name="addLinkGroup">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-default group_add_submit" value="Dodaj grupę">
                    </div>
                </form>
                <form action="{{URL::to('/removeGroup')}}" method="POST" id="removeGroupForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="removeLinkGroup">Usuń grupę</label>
                        <select name="removeLinkGroup" id="removeLinkGroup" class="form-control" name="removeLinkGroup">
                            <option value="0">Wybierz</option>
                            @foreach($link_groups as $link)
                                <option value="{{$link->id}}">{{$link->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-default group_remove_submit" value="Usuń">
                    </div>
                </form>
            </div>
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

//PART RELATED TO ADDING/REMOVING GROUPS
document.addEventListener('DOMContentLoaded', function() {
   var addGroupBtn = document.getElementsByClassName('group_add_submit')[0];
   var removeGroupBtn = document.getElementsByClassName('group_remove_submit')[0];

    /**
     * Event Listener function responsible for submiting addGroupForm, simple Yes/No swal.
     */
   function addGroupSubmitHandler(e) {
       e.preventDefault();
       swal({
           title: 'Jesteś pewien?',
           text: "Po potwierdzeniu zostanie dodana nowa grupa!",
           type: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Usuń!'
       }).then((result) => {
           if (result.value) {
           document.querySelector('#addGroupForm').submit();
           swal(
               'Dodano!',
               'Grupa została dodana',
               'Sukces'
           )}
       });
   }

    /**
     * Event Listener function responsible for submiting removeGroupForm, simple Yes/No swal.
     */
   function removeGroupSubmitHandler(e) {
       e.preventDefault();
       swal({
           title: 'Jesteś pewien?',
           text: "Po potwierdzeniu brak możliwości cofnięcia zmian",
           type: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Usuń!'
       }).then((result) => {
           if (result.value) {
           document.querySelector('#removeGroupForm').submit();
           swal(
               'Usunięto!',
               'Grupa została usunięta',
               'Sukces'
           )}
        });
   }

   addGroupBtn.addEventListener('click', addGroupSubmitHandler);
   removeGroupBtn.addEventListener('click', removeGroupSubmitHandler)
});

</script>
@endsection
