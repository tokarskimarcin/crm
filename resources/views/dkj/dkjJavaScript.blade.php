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
                tr = "";
                for (var i = 0; i < response['users'].length; i++) {
                    tr+="<tr>";
                    tr+= "<td>"+(i+1)+"</td>";
                    tr+= "<td>"+response['users'][i].first_name+" "+response['users'][i].last_name+"</td>";

                    for (var j = 0; j < response['users_statistic'].length; j++) {
                        if(response['users'][i].id == response['users_statistic'][i].id_user)
                        {
                            tr+= "<td>"+response['users_statistic'][i].count+"</td>";
                            tr+= "<td>"+response['users_statistic'][i].bad+"</td>";
                        }
                    }
                    tr+="</tr>";
//                    $("#user" + response[i].id + " td[name='status']").text("Odsłuchanych (" + response[i].yanky_count + ")");
//                    $("#user" + response[i].id + " td[name='count_user_yanek']").text(response[i].bad);
//                    $("#user" + response[i].id + " td[name='status']").removeClass("alert-danger");
//                    $("#user" + response[i].id + " td[name='status']").addClass("alert-success");
                }
                $("#consultantTable").find('tbody').html( tr );
            }
        });
    });
</script>
@endsection
