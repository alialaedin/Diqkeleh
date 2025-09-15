@props([
	'icon',
	'color',
	'title',
	'value'
])

<x-card>
  <i class="fa fa-{{ $icon }} card-custom-icon icon-dropshadow-{{ $color }} text-{{ $color }} fs-60"></i>
  <p class=" mb-1">{{ $title }}</p>
  <h3 class="mb-1 font-weight-bold">{{ $value }}</h3>
</x-card>