@props([
	'model',
	'route',
	'title' => null,
	'hasIcon' => true,
	'disabled' => false
])

<button
	{{ $attributes->merge(['class' => 'btn btn-sm btn-icon btn-danger text-white']) }}
  onclick="confirmDelete('delete-{{ $model->id }}')"
  data-toggle="tooltip"
  data-original-title="حذف"
  @disabled($disabled)>
	@if ($title)
		<span>{{ $title }}</span>
	@endif
	@if ($hasIcon)
		<i class="fa fa-trash-o {{ $title ? 'mr-1' : '' }}"></i>
	@endif
</button>

<form
  action="{{ route($route, $model) }}"
  method="POST"
  id="delete-{{ $model->id }}"
  class="d-none">
  @csrf
  @method('DELETE')
</form>