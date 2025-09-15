@props([
	'routeName',
	'isSmall' => false,
	'hasIcon' => false,
])

<a 
	{{ $attributes->merge([
		'href' => route($routeName),
    'class' => collect([
			'btn', 
			'btn-block',
			'btn-danger', 
			$isSmall ? 'btn-sm' : null
    ])->filter()->join(' '), 
	]) }}
>
<span>حذف فیلتر ها</span>
@if ($hasIcon)
	<i class="fa fa-close"></i>
@endif
</a>