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
                console.log(response);

//                for (var i = 0; i < 6; i++) {
//                    $("#" + response[i].department_info_id + " td[name='status']").text("Odsłuchany (" + response[i].yanky_count + ")");
//                    $("#" + response[i].department_info_id + " td[name='count_yanek']").text(response[i].bad);
//                    $("#" + response[i].department_info_id + " td[name='status']").removeClass("alert-danger");
//                    $("#" + response[i].department_info_id + " td[name='status']").addClass("alert-success");
//                }

            }
        });
    });

    $( "#check_users" ).on('click', function() {
        $.ajax({
            type: "POST",
            url: '{{ route('api.getUsers') }}',
            data: {

            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
              console.log(response);

              for (var i = 0; i < 6; i++) {
                  $("#user" + response[i].id + " td[name='status']").text("Odsłuchanych (" + response[i].yanky_count + ")");
                  $("#user" + response[i].id + " td[name='count_user_yanek']").text(response[i].bad);
                  $("#user" + response[i].id + " td[name='status']").removeClass("alert-danger");
                  $("#user" + response[i].id + " td[name='status']").addClass("alert-success");
              }

            }
        });
    });
</script>
