@props([
	'action', 
	'method' => 'GET',
	'hasDefaultButtons' => true
])

@php
	$isUpdateMethod = in_array($method, ['PUT', 'PATCH']);
	$formMethod = $isUpdateMethod || $method == 'POST' ? 'POST' : 'GET'; 
@endphp

<form 
	{{ $attributes->merge([
		'action' => $action,
		'method' => $formMethod
	]) }}
>

	@if ($formMethod === 'POST')
		@csrf
	@endif

	@if ($isUpdateMethod)
		@method($method)
	@endif

	{{ $slot }}

	@if ($hasDefaultButtons)
		<x-row class="justify-content-center" style="gap: 8px">
			@switch($method)
				@case('PUT')
				@case('PATCH')
					<button class="btn btn-sm btn-warning disableable" type="submit">بروزرسانی</button>
					@break
				@case('GET')
					<button class="btn btn-sm btn-info disableable" type="submit">جستجو و فیلتر</button>
					@break
				@case('POST')
					<button class="btn btn-sm btn-primary disableable" type="submit">ثبت و دخیره</button>
					@break
			@endswitch
			<button class="btn btn-sm btn-danger" type="button" onclick="window.location.href = window.location.origin + window.location.pathname">ریست فرم</button>
		</x-row>
	@endif

</form>