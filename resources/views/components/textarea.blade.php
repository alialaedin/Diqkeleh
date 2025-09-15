@props([
	'name',
	'rows' => 5,
	'id' => null,
	'defaultValue' => null,
])

<textarea 
	{{
		$attributes->merge([
			'class' => 'form-control fs-12',
			'name' => $name,
			'id' => $id ?? $name,
			'rows' => $rows,
		])
	}}
>
	{!! old($name, $defaultValue) !!}
</textarea>

@error($name)
  <span class="text-danger mt-2" style="font-size: 12px">{{ $message }}</span>
@enderror