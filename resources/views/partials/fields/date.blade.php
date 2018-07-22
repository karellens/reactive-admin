<div class="form-group">
    <label for="{{ $name }}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
        <input type="date" name="{{ $name }}" value="{{ \Carbon\Carbon::parse($value)->format('Y-m-d') }}" class="form-control" id="{{ $name }}" placeholder="{{ $label }}">
        <small id="{!! $name !!}HelpBlock" class="form-text text-muted">
            {!! $help !!}
        </small>
    </div>
</div>