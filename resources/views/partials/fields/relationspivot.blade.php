<?php
list($table, $field) = explode('.', $attrs['field']);
//list($pivot_table, $pivot_field) = explode('.', $attrs['pivot_field']);
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
        <div class="card">
            <div class="card-body" style="height:25rem;overflow-y:auto;">
                @foreach(DB::table($table)->pluck($field, 'id') as $key=>$static_value)
                    <div class="form-group">
                        <label for="{!! $name !!}[{!! $key !!}]">{{ $static_value }}</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="checkbox" id="{!! $name !!}[{!! $key !!}]" name="{!! $name !!}[{!! $key !!}][checked]" @if(isset($values[$key])) checked="checked" @endif>
                                </div>
                            </div>
                            @foreach($attrs['pivot_fields'] as $pivot_field)
                                <input type="text" class="form-control" name="{!! $name !!}[{!! $key !!}][{!! $pivot_field !!}]" placeholder="{!! $pivot_field !!}" @if(isset($values[$key])) value="{{ $values[$key]['pivot'][$pivot_field] }}" @else value="" @endif>
                            @endforeach
                        </div><!-- /input-group -->
                    </div><!-- /form-group -->
                @endforeach
            </div>
        </div>
    </div>
</div>