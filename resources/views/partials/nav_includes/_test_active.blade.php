@if(isset($active_test) && $active_test > 0)
<li>
    <span style="margin-right: 10px; font-weight: bold; color:green">
        <a style="" href="{{ URL::to('/all_user_tests') }}">Masz nowy test!</a>
    </span>
</li>
@endif