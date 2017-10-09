<!-- Modal -->
<div id="registerModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rejestracja Godzin</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="dtp_input3" class="col-md-5 control-label">Godzina przyjścia do pracy:</label>
                    <div class="input-group date form_time col-md-5" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                        <input id="register_start" class="form-control" size="16" type="text" value="" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input3" value="" /><br/>
                </div>
                <div class="form-group">
                    <label for="dtp_input3" class="col-md-5 control-label">Godzina zakończenia pracy:</label>
                    <div class="input-group date form_time col-md-5" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                        <input id="register_stop" class="form-control" size="16" type="text" value="" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input3" value="" /><br/>
                </div>
                <button id="register_hour" type="submit" class="btn btn-primary" name="register" style="font-size:18px; width:100%;">Zarejestruj</button>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>

            </div>
        </div>

    </div>
</div>


@section('script.register')
    <script>

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
        $( "#register_hour" ).click(function() {
            var register_start = 0;
            var register_stop = 0;
            register_start = $('#register_start').val();
            register_stop = $('#register_stop').val();
            if(register_start == null || register_start =='')
            {
                alert('Brak godziny rozpoczęcia pracy');
            }else if (register_stop == null || register_stop =='')
                alert('Brak godziny zakączenia pracy');
            else
            {
                $.ajax({
                    type: "POST",
                    url: '{{ url('register_hour') }}',
                    data: {"register_start": register_start, "register_stop": register_stop},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            }

        });



    </script>
@endsection