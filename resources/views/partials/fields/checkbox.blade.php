<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox-inline">
            <input type="hidden" name="{{ $name }}" value="0" >
            <label>
                <input name="{{ $name }}" type="checkbox" value="1"@if($value) checked="checked"@endif> {{ $label }}
            </label>
        </div>
        <small id="{!! $name !!}HelpBlock" class="form-text text-muted">
            {!! $help !!}
        </small>
    </div>
</div>