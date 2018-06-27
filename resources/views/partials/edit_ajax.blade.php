@foreach($config['edit_fields'] as $field => $attrs)
    @include(
        'reactiveadmin::partials.fields.'.$attrs['type'],
        array_merge($attrs, [
            'name'	=> $alias.'['.$field.']',
            'value' => old($alias.'.'.$field) ? old($alias.'.'.$field) : $row->$field,
            'label' => $attrs['title'],
            'row'   => $row,
        ])
    )
@endforeach