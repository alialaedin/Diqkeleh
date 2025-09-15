@props([
	'route',
	'title' => 'بازگشت',
	'btnColorClass' => 'secondary',
	'isSmall' => true,
])

<a 
	href="{{ $route }}"
	{{ $attributes->class([
		'btn', 
		'btn-' . $btnColorClass, 
		'btn-sm' => $isSmall
	]) }}
> 
	{{ $title }}
</a>
