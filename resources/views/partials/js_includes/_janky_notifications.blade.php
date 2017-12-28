<script>
$(document).ready(function(){
    $.ajax({
        type: "POST",
        url: '{{ route('api.getNotficationsJanky') }}',
        data: {},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          var count = response[0].sum_janky;

          if (count != 0) {
              $('#blok').css('display', 'block');
              $('#notification_janky_count').text(count);
              $(".container-fluid").css('margin-top','70px');
          }
        }
    });
});
</script>
