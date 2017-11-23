@section('script.dkjNotification')
<script>
    var department_id_info  =$("select[name='department_id_info']").val();
    $( "#check_users" ).on('click', function() {
        $.ajax({
            type: "POST",
            url: '{{ route('api.getUsers') }}',
            data: {"department_id_info":department_id_info},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response1);

//                for (var i = 0; i < 6; i++) {
//                    $("#user" + response[i].id + " td[name='status']").text("Odsłuchanych (" + response[i].yanky_count + ")");
//                    $("#user" + response[i].id + " td[name='count_user_yanek']").text(response[i].bad);
//                    $("#user" + response[i].id + " td[name='status']").removeClass("alert-danger");
//                    $("#user" + response[i].id + " td[name='status']").addClass("alert-success");
//                }
            }
        });
    });
</script>
@endsection
