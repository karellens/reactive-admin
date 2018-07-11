<div class="form-group">
    <label for="{{ $name }}" class="col-sm-2 control-label">{{ $label }}</label>
    <div class="col-sm-10">
<?php
    list($table, $field) = explode('.', $attrs['field']);

    if(is_a($value, 'Illuminate\Database\Eloquent\Collection')) {
        $multiple = array('multiple' => 'true');
        $values = array_get($value->toArray(), 'id');
    } else {
        $multiple = array();
        $values = is_object($value) ? $value->toArray()['id'] : array($value);
    }
?>
        <select name="{!! $name !!}" id="{!! $name !!}" data-placeholder="{!! $label !!}" class="form-control chosen-select" @if($multiple) multiple="multiple" @endif>
            @foreach(DB::table($table)->lists($field, 'id') as $key=>$value)
                <option value="{!! $key !!}" @if(in_array($key, $values)) selected @endif)>{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>