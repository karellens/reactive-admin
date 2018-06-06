<div class="form-group">
	<label for="{{ $name }}" class="col-sm-2 control-label">{{ $attrs['title'] }}</label>
    <div class="col-sm-10">
        <select name="{!! $name !!}" id="{!! $name !!}" class="form-control">
            @foreach($attrs['field'] as $key=>$value)
                <option value="{!! $key !!}" @if(isset($row) && $key==$row->$name) selected @endif>{!! $value !!}</option>
            @endforeach
        </select>
    </div>
</div>