@props([
	'isSmall' => false,
	'hasIcon' => false,
])

<button 
	{{ $attributes->merge([
		'class' => collect([
			'btn', 
			'btn-block',
			'btn-primary', 
			$isSmall ? 'btn-sm' : null
		])->filter()->join(' '), 
	]) }}
>
	<span>جستجو</span>
	@if ($hasIcon)
		<i class="fa fa-serach"></i>
	@endif
</button>
