@props([
	'id' => null,
	'name',
	'data',
	'selectLabel' => 'انتخاب',
	'defaultValue' => null,
	'optionValue',
	'optionLabel',
])

@php
	$id = $id ?? $name;
	$isObject = is_object(collect($data)->first());
	$selectedValue = old($name, $defaultValue) ?? request($name, $defaultValue);
@endphp

<select {{ $attributes->merge(['class' => 'form-control fs-12', 'id' => $id, 'name' => $name]) }}>
	<option value="">{{ $selectLabel }}</option>
	@foreach ($data as $option)
		@php
			$value = $isObject ? $option->{$optionValue} : $option[$optionValue];
			$label = $isObject ? $option->{$optionLabel} : $option[$optionLabel];
		@endphp
		<option value="{{ $value }}" @selected($value == $selectedValue)>
			{{ $label }}
		</option>
	@endforeach
</select>

@error($name)
  <span class="text-danger mt-2 fs-10">{{ $message }}</span>
@enderror

@push('SelectComponentScripts')
	<script>
		$('#{{ $id }}').select2({
			placeholder: @json($selectLabel),
			allowClear: true,
		});
	</script>
@endpush
