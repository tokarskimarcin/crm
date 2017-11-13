@extends('layouts.main')
@section('style')

@endsection

@section('content')

{{--Header page --}}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Kary i Premie</h1>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Podgląd Wypłat
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="start_stop">


                                <div class="col-md-12">
                                    <h1 style ="font-family: 'bebas_neueregular'; text-shadow: 2px 2px 2px rgba(150, 150, 150, 0.8); font-size:25px; margin-top: 0;">Przydziel Karę/Premię</h1>

                                    <div class="well">
                                        <div class="form-group">
                                            <form action="view_penalty_bonus" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="show_pb" value="0">
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
                                                    <button type="submit" class="btn btn-primary" name="addpbsubmit" style="font-size:18px; width:100%;">Zatwierdź</button>
                                                </div>
                                            </form>
                                        </div></br>
                                    </div>
                                </div>



                                <div class="col-md-12">
                                    <h1 style ="font-family: 'bebas_neueregular'; text-shadow: 2px 2px 2px rgba(150, 150, 150, 0.8); font-size:30px;">Sprawdź Karę/Premię</h1>
                                </div>

                                <div class="col-md-3">


                                    <div class="well">
                                        <div class="form-group">

                                            <form action="view_penalty_bonus" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="show_pb" value="1">
                                                <label for="exampleInputPassword1">Pracownik:</label>
                                                <select name="showuser" class="form-control" style="font-size:18px;">
                                                    @if($showuser == -1)
                                                    <option value=-1 selected>Wszyscy</option>
                                                    @else
                                                        <option value=-1>Wszyscy</option>
                                                    @endif
                                                    @foreach($users as $user)
                                                            @if($showuser == $user->id)
                                                                <option selected value={{$user->id}}>{{$user->last_name.' '.$user->first_name}}</option>
                                                            @else
                                                                <option value={{$user->id}}>{{$user->last_name.' '.$user->first_name}}</option>
                                                            @endif

                                                    @endforeach
                                                </select></br>

                                                <label for="exampleInputPassword1">Zakres Od:</label>
                                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                    <input class="form-control" name="date_penalty_show_start" type="text" value="{{$date_start}}" readonly >
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                </div></br>

                                                <label for="exampleInputPassword1">Zakres Do:</label>
                                                <div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="datak" style="width:100%;">
                                                    <input class="form-control" name="date_penalty_show_stop" type="text" value="{{$date_stop}}" readonly >
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                </div></br>



                                                <button type="submit" class="btn btn-primary" name="showpbsubmit" style="font-size:18px; width:100%;">Wyszukaj</button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($users_show))
                                <div class="col-md-9">
                                    <div class="well"><div class="tab-content">
                                            <div class="tab-pane active" id="profile">
                                                <div class="panel-heading" style="border:1px solid #d3d3d3;"><b>Wyszukiwanie w zakresie od </b></div>
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
                                                            <tr>
                                                                <td class="user_name_modal">{{$item->first_name.' '.$item->last_name}}</td>
                                                                <td class="user_status_modal">{{($item->type == 1) ? "Kara" : "Premia"}}</td>
                                                                <td class="user_cost_modal">{{$item->amount}} PLN</td>
                                                                <td class="user_date_modal">{{$item->event_date}}</td>
                                                                <td>{{$item->manager_first_name.' '.$item->manager_last_name}}</td>
                                                                <td class="user_comment_modal">{{$item->comment}}</td>
                                                                <td>
                                                                    <button type="button" id={{$item->id}} class="btn btn-danger action delete">Usuń</button>
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
                                <label for="dtp_input3" class="col-md-5 control-label">Pracownik:</label>
                                <div id="userDetails" class="modal-body">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dtp_input3" class="col-md-5 control-label">Data dodania</label>
                                <div id="dateDetails" class="modal-body"></div>
                            </div>
                            <div class="form-group">
                                <label for="dtp_input3" class="col-md-5 control-label">Typ:Kara/Premia</label>
                                <div id="statusDetails" class="modal-body"></div>
                            </div>
                            <div class="form-group">
                                <label for="dtp_input3" class="col-md-5 control-label">Kwota</label>
                                <div id="amountDetails" class="modal-body"></div>
                            </div>
                            <div class="form-group">
                                <label for="dtp_input3" class="col-md-5 control-label">Powód</label>
                                <div id="reasonDetails" class="modal-body"></div>
                            </div>
                            <button id="edit_user_modal" type="submit" class="btn btn-primary" name="register" style="font-size:18px; width:100%;">Zapisz</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default close" data-dismiss="save_modal">Anuluj</button>
                        </div>
                    </div>

                </div>
            </div>
@endsection

@section('script')
<script>
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

    $( ".edit" ).click(function(){
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

        $( "#amountDetails" ).html(  '<input type="number" min="0" step="1"  id="cost_modal" class="form-control"  value='+user_cost+'>' );
        $( "#dateDetails" ).html( user_date );
        $( "#reasonDetails" ).html( '<input class="form-control" id="reason_modal" type="text" value='+user_commnet+'>' );
    });

    $( "#edit_user_modal" ).click(function() {
        user_status = $( "#type_penalty_modal option:selected" ).val()
        user_cost =  $("#cost_modal").val();
        user_commnet =  $("#reason_modal").val();
            $.ajax({
                type: "POST",
                url: '{{ route('api.editPenaltyBonus') }}',
                data: {"id": id_record, "type": user_status,
                    "amount": user_cost,"comment":user_commnet},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                }
            });


    });

    {{--$( ".delete" ).click(function() {--}}
        {{--var id = (this.id);--}}
        {{--$.ajax({--}}
            {{--type: "POST",--}}
            {{--url: '{{ route('api.deleteAcceptHour') }}',--}}
            {{--data: {--}}
                {{--"id": id--}}
            {{--},--}}
            {{--headers: {--}}
                {{--'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--},--}}
            {{--success: function(response) {--}}
                {{--alert("Godziny zostały usunięte");--}}
                {{--location.reload();--}}
            {{--}--}}
        {{--});--}}
    {{--});--}}
    {{--$( ".delete" ).click(function() {--}}
        {{--var id = (this.id);--}}
        {{--$.ajax({--}}
            {{--type: "POST",--}}
            {{--url: '{{ route('api.deleteAcceptHour') }}',--}}
            {{--data: {--}}
                {{--"id": id--}}
            {{--},--}}
            {{--headers: {--}}
                {{--'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--},--}}
            {{--success: function(response) {--}}
                {{--alert("Godziny zostały usunięte");--}}
                {{--location.reload();--}}
            {{--}--}}
        {{--});--}}
    {{--});--}}

</script>
@endsection
