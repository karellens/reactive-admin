<div class="form-group">
	<label for="{{ $name }}" class="col-sm-2 control-label">{{ $attrs['title'] }}</label>
    <div class="col-sm-10">
        <input type="text" list="{!! $name !!}_list" name="{{ $name }}" value="{{ $value }}" class="form-control" id="{{ $name }}" placeholder="{{ $attrs['title'] }}"/>
        <datalist id="{!! $name !!}_list">
            @foreach($attrs['field'] as $key=>$value)
                <option>{!! $value !!}</option>
            @endforeach
        </datalist>
    </div>
</div>