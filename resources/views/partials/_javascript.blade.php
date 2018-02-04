<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
{{--<script src="{{ asset('/js/bootstrap.min.js')}}"></script>--}}
{{--<script src="{{ asset('/vendor/bootstrap/js/bootstrap.min.js')}}"></script>--}}
<script src="{{ asset('/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{ asset('/js/bootstrapLanguage.js')}}"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="{{ asset('/vendor/metisMenu/metisMenu.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="{{ asset('/js/sb-admin-2.js')}}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
{{-- Here go includes from folder js_includes --}}

    {{-- Include JS for moving notifications div--}}
    @include('partials.js_includes._janky_notifications')

    {{-- Include JS for multiple table sliders --}}
    @include('partials.js_includes._sliders')

    {{-- Include JS for sound notifications--}}
    @include('partials.js_includes._sound_notifications')

    {{-- Include small janky table for DKJ--}}
    @include('partials.js_includes._janky_table_small')

    {{-- Include big janky table for DKJ--}}
    @include('partials.js_includes._janky_table_big')

    {{-- Include JS for change department--}}
    @include('partials.js_includes._change_department')

    {{-- Include JS for notifications--}}
    @include('partials.js_includes._notifications')

    {{-- Include JS for users table--}}
    @include('partials.js_includes._users_table')

{{-- End of includes --}}

<script>
/*
$(document).ready(function() {

    var menu_visible = localStorage.menu_visible;
    var windowMenu = $('#menu-toggle').attr('aria-pressed');

    if (menu_visible == 'false') {
        $('#menu-toggle').attr('aria-pressed', true);
        $('#menu-toggle').addClass('active');
}


// });
// window.onfocus = function() {
//     checkForMenu();
// };
// function checkForMenu(){
//     var menu_visible = localStorage.menu_visible;
// console.log('focus');
//     var windowMenu = $('#menu-toggle').attr('aria-pressed');
//     console.log(menu_visible+ " " +windowMenu);
//     if (menu_visible == 'true' && windowMenu == 'true') {
//         $('#menu-toggle').attr('aria-pressed', false);
//         $('#sidebar-wrapper').fadeIn(0);
//     }
//
//     if (menu_visible == 'false' && windowMenu == 'false') {
//         $('#menu-toggle').attr('aria-pressed', true);
//         $('#sidebar-wrapper').fadeOut(0);
//         $("#wrapper").toggleClass("toggled");
//         $('#wrapper.toggled').find("#sidebar-wrapper").find(".collapse").collapse('hide');
//     }
// }
*/

//Auto resize

$(window).on('resize',function (e) {
    let side_bar_status = $('#sidebar-wrapper').css('display'); //none or block
    if($(window).width() <= 880 && side_bar_status == 'block'){
        console.log();
        $('.toggle-on').trigger('click');
    }else if($(window).width() > 880 && side_bar_status == 'none'){
        console.log();
        $('.toggle-on').trigger('click');
    }
});



if (typeof(Storage) !== "undefined") {
  if (!localStorage.menu_visible) {
      localStorage.setItem("menu_visible", "true");
  }
}

$(document).ready(function() {
    var menu_visible = localStorage.menu_visible;

    let side_bar_status = $('#sidebar-wrapper').css('display'); //none or block

    if($(window).width() <= 880 && side_bar_status == 'block'){
        console.log();
        $('.toggle-on').trigger('click');
    }else if($(window).width() > 880 && side_bar_status == 'none'){
        console.log();
        $('.toggle-on').trigger('click');
    }


    if (menu_visible == 'false') {
        $('#menu-toggle').prop('checked', true).change();
        $('#sidebar-wrapper').fadeOut(0);
        localStorage.setItem("menu_visible", "false");
        $("#wrapper").toggleClass("toggled");
        $('#wrapper.toggled').find("#sidebar-wrapper").find(".collapse").collapse('hide');
    }
});

$('#menu-toggle').change(function() {

      var menu_visible = localStorage.menu_visible;
      if (menu_visible == 'true') {
          $('#sidebar-wrapper').fadeOut(0);
          /*$('#menu-toggle').attr('aria-pressed', true);*/
          localStorage.setItem("menu_visible", "false");
      } else {
          $('#sidebar-wrapper').fadeIn(0);
          /*$('#menu-toggle').attr('aria-pressed', false);*/
          localStorage.setItem("menu_visible", "true");
      }

      $("#wrapper").toggleClass("toggled");
      $('#wrapper.toggled').find("#sidebar-wrapper").find(".collapse").collapse('hide');

});



$('#blok').on('click', () => {
    window.location.replace("{{URL::to('/dkjVerification/')}}");
});

setInterval(function () {
    countNotifications();
},60000);

$(document).ready(function(){
    countNotifications();
});

</script>
