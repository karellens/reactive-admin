@foreach($resource->getFields() as $key => $field)
	@include(
		'reactiveadmin::partials.fields.'.$field->getType(),
		[
			'name'	=> $resource->getAlias().'['.$key.']',
			'value'	=> old($resource->getAlias().'.'.$key),
			'label'	=> $field->getTitle(),
		]
	)
@endforeach