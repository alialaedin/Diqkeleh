@props([
  'isRequired' => false,
  'text'
])

<label {{ $attributes->merge(['class' => 'fs-12']) }}>
  {{ $text }}
  @if ($isRequired)
    <span class="text-danger">&starf;</span>
  @endif
</label>
