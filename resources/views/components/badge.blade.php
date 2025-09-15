@props([
  'isLight' => false,
  'type',
  'text'
])

@php
  if ($isLight) {
    $type .= '-light';
  }
@endphp

<span 
  {{ $attributes->merge([
    'class' => "badge badge-$type fs-11"
  ]) }}
>
  {{ $text }}
</span>