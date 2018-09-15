<?php
$values_dict = [];
if(isset($value) && $value)
{
    if(is_array($value)) {
        if(old($resource->getAlias().'.'.$key)) {
            // filter old
            $values_dict = array_where($value, function ($value, $key) {
                return isset($value['checked']);
            });
        } else {
            dump('wtf?');
            $values_dict = $value;
        }
    } else {
        $values_dict = $value->getDictionary();
    }
}
?>
<div class="form-group">
    <label for="{!! $name !!}" class="col-sm-2 control-label">{!! $label !!}</label>
    <div class="col-sm-10">
        <div class="card">
            <div class="card-body" style="height:25rem;overflow-y:auto;">
                @foreach($field->getOptions() as $opt_id => $opt_label)
                    <div class="form-group">
                        <label for="{!! $name !!}[{!! $opt_id !!}]">{{ $opt_label }}</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="{!! $name !!}[{!! $opt_id !!}]" name="{!! $name !!}[{!! $opt_id !!}][checked]" @if(isset($values_dict[$opt_id])) checked="checked" @endif>
                                        <label class="custom-control-label" for="{!! $name !!}[{!! $opt_id !!}]"></label>
                                    </div>
                                </div>
                            </div>
                            @foreach($field->getPivotFields() as $pivot_key => $pivot_label)
                                <input type="text" class="form-control" name="{!! $name !!}[{!! $opt_id !!}][{!! $pivot_key !!}]" placeholder="{!! $pivot_label !!}" @if(isset($values_dict[$opt_id])) value="{{ $values_dict[$opt_id]['pivot'][$pivot_key] ? $values_dict[$opt_id]['pivot'][$pivot_key] : $values_dict[$opt_id][$pivot_key] }}" @else value="" @endif>
                            @endforeach
                        </div><!-- /input-group -->
                    </div><!-- /form-group -->
                @endforeach
            </div>
        </div>
        <small id="{!! $name !!}HelpBlock" class="form-text text-muted">
            {!! $help !!}
        </small>
    </div>
</div>