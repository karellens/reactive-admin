<div class="form-group">
    <label for="{{ $name }}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
        <textarea name="{{ $name }}" class="form-control" id="{{ $name }}" placeholder="{{ $label }}" data-type="wysihtml5" rows="6">{{ $value }}</textarea>
        <small id="{!! $name !!}HelpBlock" class="form-text text-muted">
            {!! $help !!}
        </small>
    </div>
</div>