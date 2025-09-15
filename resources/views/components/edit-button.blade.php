@props([
	'target' => null,
	'title' => null,
	'route' => null,
	'model' => null,
	'hasIcon' => true
])

@if ($target)
  <button
    {{ $attributes->merge(['class' => 'btn btn-sm btn-icon btn-warning text-white']) }}
    data-target="{{ $target }}"
    data-toggle="modal">
    @if ($title)
			<span>{{ $title }}</span>
		@endif
		@if ($hasIcon)
			<i class="fa fa-pencil {{ $title ? 'mr-1' : '' }}"></i>
		@endif
  </button>
@else
  <a
		{{ $attributes->merge([
			'class' => 'btn btn-sm btn-icon btn-warning text-white',
			'href' => route($route, $model),
			'data-toggle' => 'tooltip',
			'data-original-title' => 'ویرایش',
		]) }}
	>
    @if ($title)
			<span>{{ $title }}</span>
		@endif
		@if ($hasIcon)
			<i class="fa fa-pencil {{ $title ? 'mr-1' : '' }}"></i>
		@endif
  </a>
@endif