<div class="form-group">
	<label for="{{ $name }}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
        <input type="text" list="{!! $name !!}_list" name="{{ $name }}" value="{{ $value }}" class="form-control" id="{{ $name }}" placeholder="{{ $label }}"/>
        <datalist id="{!! $name !!}_list">
            @foreach($field->getOptions() as $key => $choice)
                <option>{!! $choice !!}</option>
            @endforeach
        </datalist>
        <small id="{!! $name !!}HelpBlock" class="form-text text-muted">
            {!! $help !!}
        </small>
    </div>
</div>