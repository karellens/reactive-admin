<div class="form-group">
    <label for="{{ $name }}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
        <textarea name="{{ $name }}" class="form-control" id="{{ $name }}" placeholder="{{ $label }}" data-type="wysihtml5" rows="6">{{ $value }}</textarea>
    </div>
</div>