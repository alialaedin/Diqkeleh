@props([
	'icon' => null,
	'color',
	'title',
	'value'
])

<x-card>
	<x-row>
		<x-col :xl="$icon ? 9 : 12">
			<div class="mt-0 text-right">
				<span class="fs-14 font-weight-bold">{{ $title }}</span>
				<p class="mb-0 mt-1 text-{{ $color }} fs-20 font-weight-bold">{{ $value }}</p>
			</div>
		</x-col>
	@if ($icon)
		<x-col xl="3">
			<div class="icon1 bg-{{ $color }}-transparent my-auto float-left">
				<i class="fa fa-{{ $icon }}"></i>
			</div>
		</x-col>
	@endif
	</x-row>
</x-card>