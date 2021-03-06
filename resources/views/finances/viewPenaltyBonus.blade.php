@extends('layouts.main')
@section('style')

@endsection

@section('content')

{{--Header page --}}
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <div class="alert gray-nav ">Rozliczenia / Kary i Premie</div>
        </div>
    </div>
</div>

@if (Session::has('message_ok'))
    <div class="alert alert-success">{{ Session::get('message_ok') }}</div>
@endif

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Przydziel Karę/Premię
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">
                                <div class="col-md-12">
                                    <div class="well">
                                        <div class="form-group">
                                            <form action="create_penalty_bonus" method="post" id="create">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-md-6">
                                                    <label for="exampleInputPassword1">Pracownik:</label>
                                                    <select name="user_id" class="form-control" style="font-size:18px;">
                                                        <option>Wybierz</option>
                                                        @foreach($users as $user)
                                                            <option value={{$user->id}}>{{$user->last_name.' '.$user->first_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Data:</label>
                                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">

                                                        <input class="form-control" name="date_penalty" type="text" value="{{date("Y-m-d")}}" readonly >
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                    </div></br>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Kara/Premia:</label>
                                                    <select name="type_penalty" class="form-control" style="font-size:18px;">
                                                        <option>Wybierz</option>
                                                        <option value="1">Kara</option>
                                                        <option value="2">Premia</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Kwota:</label>
                                                    <input class="form-control" name="cost" type="number" min="0" step="1" value=""></br>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="exampleInputPassword1">Powód:</label>
                                                    <input class="form-control" name="reason" type="text" value="">

                                                    </br>
                                                </div></br></br></br></br></br></br></br></br></br></br></br></br>
                                                <div class="col-md-12">
                                                    <button type="submit" id="addpbsubmit" class="btn btn-primary" name="addpbsubmit" style="font-size:18px; width:100%;">Zatwierdź</button>
                                                </div>
                                            </form>
                                        </div></br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Sprawdź Karę/Premię
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="start_stop">
                                <div class="col-md-12">
                                    <div class="well">
                                        <div class="form-group">
                                            <form action="view_penalty_bonus" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="col-md-4">
                                                    <label for="exampleInputPassword1">Pracownik:</label>
                                                    <select name="showuser" class="form-control" style="font-size:18px;">

                                                        @if(isset($showuser))
                                                            @if($showuser == -1)
                                                                  <option value="-1" selected>Wszyscy</option>
                                                            @else
                                                                  <option value="-1">Wszyscy</option>
                                                            @endif
                                                        @else
                                                            <option value="-1" selected>Wszyscy</option>
                                                        @endif

                                                        @foreach($users as $user)

                                                        @if(isset($showuser))
                                                            @if($showuser == $user->id)
                                                                    <option value={{$user->id}} selected >{{$user->last_name.' '.$user->first_name}}</option>
                                                            @else
                                                                  <option value={{$user->id}}>{{$user->last_name.' '.$user->first_name}}</option>
                                                            @endif
                                                        @else
                                                            <option value={{$user->id}}>{{$user->last_name.' '.$user->first_name}}</option>
                                                        @endif



                                                        @endforeach
                                                    </select></br>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="exampleInputPassword1">Zakres Od:</label>
                                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                        @if(isset($date_start))
                                                            <input class="form-control" name="date_penalty_show_start" type="text" value="{{$date_start}}" readonly >
                                                        @else
                                                            <input class="form-control" name="date_penalty_show_start" type="text" value="{{date('Y-m-d')}}" readonly >
                                                        @endif

                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                    </div></br>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="exampleInputPassword1">Zakres Do:</label>
                                                    <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                      @if(isset($date_stop))
                                                          <input class="form-control" name="date_penalty_show_stop" type="text" value="{{$date_stop}}" readonly >
                                                      @else
                                                          <input class="form-control" name="date_penalty_show_stop" type="text" value="{{date('Y-m-d')}}" readonly >
                                                      @endif

                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                    </div></br>
                                                </div>
                                                <button type="submit" class="btn btn-primary" name="showpbsubmit" id="showpbsubmit_btn" style="font-size:18px; width:100%;">Wyszukaj</button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                                @if(isset($users_show))
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Wyszukiwanie w zakresie od: {{$date_start}} - {{$date_stop}}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="start_stop">
                                <div class="col-md-12">
                                    <div class="well"><div class="tab-content">
                                            <div class="tab-pane active" id="profile">
                                                <table class="table table-bordered">
                                                        <thead>
                                                            <tr align="center">
                                                                <th>Osoba</th>
                                                                <th>Status</th>
                                                                <th>Kwota</th>
                                                                <th>Data</th>
                                                                <th>Nadał</th>
                                                                <th>Komentarz</th>
                                                                <th>Akcja</th>
                                                            </tr>
                                                        </thead>
                                                    <tbody>

                                                        @foreach($users_show as $item)
                                                            <tr name="{{$item->id}}">
                                                                <td class="user_name_modal">{{$item->first_name.' '.$item->last_name}}</td>
                                                                <td class="user_status_modal">{{($item->type == 1) ? "Kara" : "Premia"}}</td>
                                                                <td class="user_cost_modal">{{$item->amount}} PLN</td>
                                                                <td class="user_date_modal">{{$item->event_date}}</td>
                                                                <td>{{$item->manager_first_name.' '.$item->manager_last_name}}</td>
                                                                <td class="user_comment_modal">{{$item->comment}}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger action delete" data-id_penalty={{$item->id}}>Usuń</button>
                                                                    <button type="button" data-toggle="modal" data-target="#editinfo" id={{$item->id}} class="btn btn-info action edit">Edycja</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>
                           @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>



            <!-- Modal -->
            <div id="editinfo" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Edycja Kary/Premii</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="userDetails" class="col-md-5 control-label">Pracownik:</label>
                                <div id="userDetails"></div>
                            </div>
                            <div class="form-group form-inline">
                                <label for="dateDetails" class="col-md-5 control-label">Data dodania:</label>
                                <div id="dateDetails"></div>
                            </div>
                            <div class="form-group">
                                <label for="statusDetails" class="col-md-5 control-label">Typ: Kara/Premia</label>
                                <div id="statusDetails" class="modal-body"></div>
                            </div>
                            <div class="form-group">
                                <label for="amountDetails" class="col-md-5 control-label">Kwota:</label>
                                <div id="amountDetails" class="modal-body"></div>
                                <div class="alert alert-danger" style="display: none" id="amount_modal_error">Podaj kwotę!</div>
                            </div>
                            <div class="form-group">
                                <label for="reasonDetails" class="col-md-5 control-label">Powód:</label>
                                <div id="reasonDetails" class="modal-body"></div>
                                <div class="alert alert-danger" id="reason_modal_error" style="display: none">Podaj powód!</div>
                            </div>
                            <button id="edit_user_modal" type="submit" class="btn btn-primary" name="register" style="font-size:18px; width:100%;">Zapisz</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default close" data-dismiss="modal">Anuluj</button>
                        </div>
                    </div>

                </div>
            </div>
@endsection

@section('script')
<script>

$('#editinfo').on('hidden.bs.modal', function () {
      $("#amount_modal_error").fadeOut(0);
      $("#reason_modal_error").fadeOut(0);
})

var validation = false;

$("#addpbsubmit").click(function () {

    var user_id = $("select[name='user_id']").val();
    var type_penalty = $("select[name='type_penalty']").val();
    var cost = $("input[name='cost']").val();
    var reason = $("input[name='reason']").val();

    $('#create').submit(function(){
        validation = true;
        $(this).find(':submit').attr('disabled','disabled');
    });

    if (validation == true) {
        $("#addpbsubmit").attr('disabled', true);
    }


    if (user_id == 'Wybierz') {
        swal("Wybierz użytkownika!")
        return false;
    }

    if (type_penalty == 'Wybierz') {
        swal("Wybierz rodzaj kary/nagrody!")
        return false;
    }

    if (cost == '') {
        swal("Podaj kwotę!")
        return false;
    }

    if (reason == '') {
        swal("Podaj powód!")
        return false;
    }

});

    var id_record;
    var user_name;
    var user_status;
    var user_cost;
    var user_date;
    var user_commnet;
    $('.form_date').datetimepicker({
        language: 'pl',
        autoclose: 1,
        minView: 2,
        pickTime: false,
    });

    $( ".edit" ).click(function(event){
        id_record = event.target.id;
        var tr = $(this).closest('tr');
         user_name = tr.find('.user_name_modal').text();
         user_status = tr.find('.user_status_modal').text();
         user_cost =  tr.find('.user_cost_modal').text();
         user_date =  tr.find('.user_date_modal').text();
         user_commnet = tr.find('.user_comment_modal').text();
        $( "#userDetails" ).html( user_name );
        if(user_status == 'Kara')
        {
            $( "#statusDetails" ).html( ' <select id="type_penalty_modal" class="form-control" style="font-size:18px;">\n' +
                '<option value="1" selected>Kara</option>\n' +
                '<option value="2">Premia</option>\n' +
                '</select>' );
        }else
        {
            $( "#statusDetails" ).html( ' <select id="type_penalty_modal" class="form-control" style="font-size:18px;">\n' +
                '<option value="1">Kara</option>\n' +
                '<option value="2" selected>Premia</option>\n' +
                '</select>' );
        }

        $( "#amountDetails" ).html(  '<input type="number" min="0" step="1" name="cost_modal" id="cost_modal" class="form-control"  value='+user_cost+'>' );
        $( "#dateDetails" ).html( user_date );
        $( "#reasonDetails" ).html( '<input class="form-control" id="reason_modal" name="reason_modal" type="text" value='+user_commnet+'>' );
    });

    $( "#edit_user_modal" ).click(function() {
        user_status = $( "#type_penalty_modal option:selected" ).val()
        user_cost =  $("#cost_modal").val();
        user_commnet =  $("#reason_modal").val();

        var validation_error = 0;

        if (user_cost == '') {
              $("#amount_modal_error").fadeIn(0);
              validation_error = 1;
        } else {
              $("#amount_modal_error").fadeOut(0);
        }

        if (user_commnet == '') {
              $("#reason_modal_error").fadeIn(0);
              validation_error = 1;
        } else {
              $("#reason_modal_error").fadeOut(0);
        }

        if (validation_error == 0) {
            $( "#edit_user_modal" ).attr('disabled', true);
            $.ajax({
                type: "POST",
                url: '{{ route('api.editPenaltyBonus') }}',
                data: {"id": id_record, "type": user_status,
                    "amount": user_cost,"comment":user_commnet},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response == 0) {
                        swal('Ups, coś poszło nie tak. Skontaktuj się z administratorem!')
                        $( "#edit_user_modal" ).removeAttr('disabled');
                    } else {
                        $("#showpbsubmit_btn").trigger("click");

                    }

                }
            });
        }



    });
    // AJAX usuwanie kar/premii
    $( ".delete" ).click(function() {
        var id= $(this).data('id_penalty');

        swal({
            title: '',
            text: "Czy napewno chcesz usunąć karę/premię?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tak'
          }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.deletePenaltyBonus') }}',
                    data: {
                        "id": id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response == 1) {
                            swal("Pomyślnie usunięto karę/premię!")
                        } else {
                            swal('Ups! Coś poszło nie tak, skontaktuj się z administratorem!')
                        }

                    }
                });
                $('tr[name=' + id + ']').fadeOut(0);
            }
          })
    });

</script>
@endsection
