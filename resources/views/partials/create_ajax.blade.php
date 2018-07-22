@foreach($resource->getFields() as $key => $field)
	@include(
		'reactiveadmin::partials.fields.'.$field->getType(),
		[
			'name'	=> $resource->getAlias().'['.$key.']',
			'value'	=> $field->getValue() ? $field->getValue() : old($resource->getAlias().'.'.$key),
			'label'	=> $field->getTitle(),
			'help'	=> $field->getHelp(),
		]
	)
@endforeach