@props([
	'name', 
	'title', 
	'isChecked' => 0, 
	'id' => null,
	'value' => 1
])

<label class="custom-control custom-checkbox fs-12">
	<input
		{{ $attributes->merge([
			'type' => 'checkbox',
			'class' => 'custom-control-input',
			'checked' => old($name, $isChecked) == 1,
			'value' => $value,
			'name' => $name,
			'id' => $id ?? $name
		]) }}
	/>
	<span class="custom-control-label">{{ $title }}</span>
</label>
