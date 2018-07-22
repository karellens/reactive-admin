<div class="form-group">
    <label for="{{ $name }}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
        <input type="text" name="{{ $name }}" value="{{ $value }}" class="form-control" id="{{ $name }}" placeholder="{{ $label }}">
        <small id="{!! $name !!}HelpBlock" class="form-text text-muted">
            {!! $help !!}
        </small>
    </div>
</div>