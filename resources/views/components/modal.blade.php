@props([
  'title',
  'size' => 'md',
])

<div
  {{ $attributes->merge([
    'class' => 'modal fade',
    'style' => 'display: none',
    'aria-hidden' => 'true'
  ]) }}
>
  <div class="modal-dialog modal-{{ $size }}" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p class="modal-title font-weight-bold">{{ $title }}</p>
        <button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        {{ $slot }}
      </div>
    </div>
  </div>
</div>