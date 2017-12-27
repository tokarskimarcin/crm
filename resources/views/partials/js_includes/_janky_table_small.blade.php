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
            for (var i = 0; i < response.length; i++) {
                if(response[i].type == 'Badania/Wysyłka')
                {
                    if(response[i].research_all > 0) {
                        $("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].research_all + ")");
                        $("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].research_janky_count);
                        $("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
                        $("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");
                    }
                    if(response[i].shipping_all > 0 ) {
                        $("#" + response[i].department_info_id * (-1) + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].shipping_all + ")");
                        $("#" + response[i].department_info_id * (-1) + "dkjstatus td[name='count_yanek']").text(response[i].shipping_janky_count);
                        $("#" + response[i].department_info_id * (-1) + "dkjstatus td[name='status']").removeClass("alert-danger");
                        $("#" + response[i].department_info_id * (-1) + "dkjstatus td[name='status']").addClass("alert-success");
                    }
                }else
                {
                    $("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].all_check_talk + ")");
                    $("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].all_bad);
                    $("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
                    $("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");
                }
            }
        }
    });
});
</script>
