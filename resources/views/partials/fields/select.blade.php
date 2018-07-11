<div class="form-group">
	<label for="{{ $name }}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
        <select name="{!! $name !!}" id="{!! $name !!}" class="form-control">
            @foreach($field->getOptions() as $key => $choice)
                <option value="{!! $key !!}" @if(isset($row) && $key==$value) selected @endif>{!! $choice !!}</option>
            @endforeach
        </select>
    </div>
</div>