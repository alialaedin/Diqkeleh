@props([
	'route',
	'title',
	'isSmall' => true,
	'isBlank' => false,
	'hasIcon' => true
])

<a 
	href="{{ $route }}"
	@if ($isBlank) target="_blank" @endif
	{{ $attributes->class([
		'btn', 
		'btn-indigo', 
		'btn-sm' => $isSmall
	]) }}
> 
	{{ $title }}
	@if ($hasIcon)
		<i class="fa fa-plus mr-1"></i>
	@endif
</a>
