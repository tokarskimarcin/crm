<script>
function countNotifications() {
  $.ajax({
          type: "POST",
          url: '{{ route('api.itCountNotifications') }}',
          data: {},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
              var container = document.getElementById('show_notification_count');
              if (container != null) {
                container.style.visibility = "hidden";
                if(!isNaN(response) && response > 0){
                    container.style.visibility = "visible";
                    container.setAttribute('data-count',response);
                }
                else{
                    container.style.visibility = "hidden";
                }
              }
          }
      });
}
let ajaxRunningNotifications = false;
$(document).ready(function(){
    $("#it_support").on('click', function() {
        if (ajaxRunningNotifications == false) {
            ajaxRunningNotifications = true;
            var department_info_id = $("#change_department").val();
            $("#it_notifications").empty();
            $("#it_support").css("pointer-events", "none");
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
                        countNotifications();
                        clickDisabled = true;
                        setTimeout(function(){clickDisabled = false;}, 2000);
                        if(response.length == 0) {
                            $("#it_notifications").append("<li>Brak nowych zgłoszeń!</li>");
                        }
                        for (var i = 0; i < response.length; i++) {
                            $("#it_notifications").append("<li><a style='background-color: #fff' href='{{URL::to('/show_notification/')}}/" + response[i].id + "'><div><i class='fa fa-comment fa-fw'></i><span> " +response[i].notification_type.name+": "+ response[i].title +" ("+response[i].user.last_name+")"+ "</span></div></a></li><li class='divider'></li>");
                        }
                        $("#it_support").css("pointer-events", "auto");
                        ajaxRunningNotifications = false;
                    }
                });
        }
    });
});
</script>
