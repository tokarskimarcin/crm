<!-- Modal -->
<div id="addHourModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Dodaj Godzin pracownika</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="dtp_input3" class="col-md-5 control-label">Godzina przyjścia do pracy:</label>
                    <div class="input-group date form_time col-md-5" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                        <input id="accept_start_add" class="form-control" size="16" type="text" value="" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input3" value="" /><br/>
                </div>
                <div class="form-group">
                    <label for="dtp_input3" class="col-md-5 control-label">Godzina zakończenia pracy:</label>
                    <div class="input-group date form_time col-md-5" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
                        <input id="accept_stop_add" class="form-control" size="16" type="text" value="" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input3" value="" /><br/>
                </div>
                @if( Session::get('count_agreement')==1)
                <div class="form-group">
                    <label for="dtp_input3" class="col-md-5 control-label">Liczna Sukcesów: </label>
                    <div class="input-group date col-md-5">
                        <input id="success_add" class="form-control" size="16" type="number" value="0">
                    </div>
                    <input type="hidden" id="dtp_input3" value="" /><br/>
                </div>
                @else
                    <input id="success_add" class="form-control" size="16" type="hidden" value="0">
                @endif
                <button id="add_hour" type="submit" class="btn btn-primary" name="register" style="font-size:18px; width:100%;">Zarejestruj</button>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close" data-dismiss="modal">Anuluj</button>
            </div>
        </div>

    </div>
</div>


@section('script.addhour')
    <script>
            var id = 0;
            var load = 0;
        $('#addHourModal').on('show.bs.modal', function(e) {
            if(load == 0) {
                var $modal = $(this),
                    esseyId = e.relatedTarget.id;
                id = esseyId;
                load++;
            }
        });

            $( ".close" ).click(function() {
                load=0;
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
        $( "#add_hour" ).click(function() {
            var accept_start = 0;
            var accept_stop = 0;
            var success = 0;
            accept_start = $('#accept_start_add').val();
            accept_stop = $('#accept_stop_add').val();
            success = $('#success_add').val();
            if(accept_start == null || accept_start =='')
            {
                alert('Brak godziny rozpoczęcia pracy');
            }else if (accept_stop == null || accept_stop =='')
                alert('Brak godziny zakończenia pracy');
            else if(success <0)
            {
                alert('Liczba zgód nie może być mniejsza niż zero');
            }
            else if(accept_start >= accept_stop)
            {
                alert('Godziny są ustawione niepoprawnie');
            }else
            {
                $(this).attr('disabled',true);
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.addAcceptHour') }}',
                    data: {"accept_start": accept_start,
                        "accept_stop": accept_stop,
                        "success": success,
                        "id_user_date":id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        window.location.reload(true);
                    },
                    error: function(response) {
                        alert('Wystąpił problem z bazą danych. Prosimy spróbuj później.');
                        location.reload();
                    }
                });
            }

        });
    </script>
@endsection
