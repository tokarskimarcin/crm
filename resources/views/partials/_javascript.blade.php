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
