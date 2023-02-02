<div class="form-group d-flex justify-content-center">
    @component($typeForm, get_defined_vars())
        <div data-controller="checkbox"
            data-checkbox-indeterminate="{{$indeterminate}}">
            <input hidden name="{{$attributes['name']}}" value="{{$attributes['novalue']}}">
            <div class="form-check">
                <input value="{{$attributes['yesvalue']}}"
                    {{ $attributes }}
                    @if(isset($attributes['value']) && $attributes['value']) checked @endif
                >
                <label class="form-check-label" for="{{$id}}">{{$placeholder ?? ''}}</label>
            </div>
        </div>
    @endcomponent

</div>