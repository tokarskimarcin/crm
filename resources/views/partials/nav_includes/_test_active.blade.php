@if(isset($active_test) && $active_test > 0)
<li>
    <span style="margin-right: 20px; font-weight: bold; color:green">
        <a style="color: white" href="{{ URL::to('/all_user_tests') }}">Masz nowy test do rozwiązania!</a>
    </span>
</li>
@endif