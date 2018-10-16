<style>
    .OneToSix:hover div{
        border-color: rgba(0, 0, 0, 0.51);
    }
</style>

@if(isset($rating))
    @for($i = 6 ; $i >= 1 ; $i --)
        @if($rating == $i)
        <label class="OneToSix">
            <div class="well well-sm" style="border-radius: 50%; padding: 1em">
                <input type="radio" name="{{$radioName}}" value="{{$i}}" checked="checked" disabled="disabled"> {{$i}}
            </div>
        </label>
        @endif
    @endfor
@else
    @for($i = 6 ; $i >= 1 ; $i --)
        <label class="OneToSix">
            <div class="well well-sm" style="border-radius: 50%; padding: 1em">
                <input type="radio" name="{{$radioName}}" value="{{$i}}" > {{$i}}
            </div>
        </label>
    @endfor
@endif