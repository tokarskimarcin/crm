<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
{{--<script src="{{ asset('/js/bootstrap.min.js')}}"></script>--}}
{{--<script src="{{ asset('/vendor/bootstrap/js/bootstrap.min.js')}}"></script>--}}
<script src="{{ asset('/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{ asset('/js/bootstrapLanguage.js')}}"></script>
<script src="{{ asset('/js/marquee.js')}}"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="{{ asset('/vendor/metisMenu/metisMenu.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="{{ asset('/js/sb-admin-2.js')}}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>


    $(document).ready(function() {
        $(function (){

            $('.simple-marquee-container').SimpleMarquee();

        });
        setInterval(function () {
            {{--$.ajax({--}}
                {{--type: "POST",--}}
                {{--url: '{{ route('api.getStatsDkj') }}',--}}
                {{--data: {},--}}
                {{--headers: {--}}
                    {{--'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
                {{--},--}}
                {{--success: function (response) {--}}
                    {{--console.log(response);--}}
                {{--}--}}
            {{--});--}}
            //alert('123');
        },60000);
    });


$(function(){
	  $(".wrapper1").scroll(function(){
	    $(".wrapper2").scrollLeft($(".wrapper1").scrollLeft());
	  });
	  $(".wrapper2").scroll(function(){
	    $(".wrapper1").scrollLeft($(".wrapper2").scrollLeft());
	  });
    $(".wrapper3").scroll(function(){
	    $(".wrapper4").scrollLeft($(".wrapper3").scrollLeft());
	  });
	  $(".wrapper4").scroll(function(){
	    $(".wrapper3").scrollLeft($(".wrapper4").scrollLeft());
	  });
    $(".wrapper5").scroll(function(){
      $(".wrapper6").scrollLeft($(".wrapper5").scrollLeft());
    });
    $(".wrapper6").scroll(function(){
      $(".wrapper5").scrollLeft($(".wrapper6").scrollLeft());
    });
	});

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
                        $("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].research_all + ")");
                        $("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].research_janky_count);
                        $("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
                        $("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");

                        $("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].shipping_all + ")");
                        $("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='count_yanek']").text(response[i].shipping_janky_count);
                        $("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='status']").removeClass("alert-danger");
                        $("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='status']").addClass("alert-success");
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

$(document).ready(function(){
    $( "#check_messages_dkj" ).on('click', function() {
        $.ajax({
            type: "POST",
            url: '{{ route('api.getStatsDkj') }}',
            data: {},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
              console.log(response);
                for (var i = 0; i < response.length; i++) {
                  if(response[i].type == 'Badania/Wysyłka') { // tutaj jezeli sa 2 rodzaje oddziału (badania/wysyłka)
                      $("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].research_all + ")");
                      $("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].research_janky_count);
                      $("#" + response[i].department_info_id + "dkjstatus td[name='undone']").text(response[i].manager_research_janky_count);
                      $("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
                      $("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");

                      $("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].shipping_all + ")");
                      $("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='count_yanek']").text(response[i].shipping_janky_count);
                      $("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='undone']").text(response[i].manager_shipping_janky_count);
                      $("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='status']").removeClass("alert-danger");
                      $("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='status']").addClass("alert-success");

                  } else { // tutaj jezeli nie ma oddział nie jest podzielony na badania/wysyłkę
                      $("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].all_check_talk + ")");
                      $("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].all_bad);
                      $("#" + response[i].department_info_id + "dkjstatus td[name='undone']").text(response[i].manager_research_janky_count);
                      $("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
                      $("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");
                  }
                }
                $('#check_messages_dkj').html('<i class="fa fa-envelope fa-fw"></i><i class="fa fa-caret-down"></i>');
            }
        });
    });
});

    //ajax do ding ding
@if(Auth::user()->user_type_id == 13)
    setInterval(function(){
      $.ajax({
          type: "POST",
          url: '{{ route('api.getStatsDkj') }}',
          data: {},
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
              var sound = false;
							console.log(response);
              for (var i = 0; i < response.length; i++) {

									if (response[i].type == 'Badania/Wysyłka') {
											var countMeShipping = $("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='undone']").text();
											var countMeResearch = $("#" + response[i].department_info_id + "dkjstatus td[name='undone']").text();
											if ((response[i].manager_research_janky_count != countMeResearch && response[i].manager_research_janky_count > countMeResearch)
												|| response[i].manager_shipping_janky_count != countMeShipping && response[i].manager_shipping_janky_count > countMeShipping) {
													var sound = true;
											}

									} else if (response[i].type == 'Badania') {
											var countMe = $("#" + response[i].department_info_id + "dkjstatus td[name='undone']").text();
											if (response[i].manager_research_janky_count != countMe && response[i].manager_research_janky_count > countMe) {
													var sound = true;
											}
									} else if (response[i].type == 'Wysyłka') {
											var countMe = $("#" + response[i].department_info_id + "dkjstatus td[name='undone']").text();
											if (response[i].manager_shipping_janky_count != countMe && response[i].manager_shipping_janky_count > countMe) {
													var sound = true;
											}
									}
              }
							//tutaj ding
							if (sound == true) {
									var snd = new Audio("{{asset('assets/1.mp3')}}");
                  snd.play();
                  sound = false;
                  $('#check_messages_dkj').html('(!) <i class="fa fa-envelope fa-fw"></i><i class="fa fa-caret-down"></i>');

									for (var i = 0; i < response.length; i++) {
										if(response[i].type == 'Badania/Wysyłka') { // tutaj jezeli sa 2 rodzaje oddziału (badania/wysyłka)
												$("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].research_all + ")");
												$("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].research_janky_count);
												$("#" + response[i].department_info_id + "dkjstatus td[name='undone']").text(response[i].manager_research_janky_count);
												$("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
												$("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");

												$("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].shipping_all + ")");
												$("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='count_yanek']").text(response[i].shipping_janky_count);
												$("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='undone']").text(response[i].manager_shipping_janky_count);
												$("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='status']").removeClass("alert-danger");
												$("#" + response[i].department_info_id*(-1) + "dkjstatus td[name='status']").addClass("alert-success");

										} else { // tutaj jezeli nie ma oddział nie jest podzielony na badania/wysyłkę
												$("#" + response[i].department_info_id + "dkjstatus td[name='status']").text("Odsłuchany (" + response[i].all_check_talk + ")");
												$("#" + response[i].department_info_id + "dkjstatus td[name='count_yanek']").text(response[i].all_bad);
												$("#" + response[i].department_info_id + "dkjstatus td[name='undone']").text(response[i].manager_research_janky_count);
												$("#" + response[i].department_info_id + "dkjstatus td[name='status']").removeClass("alert-danger");
												$("#" + response[i].department_info_id + "dkjstatus td[name='status']").addClass("alert-success");
										}
									}
							}
          }
      });
    }, 60000);
@endif

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

    function countNotifications() {
      $.ajax({
              type: "POST",
              url: '{{ route('api.itCountNotifications') }}',
              data: {},
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(response) {
                $('#show_notification_count').text(response);
              }
          });
    }

    $(document).ready(function(){
        $("#it_support").on('click', function() {
            var department_info_id = $("#change_department").val();
            $("#babum").empty();
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
                            $("#babum").append("<li>Brak nowych zgłoszeń!</li>");
                        }
                        for (var i = 0; i < response.length; i++) {
                            $("#babum").append("<li><a href='{{URL::to('/show_notification/')}}/" + response[i].id + "'><div><i class='fa fa-comment fa-fw'></i><span> " + response[i].title + "</span></div></a></li><li class='divider'></li>");
                        }
                        $("#it_support").css("pointer-events", "auto");
                    }
                });
        });
    });
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

    $(document).ready(function(){
        countNotifications();
    });
</script>
