<script>
let ajaxRunningTableBig = false;
$(document).ready(function(){
    $( "#check_messages_dkj" ).on('click', function() {
        if (ajaxRunningTableBig == false) {
            ajaxRunningTableBig = true;
            $.ajax({
                type: "POST",
                url: '{{ route('api.getStatsDkj') }}',
                data: {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    for (var i = 0; i < response.length; i++) {
                      if(response[i].type == 'Badania/Wysyłka') { // tutaj jezeli sa 2 rodzaje oddziału (badania/wysyłka)
                          if(response[i].research_all > 0) {
                              $("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].research_all + ")");
                              $("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].research_janky_count);
                              $("#" + response[i].department_info_id + "dkjstatus td[name='undone']").text(response[i].manager_research_janky_count);
                              $("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
                              $("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");
                          }
                          if(response[i].shipping_all > 0) {
                              $("#" + response[i].department_info_id * (-1) + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].shipping_all + ")");
                              $("#" + response[i].department_info_id * (-1) + "dkjstatus td[name='count_yanek']").text(response[i].shipping_janky_count);
                              $("#" + response[i].department_info_id * (-1) + "dkjstatus td[name='undone']").text(response[i].manager_shipping_janky_count);
                              $("#" + response[i].department_info_id * (-1) + "dkjstatus td[name='status']").removeClass("alert-danger");
                              $("#" + response[i].department_info_id * (-1) + "dkjstatus td[name='status']").addClass("alert-success");
                          }
                      } else if(response[i].type == 'Badania') { // tutaj jezeli nie ma oddział nie jest podzielony na badania/wysyłkę
                          $("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].all_check_talk + ")");
                          $("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].all_bad);
                          $("#" + response[i].department_info_id + "dkjstatus td[name='undone']").text(response[i].manager_research_janky_count);
                          $("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
                          $("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");

                      } else if (response[i].type == 'Wysyłka') {
                          $("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].all_check_talk + ")");
                          $("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].all_bad);
                          $("#" + response[i].department_info_id + "dkjstatus td[name='undone']").text(response[i].manager_shipping_janky_count);
                          $("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
                          $("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");
                      }
                    }
                    $('#check_messages_dkj').html('<i class="fa fa-envelope fa-fw"></i><i class="fa fa-caret-down"></i>');
                    ajaxRunningTableBig = false;
                }
            });
        }

    });
});
</script>
