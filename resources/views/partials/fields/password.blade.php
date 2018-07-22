<div class="form-group">
    <label for="{!! $name !!}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
        <input type="password" name="{!! $name !!}" value="" class="form-control" id="{!! $name !!}" placeholder="{{ $label }}">
        <small id="{!! $name !!}HelpBlock" class="form-text text-muted">
            {!! $help !!}
        </small>
    </div>
</div>
<div class="form-group">
    <label for="{!! $resource->getAlias().'['.$key.'_confirmation]' !!}" class="col-sm-2 control-label">{{ $label }} Confirmation</label>
    <div class="col-sm-10">
        <input type="password" name="{!! $resource->getAlias().'['.$key.'_confirmation]' !!}" value="" class="form-control" id="{!! $resource->getAlias().'['.$key.'_confirmation]' !!}" placeholder="{{ $label }} Confirmation">
    </div>
</div>