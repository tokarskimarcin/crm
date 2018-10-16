<style>
    .NoAverageYes:hover div{
        border-color: rgba(0, 0, 0, 0.51);
    }
</style>
@if(isset($rating))
    @if($rating == 3)
    <label class="NoAverageYes">
        <div class="well well-sm" style="background-color: rgba(0,175,0,0.75); border-radius: 25%; padding: 2em">
            <input type="radio" name="{{$radioName}}" checked="checked" disabled="disabled" value="3"> TAK
        </div>
    </label>
    @endif
    @if($rating == 2) <label class="NoAverageYes">
        <div class="well well-sm" style="background-color: rgba(255,255,0,0.75); border-radius: 25%; padding: 2em">
            <input type="radio" name="{{$radioName}}"  checked="checked" disabled="disabled" value="2"> ŚREDNIO
        </div>
    </label>
    @endif
    @if($rating == 1)
    <label class="NoAverageYes">
        <div class="well well-sm" style="background-color: rgba(255,0,0,0.75); border-radius: 25%; padding: 2em">
            <input type="radio" name="{{$radioName}}"  checked="checked" disabled="disabled" value="1"> NIE
        </div>
    </label>
    @endif
@else
    <label class="NoAverageYes">
        <div class="well well-sm" style="background-color: rgba(0,175,0,0.75); border-radius: 25%; padding: 2em">
            <input type="radio" name="{{$radioName}}" value="3"> TAK
        </div>
    </label>
    <label class="NoAverageYes">
        <div class="well well-sm" style="background-color: rgba(255,255,0,0.75); border-radius: 25%; padding: 2em">
            <input type="radio" name="{{$radioName}}" value="2"> ŚREDNIO
        </div>
    </label>
    <label class="NoAverageYes">
        <div class="well well-sm" style="background-color: rgba(255,0,0,0.75); border-radius: 25%; padding: 2em">
            <input type="radio" name="{{$radioName}}" value="1"> NIE
        </div>
    </label>
@endif
