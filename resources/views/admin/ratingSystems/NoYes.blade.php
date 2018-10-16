<style>
    .NoYes:hover div{
        border-color: rgba(0, 0, 0, 0.51);
        cursor: pointer;
    }
</style>
@if(isset($rating))
    @if($rating == 2)
    <label class="NoYes">
        <div class="well well-sm" style="background-color: rgba(0,175,0,0.75); border-radius: 25%; padding: 2em">
            <input type="radio" name="{{$radioName}}"  checked="checked"disabled="disabled" value="2"> TAK
        </div>
    </label>
    @endif
    @if($rating == 1)
    <label class="NoYes">
        <div class="well well-sm" style="background-color: rgba(255,0,0,0.75); border-radius: 25%; padding: 2em">
            <input type="radio" name="{{$radioName}}"  checked="checked" disabled="disabled" value="1"> NIE
        </div>
    </label>
    @endif
@else
    <label class="NoYes">
        <div class="well well-sm" style="background-color: rgba(0,175,0,0.75); border-radius: 25%; padding: 2em">
            <input type="radio" name="{{$radioName}}" value="2"> TAK
        </div>
    </label>
    <label class="NoYes">
        <div class="well well-sm" style="background-color: rgba(255,0,0,0.75); border-radius: 25%; padding: 2em">
            <input type="radio" name="{{$radioName}}" value="1"> NIE
        </div>
    </label>
@endif