<?php
list($table, $field) = explode('.', $attrs['field']);
list($pivot_table, $pivot_field) = explode('.', $attrs['pivot_field']);
$values = [];
if(isset($value))
{
    foreach ($value->toArray() as $k => $v)
    {
        $values[$v['id']] = $v;
    }
}
?>
<div class="form-group">
    <label for="{!! $name !!}" class="col-sm-2 control-label">{!! $attrs['title'] !!}</label>
    <div class="col-sm-10">
        <div class="panel panel-default">
            <div class="panel-body">
                @foreach(DB::table($table)->lists($field, 'id') as $key=>$static_value)
                    <div class="form-group">
                        <label for="">{{ $static_value }}</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <input type="checkbox" name="{!! $name !!}[{!! $key !!}][checked]" @if(isset($values[$key])) checked="checked" @endif>
                            </span>
                            <input type="text" class="form-control" name="{!! $name !!}[{!! $key !!}][{!! $pivot_field !!}]" @if(isset($values[$key])) value="{{ $values[$key]['pivot'][$pivot_field] }}" @else value="" @endif>
                        </div><!-- /input-group -->
                    </div><!-- /form-group -->
                @endforeach
            </div>
        </div>
    </div>
</div>