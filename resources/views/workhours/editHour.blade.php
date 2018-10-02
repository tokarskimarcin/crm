<!-- Modal -->
<div id="editHourModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edycja Godzin pracownika</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="dtp_input3" class="col-md-5 control-label">Godzina przyjścia do pracy:</label>
                    <div class="input-group date form_time col-md-5" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                        <input id="accept_start" class="form-control" size="16" type="text" value="" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input3" value="" /><br/>
                </div>
                <div class="form-group">
                    <label for="dtp_input3" class="col-md-5 control-label">Godzina zakończenia pracy:</label>
                    <div class="input-group date form_time col-md-5" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                        <input id="accept_stop" class="form-control" size="16" type="text" value="" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input3" value="" /><br/>
                </div>
                @if( Session::get('count_agreement')==1 && in_array(Auth::user()->user_type_id, $userTypesPermissionToEditSuccess))
                <div class="form-group">
                    <label for="dtp_input3" class="col-md-5 control-label">Liczba Sukcesów: </label>
                    <div class="input-group date col-md-5">
                        <input id="success" class="form-control" size="16" type="number" value="0">
                    </div>
                    <input type="hidden" id="dtp_input3" value="" /><br/>
                </div>
                @else
                    <input id="success" class="form-control" size="16" type="hidden" value="0">
                @endif
                <button id="edit_hour" type="submit" class="btn btn-primary" name="register" style="font-size:18px; width:100%;">Zarejestruj</button>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close" data-dismiss="modal">Anuluj</button>
            </div>
        </div>

    </div>
</div>


@section('script.edithour')
    <script>

            var id = 0;
            var load = 0;
            var session = {{Session::get('count_agreement')}};
        $('#editHourModal').on('show.bs.modal', function(e) {
            if(load == 0) {
                var $modal = $(this),
                    esseyId = e.relatedTarget.id;
                id = esseyId;
                load++
                var object_button = $("#"+id).closest("tr").find(".accept_hour");
                var accept_hour_start = object_button.find(".accept_hour_start").html();
                var accept_hour_stop = object_button.find(".accept_hour_stop").html();
                if(session == 1)
                {
                    var count_succes = $("#"+id).closest("tr").find(".succes_count").html();
                    $('#success').val(count_succes);
                }
                $('#accept_start').val(accept_hour_start);
                $('#accept_stop').val(accept_hour_stop);

            }
        });


        $(function() {
            $('.form_time').datetimepicker({
                language:  'pl',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 1,
                minView: 0,
                maxView: 1,
                forceParse: 0
            });
        });

            $( ".close" ).click(function() {
                load=0;
            });

        $( "#edit_hour" ).click(function() {
            var accept_start = 0;
            var accept_stop = 0;
            var success = 0;
            accept_start = $('#accept_start').val();
            accept_stop = $('#accept_stop').val();
            success = $('#success').val();
            if(accept_start == null || accept_start =='')
            {
                swal('Brak godziny rozpoczęcia pracy')
            }else if (accept_stop == null || accept_stop =='')
                swal('Brak godziny zakończenia pracy')
            else if(success <0)
            {
                swal('Liczba zgód nie może być mniejsza niż zero')
            }
            else if(accept_start >= accept_stop)
            {
                swal('Godziny są ustawione niepoprawnie')
            }else
            {
                $(this).attr('disabled',true);
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.editAcceptHour') }}',
                    data: {"accept_start": accept_start, "accept_stop": accept_stop,
                        "success": success,"id":id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response == 1) {
                            swal({
                                title: 'Godziny zostały edytowane!',
                                text: '',
                                }).then((result) => {
                                if (result.dismiss === 'timer') {
                                    $('#form_submit').trigger('click');
                                } else {
                                    $('#form_submit').trigger('click');
                                }
                            })
                        } else {
                            swal({
                                title: 'Ups! Coś poszło nie tak, skontaktuj się z administratorem.',
                                text: '',
                                }).then((result) => {
                                if (result.dismiss === 'timer') {
                                    $('#form_submit').trigger('click');
                                } else {
                                    $('#form_submit').trigger('click');
                                }
                            })
                        }
                    }
                });
            }
        });
    </script>
@endsection
