<div class="form-group">
	<label for="{!! $name !!}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
        <select name="{!! $name !!}" id="{!! $name !!}" class="form-control">
            @foreach($field->getOptions() as $option_key => $option_choice)
                <option value="{!! $option_key !!}" @if(isset($value) && $option_key==$value) selected @endif>{!! $option_choice !!}</option>
            @endforeach
        </select>
    </div>
</div>