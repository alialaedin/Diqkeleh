@props([
	'name',
	'type',
	'id' => null,
	'defaultValue' => null
])

@php
	$value = old($name, request($name, $defaultValue));
@endphp

<input 
	{{ $attributes->merge([
		'class' => 'form-control fs-12',
		'name' => $name,
		'type' => $type,
		'id' => $id ?? $name,
		'value' => $value,
	]) }}
/>
@error($name)
  <span class="text-danger mt-2 fs-10">{{ $message }}</span>
@enderror
