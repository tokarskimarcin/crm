<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
{{--<script src="{{ asset('/js/bootstrap.min.js')}}"></script>--}}
{{--<script src="{{ asset('/vendor/bootstrap/js/bootstrap.min.js')}}"></script>--}}
<script src="{{ asset('/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{ asset('/js/bootstrap-datetimepicker.pl.js')}}"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="{{ asset('/vendor/metisMenu/metisMenu.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="{{ asset('/js/sb-admin-2.js')}}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>
    $( "#check_messages" ).on('click', function() {
        $.ajax({
            type: "POST",
            url: '{{ route('api.getStats') }}',
            data: {},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                for (var i = 0; i < response.length; i++) {
                    $("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].yanky_count + ")");
                    $("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].bad);
                    $("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
                    $("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");
                }
            }
        });
    });

    $("#change_department").on('change', function() {
        var department_info_id = $("#change_department").val();
        $.ajax({
            type: "POST",
            url: '{{ route('api.changeDepartment') }}',
            data: {
              "department_info_id":department_info_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                location.reload();
            }
        });
    });

    $(document).ready(function(){

        $("#it_support").on('click', function() {
            var department_info_id = $("#change_department").val();
            $("#it_table tr").remove();
                $.ajax({
                    type: "POST",
                    url: '{{ route('api.itSupport') }}',
                    data: {
                      "department_info_id":department_info_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.length == 0) {
                            $("#it_table tbody").append("<tr><td><li><div class='alert alert-success'>Brak nowych zgłoszeń!</div></li></td></tr>");
                        }
                        for (var i = 0; i < response.length; i++) {
                            $("#babum").append("<li><a href='/show_notification/" + response[i].id + "'><div><i class='fa fa-comment fa-fw'></i><span> " + response[i].title + "</span></div></a></li><li class='divider'></li>");
                        }

                    }
                });
        });

    });
</script>
