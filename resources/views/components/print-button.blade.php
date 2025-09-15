@props([
	'title' => null,
  'model',
  'route'
])

<a 
	href="{{ route($route, $model) }}" 
	target="_blank" 
  data-toggle="tooltip"
  style="margin-inline: 1px"
  data-original-title="پرینت"
	class="btn btn-purple btn-sm btn-icon">
	@if ($title)
    <span>{{ $title }}</span>
  @endif
  <i class="si si-printer {{ $title ? 'mr-1' : '' }}"></i>
</a>