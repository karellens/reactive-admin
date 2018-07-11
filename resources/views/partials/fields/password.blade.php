<div class="form-group">
    <label for="{{ $name }}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
        <input type="password" name="{{ $name }}" value="" class="form-control" id="{{ $name }}" placeholder="{{ $label }}">
    </div>
</div>
<div class="form-group">
    <label for="{{ $name }}_confirmation" class="col-sm-2 control-label">{{ $label }} Confirmation</label>
    <div class="col-sm-10">
        <input type="password" name="{{ $name }}_confirmation" value="" class="form-control" id="{{ $name }}_confirmation" placeholder="{{ $label }} Confirmation">
    </div>
</div>