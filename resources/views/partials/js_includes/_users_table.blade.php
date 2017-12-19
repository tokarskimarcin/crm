<script>
var department_id_info = $("select[name='department_id_info']").val();
$( "#check_users" ).on('click', function() {
    $.ajax({
        type: "POST",
        url: '{{ route('api.getUsers') }}',
        data: {"department_id_info":department_id_info},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response['users'].length == 0) {
              tr = '<tr class="text-center"><td colspan="5">Brak aktywnych użytkowników!</td></tr>';
          } else {
              tr = "";
              for (var i = 0; i < response['users'].length; i++) {
                  tr+="<tr>";
                  tr+= "<td>"+(i+1)+"</td>";
                  tr+= "<td>"+response['users'][i].first_name+" "+response['users'][i].last_name+"</td>";

                  for (var j = 0; j < response['users_statistic'].length; j++) {
                      if(response['users'][i].id == response['users_statistic'][j].id_user)
                      {
                          tr+= "<td>"+response['users_statistic'][i].count+"</td>";
                          tr+= "<td>"+response['users_statistic'][i].bad+"</td>";
                      }
                  }
                  tr+="</tr>";
            }
          }
          $("#consultantTable").find('tbody').html( tr );
        }
    });
});
</script>
