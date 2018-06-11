@foreach($config['edit_fields'] as $field => $attrs)
    @include(
        'reactiveadmin::partials.fields.'.$attrs['type'],
        array(
            'name'	=> $field,
            'value'	=> old($field),
            'label'	=> $attrs['title'],
        )
    )
@endforeach