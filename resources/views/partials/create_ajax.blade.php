@foreach($config['edit_fields'] as $field => $attrs)
	@include(
		'reactiveadmin::partials.fields.'.$attrs['type'],
		array_merge($attrs, [
			'name'	=> $alias.'['.$field.']',
			'value'	=> isset(old($alias)[$field]) ? old($alias)[$field] : '',
			'label'	=> $attrs['title'],
		])
	)
@endforeach